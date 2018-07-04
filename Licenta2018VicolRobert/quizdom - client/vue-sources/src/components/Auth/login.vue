<template>
  <div id="user-login">
    <h1>Type in your username and password</h1>

    <form>
      <label class="loginform" for="name">Username:</label>
      <input type="text" v-model="guest.name" id="name">
      <label class="loginform" for="password">Password:</label>
      <input type="password" v-model="guest.password" id="password">
      <br>
      <button id="loginbutton"  :disabled="allowed === 0" @click.prevent="loginUser">Sign in</button>
    </form>

    <div class="form-error" v-if="error">Error logging in</div>
  </div>
</template>

<script>
  import Vue from 'vue'

  export default {
    name: 'loginUser',
    data () {
      return {
        guest: {name: '', password: '',device:""},
        response: {},
        error: false,
        playerId:"",
      }
    },
    computed:
      {
        allowed: function(){
          if (this.$store.state.device!="")
          {
            this.guest.device=this.$store.state.device
            return 1
          }
          else return 0
        }

      },
    methods: {
      loginUser () {
        Vue.http.post(this.$apiurl + 'authenticate',this.guest).then((success) => {
          this.response = success.body
          this.$store.dispatch('storeToken', success.body.token)
          this.$store.dispatch('storeUser', success.body.user)
          this.$store.dispatch("storeAuth",success.body.user.id)
          this.error = false
          this.$router.push({path: '/welcome'})
        }, (error) => {
          this.error = true
        })
      }
    },
    mounted(){
      console.log(this.$store.state.device)
      setTimeout(()=>{
        console.log(this.$store.state.device)
      },5000)
      }

    }
</script>

<style lang="scss" rel="stylesheet/scss" scoped>

  h1{
    text-align: center;
  }

  .loginform{
    color:black;
  }
</style>
