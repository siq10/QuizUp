<template>
  <div id="app">
    <div ref="here" class="notificationtab" v-bind:class="{ notificationshow: tabtrigger }">
      <transition-group class="notificationcontainer" mode="in-out" name="staggered-fade" tag="div" v-bind:css="false" v-on:before-enter="beforeEnter" v-on:enter="enter" v-on:leave="leave" v-on:after-leave="leaveto">
        <div :id="x=item" class="notifications" v-if="tabtrigger" v-for="(item,index) in notifications.guids" v-bind:key="item" v-bind:data-index="index">
          <p>{{ notifications.names[index] }} has challenged you!</p>
          <button class="whitebtn" @click.prevent="accept(index,x)">Accept</button>
          <button class="whitebtn" @click.prevent="decline(index,x)">Decline</button>
          <div class="time">
            {{(Math.floor(Math.abs((new Date().getTime() - new Date(notifications.times[index]["date"]).getTime())/(24*60*60*1000)))) ? (Math.floor(Math.abs((new Date().getTime() - new Date(notifications.times[index]["date"]).getTime())/(24*60*60*1000)))) +"d ":""}}
            {{Math.floor(Math.abs((new Date().getTime()-  new Date(notifications.times[index]["date"]).getTime())/(36e5)))%24?  Math.floor(Math.abs((new Date().getTime()-  new Date(notifications.times[index]["date"]).getTime())/(36e5)))%24+"h":"&nbsp&nbsp"}} {{Math.round(((new Date() -  new Date(notifications.times[index]["date"])  % 86400000) % 3600000) / 60000) }}m</div>
        </div>
      </transition-group>
    </div>
  </div>
</template>

<script>
  import Vue from 'vue'
  import Velocity from 'velocity-animate'

  export default {
    name: 'notTab',
    data () {
      return {
        nodelay:0,
        dat :new Date()
      }
    },

    computed: {
      tabtrigger: function() {
        return this.$store.state.notificationTabTrigger
      },
      notifications: function() {
        return this.$store.state.notifications
      }
    },
    methods: {
      addtransition(index){
        var dv = document.getElementById(index)
        dv.style.opacity=0;
        dv.style.transform="translateX(-144px)"
      },
      accept(index,x)
      {
        Vue.http.post(this.$apiurl + 'game', {'accepted':1,'index':index}).then((success) => {
          if(success.status === 405)
          {
            alert("Opponent is busy right now!")
          }
          else
          {

          Vue.http.delete(this.$apiurl + 'notifications/delete', {body: {'index':index}}).then((ok) => {
            this.$store.dispatch("updateNotifications",index)
            this.$store.dispatch("triggerNotifications")

            console.log(ok.body.ok)
            this.nodelay = 1
            this.$store.dispatch("updateMatchData",success.body)
            console.log("----------------------")
            console.log(this.$store.state.matchData)
            console.log("----------------------")

//            this.$store.dispatch("showGameOn",1)
          })
          }

        })
      },
      decline(index,x)
      {
        Vue.http.delete(this.$apiurl + 'notifications/delete',{body: {'index':index}}).then((success) => {
          console.log(success.body.ok)
          this.nodelay = 1
          this.$store.dispatch("updateNotifications",index)
      })

      },
      addEvent(el, done)
      {
        el.addEventListener("animationend", function () {
          el.style=""
          done()
        })
      },
      beforeEnter: function (el) {
        el.style.opacity = 0
        el.style.transform="translateX(-144px)"

      },
      enter: function (el, done) {
        var delay = (el.dataset.index) * (500/this.notifications.names.length)+(500/this.notifications.names.length)
        this.addEvent(el, done)
        setTimeout(function () {
          el.style.opacity=1
          el.style.transform="translateX(0)"
        }, delay)
      },
      leave: function (el, done) {
//        console.log(this.notifications.names.length - el.dataset.index)
        if(this.nodelay)
        {
          var delay=0
          this.nodelay=0
        }
        else
        var delay = (this.notifications.names.length - el.dataset.index) * (500/this.notifications.names.length)
        this.addEvent(el, done)
        setTimeout(function () {
          el.style.transform="translateX(-144px) scaleY(0.0001)"
          el.style.opacity=0
//          el.style.position="absolute";
          el.style.maxHeight = 0;
          el.style.padding=0;

        }, delay)
      },
      leaveto: function (el,done){
//        this.addEvent(el, done)
      }
      },
    mounted() {
      console.log(this.notifications)
      Echo.channel('notify.'+ this.$store.state.isAuthenticated)
          .listen('NotifyUser',(payload) => {
            console.log(payload)
            console.log("DADADAAADADADAIDJOIJDAIODJWIODJAWOD")
            this.$store.dispatch("addNotification",payload)
          })

      Vue.http.get(this.$apiurl + 'notifications').then((success) => {
          console.log(success.body.storage)
          var storage = success.body.storage
          if(storage!==0)
          {
            this.$store.dispatch("storeNotifications",storage)
            this.$store.dispatch("storeNotificationCount",storage.names.length)
          }
        })
      }
  }
</script>

<style lang="scss" rel="stylesheet/scss" scoped>
  .notificationtab{
    transition:height 0.8s ease-in-out	;
    height:0;
    position:relative;
    margin:0 10%;
    z-index:10;
    overflow:auto;

  }
  /* width */
  ::-webkit-scrollbar {
    width: 5px;
  }

  /* Track */
  ::-webkit-scrollbar-track {
    background: white;
    border-radius:30px;
    border:solid
  }

  /* Handle */
  ::-webkit-scrollbar-thumb {
    background: darkgray;
    -webkit-border-radius: 10px;
    -moz-border-radius: 10px;
  }

  /* Handle on hover */
  ::-webkit-scrollbar-thumb:hover {
    background : darken(darkgray,10%);
  }

  .notificationshow
  {
    height:80vh;
  }
  .notifications{
    padding:2rem;
    margin:0;
    transition:opacity 0.4s ease,
              transform 0.3s ease,
              max-height 0.3s ease,
              padding 0.3s ease;
    overflow: hidden;
    max-height:250px;
    /*text-align:initial;*/
  }
  .notificationcontainer
  {
    padding:0;
    width:70%;
    margin:0 auto;

  }
  .whitebtn{
    color:black!important;
    background: white!important;
    display:inline-block;
    text-align:initial;
    margin:0;
  }
  .time{
    margin-left:2rem;
    display: inline-block;
    color:#a8a8a8;
    font-size:20px;
  }

  @media only screen and (max-width:1053px) {
    .notifications
    {
      margin:0;
      padding:0;
      margin-bottom:20px;
      font-size:20px;
    }
    .notificationcontainer{
      margin-top:2rem;
      width:90%;
      text-align:center;
    }
    .time{
      margin-left:0;
    }
  }
</style>
