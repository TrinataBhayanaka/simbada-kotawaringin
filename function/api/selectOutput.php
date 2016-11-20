<?php
include "../../config/database.php";  
open_connection();  
	$idp 		= $_POST['programid'];
	$idk 		= $_POST['kegiatanid'];
	$tahun 		= $_POST['tahun'];
	$kodeSatker = $_POST['satker'];
	$condtn = "idp = '$idp' AND idk = '$idk' AND tahun = '$tahun' AND kodeSatker = '$kodeSatker'"; 
	$sql = mysql_query("SELECT idot,kd_output,output FROM output WHERE {$condtn}");	
	
	while ($row = mysql_fetch_assoc($sql)){
				$data[] = $row;
	}
	if(!$data){

		//$data[] = array("uraian"=>"No Results Found..");
		$data[] = array("idot"=>"","kd_output"=>"","output"=>"No Results Found..");
	
	}		
	echo json_encode($data);

exit;

?>