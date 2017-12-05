<?php
include "../../config/config.php";
//insert program 
//pr($_POST);
$query	  = "INSERT INTO usulan_rencana_pemeliharaan (idp,idk,idot,kodeSatker,
						 no_usul,tgl_usul,status_usulan) 
			VALUES ('$_POST[program]','$_POST[kegiatan]','$_POST[output]',
					'$_POST[KodeSatker]','".addslashes(html_entity_decode($_POST[no_usul]))."',
					'$_POST[tgl_usul]','1')";
//pr($query);
//exit;
$exec =  mysql_query($query);
  	echo "<script>
			alert('Data Berhasil Disimpan');
		</script>";
	
	echo "<script>
	window.location = '{$url_rewrite}/module/rencana_pemeliharaan/list_usulan.php?tgl_usul={$_POST['tgl_usul_param']}&satker={$_POST['KodeSatker']}'
	</script>";	
?>