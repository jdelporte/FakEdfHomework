@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading" id="user-info-content"></div>
                <div class="panel-body">
					<div class="row">						
						<div class="col-md-4 col-md-offset-0">
							<div>
								<b>Create Plant :</b>
								<form id="create-plant-form">
									<table>
									<tbody>
										<tr><th>Name: </th><th><input type="text" name="name"/></th></tr>
										<tr><th>Type: </th><th><select name="type" id="plant-types-select"></select></th></tr>									
										<tr><th>Capacity: </th><th><input type="text" name="capacity"/></th></tr>
										<tr><th><button type="button" onclick="createPlant()">create</button></th><th></th></tr>
									</tbody>
									</table>
								</form>
							</div>	
							<div>	
								<b>Plants stats :</b>
								<div id="plants-stats">
									<div id="plants-balance"></div>						
									<div id="plants-summary"></div>						
								</div>						
							</div> 
							<div>	
								<b>Plants list :</b>
								<div id="plants-list"></div>						
							</div> 
							
						</div>
						<div class="col-md-4 col-md-offset-0">							
							<div id="plant-info-content"></div>
						</div>
						<div class="col-md-4 col-md-offset-0">	
							<a href="javascript:runEventStream()">Run Event Stream</a>			
							<div id="event-stream-content"></div>
						</div>
					</div>
					
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
