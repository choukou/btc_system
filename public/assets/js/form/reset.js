$(document).ready(function(){
	init();
	$('#btn-reset').click(function(){
		if(validate($('form#reset'))){
			var data = {};
			data.email = $.trim($('#email').val());
			$.post('/master/auth/resetpassword', data, function(res){
				if(res['status'] == 1){
					window.location = '/changepassword?' + encodeURI('email=' + res['email']);
				} else {
					showError($('#email'), 'Email not exists!');
				}
			});
		}
	});

	$(document).on('click','#btn-change-password' ,function(){
		if(validate($('form#reset'))){
			var data = $( "form#reset" ).serialize();
			var email = getQueryVariable("email");
			data = data + '&email='+ email;
			$.post('/master/auth/changepassword', data, function(res){
				if(res['status'] == 1){
					window.location = '/login';
				}else if(res['status'] == 0 && res['msg'] == 'error_pwd_match' ){
					showError($('#password'),'Password not match!');
				} else {
					showError($('#password'),'Unsuccessful!');
				}
			});
		}
	});
});

function init() {
	$('input:first').trigger('focus');
}

function validate(form) {
	var error = 0;
	form.find('div.validate-has-error').removeClass('validate-has-error');
	form.find('span.validate-has-error').remove();
	var $fields = form.find('[data-validate]');
	$fields.each(function(index, el) {
		var $field = $(el);
		if($field.val().length == 0) {
			showError($(this), 'This is required field.');
			error++;
		}
	});

	return error ? false: true;
}
function getQueryVariable(variable) {
	var query = window.location.search.substring(1);
	var vars = query.split("&");
	for (var i=0;i<vars.length;i++) {
		var pair = vars[i].split("=");
		if (pair[0] == variable) {
			return pair[1];
		}
	}
}
