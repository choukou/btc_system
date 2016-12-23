$(document).ready(function(){
	try {
		$(document).on('click', '.btn-save', function(){
			$( "#admin-content" ).find('div.validate-has-error').removeClass('validate-has-error');
			$( "#admin-content" ).find('span.validate-has-error').remove();
			var data = {};
			var tr = $(this).parents('tr');
			tr.find("input").each(function(i, element) {
				if(!$.isNumeric($(this).val())){
					 showError($(this),'Not numeric');
					 return;
				}

				data[$(this).attr('name')] = $(this).val();
			});
			$.post('/admin/manager/savepk', data, function(res){
				if(res['status'] == 1){
					window.location = '/admin';
//				} else {
//					var msg  = res['msg'] ? res['msg']: res['Exception'];
//					$('#modal-4').find('div.modal-body').text(msg);
//					$('#modal-4').modal('show', {backdrop: 'static'});
				}
			});
		});

		$(document).on('click', '.btn-save-rate', function(){
			$( "#admin-content" ).find('div.validate-has-error').removeClass('validate-has-error');
			$( "#admin-content" ).find('span.validate-has-error').remove();
			var data = {};
			var tr = $(this).parents('tr');
			tr.find("input").each(function(i, element) {
				if(!$.isNumeric($(this).val()) && $(this).attr('name') != 'wallet'){
					 showError($(this),'Not numeric');
					 return;
				}

				data['name'] = $(this).attr('name');
				data['value'] = $(this).val();
			});
			$.post('/admin/manager/saverate', data, function(res){
				if(res['status'] == 1){
					window.location = '/rate';
//				} else {
//					var msg  = res['msg'] ? res['msg']: res['Exception'];
//					$('#modal-4').find('div.modal-body').text(msg);
//					$('#modal-4').modal('show', {backdrop: 'static'});
				}
			});
		});
		$(document).on('click', '.btn-save-user', function(){
			var data = {};
			data['user_id']= $(this).attr('user_id');
			data['status']= $(this).attr('status');
			$.post('/admin/manager/updatestatus', data, function(res){
				if(res['status'] == 1){
					window.location = '/listuser';
				}
			});
		});


		$(document).on('click', '#btn_search_user', function(){
			var data = {};
			data['user_name'] = $('input[name=user_name]').val();
			$.post('/admin/manager/listuser', data, function(res){
				$('#admin-content-user').html(res);
			});
		});

		$(document).on('click', '#receive', function(){
			var data = {};
			data['nb_bit'] = $('#nb_bit').val();
			ShowWaiting($('#wallet-admin'));
			$.post('/admin/manager/walletadmin', data, function(res){
				console.log(res);

				if(res['status'] == 1){
					setTimeout(function(){
						CloseWaiting($('#wallet-admin'));
						window.location = '/walletadmin';
					}, 10000);
				} else {
					CloseWaiting($('#wallet-admin'));
					$('#modal-4').find('div.modal-body').text(res['msg']);
					$('#modal-4').modal('show', {backdrop: 'static'});

				}
			});
		});

		$(document).on('change', '#setting_home', function() {
			var idsetting = $(this).val();
			$('#st-id').val(idsetting);
			$.each(dataSetting, function(key, object) {
				if(idsetting == object.id) {
					console.log(object.content);
					$('#st-title').val(object.title);
					$('#st-img').attr('src', object.img_path);
					$('#st-img-path').val(object.img_path);
					$('#link').val(object.link);
					var decoded = $("<textarea/>").html(object.content).text();
					editor.setValue(decoded, true);
				}
			});
		});

		$(document).on('click', '#btn-save', function() {
			var data = $( "#form_setting" ).serialize();
			$.post('/admin/manager/saveseting', data, function(res){
				if(res['status'] == 1){
					window.location = '/settingshomepage';
				} else {
					CloseWaiting($('#wallet-admin'));
					$('#modal-4').find('div.modal-body').text(res['msg']);
					$('#modal-4').modal('show', {backdrop: 'static'});

				}
			});
		});

	} catch(e) {
		console.log(e.message);
	}
});


