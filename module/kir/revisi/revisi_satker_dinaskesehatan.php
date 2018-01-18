<?php
/*
skenario
1. simbada_pkl_kir (kir fix)
- satker fix
2. simbada_pkl_penyusutan (penyusutan fix)
- satker unfix
*/
// include "../../../config/database.php";
//connect db
$hostname = '10.10.100.3';
$username = 'simbada';
$password = 'burg3rk1ng';
$conn = mysql_connect($hostname,$username,$password);

//source db
$db1 = "simbada_kotawaringin_20170129_revisi_penyusutan_20170407";
//destination db
$db2 = "simbada_kotawaringin_sotk_v2";	

$link_db1 = mysql_select_db($db1,$conn);
$link_db2 =  mysql_select_db($db2,$conn);

$link_connect=mysql_connect($hostname,$username,$password)  or die ("Koneksi Database gagal"); 

//Dinas Kesehatan
$skpd 			= "07.01";

//log (function logfile)
include "../../../config/config.php";

//hapus tabel satker
$query_select_ruangan_fix ="delete from $db2.satker where kode LIKE '$skpd%'"; 
$exec =  mysql_query($query_select_ruangan_fix);

//select Kd_Ruang dan NamaSatker dari tabel satker (simbada_kir)
$query = "SELECT Satker_ID,Tahun,KodeSektor,KodeSatker,kode,KodeUnit,Gudang,NamaSatker,Kd_Ruang 
		FROM $db1.satker WHERE kode like '$skpd%'";
//echo $query;
$exe_select = mysql_query($query);
while($row = mysql_fetch_assoc($exe_select)) {
	//db($row);
	$KodeSektor 	= $row[KodeSektor];
	$KodeSatker 	= $row[KodeSatker];
	$KodeUnit		= $row[KodeUnit];
	$Gudang			= $row[Gudang];
	$kode			= $row[kode];
	$Kd_Ruang 		= trim($row[Kd_Ruang]);
	$NamaSatker 	= trim($row[NamaSatker]);

	//update ruangan 
	$QuerySatker	  = "INSERT INTO $db2.satker(
							Tahun,KodeSektor,KodeSatker,KodeUnit,Gudang, kode,
							Kd_Ruang,NamaSatker) 
						VALUES ('$tahun','$KodeSektor','$KodeSatker','$KodeUnit','$Gudang','$kode','$Kd_Ruang','$NamaSatker')";
	//db($QuerySatker);
	$exe_kd_ruang = mysql_query($QuerySatker);	
	//exit;
}
echo "=====done=====";

exit;

?>