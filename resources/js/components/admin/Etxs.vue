<template>
	<div class="card content-area content-area-mh">
		<v-dialog/>
		<div v-if="!ready||isSaving" :class="" class="page-overlay is-loading">
			<div class="spinner"><span class="sp sp1"></span><span class="sp sp2"></span><span class="sp sp3"></span></div>
		</div>
		<div v-if="ready" class="card-innr">
			<div class="card-head has-aside">
				<h4 class="card-title">Current Withdrawals </h4>
				
			</div>
			<div class="gaps-1x"></div>
			<div v-for="error in errors" class="alert alert-danger alert-dismissible fade show">{{error}}
				<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
			</div>
			<div v-if="error.length > 0" class="gaps-1x"></div>
			<div class="row justify-content-between pdb-1x">
				<div class="col-9 col-sm-6 text-left">
					<div class="filter">
						<label>
							<input type="search" v-model="filter" class="form-control form-control-sm" placeholder="Type in to Search">
						</label>
					</div>
				</div>
				<div class="col-3 text-right">
					<div class="relative d-inline-block"></div>
				</div>
			</div>
			<table id="Etx" class="data-table dt-filter-init user-list selectable">
				<thead>
					<tr class="data-item data-head">
						<th>Date</th>
						<th>User</th>
						<th>Amount</th>
						<th class="d-none d-sm-table-cell">Txid</th>
						<th class="d-none d-md-table-cell">Address</th>
						<th>Actions</th>
					</tr>
				</thead>
				<tbody v-if="etxs.length > 0">
					
					<tr v-for="etx in withdraws" class="data-item">
						<td >{{dt(etx.created_at)}}</td>
						<td class="data-col"><div class="d-flex align-items-center">
								<div class="fake-class"> <span class="lead user-name">{{etx.user.name}}</span> <span class="sub user-id"> {{etx.user.email}}  </span> </div>
							</div></td>
						<td><span class="lead lead-btoken">{{ etx.amount}}{{ etx.symbol}}</span></td>
						<td class="d-none d-sm-table-cell" v-if="etx.txid"><a target="_blank" :href="etx.txid_link" >{{ etx.txid_short }}</a></td>
						<td class="d-none d-sm-table-cell" v-else>No Tx Found</td>
						<td class="d-none d-md-table-cell"><a target="_blank" :href="etx.address_link">{{ etx.address_short }}</a></td>
						<td class="data-col dt-status">
						
							<ul class="data-vr-list">
								<li v-show="etx.status ==1">
									<div class="data-state data-state-sm data-state-approved"></div>
									COMPLETE </li>
								<li v-show="etx.status ==2">
									<div class="data-state data-state-sm data-state-canceled"></div>
									FAILED </li>
							</ul></td>
					</tr>
				</tbody>
				<tbody v-else >
					<tr>
						<td colspan="8" align="center"><h3 class=" mt-5">No etxs Available</h3></td>
					</tr>
				</tbody>
			</table>
		</div>
		
		<!-- .card-innr --> 
	</div>
</template>
<script type="text/javascript">
//import 'vue-datetime/dist/vue-datetime.css'
import $ from 'jquery';
import moment from 'moment';
//import Select2 from 'v-select2-component';
//import { Datetime } from 'vue-datetime';
export default{
	/**
	 * The component's data.
	 */
	data() {
		return {
			user: {},
			etxs: [],
			etxForm:{
				id:null,
                user:{},
                response: "",
                symbol: "",
                txid: "",
                address: "",
                account: "",
                amount: "",
                status: "",
			},
			isSaving: false,
			modalTitle: "",
			editing: false,
			filter: "",
			ready: false,
			disabled: false,
			errors: [],
		};
	},
	computed:{
		
		withdraws(){
			if(this.filter.length > 3  ){
				console.log('f-only');
				return  _.filter(this.etxs, (o) =>{ 
					return o.user.email.indexOf(this.filter) !== -1 
					||o.user.name.indexOf(this.filter) !== -1
				});
			}
			return _.take(this.etxs , 20);
		}
	},

	/**
	 * The component has been created by Vue.
	 */
	created() {
		axios.get('/admin/ajax/etxs/').then((etxs)=>{
			this.etxs = etxs.data;
			this.ready = true;
		})
	},
	
	
	methods: {
		/**
		 * Delete the Etx 
		 */
		deleteEtx(etx) {
			this.confirm('This process is irreversible!. Are you sure you want to Completely Destroy' + etx.txid + '?. ', () => {
				this.isSaving = true;
				axios.delete('/admin/ajax/etxs/' + etx.id).then(response => {
					this.etxs = response.data;
					this.isSaving = false;
				});
			});
		},
	}
}
</script>