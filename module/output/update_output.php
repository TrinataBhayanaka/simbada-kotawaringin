<?php
include "../../config/config.php";
//update kegiatan
//pr($_POST);
//exit;
$query	  = "UPDATE output SET
				idp 		= '$_POST[program]', 
				idk 		= '$_POST[kegiatan]', 
				tahun 		= '$_POST[tahun]', 
				kodeSatker 	= '$_POST[kodeSatker]', 
				kd_output = '$_POST[kd_output]',
				output 	= '$_POST[output]'
			WHERE idot 	= '$_POST[idot]'";

$exec =  mysql_query($query);
  	echo "<script>
			alert('Data Berhasil Dirubah');
		</script>";
	
	echo "<script>
	window.location = '{$url_rewrite}/module/output/list_output.php?&thn={$_POST['tahun']}&satker={$_POST['kodeSatker']}'
	</script>";	
?>