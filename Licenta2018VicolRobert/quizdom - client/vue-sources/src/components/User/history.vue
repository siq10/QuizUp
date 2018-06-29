<template>
	<div>
		<h2>My History</h2>


		<div v-for="(r,index) in orders" class="card">
			<h3>{{ r.place[0].name }}</h3>
			<span class="date">{{ moment(r.date[0].updated_at).locale('ro').format('dddd, DD MMMM YYYY, HH:mm') }}</span>
			<span class="total">RON {{ r.total }}</span>

			<button class="" @click.prevent="fnc(index)" :disabled="r.items.length === 0">Details</button>

			<table v-if="expand && (id == index)" >
				<thead>
		            <tr>
		            	<th></th>
		                <th>Place</th>
		                <th>Cost</th>
		                <th>Date</th>
		            </tr>
		        </thead>
		        <tbody>
					<tr v-for="i in orders[index].items">
				        <td> {{i.name}}</td>
				        <td>x {{i.quantity}}</td>
				        <td>{{i.amount}}$</td>
				        <td>{{i.category_name}}</td>
			      	</tr>
			    </tbody>
			</table>
		</div>

	<!-- <table id="allOrders" class="display" cellspacing="0" width="100%">
        <thead>
            <tr>
            	<th></th>
                <th>Place</th>
                <th>Cost</th>
                <th>Date</th>
            </tr>
        </thead>
        <tbody>
	    <template v-for="(r,index) in orders">
	    	<tr>
					<td> <button class="btn" @click.prevent="fnc(index)">Details</button></td>
    				<td>{{ r.place[0].name }}</td>
    				<td>{{ r.total}}$</td>
    				<td>{{ r.date[0].updated_at }}</td>
	      	</tr>
	        <tr v-if="expand && (id == index)" v-for="i in orders[index].items">
		        <td> {{i.name}}</td>
		        <td>x {{i.quantity}}</td>
		        <td>{{i.amount}}$</td>
		        <td>{{i.category_name}}</td>
	      	</tr>
	    </template>
        </tbody>

    </table> -->
    	<button class="home" @click.prevent="homebutton">Home</button>
	</div>
</template>

<script>
	import Vue from 'vue'
	import moment from 'moment'

	export default {
		name: 'history',
		data () {
			return {
				orders: [],
				expand: 0,
				id:-1
			}
		},
		methods: {
			moment,
				fnc(ind) {
					if(this.id==ind)
					{
					this.expand = !this.expand

					}
					else
					this.expand = 1
					this.id = ind;
				},
				homebutton()
				{
					this.$router.push({path: '/welcome'})
				}
			},
		mounted: function() {
					Vue.http.get('https://horeca.uatdev.net/api/history').then((response) => {
					this.orders = response.body.orders.sort((a, b) => {
						return moment(a.date[0].updated_at).isBefore(moment(b.date[0].updated_at)) ? 1 : -1
					})
					console.log(this.orders)
				})	
			}

	}	

</script>

<style scoped>
.btn{
	margin:.8rem;
}
.home{
	margin:3rem;
}

.date, .total {
	display: block;
	font-style: italic;
	margin-bottom: .5rem;
}

table {
	font-size: 80%;
}
</style>
