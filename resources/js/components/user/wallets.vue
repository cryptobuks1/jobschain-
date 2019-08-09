<template>
	<div>
		<v-dialog/>
		<div v-if="!ready||isSaving" :class="" class="page-overlay is-loading">
			<div class="spinner"><span class="sp sp1"></span><span class="sp sp2"></span><span class="sp sp3"></span></div>
		</div>
		<div v-if="ready">
			<div class="row">
				<div class="main-content col-lg-8">
					<div class="d-lg-none"><a href="#" @click.prevent="showSendModal()" class="btn btn-danger btn-xl btn-between w-100 mgb-1-5x">{{$t('wallet.withdraw',{'symbol':balance.symbol})}}<em class="ti ti-arrow-right"></em></a>
						<div class="ht-10px mgb-0-5x d-lg-none d-none d-sm-block"></div>
					</div>
					<div class="content-area card">
						<div class="card-body"> 
							<h5 class="card-title card-title-sm">{{balance.address}}</h5>
							<div class="token-currency-choose">
								<div class="token-rate-wrap row d-flex justify-content-center"> <img :src="balance.qr_link" /> 
								</div>
								<!-- .row -->
							</div>
							
							<div class="row">
								<div class="col-md-8">
									<div class="copy-wrap mgb-0-5x">
										<span class="copy-feedback"></span><img class="rounded-circle" src="/logicon.png" />
										<input type="text" class="copy-address" :value="balance.address" disabled="">
										<button class="copy-trigger copy-clipboard" :data-clipboard-text="balance.address"><em class="ti ti-files"></em></button>
									</div>
								</div>
								<div class="col-md-4 d-flex flex-column justify-content-center">
									<button :disabled="getAddress" @click.prevent="get_new_address()" class="btn btn-sm btn-primary"><i :class="getAddress?'fa fa-spin fa-cirlce-o-notch':'ti ti-check'" class="mr-2"></i>{{$t('wallet.get_new_address')}}</button>
								</div>
							</div>
							<div class="ht-10px"></div>
							<ul class="nav nav-tabs nav-tabs-line" role="tablist">
								<li class="nav-item">
									<a class="nav-link active show" data-toggle="tab" href="#deposits">{{$t('wallet.deposits')}}</a>
								</li>
								<li class="nav-item">
									<a class="nav-link" data-toggle="tab" href="#withdrawals">{{$t('wallet.withdrawals')}}</a>
								</li>
							</ul>
							<div class="tab-content">
								<div  class="tab-pane fade  active show" id="deposits">
									<div class="has-aside">
										<h4>{{$t('wallet.deposits')}} {{$t('wallet.history')}}</h4>
									</div>
									<div class="ht-10px"></div>
									<div class="row justify-content-between pdb-1x">
										<div class="col-9 col-sm-6 text-left">
											<div class="filter">
												<label>
													<input type="search" v-model="dfilter" class="form-control form-control-sm" :placeholder="$t('wallet.txid')">
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
												<th>{{$t('wallet.date')}}</th>
												<th>{{$t('wallet.amount')}}</th>
												<th class="d-none d-sm-table-cell">{{$t('wallet.tx_id')}}</th>
												<th>{{$t('wallet.status')}}</th>
											</tr>
											</tr>
										</thead>
										<tbody v-if="balance.txs.length > 0">
											<tr v-for="tx in deposits" class="data-item">
												<td >{{dt(tx.created_at)}}</td> 
												<td><div class="d-flex align-items-center"> 
														<div class="fake-class"> 
															<span class="lead user-name">{{ tx.amount }}{{ tx.symbol }}</span> <br> <span class="sub user-id">CONF: <span :class="tx.confirmations > 3 ?'badge-success':'badge-danger'" class="badge badge-xs badge-dim ">{{tx.confirmations}}</span></span>
														</div>
													</div>
												</td>
												<td  class="d-none d-sm-table-cell">
													<a :href="tx.txid_link" target="_blank">{{tx.txid_short}}</a>
												</td>
												<td v-if="!tx.txid" class="d-none d-sm-table-cell">
													{{$t('wallet.uc_unavailable')}}
												</td>
												<td>
													<ul class="data-vr-list">
														<li v-show="tx.status == 0">
															<div class="data-state data-state-sm data-state-pending"></div>
															{{$t('wallet.uc_pending')}}
														</li>
														<li v-show="tx.status ==1">
															<div class="data-state data-state-sm data-state-approved"></div>
															{{$t('wallet.uc_complete')}}
														</li>
														<li v-show="tx.status ==2">
															<div class="data-state data-state-sm data-state-canceled"></div>
															{{$t('wallet.uc_rejected')}}
														</li>
													</ul>
												</td>
											</tr>
										</tbody>
										<tbody v-else >
											<tr>
												<td colspan="8" align="center"><h3 class=" mt-5">{{$t('wallet.no_data')}}</h3></td>
											</tr>
										</tbody>
									</table>
								</div>
								<div class="tab-pane fade" id="withdrawals">
									<div class="has-aside">
										<h4>{{$t('wallet.withdrawals')}} {{$t('wallet.history')}}</h4>
									</div>
									<div class="ht-10px"></div>
									<div class="row justify-content-between pdb-1x">
										<div class="col-9 col-sm-6 text-left">
											<div class="filter">
												<label>
													<input type="search" v-model="wfilter" class="form-control form-control-sm" :placeholder="$t('wallet.txid')">
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
											<th>{{$t('wallet.date')}}</th>
											<th>{{$t('wallet.amount')}}</th>
											<th class="d-none d-sm-table-cell">{{$t('wallet.tx_id')}}</th>
											<th class="d-none d-md-table-cell">{{$t('wallet.address')}}</th>
											<th>{{$t('wallet.status')}}</th>
										</tr>
									</thead>
									<tbody v-if="balance.etxs.length > 0">
										<tr v-for="etx in withdraws" class="data-item">
											<td class="data-col">{{dt(etx.created_at)}}</td>
											<td class="data-col"><span class="lead lead-btoken">{{ etx.amount}}{{ etx.symbol}}</span></td>
											<td class="data-col  d-none d-sm-table-cell" v-if="etx.txid"><a target="_blank" :href="etx.txid_link" >{{ etx.txid_short }}</a></td>
											<td class="data-col d-none d-sm-table-cell" v-else>No Tx Found</td>
											<td class="data-col d-none d-md-table-cell"><a target="_blank" :href="etx.address_link">{{ etx.address_short }}</a></td>
											<td class="data-col dt-status">
												<ul  class="data-vr-list">
													<li v-show="etx.status ==1">
														<div class="data-state data-state-sm data-state-approved"></div>
														{{$t('wallet.uc_complete')}} 
													</li>
													<li v-show="etx.status ==2">
														<div class="data-state data-state-sm data-state-progress"></div>
														{{$t('wallet.uc_queued')}} 
													</li>
													<li v-show="etx.status ==0">
														<div class="data-state data-state-sm data-state-canceled"></div>
														{{$t('wallet.uc_failed')}} 
													</li>
												</ul>
											</td>
										</tr>
									</tbody>
									<tbody v-else >
										<tr>
											<td colspan="8" align="center"><h3 class=" mt-5">{{$t('wallet.no_data')}}</h3></td>
										</tr>
									</tbody>
								</table>
								</div>
							</div>
						</div>
					</div>
				</div>
				<!-- .col -->
				<div class="aside sidebar-right col-lg-4">
					<div class="d-none d-lg-block"><a href="#" @click.prevent="showSendModal()" class="btn btn-danger btn-xl btn-between w-100">{{$t('wallet.withdraw',{symbol:balance.symbol})}} <em class="ti ti-arrow-right"></em></a>
						<div class="ht-30px"></div>
					</div>
					<div class="token-statistics card card-token height-auto">
						<div class="card-body">
							<div class="token-balance">
								<h6 class="card-sub-title">{{balance.text}} {{$t('wallet.balance')}}</h6>
								<span class="lead">{{balance.balance}} <span>{{balance.symbol}}</span></span>
							</div>
							<div class="token-balance token-balance-s2">
								<h6 class="card-sub-title">{{$t('wallet.stats')}}</h6>
								<ul class="token-balance-list">
									<li class="token-balance-sub"><span class="lead">{{balance.unconfirm}}</span><span class="sub">{{$t('wallet.uc_unconfirmed')}}</span></li>
									<li class="token-balance-sub"><span class="lead">{{balance.deposited}}</span><span class="sub">{{$t('wallet.uc_deposit')}}</span></li>
									<li class="token-balance-sub"><span class="lead">{{balance.sent}}</span><span class="sub">{{$t('wallet.uc_withdrawn')}}</span></li>
								</ul>
							</div>
						</div>
					</div>
					<div class="token-sales card">
						
						<div class="sap"></div>
						<div class="card-body">
							<div class="card-head">
								<h5 class="card-title card-title-sm">{{$t('wallet.backup_private_key')}}</h5>
							</div>
							<div class="row">
								<div class="col-md-12">
									<div class="input-wrap"> 
										<input class="input-bordered" v-model="sendForm.password" name="deposit"/>
										<span class="input-note">{{$t('wallet.enter_password')}}</span>
									</div>
								</div>
								<div class="col-md-12 mt-3"> 
									<a href="#"  @click.prevent="mnemonic()" class="btn btn-danger btn-block w-100">{{$t('wallet.backup_private_key')}}<em class="ti ti-plus ml-4"></em> 
									</a> 
								</div>
							</div>
						</div>
					</div>
				</div>
				<!-- .col -->
		    </div>
			<div class="modal fade" id="send-modal" tabindex="-1">
				<div class="modal-dialog modal-dialog-md modal-dialog-centered">
					<div class="modal-content"><a href="#" class="modal-close" data-dismiss="modal" aria-label="Close"><em class="ti ti-close"></em></a>
						<div class="dialog-body">
							<h4 class="dialog-title">{{$t('wallet.withdraw',{symbol:balance.text})}}</h4>
							<p>{{$t("wallet.tx_queued")}}</p>
							<form action="#">
								<div class="row">
									<div class="col-md-6">
										<div class="input-item input-with-label">
											<label for="wallet" class="input-item-label">{{$t("wallet.wallet_pass")}} </label>
											<div class="input-wrap">
											<input class="input-bordered" type="password" id="address" name="amount" v-model="sendForm.password" >	
											</div>
										</div>
										<!-- .input-item --> 
									</div>
									<div class="col-md-6">
										<div class="input-item input-with-label">
											<label for="amount" class="input-item-label">{{$t("wallet.amount_in",{symbol:balance.symbol})}}</label>
											<input class="input-bordered" type="text" id="amount" name="amount" v-model="sendForm.amount" >
										</div>
										<!-- .input-item --> 
									</div>
									<!-- .col --> 
								</div>
								<!-- .row -->
								<div class="input-item input-with-label">
									<label for="address" class="input-item-label">{{$t('wallet.coin_address',{symbol:balance.text})}}</label>
									
									<input class="input-bordered" type="text" id="address" name="amount" v-model="sendForm.address" >
									<span class="input-note">{{$t("wallet.enter")}}  {{$t('wallet.coin_address',{symbol:balance.text})}}</span></div>
								<!-- .input-item -->
								<div class="note note-plane note-danger"><em class="fas fa-info-circle"></em>
									<p>{{$t("wallet.fees_note")}}</p> 
								</div>
								<div class="ht-30px"></div>
								<div class="d-sm-flex justify-content-between align-items-center">
									<button @click.prevent="sendCoins()" :disabled="!sendOk" class="btn btn-primary">{{$t("wallet.send_now")}}</button>
									<div class="ht-20px d-sm-none"></div>
									<span v-show="sendOk" class="text-success"> <em class="ti ti-check-box"></em>>{{$t("wallet.ok_amount")}}</span> <span v-show="!sendOk" class="text-danger"><em class="ti ti-close"></em> {{$t("wallet.invalid_amount")}} </span> </div>
							</form>
							<!-- form --> 
						</div>
					</div>
					<!-- .modal-content --> 
				</div>
				<!-- .modal-dialog --> 
			</div>
		
			<div class="modal fade" id="pv-modal" tabindex="-1">
				<div class="modal-dialog modal-dialog-md modal-dialog-centered">
					<div class="modal-content"><a href="#" class="modal-close" data-dismiss="modal" aria-label="Close"><em class="ti ti-close"></em></a>
						<div class="dialog-body">
							<h4 class="dialog-title">Mnemonic (Xpriv)</h4>
							<p>DONOT SHARE THIS KEY WITH ANYONE</p>
							<div class="ht-10px"></div>
							<div class="input-wrap mgb-0-5x">
								<textarea disabled class="input-bordered" rows="8" v-model="emnemonic"></textarea>
							</div>
							<div class="ht-30px"></div>
							<div class="note note-plane note-light mgb-1x"><em class="fas fa-info-circle"></em>
								<p>If you lose your password Admin Can Recover your Coins using this Mnemonic.</p>
							</div>
						</div>
					</div>
					<!-- .modal-content --> 
				</div>
				<!-- .modal-dialog --> 
			</div>
			<!-- Modal End -->
			<div class="modal fade" id="send-success" tabindex="-1">
				<div class="modal-dialog modal-dialog-md modal-dialog-centered">
					<div class="modal-content">
						<div class="dialog-body text-center">
							<div class="ht-20px"></div>
							<div class="pay-status pay-status-success"><em class="ti ti-check"></em></div>
							<div class="ht-20px"></div>
							<h3>{{$t("wallet.send_success")}}</h3>
							<p>{{$t("wallet.send_success_desc")}}</p>
							<p v-if="last.txid">TXID: {{last.txid}}</p>
							<div class="ht-20px"></div>
							<a v-if="last.txid_link" :href="last.txid_link" target="_blank" class="btn btn-primary">{{$t("wallet.view_tx")}}</a>
							<a v-if="!last.txid" @click.prevent="showSendModal()" href="#"  class="btn btn-primary">{{$t("wallet.send_more")}}</a>
							<div class="ht-10px"></div>
						</div>
					</div>
					<!-- .modal-content --> 
				</div>
				<!-- .modal-dialog --> 
			</div>
			<!-- Modal End -->
			<div class="modal fade" id="send-failed" tabindex="-1">
				<div class="modal-dialog modal-dialog-md modal-dialog-centered">
					<div class="modal-content">
						<div class="dialog-body text-center">
							<div class="ht-20px"></div>
							<div class="pay-status pay-status-error"><em class="ti ti-alert"></em></div>
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
			<div class="modal fade" id="redeem-modal" tabindex="-1">
				<div class="modal-dialog modal-dialog-md modal-dialog-centered">
					<div class="modal-content"><a href="#" class="modal-close" data-dismiss="modal" aria-label="Close"><em class="ti ti-close"></em></a>
						<div class="dialog-body">
							<h4 class="dialog-title">Redeem Classic BitAlgo</h4>
							<p>Send your Classic Bitalgo to this Address </p>
							<div class="ht-10px"></div>
							<p>{{oldwallet.address}}</p>
							<div class="ht-10px"></div>
							<div class="input-wrap mgb-0-5x">
								<textarea placeholder="Enter the TXID here" class="input-bordered" rows="3" v-model="txid"></textarea>
							</div>
							<div class="ht-30px"></div>
							<div class="note note-plane note-light mgb-1x"><em class="fas fa-info-circle"></em>
								<p>BitAlgo will be added to your Wallet after Verification by our Team.</p>
							</div>
							<div class="ht-10px"></div>
							<div class="d-flex justify-contents-center">
								<a href="#" @click.prevent="redeemTxid()" class="btn btn-success">Request Exchange</a>
							</div>
						</div>
					</div>
					<!-- .modal-content --> 
				</div>
				<!-- .modal-dialog --> 
			</div>
			<a v-show="!oldwallet.show" href="#"  @click.prevent="showRedeemModal()" class="promo-trigger active"><div class="promo-trigger-img"><img src="/images/classic_bitalgo.png" alt="bitalgo"></div><div class="promo-trigger-text">Exchange Your<br>Classic BitAlgo</div></a>
			<div v-show="oldwallet.show" class="promo-content">
				<a href="#"  @click.prevent="oldwallet.show=false" class="promo-close"><em class="ti ti-close"></em></a> 
				<a @click.prevent="showRedeemModal()" href="#" class="promo-content-wrap">
				<div class="promo-content-img">
					<img src="/images/oldwallet.jpg" alt="walletbitalgo.io">
				</div>
				<div class="promo-content-text">
					<h5>You have <span>{{oldwallet.amount}}{{balance.symbol}}</span> <br>
						BitAlgo Classic</h5>
					<p>This will be Sent to Your<br>
						New Wallet Soon!</p>
				</div>
				</a>
			</div>	
		</div>
		
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
			oldwallet: {
				show:true,
				amount:'0.000',
				address:'',
				redeemId:''
			},
			txid:"",
			balance: {},
			settings: {},
			set_password:"",
			send_error:"",
			setuperror:"",
			queued:false,
			deposit_amount:0,
			symbol:"BTC",
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
			dfilter:'',
			wfilter:'',
			emnemonic:'',
		};
		
	},
	
	computed:{
		
		sendOk(){
			var send = parseFloat(this.sendForm.amount);
			var bal = parseFloat(this.balance.balance);
			return  send > 0 && bal > send;
		},
		deposits(){
			if(this.dfilter.length > 3 ){
				console.log('f-only');
				return  _.filter(this.balance.txs, (o) =>{ 
					return o.txid.indexOf(this.dfilter) !== -1 
				});
			}
			return _.take(this.balance.txs , 20);
		},
		withdraws(){
			if(this.wfilter.length > 3 ){
				return  _.filter(this.balance.etxs, (o) =>{ 
					return o.txid.indexOf(this.wfilter) !== -1 ||o.address.indexOf(this.wfilter) !== -1
				});
			}
			return _.take(this.balance.etxs , 20);
		}
	},


	/**
	 * The component has been created by Vue.
	 */
	created() {
		axios.get('/ajax/balance/').then(balance =>{
			this.balance = balance.data;
			this.symbol =  this.balance.symbol;
			this.oldwallet.show = false;
			if(typeof this.balance.user !='undefined' && this.balance.user.showclassic){
				this.oldwallet.show = true;	
				this.oldwallet.show = true;	
				this.oldwallet.amount = this.balance.user.classicbalance;	
			}
			this.symbol =  this.balance.symbol;
			this.ready = true;
		});
	},
	
	
	
	methods: {
		/**
		 * show Edit Balance Modal.
		 */
		showSendModal(){
			$('#send-success').modal('hide');
			$('#send-failed').modal('hide');
			$('#send-modal').modal('show');
		},
		
		showRedeemModal(){
			axios.post('/ajax/redemption/address').then(response => {
				this.isSaving = false;
				if(typeof response.data.error !== "undefined"){
					this.error(response.data.error)
					this.setuperror = response.data.error
					return ;
				}
				this.oldwallet.address = response.data.address;
				this.oldwallet.redeemId = response.data.id;
				this.oldwallet.show=false;
				$('#redeem-modal').modal('show');
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
		
		redeemTxid(){
			if(!this.txid)return this.error('Please Enter TXID');
			if(!this.oldwallet.redeemId) return this.error('Could Not Create Redeem Point for your Wallet, Please contact support');
			$('#redeem-modal').modal('hide');
			axios.post('/ajax/redeem',{
				id:this.oldwallet.redeemId,
				txid:this.txid
			}).then(response => {
				this.isSaving = false;
				if(typeof response.data.error !== "undefined"){
					this.error(response.data.error)
					this.setuperror = response.data.error
					return ;
				}
				this.oldwallet.address = response.data.address;
				this.oldwallet.redeemId = response.data.id;
				this.oldwallet.show=false;
				
				this.success('Exchange Request Recieved and will be processed shortly')
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
				this.balance = response.data;
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
		setup_wallet(){
			if(! /[A-Z]+/.test(this.set_password)) { 
				this.setuperror = this.$t('wallet.use_capital_letter')
				return false;
			}
			if(! /[a-z]+/.test(this.set_password)) { 
				this.setuperror = this.$t('wallet.use_small_letter')
				return false;
			}
			if(! /[0-9]+/.test(this.set_password)) { 
				this.setuperror = this.$t('wallet.use_number')
				return false;
			}
			if(! /[\W]+/.test(this.set_password)) { 
				this.setuperror = this.$t('wallet.use_symbol')
				return false;
			}
			if(this.set_password.length < 8) { 
				this.setuperror = this.$t('wallet.use_long')
				return false;
			}
			this.isSaving = true;
			this.setuperror="";
			axios.post('/ajax/balance/setup', { 
				password:this.set_password,
			}).then(response => {
				this.isSaving = false;
				if(typeof response.data.error !== "undefined"){
					this.error(response.data.error)
					this.setuperror = response.data.error
					return ;
				}
				this.success(this.$t('wallet.setup_complete'));
				this.balance = response.data;
			}).catch( (errors) => {
				this.isSaving = false;
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
			axios.get('/ajax/balance/').then((balance)=>{
				this.balance = balance.data;
			})
		},
		
		mnemonic(){
			$('#pv-modal').modal('hide');
			this.isSaving = true;
			axios.post('/ajax/balance/mnemonic', {
				password:this.sendForm.password,
			}).then(response => {
				this.sendForm.password ="";
				this.isSaving = false;
				if(typeof response.data.error !== "undefined"){
					this.error(response.data.error)
					return ;
				}
				this.emnemonic = response.data;
				$('#pv-modal').modal('show');
			}).catch( (errors) => {
				$('#pv-modal').modal('hide');
				this.isSaving = false;
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
