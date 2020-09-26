
var isSidebarOpen = false;
$(document).ready(function(e){
	$("#sidebar-button").click(function(e) {
        e.preventDefault();
        $(".body").toggleClass("toggled");
		if($(".body").hasClass("toggled")){
			$("#xxx").addClass('toggled');
		}
		else{
			$("#xxx").removeClass('toggled');
		}
        //$(".body").addClass("toggled");
    });
});