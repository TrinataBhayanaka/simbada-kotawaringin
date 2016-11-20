<?php
include "../../config/database.php";  
open_connection();  
	
	$kd_program = $_POST['kd_program'];
	$tahun = $_POST['tahun'];
	$KodeSatker = $_POST['KodeSatker'];

	$sql = mysql_query("SELECT COUNT(*) as total FROM program WHERE 
		kd_program ='{$kd_program}' AND KodeSatker = '{$KodeSatker}' 
		AND tahun = '{$tahun}'");
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