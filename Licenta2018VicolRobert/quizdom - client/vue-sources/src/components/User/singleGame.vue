<template>
  <div id="app">

  	<div class="marg problem" v-for="(i,index) in ids" v-if="index==current">
  		<h3>{{response[i].tq}}</h3>
  		<div class="answers" v-for="(a,order) in answers[current]" v-on:mouseover=underline(order) v-on:mouseleave=underline(-1) @click.prevent="solve(order)" v-bind:class="{ 'right':(right==order || answers[index][order]==response[i].ta && picked), 'wrong':(wrong==order) }">
        <h4 class="">{{String.fromCharCode(ch.charCodeAt() + parseInt(order+1)) + ". " + a}}</h4>
      </div>
<!--   		<div class="ans" v-if="!picked">
  			<button v-for="(a,order) in answers[index]" @click.prevent="solve(order)">{{String.fromCharCode(ch.charCodeAt() + parseInt(order+1))}}</button>
  		</div> -->
  	   <div v-if="picked" class="rep">
      <button @click.prevent="report">Report</button>
      <h4>{{result}}</h4>
      <div class="draw" v-bind:class="{ drawing: selected}">
        <h4 class="load">Loading</h4>
        <div id="wave">
          <span class="dot"></span>
          <span class="dot"></span>
          <span class="dot"></span>
        </div>
      </div>
    </div>
    <p class="helper" v-if="showhelper">{{helper}}</p>
    <p class="helper" v-if="showhelper">{{contribution}}</p>
    <h3 class="helper" v-if="showhelper">{{caniask}}</h3>

    <h4>{{end}}</h4>

    </div>

  </div>
</template>

