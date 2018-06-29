<template>
  <div id="app">
      <div class="allbtn">
        <button class="btn" @click="gotoQuick">Quick Match</button>
        <button class="btn" @click="gotoChallenge">Challenge a PLayer</button>
      </div>
    </div>
</template>

<script>
  import Vue from 'vue'

  export default {
    name: 'versusMode',
    data() {
      return {
        /*guest: {name: '', password: ''},
        response: {},
        error: false*/
        message:""
      }
    },
    methods: {
      updateMessages(m)
      {
        this.message = m
      },
      gotoQuick()
      {
        this.$router.push({path: '/quick'})

      },
      gotoChallenge()
      {
        this.$router.push({path: '/challenge'})

      }
    },
    mounted() {
//      this.conn.send("noooo");



    }
  }
</script>

<style >

</style>
