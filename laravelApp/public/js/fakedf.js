
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
		if (data.length>0){
			var innerHtml = "<b>Plants list :</b><table>";
			for  (i=0; i<data.length; i++){
				innerHtml += "<tr><th><a href='javascript:refreshPlantInfo("+data[i]["id"]+")'>"+data[i]["name"]+"</a></th></tr>";			
				innerHtml += "<tr><th>"+data[i]["type_id"]+"</th></tr>";
				innerHtml += "<tr><th>Capacity:</th><th>"+data[i]["capacity"]+"</th></tr>";
				innerHtml += "<tr></tr>";
			}
			innerHtml += "</table>";
			$('#plants-list').html(innerHtml);
		}
	});
}

function refreshPlantInfo(plantId){
	var callUrl = '/api/users/'+userId+'/plants/'+plantId;
	var method = "GET";
	var token = $('meta[name="api_token"]').attr('content');	
	doRequest(callUrl,token,{},method,function(data){
		//alert(JSON.stringify(data));
		var innerHtml = "<b>Plant: "+data.name+"</b><br/>";
		innerHtml += "type: "+data.type_id+"<br/>";						
		innerHtml += "energy: "+data["current_energy"]["energy"]+"/"+data.capacity+"<br/>";
		$('#plant-info-content').html(innerHtml);

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

