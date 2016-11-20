<?php
include "../../config/config.php";
//update pejabat
$query	  = "UPDATE program SET 
				tahun 	= '$_POST[tahun]',
				KodeSatker 		= '$_POST[KodeSatker]',
				kd_program 	= '$_POST[kd_program]',
				program 			= '$_POST[program]'
			WHERE idp 	= '$_POST[idp]'";

$exec =  mysql_query($query);
  	echo "<script>
			alert('Data Berhasil Dirubah');
		</script>";
	
	echo "<script>
	window.location = '{$url_rewrite}/module/program/list_program.php?tahun={$_POST['tahun']}&satker={$_POST['KodeSatker']}'
	</script>";	
?>