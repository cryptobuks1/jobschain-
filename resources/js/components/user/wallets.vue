<template>
	<div>
		<v-dialog/>
		<main v-if="!ready" id="content" class="bg-light" role="main">
			<div class="container">
				<div class="d-flex justify-content-between pt-5 pb-4"></div>
			</div>
			<div class="container mt-5 mb-4">
				<div class="mb-4">
					<div class="spinner"><span class="sp sp1"></span><span class="sp sp2"></span><span class="sp sp3"></span></div>
				</div>
			</div>
		</main>
		<div v-if="ready">
			<main id="content" class="bg-light" role="main">
				<div class="container">
					<div class="d-flex justify-content-between pt-5 pb-4"> </div>
				</div>
				<div class="container mt-5 mb-4">
					<div class="mb-4">
						<div class="card header-bg">
							<div class="card-body px-sm-4 pb-sm-4 ">
								<form method="get" action="/search"  autocomplete="off" spellcheck="false">
									<div class="d-none d-sm-flex align-items-baseline">
										<h1 class="h5 text-white">Search The JobChain</h1>
									</div>
									<div class="input-group input-group-main">
										<input v-model="filter" id="search" type="text" class="form-control py-3 mb-0" placeholder="Enter / Txhash / Block Hash / Address"  name="q" >
										<div class="input-group-append">
											<button class="btn btn-primary" type="submit"> <i class="fa fa-search d-inline-block d-sm-none"></i><span class="d-none d-sm-inline-block"><i class="fa fa-search"></i> Find All</span> </button>
										</div>
									</div>
								</form>
							</div>
						</div>
					</div>
					<div class="card mb-4">
						<div class="card-body">
							<div class="row">
								<div class="col-lg-6">
									<h1> Balance <span class="text-primary">{{user.balance.balance}} {{user.balance.symbol}}</span></h1>
									<span>Unconfirmed : {{user.balance.unconfirm}} {{user.balance.symbol}} <span class="text-primary">Address</span>:
									<copy :display-text="user.balance.address"  :copy-item="user.balance.address"></copy>
									</span> </div>
								<div class="col-lg-6"><span class="mt-2 float-right"><a href="#" class="mr-3 btn btn-primary" @click.prevent="showQrModal()" ><i class="fa fa-qrcode"></i>Qrcode</a><a href="#" @click.prevent="get_new_address()"  class="mr-3 btn btn-primary"><i :class="getAddress?'fa-refresh fa-spin':'fa-bank'" class="fa"></i>New Address</a><a href="#" @click.prevent="showSendModal()" class="btn btn-primary btn-outline-primary"><i class="fa fa-angle-right"></i>Send JBT</a></span></div>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-lg-6 mb-4 mb-lg-0">
							<div class="card h-100">
								<div class="card-header d-flex justify-content-between py-1">
									<ul class="nav nav-custom nav-borderless nav_tabs1" id="nav_tabs" role="tablist">
										<li class="nav-item">
											<a @click.prevent="jobTab='jobs'"  href="#" :class="jobTab=='jobs'?'btn-primary':'btn-outline-primary btn-outline'" class="mt-1 border-0 rounded-0 btn-lg btn align-centre" >Jobs</a>
										</li>
										<li class="nav-item"> 
											<a @click.prevent="jobTab='cvs'" href="#" class="mt-1 border-0 rounded-0 btn-lg btn align-centre" :class="jobTab=='cvs'?'btn-primary':'btn-outline-primary btn-outline'" >Cvs</a> 
										</li>
										<li class="nav-item">
											<a @click.prevent="jobTab='txs'" href="#" class="mt-1 border-0 rounded-0 btn-lg btn align-centre" :class="jobTab=='txs'?'btn-primary':'btn-outline-primary btn-outline'" >Tx In</a>
										</li>
										<li class="nav-item"> 
											<a @click.prevent="jobTab='etxs'" href="#" class="mt-1 border-0 rounded-0 btn-lg btn align-centre" :class="jobTab=='etxs'?'btn-primary':'btn-outline-primary btn-outline'" >Tx Out</a> 
										</li>
									</ul>
								</div>
								<div v-show="jobTab == 'jobs'" class="js-scrollbar card-body overflow-hidden" style="height: 400px;">
									<div v-if="user.jobs.length < 1" class="row">
										<div class="col-sm-12">
											<h5> You Don't have Any Jobs Posted Yet !</h5>
										</div>
									</div>
									<template v-for="job in user.jobs">
										<div class='row'>
											<div class='col-sm-4'>
												<div class='media align-items-sm-center mr-4 mb-1 mb-sm-0'>
													<div class='d-none d-sm-flex mr-2'><span :class="active==job?'btn-primary':' btn-soft-secondary'" class='btn btn-icon'><span :class="active==job?'':'text-dark'" class='btn-icon__inner'>Job</span></span></div>
													<div class='media-body'><span class='d-inline-block d-sm-none'></span> <a class="hash-tag hash-tag--xs hash-tag-xs-down--md text-truncate" href="#" @click.prevent="showJobModal(job)">{{job.title}}</a><span class='d-sm-block small text-secondary ml-1 ml-sm-0 text-nowrap' data-countdown='74000'>
														<timeago :datetime="job.created_at" :auto-update="5"></timeago>
														</span></span></div>
												</div>
											</div>
											<div class='col-sm-8'>
												<div class='d-flex justify-content-between'>
													<div class='text-nowrap'><span class='d-block mb-1 mb-sm-0'>Salary <a class='hash-tag text-truncate' :href="job.address_link">{{job.salary}}</a></span><span class="small text-secondary">{{job.address}}</span></div>
													<div class="d-flex flex-column justify-content-between" ><a href="#" @click.prevent="showJobModal(job)"  class='j-badge j-badge--xs j-badge--badge-in j-badge--secondary text-center text-nowrap' title='View Detail'>View Detail</a><a href="#" @click.prevent="active=job" class="small text-primary text-right mr-1" >{{job.msgs.length}} Msgs <i class="fa fa-chevron-circle-right"></i></a></div>
												</div>
											</div>
										</div>
										<hr class='hr-space'>
									</template>
								</div>
								<div v-show="jobTab=='cvs'" class="js-scrollbar card-body overflow-hidden" style="height: 400px;">
									<div v-if="user.cvs.length < 1" class="row">
										<div class="col-sm-12">
											<h5> You Dont have Any CV Added Yet !</h5>
										</div>
									</div>
									<template v-for="cv in user.cvs">
										<div class='row'>
											<div class='col-sm-4'>
												<div class='media align-items-sm-center mr-4 mb-1 mb-sm-0'>
													<div class='d-none d-sm-flex mr-2'><span :class="active==cv?'btn-primary':' btn-soft-secondary'" class='btn btn-icon'><span :class="active==cv?'':'text-dark'" class='btn-icon__inner'>Cv</span></span></div>
													<div class='media-body'><span class='d-inline-block d-sm-none'></span> <a class="hash-tag hash-tag--xs hash-tag-xs-down--md text-truncate" href="#" @click.prevent="showCvModal(cv)">{{cv.title}}</a><span class='d-sm-block small text-secondary ml-1 ml-sm-0 text-nowrap'>
														<timeago :datetime="cv.created_at" :auto-update="5"></timeago>
														</span></span></div>
												</div>
											</div>
											<div class='col-sm-8'>
												<div class='d-flex justify-content-between'>
													<div class='text-nowrap'><span class='d-block mb-1 mb-sm-0'>Salary <a class='hash-tag text-truncate' :href="cv.address_link">{{cv.salary}}</a></span><span class="small text-secondary">{{cv.address}}</span></div>
													<div class="d-flex flex-column justify-content-between" ><a href="#" @click.prevent="showCvModal(cv)" class='j-badge j-badge--xs j-badge--badge-in j-badge--secondary text-center text-nowrap' title='View Detail'>View Detail</a><a href="#" @click.prevent="active=cv" class="small text-primary text-right mr-1" >{{cv.msgs.length}} Msgs <i class="fa fa-chevron-circle-right"></i></a></div>
												</div>
											</div>
										</div>
										<hr class='hr-space'>
									</template>
								</div>
								<div v-show="jobTab=='txs'" class="js-scrollbar card-body overflow-hidden" style="height: 400px;">
									<div v-if="user.txs.length < 1" class="row">
										<div class="col-sm-12">
											<h5> No Deposits Found !</h5>
										</div>
									</div>
									<template v-for="txs in user.txs">
										<div class='row'>
											<div class='col-sm-4'>
												<div class='media align-items-sm-center mr-4 mb-1 mb-sm-0'>
													<div class='d-none d-sm-flex mr-2'><span class='btn btn-icon btn-soft-secondary rounded-circle'><span class='btn-icon__inner text-dark'>Tx</span></span></div>
													<div class='media-body'><span class='d-inline-block d-sm-none mr-1'>TX#</span><a class='hash-tag hash-tag--xs hash-tag-xs-down--md text-truncate' href="tx.txid_link">{{tx.txid}}</a><span class='d-none d-sm-block small text-secondary'>
														<timeago :datetime="tx.created_at" :auto-update="5"></timeago>
														</span></div>
												</div>
											</div>
											<div class='col-sm-8'>
												<div class='d-sm-flex justify-content-between'>
													<div class='text-nowrap mr-4 mb-1 mb-sm-0'><span>From <a class='hash-tag text-truncate' :href="tx.address_link">{{tx.address}}</a></span><span class='d-sm-block'>Confirmations <a :href="tx.txid_link" class='hash-tag text-truncate'>{{tx.confirmations}}</a></span></div>
													<div><span class='j-badge j-badge--xs j-badge--badge-in j-badge--secondary text-center text-nowrap' title='Amount'>{{tx.amount}}{{tx.symbol}}</span></div>
												</div>
											</div>
										</div>
										<hr class='hr-space'>
									</template>
								</div>
								<div v-show="jobTab=='etxs'" class="js-scrollbar card-body overflow-hidden" style="height: 400px;">
									<div v-if="user.etxs.length < 1" class="row">
										<div class="col-sm-12">
											<h5> No Withdwals Found !</h5>
										</div>
									</div>
									<template v-for="txs in user.etxs">
										<div class='row'>
											<div class='col-sm-4'>
												<div class='media align-items-sm-center mr-4 mb-1 mb-sm-0'>
													<div class='d-none d-sm-flex mr-2'><span class='btn btn-icon btn-soft-secondary rounded-circle'><span class='btn-icon__inner text-dark'>TxO</span></span></div>
													<div class='media-body'><span class='d-inline-block d-sm-none mr-1'>TX#</span><a class='hash-tag hash-tag--xs hash-tag-xs-down--md text-truncate' href="tx.txid_link">{{tx.txid}}</a><span class='d-none d-sm-block small text-secondary'>
														<timeago :datetime="tx.created_at" :auto-update="5"></timeago>
														</span></div>
												</div>
											</div>
											<div class='col-sm-8'>
												<div class='d-sm-flex justify-content-between'>
													<div class='text-nowrap mr-4 mb-1 mb-sm-0'><span>To<a class='hash-tag text-truncate' :href="tx.address_link">{{tx.address}}</a></span><span class='d-sm-block'>Confirmations <a :href="tx.txid_link" class='hash-tag text-truncate'>{{tx.confirmations}}</a></span></div>
													<div><span class='j-badge j-badge--xs j-badge--badge-in j-badge--secondary text-center text-nowrap' title='Amount'>{{tx.amount}}{{tx.symbol}}</span></div>
												</div>
											</div>
										</div>
										<hr class='hr-space'>
									</template>
								</div>
								<div v-if="jobTab=='jobs'||jobTab=='cvs'"  class="card-footer"> <a  class="btn btn-xs btn-block btn-primary" :href="'/'+jobTab">Browse other {{jobTab=='jobs'?'Jobs':'Cvs'}}</a> </div>
								<div v-if="jobTab=='txs'||jobTab=='etxs'"  class="card-footer"> <a  class="btn btn-xs btn-block btn-primary" :href="user.balance.explorer">Block Explorer</a> 
								</div>
							</div>
						</div>
						<div class="col-lg-6">
							<div class="card h-100">
								<div class="card-header d-flex justify-content-between align-items-center">
									
									<h4 v-if="active.title" >
										<copy :copy-item="active.address" :display-text="active.address"></copy>
									</h4>
									<h3  v-else  >MESSAGE INBOX</h3>
									<a @click.prevent="active={}" v-if="active.title" href="#" class="btn btn-sm btn-outline btn-outline-primary"><i class="fa fa-eye"></i>All</a>
								</div>
								<div class="js-scrollbar card-body overflow-hidden" style="height: 400px;">
									<template v-if="active.title">
										<div class="row">
											<div class="col-sm-12">
												<h5>{{active.itype=='job'?'Applications':'Opportunities'}} For: <span class="text-primary">{{active.title}} ( {{active.itype=='job'?'Job':'Cv'}} )</span> </h5>
												<span class="small text-secondary">You Received The following Messages. (Use this Encrypted Service to Exchange Contacts)</span> </div>
										</div>
										<hr class='hr-space'>
										<div v-if="active.msgs.length < 1" class="row">
											<div class="col-sm-12">
												<h5> No Messages Found !</h5>
											</div>
										</div>
										<template v-for="msg in active.msgs">
											<div class='row'>
												<div class='col-sm-5'>
													<div class='media align-items-sm-center mr-4 mb-1 mb-sm-0'>
														<div class='d-none d-sm-flex mr-2'><span class='btn btn-icon btn-soft-secondary'><span class='btn-icon__inner text-dark'>{{active.itype=='job'?'Job':'Cv'}}</span></span></div>
														<div class='media-body'><span class='d-inline-block d-sm-none mr-1'>{{active.itype=='job'?'Job':'Cv'}}#</span><a class='hash-tag hash-tag--xs-160 hash-tag-xs-down--md text-truncate' href="#" @click.prevent="showMsgModal(msg)">{{msg.subject}}</a><span class='d-none d-sm-block small text-secondary'>
															<div>
																<timeago :datetime="msg.created_at" :auto-update="5"></timeago>
															</div>
															</span> </div>
													</div>
												</div>
												<div class='col-sm-7'>
													<div class='d-sm-flex justify-content-between'>
														<div class='text-nowrap mr-4 mb-1 mb-sm-0'><span>{{msg.is_mine ?'To':'From'}} <a class='hash-tag text-truncate' href="msg.from_address_link">{{msg.from_address}}</a></span><span class='d-sm-block'>For <a href='#' class='hash-tag text-truncate'>{{active.title}}({{active.itype=='job'?'Job':'Cv'}})</a></span></div>
														<div><span><a href="#" class='btn btn-sm btn-primary mr-3' @click.prevent="showMsgModal(msg)" title='Amount'><i class="fa fa-eye"></i></a><a href="#" @click.prevent="showSendMsgModal(msg)" class='btn btn-sm btn-primary' title='Amount'><i class="fa fa-reply"></i></a></span></div>
													</div>
												</div>
											</div>
											<hr class="hr-space">
										</template>
									</template>
									<template   v-else v-for="msg in user.msgs">
										<div class='row'>
											<div class='col-sm-5'>
												<div class='media align-items-sm-center mr-4 mb-1 mb-sm-0'>
													<div class='d-none d-sm-flex mr-2'><span class='btn btn-icon btn-soft-secondary'><span class='btn-icon__inner text-dark'>{{msg.stream}}</span></span></div>
													<div class='media-body'><span class='d-inline-block d-sm-none mr-1'>{{msg.stream}}#</span><a class='hash-tag hash-tag--xs-160 hash-tag-xs-down--md text-truncate' href="#" @click.prevent="showMsgModal(msg)">{{msg.subject}}</a><span class='d-none d-sm-block small text-secondary'>
														<div>
															<timeago :datetime="msg.created_at" :auto-update="5"></timeago>
														</div>
														</span> </div>
												</div>
											</div>
											<div class='col-sm-7'>
												<div class='d-sm-flex justify-content-between'>
													<div class='text-nowrap mr-4 mb-1 mb-sm-0'><span>{{msg.is_mine ?'To':'From'}} <a class='hash-tag text-truncate' href="msg.from_address_link">{{msg.from_address}}</a></span><span class='d-sm-block'>For <a href='#' class='hash-tag text-truncate'>{{active.title}}({{active.itype=='job'?'Job':'Cv'}})</a></span></div>
													<div><span><a href="#" class='btn btn-sm btn-primary mr-3' @click.prevent="showMsgModal(msg)" title='Amount'><i class="fa fa-eye"></i></a><a href="#" @click.prevent="showReplyModal(msg)" class='btn btn-sm btn-primary' title='Amount'><i class="fa fa-reply"></i></a></span></div>
												</div>
											</div>
										</div>
										<hr class="hr-space">
									</template>
								</div>
								<div class="card-footer row no-gutters">
									<div class="col-md-8"><a @click.prevent="refreshWalletsInBackground()" class="btn btn-block btn-xs btn-primary" href="#"><i :class="refresh?'fa-spin':''" class="fa fa-refresh"></i> Refresh Blockchain Data</a> </div>
									<div class="col-md-4 custom-control custom-checkbox d-flex align-items-center justify-content-end text-muted">
										<input v-model="refresh" type="checkbox" id="mine" class="custom-control-input" checked="checked">
										<label class="custom-control-label" for="mine"> <span>Auto Refresh</span> </label>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</main>
			<!-- Modals --> 
			
			<!-- Qr Modal Start -->
			<div class="modal fade" id="qr-modal" tabindex="-1">
				<div class="modal-dialog modal-dialog-md modal-dialog-centered">
					<div class="modal-content">
						<div class="modal-content"><a href="#" class="modal-close" data-dismiss="modal" aria-label="Close"><em class="ti fa fa-close"></em></a>
							<div class="dialog-body text-center">
								<div class="ht-20px"></div>
								<div><img :src="user.balance.qr_link"/></div>
								<div class="ht-20px"></div>
								<h3>
									<copy display-text="user.balance.address" copy-item="user.balance.address"></copy>
								</h3>
								<div class="ht-20px"></div>
								<a :href="user.balance.address_link" target="_blank" class="btn btn-primary">View In Explorer</a>
								<div class="ht-10px"></div>
							</div>
						</div>
						<!-- .modal-content --> 
					</div>
					<!-- .modal-dialog --> 
				</div>
				<!--Qr Modal End --> 
			</div>
			<div class="modal fade" id="send-modal" tabindex="-1">
				<div class="modal-dialog modal-dialog-md modal-dialog-centered">
					<div class="modal-content"><a href="#" class="modal-close" data-dismiss="modal" aria-label="Close"><em class="ti fa fa-close"></em></a>
						<div class="dialog-body">
							<h4 class="dialog-title">{{$t('wallet.withdraw',{symbol:user.balance.symbol})}}</h4>
							<p>{{$t("wallet.tx_queued")}}</p>
							<form action="#">
								<div class="row">
									<div class="col-md-6">
										<div class="form-group ">
											<label for="wallet" class="control-label">{{$t("wallet.wallet_pass")}} </label>
											<div class="input-wrap">
												<input class="form-control" type="password" id="address" name="amount" v-model="sendForm.password" >
											</div>
										</div>
									</div>
									<div class="col-md-6">
										<div class="form-group ">
											<label for="amount" class="control-label">{{$t("wallet.amount_in",{symbol:user.balance.symbol})}}</label>
											<input class="form-control" type="text" id="amount" name="amount" v-model="sendForm.amount" >
										</div>
									</div>
									<!-- .col --> 
								</div>
								<!-- .row -->
								<div class="form-group ">
									<label for="xamount" class="control-label">{{$t('wallet.coin_address',{symbol:user.balance.text})}}</label>
									<input class="form-control" type="text" id="xamount" name="amount" v-model="sendForm.address" >
									<span class="input-note">{{$t("wallet.enter")}}  {{$t('wallet.coin_address',{symbol:user.balance.text})}}</span></div>
								<div class="note note-plane note-danger"><em class="fas fa-info-circle"></em>
									<p>{{$t("wallet.fees_note")}}</p>
								</div>
								<div class="ht-30px"></div>
								<div class="d-sm-flex justify-content-between align-items-center">
									<button @click.prevent="sendCoins()" :disabled="!sendOk" class="btn btn-primary">{{$t("wallet.send_now")}}</button>
									<div class="ht-20px d-sm-none"></div>
									<span v-show="sendOk" class="text-success"> <em class="fa fa-check-box"></em>>{{$t("wallet.ok_amount")}}</span> <span v-show="!sendOk" class="text-danger"><em class="fa fa-close"></em> {{$t("wallet.invalid_amount")}} </span> </div>
							</form>
							<!-- form --> 
						</div>
					</div>
					<!-- .modal-content --> 
				</div>
				<!-- .modal-dialog --> 
			</div>
			
			<!-- Modal End -->
			<div class="modal fade" id="job-modal" tabindex="-1" >
				<div class="modal-dialog modal-dialog-md modal-dialog-centered">
					<div class="modal-content"> <a href="#" class="modal-close" data-dismiss="modal" aria-label="Close"><em class="ti fa fa-times"></em></a>
						<div class="dialog-body">
							<h4 class="dialog-title">{{mjob.address}}</h4>
							<label for="address" class="form-label">Txid: {{mjob.txid}} </label>
							<form action="#">
								<h4 class="dialog-title text-primary">{{mjob.title}}</h4>
								<div class="row">
									<div class="col-md-8">
										<div class="form-group">
											<label for="address" class="form-label">Qualifications </label>
											<div class="form-control">{{mjob.qualifications}}</div>
										</div>
										<!-- .form --> 
									</div>
									<!-- .col -->
									<div class="col-md-4">
										<div class="form-group">
											<label for="amt" class="form-label">Salary </label>
											<div class="form-control">{{mjob.salary}}</div>
										</div>
									</div>
									<!-- .col --> 
								</div>
								<!-- .row -->
								<div class="row">
									<div class="col-md-6">
										<div class="form-group">
											<label for="address" class="form-label">Category </label>
											<div class="form-control">{{mjob.category}}</div>
										</div>
									</div>
									<!-- .col -->
									<div class="col-md-6">
										<div class="form-group">
											<label for="password" class="form-label">Expiry Date </label>
											<div class="form-control">{{dt(mjob.expiry)}}</div>
										</div>
									</div>
									<!-- .col --> 
								</div>
								<!-- .row -->
								<div class="row form-group">
									<div class="col-md-12">
										<label for="address" class="form-label">Description</label>
									</div>
									<div class="col-md-12 mb-3 mb-md-0">
										<div class="form-control">{{mjob.description}}</div>
									</div>
								</div>
								<div class="my-2"></div>
							</form>
							<!-- form --> 
						</div>
					</div>
					<!-- .modal-content --> 
				</div>
				<!-- .modal-dialog --> 
			</div>
			<!-- Modal End --> 
			
			<!-- CV Modal Start -->
			<div class="modal fade" id="cv-modal" tabindex="-1" >
				<div class="modal-dialog modal-dialog-md modal-dialog-centered">
					<div class="modal-content"> <a href="#" class="modal-close" data-dismiss="modal" aria-label="Close"><em class="ti fa fa-times"></em></a>
						<div class="dialog-body">
							<h4 class="dialog-title">{{mcv.address}}</h4>
							<label for="address" class="form-label">Txid: {{mcv.txid}} </label>
							<form action="#">
								<h4 class="dialog-title text-primary">{{mcv.title}}</h4>
								<div class="row">
									<div class="col-md-8">
										<div class="form-group">
											<label for="address" class="form-label">Expirience </label>
											<div class="form-control">{{mcv.expirience}}</div>
										</div>
										<!-- .form --> 
									</div>
									<!-- .col -->
									<div class="col-md-4">
										<div class="form-group">
											<label for="amt" class="form-label">Salary </label>
											<div class="form-control">{{mcv.salary}}</div>
										</div>
									</div>
									<!-- .col --> 
								</div>
								<!-- .row -->
								<div class="row">
									<div class="col-md-6">
										<div class="form-group">
											<label for="address" class="form-label">Category </label>
											<div class="form-control">{{mcv.category}}</div>
										</div>
									</div>
									<!-- .col -->
									<div class="col-md-6">
										<div class="form-group">
											<label for="password" class="form-label">Expiry Date </label>
											<div class="form-control">{{mcv.expiry}}</div>
										</div>
									</div>
									<!-- .col --> 
								</div>
								<!-- .row -->
								<div class="row form-group">
									<div class="col-md-12">
										<label for="address" class="form-label">Description</label>
									</div>
									<div class="col-md-12 mb-3 mb-md-0">
										<div class="form-control">{{mcv.description}}</div>
									</div>
								</div>
								<div class="my-2"></div>
							</form>
							<!-- form --> 
						</div>
					</div>
					<!-- .modal-content --> 
				</div>
				<!-- .modal-dialog --> 
			</div>
			<!-- CV Modal End -->
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
							<a v-if="last.txid_link" :href="last.txid_link" target="_blank" class="btn btn-primary">{{$t("wallet.view_tx")}}</a> <a v-if="!last.txid" @click.prevent="showSendModal()" href="#"  class="btn btn-primary">{{$t("wallet.send_more")}}</a>
							<div class="ht-10px"></div>
						</div>
					</div>
					<!-- .modal-content --> 
				</div>
				<!-- .modal-dialog --> 
			</div>
			<!-- .modal-dialog -->
			<div v-if="mmsg.length > 0" class="modal fade" id="msg-modal" tabindex="-1" >
				<div class="modal-dialog modal-dialog-md modal-dialog-centered">
					<div class="modal-content"> <a href="#" class="modal-close" data-dismiss="modal" aria-label="Close"><em class="ti fa fa-times"></em></a>
						<div class="dialog-body">
							<h4 class="dialog-title">{{mmsg.from_address}} </h4>
							<span class="small text-secondary">{{mmsg.is_mine?'OutBox':'InBox'}}</span> <span v-if="mmsg.is_mine" class="small text-secondary">Message was Sent To you on {{dt(mmsg.created_at)}}</span> <span v-else class="small text-secondary">You Sent This Message On on {{dt(mmsg.created_at)}}</span>
							<h4 class="dialog-title text-primary">Subject: {{mmsg.subject}}</h4>
							<div class="ht-10px"></div>
							<p class="dialog-title">Message: {{mmsg.address}}</p>
							<div class="form-group">
								<label for="address" class="form-label">Message </label>
								<textarea readonly name="message" class="form-control" id="" cols="30" rows="4">{{mmsg.message}}</textarea>
							</div>
							<!-- .form -->
							<div class="row form-group">
								<div class="col-md-6"> <a @click.prevent="showReplyModal(mmsg)" class="btn btn-primary  py-2 px-5">Reply Message</a> </div>
								<div class="col-md-6"> <span class="small text-danger">Sending Messages Costs {{symbol}} !</span> </div>
							</div>
							<div class="my-2"></div>
						</div>
					</div>
					<!-- .modal-content --> 
				</div>
				<!-- .modal-dialog --> 
			</div>
			<div class="modal fade" id="reply-modal" tabindex="-1" >
				<div class="modal-dialog modal-dialog-md modal-dialog-centered">
					<div class="modal-content"> <a href="#" class="modal-close" data-dismiss="modal" aria-label="Close"><em class="ti fa fa-times"></em></a>
						<div class="dialog-body">
							<h4 class="dialog-title">Reply: {{mmsg.title}}</h4>
							<div  class="ht-10px"></div>
							<p>{{mmsg.message}}</p>
							<hr class="hr-space">
							<div class="form-group">
								<label for="address" class="form-label">Subject </label>
								<input v-model="reply.subject" class=form-control type="text" name="subject">
							</div>
							<!-- .form -->
							<div class="form-group">
								<label for="address" class="form-label">Message </label>
								<textarea v-model="reply.message" name="message" class="form-control" id="" cols="30" rows="4"></textarea>
							</div>
							<!-- .form -->
							<div class="row form-group">
								<div class="col-md-12">
									<button @click.prevent="replyMessage()" type="button"class="btn btn-primary  py-2 px-5">Reply Message</button>
								</div>
							</div>
							<div class="my-2"></div>
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
import $ from 'jquery';
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
			jobTab:"jobs",
			txid:"",
			active: {},
			user: {},
			mcv: {},
			mmsg: {},
			mjob: {},
			settings: {},
			send_error: "",
			queued:false,
			refresh:false,
			symbol:"JBT",
			last: {},
			sendForm:{
                amount: "",
                address: "",
				password:""
			},
			reply:{
                message: "",
                subject: "",
				message_id:""
			},
			isSaving: false,
			getAddress: false,
			ready: false,
			disabled: false,
			errors: [],
			filter:'',
			emnemonic:'',
		};
		
	},
	
	computed:{
		
		sendOk(){
			var send = parseFloat(this.sendForm.amount);
			var bal = parseFloat(this.user.balance);
			return  send > 0 && bal > send;
		},
		deposits(){
			if(this.dfilter.length > 3 ){
				console.log('f-only');
				return  _.filter(this.user.txs, (o) =>{ 
					return o.txid.indexOf(this.dfilter) !== -1 
				});
			}
			return _.take(this.user.txs , 20);
		},
		withdraws(){
			if(this.wfilter.length > 3 ){
				return  _.filter(this.user.etxs, (o) =>{ 
					return o.txid.indexOf(this.wfilter) !== -1 ||o.address.indexOf(this.wfilter) !== -1
				});
			}
			return _.take(this.user.etxs , 20);
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
		/**
		 * show Job Modal.
		 */
		showJobModal(job){
			this.mjob = job;
			$('#job-modal').modal('show');
		},
		
		/**
		 * show Job Modal.
		 */
		showReplyModal(msg){
			this.mmsg = msg;
			this.reply.message_id = msg.id;
			$('#reply-modal').modal('show');
		},
		
		/**
		 * show Cv Modal.
		 */
		showCvModal(cv){
			this.mcv = cv;
			$('#cv-modal').modal('show');
		},
		/**
		 * show Msg Modal.
		 */
		showMsgModal(msg){
			this.mmsg = msg;
			$('#msg-modal').modal('show');
		},
		/**
		 * show Qr Modal.
		 */
		showQrModal(){
			$('#qr-modal').modal('show');
		},
		/**
		 * show Edit Balance Modal.
		 */
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
		replyMessage(){
			this.isSaving = true;
			axios.post('/ajax/msgs/reply', this.reply).then(response => {
				$('#reply-modal').modal('hide');
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
				$('#reply-modal').modal('hide');
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
