<template>
  <div id="app">
    <transition name="faderelative">
    <div class="gameon"  v-if="gameon">
      <div class="gcontainer">
        <transition name="faderelative" mode="out-in">
          <div v-if="status=='popup'" class="thisuser" :class="{'rotate1':trigger1&&!canceled1&&!accepted1 , 'rotateLeft':canceled1 , 'rotateRight':accepted1}">
            <img :class="user.imgshape" :width="h>700 ? 3*user.imgwidth : user.imgwidth" :height="h>700? 3*user.imgheight : user.imgheight" :src="$publicurl + user.imgpath">
            <br>
            <p>{{user.name}}</p>
            <br>
            <button class="ready" @click="readyGame">Ready</button>
            <a @click.prevent="cancelGame" class="cancel" >Cancel the game</a>
            <div class="leftsquare">
              <p class="gamestatus" v-if="canceled1">Canceled</p>
            </div>
            <div class="bottomsquare">
            </div>
            <div class="topsquare">

            </div>
            <div class="rightsquare">
              <p class="gamestatus" v-if="accepted1">Ready</p>
            </div>
            <div class="backsquare">

            </div>
          </div>
        </transition>

        <transition name="faderelative" mode="out-in">
          <div v-if="status=='popup'" class="thatuser" :class="{'rotate3':trigger2&&!canceled2&&!accepted2  , 'rotateLeft':classobj, 'rotateRight':accepted2}">
            <img :class="opponent.imgshape" :width="h>700? 3*opponent.imgwidth : opponent.imgwidth" :height="h>700 ? 3*opponent.imgheight : opponent.imgheight" :src="$publicurl + opponent.imgpath">
            <br>
            <p>{{opponent.name}}</p>
            <br>
            <button class="ready" disabled>Ready</button>
            <a disabled class="cancel">Cancel the game</a>
            <div class="leftsquare">
              <p class="gamestatus" v-if="canceled2">Canceled</p>
            </div>
            <div class="bottomsquare">
            </div>
            <div class="topsquare">

            </div>
            <div class="rightsquare">
              <p class="gamestatus" v-if="accepted2">Ready</p>
            </div>
            <div class="backsquare">

            </div>
          </div>
        </transition>
      </div>


      <div class="gamewindow" v-if="status=='inProgress'">
        <div class="p1">
          <img class="brd1" :class="[w>700 ? user.imgclass :'round',{ 'shinegreen1':correct1==1},{ 'shinered1':correct1==-1}]" :width="w>700 ? 1.5*user.imgwidth : 60" :height="w>700? 1.5*user.imgheight : 60" :src="$publicurl + user.imgpath">
          <div class="infouser">
          <p class="wrp">{{user.name}}</p>
          <p :style="{color:effect1}">{{score1}}</p>

          <timer ref="timer1"  @timeleft="getTime(1,$event)" :time="timer1" :interval="100" :stop="stop1">
          </timer>
          </div>
        </div>
        <div class="titleq">
          <p>Question</p>
          <p>{{qcount}}</p>
        </div>
        <div class="p2">
          <img class="brd2" :class="[w>700 ? opponent.imgclass :'round', { 'shinegreen2':correct2==1},{ 'shinered2':correct2==-1}]" :width="w>700 ? 1.5*opponent.imgwidth : 60" :height="w>700? 1.5*opponent.imgheight : 60" :src="$publicurl + opponent.imgpath">
          <div class="infouser">
            <p class="wrp">{{opponent.name}}</p>
          <p :style="{color:effect2}">{{score2}}</p>
          <timer  ref="timer2" @timeleft="getTime(2,$event)" :time="timer2" :interval="100" :stop="stop2">
          </timer>
          </div>
        </div>
        <div class="problems" v-for="i in ids" v-if="i==qcount-1">
          <div class="questioncontainer">
            <h4 class="question1">{{problems[i].tq}}</h4>
          </div>
          <div class="answercontainer">
            <div class="barcontainer b1"></div>
            <animate-bar ref="bar1" uid="1" class="bar1" width="5" :height="wid1" :color="barcolor1" time="timeBetweenFrames"  animateDimension="height" endValue="0" ></animate-bar>
            <div class="barcontainer b2"></div>

            <div class="answers1" :class="{green:(correct1==1 && answered==order),red:(correct1==-1 && answered==order)}" v-for="(a,order) in answers[qcount-1]" @click.prevent="solve(order)">
              <h4 class="">{{ String.fromCharCode(ch.charCodeAt() + parseInt(order+1)) + ". " + a}}</h4>
            </div>

            <div  class="barcontainer b3"></div>
            <animate-bar ref="bar2" uid="2" class="bar2" width="5" :height="wid2" :color="barcolor2" time="timeBetweenFrames"  animateDimension="height" endValue="0" ></animate-bar>
            <div  class="barcontainer b4"></div>

          </div>

        </div>

      </div>
      <div class="result" v-if="status=='end' && winner==1">
        <p>Winner is :</p>
        <h3>{{user.name}}</h3>
        <p>with</p>
        <h3>{{score1}} points!</h3>       </div>
      <div class="result" v-if="status=='end' && winner==2">
        <p>Winner is :</p>
        <h3>{{opponent.name}}</h3>
        <p>with</p>
        <h3>{{score2}} points!</h3>
      </div>
    </div>
    </transition>

      <sweet-modal modal-theme="dark" overlay-theme="dark" ref="error" icon="error">{{opponent}} has refused your challenge!</sweet-modal>

  </div>
