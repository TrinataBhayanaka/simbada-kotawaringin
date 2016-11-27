<?php
include "../../config/database.php";  
open_connection();  
	
	$kodeKelompok = $_POST['kodeKelompok'];
	$KodeSatker = $_POST['KodeSatker'];
	$expl = explode('.', $kodeKelompok);
	if($expl[0] == '01'){
		$tabel = "tanah";
	}elseif ($expl[0] == '02') {
		$tabel = "mesin";
	}elseif ($expl[0] == '03') {
		$tabel = "bangunan";
	}elseif ($expl[0] == '04') {
		$tabel = "jaringan";
	}elseif ($expl[0] == '05') {
		$tabel = "asetlain";
	}elseif ($expl[0] == '06') {
		$tabel = "kdp";
	}
	$sql = mysql_query("SELECT COUNT(Aset_ID) as total FROM $tabel WHERE 
		kodeKelompok = '{$kodeKelompok}' AND KodeSatker = '{$KodeSatker}' 
		AND StatusValidasi = '1' AND Status_Validasi_Barang	 = '1'  AND StatusTampil = '1'
		AND (kondisi = '1' or kondisi = '2')");
	while ($row = mysql_fetch_assoc($sql)){
		$list = $row['total'];
	}
	//exit;
	// echo json_encode($list);
	if($list){
		echo $list;
	}else {
		echo 0;
	}

exit;

?>