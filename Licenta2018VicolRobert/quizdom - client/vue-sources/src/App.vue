<template>
  <div id="app">
    <header class="hder">
      <div class="headerflex">
        <h1 id="appname"  @click.prevent="home">QuizUp</h1>
        <not-button></not-button>
      </div>
      <not-tab v-if="logged"></not-tab>
    </header>
    <actual-game v-if="logged"></actual-game>
    <div class="body-wrapper">
      <transition name="fade">
        <router-view></router-view>
      </transition>
    </div>
  </div>
</template>

<script>
  import notButton from "./components/User/notButton.vue";
  import notTab from "./components/User/notTab.vue";
  import ActualGame from "./components/User/actualGame.vue";
  export default {
    components: {
      ActualGame,
      notButton,
      notTab,
    },
    name: 'app',
  methods:
    {
      home(){
          this.$router.push({path: '/welcome'})
      }
    },
    computed:{
      logged:function(){
        return this.$store.state.isAuthenticated
      }
    },
  mounted()
  {
    var self = this
    var promise1 = new Promise(function(resolve, reject) {
      let OneSignalScript = document.createElement('script')
      OneSignalScript.async = true
      OneSignalScript.onload = () => {
        resolve('Success!');
      }
      OneSignalScript.setAttribute('src', 'https://cdn.onesignal.com/sdks/OneSignalSDK.js')
      document.head.appendChild(OneSignalScript)


    });
    promise1.then(function(value){
      self.$store.dispatch('storeOneSignal',window.OneSignal || [])

      self.$store.state.OneSignal.push(function() {
        self.$store.state.OneSignal.init({
          appId: "7ceb9b7f-5aa3-448e-8941-01212bbf1a32",
        });
      });
      self.$store.state.OneSignal.getIdsAvailable().then(function(response) {
      })

      self.$store.state.OneSignal.isPushNotificationsEnabled(function(isEnabled) {
        if (isEnabled) {
          // user has subscribed
          self.$store.state.OneSignal.getUserId( function(userId) {
            self.$store.dispatch("storeDevice",userId)
//            console.log(self.$store.state.device)
            // Make a POST call to your server with the user ID
          });
        }
      });

  })
  }
}
</script>

<style lang="scss" rel="stylesheet/scss">
  @import 'assets/scss/app';

  .headerflex{
    display: flex;
    justify-content:center;
    padding:5px;
  }
  .fade-enter-active, .fade-leave-active {
    transition: opacity .5s;
    position: absolute;
    width: 100%;
  }
  .fade-enter, .fade-leave-to{
    opacity: 0
  }
  .hder{
    position:absolute;
    z-index:1;
  }
  #appname{
    cursor:pointer;

  }
</style>
