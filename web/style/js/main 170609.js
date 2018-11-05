$(document).ready(function(){
	$('#roll_top').hide();
	$(window).scroll(function () {
		if ($(window).scrollTop() > 400) {
			$('#roll_top').fadeIn(400);//当滑动栏向下滑动时，按钮渐现的时间
		} else {
			$('#roll_top').fadeOut(0);//当页面回到顶部第一屏时，按钮渐隐的时间
		}
	});
	$('.tot').click(function () {
		$('html,body').animate({
			scrollTop : '0px'
		}, 400);//返回顶部所用的时间 返回顶部也可调用goto()函数
	});
});

$(document).ready(function(){
	$('.buy-ts').hide();
	$(window).scroll(function () {
		if ($(window).scrollTop() > 400) {
			$('.buy-ts').fadeOut(400);//当滑动栏向下滑动时，按钮渐现的时间
		} else {
			$('.buy-ts').fadeIn(0);//当页面回到顶部第一屏时，按钮渐隐的时间
		}
	});

});


function goto(selector){
	$.scrollTo ( selector , 1000);	
}

$(document).ready(function(){
    $("#explain-open1").click(function(){
        $("#explain-txt1").toggleClass("explain-more");
    })
});


$(document).ready(function(){
    $("#explain-open2").click(function(){
        $("#explain-txt2").toggleClass("explain-more");
    })
});
mui('.mui-bar-tab,.mui-scroll').on('tap','a',function(){document.location.href=this.href;});

$(function(){
	var size = $(window).height();
	$("body,html").css("height",size);
});










