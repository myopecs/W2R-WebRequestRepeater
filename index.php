<?php
/**
WeRequestRepeater

Author: Mr Hery
Date: 27 July 2023
Malaysia Open Cyber Security (MyOPECS)
**/
set_time_limit(0);

if(isset($_GET["start"])){
	header("Content-Type: text/plain");
	
	if(isset($_POST["data"])){
		$obj = $_POST["data"];
		$b64 = base64_encode($obj);
		echo $b64;
		@shell_exec("start php " . __DIR__ . "/p.php " . $b64);
	}
	
	die();
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
	<title>W2R WebRequestRepeater by MyOPECS</title>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" />
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css" />
	
</head>
<body>

<div class="container mt-2">
	<div class="row">
		<div class="col-md-12">
			<div class="card mb-2">
				<div class="card-header">
					Target Info:
				</div>
				
				<div class="card-body">
					<div class="row">
						<div class="col-md-3 mb-2">
							<select class="form-control" id="request_method">
								<option value="GET">GET</option>
								<option value="POST">POST</option>
								<option value="PUT">PUT</option>
								<option value="DELETE">DELETE</option>
							</select>
						</div>
						
						<div class="col-md-9 mb-2">
							<input type="text" class="form-control" id="target_url" placeholder="https://...." />
						</div>
					</div>
				</div>
			</div>
			
			<div class="card mb-2">
				<div class="card-header">
					Request Header 
					
					<button class="btn btn-sm btn-primary add-rh">
						<span class="fa fa-plus"></span> Add Header
					</button>
				</div>
				
				<div class="card-body rh-body">
					<div class="card mb-2 rh" data-row="1">
						<div class="card-body">
							<div class="row">
								<div class="col-12 mb-2">
									<button class="btn btn-sm btn-danger del-rh" data-row="1">
										<span class="fa fa-trash"></span> Delete
									</button>
								</div>
								
								<div class="col-md-4 col-12 mb-2">
									<input type="text" class="form-control" id="rhk_1" placeholder="Key" value="User-Agent" />
								</div>
								
								<div class="col-md-8 col-12 mb-2">
									<input type="text" class="form-control" id="rhv_1" value="Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:109.0) Gecko/20100101 Firefox/115.0" placeholder="Value" />
								</div>
							</div>
						</div>						
					</div>
				</div>
			</div>
			
			<div class="card mb-2">
				<div class="card-header">
					Request Body 
					
					<button class="btn btn-sm btn-primary add-rb">
						<span class="fa fa-plus"></span> Add Body
					</button>
				</div>
				
				<div class="card-body rb-body">
					<div class="card mb-2 rb" data-row="1">
						<div class="card-body">
							<div class="row">
								<div class="col-12 mb-2">
									<button class="btn btn-sm btn-danger del-rb" data-row="1">
										<span class="fa fa-trash"></span> Delete
									</button>
								</div>
								
								<div class="col-md-4 col-12 mb-2">
									<input type="text" class="form-control" id="rbk_1" placeholder="Key" value="param1" />
								</div>
								
								<div class="col-md-8 col-12 mb-2">
									<input type="text" class="form-control" id="rbv_1" value="just data" placeholder="Value" />
								</div>
							</div>
						</div>						
					</div>
				</div>
			</div>
			
			<div class="card mb-2">
				<div class="card-header">
					Launch Setting
				</div>
				
				<div class="card-body">
					<div class="alert alert-info">
						<strong>Info!</strong> You can run multiple time using parallel PHP by clicking the button start multiple time.
					</div>
					<!--PHP Parallel Mode (like thread):
					<input type="number" class="form-control" name="parallel" value="1" /><br />-->
				</div>
			</div>
			
			<button class="btn btn-lg btn-block btn-success" id="start">
				<span class="fa fa-rocket"></span> Start
			</button>
		</div>
	</div>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>

<script>
$("#start").on("click", function(){
	// alert();
	var target_url = $("#target_url").val();
	
	if(target_url.length < 1){
		alert("Please insert a valid URL.");
		return;
	}
	
	var request_method = $("#request_method").val();
	var headers = [];
	var bodies = [];
	
	$(".rh-body").children(".rh").each(function(){
		var row = $(this).data("row");
		var rhk = $("#rhk_" + row).val();
		var rhv = $("#rhv_" + row).val();
		
		if(rhk.length > 0){
			headers.push(rhk + ": " + rhv);
		}
	});
	
	$(".rb-body").children(".rb").each(function(){
		var row = $(this).data("row");
		var rbk = $("#rbk_" + row).val();
		var rbv = $("#rbv_" + row).val();
		
		if(rbk.length > 0){
			bodies.push(rbk + "=" + rbv);
		}
	});
	
	//var parallel = parseInt($("#parallel").val());
	
	var robj = {
		target_url: target_url,
		request_method: request_method,
		headers: headers,
		bodies: bodies
	};
	
	console.log(robj);
	
	parallel = 1;
	if(parallel > 1){
		var nop = 0;
		
		var tv = setInterval(function(){
			$.ajax({
				url: "index.php?start",
				method: "POST",
				data: {
					action: "parallel",
					data: JSON.stringify(robj)
				}
			});
			nop += 1;
			
			if(nop >= parallel){
				clearInterval();
			}
		}, 1000);
	}else{
		$.ajax({
			url: "index.php?start",
			method: "POST",
			data: {
				action: "parallel",
				data: JSON.stringify(robj)
			}
		}).done(function(res){
			console.log(res);
		});
	}
});

var rh_id = 1;
var rb_id = 1;

$(".add-rh").on("click", function(){
	rh_id += 1;
	
	$(".rh-body").append('\
		<div class="card mb-2 rh" data-row="'+ rh_id +'">\
			<div class="card-body">\
				<div class="row">\
					<div class="col-12 mb-2">\
						<button class="btn btn-sm btn-danger del-rh" data-row="'+ rh_id +'">\
							<span class="fa fa-trash"></span> Delete\
						</button>\
					</div>\
					\
					<div class="col-md-4 col-12 mb-2">\
						<input type="text" class="form-control" id="rhk_'+ rh_id +'" placeholder="Key" />\
					</div>\
					\
					<div class="col-md-8 col-12 mb-2">\
						<input type="text" class="form-control" id="rhv_'+ rh_id +'" placeholder="Value" />\
					</div>\
				</div>\
			</div>\
		</div>\
	');
});

$(document).on("click", ".del-rb", function(){
	var row = $(this).data("row");
	
	$(".rb[data-row="+ row +"]").remove();
});

$(".add-rb").on("click", function(){
	rh_id += 1;
	
	$(".rb-body").append('\
		<div class="card mb-2 rb" data-row="'+ rh_id +'">\
			<div class="card-body">\
				<div class="row">\
					<div class="col-12 mb-2">\
						<button class="btn btn-sm btn-danger del-rb" data-row="'+ rh_id +'">\
							<span class="fa fa-trash"></span> Delete\
						</button>\
					</div>\
					\
					<div class="col-md-4 col-12 mb-2">\
						<input type="text" class="form-control" id="rbk_'+ rh_id +'" placeholder="Key" />\
					</div>\
					\
					<div class="col-md-8 col-12 mb-2">\
						<input type="text" class="form-control" id="rbv_'+ rh_id +'" placeholder="Value" />\
					</div>\
				</div>\
			</div>\
		</div>\
	');
});

$(document).on("click", ".del-rh", function(){
	var row = $(this).data("row");
	
	$(".rh[data-row="+ row +"]").remove();
});
</script>

<br />
<br />
<br />
<br />
<br />
<br />
<br />
<br />
<br />
<br />
<br />
<br />
</body>
</html> 