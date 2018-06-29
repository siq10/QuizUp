<template>
	<div>
		<h1>Places</h1>
				<table class="places" align="center">
				<tr v-for="p in placeArray" @click.prevent="goToMenu(p.id,p.name)">
					<td>{{p.name}}</td>
				</tr>
				</table>
				<!-- <ul>
			
					<li v-for="p in placeArray" @click.prevent="goToMenu(p.id,p.name)">{{p.name}}</li>
				</ul> -->


				<button @click.prevent="goHome" >Home</button>
	</div>
</template>

<script>
	import Vue from 'vue'

	export default {
		name: 'places',
		data () {
			
			return{
				placeArray:[]
			}
		},
		methods: {
				goHome()
				{
					this.$router.push({path: '/welcome'})
				},
				goToMenu(place_id,place_name)
				{
					this.$router.push({ path: '/places/'+ place_id + '/menu/' + place_name })

				}

		},
		mounted: function () {
					Vue.http.get('https://horeca.uatdev.net/api/places').then((response) => {
						this.placeArray = response.body.places
				})
    	}
	}
</script>

<style scoped>
	table {
    font-family: arial, sans-serif;
    border-collapse: collapse;
    width: 100%;
	}
	.pref
	{
		margin:1rem; 
	}
	form{
		text-align: center;		
	}
	td, th {
    border: 1px solid #dddddd;
    text-align: left;
    padding: 8px;
	}

	.places {
		width:80%;
		border-style: solid;
		margin-bottom: 1rem;		
	}
	.navbr
	{
		border-style:solid;
	}
	.navbr:hover
	{
		color: #000;
		font-weight: bold;
 	cursor: pointer; 
	}
	.dsa
	{
		display:inline;
	}
</style>