<?php

class RETRIEVE_REPORT extends DB {

     public function daftar_barang_berdasarkan_sk_mutasi($no_sk) {
          $query = "select  A.kodeLokasi,A.kodeSatker,A.kodeKelompok,A.NilaiBuku,A.Tahun,A.AkumulasiPenyusutan,A.Kondisi,A.NilaiPerolehan,A.Info,A.noRegister,A.TglPerolehan,A.TglPembukuan,
          P.Mutasi_ID,A.Aset_ID
          from mutasiaset PA 
          left join mutasi P on P.Mutasi_ID=PA.Mutasi_ID 
          left join Aset A on PA.Aset_ID=A.Aset_ID
          where P.Mutasi_ID='$no_sk' ";
            // echo $query;
      // exit;
          $result = $this->query($query) or die($this->error());
          $check = $this->num_rows($result);
          while ($data = $this->fetch_array($result)) {
               switch ($data[Kondisi]) {
                    case 1:
                         $data[Kondisi] = "Baik";
                         break;
                    case 2:
                         $data[Kondisi] = "Rusak Ringan";
                         break;
                    case 3:
                         $data[Kondisi] = "Rusak Berat";
                         break;
               }
               $data[Satker] = $this->get_skpd($data[kodeSatker]);
               $data[Kelompok] = $this->get_kelompok($data[kodeKelompok]);
               $dataArr[] = $data;
          }
          return $dataArr;
     }
      
     public function daftar_barang_berdasarkan_usulan_mutasi($no_usulan) {
          $query = "select  A.kodeLokasi,A.kodeSatker,A.kodeKelompok,A.NilaiBuku,A.Tahun,A.AkumulasiPenyusutan,A.Kondisi,A.NilaiPerolehan,A.Info,A.noRegister,A.TglPerolehan,A.TglPembukuan,
              US.Penetapan_ID,US.Aset_ID,US.NilaiPerolehanTmp
          from usulanaset US 
          left join usulan U on U.Usulan_ID=US.Usulan_ID 
                    left join Aset A on US.Aset_ID=A.Aset_ID
                    where U.Usulan_ID='$no_usulan' ";
            // echo $query;
      // exit;
          $result = $this->query($query) or die($this->error());
          $check = $this->num_rows($result);
          while ($data = $this->fetch_array($result)) {
               switch ($data[Kondisi]) {
                    case 1:
                         $data[Kondisi] = "Baik";
                         break;
                    case 2:
                         $data[Kondisi] = "Rusak Ringan";
                         break;
                    case 3:
                         $data[Kondisi] = "Rusak Berat";
                         break;
               }
               // $data[Satker] = $this->get_skpd($data[kodeSatker]);
               $data[Kelompok] = $this->get_kelompok($data[kodeKelompok]);
               $dataArr[] = $data;
          }
          return $dataArr;
     }

     public function get_skpd($kodeSatker) {
          $query = "select NamaSatker from Satker where kode='$kodeSatker' limit 1";
          $result = $this->query($query) or die($this->error());
          while ($data = $this->fetch_array($result)) {
               $NamaSatker = $data[NamaSatker];
          }
          return $NamaSatker;
     }

     public function get_kelompok($kodeKelompok) {
          $query = "select Uraian from Kelompok where Kode='$kodeKelompok' limit 1";
          $result = $this->query($query) or die($this->error());
          while ($data = $this->fetch_array($result)) {
               $Uraian = $data[Uraian];
          }
          return $Uraian;
     }

     public function list_daftar_sk_penghapusan() {
          $query = "select * from penghapusan group by NoSKHapus";
          $result = $this->query($query) or die($this->error());
          $check = $this->num_rows($result);
          while ($data = $this->fetch_array($result)) {
               $dataArr[] = $data;
          }
          return array('dataArr' => $dataArr, 'count' => $check);
     }

