<?php
include "../../../config/config.php";
include "../../report_engine.php";

$modul = $_REQUEST['menuID'];
$mode = $_REQUEST['mode'];
$tab = $_REQUEST['tab'];
$tglawalperolehan = $_REQUEST['tglPerolehan_awal_induk'];
$tglakhirperolehan = $_REQUEST['tglPerolehan_akhir_induk'];
$tglcetak = $_REQUEST['tglCetakRekapBivIndk'];
// pr($_REQUEST);
$paramater_url="menuID=$modul&mode=$mode&tab=$tab&skpd_id=$skpd_id&tglawalperolehan=$tglawalperolehan&tglakhirperolehan=$tglakhirperolehan&tglcetak=$tglcetak&tipe_file=$tipe";
// echo $paramater_url;
// exit;
$REPORT=new report_engine();
$url = "report_perolehanaset_cetak_rekapitulasibukuindukinventarisdaerah.php?$paramater_url";
$REPORT->show_pilih_download($url);  




?>
 
