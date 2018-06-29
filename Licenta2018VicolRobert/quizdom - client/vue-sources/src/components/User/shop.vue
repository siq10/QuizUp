<template>
  <div>
		<div class="card">
			<div class="marg" v-if="!purchased">
			<h2>You currently have {{tokens}} tokens!</h2>
			<h2 v-if="!tok">You cannot purchase problems without tokens	</h2>
		<br>
			<h2 v-if="warning">Please fill all the mandatory fields!</h2>
			<form @submit.prevent="buythem" v-if="tok">
				<label for="count">Problem number</label>
				<input id="count" type="number" required placeholder="How many problems do you want?" v-model="count">
				<label for="cat">Choose Category</label>
				<select id="cat" v-model="catid" required>
					<option v-for="cat in allcategories" v-bind:value="cat.id">{{cat.name}}</option>
				</select>
				<br><br><br><br>
				<button type="submit">Purchase</button>
			</form>
			</div>
			<h2 v-if="purchased">{{status}}</h2>
      <h2 v-if="!tok">You need more tokens to post a problem in this category!</h2>
		</div>
  </div>
</template>




<script>
	import Vue from 'vue'

	export default {
		name: 'shop',
		data () {
			//var user = JSON.parse(window.localStorage.getItem('USER'))
			return {
				tok:1,
				count:0,
				catid:0,
				warning:0,
				tokens:0,
				allcategories:[],
				purchased:0,
				status:""
			}
		},

		methods: {
			buythem()
			{
			if(this.count<=this.tokens)
			 		Vue.http.post(this.$apiurl + 'purchase', {amount: this.count, category_id:this.catid} ).then((response) => {
			 	 		this.purchased=1
			 	 		this.status=response.body.status
			 	 		self=this
			 	 		setTimeout(function(){
         					self.$router.push({path:"/welcome"})
         				},3000)
			 		})
			else
				{
					this.purchased=1
					this.status="You don't have enough tokens!"
		 	 		setTimeout(function(){
    	     			self.$router.push({path:"/welcome"})
         			},3000)
				}

			}
		},
		mounted() {
				Vue.http.get(this.$apiurl + "shopinfo").then((response) => {
					if (response.body)
					{
						this.tokens = response.body.tokens
						this.allcategories = response.body.allcategories
						if(this.tokens==0)
							this.tok=0
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
	flex-basis: 40%;
	/*min-width:%;*/
	flex-wrap: wrap;

}
.flx:last-child{
	align-self: center;

}
label{
display: block;
margin-top:1rem;
}
textarea{
	margin-top:1rem;
	min-width:35rem;
	height:5rem;
	font-size: 1.1rem;
}
#count{
	width:10rem;
}
form{
	border-radius: 2%;
}




</style>
