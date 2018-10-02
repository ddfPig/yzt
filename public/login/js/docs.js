$(function(){
	 $('body').running();
//$('.slui').each(count) 
	 $(".shujulist li").each(function(){
			if(($(this).index()+1)%5==0){
				$(this).css("margin-right","0");
			}
		})	
		
	$(".loginbtn").click(function(){
		$(".falsetip").show();
	})
})

