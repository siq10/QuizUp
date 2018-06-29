<template>
		<div class="allbtn">
			<button class="btn" @click="gotoOwnedList" >Created Problems</button>
			<button class="btn" @click="gotoBoughtList" >Bought Problems</button>

		</div>
</template>




<script>
	import Vue from 'vue'

	export default {
		name: 'owned',
		data () {
			//var user = JSON.parse(window.localStorage.getItem('USER'))
			return {
				
			}
		},

		methods: {
			gotoBoughtList(){
				this.$router.push({path:"/boughtList"})
			},
			gotoOwnedList(){
				this.$router.push({path:"/ownedList"})
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
	

</style>s