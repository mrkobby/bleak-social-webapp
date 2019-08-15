var $input=$('<div class="modal-body"><div class="input-group"><input type="text" class="form-control inputBox" placeholder="Quick Message"><div class="fj""><button type="button" class="cg fp">Send <span class="fa fa-chevron-right" ></span></button></div></div></div>');
$(document).on("click",".js-msgGroup",function(){
	$(".js-msgGroup, .js-newMsg").addClass("hide"),
	$(".js-conversation").removeClass("hide"),
	$(".modal-title").html('<span class="fa fa-chevron-left"></span>&nbsp;<a class="js-gotoMsgs hand">Back</a>'),
	$input.insertBefore(".js-modalBody")}),
	$(function(){function o(){
			return $(window).width()-($('[data-toggle="dropdown"]').offset().left+$('[data-toggle="dropdown"]').outerWidth())}
	$(window).on("resize",function(){var t=$('[data-toggle="dropdown"]').data("bs.dropdown");t&&(t.options.viewport.padding=o())}),
	$('[data-toggle="dropdown"]').dropdown({template:'<div class="dropdown" role="tooltip"><div class="arrow"></div><div class="dropdown-content p-x-0"></div></div>',title:"",html:!0,trigger:"manual",placement:"bottom",viewport:{selector:"body",padding:o()},content:function(){var o=$(".app-navbar .navbar-nav:last-child").clone();return'<div class="nav nav-stacked" style="width: 200px">'+o.html()+"</div>"}}),
	$('[data-toggle="dropdown"]').on("click",function(o){o.stopPropagation(),
	$('[data-toggle="dropdown"]').data("bs.dropdown").tip().hasClass("in")?($('[data-toggle="dropdown"]').dropdown("hide"),
	$(document).off("click.app.dropdown")):($('[data-toggle="dropdown"]').dropdown("show"),setTimeout(function(){
		$(document).one("click.app.dropdown",function(){$('[data-toggle="dropdown"]').dropdown("hide")})},1))})}),
		$(document).on("click",".js-gotoMsgs",function(){
			$input.remove(),$(".js-conversation").addClass("hide"),$(".js-msgGroup, .js-newMsg").removeClass("hide"),$(".modal-title").html("Messages")}),
		$(document).on("click","[data-action=growl]",function(o){o.preventDefault(),
		$("#app-growl").append('<div class="alert alert-dark alert-dismissible fade in" role="alert">' +
								'<button type="button" class="close" data-dismiss="alert" aria-label="Close">' +
								'<span aria-hidden="true">×</span></button>' +
								'<p>Bleaker\'s Word #65: The greatest advicer is WEED.</p></div>')}),
		$(document).on("focus",'[data-action="grow"]',function(){
		$(window).width()>1e3&&$(this).animate({width:250})}),
		$(document).on("blur",'[data-action="grow"]',function(){if($(window).width()>1e3){$(this).animate({width:170})}}),
		$(function(){function o(){$(window).scrollTop()>$(window).height()?$(".docs-top").fadeIn():$(".docs-top").fadeOut()}
		$(".docs-top").length&&(o(),$(window).on("scroll",o))}),
		$(function(){function o(){i.width()>768?e():t()}function t(){i.off("resize.theme.nav"),i.off("scroll.theme.nav"),n.css({position:"",left:"",top:""})}function e(){
			function o(){e.containerTop=$(".docs-content").offset().top-40,e.containerRight=$(".docs-content").offset().left+$(".docs-content").width()+45,t()}function t(){
				var o=i.scrollTop(),t=Math.max(o-e.containerTop,0);return t?void n.css({
					position:"fixed",left:e.containerRight,top:40}):($(n.find("li")[1]).addClass("active"),n.css({
						position:"",left:"",top:""}))}
			var e={};o(),$(window).on("resize.theme.nav",o).on("scroll.theme.nav",t),$("body").scrollspy({
				target:"#markdown-toc",selector:"li > a"}),setTimeout(function(){
					$("body").scrollspy("refresh")},1e3)}var n=$("#markdown-toc"),i=$(window);n[0]&&(o(),i.on("resize",o))});