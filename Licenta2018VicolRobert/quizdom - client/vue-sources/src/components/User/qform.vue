<template>
	<div>
		<div class="card">
			<div class="marg" v-if="tok&& !add">
			<h2>Submit a problem for the {{catname}} category!</h2>
			<div class="flex">
				<div class="flx">
				<label for="q">Question (mandatory)	</label>
               	<textarea id="q" name="question" placeholder="Find a problem worth asking..." v-model="q" required> </textarea> 
               </div>
               	<div class="flx">
				<label for="ar">Right Answer (mandatory)	</label>
               	<textarea id="ar" name="ar" placeholder="Give your problem the correct answer" v-model="ar" required> </textarea> 
               </div>
               	<div class="flx">
				<label for="a1">Wrong answer 1 (mandatory)	</label>
               	<textarea id="a1" name="a1" placeholder="Wrong answer to trap other users" v-model="a1" required> </textarea> 
               </div>
               	<div class="flx">
				<label for="a2">Wrong answer 2 (optional)	</label>
               	<textarea id="a2" name="a2" placeholder="Wrong answer to trap other users" v-model="a2" required> </textarea> 
               </div>
               <div class="flx3 flx">
				<label for="a3">Wrong answer 3 (optional)	</label>
               	<textarea id="a3" name="a3" placeholder="Wrong answer to trap other users" v-model="a3" required> </textarea> 
               </div>
		</div>  
		<br>
			<h2 v-if="warning">Please fill all the mandatory fields!</h2>

              	<button @click="send">Submit</button>
			</div>
			<h2 v-if="!tok">You need more tokens to post a problem in this category!</h2>
			<h2 v-if="add">Added</h2>
	</div>
	</div>

</template>




<script>
	import Vue from 'vue'

	export default {
		name: 'qform',
		data () {
			//var user = JSON.parse(window.localStorage.getItem('USER'))
			return {
      			catid : JSON.parse(window.localStorage.getItem('catid')),
      			catname : window.localStorage.getItem('catname'),
				tok:1,
				q:"",
				ar:"",
				a1:"",
				add:0,
				a2:"",
				a3:"",
				warning:0
			}
		},

		methods: {
			gotoCreateForm(id){
          		window.localStorage.setItem('catid',id)
				this.$router.push({path: "/qform"})
			},
			send()
			{
				if(this.q=="" || this.ar=="" || this.a1=="")
					{this.warning=1
						return
					}
				console.log(this.q,this.ar,this.a1,this.a2,this.a3)
			 Vue.http.post(this.$apiurl + 'submit', {question: this.q, right:this.ar, bad1:this.a1, bad2:this.a2, bad3:this.a3, category_id:this.catid} ).then((response) => {	
			 	 this.add=1
			 	 setTimeout(function(){
         			self.$router.push({path:"/welcome"})
         		},3000)
			 })
			}
		},
		mounted() {
				Vue.http.get(this.$apiurl + 'tokens').then((response) => {	
					if (response.body.tokens<10)
					{
						this.tok = 0
					}

			})
			}
	
	}	
</script>




<style>
.flex{
	width:100%;
	display: flex;
	align-content:space-between;
	flex-direction: row;
	justify-content: space-between;
	flex-wrap: wrap;
}
h2{
	text-align: left;
}
.flx{
	display:block;
	flex-basis: 30rem;
	/*min-width:%;*/
	flex-wrap: wrap;

}	

label{
display: block;
margin-top:1rem;
}
textarea{
	margin-top:1rem;
	width:100%;
	height:5rem;
	font-size: 1.1rem;
}




</style>