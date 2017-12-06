<?php
include "../../config/config.php";
//update pejabat
//pr($_POST);
$query	  = "UPDATE usulan_rencana_pemeliharaan_aset SET 
				kodeKelompok 		= '$_POST[kodeKelompok]',
				jml_usul 			= '$_POST[jml_usul]',
				satuan_usul 		= '$_POST[satuan_usul]',
				jml_optml 			= '$_POST[jml_optml]',
				satuan_optml 		= '$_POST[satuan_optml]',
				ket 				= '".addslashes(html_entity_decode($_POST[ket]))."',
				status_barang		= '".addslashes(html_entity_decode($_POST[status_barang]))."',
				pemeliharaan		= '".addslashes(html_entity_decode($_POST[pemeliharaan]))."'
			WHERE idr 	= '$_POST[idr]'";
$exec =  mysql_query($query);
  	echo "<script>
			alert('Data Berhasil Dirubah');
		</script>";
	
	echo "<script>
	window.location = '{$url_rewrite}/module/rencana_pemeliharaan/list_usulan_aset.php?idus={$_POST['idus']}&tgl_usul={$_POST['tgl_usul_param']}&satker={$_POST['satker']}'
	</script>";	
?>