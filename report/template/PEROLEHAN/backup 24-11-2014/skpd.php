<?php

include "../../../config/config.php";
include "../../report_engine.php";

//print_r($_REQUEST);
$modul 	  = $_REQUEST['menuID'];
$mode 	  = $_REQUEST['mode'];
$kelompok = $_REQUEST['kelompok_id'];
$tab 	  = $_REQUEST['tab'];
$tahun	  = $_REQUEST['tahun_buku_inventaris_skpd'];
$skpd_id  = $_REQUEST['kodeSatker3'];
$bukuInv  = $_REQUEST['bukuInv'];
// pr($_REQUEST);
$penanda  ='1';
$tipe	  = $_REQUEST['tipe_file'];

$paramater_url="menuID=$modul&mode=$mode&tab=$tab&skpd_id3=$skpd_id&tahun_buku_inventaris_skpd=$tahun&kelompok_id=$kelompok&penanda=$penanda&bukuinv=$bukuInv&tipe_file=";

$REPORT=new report_engine();
  
$url="report_perolehanaset_cetak_bukuinventarisSKPD.php?$paramater_url";
    
$REPORT->show_pilih_download($url);    

?>

 