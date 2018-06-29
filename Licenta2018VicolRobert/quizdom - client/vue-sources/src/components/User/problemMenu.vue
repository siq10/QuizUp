<template>
		<div class="allbtn">
			<button class="btn" @click="gotoShop" >Acquire Problems</button>
			<button class="btn" @click="gotoCreate" >Create Problems</button>
			<button class="btn" @click="gotoOwned" >Owned Problems</button>

		</div>
</template>




<script>
	import Vue from 'vue'

	export default {
		name: 'problemMenu',
		data () {
			//var user = JSON.parse(window.localStorage.getItem('USER'))
			return {
				
			}
		},

		methods: {
			gotoShop(){
				this.$router.push({path: "/shop"})
			},
			gotoCreate(){
				this.$router.push({path:"/create"})
			},
			gotoOwned(){
				this.$router.push({path:"/owned"})
			},

			mounted() {
				Vue.http.get(this.$apiurl + 'pcount').then((response) => {
					if(response.body.problems>=100)	
					{
						this.allowed = 1
					}
				})
			}
	}
	}	
</script>




<style>
	

</style>