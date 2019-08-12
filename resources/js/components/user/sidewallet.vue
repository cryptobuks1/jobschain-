<template>
	<div>
		<div v-if="!ready||isSaving" :class="" class="page-overlay is-loading">
			<div class="spinner"><span class="sp sp1"></span><span class="sp sp2"></span><span class="sp sp3"></span></div>
		</div>
		<div v-if="ready">
			<div class="col-lg-4">
				<div class="p-4 mb-3 bg-white">
					<h3 class="h5 text-black mb-3">Wallet balance</h3>
					<p class="mb-0 font-weight-bold">Confirmed</p>
					<p class="h3 mb-4 text-primary">{{user.balance.balance}} {{user.balance.symbol}} </p>
					<p class="mb-0 font-weight-bold">Unconfirmed</p>
					<p class="mb-4"><a href="#">{{user.balance.unconfirm}} {{user.balance.symbol}}</a></p>
				</div>
				<div class="p-4 mb-3 bg-white">
					<h3 class="h5 text-black mb-3">Send JBT</h3>
					<div class="row form-group mb-2">
						<div class="col-md-12 mb-3 mb-md-0">
							<input v-model="sendForm.address" type="text" id="address" class="form-control" placeholder="Jbt Address">
						</div>
					</div>
					<div class="row form-group mb-2">
						<div class="col-md-12 mb-3 mb-md-0">
							<input v-model="sendForm.amounr" type="text" id="amount" class="form-control" placeholder="Jbt Amount">
						</div>
					</div>
					<div class="row form-group mb-5">
						<div class="col-md-12 mb-3 mb-md-0">
							<input v-model="sendForm.password" type="text" id="password" class="form-control" placeholder="Password">
						</div>
					</div>
					<p><a href="#" @click.prevent="sendCoins()" class="btn btn-primary btn-block  py-2 px-4">Send JBT</a></p>
				</div>
				<div class="p-4 mb-3 bg-white">
					<h3 class="h5 text-black mb-3">Receive JBT</h3>
					<a href="#" class="mb-0 font-weight-bold">
					<copy :display-item="user.balance.address" :copy-item="user.balance.address"></copy>
					</a>
					<div class="row d-flex justify-content-center my-4"> <img :src="user.balance.qr_link"/> </div>
					<p class="mb-4"><a @click.prevent="get_new_address()" href="#">Get New Address <i :class="isAddress?'fa-spin':''" class="fa fa-refresh"></i></a></p>
				</div>
			</div>
			<!-- Send Success -->
			<div class="modal fade" id="send-success" tabindex="-1">
				<div class="modal-dialog modal-dialog-md modal-dialog-centered">
					<div class="modal-content">
						<div class="dialog-body text-center">
							<div class="ht-20px"></div>
							<div><i class="status-icon icon-success d-inline-block text-center rounded fa fa-check"></i></div>
							<div class="ht-20px"></div>
							<h3>{{$t("wallet.send_success")}}</h3>
							<p>{{$t("wallet.send_success_desc")}}</p>
							<p v-if="last.txid">TXID: {{last.txid}}</p>
							<div class="ht-20px"></div>
							<a v-if="last.txid_link" :href="last.txid_link" target="_blank" class="btn btn-primary">{{$t("wallet.view_tx")}}</a>
							<div class="ht-10px"></div>
						</div>
					</div>
					<!-- .modal-content --> 
				</div>
				<!-- .modal-dialog --> 
			</div>
			
			<!-- send fialed Modal -->
			<div class="modal fade" id="send-failed" tabindex="-1">
				<div class="modal-dialog modal-dialog-md modal-dialog-centered">
					<div class="modal-content">
						<div class="dialog-body text-center">
							<div class="ht-20px"></div>
							<div><i class="status-icon icon-error d-inline-block text-center rounded fa fa-times"></i></div>
							<div class="ht-20px"></div>
							<h3 v-if="!queued">{{$t("wallet.send_failed")}}</h3>
							<h3 v-if="queued">{{$t("wallet.send_queued")}}</h3>
							<p>{{send_error}}</p>
							<p>{{$t("wallet.send_failed_desc")}}</p>
							<div  class="ht-20px"></div>
							<a @click.prevent="showSendModal()" href="#"  class="btn btn-primary">{{$t("wallet.send_again")}}</a>
							<div class="ht-10px"></div>
						</div>
					</div>
					<!-- .modal-content --> 
				</div>
				<!-- .modal-dialog --> 
			</div>
		</div>
	</div>
</template>
<script type="text/javascript">
import copy from './copy.vue';
export default{
	components:{
		copy:copy
	},
	/**
	 * The component's data.
	 */
	data() {
		return {
			user: {},
			symbol:"JBT",
			last: {},
			sendForm:{
                amount: "",
                address: "",
				password:""
			},
			isSaving: false,
			getAddress: false,
			ready: false,
			disabled: false,
			errors: [],
		};
		
	},
	
	computed:{
		
		sendOk(){
			var send = parseFloat(this.sendForm.amount);
			var bal = parseFloat(this.user.balance);
			return  send > 0 && bal > send;
		}

	},
	/**
	 * The component has been created by Vue.
	 */
	created() {
		axios.get('/ajax/user/').then(user =>{
			this.user = user.data;
			this.symbol =  this.user.balance.symbol;
			this.ready = true;
		});
	},
	
	
	
	methods: {
		
		showSendModal(){
			$('#send-success').modal('hide');
			$('#send-failed').modal('hide');
			$('#send-modal').modal('show');
		},
	
		get_new_address(){
			this.getAddress = true;
			this.isSaving = true;
			this.setuperror="";
			axios.post('/ajax/balance/get_new_address').then(response => {
				this.getAddress = false;
				this.isSaving = false;
				if(typeof response.data.error !== "undefined"){
					this.error(response.data.error)
					this.setuperror = response.data.error
					return ;
				}
				this.user = response.data;
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
	
		refreshWalletsInBackground(){ 
			this.refresh = true;
			axios.get('/ajax/user/').then( user =>{
				this.user = user.data;
				this.refresh = false;
			})
		},
		
		sendCoins(){
			$('#send-failed').modal('hide');
			this.isSaving = true;
			axios.post('/ajax/balance/send', {
				amount:this.sendForm.amount,
				address:this.sendForm.address,
				password:this.sendForm.password,
			}).then(response => {
				$('#send-modal').modal('hide');
				this.isSaving = false;
				if(typeof response.data.error !== "undefined"){
					this.queued = false;
					this.send_error = response.data.error
					$('#send-failed').modal('show');
					return ;
				}
				this.last = response.data;
				$('#send-success').modal('show');
				this.refreshWalletsInBackground();
			}).catch( (errors) => {
				this.isSaving = false;
			    $('#send-modal').modal('hide');
				if (errors.response) {
					this.errors = errors.response.data.errors;
					return this.error('Transaction Failed: <br>'+errors.response.data.message);
				} else if (errors.request) {
					return this.error('The Server Sent An Empty Response');
				} else {
				    return this.error('Could No Complete Your Request. Internet Connection Problems?');
				}
				return this.error('An Error Occured and Balance Not Updated');
			});
		},
	}
}
</script> 
