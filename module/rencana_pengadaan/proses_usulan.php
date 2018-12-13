<?php
include "../../config/config.php";

if($_GET['flag'] == 1){
	//proses pengajuan usulan
	$query = "UPDATE usulan_rencana_pengadaaan SET 
					status_usulan 	= '1'
				WHERE idus 	= '$_GET[idus]'";
	$exec =  mysql_query($query);
	
	$query2 = "UPDATE usulan_rencana_pengadaaan_aset SET 
					status_usulan 	= '1'
				WHERE idus 	= '$_GET[idus]'";
	$exec2 =  mysql_query($query2);

	echo "<script>
				alert('Pengajuan Usulan Diproses');
		</script>";
		
	/*echo "<script>
	window.location = '{$url_rewrite}/module/rencana_pengadaan/list_usulan.php?tgl_usul={$_GET['tgl_usul']}&satker={$_GET['satker']}'
	</script>";*/	
	echo "<script>
	window.location = '{$url_rewrite}/module/rencana_pengadaan/index.php'
	</script>";

}elseif($_GET['flag'] == 2){
	//batal proses pengajuan usulan
	$query = "UPDATE usulan_rencana_pengadaaan SET 
					status_usulan 	= '0'
				WHERE idus 	= '$_GET[idus]'";
	$exec =  mysql_query($query);
	  	
	$query2 = "UPDATE usulan_rencana_pengadaaan_aset SET 
					status_usulan 	= '0'
				WHERE idus 	= '$_GET[idus]'";
	$exec2 =  mysql_query($query2);
	  	
	echo "<script>
				alert('Pengajuan Usulan Dibatalkan');
		</script>";
		
	/*echo "<script>
	window.location = '{$url_rewrite}/module/rencana_pengadaan/list_usulan.php?tgl_usul={$_GET['tgl_usul']}&satker={$_GET['satker']}'
	</script>";*/	
	echo "<script>
	window.location = '{$url_rewrite}/module/rencana_pengadaan/index.php'
	</script>";
}elseif($_GET['flag'] == 3){
	//batal proses verifikasi
	$query = "UPDATE usulan_rencana_pengadaaan SET 
					status_verifikasi 	= '0'
				WHERE idus 	= '$_GET[idus]'";
	$exec =  mysql_query($query);

	$query2 = "UPDATE usulan_rencana_pengadaaan_aset SET 
					status_verifikasi 	= '0'
				WHERE idus 	= '$_GET[idus]'";
	$exec2 =  mysql_query($query2);
	  	
	echo "<script>
				alert('Verifikasi Usulan Dibatalkan');
		</script>";
		
	/*echo "<script>
	window.location = '{$url_rewrite}/module/rencana_pengadaan/list_penetapan.php?tgl_usul={$_GET['tgl_usul']}&satker={$_GET['satker']}'
	</script>";*/	
	echo "<script>
	window.location = '{$url_rewrite}/module/rencana_pengadaan/list_penetapan.php'
	</script>";	
}elseif($_GET['flag'] == 4){
	//batal proses penetapan
	$query = "UPDATE usulan_rencana_pengadaaan SET 
					status_penetapan 	= '0'
				WHERE idus 	= '$_GET[idus]'";
	$exec =  mysql_query($query);

	$query2 = "UPDATE usulan_rencana_pengadaaan_aset SET 
					status_penetapan 	= '0'
				WHERE idus 	= '$_GET[idus]'";
	$exec2 =  mysql_query($query2);
	  	
	echo "<script>
				alert('Penetapan Usulan Dibatalkan');
		</script>";
		
	echo "<script>
	window.location = '{$url_rewrite}/module/rencana_pengadaan/list_penetapan.php'
	</script>";	
}elseif($_GET['flag'] == 5){
	//proses penetapan
	$query = "UPDATE usulan_rencana_pengadaaan SET 
					status_penetapan 	= '1'
				WHERE idus 	= '$_GET[idus]'";
	$exec =  mysql_query($query);

	$query2 = "UPDATE usulan_rencana_pengadaaan_aset SET 
					status_penetapan 	= '1'
				WHERE idus 	= '$_GET[idus]'";
	$exec2 =  mysql_query($query2);
	  	
	echo "<script>
				alert('Penetapan Usulan');
		</script>";
		
	/*echo "<script>
	window.location = '{$url_rewrite}/module/rencana_pengadaan/list_penetapan.php?tgl_usul={$_GET['tgl_usul']}&satker={$_GET['satker']}'
	</script>";	*/
	echo "<script>
	window.location = '{$url_rewrite}/module/rencana_pengadaan/list_penetapan.php'
	</script>";
}

?>