<?php
include "../../config/config.php";
//update kegiatan
//pr($_POST);
//exit;
$query	  = "UPDATE kegiatan SET 
				kd_kegiatan = '$_POST[kd_kegiatan]',
				kegiatan 	= '$_POST[kegiatan]'
			WHERE idk 	= '$_POST[idk]'";

$exec =  mysql_query($query);
  	echo "<script>
			alert('Data Berhasil Dirubah');
		</script>";
	
	echo "<script>
	window.location = '{$url_rewrite}/module/kegiatan/list_kegiatan.php?idp={$_POST[idp]}&thn={$_POST['tahun']}&satker={$_POST['kodeSatker']}'
	</script>";	
?>