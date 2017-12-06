<?php
include "../../config/config.php";
//update pejabat
//pr($_POST);
//exit;
if($_POST[jml_rill_rev] == 0){
	$flag = '2';
}else{
	$flag = '1';
}
$query	  = "UPDATE usulan_rencana_pengadaaan_aset SET 
				satuan_usul 		= '$_POST[satuan_usul]',
				jml_max_rev 		= '$_POST[jml_max_rev]',
				satuan_max 			= '$_POST[satuan_max]',
				satuan_optml 		= '$_POST[satuan_optml]',
				jml_optml			= '$_POST[jml_optml]',
				jml_ekstra_rev		= '$_POST[jml_ekstra_rev]',
				jml_rill_rev 		= '$_POST[jml_rill_rev]',
				satuan_rill 		= '$_POST[satuan_rill]',
				jml_usul_rev 		= '$_POST[jml_usul_rev]',
				satuan_usul_rev 	= '$_POST[satuan_usul_rev]',
				ket 	= '".addslashes(html_entity_decode($_POST[ket]))."',
				cara 	= '".addslashes(html_entity_decode($_POST[cara]))."',
				status_verifikasi	= '$flag'
			WHERE idr 	= '$_POST[idr]'";
//pr($query);
//exit();					
$exec =  mysql_query($query);

$up_status_penetapan = "UPDATE usulan_rencana_pengadaaan SET 
				status_verifikasi	= '1'
			WHERE idus 	= '$_POST[idus]'";
$exe =  mysql_query($up_status_penetapan);

  	echo "<script>
			alert('Data Telah Diverifikasi');
		</script>";
	
	echo "<script>
	window.location = '{$url_rewrite}/module/rencana_pengadaan/list_penetapan_aset.php?idus={$_POST['idus']}&tgl_usul={$_POST['tgl_usul_param']}&satker={$_POST['satker']}'
	</script>";	
?>