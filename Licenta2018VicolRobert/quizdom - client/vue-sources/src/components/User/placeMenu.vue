<template>
	<div>
		<h1>{{$route.params.name}}</h1>
		<h2>{{title}}</h2>
				<table class="menu" align="center">
					<tr>
						<td class="navbr" v-for='(p,index) in products' @click.prevent="itemsIn(index)">{{p.name}}</td>

					</tr>
				</table>
				<br>
				<table v-if='row!=-1'>
				    <th>Product</th>
        			<th>Price</th>
        			<th>Ingredients</th>
        			<template  v-for='(i,ind) in products[row].products'>
					<tr>
						<td>{{i.name}}</td>
						<td>{{i.price}}</td>
						<td>{{i.ingredients}}</td>
					</tr>
					</template>
				</table>


				<button @click.prevent="goHome">Home</button>
	</div>
</template>

<script>
	import Vue from 'vue'

	export default {
		name: 'placeMenu',
		data () {
			
			return{
				products:[],
				row:-1,
				title:""

			}
		},
		methods: {
				goHome()
				{
					this.$router.push({path: '/welcome'})
				},
				itemsIn(value)
				{
					this.row = value
				},

		},
		mounted() {
				Vue.http.get('https://horeca.uatdev.net/api/menu/' + this.$route.params.place_id).then((response) => {
					if (typeof response.body.products[0] == 'undefined' )
					{
						this.title = "This place has no available products!"
					}
					else {
						this.title = "Categories"
					this.products = response.body.products
					}
					// this.payload.product_id = response.body.products[0].id
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
		width:10%; 
		border-style: solid
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