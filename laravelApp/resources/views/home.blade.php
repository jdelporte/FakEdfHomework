@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading" id="user-info-content"></div>
                <div class="panel-body">
					<div class="row">						
						<div class="col-md-4 col-md-offset-2">
							<div>
								<b>Create Plant :</b>
								<form id="create-plant-form">
									Name: <input type="text" name="name"/><br/>
									Type: <select name="type"></select><br/>									
									Capacity: <input type="text" name="capacity"/><br/>
									<button type="button" onclick="createPlant()">create</button>
								</form>
							</div>	
							<div>	
								<b>Plants list :</b>
								<div id="plants-list"></div>							
							</div>
						</div>
						<div class="col-md-4 col-md-offset-2">
						
						</div>
					</div>
					
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
