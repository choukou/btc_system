$(document).ready(function() {
	try {

	} catch (e) {
		alert('ready' + e.message);
	}
});
function showError(_this,msg) {
	try {
		$(_this).next('span').remove();
		$(_this).parents('.form-group').addClass('validate-has-error');
		var strError = '<span class="validate-has-error name-error">'+msg+'.</span>';
		$(_this).parent('div').append(strError);
	} catch (e) {
		alert('showError' + e.message);
	}
}
function getData(obj) {
	try {
		var data = {};
		$.each(obj, function(key, element) {
			switch (element.type) {
			case 'text':
				data[key] = $('#' + key).val();
				break;
			case 'select':
				data[key] = $('#' + key).val();
				if (!data[key]) {
					data[key] = -1;
				}
				break;
			case 'radiobox':
				var name = element['attr']['name'];
				data[key] = $("input[name='" + name + "']:checked").val();
				break;
			case 'checkbox':
				data[key] = false;
				if ($('#' + key).is(":checked")) {
					data[key] = true;
				}
				break;
			case 'money':
				data[key] = $.mbRTrim($('#' + key).val()).replace(/,/g, '');
				break;
			case 'numeric':
				data[key] = $.mbRTrim($('#' + key).val()).replace('', 0);
				break;
			default:
				break;
			}
			;
		});
		return data;
	} catch (e) {
		alert('getData: ' + e.message);
	}
}

function validateRequired() {
	var error = 0;
	$('.required:not(label,div)').each(function(index){
		var formGroup = $(this).parents('div.form-group');
		formGroup.removeClass('validate-has-error');
		formGroup.find('span.validate-has-error').remove();

		if($(this).val().length == 0) {
			showError($(this), 'This is required field.');
			error++;
		}
	});
	return error ? false: true;
}


function ShowWaiting(el) {
	var html = '<div class="waiting">';
	html += '<div>';
	html += '<img alt="" src="/assets/loading.gif">';
	html += '</div>';
	html += '</div>';
	el.append(html);
	el.find('.waiting').height(el.height());
}

function CloseWaiting(el) {
	el.find('.waiting').remove();
}