$(document).ready(function(){
	try {
		$(document).on('click', '#btn_add_to_tree', function(){
			getAddTree();
		});

		$(document).on('click', '#add', function(){
			if(validateSend()){
				var data = {};
				data.parent_user_name = $.trim($('#parent_user').val());
				data.child_user_id =$('#child_user').val();
				$.post('/master/tree/saveaddtree', data, function(res){
					if(res['status'] == 1){
						window.location = '/referral';
					} else {
						var msg  = res['msg'] ? res['msg']: res['Exception'];
						$('#modal-4').find('div.modal-body').text(msg);
						$('#modal-4').modal('show', {backdrop: 'static'});
					}
				});
			}
		});

	} catch(e) {
		console.log(e.message);
	}
});

function getAddTree() {
	$.post('/addtree', {}, function(res){
		$('#referral-content').html(res);
	});
}
function validateSend() {
	if(!validateRequired()){
		return false;
	}

	return true;
}