     public function  get_kontrak($noKontrak){
          $query="select K.noKontrak,K.tglKontrak,K.keterangan, K.n_status, S.nosp2d, S.tglsp2d from kontrak K "
                  . " left join sp2d S on K.id=S.idKontrak where K.noKontrak='$noKontrak'";
          echo  $query;
          echo "<br/>";
          $result = $this->query($query) or die($this->error());
          $check = $this->num_rows($result);
          while ($data = $this->fetch_array($result)) {
               $tglKontrak = $this->format_tanggal($data[tglKontrak]);
               $status_kontrak=$data[n_status];
               $keterangan=$data[keterangan];
               $nosp2d.=$data[nosp2d]."<br/>";
               $tglsp2d.=  $this->format_tanggal($data[tglsp2d])."<br/>";
          }
          return array($tglKontrak,$keterangan,$nosp2d,$tglsp2d,$status_kontrak);//array('dataArr' => $dataArr, 'count' => $check);
          
     }

     public function daftar_barang_berdasarkan_sk_penghapusan($no_sk) {
          $query = "select  A.kodeLokasi,A.kodeSatker,A.kodeKelompok,A.NilaiBuku,A.Tahun,A.AkumulasiPenyusutan,A.Kondisi,A.NilaiPerolehan,A.Info,A.noRegister  from penghapusanaset PA left join penghapusan P on 
				P.Penghapusan_ID=PA.Penghapusan_ID 
                                                       left join Aset A on PA.Aset_ID=A.Aset_ID
                                                       where P.Penghapusan_ID='$no_sk' ";
          //   echo $query;
          $result = $this->query($query) or die($this->error());
          $check = $this->num_rows($result);
          while ($data = $this->fetch_array($result)) {
               switch ($data[Kondisi]) {
                    case 1:
                         $data[Kondisi] = "Baik";
                         break;
                    case 2:
                         $data[Kondisi] = "Rusak Ringan";
                         break;
                    case 3:
                         $data[Kondisi] = "Rusak Berat";
                         break;
               }
               $data[Satker] = $this->get_skpd($data[kodeSatker]);
               $data[Kelompok] = $this->get_kelompok($data[kodeKelompok]);
               $dataArr[] = $data;
          }
          return $dataArr;
     }
      public function daftar_barang_berdasarkan_usulan_penghapusan($no_usulan) {
          $query = "select  A.kodeLokasi,A.kodeSatker,A.kodeKelompok,A.NilaiBuku,A.Tahun,A.AkumulasiPenyusutan,A.Kondisi,A.NilaiPerolehan,A.Info,A.noRegister  from usulanaset US left join usulan U on 
        U.Usulan_ID=US.Usulan_ID 
                                                       left join Aset A on US.Aset_ID=A.Aset_ID
                                                       where U.Usulan_ID='$no_usulan' ";
          //   echo $query;
          $result = $this->query($query) or die($this->error());
          $check = $this->num_rows($result);
          while ($data = $this->fetch_array($result)) {
               switch ($data[Kondisi]) {
                    case 1:
                         $data[Kondisi] = "Baik";
                         break;
                    case 2:
                         $data[Kondisi] = "Rusak Ringan";
                         break;
                    case 3:
                         $data[Kondisi] = "Rusak Berat";
                         break;
               }
               // $data[Satker] = $this->get_skpd($data[kodeSatker]);
               $data[Kelompok] = $this->get_kelompok($data[kodeKelompok]);
               $dataArr[] = $data;
          }
          return $dataArr;
     }
     
