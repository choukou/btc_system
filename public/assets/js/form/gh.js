$(document).ready(function(){
	try {
		$(document).on('click', '#btn_create_gh', function(){
			getCreatePh();
		});

		$(document).on('click', '#create', function(){
			var data = {};
			$.post('/donate/get/savegh', data, function(res){
//				console.log(res);
				if(res['status'] == 1){
					window.location = '/gethelp';
				} else {
					var msg  = res['msg'] ? res['msg']: res['Exception'];
					$('#modal-4').find('div.modal-body').text(msg);
					$('#modal-4').modal('show', {backdrop: 'static'});
				}
			});
		});

	} catch(e) {
		console.log(e.message);
	}
});

function getCreatePh() {
	$.post('/creategh', {}, function(res){
		$('#gh-content').html(res);
	});
}

