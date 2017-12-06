<?php
include "../../config/database.php";  
open_connection();  
	
	$programid = $_POST['programid'];
	$kegiatanid = $_POST['kegiatanid'];
	$outputid = $_POST['outputid'];
	$tahun = $_POST['tahun'];
	$satker = $_POST['satker'];

	/*$sql = mysql_query("SELECT COUNT(*) as total FROM usulan_rencana_pemeliharaan WHERE 
		idp ='{$programid}' AND idk = '{$kegiatanid}' AND idot = '{$outputid}' AND kodeSatker = '{$satker}' 
		AND YEAR(tgl_usul) = '{$tahun}'");*/
	/*$sql2 ="SELECT COUNT(*) as total FROM usulan_rencana_pengadaaan WHERE 
		idp ='{$programid}' AND idk = '{$kegiatanid}' AND idot = '{$outputid}' AND kodeSatker = '{$satker}' 
		AND YEAR(tgl_usul) = '{$tahun}'";
	echo $sql2;	*/
	$sql = mysql_query("SELECT COUNT(*) as total FROM usulan_rencana_pemeliharaan WHERE 
		idp ='{$programid}' AND idk = '{$kegiatanid}' AND idot = '{$outputid}' AND kodeSatker = '{$satker}' 
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