     public function daftar_pengadaan_berdasarkan_skpd($skpd,$tglPerolehanAwal,$tglPerolehanAkhir){
          /*$query="select K.Uraian, A.info,A.kodeKelompok,A.kodeSatker, A.noKontrak,A.StatusValidasi,"
                  . "  Sum(NilaiPerolehan) as Total,Sum(Kuantitas) as Jumlah,NilaiPerolehan as Satuan"
                  . "    from aset A  inner join kelompok K on K.Kode=A.kodeKelompok "
                  . "where A.kodeSatker like '$skpd%' and A.TglPerolehan>='$tglPerolehanAwal'"
                              . " and TglPerolehan<='$tglPerolehanAkhir'  and A.noKontrak is not null and A.StatusValidasi=1 "
                              . " group by A.kodeSatker,A.kodeKelompok,A.noKontrak";*/
          
          //custom kontrak aset baru                         
          $query = "select K.Uraian, A.info,A.kodeKelompok,A.kodeSatker, A.noKontrak,A.StatusValidasi, Sum(NilaiPerolehan) as Total,
                    Sum(Kuantitas) as Jumlah,NilaiPerolehan as Satuan from aset A 
                      inner join kelompok K on K.Kode = A.kodeKelompok 
                      inner join kontrak Ka on Ka.noKontrak = A.noKontrak
                    where A.kodeSatker like '$skpd%' and A.TglPerolehan>='$tglPerolehanAwal' and TglPerolehan<='$tglPerolehanAkhir' 
                          and A.noKontrak is not null and A.StatusValidasi=1 and Ka.n_status = 1 and Ka.tipeAset = 1
                      group by A.kodeSatker,A.kodeKelompok,A.noKontrak";    
                                          
         echo $query;
         echo "<br>";
          $result = $this->query($query) or die($this->error());
          //$check = $this->num_rows($result);
          while ($data = $this->fetch_array($result)) {
              
               list($tglKontrak,$keterangan,$nosp2d,$tglsp2d,$status_kontrak)=  $this->get_kontrak($data[noKontrak]);
               $data['tglkontrak'] = $tglKontrak;
               $data['Satker']=  $this->get_skpd($data['kodeSatker']);
               $data['keterangan'] = $keterangan;
               $data['nosp2d'] = $nosp2d;
               $data['tglsp2d'] = $tglsp2d;
               if($status_kontrak==1)
                $dataArr[] = $data;
          }
         // pr($dataArr);
          return $dataArr;//array('dataArr' => $dataArr, 'count' => $check);
    }

    public function daftar_pengadaan_kapitalisasi_berdasarkan_skpd($skpd,$tglPerolehanAwal,$tglPerolehanAkhir){
          /*$query="select K.Uraian, A.info,A.kodeKelompok,A.kodeSatker, A.noKontrak,A.StatusValidasi,"
                  . "  Sum(NilaiPerolehan) as Total,Sum(Kuantitas) as Jumlah,NilaiPerolehan as Satuan"
                  . "    from aset A  inner join kelompok K on K.Kode=A.kodeKelompok "
                  . "where A.kodeSatker like '$skpd%' and A.TglPerolehan>='$tglPerolehanAwal'"
                              . " and TglPerolehan<='$tglPerolehanAkhir'  and A.noKontrak is not null and A.StatusValidasi is null"
                              . " group by A.kodeSatker,A.kodeKelompok,A.noKontrak";*/
          //custom kontrak kapitalisasi                    
          $query="select K.Uraian, A.info,A.kodeKelompok,A.kodeSatker, A.noKontrak,A.StatusValidasi, Sum(NilaiPerolehan) as Total,
                  Sum(Kuantitas) as Jumlah,NilaiPerolehan as Satuan from aset A 
                    inner join kelompok K on K.Kode=A.kodeKelompok 
                    inner join kontrak Ka on Ka.noKontrak = A.noKontrak 
                  where A.kodeSatker like '$skpd%' and ka.tglKontrak>='$tglPerolehanAwal' and ka.tglKontrak<='$tglPerolehanAkhir' 
                  and A.noKontrak is not null and A.StatusValidasi is null 
                  and Ka.n_status = 1 and Ka.tipeAset = 2 
                    group by A.kodeSatker,A.kodeKelompok,A.noKontrak";                       
          //echo $query;
          //echo "<br>";
          $result = $this->query($query) or die($this->error());
          $check = $this->num_rows($result);
          while ($data = $this->fetch_array($result)) {
              
               list($tglKontrak,$keterangan,$nosp2d,$tglsp2d,$status_kontrak)=  $this->get_kontrak($data[noKontrak]);
               $data['tglkontrak'] = $tglKontrak;
               $data['Satker']=  $this->get_skpd($data['kodeSatker']);
               $data['keterangan'] = $keterangan;
               $data['nosp2d'] = $nosp2d;
               $data['tglsp2d'] = $tglsp2d;
               if($status_kontrak==1)
                $dataArr[] = $data;
          }
         // pr($dataArr);
          return $dataArr;//array('dataArr' => $dataArr, 'count' => $check);
    }

