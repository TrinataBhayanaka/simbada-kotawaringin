<?php
include "../../config/database.php";  
open_connection();  
	$idp 		= $_POST['programid'];
	$tahun 		= $_POST['tahun'];
	$kodeSatker = $_POST['satker'];
	$condtn = "idp = '$idp' AND tahun = '$tahun' AND kodeSatker = '$kodeSatker'"; 
	$sql = mysql_query("SELECT idk,kd_kegiatan,kegiatan FROM kegiatan WHERE {$condtn}");	
	
	while ($row = mysql_fetch_assoc($sql)){
				$data[] = $row;
	}
	if(!$data){

		//$data[] = array("uraian"=>"No Results Found..");
		$data[] = array("idk"=>"","kd_kegiatan"=>"","kegiatan"=>"No Results Found..");
	
	}		
	echo json_encode($data);

exit;

?>