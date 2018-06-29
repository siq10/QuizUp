<template>
  <div id="app">
    <div class="search" v-bind:class="react ? 'bigger':'shrink'">
        <transition-group name="profile" tag="div" class="profilescontainer">
          <div class="user" v-for="user in list" :key="user.id">
            <img :class="user.imgshape" :width="user.imgwidth" :height="user.imgheight" :src="$publicurl + user.imgpath">
            <h3>{{user.name}}</h3>
            <p>{{user.wins + " - " + user.losses}}</p>
          </div>

        </transition-group>
        <div class="leavebtn" @click="leaveRoom">Leave Game Room</div>
    </div>
  </div>
</template>

<script>
  import Vue from 'vue'

  export default {
    name: 'quickgame',
    data () {
      return {
        Started:0,
        list:[]
      }
    },
    computed:{
      react(){
        return this.Started
      }
    },
    methods: {
      leaveRoom() {
        Vue.http.delete(this.$apiurl + 'room',{"headers":{"X-Socket-Id": Echo.socketId()}}).then((response) => {
          console.log(response)
          if (response.ok){
            this.remove(this.list,response.body.id)
          }
//            this.list.shift()
        })
      },
      remove(array, element) {
        const index = array.map(function(e) { return e.id; }).indexOf(element);
//        console.log(index)
        if (index !== -1) {
          array.splice(index, 1);
        }
      }

    },
    mounted() {
      var self = this
      setTimeout(() => {
        this.Started = 1;
        Vue.http.get(this.$apiurl + 'queued').then((response) => {
          var copy = Object.values(response.body.cachedList)
//          for (let i =0;i<copy.length;i++)
//          {
//            var img = new Image();
//            img.onload = function() {
//              if(this.width >150 && this.height > 150)
//              {
//                if(this.width > this.height)
//                {
//                  copy[i].width = this.width / (this.width / 125)
//                  copy[i].height = this.height / (this.width / 125)
//                  copy[i].class = "square"
//                }
//                else
//                if(this.width < this.height)
//                {
//                  copy[i].height = this.height / (this.height / 125)
//                  copy[i].width = this.width / (this.height / 125)
//                  copy[i].class = "square"
//                }
//                else
//                {
//                  copy[i].width = this.width / (this.width / 150)
//                  copy[i].height = this.height / (this.width / 150)
//                  copy[i].class = "round"
//                }
//              }
//              else
//              {
//                copy[i].width = 100
//                copy[i].height = 100
//              }
//            }
//            img.src = this.$publicurl + copy[i].imgpath
//          }

//            Array.prototype.push.apply(this.list,response.body.cachedList)
          setTimeout(() => {
            this.list = copy
            Echo.channel('quickroom')
              .listen('QuickRoom',(payload) => {
//                console.log(payload)
                if (payload.status==1)
                {
                  self.list.push(payload.user)

//                  var img = new Image();
//                  img.onload =  function() {
//                    if(this.width >150 && this.height > 150)
//                    {
//                      if(this.width > this.height)
//                      {
//                        payload.user.width = this.width / (this.width / 125)
//                        payload.user.height = this.height / (this.width / 125)
//                        payload.user.class = "square"
//                      }
//                      else
//                      if(this.width < this.height)
//                      {
//                        payload.user.height = this.height / (this.height / 125)
//                        payload.user.width = this.width / (this.height / 125)
//                        payload.user.class = "square"
//                      }
//                      else
//                      {
//                        payload.user.width = this.width / (this.width / 150)
//                        payload.user.height = this.height / (this.width / 150)
//                        payload.user.class = "round"
//                      }
//                    }
//                    else
//                    {
//                      payload.user.width = 100
//                      payload.user.height = 100
//                    }
//                  }
//                  img.src = this.$publicurl + payload.user.imgpath

                }
                else{
                  console.log(payload.user)
                this.remove(this.list,payload.user.id)
                }
              })
          },500)
        })


      },200)

//      var x=this.$refs.here.style.height = "200px"


    }
  }
</script>

<style lang="scss" rel="stylesheet/scss" scoped>
  .profilescontainer
  {
    display: flex;
    flex-wrap: wrap;
    flex-direction: row;
    justify-content: flex-start;
    overflow-y : auto;
    max-height:calc(100vh - 240px);
    padding:5px;

  }
  .user
  {
    flex:0 1 auto;
    width: 150px;
    text-align: center;
    border-radius:10px;
    background:radial-gradient(circle, white,lightgray,gray);
    max-height: 210px;
    margin:5px;
    transition: all 0.5s ease;
  }
  .user h3 , .user p {
    color:black;
    margin: 0;
    padding:0;
  }

  .user img{
    margin-top:0.5rem;
    border:3px solid darkred;
  }

  .polygon {
    border-radius:10px;
  }
  .round{
    border-radius:50%;  /*pt pozele cu width = height*/
  }


  .search{
    height:calc(100vh - 200px);
    width:100%;
    position:relative;
    background:	rgba(96,96,96,0.4);
    border:1px solid white;
    border-radius:15px;
    transition:transform 0.5s ease-in-out;
    position: relative;
  }

  .leavebtn{
    text-align:center;
    width:250px;
    height:40px;
    color:black;
    background:radial-gradient(circle, ghostwhite,lightgray,black);
    position:absolute;
    bottom:1px;
    -webkit-clip-path: polygon(20% 0%, 80% 0%, 100% 100%, 0% 100%);
    clip-path: polygon(20% 0%, 80% 0%, 100% 100%, 0% 100%);
    text-shadow: 0 1px 1px darkgrey;
    box-shadow: inset 0 3px 5px darkgrey;
    left: calc(50% - 125px);
    transition:all 0.2s ease;
    cursor:pointer;
  }
  .leavebtn:hover{
    height:60px;
    transform:skewX(20deg);

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
    border-radius: 10px;
  }

  /* Handle on hover */
  ::-webkit-scrollbar-thumb:hover {
    background : darken(darkgray,10%);
  }


  .bigger{
    transform:scaleY(1);
  }
  .shrink{
    transform:scaleY(0);
  }
  @media only screen and (max-width: 700px) {
    .search{
      height:calc(100vh - 150px);
      width:105%;
      right:2.5%;
    }
  }
</style>
