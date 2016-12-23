$(document).ready(function(){
	try {
		requestPin();
		sendPin();
	} catch(e) {
		console.log(e.message);
	}
});

$(document).on('click', '#send_tk', function(){
	if(validateSend()){
		var data = {};
		data.user_id = $('#tranfer_user').val();
		data.pin =$('#nb_tokens').val();
		$.post('/donate/pin/savesendpin', data, function(res){
			if(res['status'] == 1){
				window.location = '/token';
			} else {
				$('#modal-4').find('div.modal-body').text('Unsuccessful!');
				$('#modal-4').modal('show', {backdrop: 'static'});
			}
		});
	}
});
$(document).on('click', '#request_tk', function(){
	var btn = $(this);
	if(validateRequest()){
		$('.main-content').css('cursor', 'wait');
		btn.prop('disabled', true);
		$.post('/donate/pin/saverequestpin', {pin: $('#nb_tokens_rq').val()}, function(res){
			btn.prop('disabled', false);
			$('.main-content').css('cursor', 'default');
			if(res['status'] == 1){
				window.location = '/token';
			} else if(res['status'] == 202) {
				$('#modal-4').find('div.modal-body').text(res['Exception']);
				$('#modal-4').modal('show', {backdrop: 'static'});
			} else {
				$('#modal-4').find('div.modal-body').text('Unsuccessful!');
				$('#modal-4').modal('show', {backdrop: 'static'});
			}
		});
	}
});

$(document).on('keyup', '#nb_tokens_rq', function(){
	var sell = $('#sell').val();
	var number = $(this).val();
	if($.isNumeric(number) && 1*number > 0){
		$('#bc_cost').html('');
		if(sell > 0){
			$('#bc_cost').append(number*pin_cost*sell + '$' );
		}else {
			$('#bc_cost').append(number*pin_cost + '<i class="fa fa-btc" aria-hidden="true"></i>');
		}
	}else{
		$('#bc_cost').html('');
	}

});

function requestPin(){
	$(document).on('click', '#request_pin', function(){
		$.post('/requestpin', {}, function(res){
			$('#pin-content').html(res);
		});
	});
}

function sendPin() {
	$(document).on('click', '#send_pin', function(){
		$.post('/sendpin', {}, function(res){
			$('#pin-content').html(res);
		});
	});
}

function validateSend() {
	if(!validateRequired()){
		return false;
	}
	var token = $('#nb_tokens').val();
	if(!$.isNumeric(token) || 1*token < 1){
		showError($('#nb_tokens'), 'Format is not good!');
		return false;
	}
	if(token > $('#pin-content').attr('tokens')*1){
		showError($('#nb_tokens'), 'Tokens is not enough!');
		return false;
	}
	return true;
}

function validateRequest() {
	if(!validateRequired()){
		return false;
	}
	var number = $('#nb_tokens_rq').val();
	if(!$.isNumeric(number) || 1*number < 0){
		showError($('#nb_tokens'), 'Format is not good!');
		return false;
	}
	return true;
}