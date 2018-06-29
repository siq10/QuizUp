<template>
  <div>
    <video id="preview" v-if="inBrowser && !noCameras"></video>
    <p v-if="inBrowser && noCameras" class="error">Nu a fost detectata nici o camera pe acest dispozitiv</p>
  </div>
</template>

<script>
  import Vue from 'vue'

  const Instascan = require('instascan')

  export default {
    name: 'newOrder',
    data () {
      return {
        inBrowser: true,
        noCameras: false,
      }
    },
    methods: {
      /**
       * Start the order for the table we have from the QR code
       */
      startOrder (table_id) {
        return Vue.http.post('https://horeca.uatdev.net/api/startOrder', {'table_id': table_id}).then((success) => {
          window.localStorage.setItem("activeOrder",1)
          this.$router.push({path: 'menu'})
        })
      },

      /**
       * Inside the application we will use a different method for scanning
       */
      initializeCordovaScanner () {
        this.inBrowser = false
        let self = this
        // prepare the scanner
        QRScanner.prepare(function (err) {
          // there was an error preparing the cordova scanner
          if (err) {
            console.error('Prepare failed')
            console.error(err._message)
          } else {
            // showing the scanner in the window
            QRScanner.show(function (status) {
              // begin scanning
              QRScanner.scan(function (err, text) {
                if (err) {
                  console.error('An error occurred', err._message)
                } else {
                  // get and parse the content
                  let content = JSON.parse(text)
                  console.log(content)
                  // set the table ID
                  self.table_id = content.table_id
                  // stop the camera and start the order
                  self.startOrder(content.table_id).then(() => {
                    QRScanner.destroy()
                  })
                }
              })
            })
          }
        })
      },

      /**
       * Inside the browser we will use a different method for scanning
       */
      initializeBrowserScanner () {
        this.inBrowser = true
        let scanner = new Instascan.Scanner({video: document.getElementById('preview')})
        let self = this
        // attach the event listener
        scanner.addListener('scan', function (content) {
          // get and parse the content
          content = JSON.parse(content)
          // set the table ID
          self.table_id = content.table_id
          // stop the camera and start the order
          self.startOrder(content.table_id).then(() => {
            scanner.stop()
          })
        })
        // get the cameras of the device
        Instascan.Camera.getCameras().then(function (cameras) {
          if (cameras.length > 0) {
            scanner.start(cameras[0])
          }
          else {
            console.error('No cameras found.')
            self.noCameras = true
          }
        }).catch(function (e) {
          console.error(e)
        })
      }
    },
    mounted: function () {
      // determine the environment and initialize the proper camera
	      if (typeof QRScanner !== 'undefined') {
	        	this.initializeCordovaScanner()
	      } else {
	        	this.initializeBrowserScanner()
	      }
	}
  }
</script>

<style lang="scss" rel="stylesheet/scss" scoped>
  @import '../../assets/scss/variables';
  
  .error{
    text-align: center;
    background: $accent;
    color: $white;
    margin: 2rem 0;
    padding: 1rem;
  }

</style>
