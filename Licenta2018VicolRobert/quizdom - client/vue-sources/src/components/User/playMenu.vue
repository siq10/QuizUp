	<template>
	<div id='app'>
		<div class="allbtn">
			<button class="btn" @click="gotoSinglePlayer">Single Player</button>
			<button class="btn" @click="gotoMultiPlayer" :disabled="allowed === 0">Versus</button>

		</div>
		<div class="align" v-if='!allowed'>
			<p>(you can only play Versus Mode if you have at least Level 10)</p>
		</div>


	</div>
</template>

<script>
	import Vue from 'vue'

	export default {
		name: 'playMenu',
		data () {
			//var user = JSON.parse(window.localStorage.getItem('USER'))
			return {
				allowed:0,
			}
		},

		methods: {
			gotoSinglePlayer() {
				this.$router.push({path: '/sp'})

			},


			gotoMultiPlayer() {
				this.$router.push({path:'/mp'})
			}
		},
		mounted() {
				Vue.http.get(this.$apiurl + 'pcount').then((response) => {
					if(response.body.problems>=1)
					{
						this.allowed = 1
					}
				})
			}

	}
</script>

<style scoped>
	.allbtn {
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;

  	}
  	.btn {
  		margin:1rem;
  		width:60%;
  	}
  	.align
  	{
  		text-align:center;
  	}
</style>
