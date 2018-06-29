<template>
  <div id="app">
    <div class="allbtn">
      <button  v-for="r in response" class="btn" @click.prevent="startGame(r.id)">{{r.name}}</button>
    </div>
    <h2 v-if="empty">You have no unseen acquired questions in any category!</h2>
  </div>
</template>

<script>
  import Vue from 'vue'

  export default {
    name: 'singleMode',
    data () {
      return {
        /*guest: {name: '', password: ''},
        error: false*/
        response: {},
        empty:0
      }
    },
    methods: {
      startGame(id) {
          this.$store.dispatch('storeCategory',id)
          window.localStorage.setItem('catid',id)

          this.$router.push({path:"/singlegame"})
      }

    },
    mounted() {
        Vue.http.get(this.$apiurl + 'categories').then((response) => {
          if(response.body.ok==1)
          {
            this.response = response.body.categories
          }
          else
          {
            this.empty = 1
          }
        })
      }

  }
</script>

<style scoped>
  h2{
    text-align: center;
  }
</style>
