
var userId;
var plantId;

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
		plantId = data.id;
		var innerHtml = "<b>Plant: "+data.name+"</b><br/>";
		innerHtml += "type: "+data.type_id+"<br/>";						
		innerHtml += "capacity: "+data.capacity+"<br/>";			
		innerHtml += "consumption: "+data["current_energy"]["actual_consumption"]+"/"+data["current_energy"]["production"]+"<br/>";
		
		innerHtml += "<form id='update-energy-form'>";
		innerHtml += "<input type='hidden' name='id' value='"+data.id+"'/><br/>";		
		innerHtml += "energy : <input type='text' name='energy'/><br/>";
		innerHtml += "<button type='button' onclick='produceEnergy()'>produce</button>";
		innerHtml += "<button type='button' onclick='consumeEnergy()'>consume</button>";
		innerHtml += "</form>";
		
		innerHtml += "<table id='plant-historic'>";
		innerHtml += "<tr><th>time</th><th>producted</th><th>consumed</th><th>expected</th></tr>";		
		for (var i=0; i<data.plant_energies.length; i++){
			en = data.plant_energies[i];
			innerHtml += "<tr><th>"+en.time+"</th><th>"+en.production+"</th>";
			innerHtml += "<th>"+en.actual_consumption+"</th><th>"+en.wanted_consumption+"</th></tr>";
		}	
		innerHtml += "</table>";
		
		$('#plant-info-content').html(innerHtml);
		
	});
}

function produceEnergy(){	
	var formTab = $("#update-energy-form").serializeArray();
	var callUrl = '/api/users/'+userId+'/plants/'+formTab[0].value+"/produce";
	var method = "POST";
	var token = $('meta[name="api_token"]').attr('content');
	var params = {energy:formTab[1].value};
	doRequest(callUrl,token,params,method,function(data){
		refreshPlantInfo(formTab[0].value);
	})
}

function consumeEnergy(){	
	var formTab = $("#update-energy-form").serializeArray();
	var callUrl = '/api/users/'+userId+'/plants/'+formTab[0].value+"/consume";
	var method = "POST";
	var token = $('meta[name="api_token"]').attr('content');
	var params = {energy:formTab[1].value};
	doRequest(callUrl,token,params,method,function(data){
		refreshPlantInfo(formTab[0].value);
	})
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

