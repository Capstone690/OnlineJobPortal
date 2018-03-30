$(window).scroll(function() {    
    var scroll = $(window).scrollTop();

     //>=, not <=
    if (scroll >= 50) {
        //clearHeader, not clearheader - caps H
        $(".navbar").addClass("static-nav");
    }else{
    	$(".navbar").removeClass("static-nav");
    }
}); //missing )
