import moment from 'moment';
import { SweetModal} from 'sweet-modal-vue'
export default {
	data(){
		return{
			errorMessage:'',
			successMessage:'',
		}
	},
	components: {
		SweetModal
	},
	computed: {},
	methods: {
		/**
		 * Generate a unique ID
		 */
		uuid() {
			return Math.random().toString(36).substr(2, 9);
		},
		
		dt(date) {
			//console.log(date);
			return moment(date).calendar();
		},


		/**
		 * Show an error message.
		 vm.$modal.show('dialog', { text: '<i class="la la-times-circle tx-56 text-danger"></i><br> Freedom', classes: 'v--modal text-center', buttons: [{ title: 'Close' }] })
		 */
		error(message) {
			this.errorMessage = message;
			this.$refs.errorModal.open();
		},


		/**
		 * Show a success message.
		 */
		success(message) {
			this.successMessage = message;
			this.$refs.successModal.open();
		},


		/**
		 * Show confirmation message.
		 */
		confirm(message, success, failure) {
			var html = '<i class="la la-exclamation-circle tx-56 text-warning"></i><br>' + message;
			this.$modal.show('dialog', {
				text: html,
				classes: 'v--modal text-center',
				buttons: [{
					title: 'Cancel',
					handler: failure
				}, {
					title: 'Proceed', // Button title
					default: true, // Will be triggered by default if 'Enter' pressed.
					handler: () => {
						this.$modal.hide('dialog');
						return success()
					}
				}]
			})
		},


		/**
		 * Add the following to the clipboard.
		 */
		addToClipboard(input) {
			if (document.queryCommandSupported('copy')) {
				var $temp = $("<input>");
				$("body").append($temp);
				$temp.val(input).select();
				document.execCommand("copy");
				$temp.remove();
			}
		},


		/**
		 * Format the given date for display.
		 */
		formatDate(date) {
			return moment(date + ' +0000', 'YYYY-MM-DD hh:mm:ss Z').format('MMMM Do, h:mm:ss A');
		},

		listenToNotifications(uid) {
			window.uid = window.Echo.private(`App.Models.User.${uid}`)
				.notification((notification) => {
					var action = { 
						text: 'OK',
						onClick: (e, toastObject) => {
							toastObject.goAway(0);
						}
					}
					if (notification.message.url) {
						action = {
							text: notification.message.url,
							onClick: (e, toastObject) => {
								window.location.href = notification.message.link;
							}
						}
					}
					this.$toasted.show(notification.message.text, {
						icon: 'la la-circle-o-notch la-spin tx-18 mr-2',
						action: action
					});
				});

		},
	}

}
