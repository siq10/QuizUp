<template>
  <div id="create-user">
    <h1>Create a new User</h1>

    <form>
      <label for="name">Name:</label>
      <input type="text" v-model="newUser.name" id="name">
      <label for="password">Password: </label>
      <input type="password" v-model="newUser.password" id="password">
      <label for="email">Email:</label>
      <input type="email" v-model="newUser.email" id="email">
      <button @click.prevent="createUser">Create</button>
    </form>

    <div v-if="error" class="form-error">Can't create your user account</div>
  </div>
</template>

<script>
  import Vue from 'vue'

  export default {
    name: 'createUser',
    data () {
      return {
        newUser: {name: '', email: '', password: ''},
        response: null,
        error: false
      }
    },
    methods: {
      createUser () {
        Vue.http.post(this.$apiurl + 'register', this.newUser).then((success) => {
          this.response = success
          this.error = false
          this.$router.push({path: '/welcome'})
        }, (error) => {
          this.error = true
        })
      }
    }
  }
</script>

<style lang="scss" rel="stylesheet/scss" scoped>
  h1 {
    text-align: center;
    #create-user{
      color:black;
    }
  }
</style>
