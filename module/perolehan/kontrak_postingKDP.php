<?php
include "../../config/config.php";
$menu_id = 10;
            $SessionUser = $SESSION->get_session_user();
            ($SessionUser['ses_uid']!='') ? $Session = $SessionUser : $Session = $SESSION->get_session(array('title'=>'GuestMenu', 'ses_name'=>'menu_without_login')); 
            $USERAUTH->FrontEnd_check_akses_menu($menu_id, $Session);

$kontrakID = $_GET['id'];
$sql = mysql_query("SELECT * FROM kontrak WHERE id = '{$kontrakID}'");
while ($dataKontrak = mysql_fetch_assoc($sql)){
            $noKontrak = $dataKontrak;
        }

$updateKontrak = mysql_query("UPDATE kontrak SET n_status = '1' WHERE id = '{$noKontrak['id']}'");


$sql = mysql_query("SELECT SUM(nilai) as total FROM sp2d WHERE idKontrak='{$kontrakID}' AND type = '2'");
while ($dataSP2D = mysql_fetch_assoc($sql)){
    $sumsp2d = $dataSP2D;
  }

  $sql = mysql_query("SELECT * FROM aset WHERE noKontrak = '{$noKontrak[noKontrak]}' AND ((StatusValidasi != 9 and StatusValidasi != 13) OR StatusValidasi IS NULL) AND ((Status_Validasi_Barang != 9 and Status_Validasi_Barang != 13) OR Status_Validasi_Barang IS NULL)");
  while ($dataAset = mysql_fetch_assoc($sql)){
              $aset[] = $dataAset;
          }
  $counter = 0;    
  $bopsisa = $sumsp2d['total'];

  foreach($aset as $key => $data){
    $counter++;
    if(count($aset) == $counter){
      $bop = $bopsisa;
    } else{
      $bopsisa = $bopsisa - ($data['NilaiPerolehan']/$noKontrak['nilai']*$sumsp2d['total']);  
      $bop = ($data['NilaiPerolehan']/$noKontrak['nilai']*$sumsp2d['total']);
    }

    $NilaiPerolehan = ($data['NilaiPerolehan'] + $bop);
    $satuan = (($data['Satuan']) + ($bop/$data['Kuantitas']));
    // pr($satuan);exit;
    $updateAset = mysql_query("UPDATE aset SET NilaiPerolehan = '{$NilaiPerolehan}', Satuan = '{$satuan}' WHERE Aset_ID = '{$data['Aset_ID']}'");
    $updateKapital = mysql_query("UPDATE kapitalisasi SET n_status = '1', nilai = if(nilai is null,0,nilai)+{$bop}  WHERE idKontrak = '{$noKontrak['id']}' AND asetKapitalisasi = '{$data['Aset_ID']}'");
  
    $jenisAset[$key] = $data['TipeAset'];
  }  
// pr($jenisAset);exit;

$sql = mysql_query("SELECT * FROM kapitalisasi WHERE idKontrak = '{$noKontrak['id']}'");
while ($dataKapital = mysql_fetch_assoc($sql)){
            $kapital[] = $dataKapital;
        }