</template>

<script>
  import Vue from 'vue'
  import Timer from '@/components/Utils/timer'
  import AnimateBar from "../Utils/animatedbar.vue";


  export default {
    components: {
      AnimateBar,
      Timer,
    },
    name: 'actualGame',
    data () {
      return {
        opponent:"",
        winner:0,
          user:{},
          received:0,
          trigger1:0,
          trigger2:0,
          canceled1:0,
          canceled2:0,
          accepted1:0,
          accepted2:0,
          problems:[],
        score1:0,
        score2:0,
        status:"popup",
        qcount:1,
        response:[],
        ids:[],
        answers:[],
        ch:String.fromCharCode(64),
        stop1:false,
        stop2:false,
        seconds1:0,
        seconds2:0,
        setI1:0,
        setI2:0,
        wid1:300,
        wid2:300,
        correct1:0,
        correct2:0,
        answered:-1,
        timeBetweenFrames:20000/300,

        barcolor1:"darkorange",
        barcolor2:"darkorange",
        effect1:"white",
        effect2:"white",
        timer1:20,
        timer2:20,
        time:0
      }
    },
    computed:{
      classobj(){
        return this.canceled2===1
      },
      w(){ return Math.max(document.documentElement.clientWidth, window.innerWidth || 0)},
      h () { return  Math.max(document.documentElement.clientHeight, window.innerHeight || 0)},
      gameon:function(){
        return this.$store.state.gameon
      },
      gamedata() {
        return this.$store.state.matchData
      }
      },
    watch:{
      gamedata(newdata,olddata)
      {
//        alert(typeof this.$store.state.matchData.userid1)

        if(this.received==0) {
          this.received = 1
          this.user.id = this.$store.state.matchData.userid1
          this.user.name = this.$store.state.matchData.username1
          this.user.imgpath = this.$store.state.matchData.userimg1
          this.user.imgwidth = this.$store.state.matchData.userwidth1
          this.user.imgheight = this.$store.state.matchData.userheight1
          this.user.imgshape = this.$store.state.matchData.usershape1

          this.opponent={}
          this.opponent.id = this.$store.state.matchData.userid2
          this.opponent.name = this.$store.state.matchData.username2
          this.opponent.imgpath = this.$store.state.matchData.userimg2
          this.opponent.imgwidth = this.$store.state.matchData.userwidth2
          this.opponent.imgheight = this.$store.state.matchData.userheight2
          this.opponent.imgshape = this.$store.state.matchData.usershape2
          console.log(this.user)
          console.log(this.opponent)
          this.time = this.$store.state.matchData.timestarted
          this.$store.dispatch("showGameOn", 1)
        }
      },
      gameon()
      {
        if(this.gameon == 1)
        {
          setTimeout(()=>{
            this.trigger1 = 1
          },1000)
          setTimeout(()=>{
            this.trigger2 = 1
          },1500)
        }

      }
    },
    methods: {
      readyGame(){
        var self =this
        Vue.http.put(this.$apiurl + 'game/accept').then((response) => {
          if(response.status === 202)
          {
            this.accepted1 = 1
          }
          else
            if(response.status === 200)
            {
              this.accepted1 = 1
              console.log(response)
              setTimeout(()=>{
                this.status = "inProgress"
                this.setI1 = setInterval(function(){
                  self.wid1-=1
//              console.log(self.wid)
                  if(self.wid1 <=0)
                  {
                    clearInterval(self.setI1)
                  }
                },self.timeBetweenFrames)

                this.setI2 = setInterval(function(){
                  self.wid2-=1
//              console.log(self.wid)
                  if(self.wid2<=0)
                  {
                    clearInterval(self.setI2)
                  }
                },self.timeBetweenFrames)
              },1500)




              this.problems = response.body.problems
              this.qcount = 1
              console.log(this.problems)

              //console.log(this.response)

              for(var i = 0 ; i<this.problems.length;i++)
              {
                this.ids.push(i)
              }
//            this.shuffleArray(this.ids)
              for(var j=0;j<this.ids.length;j++)
              {
                this.answers[j]= []
                if(this.problems[this.ids[j]].t1)
                  this.answers[j].push(this.problems[this.ids[j]].t1)
                if(this.problems[this.ids[j]].t2)
                  this.answers[j].push(this.problems[this.ids[j]].t2)
                if(this.problems[this.ids[j]].t3)
                  this.answers[j].push(this.problems[this.ids[j]].t3)
                this.answers[j].push(this.problems[this.ids[j]].ta)
                this.shuffleArray(this.answers[j])
              }











            }
        })
      },
      cancelGame()
      {

        Vue.http.delete(this.$apiurl + 'game/cancel').then((response) => {
          if(response.status === 204)
          {
            this.canceled1 = 1
            setTimeout(() => {
              this.$store.dispatch("showGameOn", 0)
              setTimeout(() => {
                this.received=0
                this.trigger1=0
                this.trigger2=0
                this.accepted1=0
                this.accepted2=0
                this.canceled1=0
                this.canceled2=0
                this.received = 0
                this.status="";
              },1000)
            },2000)
          }
        })
      },
      shuffleArray(array) {
        for (var i = array.length - 1; i > 0; i--) {
          var j = Math.floor(Math.random() * (i + 1));
          var temp = array[i];
          array[i] = array[j];
          array[j] = temp;
        }
      },
      getTime(id,value)
      {
        if(id==1)
        {
          this.seconds1 = value.seconds
          if(this.correct1 == 1)
          {
            this.score1 += this.seconds1 * 5
          }
        }
        else
          if(id==2)
          {
            this.seconds2 = value.seconds
          }

      },
      showWinner(player)
      {
        this.received=1
        this.winner = player
        this.status = "end"
        this.$store.dispatch("updateMatchData",{})

        setTimeout(()=>{

          this.$store.dispatch("showGameOn",0)

          this.trigger1=0
            this.trigger2=0
            this.accepted1=0
            this.accepted2=0
            this.canceled1=0
            this.canceled2=0
            this.status="";
            this.received=0

          },6000
        )
      },
      solve(order)
      {
        if(this.answered != -1)
          return
        this.stop1 = true
        clearInterval(this.setI1)
        this.answered = order
        if(this.problems[this.ids[this.qcount-1]].ta == this.answers[this.qcount-1][order])
        {
          this.correct1 = 1
          this.barcolor1 = "green"
          this.effect1 = "green"
        }
        else
        {
          this.barcolor1 = "red"
          this.effect1 = "red"
          this.correct1 = -1
        }
        setTimeout(()=>
        {
          Vue.http.put(this.$apiurl + 'game/answer',{"right":this.correct1,"time":this.seconds1,"score":this.score1,"id":this.opponent.id,"problem":order}).then((payload) => {
            if(this.correct1 && this.correct2)
            {
              //Ambii au raspuns la intrebare => aducem variabilele la starea initiala
                Vue.http.put(this.$apiurl + 'game/next',{"id":this.opponent.id}).then((payload) => {
                  setTimeout(()=> {
                    if(payload.data.end)
                    {
                      this.showWinner(payload.data.end.winner)
                      return
                    }
                      this.qcount = payload.data.pbnumber.value + 1
                  this.correct1 = 0
                  this.barcolor1 = "darkorange"
                  this.effect1 = "white"
                  this.correct2 = 0
                  this.barcolor2 = "darkorange"
                  this.effect2 = "white"

                    this.answered = -1
                  this.wid1 = 300
                    this.wid2 = 300
//                  this.$refs.bar1.init()
//                    this.$refs.bar2.init()
                    this.timer1 = 20
                    this.timer2 = 20
                    this.stop1 = false
                    this.stop2 = false
                    var self = this
                    this.setI1 = setInterval(function(){
                      self.wid1-=1
//              console.log(self.wid)
                      if(self.wid1 <=0)
                      {
                        clearInterval(self.setI1)
                      }
                    },self.timeBetweenFrames)

                    this.setI2 = setInterval(function(){
                      self.wid2-=1
//              console.log(self.wid)
                      if(self.wid2<=0)
                      {
                        clearInterval(self.setI2)
                      }
                    },self.timeBetweenFrames)
                  },3000)
                })
            }
          })
          console.log(order)
          console.log(this.seconds1)
        },75)
//        console.log(this.problems[this.ids[this.qcount-1]].ta)
//        console.log(this.answers[this.qcount-1][order])


      }

    },
    mounted() {
      var self = this
//      var x=this.$refs.here.style.height = "200px"
      Vue.http.get(this.$apiurl + 'game/status').then((payload) => {
        if (this.received==0 && typeof payload.body.nogame==="undefined")
        {
          //on refresh, with game about to start
          this.received = 1
          this.user.id = payload.body.userid1
          this.user.name = payload.body.username1
          this.user.imgpath = payload.body.userimg1
          this.user.imgwidth = payload.body.userwidth1
          this.user.imgheight = payload.body.userheight1
          this.user.imgshape = payload.body.usershape1

          this.opponent={}
          this.opponent.id = payload.body.userid2
          this.opponent.name = payload.body.username2
          this.opponent.imgpath = payload.body.userimg2
          this.opponent.imgwidth = payload.body.userwidth2
          this.opponent.imgheight = payload.body.userheight2
          this.opponent.imgshape = payload.body.usershape2

          this.accepted1 = payload.body.accepted1
          this.accepted2 = payload.body.accepted2
          this.status = payload.body.status
          this.time = payload.body.timestarted

          if(payload.body.status === "inProgress")
          {
            this.score1 = payload.body.score1
            this.score2 = payload.body.score2

            this.problems = payload.body.problems
            this.status = "inProgress"
            this.qcount = payload.body.pbnumber + 1
            console.log(this.problems)

            //console.log(this.response)

            for(var i = 0 ; i<this.problems.length;i++)
            {
              this.ids.push(i)
            }
//            this.shuffleArray(this.ids)
            for(var j=0;j<this.ids.length;j++)
            {
              this.answers[j]= []
              if(this.problems[this.ids[j]].t1)
                this.answers[j].push(this.problems[this.ids[j]].t1)
              if(this.problems[this.ids[j]].t2)
                this.answers[j].push(this.problems[this.ids[j]].t2)
              if(this.problems[this.ids[j]].t3)
                this.answers[j].push(this.problems[this.ids[j]].t3)
              this.answers[j].push(this.problems[this.ids[j]].ta)
              this.shuffleArray(this.answers[j])
            }
            this.setI1 = setInterval(function(){
              self.wid1-=1
//              console.log(self.wid)
              if(self.wid1 <=0)
              {
                clearInterval(self.setI1)
              }
            },self.timeBetweenFrames)

            this.setI2 = setInterval(function(){
              self.wid2-=1
//              console.log(self.wid)
              if(self.wid2<=0)
              {
                clearInterval(self.setI2)
              }
            },self.timeBetweenFrames)

          }
          console.log(payload)
          console.log(this.ids)
          this.$store.dispatch("showGameOn",1)

        }
        })

        Echo.channel('Game.'+ this.$store.state.isAuthenticated)
          .listen('GameInProgress',(payload) => {
            console.log("pushed:")
            console.log(payload)

            if(payload.data.problems)
            {

              this.problems = payload.data.problems
              this.qcount = 1
              console.log(this.problems)

              //console.log(this.response)

              for(var i = 0 ; i<this.problems.length;i++)
              {
                this.ids.push(i)
              }
//            this.shuffleArray(this.ids)
              for(var j=0;j<this.ids.length;j++)
              {
                this.answers[j]= []
                if(this.problems[this.ids[j]].t1)
                  this.answers[j].push(this.problems[this.ids[j]].t1)
                if(this.problems[this.ids[j]].t2)
                  this.answers[j].push(this.problems[this.ids[j]].t2)
                if(this.problems[this.ids[j]].t3)
                  this.answers[j].push(this.problems[this.ids[j]].t3)
                this.answers[j].push(this.problems[this.ids[j]].ta)
                this.shuffleArray(this.answers[j])
              }

            }
            if(payload.data.right == 1)
            {
              self.score2 = payload.data.score
              clearInterval(self.setI2)
              self.stop2 = true
              self.effect2 = "green"
              self.correct2 = 1
              self.barcolor2 = "green"
              self.seconds2 = payload.data.time
              self.$refs.timer2.count = payload.data.time*1000
            }
            else
              if(payload.data.right==-1)
            {
              self.score2 = payload.data.score
              clearInterval(self.setI2)
              self.stop2 = true
              self.esconds2 = payload.data.time
              self.$refs.timer2.count = payload.data.time*1000
              self.correct2 = -1
              self.effect2 = 'red'
              self.barcolor2 = "red"
            }
            else
              if(payload.data.value)
              {
                setTimeout(() => {
                  self.qcount = payload.data.value + 1
                  self.correct1 = 0
                  self.barcolor1 = "darkorange"
                  self.effect1 = "white"
                  self.correct2 = 0
                  self.barcolor2 = "darkorange"
                  self.effect2 = "white"
                  self.answered = -1
                  self.wid1 = 300
                  self.wid2 = 300


                  this.answered = -1
                  this.wid1 = 300
                  this.wid2 = 300
//                  console.log(this.$refs.bar1)
//                  this.$refs.bar1[0].init()
//                  this.$refs.bar2[0].init()
                  this.timer1 = 20
                  this.timer2 = 20
                  this.stop1 = false
                  this.stop2 = false
                  this.setI1 = setInterval(function(){
                    self.wid1-=1
//              console.log(self.wid)
                    if(self.wid1 <=0)
                    {
                      clearInterval(self.setI1)
                    }
                  },self.timeBetweenFrames)

                  this.setI2 = setInterval(function(){
                    self.wid2-=1
//              console.log(self.wid)
                    if(self.wid2<=0)
                    {
                      clearInterval(self.setI2)
                    }
                  },self.timeBetweenFrames)


                },3000)

              }
              else
                if(payload.data.end)
                {
                  setTimeout(()=>{
                    this.showWinner(3 - payload.data.winner)
                  },3000)
//                  console.log("----------" + payload.data.winner)
                }
//            self.timer2 = payload.timer2
          })
        Echo.channel('notify.'+ this.$store.state.isAuthenticated)
          .listen('RespondToGameInvitation',(payload) => {
            //on pushed client

            console.log(payload)
            console.log(self.$store.state.isAuthenticated)
            console.log("game")
            if(payload.accepted==-1)
            {
              self.opponent = payload.data.opponentName
              self.$refs.error.open()
            }
            else
              if(payload.accepted===0 && self.received==0)
              {
//                self.received = 1
                self.user.id = payload.data.userid2
                self.user.name = payload.data.username2
                self.user.imgpath = payload.data.userimg2
                self.user.imgwidth = payload.data.userwidth2
                self.user.imgheight = payload.data.userheight2
                self.user.imgshape = payload.data.usershape2

                self.opponent = {}

                self.opponent.id = payload.data.userid1
                self.opponent.name = payload.data.username1
                self.opponent.imgpath = payload.data.userimg1
                self.opponent.imgwidth = payload.data.userwidth1
                self.opponent.imgheight = payload.data.userheight1
                self.opponent.imgshape = payload.data.usershape1

                self.time = payload.data.timestarted
                self.$store.dispatch("showGameOn",1)

              }
            else
              if (payload.data.cancel === 1)
              {console.log(payload)
                self.canceled2 = 1
                setTimeout(() => {
                  self.$store.dispatch("showGameOn", 0)
                  setTimeout(() => {
                    self.received=0
                    self.trigger1=0
                    self.trigger2=0
                    self.accepted=0
                    console.log("aici a intrat")
                    self.canceled1=0
                    self.canceled2 = 0
                  },1000)
                },2000)
              }
            else
              if(payload.data.accepted ===1)
              {

                self.accepted2 = 1
                if(self.accepted1==1)
                {

                  setTimeout(()=>{
                    self.status = "inProgress"
                  },1500)
                }
              }
//            this.$store.dispatch("addNotification",payload)
//            this.notificationsCount+=1
          })

    }
  }
