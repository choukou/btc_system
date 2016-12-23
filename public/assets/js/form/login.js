$(document).ready(function(){
	init();
	$('#btn-login').click(function(){

		if(validate($('form#login'))){
			var captchaForm = $(this).attr('captcha');
			var captchaInput = $('#captcha').val();
			if(captchaForm == captchaInput) {
				var data = {};
				data.username = $.trim($('#login #username').val());
				data.passwd = $.trim($('#login #passwd').val());
				$.post('/master/auth/login', data, function(res){
					if(res['status'] == 1){
						window.location = res['auth'].HTTP_REFERER;
					} else {
						showError($('#passwd, #username'), 'User or password not match!');
					}
				});
			} else {
				showError($('#captcha'), 'Captcha not match!');
			}
		}
	});

	$(document).on('click', '#login-btn .logined', function(){
		$('.login-container').show();
	});
	$(document).on('click', '#login-close', function(){
		$('.login-container').hide();
	});
});

function init() {
	$('#login #username').trigger('focus');

	$("#login #passwd, #login #username").keyup(function (e) {
		if(e.keyCode == 13){
			$('#btn-login').trigger('click');
		}
	});
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
