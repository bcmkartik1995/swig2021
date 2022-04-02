<?php
ini_set("display_errors", "1");
// Enter your Host, username, password, database below.
//$con = mysqli_connect("localhost", "swigtv_master", "Ojm84ftyv2igSKiW8Zc8 ", "swig_tv_backend", "3308");
$con = mysqli_connect("localhost", "swigmedi_samgr", "zN7j.Sm1RP]- ", "swigmedi_swigappmanager", "3306");
print_r($con);
echo "done";
if (mysqli_connect_errno())
{
	echo "Failed to connect to MySQL: " . mysqli_connect_error();
	die();
}


?>