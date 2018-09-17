jQuery(document).ready(function($){
	$(document).on('change','#focus_type',function(){
		if( this.value === 'custom') {
			$(".outline_color-group").addClass("open");
		} else {
			$(".outline_color-group").removeClass("open");
		}
	});
});