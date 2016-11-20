<?php
include "../../config/config.php";
//insert program 
$query	  = "INSERT INTO program (tahun,KodeSatker,kd_program,program) 
			VALUES ('$_POST[tahun]','$_POST[KodeSatker]',
					'$_POST[kd_program]','$_POST[program]')";

$exec =  mysql_query($query);
  	echo "<script>
			alert('Data Berhasil Disimpan');
		</script>";
	
	echo "<script>
	window.location = '{$url_rewrite}/module/program/list_program.php?tahun={$_POST['tahun']}&satker={$_POST['KodeSatker']}'
	</script>";	
?>