foreach ($kapital as $key => $value) {
  $sqlkib = "SELECT * FROM {$value['tipeAset']} WHERE Aset_ID = '{$value['Aset_ID']}'";
  $sqlquery = mysql_query($sqlkib);
  while ($dataAset = mysql_fetch_assoc($sqlquery)){
          $kib = $dataAset;
      }

  if($jenisAset[$key]=="A"){
	    $tabel = "tanah";
	    $logtabel = "log_tanah";
	    $idkey = "Tanah_ID";
	} elseif ($jenisAset[$key]=="B") {
	    $tabel = "mesin";
	    $logtabel = "log_mesin";
	    $idkey = "Mesin_ID";
	} elseif ($jenisAset[$key]=="C") {
	    $tabel = "bangunan";
	    $logtabel = "log_bangunan";
	    $idkey = "Bangunan_ID";
	} elseif ($jenisAset[$key]=="D") {
	    $tabel = "jaringan";
	    $logtabel = "log_jaringan";
	    $idkey = "Jaringan_ID";
	} elseif ($jenisAset[$key]=="E") {
	    $tabel = "asetlain";
	    $logtabel = "log_asetlain";
	    $idkey = "AsetLain_ID";
	} elseif ($jenisAset[$key]=="F") {
	    $tabel = "kdp";
	    $logtabel = "log_kdp";
	    $idkey = "KDP_ID";
	} elseif ($jenisAset[$key]=="G") {
	    $tabel = "aset";
	    $logtabel = "log_aset";
	    $idkey = "Aset_ID";
	} elseif ($jenisAset[$key]=="H") {
	    $tabel = "aset";
	    $logtabel = "log_aset";
	    $idkey = "Aset_ID";
	}

  $sql = mysql_query("UPDATE {$tabel} SET NilaiPerolehan = NilaiPerolehan + {$kib['NilaiPerolehan']}, StatusTampil = '1' WHERE Aset_ID = '{$value['asetKapitalisasi']}'");
  $sql = mysql_query("UPDATE aset SET NilaiPerolehan = NilaiPerolehan + {$kib['NilaiPerolehan']}, Satuan = Satuan + {$kib['NilaiPerolehan']} WHERE Aset_ID = '{$value['asetKapitalisasi']}'");
  $sql = mysql_query("UPDATE kdp SET StatusTampil = '0', StatusValidasi = '0', Status_Validasi_Barang = '0' WHERE Aset_ID = '{$value['Aset_ID']}'");
  $sql = mysql_query("UPDATE aset SET StatusValidasi = '0', Status_Validasi_Barang = '0' WHERE Aset_ID = '{$value['Aset_ID']}'");

  //log
  $sqlkib = "SELECT * FROM {$value['tipeAset']} WHERE Aset_ID = '{$value['Aset_ID']}'";
  $sqlquery = mysql_query($sqlkib);
  while ($dataAset = mysql_fetch_assoc($sqlquery)){
          $kib = $dataAset;
      }    
  $kib['changeDate'] = date("Y-m-d");
  $kib['TglPerubahan'] = $noKontrak['tglKontrak'];//$kib['TglPerolehan'];
  $kib['action'] = "Penghapusan KDP";
  $kib['operator'] = $_SESSION['ses_uoperatorid'];
  $kib['NilaiPerolehan_Awal'] = $kib['NilaiPerolehan'];
  $kib['NilaiPerolehan'] = $kib['NilaiPerolehan'] + $value['nilai'];
  $kib['Kd_Riwayat'] = 35;    
  
  
        unset($tmpField);
        unset($tmpValue);
        foreach ($kib as $key => $val) {
          $tmpField[] = $key;
          $tmpValue[] = "'".$val."'";
        }
         
        $fileldImp = implode(',', $tmpField);
        $dataImp = implode(',', $tmpValue);

        $sql = mysql_query("INSERT INTO log_kdp ({$fileldImp}) VALUES ({$dataImp})");


  $sqlkib = "SELECT * FROM {$tabel} WHERE Aset_ID = '{$value['asetKapitalisasi']}'";
  $sqlquery = mysql_query($sqlkib);
  while ($dataAset = mysql_fetch_assoc($sqlquery)){
          $kib = $dataAset;
      }    
  $kib['changeDate'] = date("Y-m-d");
  $kib['TglPerubahan'] =$noKontrak['tglKontrak'];// $kib['TglPerolehan'];
  $kib['action'] = "Ubah Status";
  $kib['operator'] = $_SESSION['ses_uoperatorid'];
  $kib['NilaiPerolehan_Awal'] = $kib['NilaiPerolehan'] - $value['nilai'];
  $kib['NilaiPerolehan'] = $kib['NilaiPerolehan'];
  $kib['Kd_Riwayat'] = 36;    
  
  
        unset($tmpField);
        unset($tmpValue);
        foreach ($kib as $key => $val) {
          $tmpField[] = $key;
          $tmpValue[] = "'".$val."'";
        }
         
        $fileldImp = implode(',', $tmpField);
        $dataImp = implode(',', $tmpValue);

        $sql = mysql_query("INSERT INTO {$logtabel} ({$fileldImp}) VALUES ({$dataImp})");

}
  echo "<meta http-equiv=\"Refresh\" content=\"0; url={$url_rewrite}/module/perolehan/kontrak_posting.php\">";
  exit;

?>
