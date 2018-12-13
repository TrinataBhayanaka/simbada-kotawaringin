<?php
include "../../config/config.php";
$idus = $_GET['idus'];
$tgl_usul = $_GET['tgl_usul'];
$satker = $_GET['satker'];

//ceck fisrst
$count = mysql_query("select count(idr) as jml from usulan_rencana_pengadaaan_aset where idus = '{$idus}'");
$hit = mysql_fetch_assoc($count); 
if($hit['jml'] > 0 ){
	echo "<script>
			alert('Data Usulan sedang Digunakan');
		</script>";
	
	/*echo "<script>
	window.location = '{$url_rewrite}/module/rencana_pengadaan/list_usulan.php?tgl_usul={$tgl_usul}&satker={$satker}'
	</script>";*/
	echo "<script>
	window.location = '{$url_rewrite}/module/rencana_pengadaan/index.php'
	</script>";	
}else{
//delete program
$query ="delete from usulan_rencana_pengadaaan where idus = '$idus'"; 
$exec =  mysql_query($query);
  	echo "<script>
			alert('Data Berhasil Dihapus');
		</script>";
	
	/*echo "<script>
	window.location = '{$url_rewrite}/module/rencana_pengadaan/list_usulan.php?tgl_usul={$tgl_usul}&satker={$satker}'
	</script>";*/	
	echo "<script>
	window.location = '{$url_rewrite}/module/rencana_pengadaan/index.php'
	</script>";
}
		
?>