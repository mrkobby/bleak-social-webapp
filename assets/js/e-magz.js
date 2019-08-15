$(function(){
	// love
	$(".love").each(function(){
		$(this).find("div").html($.number($(this).find("div").html()));
		$(this).click(function(){
			var countNow = $(this).find("div").html().replace(',', '');
			if(!$(this).hasClass("active")) {
				$(this).find(".animated").remove();
				$(this).addClass("active");
				$(this).find("i").removeClass("ion-android-favorite-outline");
				$(this).find("i").addClass("ion-android-favorite");
				$(this).find("div").html(parseInt(countNow) + 1);
				$(this).find("div").html($.number($(this).find("div").html()));
				$(this).append($(this).find("i").clone().addClass("animated"));
				$(this).find("i.animated").on("animationend webkitAnimationEnd oAnimationEnd MSAnimationEnd", function(e){
					$(this).remove();
				  $(this).off(e);
				});
			}else{
				$(this).find(".animated").remove();
				$(this).removeClass("active");
				$(this).find("i").addClass("ion-android-favorite-outline");
				$(this).find("i").removeClass("ion-android-favorite");
				$(this).find("div").html(parseInt(countNow) - 1);
				$(this).find("div").html($.number($(this).find("div").html()));

			}
			return false;
		});
	});

});