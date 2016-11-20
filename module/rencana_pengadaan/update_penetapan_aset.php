<?php
include "../../config/config.php";
//update pejabat
//pr($_POST);
$query	  = "UPDATE usulan_rencana_pengadaaan_aset SET 
				satuan_usul 		= '$_POST[satuan_usul]',
				jml_max_rev 		= '$_POST[jml_max_rev]',
				satuan_max 			= '$_POST[satuan_max]',
				satuan_optml 		= '$_POST[satuan_optml]',
				jml_rill_rev 		= '$_POST[jml_rill_rev]',
				satuan_rill 		= '$_POST[satuan_rill]',
				jml_usul_rev 		= '$_POST[jml_usul_rev]',
				satuan_usul_rev 	= '$_POST[satuan_usul_rev]',
				cara 	= '".addslashes(html_entity_decode($_POST[cara]))."',
				status_usulan       = '1',
				status_penetapan	= '1'
			WHERE idr 	= '$_POST[idr]'";
//pr($query);
//exit;			
$exec =  mysql_query($query);
  	echo "<script>
			alert('Data Berhasil Dirubah');
		</script>";
	
	echo "<script>
	window.location = '{$url_rewrite}/module/rencana_pengadaan/list_penetapan_aset.php?idus={$_POST['idus']}&tgl_usul={$_POST['tgl_usul_param']}&satker={$_POST['satker']}'
	</script>";	
?>