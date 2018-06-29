<template>
		<div class="allbtn">
			<button class="btn" v-for="cat in categories" @click="gotoCreateForm(cat.id,cat.name)" :disabled="cat.val == 1">{{cat.name}}</button>

		</div>
</template>




<script>
	import Vue from 'vue'

	export default {
		name: 'qmenu',
		data () {
			//var user = JSON.parse(window.localStorage.getItem('USER'))
			return {
				categories:{},
			}
		},

		methods: {
			gotoCreateForm(id,name){
          		window.localStorage.setItem('catid',id)
          		// console.log(name)
          		window.localStorage.setItem('catname',name)
          		// console.log(window.localStorage)
				this.$router.push({path: "/qform"})
			},
		},
		mounted() {
				Vue.http.get(this.$apiurl + 'caniadd').then((response) => {
					this.categories = response.body.ret		
				})
			}
	
	}	
</script>




<style>
	

</style>