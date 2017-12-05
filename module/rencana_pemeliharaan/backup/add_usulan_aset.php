<?php
include "../../config/config.php";
//insert program 
// pr($_POST);
// exit;
$query	  = "INSERT INTO usulan_rencana_pemeliharaan_aset (
					idus,kodeKelompok,
					jml_usul,satuan_usul,
					jml_optml,satuan_optml,
					ket) 
			VALUES ('$_POST[idus]','$_POST[kodeKelompok]',
					'$_POST[jml_usul]','$_POST[satuan_usul]',
					'$_POST[jml_optml]','$_POST[satuan_optml]',
					'".addslashes(html_entity_decode($_POST[ket]))."')";

$exec =  mysql_query($query);
  	echo "<script>
			alert('Data Berhasil Disimpan');
		</script>";
	
	echo "<script>
	window.location = '{$url_rewrite}/module/rencana_pemeliharaan/list_usulan_aset.php?idus={$_POST[idus]}&tgl_usul={$_POST['tgl_usul_param']}&satker={$_POST['satker']}'
	</script>";	
?>