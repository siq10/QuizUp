<template>
	<div id='app'>
		<h2>Welcome, {{ name }}!</h2>
		<div class="allbtn">
			<button class="btn" @click="playMenu" >Play</button>
			<button class="btn" @click="problemMenu">Problems</button>
		<button class="btn" @click="account">Account Details</button>
		<button class="btn" @click="classes">MP Classes</button>
		<button class="btn" @click="logout">Log Out</button>

		</div>
	</div>
</template>

<script>
	import Vue from 'vue'

	export default {
		name: 'welcome',
		data () {
			var user = JSON.parse(window.localStorage.getItem('USER'))
			return {
				name: user.name,
			}
		},

		methods: {
			// startOrder() {
			// 	this.$router.push({path: 'newOrder'})
			// },


			// wantToPay() {
/*				Vue.http.put('https://horeca.uatdev.net/api/payOrder', this.payload ).then((success) => {
				this.response = success
				this.error = false
				this.message = success.body.answer
				this.final = 0
				this.btn = 1
			})*/
			// },

			playMenu () {

				this.$router.push({path: 'playModes'})
			},
			classes(){
				this.$router.push({path:'classes'})
			},
			problemMenu(){
				this.$router.push({path:'problemMenu'})
			},
			// seeMenu () {
			// 	Vue.http.get('https://horeca.uatdev.net/api/menu/' + this.place_id).then((response) => {
			// 		this.response = response
			// 		this.menu_item = response.body.products
			// 		this.$store.dispatch('storeMenu',this.menu_item)
			// 		this.$router.push({path: 'menu'})
			// 	})
			// },
			// goHome () {
			// 		this.$router.push({path: 'welcome'})
			// 		this.ok=0
			// 		this.btn = 0
			// 		this.check = 1
			// },
			account() {
				this.$router.push({path:'account'})
			},
			prevOrders() {
					this.$router.push({path: 'history'})
			},

			places() {
				this.$router.push({path: 'places' })
			},
			logout() {
        Echo.channel('notify.'+ this.$store.state.isAuthenticated)
          .stopListening('NotifyUser')
          .stopListening("RespondToGameInvitation")
          .stopListening("QuickRoom")
				window.localStorage.clear()
        this.$store.dispatch("storeAuth",0)
        this.$router.push('/')
			}

		}
	}
</script>

<style scoped>

	h2{
    text-align:center;
	}

</style>
