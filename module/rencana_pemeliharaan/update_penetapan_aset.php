<?php
include "../../config/config.php";
//update pejabat
//pr($_POST);
//exit;
if($_POST[jml_usul_rev] != 0){
	$flag = '1';
}else{
	$flag = '2';
}
$query	  = "UPDATE usulan_rencana_pemeliharaan_aset SET 
				satuan_usul 		= '$_POST[satuan_usul]',
				satuan_optml 		= '$_POST[satuan_optml]',
				jml_usul_rev 		= '$_POST[jml_usul_rev]',
				satuan_usul_rev 	= '$_POST[satuan_usul_rev]',
				ket 	= '".addslashes(html_entity_decode($_POST[ket]))."',
				pemeliharaan 	= '".addslashes(html_entity_decode($_POST[pemeliharaan]))."',
				status_barang 	= '".addslashes(html_entity_decode($_POST[status_barang]))."',

				status_verifikasi	= '$flag'
			WHERE idr 	= '$_POST[idr]'";		
$exec =  mysql_query($query);

$up_status_penetapan = "UPDATE usulan_rencana_pemeliharaan SET 
				status_verifikasi	= '1'
			WHERE idus 	= '$_POST[idus]'";
$exe =  mysql_query($up_status_penetapan);

  	echo "<script>
			alert('Data Berhasil Dirubah');
		</script>";
	
	echo "<script>
	window.location = '{$url_rewrite}/module/rencana_pemeliharaan/list_penetapan_aset.php?idus={$_POST['idus']}&tgl_usul={$_POST['tgl_usul_param']}&satker={$_POST['satker']}'
	</script>";	
?>