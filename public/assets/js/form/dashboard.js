$(document).ready(function(){
	var current = $('#r_wallet').attr('r_wallet');
	setInterval(function(){calcProfitBonus(current, rate_day);},1000);
});

function calcProfitBonus(current, rate_day) {
	var currentdate = new Date();
	var rate_vl = (currentdate.getHours()*60 + currentdate.getMinutes() * 60 + currentdate.getSeconds())*rate_day*1000/(24*60*60);
	var new_vl = ((current*1000 + rate_vl)/1000).toFixed(8);
	 $('#r_wallet').text(new_vl);
//	 $('#r_wallet').attr('r_wallet', new_vl);
//	 $('#p_r_wallet').attr('data-to', new_vl);

}