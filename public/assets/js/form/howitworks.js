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
	var strDesc	=	$('textarea#description').val();
	$('textarea#description').val(strDesc.trim());
}
/**
 * Save data to database
 */
function save(){
	var news_id			=	$('#news_id').val();
	var description		=	CKEDITOR.instances['description'].getData();
	var data 			=	{
		news_id		:	news_id,
		description 	:	description,
		};
	var url			=	'/master/news/savehowitworks';
	$.post(
			url
		,	data
		,	function(res){
				if(res==1){
					bootbox.alert('Save data successful', function(r) {
						location.reload();
					});
				}else if(res =='exist'){
					addError('Exits');
				}else{
					bootbox.alert('Save data error', function() {});
				}
			}
	);
}
/**
 * Editor plugin
 */
function BrowseServer()
{
	// You can use the "CKFinder" class to render CKFinder in a page:
	var finder = new CKFinder();
	finder.basePath = '../';	// The path for the installation of CKFinder (default = "/ckfinder/").
	finder.selectActionFunction = SetFileField;
	finder.popup();
}
// This is a sample function which is called when a file is selected in CKFinder.
function SetFileField(fileUrl)
{
	$('#news_image').attr('src',fileUrl);
	$('#image').val(fileUrl);
	$('#btn-del-image').show();
	$('#btm-add-image').val('Đổi ảnh');
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
	var url ="/master/news/table";
	$.post(url,data,function(res){
			$('#display-result').html(res);
		}
	);
}