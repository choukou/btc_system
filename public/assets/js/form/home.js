/**
 * Hiepnv
 * frm news list
 */
$(document).ready(function(){
	try {
	}
	catch(err) {
		console.log(err.message);
	}
});
/**
 * load data
 */
function loadTable(page){
	var data = {
			strSearch	:	''
		,	page		:	page
		,	page_size	:	10
	};
	var url ="/master/home/table";
	$.post(url,data,function(res){
			$('#display-result').html(res);
		}
	);
}