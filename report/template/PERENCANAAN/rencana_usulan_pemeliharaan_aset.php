<?php
include "../../../config/config.php";
include "../../report_engine.php";


$idus = $_REQUEST['idus'];
$skpd_id = $_REQUEST['satker'];

$paramater_url="skpd_id=$skpd_id&idus=$idus&tipe_file=$tipe";
$REPORT=new report_engine();
$url = "report_perencanaan_cetak_usulan_pemeliharaan_barang_aset.php?$paramater_url";
$REPORT->show_pilih_download($url);  

?>
 
