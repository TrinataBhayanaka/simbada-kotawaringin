<?php
include "../../config/config.php";
$idot = $_GET['idot'];
$tahun = $_GET['tahun'];
$satker = $_GET['satker'];

//delete program
$query ="delete from output where idot = '$idot'"; 
$exec =  mysql_query($query);
  	echo "<script>
			alert('Data Berhasil Dihapus');
		</script>";
	
	echo "<script>
	window.location = '{$url_rewrite}/module/output/list_output.php?thn={$tahun}&satker={$satker}'
	</script>";		
?>