function ajaxMoveRequest(url, tableId){
	$.ajax({
		url: url,
		data: {ajax:1},
		method: "get",
		success: function(){
			$("#"+tableId).yiiGridView.update(tableId);
		}
	});
}