<?php
include "../../config/config.php";
//update pejabat
//pr($_POST);
/*$query	  = "UPDATE usulan_rencana_pengadaaan SET 
				no_usul 	= '$_POST[no_usul]',
				tgl_usul 	= '$_POST[tgl_usul]',
				kodeSatker 		= '$_POST[KodeSatker]',
				idp 			= '$_POST[program]',
				idk 			= '$_POST[kegiatan]',
				idot 			= '$_POST[output]',
				tahun			= '$_POST[tahun]'
			WHERE idus 	= '$_POST[idus]'";*/

$query	  = "UPDATE usulan_rencana_pengadaaan SET 
				no_usul 	= '$_POST[no_usul]',
				tgl_usul 	= '$_POST[tgl_usul]',
				kodeSatker 		= '$_POST[KodeSatker]',
				tahun			= '$_POST[tahun]'
			WHERE idus 	= '$_POST[idus]'";

//pr($query);
//exit;			
$exec =  mysql_query($query);
  	echo "<script>
			alert('Data Berhasil Dirubah');
		</script>";
	
	echo "<script>
	window.location = '{$url_rewrite}/module/rencana_pengadaan/list_usulan.php?tgl_usul={$_POST['tgl_usul_param']}&satker={$_POST['KodeSatker']}'
	</script>";	
?>