     public function format_tanggal($tgl) {
    if($tgl!="0000-00-00" && $tgl!="")
    {
          $temp=explode(" ", $tgl);
          $temp=explode("-", $temp[0]);
          $tahun=$temp[0];
          $bln=$temp[1];
          $hari=$temp[2];


        switch($bln)
        {
            case "01" : $namaBln = "Januari";
                      break;
            case "02" : $namaBln = "Februari";
                      break;
            case "03" : $namaBln = "Maret";
                       break;
            case "04" : $namaBln = "April";
                       break;
            case "05" : $namaBln = "Mei";
                     break;
            case "06" : $namaBln = "Juni";
                     break;
            case "07" : $namaBln = "Juli";
                     break;
            case "08" : $namaBln = "Agustus";
                     break;
            case "09" : $namaBln = "September";
                     break;
            case "10" : $namaBln = "Oktober";
                     break;
            case "11" : $namaBln = "November";
                         break;
            case "12" : $namaBln = "Desember";
                         break;
        }
        $tgl_full="$hari $namaBln $tahun";
        return $tgl_full;
    }
    else return "";
}
  public function test($skpd,$tglPerolehanAwal,$tglPerolehanAkhir){
    /*pr($skpd);
    pr($tglPerolehanAwal);
    pr($tglPerolehanAkhir);*/
    $tahun = explode('-', $tglPerolehanAkhir);
    //get list satker kontrak
    $sqlkontrakSatker = mysql_query("SELECT kodeSatker FROM kontrak WHERE kodeSatker like '$skpd%' and tglKontrak >='$tglPerolehanAwal' and tglKontrak <='$tglPerolehanAkhir' AND (tipeAset = 1 or tipeAset = 2 or tipeAset = 3)  group by kodeSatker");
    //pr($sql);
    //tipeAset = 1 or tipeAset = 2 or tipeAset = 3
    while ($dataKontrakSatker = mysql_fetch_assoc($sqlkontrakSatker)){
        $satker[] = $dataKontrakSatker;
    }    
    sort($satker);
    //exit();
    foreach ($satker as $ky => $valSatker) {
      //pr($valSatker['kodeSatker']);
      
      //get list kontrak
      $sqlkontrak= mysql_query("SELECT * FROM kontrak WHERE kodeSatker = '$valSatker[kodeSatker]' and tglKontrak >='$tglPerolehanAwal' and tglKontrak <='$tglPerolehanAkhir' AND (tipeAset = 1 or tipeAset = 2 or tipeAset = 3)");
      
      while ($dataKontrak = mysql_fetch_assoc($sqlkontrak)){
        
        $kontrak = $dataKontrak;
        //pr($kontrak);
        //get sp2d penunjang
        $sqlsp2dp = mysql_query("SELECT SUM(nilai) as total FROM sp2d WHERE idKontrak='{$kontrak['id']}' AND type = '2'");
        while ($dataSP2D = mysql_fetch_assoc($sqlsp2dp)){
            $sumsp2d = $dataSP2D;
          }

        //get total nilai all aset
        $sqlsum = mysql_query("SELECT SUM(NilaiPerolehan) as total FROM aset WHERE noKontrak = '{$kontrak['noKontrak']}' AND ((StatusValidasi != 9 and StatusValidasi != 13) OR StatusValidasi IS NULL) AND ((Status_Validasi_Barang != 9 and Status_Validasi_Barang != 13) OR Status_Validasi_Barang IS NULL)");
         while ($sum = mysql_fetch_assoc($sqlsum)){
            $sumTotal = $sum;
          }

        //get list barang by nokontrak
        $RKsql = mysql_query("SELECT noKOntrak,Aset_ID, noRegister, Satuan, kodeLokasi, kodeKelompok, NilaiPerolehan FROM aset WHERE noKontrak = '{$kontrak['noKontrak']}' AND ((StatusValidasi != 9 and StatusValidasi != 13) OR StatusValidasi IS NULL) AND ((Status_Validasi_Barang != 9 and Status_Validasi_Barang != 13) OR Status_Validasi_Barang IS NULL)");
        $rKontrak = array();
        $list = array();
        while ($dataRKontrak = mysql_fetch_assoc($RKsql)){
              $rKontrak[] = $dataRKontrak;
        }
        foreach ($rKontrak as $key => $value) {
          $sqlnmBrg = mysql_query("SELECT Uraian FROM kelompok WHERE Kode = '{$value['kodeKelompok']}' LIMIT 1");
          while ($uraian = mysql_fetch_assoc($sqlnmBrg)){
              $tmp = $uraian;
              $rKontrak[$key]['uraian'] = $tmp['Uraian'];
            }
        }
        $i = 1;
        $j = 0;
        $tmp = 0;
        foreach ($rKontrak as $key => $value) {
           # code...
            $list[]= $value; 
            $bopsisa = $sumsp2d['total'];
            $j++;

            //SELECT k.*,kp.* FROM `kontrak` as k INNER JOIN kapitalisasi as kp on kp.idKontrak = k.id WHERE k.tipeAset = 2 AND i(tglKontrak) = '2018' AND kodeSatker = '12.02.01.01'

            if($kontrak['tipeAset'] == 1){
              $KptlQr = mysql_query("SELECT k.*,kp.* FROM kontrak as k INNER JOIN kapitalisasi as kp on kp.idKontrak = k.id WHERE k.tipeAset = 2 AND year(tglKontrak) = '$tahun[0]' AND kodeSatker = '$valSatker[kodeSatker]' AND Aset_ID = '$value[Aset_ID]' ");
                $kptls = mysql_fetch_assoc($KptlQr);
                if($kptls){
                  $NP = $value['NilaiPerolehan'] - $kptls['nilai'];
                }else{
                  $NP = $value['NilaiPerolehan']; 
                }
            }else{
              $NP = $value['NilaiPerolehan']; 
            }
            
            

            if(count($rKontrak) != $j){
                //$bop = ceil($value['NilaiPerolehan']/$sumTotal['total']*$sumsp2d['total']);
                $bop = ceil($NP/$sumTotal['total']*$sumsp2d['total']);
                $tmp+= $bop;
            }else{
                $bop = $bopsisa - $tmp;
            }
            /*echo "bop  = ".$bop;echo "<br/>";
            echo "bop sisa = ".$bopsisa;echo "<br/>";*/
            
            //$satuan = $value['NilaiPerolehan']-$bop;
            $satuan = $NP-$bop;
            $list[$key]['bop'] = $bop;
            $list[$key]['satuan'] = $satuan;
            $list[$key]['NilaiKontrak'] = $kontrak['nilai'];
            $list[$key]['TglKontrak'] = $kontrak['tglKontrak'];
            $list[$key]['tipeAset'] = $kontrak['tipeAset'];
            if($kontrak['tipeAset'] == 1){
              $list[$key]['tipeKontrak'] = 'Aset Baru';
            }elseif($kontrak['tipeAset'] == 2){
              $list[$key]['tipeKontrak'] = 'Kapitalisasi';
            }elseif($kontrak['tipeAset'] == 3){
              $list[$key]['tipeKontrak'] = 'Ubah Status';
            }else{
              $list[$key]['tipeKontrak'] = '-';
            }
             
            //get list sp2dpenunjang
            $sp2dpRincian=array();
            $totalsp2dpenunjang = 0;
            $sqlsp2dpRincian = mysql_query("SELECT * from sp2d where idKontrak = '{$kontrak['id']}' AND type = '2'");
            while ($rincian = mysql_fetch_assoc($sqlsp2dpRincian)){
              $sp2dpRincian[] = $rincian;
              if($kontrak['tipeAset'] == 3){
                $totalsp2dpenunjang+=$rincian['nilai'];
              }
            }
            $list[$key]['sp2dpenunjang'] = $sp2dpRincian;
              
            if($kontrak['tipeAset'] == 3){
              $list[$key]['totalsp2dpenunjang'] = $totalsp2dpenunjang;
              $totalFix = $totalsp2dpenunjang + $kontrak['nilai'];
              $list[$key]['totalPerolehan'] = $totalFix;
            }
            
            
            //get list sp2dpenunjang
            $sp2dRincian=array();
            $sqlsp2dRincian = mysql_query("SELECT * from sp2d where idKontrak = '{$kontrak['id']}' AND type = '1'");
            while ($rincian2 = mysql_fetch_assoc($sqlsp2dRincian)){
              $sp2dRincian[] = $rincian2;
            }
            $list[$key]['sp2d'] = $sp2dRincian;
        
        $i++;
                 
        }  
        //pr($list);
        //exit();
        $dataFix[$valSatker['kodeSatker']][$kontrak['noKontrak']]['data'] = $list;         
      }
    }
    //pr($dataFix); 
    return $dataFix;     
    //pr($dataFix); 
    //exit();
  }

