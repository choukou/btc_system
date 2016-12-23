$(document).ready(function(){
	try {
		initProfileEvents();
	}
	catch(err) {
		console.log(err.message);
	}
});
function initProfileEvents()
{
	$(document).on('click', '#btn-change:enabled', function() {
		var btnChange = $(this);
		btnChange.prop('disabled', true);
		$.post('/sendcode', '', function(res) {
			if(res['status'] == 1){
				btnChange.addClass('hidden');
				$('#btn-update-account').parent().removeClass('hidden');
				$('#code').parents('div.control-group.hidden').removeClass('hidden');
				$('input:not(#user_name)').removeAttr('readonly');
			} else {
				bootbox.alert('Send Code to email error!', function(r) {});
				btnChange.prop('disabled', false);
			}

		});

	});
	$(document).on('click','#btn-update-account',function(){
		try{
			var validObj = ['email','phone','wallet'];
			var Err = 0;
			$.each(validObj,function(e,k){
				if($('#'+k).val()==''){
					showError($('#'+k),'This field is required.');
					Err++;
				}else{
					if(k=='email'){
						 var email = $('#'+k).val();
						 var re = /[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,4}/igm;
						 if (!re.test(email)) {
							 showError($('#'+k),'Email invalid.');
							 Err++;
						 }
					}
				}
			});
			if(Err > 0){
				return;
			}else{
				var data ={
						user_id 	: $.trim($('#user_id').val()),
						email		: $.trim($('#email').val()),
						phone		: $.trim($('#phone').val()),
						wallet		: $.trim($('#wallet').val()),
						code		: $.trim($('#code').val())
				};
				$.post( '/master/auth/updateaccount'
					,	data
					,	function(res){
							if(res==1){
								bootbox.alert('Save data successful', function(r) {
									location.reload();
								});
							}else{
								bootbox.alert('Data input incorrect', function(r) {});
							}
						}
				);
				return;
			}
		}catch(e){
			alert('btn-update-account '+e.message);
		}
	})
	$(document).on('click','#changepassword',function(){
		try{
			var validObj = ['old_password','new_password','confirm_password'];
			var Err = 0;
			$.each(validObj,function(e,k){
				if($('#'+k).val()==''){
					showError($('#'+k),'This field is required.');
					Err++;
				}
			});
			if(Err > 0){
				return;
			}
			if($('#new_password').val().trim() !== $('#confirm_password').val().trim()){
				showError($('#confirm_password'),'New password and confirm new password are the same');
				Err++;
			}
			if(Err > 0){
				return;
			}else{
				var data ={
						user_id 		:$('#user_id').val().trim(),
						old_password	:$('#old_password').val().trim(),
						new_password	:$('#new_password').val().trim(),
				};
				$.post( '/master/auth/updatepassword'
					,	data
					,	function(res){
							if(res==1){
								bootbox.alert('Save data successful', function(r) {
									location.reload();
								});
							}else{
								bootbox.alert('Data input incorrect', function(r) {});
							}
						}
				);
				return;
			}
		}catch(e){
			alert('changepassword '+e.message);
		}
	})
	$(document).on('click','#change_t_password',function(){
		try{
			var validObj = ['t_old_password','t_new_password','t_confirm_password'];
			var Err = 0;
			$.each(validObj,function(e,k){
				if($('#'+k).val()==''){
					showError($('#'+k),'This field is required.');
					Err++;
				}
			});
			if(Err > 0){
				return;
			}
			if($('#t_new_password').val().trim() !== $('#t_confirm_password').val().trim()){
				showError($('#confirm_password'),'New password and confirm new password are the same');
				Err++;
			}
			if(Err > 0){
				return;
			}else{
				var data ={
						user_id 		:$('#user_id').val().trim(),
						old_tpassword	:$('#t_old_password').val().trim(),
						new_tpassword	:$('#t_new_password').val().trim(),
				};
				$.post( '/master/auth/updatetpassword'
					,	data
					,	function(res){
							if(res==1){
								bootbox.alert('Save data successful', function(r) {
									location.reload();
								});
							}else{
								bootbox.alert('Data input incorrect', function(r) {});
							}
						}
				);
				return;
			}
		}catch(e){
			alert('changepassword '+e.message);
		}
	})
	$(document).on('click','#updatebank',function(){
		try{
			var validObj = ['user_id','bank_acc_id','bank_name','bank_branch_name','bank_acc_name','bank_acc_number','bank_link_phone'];
			var Err = 0;
			var data = {};
			$.each(validObj,function(e,k){
				if($('#'+k).val()==''){
					showError($('#'+k),'This field is required.');
					Err++;
				}else{
					data[k] = $('#' + k).val();
				}
			});
			if(Err > 0){
				return;
			}else{
				$.post( '/master/auth/updatebank'
					,	data
					,	function(res){
							if(res==1){
								bootbox.alert('Save data successful', function(r) {
									location.reload();
								});
							}else{
								bootbox.alert('Data input incorrect', function(r) {});
							}
						}
				);
				return;
			}
		}catch(e){
			alert('changepassword '+e.message);
		}
	})
}