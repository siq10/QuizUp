<template>
  <div id="app">
    <sweet-modal modal-theme="dark" overlay-theme="dark" ref="success" icon="success">The challenge was sent!</sweet-modal>
    <sweet-modal modal-theme="dark" overlay-theme="dark" ref="info" icon="info" title="Game On!">
      <h3>Do you want to challenge {{opponent.name}}?</h3>
      <button @click="yes()">Yes</button>
      <button @click="nope()">No</button>

    </sweet-modal>
    <sweet-modal modal-theme="dark" overlay-theme="dark" ref="error" icon="error">Could not send challenge!</sweet-modal>

    <div class="card">
      <div class="form">
        <label for="name">Search an opponent</label>
        <input id="name" v-model="query" autocomplete="off">
      </div>
      <transition-group
        name="slide"
        tag="ul"
        class="playerlist"
        ref="players"
      >
        <li
          class="flxcontainer"
          v-for="(item, index) in computedList"
          v-bind:key="item.name"
          v-bind:data-index="index"
        >


          <div class="active">
            <h2 class="month">{{item.lastseen.month}}</h2>
            <h2 class="day">{{item.lastseen.day}}</h2>
          </div>
          <div class="details">
            <h3>{{ item.name }}</h3>
            <p>20-2</p>
          </div>
          <div class="actions">
            <button @click="openmodal(item)" class="sendChallenge">Challenge!</button>
            <button class="call">Call!</button>
          </div>
        </li>
      </transition-group>
      <h3 class="playernumber">{{computedLength}} matches found!</h3>
    </div>
  </div>
</template>

<script>
  import Vue from 'vue'
//  import smoothHeight from 'vue-smooth-height';

  export default {
    name: 'challenge',
//    mixins:[smoothHeight],
    data() {
      return {
        /*guest: {name: '', password: ''},
        response: {},
        error: false*/
        query: '',
        list: [
        ],
        message:"",
        opponent:""

      }
    },
    mounted() {
//      this.conn.send("noooo");
      this.listen()
      var self =this
      Vue.http.get(this.$apiurl + 'players').then((response) => {
        self.list = response.body.users
        console.log(self.list)
//        self.$registerSmoothElement({
//          el: self.$refs.players,
//        })
      })


    },
    computed: {
      computedList: function () {
        var vm = this
        return this.list.filter(function (item) {
          return item.name.toLowerCase().indexOf(vm.query.toLowerCase()) !== -1
        })
      },
      computedLength: function() {
        return this.computedList.length;
      },

    },
    methods: {
      listen()
      {
        console.log('game.'+ JSON.parse(window.localStorage.getItem('USER')).id)
        Echo.channel('game.'+ JSON.parse(window.localStorage.getItem('USER')).id)
          .listen('StartGame',(payload) => {
            console.log(payload)
            console.log("DADADAAADADADAIDJOIJDAIODJWIODJAWOD")
          })

      },
      openmodal(item)
      {
        this.opponent = item
        console.log(item)
        this.$refs.info.open()
      },
      yes()
      {
//        var self = this
        Vue.http.post(this.$apiurl +"challenge/send",{"id":this.opponent.id}).then((response) => {
            if(response.data.status==1)
            {
              console.log(response)
              this.$refs.info.close()
              this.$refs.success.open()
            }
            else
            {
              console.log(response)

              this.$refs.info.close()
              this.$refs.error.open()
            }
        })
      },
      nope()
      {
        this.$refs.info.close()
      }
    }
  }
</script>

<style scoped>
  @import "https://cdn.jsdelivr.net/npm/animate.css@3.5.1";
  h1{
    text-align: center;
  }

  .before-enter{
    opacity:0;
    height:0;
  }
  .enter{

  }
  .playerlist{
    width:80%;
    background-color:#2E2727;
    color:white;
    border-radius:15px;
    opacity:0.6;
    margin: 0 auto;
    padding:0;
    list-style-type:none;
    overflow:hidden;
    max-height:5000px;
    transition: all 1s ease;
  }
  .form {
    width:90%;
    margin: 0 auto;
    padding-bottom:2rem;
  }
  .form input{
    margin:0 auto;
    /*display: block;*/
    background-color:transparent;
    border:none;
    border-bottom:4px solid black;
    border-radius:2px;
    width:50px;
    font-size:20px;
    line-height:30px;
    text-align: center;
    transition: all 0.5s
  }
  .form input:focus{
    width:200px;
  }

  .form label{
    display: block;
    font-size:25px;
    font-weight: 700;


  }
  textarea:focus, input:focus{
    outline: none;
    box-shadow: none;

  }
  .flxcontainer{
    display: flex;
    justify-content:space-between;
    align-items: center;
    border-bottom:2px solid black;
    border-radius:20px;
    height:100px;

  }
  .active{
    flex-basis: 30%;
    color:#A4A2A2
  }
  .active h2{
    padding:0;
    margin:0;
    /* BAAAD CHOICE MA MAN */
    margin-left:-5rem;
  }
  .month{
    font-size:20px;
    font-weight:500;
  }

  .day{
    font-size:28px;
    line-height:40px;
    font-weight: 800;
  }
  .details h3, .details p{
    padding:0;
    margin:0;
    text-align:center;
  }
  .details p{
   color:#A4A2A2;
  }
  .details{
    flex-basis:40%;
    flex-grow:1;
  }
  .actions button{
    margin-right:1rem;
    background-color:black;
    color:#A4A2A2;
  }
  .actions{
    flex-basis:30%;
  }
  .playernumber{
    margin-top:2rem;
  }


  .slide-enter-active {
    animation: appear .5s;
  }
  .slide-leave-active {
    animation: appear .5s reverse;
  }
  @keyframes appear {
    0% {
      transform: scaleY(0);
      opacity:0;
    }
    /*50% {*/
      /*transform: scaleY();*/
    /*}*/
    100% {
      transform: scaleY(1);
      opacity:1;
    }
  }
</style>
