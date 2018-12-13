<?php
include "../../config/config.php";
//update pejabat
//pr($_POST);
//pr($_GET);
//exit;
$query	  = "UPDATE usulan_rencana_pengadaaan_aset SET 
				status_validasi = '0'
			WHERE idus 	= '$_GET[idus]'";		
$exec =  mysql_query($query);

$up_status_penetapan = "UPDATE usulan_rencana_pengadaaan SET 
				status_validasi	= '0'
			WHERE idus 	= '$_GET[idus]'";
$exe =  mysql_query($up_status_penetapan);
	
  	echo "<script>
			alert('Data validasi berhasil dibatalkan');
		</script>";
	
	/*echo "<script>
	window.location = '{$url_rewrite}/module/rencana_pengadaan/list_validasi_aset.php?idus={$_GET['idus']}&tgl_usul={$_GET['tgl_usul']}&satker={$_GET['satker']}'
	</script>";*/	

	echo "<script>
	window.location = '{$url_rewrite}/module/rencana_pengadaan/list_validasi_aset.php?idus={$_GET['idus']}&satker={$_GET['satker']}'
	</script>";
?>