<?php
include "../../config/config.php";
$idus = $_GET['idus'];
$idr = $_GET['idr'];
$tgl_usul = $_GET['tgl_usul'];
$satker = $_GET['satker'];

//delete program
$query ="delete from usulan_rencana_pengadaaan_aset where idr = '$idr'"; 
$exec =  mysql_query($query) or die(mysql_error());
  	echo "<script>
			alert('Data Berhasil Dihapus');
		</script>";
	
	/*echo "<script>
	window.location = '{$url_rewrite}/module/rencana_pengadaan/list_usulan_aset.php?idus={$idus}&tgl_usul={$tgl_usul}&satker={$satker}'
	</script>";*/

	echo "<script>
	window.location = '{$url_rewrite}/module/rencana_pengadaan/list_usulan_aset.php?idus={$idus}&satker={$satker}'
	</script>";		
?>