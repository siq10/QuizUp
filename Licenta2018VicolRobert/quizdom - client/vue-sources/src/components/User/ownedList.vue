<template>
	<div>
		<div id="trapezoid">
			<ul class="navb">
				<li v-for="c in categories" @click.prevent="showproblems(c.id)">{{c.name}}</li>
			</ul>
		</div>
		<div class="tablecontainer">
			<table class="tbl">
				<template class="letsee" v-for="p in problems" v-if="p.catid==categorywanted">
					<tr class="tablelead" @click.prevent="select(p.pid)">{{p.tq}}</tr>
					<tr class="tmargin" v-if="chosen==p.pid">There have been {{p.answers}} answers to this problem</tr>
					<tr class="tmargin" v-if="chosen==p.pid">Correct: {{p.answers-p.mistakes}}</tr>
					<tr class="tmargin tabletail" v-if="chosen==p.pid">Mistakes: {{p.mistakes}}</tr>
					<br>
				</template>
			</table>
			<h3 v-if="!categorywanted">Choose one category to see your posted problems!</h3>
		</div>
	</div>
</template>


	

<script>
	import Vue from 'vue'

	export default {
		name: 'ownedList',
		data () {
			//var user = JSON.parse(window.localStorage.getItem('USER'))
			return {
				categories:[],
				categorywanted:0,
				problems:[],
				chosen:0
			}
		},

		methods: {
			select(id)
			{
				this.chosen=id;
			},
			showproblems(id)
			{
				this.categorywanted = id;
			}

	},
	mounted() {
				Vue.http.get(this.$apiurl + 'user/OP').then((response) => {
					if(response.body.ok==1)	
					{
						 console.log(response.body)
						 this.categories = response.body.categories;
						 this.problems = response.body.problems;
					}
				})
			}
	}	
</script>




<style>
	header{
		margin-bottom: 0;
	}
		.navb{
		padding:0;
		margin-top:-2.7rem;
		text-align: center;
	}
	.navb > li {
		display: inline;
		margin:1rem;
		position:relative;

	}
	#trapezoid > ul>li{
		text-align: center;
		color:white;
		font-weight: 500;
		font-size: 1.3rem;
		cursor: pointer;
	}
	#trapezoid>ul>li::before{
		content:"";
		position: absolute;
		bottom:-5px;
		border:none;
		background-color:white;
		width:100%;
		height:0.2rem;
		transform: scaleX(0);
		transition:transform 0.4s ease;
	}

	#trapezoid>ul>li:hover::before{
		transform: scaleX(1);
	}
	.body-wrapper{
		margin-top:0;
	}
	.letsee{
	}
</style>	