<?php
include "../../config/config.php";
$idk = $_GET['idk'];
$idp = $_GET['idp'];
$tahun = $_GET['tahun'];
$satker = $_GET['satker'];

//delete program
$query ="delete from kegiatan where idk = '$idk'"; 
$exec =  mysql_query($query);
  	echo "<script>
			alert('Data Berhasil Dihapus');
		</script>";
	
	echo "<script>
	window.location = '{$url_rewrite}/module/kegiatan/list_kegiatan.php?thn={$tahun}&satker={$satker}'
	</script>";		
?>