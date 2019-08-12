<style >
.img-step {
	border-radius: 4px;
	margin-right: 1.428rem;
	min-width: 4.714rem;
	height: 4.714rem;
	padding: 0.714rem 0.428rem 0.571rem 0.857rem;
}
.step-icon {
	font-size: 50px;
	vertical-align: top;
}
</style>
<template>
<div>
	<v-dialog/>
	<div v-if="!ready||isSaving" :class="" class="page-overlay is-loading">
		<div class="spinner"><span class="sp sp1"></span><span class="sp sp2"></span><span class="sp sp3"></span></div>
	</div>
	<div v-if="ready" class="row">
		<div class="main-content col-lg-9">
			<div class='card b-0'>
				<div  class='card-body row no-gutters mt-3'>
					<div class='col-md-6 border-right'>
						<div class="px-3">
							<h2>{{$t('auth.change_password')}}</h2>
							<form id="changepassform" class="no-border no-padding" accept-charset="UTF-8" method="post">
								<div :class="{'text-danger':formErrors.current_password.length > 0,'text-primary':form.current_password.length > 0 && !formErrors.current_password}" class="form-group">
									<label class="form-label">{{$t('auth.current_password')}}</label>
									<input v-model="form.current_password" type="password" class="form-control form-control-lg" name="current_password" id="current_password" :placeholder="$t('auth.current_password')">
									<span v-if="formErrors.current_password.length > 0" class="help-block error-help-block">{{formErrors.current_password}}</span> </div>
								<div :class="{'text-danger':formErrors.password.length > 0,'text-primary':form.password.length > 0 && !formErrors.password}" class="form-group">
									<label class="form-label">{{$t('auth.new_password')}}</label>
									<input v-model="form.password" id="password" :placeholder="$t('auth.new_password')" required="required" name="password" type="password" value="" class="form-control form-control-lg" aria-required="true">
									<span v-if="formErrors.password.length > 0" class="help-block error-help-block">{{formErrors.password}}</span> </div>
								<div :class="{'text-danger':formErrors.password_confirmation.length > 0,'text-primary':form.password_confirmation.length > 0 && !formErrors.password_confirmation}" class="form-group">
									<label class="form-label">{{$t('auth.ph_password_conf')}}</label>
									<input v-model="form.password_confirmation" id="password-confirm" :placeholder="$t('auth.ph_password_conf')" required="required" name="password_confirmation" type="password" value="" class="form-control form-control-lg" aria-required="true">
									<span v-if="formErrors.password_confirmation.length > 0" class="help-block error-help-block">{{formErrors.password_confirmation}}</span> </div>
								<span class="password-validations">
								<div v-html="$t('auth.one_lowercase')" :class="lowercase?'text-primary':'text-danger'" v-show="form.password.length>0"  id="lowercase"> </div>
								<div v-html="$t('auth.one_uppercase')" :class="uppercase?'text-primary':'text-danger'" v-show="form.password.length>0" id="uppercase"> </div>
								<div v-html="$t('auth.one_numerical')" :class="numeric?'text-primary':'text-danger'" v-show="form.password.length>0" id="numeric"></div>
								<div v-html="$t('auth.one_special')" :class="symbol?'text-primary':'text-danger'" v-show="form.password.length>0" id="symbol"></div>
								<div v-html="$t('auth.eight_min')" :class="long?'text-primary':'text-danger'" v-show="form.password.length>0" id="length"></div>
								</span>
								<div class='centered mt-3'>
									<button @click.prevent="updatePassword()" type="submit" name="commit" class="btn btn-lg btn-primary btn-block" id="password-submit"><i :class="isSaving?'fa-spin fa-refresh':'fa-check'" class="fa mr-2"></i> {{$t('auth.save_new_pass')}} </button>
								</div>
							</form>
						</div>
					</div>
					<div v-if="!user.secretKey" class='col-md-6'>
						<div class="px-3">
							<h2>{{$t('auth.code_settings')}}</h2>
							<p class=''>{{$t('auth.customize_authentication')}}</p>
							<ul>
								<li class='my-2'>
									<div class='d-flex flex-row'>
										<div class="rounded border img-step"> <i :class="user.enable_twofa_email?'text-primary':'text-danger'" class="fi fi-mail step-icon"></i> </div>
										<span>
										<p :class="user.enable_twofa_email?'text-primary':'text-danger'" class="mb-2 h5"><i :class="user.enable_twofa_email?'fa fa-check':'fa fa-times'"></i> &nbsp;
											<template v-if="user.enable_twofa_email"> {{$t('auth.enabled')}}</template>
											<template v-else> {{$t('auth.disabled')}}</template>
										</p>
										<a href="/authentication/toggle-twofa-email" :class="user.enable_twofa_email?'btn-danger':'btn-primary'" class="btn btn-block btn-sm" @click.prevent="toggleEmail()">
										<template v-if="user.enable_twofa_email">{{$t('auth.disable_email')}}</template>
										<template v-else>{{$t('auth.enable_email')}}</template>
										</a> </span> </div>
								</li>
								<li class='my-2'>
									<div class='d-flex flex-row'>
										<div class='rounded border img-step'> <i :class="user.enable_twofa_sms?'text-primary':'text-danger'" class="fi fi-smartphone-1 step-icon"></i> </div>
										<span>
										<p :class="user.enable_twofa_sms?'text-primary':'text-danger'" class="mb-2 h5"><i :class="user.enable_twofa_sms?'fa fa-check':'fa fa-times'"></i> &nbsp;
											<template v-if="user.enable_twofa_sms"> {{$t('auth.enabled')}}</template>
											<template v-else> {{$t('auth.disabled')}}</template>
										</p>
										<a @click.prevent="toggleSMS()" :class="user.enable_twofa_sms?'btn-danger':'btn-primary'" class="btn btn-block btn-sm">
										<template v-if="user.enable_twofa_sms">{{$t('auth.disable_sms')}}</template>
										<template v-else>{{$t('auth.enable_sms')}}</template>
										</a> </span> </div>
								</li>
								<li class='my-2'>
									<div class='d-flex flex-rows'>
										<div class="rounded border img-step"> <i class="fi fi-laptop step-icon text-primary"></i> </div>
										<div>
											<div class="form-group">
												<input type="text" v-model="code" name="code" id="code" class="form-control form-control-lg" :placeholder="$t('auth.mobile_auth_code')" autocomplete="off"/>
											</div>
											<div class="centered buttons"> <a @click.prevent="disable2fa()"  href="#" class="btn btn-danger btn-block">{{$t('auth.disable_mobile_auth')}}</a> </div>
										</div>
									</div>
								</li>
							</ul>
							<p class='text-primary'>{{$t('auth.disable_mobile_auth_information')}}</p>
						</div>
					</div>
					<div v-else class='col-md-6'>
						<div class="px-3">
							<h2>{{$t('auth.mobile_authentication')}}</h2>
							<p class=''>{{$t('auth.secure_your_account')}}</p>
							<ul>
								<li class='my-2'>
									<div class='d-flex flex-row'>
										<div class="rounded border img-step"> <i class="fi fi-smartphone step-icon"></i> </div>
										{{$t('auth.download_auth_app')}} </div>
								</li>
								<li class='my-2'>
									<div class='d-flex flex-row'>
										<div class='rounded border img-step'> <i class="fi fi-qr-code step-icon"></i> </div>
										<span v-html="$t('auth.scan_qr',{secret:user.secretKey})"></span> </div>
								</li>
								<li class='my-2'>
									<div class='d-flex flex-rows'>
										<div class="rounded border img-step"> <i class="fi fi-laptop step-icon"></i> </div>
										{{$t('auth.enter_auth_code_below')}} </div>
								</li>
							</ul>
							<div class='d-flex flex-row'> <img :src="user.inlineUrl"/>
								<div class='p-3'>
									<form class="no-border" action="/save_secret" accept-charset="UTF-8" method="post">
										<div class="form-group mt-3">
											<input :class="{'is-invalid':code_error}" v-model="code" type="text" :placeholder="$t('auth.auth_code')" name="code" id="code" class="form-control form-control-lg" autocomplete="off"/>
										</div>
										<div class="centered buttons">
											<button :disabled="isSaving" @click.prevent="enable2fa()"  name="update"  class="btn btn-primary btn-block"><i :class="isSaving?'fa-spin fa-refresh':'fa-check'" class="fa mr-2"></i>{{$t('auth.enable')}}</button>
										</div>
									</form>
								</div>
							</div>
							<p class='centered lost-phone'>{{$t('auth.recovery_information')}}.</p>
						</div>
					</div>
				</div>
			</div>
		</div>
		<!-- .col -->
		<div class="aside sidebar-right col-lg-3">
			<div class="px-3">
				<h2>{{$t('auth.edit_user')}}</h2>
				<form id="uform" class="no-border no-padding" accept-charset="UTF-8" method="post">
					<div class="form-group">
						<label class="form-label">{{$t('auth.email')}}</label>
						<input disabled v-model="user.email" type="text" class="form-control form-control-lg" name="email" id="email" :placeholder="$t('auth.email')">
					</div>
					<div :class="{'text-danger':uformErrors.name.length > 0,'text-primary':uform.name.length > 0 && !uformErrors.name}" class="form-group">
						<label class="form-label">{{$t('auth.name')}}</label>
						<input v-model="uform.name" type="text" class="form-control form-control-lg" name="name" id="current_password" :placeholder="$t('auth.name')">
						<span v-if="uformErrors.name.length > 0" class="help-block error-help-block">{{uformErrors.name}}</span> </div>
					<div :class="{'text-danger':uformErrors.first_name.length > 0,'text-primary':uform.first_name.length > 0 && !uformErrors.first_name}" class="form-group">
						<label class="form-label">{{$t('auth.first_name')}}</label>
						<input v-model="uform.first_name" id="password" :placeholder="$t('auth.first_name')" required="required" name="first_name" type="text" value="" class="form-control form-control-lg" aria-required="true">
						<span v-if="uformErrors.first_name.length > 0" class="help-block error-help-block">{{uformErrors.first_name}}</span> </div>
					<div :class="{'text-danger':uformErrors.last_name.length > 0,'text-primary':uform.last_name.length > 0 && !uformErrors.last_name}" class="form-group">
						<label class="form-label">{{$t('auth.last_name')}}</label>
						<input v-model="uform.last_name" id="password-confirm" :placeholder="$t('auth.last_name')" required="required" name="last_name" type="text" value="" class="form-control form-control-lg" aria-required="true">
						<span v-if="uformErrors.last_name.length > 0" class="help-block error-help-block">{{uformErrors.last_name}}</span> </div>
					
					<div class='centered mt-3'>
						<button @click.prevent="updateUser()" type="submit" name="commit" class="btn btn-lg btn-primary btn-block" id="password-submit"><i :class="isSaving?'fa-spin fa-refresh':'fa-check'" class="fa mr-2"></i> {{$t('auth.update_user')}} </button>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>
