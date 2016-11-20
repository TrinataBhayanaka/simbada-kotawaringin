<?php
include "../../config/database.php";  
open_connection();  
	
	$program = $_POST['program'];
	$kegiatan = $_POST['kegiatan'];
	$kd_output = $_POST['kd_output'];
	$tahun = $_POST['tahun'];
	$kodeSatker = $_POST['kodeSatker'];

	$sql = mysql_query("SELECT COUNT(*) as total FROM output WHERE 
		idp = '{$program}' AND idk = '{$kegiatan}' AND kd_output ='{$kd_output}' 
		AND kodeSatker = '{$kodeSatker}' AND tahun = '{$tahun}'");
	while ($row = mysql_fetch_assoc($sql)){
		$list = $row['total'];
	}
	//exit;
	// echo json_encode($list);
	if($list){
		echo 1;
	}else {
		echo 0;
	}

exit;

?>