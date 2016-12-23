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
		$('#user_to_id').focus();
	} catch (e) {
		alert('initialize' + e.message);
	}
}
function initEvents() {
	try {
		$('#btn-upload').click(function(){
			$('#image').trigger('click');
		});
		$('#fileupload').change(function(){
			$('#subfile').val($(this).val());
			var file = $(this)[0].files[0];
			$('#imgshow').src = file.getAsDataURL();
		});
		$('#btn-del-image').click(function(){
			delImage();
		});
		$(document).on('click','.btn-delete',function(){
			deletedata($(this));
		})
		$(document).on('click','#btn-save',function(){
			try {
				var validObj = ['user_to_id','content'];
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
					var news_id			=	$('#news_id').val();
					var news_title		=	$('#title').val();
					var description		=	$('#description').val();//CKEDITOR.instances['description'].getData();
					var image			=	$('#image').val();
					var data 			=	{
						news_id		:	news_id,
						title : news_title,
						image : image,
						description 	:	description,
						};
					$.post( '/master/news/save'
						,	data
						,	function(res){
								if(res==1){
									window.location = '/master/news/index';
								}else{
									alert('Error');
								}
							}
					);
					return;
				}
			} catch (e) {
				alert('btn-save :' + e.message);
			}
		}); 
	} catch (e) {
		alert('initialize' + e.message);
	}
}
function deletedata(_this){
	var news_id			=	$(_this).attr('news_id');
	var data 			=	{news_id		:	news_id};
	var url			=	'/master/news/delete';
	$.post(
			url
		,	data
		,	function(res){
				if(res==1){
					bootbox.alert('Delete data successful', function(r) {
						location.href='/master/news/index';
					});
				}else{
					bootbox.alert('Save data error', function() {});
				}
			}
	);
}
function delImage(){
	$('#image').val('');
	$('#news_image').attr('src','');
	$('#btn-del-image').hide();
}
function BrowseServer()
{
	// You can use the "CKFinder" class to render CKFinder in a page:
	var finder = new CKFinder();
	finder.basePath = '../';	// The path for the installation of CKFinder (default = "/ckfinder/").
	finder.selectActionFunction = SetFileField;
	finder.popup();
}
//This is a sample function which is called when a file is selected in CKFinder.
function SetFileField(fileUrl)
{
	$('#news_image').attr('src',fileUrl);
	$('#image').val(fileUrl);
	$('#btn-del-image').show();
	$('#btm-add-image').val('Đổi ảnh');
}