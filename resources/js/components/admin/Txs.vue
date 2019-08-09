<template>
	<div class="card content-area content-area-mh">
		<v-dialog/>
		<div v-if="!ready||isSaving" :class="" class="page-overlay is-loading">
			<div class="spinner"><span class="sp sp1"></span><span class="sp sp2"></span><span class="sp sp3"></span></div>
		</div>
		<div v-if="ready" class="card-innr">
			<div class="card-head has-aside">
				<h4 class="card-title">Current Deposits</h4>
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
			<table id="Tx" class="data-table dt-filter-init user-list selectable">
				<thead>
					<tr class="data-item data-head">
						<th>Date</th>
						<th>User</th>
						<th>Amount</th>
						<th class="d-none d-sm-table-cell">Txid</th>
						<th class="d-none d-sm-table-cell">Address</th>
						<th>Status</th>
					</tr>
					</tr>
				</thead>
				<tbody v-if="txs.length > 0">
					<tr v-for="tx in deposits" class="data-item">
						<td >{{dt(tx.created_at)}}</td> 
                        <td>
							<div class="d-flex align-items-center"> 
								<div class="fake-class"> 
									<span class="lead user-name">{{tx.user.name}}</span> <span class="sub user-id d-none d-md-block"> {{tx.user.email}}</span>
								</div>
							</div>
						</td>
                        <td><div class="d-flex align-items-center"> 
								<div class="fake-class"> 
									<span class="lead user-name">{{ tx.amount }}{{ tx.symbol }}</span> <br> <span class="sub user-id">CONF: <span :class="tx.confirmations > 3 ?'badge-success':'badge-danger'" class="badge badge-xs badge-dim ">{{tx.confirmations}}</span></span>
								</div>
							</div>
						</td>
						<td class="d-none d-sm-table-cell"><a :href="tx.txid_link" target="_blank">{{tx.txid_short}}</a></td>
						<td class="d-none d-sm-table-cell"><a :href="tx.address_link" target="_blank">{{tx.address_short}}</a></td>
                        <td>
							<ul class="data-vr-list">
								<li v-show="tx.status == 0">
									<div class="data-state data-state-sm data-state-pending"></div>
									PENDING
								</li>
								<li v-show="tx.status ==1">
									<div class="data-state data-state-sm data-state-approved"></div>
									COMPLETE
								</li>
								<li v-show="tx.status ==2">
									<div class="data-state data-state-sm data-state-canceled"></div>
									REJECTED
								</li>
							</ul>
						</td>
                     
					</tr>
				</tbody>
				<tbody v-else >
					<tr>
						<td colspan="8" align="center"><h3 class=" mt-5">No txs Available</h3></td>
					</tr>
				</tbody>
			</table>
		</div>
		<!-- .card-innr --> 
	</div>
</template>
<script type="text/javascript">
import $ from 'jquery';
import moment from 'moment';
export default{
	/**
	 * The component's data.
	 */
	data() {
		return {
			user: {},
			txs: [],
			txForm:{},
			isSaving: false,
			modalTitle: "",
			editing: false,
			ready: false,
			disabled: false,
			errors: [],
			filter: "",
			tfilter:false,
		};
		
	},
	
	computed:{
		
		deposits(){
			if(this.filter.length > 3 ){
				return  _.filter(this.txs, (o) =>{ 
					return o.user.email.indexOf(this.filter) !== -1 
					|| o.user.name.indexOf(this.filter) !== -1
				});
			}
			return _.take(this.txs , 20);
		}
	},


	/**
	 * The component has been created by Vue.
	 */
	created() {
		axios.get('/admin/ajax/txs/').then((txs)=>{
			this.txs = txs.data;
			this.ready = true;
		})
	},
	
	
	methods: {
		/**
		 * Delete the Tx 
		 */
		deleteTx(tx) {
			this.confirm('This process is irreversible!. Are you sure you want to Completely Destroy ' + tx.name + '?. ', () => {
				this.isSaving = true;
				axios.delete('/admin/ajax/txs/' + tx.id).then(response => {
					this.txs = response.data;
					this.isSaving = false;
				});
			});
		},
	}
}
</script>