</script>

<style lang="scss" rel="stylesheet/scss" scoped>
  .shinered1{
    -moz-box-shadow:    15px 0 20px indianred!important;
    -webkit-box-shadow: 15px 0 20px indianred!important;
    box-shadow:         15px 0 20px indianred!important;
  }
  .shinegreen1{
    -moz-box-shadow:    15px 0 20px darkgreen!important;
    -webkit-box-shadow: 15px 0 20px darkgreen!important;
    box-shadow:         15px 0 20px darkgreen!important;
  }

  .shinered2{
    -moz-box-shadow:    -15px 0 20px indianred!important;
    -webkit-box-shadow: -15px 0 20px indianred!important;
    box-shadow:         -15px 0 20px indianred!important;
  }
  .shinegreen2{
    -moz-box-shadow:    -15px 0 20px darkgreen!important;
    -webkit-box-shadow: -15px 0 20px darkgreen!important;
    box-shadow:         -15px 0 20px darkgreen!important;
  }
  .faderelative-enter-active, .faderelative-leave-active {
    transition: opacity .5s;
    width: 100%;
  }
  .faderelative-enter, .faderelative-leave-to{
    opacity: 0
  }

  .gameready{
    width:100vw;
    height:100vh;
  }
  .ready{
    background:white!important;
    color:black!important;

  }
  .brd1{
    /*border:2px solid white;*/
    -moz-box-shadow:    5px 0 20px #fff;
    -webkit-box-shadow: 5px 0 20px #fff;
    box-shadow:         5px 0 20px #fff;
    -webkit-border-radius: 10px;
    -moz-border-radius: 10px;
    border-radius: 10px;
  }
  .brd2{
    /*border:2px solid white;*/
    -webkit-border-radius: 10px;
    -moz-border-radius: 10px;
    border-radius: 10px;
    -moz-box-shadow:    -5px 0 20px #fff;
    -webkit-box-shadow: -5px 0 20px #fff;
    box-shadow:         -5px 0 20px #fff;

  }
  .ready:disabled{
    background:darkgrey!important;
  }
  .thisuser img{
    margin-top:0.5rem;
    border:3px solid darkred;
  }
  .polygon {
    border-radius:10px;
  }
  .round{
    border-radius:50%;  /*pt pozele cu width = height*/
  }

  .gcontainer{

    perspective:1000px;
    transform-style: preserve-3d;
    perspective-origin: center;
  }

  .gameon{
    -moz-box-shadow:    inset 0 0 20px #000000;
    -webkit-box-shadow: inset 0 0 20px #000000;
    box-shadow:         inset 0 0 100px #000000;
    background:darkred;
    position:fixed;
    z-index: 100;
    width:100vw;
    height:100vh;
    backface-visibility: hidden;
  }
  .v-on-enter{
    /*transform:scale(0);*/
  }
  .v-enter-active{
    opacity: 0;
    transform:scaleX(0);
    transition:all 1s;
  }
  .v-enter-to{
    opacity: 1;
    transform:scaleX(1);
  }
  .v-leave-to{
    opacity: 0;
    transform:scaleX(0);
  }
  .v-leave-active{
    transition:all 1s;
  }

  .cancel{
    text-decoration:underline;
    margin-left:10px;
    padding:10px;
  }
  .thisuser p, .thatuser p{
    display: inline-block;
    max-width:150px;
    font-weight:bold;
    font-variant:small-caps;
    font-size:20px;

  }
  .thisuser img, .thatuser img{
    margin-top:1rem;
  }
  .thisuser button, .thatuser button {
    margin-bottom:1rem;
  }
  .thisuser a, .thatuser a{
    font-size:12px;
    margin-top:2rem;
  }


  .thisuser, .thatuser {
    background: transparent;
    max-width:80vw;
    min-height:25vh;
    margin:0 auto;
    text-align:center;
  }
  .thisuser{
    transition:all 1s ease;
    margin-top:75px;
  }
  .thatuser{
    transition:all 1s ease;
    margin-top:20px;

  }


  /*.vs{*/
    /*background: teal;*/
    /*color:red;*/
    /*font-size:2rem;*/
    /*width:80vw;*/
    /*height:8vh;*/
    /*margin:1rem auto;*/
    /*text-align:center;*/
    /*transition:all 1s ease;*/

  /*}*/

  .thisuser,.thatuser,.vs{
    -webkit-backface-visibility : hidden;
    -moz-backface-visibility : hidden;
    -o-backface-visibility : hidden;
    -ms-backface-visibility : hidden;
    backface-visibility : hidden;
    transform-style: preserve-3d;
    position: relative;
    transform-origin: left;
    transform:rotateY(90deg) translateZ(80vw);

    -moz-box-shadow:    inset 0 0 10px #000;
    -webkit-box-shadow: inset 0 0 10px #000;
    box-shadow:         inset 0 0 50px #000;
  }

  .thisuser a,.thatuser a{
    cursor:pointer;
  }
  .backsquare{
    width:80vw;
    position:absolute;
    height:100%;
    left:0;
    top:0;
    background:transparent;
    transform:translateZ(-80vw);
    transform-origin: left center;
    -moz-box-shadow:    inset 0 0 10px #000000;
    -webkit-box-shadow: inset 0 0 10px #000000;
    box-shadow:         inset 0 0 40px #000000;
  }
  .bottomsquare{
    width:80vw;
    position:absolute;
    height:80vw;
    left:0;
    bottom:0;
    background:transparent;
    transform: rotateX(90deg) ;
    transform-origin: bottom center;
    -moz-box-shadow:    inset 0 0 10px #000000;
    -webkit-box-shadow: inset 0 0 10px #000000;
    box-shadow:         inset 0 0 10px #000000;

  }

  .topsquare{
    width:80vw;
    position:absolute;
    height:80vw;
    left:0;
    top:0;
    background:transparent;
    transform: rotateX(-90deg);
    transform-origin: top center;
    -moz-box-shadow:    inset 0 0 10px #000000;
    -webkit-box-shadow: inset 0 0 10px #000000;
    box-shadow:         inset 0 0 10px #000000;

  }

  .leftsquare{
    width:80vw;
    position:absolute;
    height:100%;
    left:0;
    top:0;
    background:darkred ;
    transform-origin: left center;
    transform:  translateZ(-80vw) rotateY(-90deg);
    -moz-box-shadow:    inset 0 0 10px #000000;
    -webkit-box-shadow: inset 0 0 10px #000000;
    box-shadow:         inset 0 0  40px #000000;
    display: flex;
    flex-direction: column;
    justify-content: center;
  }
  .rightsquare{
    width:80vw;
    position:absolute;
    height:100%;
    left:0;
    top:0;
    background:darkred;
    transform:  translateZ(-80vw) rotateY(90deg);
    transform-origin: right center;
    -moz-box-shadow:    inset 0 0 10px #000000;
    -webkit-box-shadow: inset 0 0 10px #000000;
    box-shadow:         inset 0 0 40px #000000;
    display: flex;
    flex-direction: column;
    justify-content: center;
  }

  .gamestatus{
    transition:2s all;
    opacity:1;
    margin:0 auto;
  }
  /*.vs::before*/
  /*{*/
    /*content:"";*/
    /*width:50vw;*/
    /*height:50vw;*/
    /*position:absolute;*/
    /*left:0;*/
    /*bottom:0;*/
    /*background:red;*/
    /*transform: rotateX(90deg);*/
    /*transform-origin: bottom center;*/
    /*-moz-box-shadow:    inset 0 0 10px #000000;*/
    /*-webkit-box-shadow: inset 0 0 10px #000000;*/
    /*box-shadow:         inset 0 0 10px #000000;*/
  /*}*/

  /*.vs::after{*/
    /*content:"";*/
    /*width:50vw;*/
    /*height:20vh;*/
    /*position:absolute;*/
    /*left:0;*/
    /*top:0;*/
    /*background:red;*/
    /*transform: rotateY(90deg);*/
    /*transform-origin: left center;*/
    /*-moz-box-shadow:    inset 0 0 10px #000000;*/
    /*-webkit-box-shadow: inset 0 0 10px #000000;*/
    /*box-shadow:         inset 0 0 10px #000000;*/
  /*}*/
  .rotate1{
    transform:rotateY(0) translateX(0) rotateX(-20deg);
    -moz-transform:rotateY(0) translateX(0) rotateX(0);

  }

  .rotateLeft{
    transform-origin:right center;
    transform: rotateY(90deg) translateX(80vw) rotateX(0);
    -moz-transform:rotateY(90deg) translateX(80vw) rotateX(0);
  }

  .rotateRight{
    transform-origin: left center;
    transform: rotateY(-90deg) translateX(-80vw) rotateX(0);
    -moz-transform:rotateY(-90deg) translateX(-80vw) rotateX(0);

  }
  /*.rotate2{*/

    /*transform:rotateY(0) translateX(0);*/
  /*}*/
  .rotate3{

    transform:rotateY(0) translateX(0) rotateX(20deg);
    -moz-transform:rotateY(0) translateX(0) rotateX(0);
  }
  .loader{
    background-color:red;
    width:40px;
    height:10px;
    margin:20px auto 0;
    transition:all 2s;

  }

  .p1 p,.p2 p, .titleq p{
    margin: 0;
    padding:0;
  }
  @media only screen and (min-width: 700px) {
    .result{
      display: flex;
      align-items: center;
      justify-content: center;
      flex-direction: column;
      margin:0 auto;
      margin-top:20%;
      width:70%;
      background:black;
      border-radius:10px;
      color:white!important;
    }

    .question1{
      text-align:center;
      font-size:20px;
      max-width: 80%;
      margin:20px auto;
    }

    .answercontainer .answers1{
      margin-left: 40px;
      cursor:pointer;
      width:92%;
      padding:20px 0 20px 5%;
      position:relative;
    }
    .answers1::before{
      content: '';
      position:absolute;
      width:90%;
      height:2px;
      border-radius:2px;
      margin-left:auto;
      margin-right:auto;
      left: 0;
      right:0;
      bottom:0;
      transform:scale(0);
      transition:all 0.5s ease;
      background: white;
    }

    .answers1:hover::before{
      transform:scale(1);
    }


    .answercontainer .answers1:hover{
      background: rgba(210,210,210,0.2);

    }

    .answercontainer {
      position: relative;
      padding-bottom:20px;
    }
      .bar1{
        position:absolute;
        left: 5px;
        top:-60px;
      }
      .bar2{
        position: absolute;
        right:5px;
        top: -60px;
      }

      .barcontainer{
        position:absolute;
        width:2px;
        top:-60px;
        background: white;
        height: 300px;
      }
      .b1{
        left:3px;
      }
      .b2{
        left:10px;
      }
      .b3{
        right:10px;
      }
      .b4{
        right:3px;
      }

      .b1::after,.b3::after{
        content:"";
        position: absolute;
        top:-2px;
        width:9px;
        height:4px;
        background:white;
      }
    .problems{
      width:calc(100% - 60px);
      border-radius:10px;
      background-color:black;
      position:absolute;
      top:200px;
      margin-left: auto;
      margin-right: auto;
      left: 0;
      right: 0;
    }
    .titleq{
      position:absolute;
      margin-left: auto;
      margin-right: auto;
      left: 0;
      right: 0;
      text-align:center;
      font-weight:bold;
      font-variant:small-caps;
      margin-top:1rem;
    }
    .titleq p:first-child{
      font-size:50px;

    }
    .titleq p:nth-child(2){
      font-size:70px;
    }

    .p1,.p2{
      min-width:250px;
      max-width:500px;
      margin:2rem;
      text-align:center;
      font-size:50px;
      display:flex;
      font-weight:bold;
      font-variant:small-caps;
      border:3px solid black;
      border-radius:10px;
      background: black;
    }
    .p1 p,.p2 p{
      text-align:initial;
      font-size:15px;
    }
    .p1 p:first-child::before, .p2 p:first-child::before {
      content: "Name: \00a0";
    }
    .p1 p:nth-child(2)::before, .p2 p:nth-child(2)::before {
      content:"Total Score: \00a0";
    }
    .p1 .infouser{
      margin-right:10px;
    }
    .p2 .infouser{
      margin-left:10px;
    }
    .brd1 , .brd2 {
      margin:15px 20px 15px 20px;

    }
    .p1 {
      position:absolute;
      left:0;
      top:0;


    }
    .p2 {
      position:absolute;
      right:0;
      top:0;

      flex-direction: row-reverse;
    }

    .infouser{
      vertical-align: top;
      margin-top:12px;
    }
    .green{
      background:darkgreen!important;
    }
    .red{
      background:indianred!important;
    }
  }
  //-------------------------------------------------------------------------------
  .wrp{
    overflow: hidden;
  }


  //-------------------------------------------------------------------------------

  @media only screen and (max-width: 700px) {
    .green{
      background:green!important;
    }
    .red{
      background:red!important;
    }
    .p1,.p2{
      width:90px;
      text-align:center;
      display:inline-block;
      font-weight:bold;
      font-variant:small-caps;

    }
    .titleq{
      width:calc(98vw - 190px);
      text-align:center;
      display:inline-block;
      font-weight:bold;
      font-variant:small-caps;
    }
    .titleq p:first-child{
      font-size:30px;

    }
    .titleq p:nth-child(2){
      font-size:60px;
    }
    .backsquare{
      -moz-box-shadow:    inset 0 0 400px #000000;
      -webkit-box-shadow: inset 0 0 400px #000000;
      box-shadow:         inset 0 0 400px #000000;
    }
    .bottomsquare{
      -moz-box-shadow:    inset 0 0 100px #000000;
      -webkit-box-shadow: inset 0 0 100px #000000;
      box-shadow:         inset 0 0 100px #000000;

    }

    .topsquare{
      -moz-box-shadow:    inset 0 0 100px #000000;
      -webkit-box-shadow: inset 0 0 100px #000000;
      box-shadow:         inset 0 0 100px #000000;

    }

    .leftsquare{
      -moz-box-shadow:    inset 0 0 400px #000000;
      -webkit-box-shadow: inset 0 0 400px #000000;
      box-shadow:         inset 0 0  400px #000000;
    }
    .rightsquare{
      -moz-box-shadow:    inset 0 0 400px #000000;
      -webkit-box-shadow: inset 0 0 400px #000000;
      box-shadow:         inset 0 0 400px #000000;
    }
    .result{
      font-weight:800;
      font-size:18px;
      margin-top:20%;
      text-align:center;
    }

    .gamewindow{
      margin:10px 1vw
    }

    .answers1{
      display: flex;
      align-items: center;
      margin:0 auto;
      justify-content: center;
      width:80vw;
      height: 4rem;
      overflow: auto;
      border:2px solid black;
      background:white;
      border-radius:10px;
      color:black;
      margin-top:10px;
      padding-top:4px;
      /*padding-bottom:25px;*/
      /*padding-top:15px;*/
    }
    .answers1 h4{
      margin: 0;
      padding:5px 5px 5px 5px;
      -ms-word-break:normal;
      word-break: normal;
    }
    .question1{
      display:inline-block;
      -ms-word-break: normal;
      word-break: normal;
      padding: 3px 3px 0 3px;
      width:90vw;
      margin: 0 0 0 4vw;
      text-align: center;
      overflow:auto;
      height:15vh;
      -moz-box-shadow:    inset 0 0 50px #000000;
      -webkit-box-shadow: inset 0 0 50px #000000;
      box-shadow:         inset 0 0 50px #000000;
      border-radius:10px;
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

    /*#animatedbar{*/
      /*position:absolute;*/

    .answercontainer{
      position: relative;
    }

    /*}*/
    .bar1{
      position:absolute;
      left:5px;
      top:0
    }
    .bar2{
      position: absolute;
      right:5px;
      top: 0;
    }

    .barcontainer{
      position:absolute;
      width:2px;
      top:0;
      background: white;
      height: 300px;
    }
    .b1{
      left:3px;
    }
    .b2{
      left:10px;
    }
    .b3{
      right:10px;
    }
    .b4{
      right:3px;
    }

    .b1::after,.b3::after{
      content:"";
      position: absolute;
      top:-2px;
      width:9px;
      height:4px;
      background:white;
    }

//1+2vw la stanga / 1+2vw la dreapta//

  }

</style>
