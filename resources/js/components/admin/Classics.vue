<template>
	<div class="card content-area content-area-mh">
		<v-dialog/>
		<div v-if="!ready||isSaving" :class="" class="page-overlay is-loading">
			<div class="spinner"><span class="sp sp1"></span><span class="sp sp2"></span><span class="sp sp3"></span></div>
		</div>
		<div v-if="ready" class="card-innr">
			<div class="card-head has-aside">
				<h4 class="card-title">Classic Bitalgo</h4>
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
			<table :data="balances" :filters="filters" :currentPage.sync="currentPage" :pageSize="20" @totalPagesChanged="totalPages = $event" @totalItemsChanged="totalItems = $event" id="Tx" class="data-table dt-filter-init user-list selectable">
				<thead slot="head">
					<tr class="data-item data-head">
						<th>Select</th>
						<th>Email</th>
						<th>Amount</th>
						<th class="d-none d-sm-table-cell">Txid</th>
						<th class="d-none d-sm-table-cell">Address</th>
						<th>Status</th>
					</tr>
					</tr>
				</thead>
				<tbody slot="body" slot-scope="{displayData}" >
					<template v-if="displayData.length > 1">
					<tr v-for="tx in displayData" v-if="tx.classic" :key="tx.id" class="data-item">
						<td class="data-col dt-tnxno">
							<div class="d-flex align-items-center">
								<div class="input-item">
									<input :id="'checkbox_'+tx.classic.id" type="checkbox" v-model="ids" :value="tx.id" class="input-checkbox input-checkbox-md" ><label :for="'checkbox_'+tx.classic.id">#{{tx.classic.id}}</label>
								</div>
							</div>
						</td>
                        <td class="data-col">
							<div class="d-flex align-items-center"> 
								<div class="fake-class"> 
									<span class="lead user-name">{{tx.email}}</span> <span  class="sub user-id d-none d-md-block"> {{tx.name}}</span>
								</div>
							</div>
						</td>
                        <td class="data-col">
							<div class="d-flex align-items-center"> 
								<div class="fake-class"> 
									<span class="lead user-name">{{ tx.classic.balance }}{{ tx.classic.symbol }}</span> 
								</div>
							</div>
						</td>
						<td class="d-none d-sm-table-cell data-col"><a :href="tx.classic.txid_link" target="_blank">{{tx.classic.txid_short}}</a></td>
						<td class="d-none d-sm-table-cell data-col"><a :href="tx.classic.address_link" target="_blank">{{tx.classic.address_short}}</a></td>
                        <td class="data-col">
							<ul class="data-vr-list">
								<li v-show="tx.classic.status == 0">
									<div class="data-state data-state-sm data-state-pending"></div>
									PENDING
								</li>
								<li v-show="tx.classic.status == 0">
									<a href="#" @click.prevent="reject(tx.classic.id)" class="btn btn-sm btn-icon btn-danger">Reject</a>
								</li>
								<li v-show="tx.classic.status == 0">
									<a href="#" @click.prevent="accept(tx.classic.id)" class="btn btn-sm btn-icon btn-success">Approve</a>
								</li>
								<li v-show="tx.classic.status ==1">
									<div class="data-state data-state-sm data-state-approved"></div>
									COMPLETE
								</li>
								<li v-show="tx.classic.status ==2">
									<div class="data-state data-state-sm data-state-canceled"></div>
									REJECTED
								</li>
							</ul>
						</td>
					</tr></template>
					<tr v-else >
						<td colspan="8" align="center"><h3 class=" mt-5">No Classic ALG Available</h3></td>
					</tr>
					
				</tbody>
			</table>
		
		<v-table  id="Balance" class="data-table dt-filter-init user-list selectable">
				<thead >
					<tr class="data-item data-head">
                        <th>Account</th>
                        <th>Balance</th>
						<th>Actions</th>
					</tr>
				</thead>
				<tbody  >
					
					<tr v-else v-for="balance in displayData" class="data-item" >
						<td class="data-col dt-user">
							<div class="d-flex align-items-center"> 
								<div class="fake-class">
									<span class="lead user-name">{{balance.user.name}}</span> <span class="sub user-id"> {{balance.user.email}}<span class="badge badge-xs badge-dim badge-info ml-3">{{balance.user.status}}</span> </span> 
								</div>
							</div>
						</td>
                        <td class="data-col">
							<div class="d-flex align-items-center"> 
								<div class="fake-class">
									<span class="lead user-name">{{ balance.balance}}{{balance.symbol}}</span> 
								</div>
							</div>
						</td>
						<td class="data-col dt-status">
							<button @click.prevent="showEditModal(balance)" class="dt-status-md btn btn-outline btn-xs btn-icon btn-success mr-3">Top Up</button>
							<button @click.prevent="toggleU(balance.user)" class="dt-status-md btn btn-outline btn-xs btn-icon btn-success mr-3">{{balance.user.status?'Ban':'Enable'}} User</button>
						</td>
					</tr>
				</tbody>
				</tbody>
			</v-table>
			<smart-pagination :currentPage.sync="currentPage" :totalPages="totalPages" />
		
		
		</div>
	
		<div class="modal fade" id="passmodal" tabindex="-1">
				<div class="modal-dialog modal-dialog-md modal-dialog-centered">
					<div class="modal-content"><a href="#" class="modal-close" data-dismiss="modal" aria-label="Close"><em class="ti ti-close"></em></a>
						<div class="popup-body">
							<h4 class="popup-title">Enter Wallet Password</h4>
							<div class="input-wrap mgb-0-5x">
								<input type="password" placeholder="Wallet Password is Required" class="input-bordered" rows="3" v-model="password"/>
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
			users: [],
			ready: false,
			ids: [],
			password:'',
			tx: '',
			isSaving: false,
			processMultiple:false,
			errors: [],
			filter: "",
		};
		
	},
	
	computed:{
		xusers(){
			if(this.filter.length > 3 ){
				return  _.filter(this.users, (o) =>{ 
					return o.email.indexOf(this.filter) !== -1 
					|| o.name.indexOf(this.filter) !== -1
				});
			}
			return _.take(this.users , 100);
		}
	},


	/**
	 * The component has been created by Vue.
	 */
	created() {
		axios.get('/admin/ajax/classics/').then((classics)=>{
			this.users = classics.data;
			this.ready = true;
		})
	},
	
	
	methods: {
		/**
		 * Delete the Tx 
		 */
		reject(tx) {
			this.confirm('This process is irreversible!. Are you sure you want to Completely Reject Transaction #:' + tx + '?. ', () => {
				this.isSaving = true;
				this.post('/admin/ajax/classics/' + tx ,{status:'reject'},"Classic Bitalgo Status Rejected Successfully" )
			});
		},

		rejectAll( ) {
			if(this.ids.length < 1) return this.error('Please Select Some Rows')
			this.confirm('This process is irreversible!. Are you sure you want to Completely Reject These Transactions ?. ', () => {
				this.isSaving = true;
				this.post('/admin/ajax/classics/many',{status:'reject',ids:this.ids},"Classic Bitalgo Status Rejected Successfully" )
			});
		},
		
		accept(tx) {
			this.tx = tx;
			this.processMultiple = false;
			$('#passmodal').modal('show');
		},
		
		acceptTx() {
			if(!this.password)this.error('Please Enter Wallet Password')
			this.isSaving = true;
			this.post('/admin/ajax/classics/' + this.tx ,{status:'accept',password:this.password},"Classic Bitalgo Status Allocated Successfully" )
		},
		
		acceptAll() {
			if(this.ids.length < 1) return this.error('Please Select Some Rows')
			this.processMultiple = true;
			$('#passmodal').modal('show');
		},
		
		acceptAllTx() {
			if(!this.password) return this.error('Please Enter Wallet Password')
			if(this.ids.length < 1) return this.error('Please Select Some Rows')
			this.isSaving = true;
			this.post('/admin/ajax/classics/many',{status:'accept',password:this.password,ids:this.ids},"Classic Bitalgo Status Allocated Successfully" )
		},
		
		processTx(){
			$('#passmodal').modal('hide');
			return this.processMultiple ? this.acceptAllTx():this.acceptTx();
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
				this.classics = response.data;
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