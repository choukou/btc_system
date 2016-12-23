$(document).ready(function(){
	try {

		var pack = $('#ph-content').data('default-pack');
		if(pack > 0) {
			getCreatePh(pack);
		}
		$(document).on('click', '#btn_create_ph', function(){
			getCreatePh(0);
		});

		$(document).on('change', '#package_ph', function() {
			var idpackage  = $(this).val();
			var option = $(this).find('option[value=' + idpackage + ']');
			var start = 1*option.attr('start');
			var end = 1*option.attr('end');
			var distance = 1*option.attr('distance');
			var interest_rate = option.attr('interest_rate');
			var pin_cost = option.attr('pin_cost');
			$('#note-package').html('Min:' + start + ', Max:' + end);
//			var html = '';
//			while (start <= end) {
//				if(distance < 1){
//					start = 1*start.toFixed(1);
//				}
//				html += '<option value=' + start + '>' + start + '</option>';
//				start += distance;
//			}
//			$('#bitcoin_nb').html(html);


		});

		$(document).on('click', '#create', function(){
			if(validateSend()){
				var data = {};
				data.package_id = $('#package_ph').val();
				data.bitcoin =$('#bitcoin_nb').val();
				ShowWaiting($('#ph-content'));
				setTimeout(function(){
					$.post('/donate/provide/saveph', data, function(res){
						CloseWaiting($('#ph-content'));
//						console.log(res);
						if(res['status'] == 1){
							window.location = '/providehelp';
						} else {
							var msg  = res['msg'] ? res['msg']: res['Exception'];
							$('#modal-4').find('div.modal-body').text(msg);
							$('#modal-4').modal('show', {backdrop: 'static'});
						}
					});
				}, 10000);
			}
		});

	} catch(e) {
		console.log(e.message);
	}
});

function getCreatePh(pack) {
	$.post('/createph', {pack: pack}, function(res){
		$('#ph-content').html(res);
		if(pack > 0) {
			$('#package_ph').trigger('change');
		}
	});
}
function validateSend() {
	if(!validateRequired()){
		return false;
	}
	var idpackage  = $('#package_ph').val();
	var option = $('#package_ph').find('option[value=' + idpackage + ']');
	var start = 1*option.attr('start');
	var end = 1*option.attr('end');

	var bitcoin =1*$('#bitcoin_nb').val();
	if(bitcoin < start || bitcoin > end) {
		showError($('#bitcoin_nb'), 'Number bitcoin is not good!');
		return false;
	}

	return true;
}

