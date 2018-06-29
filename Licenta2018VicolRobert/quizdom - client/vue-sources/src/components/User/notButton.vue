<template>
  <div id="app">
    <button id="notificationbutton" @click.prevent="showtab" v-bind:class="{ triggered: isTriggered }" v-if="logged"><span v-bind:class="{'sas':notificationsCount}">{{notificationsCount==0?"-":notificationsCount}}</span></button>
  </div>
</template>

<script>
  import Vue from 'vue'

  export default {
    name: 'notButton',
    data () {
      return {
        error: false,
      }
    },
    computed:{
      logged:function(){
        return this.$store.state.isAuthenticated
      },
      notificationsCount:function() {
        return this.$store.state.notificationsCount
      },
      isTriggered(){
        return this.$store.state.notificationsButton
      }
    },
    methods: {
      showtab () {
//        var x=this.$refs.here.style
//      console.log(x)
//        x.height = "400px"

        this.$store.dispatch("triggerNotifications")
//        this.notificationsCount=0
//        console.log(this.$store.state.notificationTabTrigger)
      },

    },
    mounted() {
//      var x=this.$refs.here.style.height = "200px"
      Vue.http.get(this.$apiurl + 'logged').then((success) => {
        this.error = false
        this.$store.dispatch("storeAuth", success.body.response)
      }, (error) => {
          this.error = true
        })
    }
  }
</script>

<style lang="scss" rel="stylesheet/scss" scoped>
     #notificationbutton{
       position:relative;
       z-index: 100;
      min-width: 100px;
      height:50px;
      border:solid white 2px;
       background: black;
       color:white;
       /*font-size:40px;*/
       /*text-align:right;*/
       margin:5px;
       border-radius:5px;
       /*text-indent: 50px;*/
       cursor:pointer;
       outline: none;
    }
     .sas{
       display: inline-block;

       padding: 7px;
       border:1px solid white;
       border-radius:50%;
       background: white;
       color:black;
       margin:0;
       text-align: center;
     }
     #notificationbutton span{
       font-size: 20px;
       margin-left:50px;
       font-weight:700;
     }

    #notificationbutton::before{
      outline: none;
      content:"";
      position:absolute;
      left:0;
      top:3px;
      background:white;
      width:50px;
      height:40px;
      clip-path: polygon(30% 0%,70% 0%,70% 60%, 100% 40%, 50% 100%, 0% 40%,30% 60%);
      transition: transform ease 0.75s;
    }
    .triggered::before{
      transform:rotate(180deg);
    }

</style>
