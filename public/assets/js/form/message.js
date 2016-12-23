/**
 * Hiepnv
 * frm news list
 */
$(document).ready(function(){
	try {
		initEvents();
	}
	catch(err) {
		console.log(err.message);
	}
});

function initEvents()
{
	$('#btn-save').click(function(){
		save();
	});
	$(document).on('click','.btn-delete',function(){
		deletedata($(this));
	})
	var strDesc	=	$('textarea#content').val();
	$('textarea#content').val(strDesc.trim());
}
/**
 * cancel
 */
function cancel(){
	local.reload();
}
/**
 * Save data to database
 */
function save(){
	var validObj = ['title','description'];
	var Err = 0;
	$.each(validObj,function(e,k){
		if($('#'+k).val()==''){
			showError($('#'+k),'This field is required.');
			Err++;
		}
	});
	if(Err > 0){
		return;
	}else{
		var msg_id			=	$('#msg_id').val();
		var content			=	CKEDITOR.instances['msg_content'].getData();
		var user_to_id		=	$('#user_to_id').val();
		var data 			=	{
			msg_id		:	msg_id,
			content 	 :	content,
			user_to_id	: user_to_id
			};
		var url			=	'/master/message/save';
		$.post(
				url
			,	data
			,	function(res){
					if(res==1){
						bootbox.alert('Send successful', function(r) {
							location.href='/master/message/index';
						});
					}else if(res =='exist'){
						addError(news_name_exist);
					}else{
						bootbox.alert('Send error', function() {});
					}
				}
		);
	}
}
function deletedata(_this){
	var msg_id			=	$(_this).attr('msg_id');
	var data 			=	{msg_id		:	msg_id};
	var url			=	'/master/message/delete';
	$.post(
			url
		,	data
		,	function(res){
				if(res==1){
					bootbox.alert('Delete data successful', function(r) {
						location.href='/master/message/index';
					});
				}else{
					bootbox.alert('Save data error', function() {});
				}
			}
	);
}
/**
 * load data
 */
function loadTable(page){
	var strSearch = $('#txt-search').val().trim();
	var data = {
			strSearch	:	strSearch
		,	page		:	page
		,	page_size	:	$('#page_size').val()
	};
	var url ="/master/message/table";
	$.post(url,data,function(res){
			$('#display-result').html(res);
		}
	);
}