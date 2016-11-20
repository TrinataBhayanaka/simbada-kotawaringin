<?php
include "../../config/config.php";
//insert kegiatan
$query	  = "INSERT INTO kegiatan (idp,tahun,kodeSatker,kd_kegiatan,kegiatan) 
			VALUES ('$_POST[program]','$_POST[tahun]','$_POST[kodeSatker]',
					'$_POST[kd_kegiatan]','$_POST[kegiatan]')";

$exec =  mysql_query($query);
  	echo "<script>
			alert('Data Berhasil Disimpan');
		</script>";
	
	echo "<script>
	window.location = '{$url_rewrite}/module/kegiatan/list_kegiatan.php?thn={$_POST['tahun']}&satker={$_POST['kodeSatker']}'
	</script>";	
?>