export default {
	bind: function (el, binding, vnode) {
		var element = $(el);
		var loader = ($(el).hasClass('btn-icon'))?'':'Working...'
		var	html = element.html();
		var	event = binding.expression || "saving";
		vnode.context.$watch(event, function (newVal, oldVal) {
			if (newVal){
				return element.attr("disabled", !0).html('<i class="la la-circle-o-notch la-spin"></i>'+loader);
			}
			element.removeAttr("disabled").html(html);
		},{immediate: true})
	}
}
