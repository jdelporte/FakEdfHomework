
var userId;

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
	var method = "GET";
	var token = $('meta[name="api_token"]').attr('content');
	doRequest(callUrl,token,{},method,function(data){
		alert(JSON.stringify(data));
	});
});


function fillUserInfoContent(){	
	var callUrl = '/api/users';
	var method = "GET";
	var token = $('meta[name="api_token"]').attr('content');
	doRequest(callUrl,token,{},method,function(data){
		innerHtml = "<table><tr><th>name: </th><th>"+data.name+"</th></tr>"+
					"<tr><th>email: </th><th>"+data.email+"</th></tr></table>";
		$('#user-info-content').html(innerHtml);
		userId = data.id;		
		refreshPlantsList();
	});
}

function refreshPlantsList(){
	var callUrl = '/api/users/'+userId+'/plants';
	var method = "GET";
	var token = $('meta[name="api_token"]').attr('content');	
	doRequest(callUrl,token,{},method,function(data){
		//alert(JSON.stringify(data));
		var innerHtml = "<table>";
		for  (i=0; i<data.length; i++){
			innerHtml += "<tr><th>"+data[i]["name"]+"</th></tr>";			
			innerHtml += "<tr><th>"+data[i]["type_id"]+"</th></tr>";
			innerHtml += "<tr><th>Capacity:</th><th>"+data[i]["capacity"]+"</th></tr>";
			innerHtml += "<tr></tr>";
		}
		innerHtml += "</table>";
		$('#plants-list').html(innerHtml);
	});
}

function createPlant(){	
	var callUrl = '/api/users/'+userId+'/plants';
	var method = "POST";
	var token = $('meta[name="api_token"]').attr('content');
	var formTab = $("#create-plant-form").serializeArray();
	var params = {name:formTab[0].value,type_id:1,capacity:formTab[1].value};
	doRequest(callUrl,token,params,method,function(data){
		refreshPlantsList();
	});
}


fillUserInfoContent();

