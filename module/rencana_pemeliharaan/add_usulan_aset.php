<?php
include "../../config/config.php";
//insert program 
//pr($_POST);
//exit;
$query	  = "INSERT INTO usulan_rencana_pemeliharaan_aset (
					idus,kodeKelompok,
					jml_usul,satuan_usul,
					jml_optml,satuan_optml,
					jml_baik,jml_rusak_ringan,
					status_barang,ket,pemeliharaan) 
			VALUES ('$_POST[idus]','$_POST[kodeKelompok]',
					'$_POST[jml_usul]','$_POST[satuan_usul]',
					'$_POST[jml_optml]','$_POST[satuan_optml]',
					'$_POST[kondisi_baik]','$_POST[kondisi_rusak_ringan]',
					'".addslashes(html_entity_decode($_POST[status_barang]))."',
					'".addslashes(html_entity_decode($_POST[ket]))."',
					'".addslashes(html_entity_decode($_POST[pemeliharaan]))."'
					)";
//pr($query);
//exit;
$exec =  mysql_query($query);
  	echo "<script>
			alert('Data Berhasil Disimpan');
		</script>";
	
	echo "<script>
	window.location = '{$url_rewrite}/module/rencana_pemeliharaan/list_usulan_aset.php?idus={$_POST[idus]}&tgl_usul={$_POST['tgl_usul_param']}&satker={$_POST['satker']}'
	</script>";	
?>