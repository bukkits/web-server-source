function scrollTo(project){
	var element = $("#top-" + project);
	var top = element.position();
	top = top.top;
	$("html, body").animate({scrollTop: top}, 500);
}
function scrollToTop(){
	$("html, body").animate({scrollTop: 0}, 500);
}