<script>
  import Vue from 'vue'

  export default {
    name: 'singleGame',
    data () {
      return {
        response: {},
        active:-1,
        selected:0,
        ids:[],
        clicked:0,
        current:0,
        answers:[],
        picked:0,
        right:-1,
        wrong:-1,
        result:"",
        end:"",
        showhelper:1,
        caniask:"",
        howmany:0,
        helper:"Each problem can have between 2 and 4 answers. Only one of those is correct. If you believe that the problem is wrong or that it does not match its category feel free to Report it. You will be able to do that once you try to solve the problem.",
        contribution:"You can contribute to the set of problems in any category. All you have to do to submit a problem is to solve 10 of them. Once submited, the problem will be available for purchase by any other user. The tokens spent by others on purchasing your questions will be redirected to your account.",
        reported:0,
        ch:String.fromCharCode(64),
      }
    },
    methods: {
    shuffleArray(array) {
    	for (var i = array.length - 1; i > 0; i--) {
        	var j = Math.floor(Math.random() * (i + 1));
        	var temp = array[i];
        	array[i] = array[j];
        	array[j] = temp;
    	}
	},
  underline(ord){
    this.active = ord;
  },
	report(){
		this.reported = 1
	},
	solve(pick){
    if(this.clicked==1)
      return;
    this.showhelper = 0
    this.clicked=1
		var self = this
		if(this.response[this.ids[this.current]].ta == this.answers[this.current][pick])
		{
			this.right = pick
			var payload = 1
			this.result = "The answer is correct!"
      this.progress()
		}
		else
		{
			this.wrong = pick
			var payload = 2
			this.result = "The answer is wrong!"
		}
		this.picked=1
    this.selected=1
		setTimeout(function(){
         	self.result = ""
         	self.right = -1
         	self.wrong = -1
         	self.picked = 0
          self.clicked=0
          self.selected=0
          self.showhelper = 1
  			Vue.http.put(self.$apiurl + 'solve', {'id':self.response[self.current].pid,'answered' : payload,'reported':self.reported}).then((success) => {
			})
  			self.reported = 0
         	self.current = self.current + 1
         	if(self.current==self.ids.length)
         	{
            self.showhelper = 0
         		self.end = "You have answered all the problems available in this category!"
         		setTimeout(function(){
         			self.$router.go(-1)
         		},3000)
         	}
       	},3000);

		},
    progress(){
          var howmany = this.howmany
          howmany+=1
          if(howmany>=10)
          {
            this.caniask = "You can currently submit "+howmany/10 + " problems."
          }
          else
          {
            if(howmany==9)
              this.caniask = "You must solve "+(10-howmany) + " more problem until you can submit one."
            else
              this.caniask = "You must solve "+(10-howmany) + " more problems until you can submit one."
          }
    }
  },

  	mounted() {
    	var cat = this.$store.state.category_id
      var catid = JSON.parse(window.localStorage.getItem('catid'))
    	Vue.http.get(this.$apiurl + 'unanswered/'+catid).then((response) => {
    		if(response.body.ok == 1)
    		{
    			this.response = response.body.problems
          //console.log(this.response)

    			for(var i = 0 ; i<this.response.length;i++)
    			{
    				this.ids.push(i)
    			}
 				this.shuffleArray(this.ids)
 				for(var j=0;j<this.ids.length;j++)
 				{
          this.answers[j]= []
 					if(this.response[this.ids[j]].t1)
 						this.answers[j].push(this.response[this.ids[j]].t1)
 					if(this.response[this.ids[j]].t2)
 						this.answers[j].push(this.response[this.ids[j]].t2)
 				 	if(this.response[this.ids[j]].t3)
 						this.answers[j].push(this.response[this.ids[j]].t3)
 					this.answers[j].push(this.response[this.ids[j]].ta)
 					this.shuffleArray(this.answers[j])
 				}

        Vue.http.get(this.$apiurl + 'canask/'+catid).then((response) => {
          console.log(response.body.value)
          var howmany = response.body.value
          // howmany = 41
          this.howmany = howmany
          if(howmany>=10)
          {
            this.caniask = "You can currently submit "+Math.floor(howmany/10) + " problems."
          }
          else
          {
            if(howmany==9)
              this.caniask = "You must solve "+(10-howmany) + " more problem until you can submit one."
            else
            {
              this.caniask = "You must solve "+(10-howmany) + " more problems until you can submit one."
              console.log(howmany)
            }
          }
        })
    		}

    	})
    /*console.log(this.ids)
    console.log(this.answers)
    console.log(this.response)*/

    },

  }
</script>

<style scoped>
  #wave{
    margin-top:1rem;
  }
  .ans{
    display:flex;
    justify-content:center;
  }
  .ans button{
    margin:5%;
  }
  .right
  {
  	color:green
  }
  .wrong
  {
  	color:red
  }
  .rep{
    text-align:center;
  }
  .helper{

  }
  .load{

  }


.draw {
        position:relative;
    width:20rem;
    height:10rem;
    font-size: 2rem;
    color:black;
    border:none;
    margin:0 auto;
}
.draw::before{
    content:"";
    position:absolute;
    left:0;
    top:0;
    animation-name:befo;
    animation-duration:1.5s;
    animation-fill-mode:forwards;

}

.draw::after{
    content:"";
    position:absolute;
    right:0;
    bottom:0;
    animation-name:afte;
    animation-duration:1.5s;
    animation-delay:1.5s;
    animation-fill-mode: forwards;
}


@keyframes befo {
  0% {
      border-top:solid 2px black;
      border-right:solid 2px black;
      width:0;
      height:0;
    }
  50% {width:100%;height:0;      border-top:solid 2px black;
      border-right:solid 2px black;}
  100% {width:100%;height:99%;      border-top:solid 2px black;
      border-right:solid 2px black;}
}


@keyframes afte{
  0%{    border-bottom:solid 2px black;
    border-left:solid 2px black;
    width:0;
    height:0;
  }
  50%{width:100%;height:0; border-bottom:solid 2px black;
    border-left:solid 2px black;}
  100%{width:100%;height:99%; border-bottom:solid 2px black;
    border-left:solid 2px black;}
}
</style>