</template>

<!-- br-pagebody --> 
<script>
let Validator = require('validatorjs');
export default {
	data() {
		return {
			form: {
				current_password: '',
				password: '',
				password_confirmation: ''
			},
			formErrors: {
				current_password: '',
				password: '',
				password_confirmation: ''
			},
			uform: {
				name: '',
				first_name: '',
				last_name: ''
			},
			uformErrors: {
				name: '',
				first_name: '',
				last_name: ''
			},
			auth: {},
			code: '',
			code_error: false,
			user: {},
			errors: [],
			isSaving:false,
			loading:false,
			ready:false
		}
	},

	computed: {
		uppercase() {
			return /[A-Z]+/.test(this.form.password)
		},
		lowercase() {
			return /[a-z]+/.test(this.form.password)
		},
		numeric() {
			return /[0-9]+/.test(this.form.password)
		},
		symbol() {
			return /[\W]+/.test(this.form.password)
		},
		long() {
			return this.form.password.length >= 8
		},
	},
	/**
	 * The component has been created by Vue.
	 */
	created() {
		axios.get('/ajax/user/').then(user =>{
			this.user = user.data;
			this.uform.name = this.user.name;
			this.uform.first_name = this.user.first_name;
			this.uform.last_name = this.user.last_name;
			this.ready = true;
		});
	},
	
	
	methods: {
		passwordIsStrong() {
			return (this.uppercase &&
				this.lowercase &&
				this.numeric &&
				this.symbol &&
				this.long)
		},
		formIsValid() {
			let rules = {
				current_password: 'required|string',
				password: 'required|min:6|max:20|confirmed',
				password_confirmation: 'required|same:password',
			}
			let validation = new Validator(this.form, rules);
			if (validation.fails()) {
				_.each(rules, (value, key) => {
					this.$set(this.formErrors, key, validation.errors.first(key))
				});
			}
			return validation.passes() && this.passwordIsStrong();
		},
		uformIsValid() {
			let urules = {
				name: 'required|string',
				first_name: 'required|string',
				last_name: 'required|string',
			}
			let validation = new Validator(this.uform, urules);
			if (validation.fails()) {
				_.each(urules, (value, key) => {
					this.$set(this.uformErrors, key, validation.errors.first(key))
				});
			}
			return validation.passes();
		},

		updatePassword() {
			if (!this.uformIsValid()) return false;
			this.isSaving = true;
			axios.post('/ajax/user/update/password', this.form).then(response => {
				this.isSaving = false;
				this.form= {
					current_password: '',
					password: '',
					password_confirmation: ''
				}
				this.formErrors = {
					current_password: '',
					password: '',
					password_confirmation: ''
				}
				if (typeof response.data.error !== "undefined") {
					this.error(response.data.error);
					return;
				}
				this.success(this.$t('auth.password_changed'));
			}).catch((errors) => {
				this.isSaving = false;
				this.form= {
					current_password: '',
					password: '',
					password_confirmation: ''
				}
				this.formErrors = {
					current_password: '',
					password: '',
					password_confirmation: ''
				};
				if (errors.response) {
					if(errors.response.status == 422){
						_.each(errors.response.data.errors, (value, key)=>{
							this.formErrors[key] = _.first(value)
						})
					}else{
						this.errors = errors.response.data.errors;
					}
					return this.error(errors.response.data.message);
				} else if (errors.request) {
					return this.error(this.$t('auth.empty_response'));
				} else {
					return this.error(this.$t('auth.internet_problems'));
				}
				return this.error(this.$t('auth.unknown_problems'));
			});
		},
		
		
		updateUser(){
			if (!this.uformIsValid()) return false;
			this.isSaving = true;
			this.postData('/ajax/user/update', this.uform , this.$t('auth.user_updated'));
		},
		
		
		postData(url , data , success) {
			this.isSaving = true;
			axios.post(url, data).then(response => {
				this.isSaving = false;
				if (typeof response.data.error !== "undefined") {
					this.error(response.data.error);
					return;
				}
				this.user = response.data;
				this.success(success);
			}).catch((errors) => {
				this.isSaving = false;
				if (errors.response) {
					if(errors.response.status == 422){
						_.each(errors.response.data.errors, (value, key)=>{
							this.errors.push(_.first(value)) ;
						})
					}else{
						this.errors = errors.response.data.errors;
					}
					return this.error(errors.response.data.message);
				} else if (errors.request) {
					return this.error(this.$t('auth.empty_response'));
				} else {
					return this.error(this.$t('auth.internet_problems'));
				}
				return this.error(this.$t('auth.unknown_problems'));
			});
		},
		
		enable2fa(){
			if(!this.code){
				this.error('EMPTY CODE');
				this.code_error=true;
			} 
			this.postData('/save_secret', {
				secret:this.user.secretKey,
				code:this.code,
			}, this.$t('auth.twofa_enabled'));
		},
		
		toggleEmail(){
			var success = this.user.enable_twofa_email?this.$t('auth.twofa_email_disabled'):this.$t('auth.twofa_email_enabled')
			this.postData('/authentication/toggle-twofa-email', {}, success);
		},
		
		toggleSMS(){
			var success = this.user.enable_twofa_sms?this.$t('auth.twofa_sms_disabled'):this.$t('auth.twofa_sms_enabled');
			this.postData('/authentication/toggle-twofa-sms', {}, success);
			
		},
		disable2fa(){
			if(!this.code){
				this.error('EMPTY CODE');
				this.code_error=true;
			} 
			this.postData('/authentication/disable-google-authenticator', {
				code:this.code,
			}, this.$t('auth.auth_disabled'));
		}
	}
}

</script>