   public function tdkberwujud($skpd,$tglPerolehanAwal,$tglPerolehanAkhir){
    /*pr($skpd);
    pr($tglPerolehanAwal);
    pr($tglPerolehanAkhir);*/

    //get list satker kontrak
    $sqlkontrakSatker = mysql_query("SELECT kodeSatker FROM aset WHERE kodeSatker like '$skpd%' and TglPembukuan >='$tglPerolehanAwal' and TglPembukuan <='$tglPerolehanAkhir' AND kodeKelompok like '07%'  group by kodeSatker");
    //pr($sql);
    while ($dataKontrakSatker = mysql_fetch_assoc($sqlkontrakSatker)){
        $satker[] = $dataKontrakSatker;
    }    
    sort($satker);
    foreach ($satker as $ky => $valSatker) {
        $RKsql = mysql_query("SELECT kodeKelompok,kodeSatker, noRegister, TglPerolehan, TglPembukuan, kondisi, Info,NilaiPerolehan FROM aset WHERE Status_Validasi_Barang = 1 AND kodeSatker = '{$valSatker[kodeSatker]}' AND kodeKelompok like '07%'");
        $rAset = array();
        while ($dataAset = mysql_fetch_assoc($RKsql)){
              $rAset[] = $dataAset;
        }
        foreach ($rAset as $key => $value) {
          $sqlnmBrg = mysql_query("SELECT Uraian FROM kelompok WHERE Kode = '{$value['kodeKelompok']}' LIMIT 1");
          while ($uraian = mysql_fetch_assoc($sqlnmBrg)){
              $tmp = $uraian;
              $rAset[$key]['uraian'] = $tmp['Uraian'];
            }
        }
      $dataFix[$valSatker['kodeSatker']]['data'] = $rAset; 
    }
    if($dataFix){
      return $dataFix;
    }else{
      return false;
    }
  }

  public function TTD($id,$Updtpath){
    $query = "UPDATE usulan SET StatusGenerate = '1', path_file = '$Updtpath' WHERE Usulan_ID = '$id'";
    mysql_query($query) or die(mysql_error());
  }

  public function usulan($id){
    $query = "select NoUsulan from usulan where Usulan_ID = '$id'";
    //echo $query;
    $exe = mysql_query($query) or die(mysql_error());
    $data = mysql_fetch_assoc($exe);
    return $data;
  }

}

?>