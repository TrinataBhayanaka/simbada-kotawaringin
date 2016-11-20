<?php
include "../../config/config.php";
//insert kegiatan
$query	  = "INSERT INTO output (idk,idp,tahun,kodeSatker,kd_output,output) 
			VALUES ('$_POST[kegiatan]','$_POST[program]','$_POST[tahun]','$_POST[kodeSatker]',
					'$_POST[kd_output]','$_POST[output]')";
//pr($query);
//exit();
$exec =  mysql_query($query);
  	echo "<script>
			alert('Data Berhasil Disimpan');
		</script>";
	
	echo "<script>
	window.location = '{$url_rewrite}/module/output/list_output.php?thn={$_POST['tahun']}&satker={$_POST['kodeSatker']}'
	</script>";	
?>