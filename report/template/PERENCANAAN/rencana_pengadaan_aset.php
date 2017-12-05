<?php
include "../../../config/config.php";
include "../../report_engine.php";

$tanggalAwal = $_REQUEST['tgl_usul_awal'];
$tanggalAkhir = $_REQUEST['tgl_usul_akhir'];
$skpd_id = $_REQUEST['kodeSatker'];

//pr($_REQUEST);
$paramater_url="skpd_id=$skpd_id&tanggalAwal=$tanggalAwal&tanggalAkhir=$tanggalAkhir&tipe_file=$tipe";
$REPORT=new report_engine();
$url = "report_perencanaan_cetak_pengadaan_barang_aset.php?$paramater_url";
$REPORT->show_pilih_download($url);  

?>
 
