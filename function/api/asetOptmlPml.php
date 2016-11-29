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
	//total = baik + rusak ringan
	$sql = mysql_query("SELECT COUNT(Aset_ID) as total FROM $tabel WHERE 
		kodeKelompok = '{$kodeKelompok}' AND KodeSatker = '{$KodeSatker}' 
		AND StatusValidasi = '1' AND Status_Validasi_Barang	 = '1'  AND StatusTampil = '1'
		AND (kondisi = '1' or kondisi = '2')");
	while ($row = mysql_fetch_assoc($sql)){
		$list = $row['total'];
	}
	//baik
	$sql2 = mysql_query("SELECT COUNT(Aset_ID) as total FROM $tabel WHERE 
		kodeKelompok = '{$kodeKelompok}' AND KodeSatker = '{$KodeSatker}' 
		AND StatusValidasi = '1' AND Status_Validasi_Barang	 = '1'  AND StatusTampil = '1'
		AND kondisi = '1'");
	while ($row2 = mysql_fetch_assoc($sql2)){
		$list2 = $row2['total'];
	}
	//rusak ringan
	$sql3 = mysql_query("SELECT COUNT(Aset_ID) as total FROM $tabel WHERE 
		kodeKelompok = '{$kodeKelompok}' AND KodeSatker = '{$KodeSatker}' 
		AND StatusValidasi = '1' AND Status_Validasi_Barang	 = '1'  AND StatusTampil = '1'
		AND kondisi = '2'");
	while ($row3 = mysql_fetch_assoc($sql3)){
		$list3 = $row3['total'];
	}
	//exit;
	// echo json_encode($list);
	if($list){
		//echo $list;
		$data[] = array("total"=>$list,"Baik"=>$list2,"RR"=>$list3);
	}else {
		//echo 0;
		$data[] = array("total"=>0,"Baik"=>0,"RR"=>0);
	}
	print json_encode($data);
exit;

?>