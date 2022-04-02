<?
ini_set("display_errors", "0");
    echo php_uname("n");
    
    

    $data = array(
    'email' => 'dl@gmail.com',
    'password' => 'Dl123456');
     print_r($data);
$postRequest = json_encode($data);
    $cURLConnection = curl_init('https://origin.watchbeem.com:8888/registeruser');
    print_r($cURLConnection);
    curl_setopt($cURLConnection, CURLOPT_POSTFIELDS, $postRequest);
    curl_setopt($cURLConnection, CURLOPT_RETURNTRANSFER, true);

	curl_setopt($cURLConnection, CURLOPT_HTTPHEADER, array(
    'Authorization: Basic c3dpZzpmc0hldXFxTENKTjM0N0Bl',
    'Content-Type: application/json'
	));

    $apiResponse = curl_exec($cURLConnection);
    curl_close($cURLConnection);

// $apiResponse - available data from the API request
  //  $jsonArrayResponse = json_decode($apiResponse);
	print_r($apiResponse);
	echo "done";

?>