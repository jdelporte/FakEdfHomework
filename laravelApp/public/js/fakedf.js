
var userId;
var plantId;
var plantTypes;

function doRequest(callUrl,token,params,method,successFunc){
	$.ajax({
		url: callUrl,
		data: $.extend(params,{api_token: token}),
		method: method,
		accepts: "application/json; charset=utf-8",
		success : successFunc
	});
}

function fillPlantTypesSelect(){
	var callUrl = '/api/planttypes';
	var method = "GET";
	doRequest(callUrl,{},{},method,function(data){
		plantTypes = data;
		//alert(JSON.stringify(plantTypes));
		var innerHtml = "";
		for (var i=0; i<plantTypes.length; i++){
			innerHtml += "<option value='"+plantTypes[i].id+"' >"+plantTypes[i].type+"</option>";
		}
		$('#plant-types-select').html(innerHtml);
		refreshPlantStats();
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
			var innerHtml = "<table>";
			for  (i=0; i<data.length; i++){
				innerHtml += "<tr><th><a class='list-plant-name' href='javascript:refreshPlantInfo("+data[i]["id"]+")'>"+data[i]["name"]+"</a>";			
				innerHtml += "<div class='list-plant-type'>"+plantTypes.find(function (d){return d.id ===  data[i]["type_id"];}).type+"</div>";
				innerHtml += "<div class='list-plant-capacity'>Capacity: "+data[i]["capacity"]+"</div></th></tr>"
			}
			innerHtml += "</table>";
			$('#plants-list').html(innerHtml);
		}
	});
}

function refreshPlantStats(){	
	var callUrl = '/api/users/'+userId+'/balance';
	var method = "GET";
	var token = $('meta[name="api_token"]').attr('content');
	var summary;
	var balance;
	doRequest(callUrl,token,{},method,function(data){
		balance = data;
		innerHtml = "Balance : "+balance["consumption"]+"/"+balance["production"];	
		$('#plants-balance').html(innerHtml);
	});
	callUrl = '/api/users/'+userId+'/summary';
	doRequest(callUrl,token,{},method,function(data){
		summary = data
		innerHtml = "<table class='plants-summary'>";
		for (var key in summary) {
			type = plantTypes.find(function (d){return d.id == key;}).type;
			innerHtml += "<tr><th>"+type+"</th><th>"+summary[key].consumption+"/"+summary[key].production+"</th></tr>";
		}
		innerHtml += "</div>";	
		$('#plants-summary').html(innerHtml);
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
		innerHtml += "type: "+plantTypes.find(function (d){return d.id ===  data.type_id ;}).type+"<br/>";						
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
		refreshPlantStats();
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
		refreshPlantStats();
	})
}

function createPlant(){	
	var callUrl = '/api/users/'+userId+'/plants';
	var method = "POST";
	var token = $('meta[name="api_token"]').attr('content');
	var formTab = $("#create-plant-form").serializeArray();
	
	var params = {name:formTab[0].value,type_id:formTab[1].value,capacity:formTab[2].value};
	doRequest(callUrl,token,params,method,function(data){
		refreshPlantsList();
	});
}


fillUserInfoContent();
fillPlantTypesSelect();

