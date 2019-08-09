<template>
	<div class="card content-area content-area-mh">
		<v-dialog/>
		<div v-if="!ready||isSaving" :class="" class="page-overlay is-loading">
			<div class="spinner"><span class="sp sp1"></span><span class="sp sp2"></span><span class="sp sp3"></span></div>
		</div>
		<div v-if="ready" class="card-innr">
			<div class="card-head has-aside">
				<h4 class="card-title">Exchange Bitalgo</h4>
			</div>
			<div class="gaps-1x"></div>
			<div v-for="error in errors" class="alert alert-danger alert-dismissible fade show">{{error}}
				<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
			</div>
			<div v-if="error.length > 0" class="gaps-1x"></div>
			<div class="row justify-content-between pdb-1x">
				<div class="col-md-6 text-left">
					<div class="filter">
						<label>
							<input type="search" v-model="filter" class="form-control form-control-sm" placeholder="Type in to Search">
						</label>
					</div>
				</div>
				<div class="col-md-6 text-right">
					<a href="#" @click.prevent="rejectAll()" class="btn btn-sm btn-danger">Reject {{ids.length}}</a>
					<a href="#" @click.prevent="acceptAll()" class="btn btn-sm btn-success">Approve {{ids.length}}</a>
				</div>
			</div>
			<table id="Tx" class="data-table dt-filter-init user-list selectable">
				<thead>
					<tr class="data-item data-head">
						<th>Select</th>
						<th>Email</th>
						<th>Amount</th>
						<th class="d-none d-sm-table-cell">User Txid</th>
						<th class="d-none d-sm-table-cell">Admin Txid</th>
						<th>Status</th>
					</tr>
					</tr>
				</thead>
				<tbody v-if="redeems.length > 0">
					<tr v-for="tx in xredeems" class="data-item">
						<td class="data-col">
							<div class="input-item">
								<input type="checkbox" :if="'checkbox_'+tx.id" v-model="ids" :value="tx.id" class="input-checkbox input-checkbox-md" ><label :for="'checkbox_'+tx.id">#{{tx.id}}</label>
							</div>
						</td>
                        <td class="data-col">
							<div class="d-flex align-items-center"> 
								<div class="fake-class"> 
									<span class="lead user-name">{{tx.user.email}}</span> <span v-if="tx.user.name" class="sub user-id d-none d-md-block"> {{tx.user.name}}</span>
								</div>
							</div>
						</td>
                        <td class="data-col">
							<div class="d-flex align-items-center"> 
								<div class="fake-class"> 
									<span class="lead user-name">{{ tx.amount }}{{ tx.symbol }}</span> 
								</div>
							</div>
						</td>
						<td class="d-none d-sm-table-cell data-col"><a :href="tx.txid_link" target="_blank">{{tx.txid_short}}</a></td>
						<td class="d-none d-sm-table-cell data-col"><a :href="tx.xtxid_link" target="_blank">{{tx.xtxid_short}}</a>
						</td>
						<td class="data-col">
							<ul class="data-vr-list">
								<li v-show="tx.status == 1">
									<div class="data-state data-state-sm data-state-pending"></div>
									PENDING
								</li>
								<li v-show="tx.status == 1">
									<a href="#" @click.prevent="reject(tx.id)" class="btn btn-sm btn-danger btn-icon">Reject</a>
								</li>
								<li v-show="tx.status == 1">
									<a href="#" @click.prevent="accept(tx.id)" class="btn btn-sm btn-success btn-icon">Approve</a>
								</li>
								<li v-show="tx.status ==2">
									<div class="data-state data-state-sm data-state-approved"></div>
									COMPLETE
								</li>
								<li v-show="tx.status ==3">
									<div class="data-state data-state-sm data-state-canceled"></div>
									REJECTED
								</li>
							</ul>
						</td>
					</tr>
				</tbody>
				<tbody v-else >
					<tr>
						<td colspan="8" align="center"><h3 class=" mt-5">No Classic ALG Exchange Request Available</h3></td>
					</tr>
				</tbody>
			</table>
		</div>
	
		<div class="modal fade" id="passmodal" tabindex="-1">
				<div class="modal-dialog modal-dialog-md modal-dialog-centered">
					<div class="modal-content"><a href="#" class="modal-close" data-dismiss="modal" aria-label="Close"><em class="ti ti-close"></em></a>
						<div class="popup-body">
							<template v-if="status"><div class="gaps-1x"></div>
							<p>Your Wallet Password.</p>
							<div class="input-wrap mgb-0-5x">
								<input type="password" placeholder="Wallet Password is Required" class="input-bordered" rows="3" v-model="password"/>
							</div></template>
							<div class="gaps-1x"></div>
							<p>Message To user.</p>
							<div class="gaps-1x"></div>
							<div class="input-wrap mgb-0-5x">
								<textarea placeholder="Enter A Message for The User. " class="input-bordered" rows="3" v-model="message"></textarea>
							</div>
							
							<div class="gaps-3x"></div>
							<div class="note note-plane note-light mgb-1x"><em class="fas fa-info-circle"></em>
								<p>This Process is Irreversable, Bitalgo will be sent immediately!.</p>
							</div>
							<div class="gaps-1x"></div>
							<div class="d-flex justify-contents-center">
								<a href="#" @click.prevent="processTx()" class="btn btn-success">Complete Exchange</a>
							</div>
						</div>
					</div>
					<!-- .modal-content --> 
				</div>
				<!-- .modal-dialog --> 
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
			redeems: [],
			ids: [],
			password:'',
			status:'',
			tx: '',
			isSaving: false,
			errors: [],
			filter: "",
			message: "",
			ready: false,
		};
		
	},
	
	computed:{
		xredeems(){
			if(this.filter.length > 3 ){
				return  _.filter(this.redeems, (o) =>{ 
					return o.user.email.indexOf(this.filter) !== -1 
					|| o.user.name.indexOf(this.filter) !== -1
				});
			}
			return _.take(this.redeems , 100);
		}
	},


	/**
	 * The component has been created by Vue.
	 */
	created() {
		axios.get('/admin/ajax/redeems/').then((redeems)=>{
			this.redeems = redeems.data;
			this.ready = true;
		})
	},
	
	
	methods: {
		/**
		 * Delete the Tx 
		 */
		
		reject(tx) {
			this.status = false;
			this.processMultiple = false;
			this.tx = tx;
			$('#passmodal').modal('show');
		},
		rejectAll() {
			if(this.ids.length < 1) return this.error('Please Select Some Rows');
			this.status = false;
			this.processMultiple = true;
			$('#passmodal').modal('show');
		},
		rejectTx() {
			this.confirm('This process is irreversible!. Are you sure you want to Completely Reject Transaction #:' + this.tx + '?. ', () => {
				this.isSaving = true;
				this.post('/admin/ajax/redeems/' + this.tx ,{status:'reject',message:this.message,password:this.password},"Classic Bitalgo Status Rejected Successfully" )
			});
		},

		rejectAllTx( ) {
			if(this.ids.length < 1) return this.error('Please Select Some Rows')
			this.confirm('This process is irreversible!. Are you sure you want to Completely Reject These Transactions ?. ', () => {
				this.isSaving = true;
				this.post('/admin/ajax/redeems/many',{status:'reject',ids:this.ids,message:this.message,password:this.password},"Classic Bitalgo Status Rejected Successfully" )
			});
		},
		
		accept(tx) {
			this.status = true;
			this.processMultiple = false;
			this.tx = tx;
			$('#passmodal').modal('show');
		},
		acceptAll() {
			this.status = true;
			this.processMultiple = true
			if(this.ids.length < 1) return this.error('Please Select Some Rows')
			$('#passmodal').modal('show');
		},
		
		acceptTx() {
			if(!this.password)this.error('Please Enter Wallet Password')
			this.isSaving = true;
			this.post('/admin/ajax/redeems/' + this.tx ,{status:'accept',password:this.password,message:this.message},"Classic Bitalgo Status Allocated Successfully" )
		},
		
		acceptAllTx() {
			if(!this.password) return this.error('Please Enter Wallet Password')
			if(this.ids.length < 1) return this.error('Please Select Some Rows')
			this.isSaving = true;
			this.post('/admin/ajax/redeems/many',{status:'accept',password:this.password,ids:this.ids,message:this.message},"Classic Bitalgo Status Allocated Successfully" )
		},
		
		processTx(){
			$('#passmodal').modal('hide');
			if(this.status){
				return this.processMultiple ? this.acceptAllTx():this.acceptTx();
			}else{
				return this.processMultiple ? this.rejectAllTx():this.rejectTx();
			}
			
		},
		
		post(url, data, message ){
			axios.post(url, data).then(response => {
				this.isSaving = false;
				this.ids=[];
				if(typeof response.data.error !== "undefined"){
					this.error(response.data.error)
					this.setuperror = response.data.error
					return ;
				}
				this.redeems = response.data;
				this.success(message)
			}).catch( (errors) => {
				this.isSaving = false;
				this.getAddress = false;
				if (errors.response) {
					this.errors = errors.response.data.errors;
					return this.error(errors.response.data.message);
				} else if (errors.request) {
					return this.error(this.$t('wallet.empty_error'));
				} else {
				    return this.error(this.$t('wallet.connection_error'));
				}
				return this.error(this.$t('wallet.unknown_error'));
			});
		},
	}
}
</script>