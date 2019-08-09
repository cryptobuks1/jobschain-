<template>
	<div class="card content-area content-area-mh">
		<v-dialog/>
		<div v-if="!ready||isSaving" :class="" class="page-overlay is-loading">
			<div class="spinner"><span class="sp sp1"></span><span class="sp sp2"></span><span class="sp sp3"></span></div>
		</div>
		<div v-if="ready" class="card-innr">
			<div class="card-head has-aside">
				<h4 class="card-title">Current Balances </h4>
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
							<input type="search" v-model="filters.name.value" class="form-control form-control-sm" placeholder="Filter Users By Email, Name, Account or Symbol">
						</label>
					</div>
				</div>
				<div class="col-3 text-right">
					<div class="relative d-inline-block"></div>
				</div>
			</div>
			<v-table :data="balances" :filters="filters" :currentPage.sync="currentPage" :pageSize="20" @totalPagesChanged="totalPages = $event" @totalItemsChanged="totalItems = $event" id="Balance" class="data-table dt-filter-init user-list selectable">
				<thead slot="head">
					<tr class="data-item data-head">
                        <th>Account</th>
                        <th>Balance</th>
						<th>Actions</th>
					</tr>
				</thead>
				<tbody  slot="body" slot-scope="{displayData}">
					<tr v-if="displayData.length < 1"  >
						<td colspan="8" align="center"><h3 class=" mt-5">No balances Available</h3></td>
					</tr>
					<tr v-else v-for="balance in displayData" class="data-item" :key="balance.id">
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
		<div class="modal fade" id="item-modal" tabindex="-1">
			<div class="modal-dialog modal-dialog-md modal-dialog-centered">
				<div class="modal-content"> <a href="#" class="modal-close" data-dismiss="modal" aria-label="Close"><em class="ti ti-close"></em></a>
					<div class="popup-body popup-body-md">
						<h3 class="popup-title">{{modalTitle}}</h3>
						<form class="adduser-form validate-modern" autocomplete="false">
							<div>
								<div class="row">
									<div class="col-md-6">
										<div class="input-item">
											 <label for="user_id" class="control-label">User Id</label>
											<div class="input-wrap">
												<div class="input-bordered">{{balanceForm.user.name}}</div>
											</div>
											<span class="input-note">Email:  {{balanceForm.user.email}}</span>
										</div>
									</div>
									<div class="col-md-6">
										<div class="input-item">
											 <label for="balances" class="control-label">{{balanceForm.symbol}} Balance</label>
											<div class="input-wrap">
												<div class="input-bordered">{{balanceForm.balance}}{{balanceForm.symbol}}</div>
											</div>
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-md-4">
										<div class="input-item">
											 <label for="topup" class="control-label">Top Up Amount</label>
											<div class="input-wrap">
												<input class="input-bordered" :disabled="disabled" v-model="balanceForm.topup " name="topup" type="text" id="topup"  >
											</div>
											<span class="input-note">In {{balanceForm.symbol}}</span>
										</div>
									</div>
								
									<div class="col-md-8">
										<div class="input-item">
											 <label for="password" class="control-label">Your Wallet Password</label>
											<div class="input-wrap">
												<input class="input-bordered" :disabled="disabled" v-model="balanceForm.password" name="pasword" type="password" id="password"  >
											</div>
											<span class="input-note">Enter Your Wallet Password </span>
										</div>
									</div>
								</div>
								<div class="gaps-1-5x"> </div>
								<div class="d-flex"> 
									<input v-show="!disabled"  @click.prevent="topUp('balance')" class="btn btn-secondary mr-5" type="submit" value="Topup to Balance">
								</div>
							</div>
						</form>
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
export default{
	/**
	 * The component's data.
	 */
	data() {
		return {
			user: {},
			balances: [],
			currentPage: 1,
    		totalPages: 0,
    		totalItems: 0,
			filters: {
			  name: { value: '', keys: ['user.email','user.name'] }
			},
			balanceForm:{
				id:null,
                user: {},
                balance: "",
                topup: 0,
                password: "",
                symbol: "",
			},
			isSaving: false,
			modalTitle: "",
			editing: false,
			ready: false,
			disabled: false,
			errors: [],
			filter: "",
		};
		
	},


	/**
	 * The component has been created by Vue.
	 */
	created() {
		axios.get('admin/ajax/balances').then((balances)=>{
			this.balances = balances.data;
			this.ready = true;
		})
	},
	
	computed:{
		xbalances(){
			if(this.filter.length > 2){
				return  _.filter(this.balances, (o) =>{ 
					var sym = o.symbol.toLowerCase();
					var lcfilter = this.filter.toLowerCase();
					return (o.user.email.indexOf(this.filter) !== -1
							||o.user.name.indexOf(this.filter) !== -1 
							||sym.indexOf(lcfilter) !== -1
						   );
				});
			}
			return _.take(this.balances , 25);
		}
	},
	
	methods: {
	
		/**
		 * show Edit Balance Modal.
		 */
		showEditModal(balance){
			this.setForm(balance);
			this.disabled=false;
			this.editing=true;
			this.modalTitle = balance.symbol + " Topup: # "+balance.user.name
			$('#item-modal').modal('show');
		},
		
		setForm(balance){
			this.balanceForm = {
				id:balance.id,
                user:balance.user,
                balance:balance.balance,
                topup:'0.0000000',
                password:"",
                symbol:balance.symbol
			};
		},
		emptyForm(){
			this.balanceForm = {
				id:null,
                user: {},
                balance: "",
                topup: 0,
                password:"",
                symbol: "",
			};
		},
		
		topUp(type){
			this.isSaving = true;
			axios.post('/admin/ajax/balances/'+this.balanceForm.id, {
				topup:this.balanceForm.topup,
				password:this.balanceForm.password
			}).then(response => {
				$('#item-modal').modal('hide');
				this.isSaving = false;
				if(typeof response.data.error !== "undefined"){
					return this.error(response.data.error);
				}
				this.balances = response.data;
			}).catch( (errors) => {
			    $('#item-modal').modal('hide');
				this.isSaving = false;
				if (errors.response) {
					this.errors = errors.response.data.errors;
					return this.error('An Error Occured and Balance Not Updated <br>'+errors.response.data.message);
				} else if (errors.request) {
					return this.error('The Server Sent An Empty Response');
				} else {
				    return this.error('Could No Complete Your Request. Internet Connection Problems?');
				}
				return this.error('An Error Occured and Balance Not Updated');
			});
		},
		toggleU(user){
			this.isSaving = true;
			axios.post('/admin/ajax/toggle/'+user.id).then(response => {
				this.isSaving = false;
				this.balances = response.data;
				return this.success('User status Toggled Successfully');
			}).catch( (errors) => {
			    $('#item-modal').modal('hide');
				this.isSaving = false;
				if (errors.response) {
					this.errors = errors.response.data.errors;
					return this.error('An Error Occured :<br>'+errors.response.data.message);
				} else if (errors.request) {
					return this.error('The Server Sent An Empty Response');
				} else {
				    return this.error('Could No Complete Your Request. Internet Connection Problems?');
				}
				return this.error('An Error Occured and User Not Updated');
			});
		}
	}
}
</script>