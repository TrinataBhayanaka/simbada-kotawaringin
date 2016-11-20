<?php
include "../../config/config.php";
$idp = $_GET['idp'];
$tahun = $_GET['tahun'];
$satker = $_GET['satker'];

//delete program
$query ="delete from program where idp = '$idp'"; 
$exec =  mysql_query($query);
  	echo "<script>
			alert('Data Berhasil Dihapus');
		</script>";
	
	echo "<script>
	window.location = '{$url_rewrite}/module/program/list_program.php?tahun={$tahun}&satker={$satker}'
	</script>";		
?>