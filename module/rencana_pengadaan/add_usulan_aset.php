<?php
include "../../config/config.php";
//insert program 
//pr($_POST);
//exit;
$query	  = "INSERT INTO usulan_rencana_pengadaaan_aset (
					idus,kodeKelompok,
					jml_usul,satuan_usul,
					jml_max,satuan_max,
					jml_optml,satuan_optml,
					jml_intra,jml_ekstra,
					jml_rill,satuan_rill,ket) 
			VALUES ('$_POST[idus]','$_POST[kodeKelompok]',
					'$_POST[jml_usul]','$_POST[satuan_usul]',
					'$_POST[jml_max]','$_POST[satuan_max]',
					'$_POST[jml_optml]','$_POST[satuan_optml]',
					'$_POST[jml_intra]','$_POST[jml_ekstra]',
					'$_POST[jml_rill]','$_POST[satuan_rill]',
					'".addslashes(html_entity_decode($_POST[ket]))."')";

$exec =  mysql_query($query) or die(mysql_error());
  	echo "<script>
			alert('Data Berhasil Disimpan');
		</script>";
	
	echo "<script>
	window.location = '{$url_rewrite}/module/rencana_pengadaan/list_usulan_aset.php?idus={$_POST[idus]}&tgl_usul={$_POST['tgl_usul_param']}&satker={$_POST['satker']}'
	</script>";	
?>