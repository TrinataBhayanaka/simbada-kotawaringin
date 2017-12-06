<?php
include "../../config/config.php";
//update pejabat
//pr($_POST);
//exit();
$query	  = "UPDATE usulan_rencana_pengadaaan_aset SET 
				kodeKelompok 		= '$_POST[kodeKelompok]',
				jml_usul 			= '$_POST[jml_usul]',
				satuan_usul 		= '$_POST[satuan_usul]',
				jml_max 			= '$_POST[jml_max]',
				satuan_max 			= '$_POST[satuan_max]',
				jml_optml 			= '$_POST[jml_optml]',
				satuan_optml 		= '$_POST[satuan_optml]',
				jml_intra			= '$_POST[jml_intra]',
				jml_ekstra			= '$_POST[jml_ekstra]',
				jml_rill 			= '$_POST[jml_rill]',
				satuan_rill 		= '$_POST[satuan_rill]',
				ket 				= '".addslashes(html_entity_decode($_POST[ket]))."'
			WHERE idr 	= '$_POST[idr]'";
//pr($query);
//exit;			
$exec =  mysql_query($query);
  	echo "<script>
			alert('Data Berhasil Dirubah');
		</script>";
	
	echo "<script>
	window.location = '{$url_rewrite}/module/rencana_pengadaan/list_usulan_aset.php?idus={$_POST['idus']}&tgl_usul={$_POST['tgl_usul_param']}&satker={$_POST['satker']}'
	</script>";	
?>