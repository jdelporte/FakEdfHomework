


function doRequest(callUrl,token,params,method,successFunc){
	$.ajax({
		url: callUrl,
		data: $.extend(params,{api_token: token}),
		method: method,
		accepts: "application/json; charset=utf-8",
		success : successFunc
	});
}

$('#testApi').on('click',function(){
	var callUrl = '/api/users/1/plants';
	var token = $('meat[name="api_token"]').Attr('content');
	doRequest(callUrl,token,{},"GET",function(data){
		alert(JSON.stringify(data));
	});
});
