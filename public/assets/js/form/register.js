$(document).ready(function() {
	try {
		initialize();
		initEvents();
	} catch (e) {
		alert('ready' + e.message);
	}
});
function initialize() {
	try {
		$('#username').focus();
	} catch (e) {
		alert('initialize' + e.message);
	}
}
function initEvents() {
	try {
		$(document).on('click','#btn-save',function(){
			try {
				ShowWaiting($('#register'));

				$( "#form-register" ).find('div.validate-has-error').removeClass('validate-has-error');
				$( "#form-register" ).find('span.validate-has-error').remove();
				var validObj = ['username','password','email'];
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
						if(k=='username'){
							 var username = $('#'+k).val();
							 var re = /\s/igm;
							 if (re.test(username)) {
								 showError($('#'+k),'User name is not space.');
								 Err++;
							 }
						}
					}
				});
				if(Err > 0){
					CloseWaiting($('#register'));
					return;
				}else{
					var data = $( "#form-register" ).serializeArray();
					$.post( '/master/home/saveregister'
						,	data
						,	function(res){
								CloseWaiting($('#register'));
								if(res['status'] == 1){
									window.location = '/';
								}else if(res['status'] == 202 && res['msg'] == 'error_pwd_match' ){
									showError($('#password'),'Password not match!');
									showError($('#confirmpassword'),'Password not match!');
								}else if(res['status'] == 202 && res['msg'] == 'error_tpwd_match' ){
									showError($('#securitypassword'),'Security Password not match!');
									showError($('#confirmsecuritypassword'),'Security Password not match!');
								}else if(res['status'] == 0 && res['msg'] == 'exists_user_name' ){
									showError($('#username'),'User name is exist.');
								}else if(res['status'] == 0 && res['msg'] == 'exists_email' ){
									showError($('#email'),'Email is exist.');
								}else if(res['status'] == 0 && res['msg'] == 'not_exists_parent' ){
									showError($('#parent_name'),'Parent name is not exist.');
								} else {
									$('#modal-4').find('div.modal-body').text('Unsuccessful!');
									$('#modal-4').modal('show', {backdrop: 'static'});
								}
							}
					).fail(function() {
						CloseWaiting($('#register'));
					});
					return;
				}
			} catch (e) {
				CloseWaiting($('#register'));
				alert('btn-save :' + e.message);
			}
		});
	} catch (e) {
		alert('initialize' + e.message);
	}
}