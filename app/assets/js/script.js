

$(window).ready(function(e){
	$('form').submit(function(e){
		e.preventDefault();
	});
	
	$( ".datepicker-month-only" ).datepicker({
		minViewMode: 1
	});
	$( ".datepicker-year-only" ).datepicker({
		minViewMode: 2
	});
	
	$(".alert").hide();
	
	$(window).resize(function() {
		resetFullHeight();
	});
	resetFullHeight();
	
	
	$(window).scroll(function(){
		checkScrollPosition();
	});
	checkScrollPosition();
});

function resetFullHeight(){
	setTimeout(function(){
		$(".full-height-screen").show();
		$(".full-height-screen").height($(window).height() - $(".full-height-screen").offset().top - 10);
	}, 1);
}

function checkScrollPosition(){
	if($(window).scrollTop() > 10){
		$(".page-title-2").addClass("scroll");
	}
	else{
		$(".page-title-2").removeClass("scroll");
		//$(".page-title-2.scrollview").addClass("scroll");
	}
}

$.fn.formToJSON = function(){
	var json	= {};
	var form	= this;
	
	$(form).find('input:hidden, input:text, input:password, input:file, select, textarea')
	.each(function() {
		var name = $(this).attr('name');
		var value = $(this).val();
		
		json[name] = value;
		
	});
	
	return json;
}
$.fn.JSONToForm = function(json){
	var form	= this;
	
	$(form).find('input:hidden, input:text, input:password, input:file, select, textarea')
	.each(function() {
		var name = $(this).attr('name');
		
		var value = json[name];
		if(value != null){
			$(this).val(json[name]);	
		}
		else{
			$(this).val('');
		}
		
	});
	
	return json;
}
Array.prototype.move = function (old_index, new_index) {
    if (new_index >= this.length) {
        var k = new_index - this.length;
        while ((k--) + 1) {
            this.push(undefined);
        }
    }
    this.splice(new_index, 0, this.splice(old_index, 1)[0]);
    return this; // for testing purposes
};
$.fn.resetForm = function(){
	var form = this;
	$(form).find('input:hidden, input:text, input:password, input:file, select, textarea')
	.each(function() {
		$(this).val('');
		
	});
}

function scrollTo(content, target){
	$(content).animate({
		scrollTop: 0
	}, 0);
	
	$(content).animate({
        scrollTop: $(target).offset().top - $(content).offset().top - 10
    }, 800);
}

function showAlert(target, btn, type, msg){
	$(target).removeClass("alert-success");
	$(target).removeClass("alert-warning");
	$(target).removeClass("alert-danger");
	
	msg+='<a href="#" class="close" data-dismiss="alert" aria-label="close">×</a>';
	$(target).html(msg);
	$(target).addClass(type);
	$(target).alert();
	$(target).show();
	
	$(btn).button('reset');
}