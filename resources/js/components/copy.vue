<template>
	<div @mouseover="show = true" @mouseleave="show = false"  @click="doCopy"  class="click-to-copy"> 
		<span class="click-to-copy-text">{{displayText}}</span> 
		<span  data-label="Copied" :class={'hidden':show} class="Label click-to-copy-label">Copy</span>
	</div>
</template>
<script>
	export default{
		props:{
			displayText:{
			  type: [String, Number],
			  required: true
			},
			copyItem:{
			  type: [String, Number],
			  required: true
			}
		},
		data(){
			return{
				show:false,
			}
		},
		methods:{
			doCopy(){
				this.$copyText(this.copyItem).then((e)=> {
					this.copying = true;
					settimeout(()=>{
					   this.copying = false;
					},600)
				}, (e) =>{
				  this.copying = false;
				})
			}
		}
	}
</script>
<style>
@keyframes floatup {
 20% {
 opacity: .999;
}
 100% {
 transform: translate3d(-50%, -17px, 0);
}
}
.click-to-copy {
	cursor: pointer;
}
.click-to-copy .copy-loader {
	position: absolute;
	top: 10px;
	left: 10px;
}
.click-to-copy .Icon--arrowDown {
	color: #0069ff;
}
.click-to-copy .click-to-copy-text.Highlighted {
	background-color: #0069ff;
	color: #fff;
}
.click-to-copy .Label {
	color: #0069ff;
	display: inline-block;
	font-size: 13px;
	line-height: 100%;
	position: relative;
	opacity: 0.999;
	transition: opacity 0.2s ease-in-out;
	top: -1px;
}
.click-to-copy .Label.hidden {
	opacity: 0.001;
}
.click-to-copy .Label::after {
	content: attr(data-label);
	color: #0069ff;
	display: inline-block;
	position: absolute;
	top: -2px;
	left: 50%;
	opacity: 0.001;
	text-align: center;
	transform: translate3d(-50%, 0, 0);
	-webkit-backface-visibility: hidden;
	white-space: nowrap;
}
.click-to-copy.copying .Label::after {
	animation: floatup 0.5s ease-in-out;
}
.click-to-copy.copying.inline-button .Label::after {
	color: #676767;
}
.click-to-copy .Tooltip {
	top: -21px;
}
.click-to-copy.truncate .click-to-copy-text {
	max-width: calc(100% - 40px);
}
</style>
