<template>
	<div>
		<h1>Menu - {{place_name}}</h1>
		<h2>Categories</h2>
				<table class="menu" align="center">
					<tr>
						<td class="navbr" v-for='(m,index) in menu_items' @click.prevent="itemsIn(index)">{{m.name}}</td>
					</tr>
				</table>
				<br>
				<table v-if='row!=-1'>
				    <th>Product</th>
        			<th>Price</th>
        			<th>Ingredients</th>

        			<template  v-for='(i,ind) in menu_items[row].products'>
					<tr @click.prevent="showForm(ind)">
						<td>{{i.name}}</td>
						<td>{{i.price}}</td>
						<td>{{i.ingredients}}</td>
					</tr>
					<form v-if='expand && (form==ind)'>
						<input type="hidden" name="product_id" id="id" v-model="payload.product_id">
						<label for="q">Add item to your order:</label>
						<input type="number" min="1" name="quantity" v-model="payload.quantity" placeholder="quantity" id="q">
						<textarea class="pref" name="preferences" v-model="payload.preferences" rows="3" cols="40" maxlength="100" placeholder="Preferences (optional)"></textarea>
						<button class="add" @click.prevent="addToOrder(i.id)" >Add</button>
						<br>
						<h3>
						{{result}}
						</h3>
					</form>
					</template>
				</table>


				<button @click.prevent="goBackToOrder" >Home</button>
	</div>
</template>

<script>
	import Vue from 'vue'

	export default {
		name: 'showMenu',
		data () {
			var place_name=window.localStorage.getItem('PLACENAME')
			var message
			return {
			payload: {quantity: 1,product_id:0 ,preferences:''},
			place_name,
			message:"",
			categories:[],
			expand:0,
			form:-1,
			row:-1,
			result:""

			}
		},
		methods: {
			addToOrder(prod_id) {
         		var self = this
				var doit = 1;
					if (this.payload.quantity <= 0 ) {
						this.result="Invalid data"
         			setTimeout(function(){
         					self.result = ""
         					},3000);
         			doit = 0;
         			}
         			if (doit)
					{
						this.payload.product_id = prod_id
					Vue.http.post('https://horeca.uatdev.net/api/addtoOrder', this.payload ).then((success) => {
					this.response = success
					this.error = false
         					this.result="Product added to your order"
					setTimeout(function(){
						self.result = ""
         				},3000);
				}, 
				(error) => {
					this.error = true
				})
			}},

			itemsIn(value)
			{
				this.row = value
			},

			showForm(id)
			{
				if(this.form==id)
				{
					this.expand = !this.expand
				}
				else
				this.expand = 1
				this.form = id
			},

			goBackToOrder() {
				this.$router.push('welcome')
			},


		},

		computed: {
			menu_items(){
				if(this.$store.state.menu_items.length == 0)
				{	
					var place_id = window.localStorage.getItem('PLACEID')
					Vue.http.get('https://horeca.uatdev.net/api/menu/' + place_id).then((response) => {
					this.$store.dispatch('storeMenu',response.body.products)
				})
				}
				return this.$store.state.menu_items
			}
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

	.menu {
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