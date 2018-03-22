<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
#This code provided by:
#Andreas Hadiyono (andre.hadiyono@gmail.com)
#Gunadarma University
include "$path/report/report_engine.php";
// pr($path);
class report_engine_daftar extends report_engine {

     public function retrieve_daftar_penetapan_mutasi($dataArr, $gambar, $sk, $tanggalCetak,$TitleSk) {
          if ($dataArr != "") {
               
               $no = 1;
               $skpdeh = "";
               $thn = "";
               $status_print = 0;
               $perolehanTotal = 0;
//pr($data);

               $head ="
                    <html>
                    <head>
                         <style>
                      table
                              {
                            font-size:10pt;
                            font-family:Arial;
                            border-collapse: collapse;                                                   
                            border-spacing:0;
                           }
                     h3   
                              {
                                   font-family:Arial;  
                                   font-size:13pt;
                                   color:#000;
                              }
                              p
                              {
                                   font-size:10pt;
                                   font-family:Arial;
                                   font-weight:bold;
                              }
                         </style>
                    </head>";

               $body ="
               <body>
                    <table style=\"text-align: left; width: 100%;\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\">
                              <tr>
                               <td style=\"width: 10%;\"><img style=\"width: 80px;\" alt=\"\" src=\"$gambar\"></td>
                               <td colspan=\"2\" style=\";width: 70%; text-align: center;\">
                                    <h3>LAMPIRAN {$TitleSk}</h3>
                                    <h3>PEMERINTAH $this->NAMA_KABUPATEN </h3>
                               </td>
                              </tr>
                              <tr>
                              <td>&nbsp;</td>
                              <td style=\"width: 50%;text-align:right\">&nbsp;</td>
                             <td>&nbsp;</td>
                              </tr>
                              <tr>
                              <td>&nbsp;</td>
                              <td style=\"width: 20%;text-align:right\">&nbsp;</td>
                                   <td align=\"right\">
                                      <table style=\"font-weight:bold;\">
                                        <tr>
                                             <td align=\"left\">Nomor</td>
                                             <td> : </td>
                                             <td align=\"left\">$sk</td>
                                        </tr>
                                        <tr>
                                             <td align=\"left\">Tanggal</td>
                                             <td> : </td>
                                             <td align=\"left\">$tanggalCetak</td>
                                        </tr>
                                      </table>
                              </td>
                             </tr>
                         </table>";
               $body.="<table style=\"text-align: left; width: 100%; border-collapse: collapse;\" border=\"1\" cellpadding=\"0\" cellspacing=\"0\">
                                                             
                    <thead>
               <tr style=\"text-align:center;font-weight:bold;\">
                         <td style=\"width:5%;text-align:center;font-weight:bold;\">No<br/> Urut </td>
                         <td style=\"width:10%;text-align:center;font-weight:bold;\">Kode Barang</td>
                         <td style=\"width:20%;text-align:center;font-weight:bold;\">Nama Barang</td>
                         <td style=\"width:10%;text-align:center;font-weight:bold;\">Tanggal Perolehan</td>
                         <td style=\"width:10%;text-align:center;font-weight:bold;\">Tanggal Pembukuan</td>
                         <td style=\"width:5%;text-align:center;font-weight:bold;\">No Register</td>
                         <td style=\"width:20%;text-align:center;font-weight:bold;\">Nama Unit</td>
                         <td style=\"width:5%;text-align:center;font-weight:bold;\">Tahun</td>
                         <td style=\"width:10%;text-align:center;font-weight:bold;\">Kondisi</td>
                         <td style=\"width:10%;text-align:center;font-weight:bold;\">Akumulasi Penyusutan</td>
                         <td style=\"width:10%;text-align:center;font-weight:bold;\">Nilai Buku</td>
                         <td style=\"width:10%;text-align:center;font-weight:bold;\">Nilai Perolehan</td>
                         <td style=\"width:20%;text-align:center;font-weight:bold;\">Keterangan</td>
               </tr>
             </thead>";

               foreach ($dataArr as $key => $value) {
                    $perolehan = number_format($value[NilaiPerolehan], 2, ",", ".");
                    $AkumulasiPenyusutan=number_format($value[AkumulasiPenyusutan], 2, ",", ".");
                    $NilaiBuku=number_format($value[NilaiBuku], 2, ",", ".");
                    // pr($value['noRegister']);
                    $Satker=$this->getNamaSatker($value['kodeSatker']);
                    $NamaSatker=$Satker[0]->NamaSatker;
                    $kodeNoReg=sprintf("%04s",$value['noRegister']);
                    // pr();

                    $body.="<tbody>
               <tr>
                         <td style=\"width:5%;text-align:center\">$no</td>
                         <td style=\"width:10%;text-align:center\">{$value[kodeKelompok]}</td>
                         <td style=\"width:20%\">{$value[Kelompok]}</td>
                         <td style=\"width:10%;text-align:center\">{$value[TglPerolehan]}</td>
                         <td style=\"width:10%;text-align:center\">{$value[TglPembukuan]}</td>
                         <td style=\"width:5%;text-align:center\">{$kodeNoReg}</td>
                         <td style=\"width:20%;text-align:center\">{$NamaSatker}</td>
                         <td style=\"width:5%;text-align:center\">{$value[Tahun]}</td>
                         <td style=\"width:10%;text-align:center\">{$value[Kondisi]}</td>
                         <td style=\"width:10%;text-align:right\">{$AkumulasiPenyusutan}</td>
                         <td style=\"width:10%;text-align:right\">{$NilaiBuku}</td>
                         <td style=\"width:10%;text-align:right\">{$perolehan}</td>
                         <td style=\"width:20%\">{$value[Info]}</td>
               </tr>
             </tbody>";
                    $perolehanTotal+=$value[NilaiPerolehan];
                    $akumalasiTotal+=$value[AkumulasiPenyusutan];
                    $nilaiBukuTotal+=$value[NilaiBuku];
                    $no++;
               }
               $perolehanTotal = number_format($perolehanTotal, 2, ",", ".");
               $akumalasiTotal = number_format($akumalasiTotal, 2, ",", ".");
               $nilaiBukuTotal = number_format($nilaiBukuTotal, 2, ",", ".");

               $body.="<tbody>
               <tr>
                         <td colspan=\"9\" style=\"text-align:center;font-weight:bold;\">Jumlah</td>
                         <td style=\"width:20%;text-align:right\">{$akumalasiTotal}</td>
                         <td style=\"width:20%;text-align:right\">{$nilaiBukuTotal}</td>
                         <td style=\"width:20%;text-align:right\">{$perolehanTotal}</td>
                         <td style=\"width:15%\"></td>
               </tr>
             </tbody></table>";

               $html[] = $head . $body;
          }

          return $html;
     }
     
     public function retrieve_daftar_usulan_mutasi($dataArr, $gambar, $sk, $tanggalCetak,$TitleSk) {
          if ($dataArr != "") {
               
               $no = 1;
               $skpdeh = "";
               $thn = "";
               $status_print = 0;
               $perolehanTotal = 0;
//pr($data);

               $head ="
                    <html>
                    <head>
                         <style>
                      table
                              {
                            font-size:10pt;
                            font-family:Arial;
                            border-collapse: collapse;                                                   
                            border-spacing:0;
                           }
                     h3   
                              {
                                   font-family:Arial;  
                                   font-size:13pt;
                                   color:#000;
                              }
                              p
                              {
                                   font-size:10pt;
                                   font-family:Arial;
                                   font-weight:bold;
                              }
                         </style>
                    </head>";

               $body ="
               <body>
                    <table style=\"text-align: left; width: 100%;\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\">
                              <tr>
                               <td style=\"width: 10%;\"><img style=\"width: 80px;\" alt=\"\" src=\"$gambar\"></td>
                               <td colspan=\"2\" style=\";width: 70%; text-align: center;\">
                                    <h3>LAMPIRAN {$TitleSk}</h3>
                                    <h3>PEMERINTAH $this->NAMA_KABUPATEN </h3>
                               </td>
                              </tr>
                              <tr>
                              <td>&nbsp;</td>
                              <td style=\"width: 50%;text-align:right\">&nbsp;</td>
                             <td>&nbsp;</td>
                              </tr>
                              <tr>
                              <td>&nbsp;</td>
                              <td style=\"width: 20%;text-align:right\">&nbsp;</td>
                                   <td align=\"right\">
                                      <table style=\"font-weight:bold;\">
                                        <tr>
                                             <td align=\"left\">Nomor</td>
                                             <td> : </td>
                                             <td align=\"left\">$sk</td>
                                        </tr>
                                        <tr>
                                             <td align=\"left\">Tanggal</td>
                                             <td> : </td>
                                             <td align=\"left\">$tanggalCetak</td>
                                        </tr>
                                      </table>
                              </td>
                             </tr>
                         </table>";
               $body.="<table style=\"text-align: left; width: 100%; border-collapse: collapse;\" border=\"1\" cellpadding=\"0\" cellspacing=\"0\">
                                                             
                    <thead>
               <tr style=\"text-align:center;font-weight:bold;\">
                         <td style=\"width:5%;text-align:center;font-weight:bold;\">No<br/> Urut </td>
                         <td style=\"width:10%;text-align:center;font-weight:bold;\">Kode Barang</td>
                         <td style=\"width:20%;text-align:center;font-weight:bold;\">Nama Barang</td>
                         <td style=\"width:10%;text-align:center;font-weight:bold;\">Tanggal Perolehan</td>
                         <td style=\"width:10%;text-align:center;font-weight:bold;\">Tanggal Pembukuan</td>
                         <td style=\"width:5%;text-align:center;font-weight:bold;\">No Register</td>
                         <td style=\"width:20%;text-align:center;font-weight:bold;\">Nama Unit</td>
                         <td style=\"width:5%;text-align:center;font-weight:bold;\">Tahun</td>
                         <td style=\"width:10%;text-align:center;font-weight:bold;\">Kondisi</td>
                         <td style=\"width:10%;text-align:center;font-weight:bold;\">Akumulasi Penyusutan</td>
                         <td style=\"width:10%;text-align:center;font-weight:bold;\">Nilai Buku</td>
                         <td style=\"width:10%;text-align:center;font-weight:bold;\">Nilai Perolehan</td>
                         <td style=\"width:20%;text-align:center;font-weight:bold;\">Keterangan</td>
               </tr>
             </thead>";

               foreach ($dataArr as $key => $value) {
                    $perolehan = number_format($value[NilaiPerolehan], 2, ",", ".");
                    $AkumulasiPenyusutan=number_format($value[AkumulasiPenyusutan], 2, ",", ".");
                    $NilaiBuku=number_format($value[NilaiBuku], 2, ",", ".");
                    // pr($value['noRegister']);
                    $Satker=$this->getNamaSatker($value['kodeSatker']);
                    $NamaSatker=$Satker[0]->NamaSatker;
                    $kodeNoReg=sprintf("%04s",$value['noRegister']);
                    // pr();

                    $body.="<tbody>
               <tr>
                         <td style=\"width:5%;text-align:center\">$no</td>
                         <td style=\"width:10%;text-align:center\">{$value[kodeKelompok]}</td>
                         <td style=\"width:20%\">{$value[Kelompok]}</td>
                         <td style=\"width:10%;text-align:center\">{$value[TglPerolehan]}</td>
                         <td style=\"width:10%;text-align:center\">{$value[TglPembukuan]}</td>
                         <td style=\"width:5%;text-align:center\">{$kodeNoReg}</td>
                         <td style=\"width:20%;text-align:center\">{$NamaSatker}</td>
                         <td style=\"width:5%;text-align:center\">{$value[Tahun]}</td>
                         <td style=\"width:10%;text-align:center\">{$value[Kondisi]}</td>
                         <td style=\"width:10%;text-align:right\">{$AkumulasiPenyusutan}</td>
                         <td style=\"width:10%;text-align:right\">{$NilaiBuku}</td>
                         <td style=\"width:10%;text-align:right\">{$perolehan}</td>
                         <td style=\"width:20%\">{$value[Info]}</td>
               </tr>
             </tbody>";
                    $perolehanTotal+=$value[NilaiPerolehan];
                    $akumalasiTotal+=$value[AkumulasiPenyusutan];
                    $nilaiBukuTotal+=$value[NilaiBuku];
                    $no++;
               }
               $perolehanTotal = number_format($perolehanTotal, 2, ",", ".");
               $akumalasiTotal = number_format($akumalasiTotal, 2, ",", ".");
               $nilaiBukuTotal = number_format($nilaiBukuTotal, 2, ",", ".");

               $body.="<tbody>
               <tr>
                         <td colspan=\"9\" style=\"text-align:center;font-weight:bold;\">Jumlah</td>
                         <td style=\"width:20%;text-align:right\">{$akumalasiTotal}</td>
                         <td style=\"width:20%;text-align:right\">{$nilaiBukuTotal}</td>
                         <td style=\"width:20%;text-align:right\">{$perolehanTotal}</td>
                         <td style=\"width:15%\"></td>
               </tr>
             </tbody></table>";

               $html[] = $head . $body;
          }

          return $html;
     }

     public function retrieve_daftar_sk($dataArr, $gambar, $sk, $tanggalCetak,$TitleSk) {
          if ($dataArr != "") {
               
               $no = 1;
               $skpdeh = "";
               $thn = "";
               $status_print = 0;
               $perolehanTotal = 0;
//pr($data);

               $head = "
     <html>
     <head>
               <style>
                              table
		{
                                        font-size:10pt;
                                        font-family:Arial;
                                        border-collapse: collapse;											
                                        border-spacing:0;
            }
                         h3   
		{
		font-family:Arial;	
		font-size:13pt;
		color:#000;
		}
		p
		{
		font-size:10pt;
		font-family:Arial;
		font-weight:bold;
		}
		</style>
		</head>
               ";

          $body = "
          <body>
               <table style=\"text-align: left; width: 100%;\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\">

                    <tr>
                         <td style=\"width: 10%;\"><img style=\"width: 80px;\" alt=\"\" src=\"$gambar\"></td>
                         <td colspan=\"2\" style=\";width: 70%; text-align: center;\">
                              <h3>LAMPIRAN {$TitleSk}</h3>
                              <h3>PEMERINTAH KABUPATEN KOTAWARINGIN TIMUR</h3>
                         </td>
                    </tr>
                    <tr>
                         <td>&nbsp;</td>
                         <td style=\"width: 50%;text-align:right\">&nbsp;</td>
                         <td>&nbsp;</td>
                    </tr>
                    <tr>
                         <td>&nbsp;</td>
                         <td style=\"width: 20%;text-align:right\">&nbsp;</td>
                              <td align=\"right\">
                                   <table style=\"font-weight:bold;\">
                                        <tr>
                                             <td align=\"left\">Nomor</td>
                                             <td> : </td>
                                             <td align=\"left\">$sk</td>
                                        </tr>
                                        <tr>
                                             <td align=\"left\">Tanggal</td>
                                             <td> : </td>
                                             <td align=\"left\">$tanggalCetak</td>
                                        </tr>
                                   </table>
                         </td>
                    </tr>
               </table><br/>";

         $body.="<table style=\"text-align: left; width: 100%; border-collapse: collapse;\" border=\"1\" cellpadding=\"0\" cellspacing=\"0\">
               <thead>
                    <tr style=\"text-align:center;font-weight:bold;\">
                         <td style=\"width:5%;text-align:center;font-weight:bold;\">No<br/> Urut </td>
                         <td style=\"width:20%;text-align:center;font-weight:bold;\">Nama Barang</td>
                         <td style=\"width:15%;text-align:center;font-weight:bold;\">Kode Lokasi</td>
                         <td style=\"width:15%;text-align:center;font-weight:bold;\">Kode Barang</td>
                         <td style=\"width:15%;text-align:center;font-weight:bold;\">Nama Unit</td>
                         <td style=\"width:5%;text-align:center;font-weight:bold;\">Tahun</td>
                         <td style=\"width:10%;text-align:center;font-weight:bold;\">Kondisi</td>
                         <td style=\"width:15%;text-align:center;font-weight:bold;\">Akumulasi <br/>Penyusutan</td>
                         <td style=\"width:15%;text-align:center;font-weight:bold;\">Nilai <br/>Buku</td>
                         <td style=\"width:15%;text-align:center;font-weight:bold;\">Nilai<br/>Perolehan</td>
                         <td style=\"width:15%;text-align:center;font-weight:bold;\">Keterangan</td>
                     </tr>
             </thead>";

               foreach ($dataArr as $key => $value) {
                    $perolehan = number_format($value[NilaiPerolehan], 2, ",", ".");
                    // pr($value['noRegister']);
                    $Satker=$this->getNamaSatker($value['kodeSatker']);
                    $NamaSatker=$Satker[0]->NamaSatker;
                    $kodeNoReg=sprintf("%04s",$value['noRegister']);
                    // pr();
                    $body.="<tbody>
               <tr>
                         <td style=\"width:5%\">$no</td>
                         <td style=\"width:20%\">{$value[Kelompok]}</td>
                         <td style=\"width:15%;text-align:center\">{$value[kodeLokasi]}</td>
                         <td style=\"width:15%;text-align:center\">{$value[kodeKelompok]}.{$kodeNoReg}</td>
                         <td style=\"width:20%;text-align:center\">{$NamaSatker}</td>
                         <td style=\"width:20%;text-align:center\">{$value[Tahun]}</td>
                         <td style=\"width:10%;text-align:center\">{$value[Kondisi]}</td>
                         <td style=\"width:15%;text-align:right\">{$value[AkumulasiPenyusutan]}</td>
                         <td style=\"width:15%;text-align:right\">{$value[NilaiBuku]}</td>
                         <td style=\"width:15%;text-align:right\">{$perolehan}</td>
                         <td style=\"width:15%\">{$value[Info]}</td>
               </tr>
             </tbody>";
                    $perolehanTotal+=$value[NilaiPerolehan];
                    $akumalasiTotal+=$value[AkumulasiPenyusutan];
                    $nilaiBukuTotal+=$value[NilaiBuku];
                    $no++;
               }
               $perolehanTotal = number_format($perolehanTotal, 2, ",", ".");
               $akumalasiTotal = number_format($akumalasiTotal, 2, ",", ".");
               $nilaiBukuTotal = number_format($nilaiBukuTotal, 2, ",", ".");

               $body.="<tbody>
               <tr>
                         <td colspan=\"7\" style=\"text-align:right\">Jumlah</td>
                         <td style=\"width:20%;text-align:right\">{$akumalasiTotal}</td>
                         <td style=\"width:20%;text-align:right\">{$nilaiBukuTotal}</td>
                         <td style=\"width:20%;text-align:right\">{$perolehanTotal}</td>
                         <td style=\"width:15%\"></td>
               </tr>
             </tbody></table>";

               $html[] = $head . $body;
          }

          return $html;
     }

     public function report_daftar_pengadaan($dataArr, $gambar, $tglawalperolehan, $tglakhirperolehan,$tglcetak) {
          if ($dataArr != "") {

               $no = 1;
               $skpdeh = "";
               $thn = "";
               $status_print = 0;
               $perolehanTotal = 0;
//pr($data);

$head = "
     <html>
     <head>
               <style>
                              table
		{
                                        font-size:9pt;
                                        font-family:Arial;
                                        border-collapse: collapse;											
                                        border-spacing:0;
            }
                         h3   
		{
		font-family:Arial;	
		font-size:12pt;
		color:#000;
		}
		p
		{
		font-size:10pt;
		font-family:Arial;
		font-weight:bold;
		}
		</style>
		</head>
               ";

               foreach ($dataArr as $key => $value) {
                    $perolehan = number_format($value[NilaiPerolehan], 2, ",", ".");
                    $uraian = $value[Uraian];
                    $nokontrak = $value[noKontrak];
                    $tglkontrak = $value[tglkontrak];
                    $nosp2d = $value[nosp2d];
                    $tglsp2d = $value[tglsp2d];
                    $keterangan = $value[keterangan];
                    $info = $value[info];
                    $Jumlah = $value[Jumlah];
                    $Satuan = number_format($value[Satuan], 2, ",", ".");
                    $Total = number_format($value[Total], 2, ",", ".");
                    $kodeSatker = $value[kodeSatker];
                    $Satker = $value[Satker];
                    if($value[StatusValidasi] == 1){
                         $info ="Aset Baru";
                    }else{
                         $info ="Kapitalisasi";
                    }
                   // list($nip_pengurus,$nama_jabatan_pengurus,$InfoJabatanPengurus)=$this->get_jabatan($kodeSatker,"1");
                    
                    
                    list($nip_pengurus,$nama_jabatan_pengurus,$InfoJabatanPengurus)=$this->get_jabatan($satker_id,"3",$thnPejabat);
				list($nip_pengguna,$nama_jabatan_pengguna,$InfoJabatanPengguna)=$this->get_jabatan($satker_id,"4",
				$thnPejabat);
				if($nip_pengurus!="")
				{
					$nip_pengurus_fix=$nip_pengurus;
				}
				else
				{
					$nip_pengurus_fix='........................................';
				}

				if($nip_pengguna!="")
				{
					$nip_pengguna_fix=$nip_pengguna;
				}
				else
				{
					$nip_pengguna_fix='........................................';
				}

				if($nama_jabatan_pengguna!="")
				{
					$nama_jabatan_pengguna_fix=$nama_jabatan_pengguna;
				}
				else
				{
					$nama_jabatan_pengguna_fix='........................................';
				}

				if($nama_jabatan_pengurus!="")
				{
					$nama_jabatan_pengurus_fix=$nama_jabatan_pengurus;
				}
				else
				{
					$nama_jabatan_pengurus_fix='........................................';
				}
                    
                    $footer ="	     
                                             
                    <table style=\"text-align: left; border-collapse: collapse; width: 1024px; height: 90px;\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\">

                         <tr>
                              <td style=\"text-align: center;\" colspan=\"3\" width=\"400px\">Mengetahui</td>
                              <td>&nbsp;</td>
                              <td>&nbsp;</td>
                              <td>&nbsp;</td>
                              <td>&nbsp;</td>
                              <td>&nbsp;</td>
                              <td style=\"text-align: center;\" colspan=\"3\" width=\"400px\">$this->NAMA_KABUPATEN, $tanggalCetak</td>
                         </tr>
                         
                         <tr>
                              <td style=\"text-align: center;\" colspan=\"3\">$InfoJabatanPengguna</td>
                              <td>&nbsp;</td>
                              <td>&nbsp;</td>
                              <td>&nbsp;</td>
                              <td>&nbsp;</td>
                              <td>&nbsp;</td>
                              <td style=\"text-align: center;\" colspan=\"3\">$InfoJabatanPengurus</td>
                         </tr>
                         <tr>
                              <td colspan=\"11\" style=\"height: 80px\"></td>
                         </tr>
                         
                         <tr>
                              <td style=\"text-align: center;\" colspan=\"3\">$nama_jabatan_pengguna_fix</td>
                              <td>&nbsp;</td>
                              <td>&nbsp;</td>
                              <td>&nbsp;</td>
                              <td>&nbsp;</td>
                              <td>&nbsp;</td>
                              <td style=\"text-align: center;\" colspan=\"3\">$nama_jabatan_pengurus_fix</td>
                         </tr>
                              <tr>
                              <td style=\"text-align: center;\" colspan=\"3\">______________________________</td>
                              <td>&nbsp;</td>
                              <td>&nbsp;</td>
                              <td>&nbsp;</td>
                              <td>&nbsp;</td>
                              <td>&nbsp;</td>
                              <td style=\"text-align: center;\" colspan=\"3\">______________________________</td>
                         </tr>
                         <tr>
                              <td style=\"text-align: center;\" colspan=\"3\">NIP.&nbsp;$nip_pengguna_fix</td>
                              <td>&nbsp;</td>
                              <td>&nbsp;</td>
                              <td>&nbsp;</td>
                              <td>&nbsp;</td>
                              <td>&nbsp;</td>
                              <td style=\"text-align: center;\" colspan=\"3\">NIP.&nbsp;$nip_pengurus_fix</td>
                         </tr>

                    </table>";
                   
                    $footer=  $this->set_footer_to_png($this->path, $this->url_rewrite, $footer);
                    if ($skpdeh == "" && $no == 1) {




               $body = "<table style=\"text-align: left; width: 100%;\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\">
                              <tr>
                                   <td style=\"width: 10%;\"><img style=\"width: 80px; height: 85px;\" alt=\"\" src=\"$gambar\"></td>
                                   <td colspan=\"2\" style=\";width: 70%; text-align: center;\">
                                        <h3>Daftar Pengadaan Barang</h3>
                                        <h3>Periode $tglawalperolehan s/d $tglakhirperolehan</h3>
                                   </td>
                              </tr>
                              <tr>
                                   <td>&nbsp;</td>
                                   <td>&nbsp;</td>
                              </tr>
                              <tr>
                                   <td>&nbsp;</td>
                                   <td>&nbsp;</td>
                              </tr>
                              <tr>
                                   <td colspan='2' style=\"width: 10%;text-align:left;font-weight:bold;\">SKPD</td>
                                   <td style=\"width: 90%;text-align:left;font-weight:bold;\">: $Satker</td>

                              </tr>
                              <tr>
                                   <td colspan='2' style=\"width: 10%;text-align:left;font-weight:bold;\">Kab/Kota</td>
                                   <td style=\"width: 90%;text-align:left;font-weight:bold;\">: Kotawaringin Timur</td>

                              </tr>
                               <tr>
                                   <td colspan='2' style=\"width: 10%;text-align:left;font-weight:bold;\">Provinsi</td>
                                   <td style=\"width: 90%;text-align:left;font-weight:bold;\">: Kalimantan Tengah</td>

                              </tr>
                         </table><br/>";
                         $body.="<table style=\"text-align: left; width: 100%; border-collapse: collapse;\" border=\"1\" cellpadding=\"0\" cellspacing=\"0\">
                              <thead>
                                   <tr style=\"text-align:center;font-weight:bold;\">
                                        <td rowspan='2' style=\"width:30px;text-align:center;font-weight:bold;\">No<br/> Urut </td>
                                        <td rowspan='2' style=\"width:180px;text-align:center;font-weight:bold;\">Nama / Jenis Barang yang dibeli</td>
                                        <td colspan='2' style=\"width:120px;text-align:center;font-weight:bold;\">Kuitansi/Kontrak</td>
                                        <td colspan='2'  style=\"width:120px;text-align:center;font-weight:bold;\">SP2D</td>
                                        <td colspan='2'  style=\"width:170px;text-align:center;font-weight:bold;\">Jumlah</td>
                                        <td rowspan='2' style=\"width:180px;text-align:center;font-weight:bold;\">Jenis Kontrak</td>
                                   </tr>
                                   <tr>
                                        <td style=\"text-align:center;font-weight:bold;\">Tanggal</td>
                                        <td style=\"text-align:center;font-weight:bold;\">Nomor</td>
                                        <td style=\"text-align:center;font-weight:bold;\">Tanggal</td>
                                        <td style=\"text-align:center;font-weight:bold;\">Nomor</td>
                                        <td style=\"text-align:center;font-weight:bold;\">Banyaknya<br/>Barang</td>
                                        <td style=\"text-align:center;font-weight:bold;\">Total</td>
                                   </tr>
                              </thead>";
                    }
                    
                    if ($skpdeh != $value[kodeSatker] && $no > 1) {
                    $perolehanTotal = number_format($perolehanTotal, 2, ",", ".");
                         $body.="<tbody>
                         <tr>
                              <td colspan=\"7\" style=\"text-align:center;font-weight:bold;\">Jumlah</td>
                              <td style=\"text-align:right\">{$perolehanTotal}</td>
                              <td colspan=\"2\"  style=\"width:15%\"></td>
                         </tr></tbody></table>";
                         
                         
                         $no=1;
                         $body.=$footer;
                          $html[] = $head . $body;
                          $perolehanTotal=0;
                          $body = "<table style=\"text-align: left; width: 100%;\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\">
                              <tr>
                                   <td style=\"width: 10%;\"><img style=\"width: 80px; height: 85px;\" alt=\"\" src=\"$gambar\"></td>
                                   <td colspan=\"2\" style=\";width: 70%; text-align: center;\">
                                        <h3>Daftar Pengadaan Barang</h3>
                                        <h3>Periode $tglawalperolehan s/d $tglakhirperolehan</h3>
                                   </td>
                              </tr>
                              <tr>
                                   <td>&nbsp;</td>
                                   <td>&nbsp;</td>
                              </tr>
                              <tr>
                                   <td>&nbsp;</td>
                                   <td>&nbsp;</td>
                              </tr>
                              <tr>
                                   <td colspan='2' style=\"width: 10%;text-align:left;font-weight:bold;\">SKPD</td>
                                   <td style=\"width: 90%;text-align:left;font-weight:bold;\">: $Satker</td>

                              </tr>
                              <tr>
                                   <td colspan='2' style=\"width: 10%;text-align:left;font-weight:bold;\">Kab/Kota</td>
                                   <td style=\"width: 90%;text-align:left;font-weight:bold;\">: Kotawaringin Timur</td>

                              </tr>
                               <tr>
                                   <td colspan='2' style=\"width: 10%;text-align:left;font-weight:bold;\">Provinsi</td>
                                   <td style=\"width: 90%;text-align:left;font-weight:bold;\">: Kalimantan Tengah</td>

                              </tr>
                         </table><br/>";
                         $body.="<table style=\"text-align: left; width: 100%; border-collapse: collapse;\" border=\"1\" cellpadding=\"0\" cellspacing=\"0\">
                              <thead>
                                   <tr style=\"text-align:center;font-weight:bold;\">
                                        <td rowspan='2' style=\"width:30px;text-align:center;font-weight:bold;\">No<br/> Urut </td>
                                        <td rowspan='2' style=\"width:180px;text-align:center;font-weight:bold;\">Nama / Jenis Barang yang dibeli</td>
                                        <td colspan='2' style=\"width:120px;text-align:center;font-weight:bold;\">Kuitansi/Kontrak</td>
                                        <td colspan='2'  style=\"width:120px;text-align:center;font-weight:bold;\">SP2D</td>
                                        <td colspan='2'  style=\"width:170px;text-align:center;font-weight:bold;\">Jumlah</td>
                                        <td rowspan='2' style=\"width:180px;text-align:center;font-weight:bold;\">Jenis Kontrak</td>
                                   </tr>
                                   <tr>
                                        <td style=\"text-align:center;font-weight:bold;\">Tanggal</td>
                                        <td style=\"text-align:center;font-weight:bold;\">Nomor</td>
                                        <td style=\"text-align:center;font-weight:bold;\">Tanggal</td>
                                        <td style=\"text-align:center;font-weight:bold;\">Nomor</td>
                                        <td style=\"text-align:center;font-weight:bold;\">Banyaknya<br/>Barang</td>
                                        <td style=\"text-align:center;font-weight:bold;\">Total</td>
                                   </tr></thead>";
                     }
                     
                    
                    $skpdeh=$value[kodeSatker];
                    $body.="<tbody>
                              <tr>
                                   <td style=\"text-align:center;\">$no</td>
                                   <td>$uraian</td>
                                   <td style=\"text-align:center;\">$tglkontrak</td>
                                   <td>$nokontrak</td>
                                   <td style=\"text-align:center;\">$tglsp2d</td>
                                   <td>$nosp2d</td>
                                   <td style=\"text-align:center;\">$Jumlah</td>
					     	<td style=\"text-align:right;\">$Total</td>
                                   <td>$info</td>
                              </tr>
                         </tbody>";
                    $perolehanTotal+=$value[Total];
                    $no++;
               }
               $perolehanTotal = number_format($perolehanTotal, 2, ",", ".");
               $body.="<tbody>
                    <tr>
                         

                         <td colspan=\"7\" style=\"text-align:center;font-weight:bold;\">Jumlah</td>
                         <td style=\"text-align:right\">{$perolehanTotal}</td>
                         <td colspan=\"2\"  style=\"width:15%\"></td>
                    </tr>
             </tbody></table>";
                         $body.=$footer;

               $html[] = $head . $body;
          }

          return $html;
     }


     public function report_daftar_pengadaan_rev($dataArr, $gambar, $tglawalperolehan, $tglakhirperolehan,$tglcetak) {
         // pr($dataArr); 
          //exit();
           if($dataArr!="")
          {
               $head = "<html>
                         <head>
                              <style>
                                   table
                                   {
                                        font-size:10pt;
                                        font-family:Arial;
                                        border-collapse: collapse;                                                      
                                        border-spacing:0;
                                   }
                                   h3
                                   {
                                        font-family:Arial;  
                                        font-size:13pt;
                                        color:#000;
                                             
                                   }
                                   p
                                   {
                                        font-size:10pt;
                                        font-family:Arial;
                                        font-weight:bold;
                                   }
                                   </style>
                              </head>
                               ";

                                        
               $no=1;
               $skpdeh="";
               $thn="";
               $status_print=0;
               $perolehanTotal=0;
               $kodePemilik= '';
               $temp =  array();
               $html="<table style=\"text-align: left; width: 100%;\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\">
                              <tr>
                                   <td style=\"width: 10%;\"><img style=\"width: 80px; height: 85px;\" alt=\"\" src=\"$gambar\"></td>
                                   <td colspan=\"2\" style=\"width: 70%; text-align: center;\">
                                        <h3>Daftar Pengadaan Barang</h3>
                                        <h3>Periode $tglawalperolehan s/d $tglakhirperolehan</h3>
                                   </td>
                              </tr>
                              <tr>
                                   <td>&nbsp;</td>
                                   <td>&nbsp;</td>
                                   <td>&nbsp;</td>
                              </tr>
                              <tr>
                                   <td>&nbsp;</td>
                                   <td>&nbsp;</td>
                                   <td>&nbsp;</td>
                              </tr>
                              <tr>
                                   <td colspan='2' style=\"width: 20%;text-align:left;font-weight:bold;\">KABUPATEN / KOTA</td>
                                   <td style=\"width: 80%;text-align:left;font-weight:bold;\">: $this->NAMA_KABUPATEN</td>

                              </tr>
                               <tr>
                                   <td colspan='2' style=\"width: 20%;text-align:left;font-weight:bold;\">PROVINSI</td>
                                   <td style=\"width: 80%;text-align:left;font-weight:bold;\">: $this->NAMA_PROVINSI</td>

                              </tr>
                         </table><br/>";
               $html.="<table style=\"text-align: left; width: 100%; border-collapse: collapse;\" border=\"1\" cellpadding=\"0\" cellspacing=\"0\">
                    <tr style=\"text-align:center;font-weight:bold;\">
                         <td rowspan='2' style=\"width:30px;text-align:center;font-weight:bold;\">No<br/> Urut </td>
                         <td rowspan='2' style=\"width:180px;text-align:center;font-weight:bold;\">Nama / Jenis Barang</td>
                         <td colspan='2' style=\"width:200px;text-align:center;font-weight:bold;\">Kuitansi/Kontrak</td>
                         <td colspan='2'  style=\"width:200px;text-align:center;font-weight:bold;\">SP2D</td>
                         <td colspan='2'  style=\"width:200px;text-align:center;font-weight:bold;\">SP2D Penunjang</td>
                         <td rowspan='2' style=\"width:120px;text-align:center;font-weight:bold;\">Nilai <br/>Perolehan</td>
                         <td rowspan='2' style=\"width:120px;text-align:center;font-weight:bold;\">Nilai <br/>Penunjang</td>
                         <td rowspan='2' style=\"width:120px;text-align:center;font-weight:bold;\">Nilai <br/>Perolehan Total</td>
                         <td rowspan='2' style=\"width:180px;text-align:center;font-weight:bold;\">Jenis Kontrak</td>
                    </tr>
                    <tr>
                         <td style=\"width:80px;text-align:center;font-weight:bold;\">Tanggal</td>
                         <td style=\"width:120px;text-align:center;font-weight:bold;\">Nomor</td>
                         <td style=\"width:80px;text-align:center;font-weight:bold;\">Tanggal</td>
                         <td style=\"width:120px;text-align:center;font-weight:bold;\">Nomor</td>
                         <td style=\"width:80px;text-align:center;font-weight:bold;\">Tanggal</td>
                         <td style=\"width:120px;text-align:center;font-weight:bold;\">Nomor</td>
                    </tr>";

               foreach ($dataArr as $key1 => $value1) {
                    // satker
                    $tmpSatker=$this->getNamaSatker($key1);
                    $Satker = "[".$key1."] ".$tmpSatker[0]->NamaSatker;
                    //pr($tmpSatker); 
                    $html.="<tr style=\"text-align:left;font-weight:bold; \">
                              <td style=\"text-align:left;font-weight:bold; \" colspan=\"12\">$Satker</td>
                         </tr>";
                    $num = 1;     
                    foreach ($value1 as $key2 => $value2) {
                              # code...
                              $html.="<tr style=\"text-align:left;font-weight:bold;\">
                                   <td style=\"text-align:center;font-weight:bold;\" >$num</td>
                                   <td style=\"text-align:left;font-weight:bold;\" colspan=\"11\">No Kontrak : $key2</td>
                              </tr>";
                         $no=1;   
                         $totalSatuan = 0;  
                         $totalBop = 0;  
                         $totalNP = 0;  
                         foreach ($value2['data'] as $key3 => $value3) {
                              # code...
                              $html.="
                                   <tr>
                                        <td style=\"text-align:center;\">$no</td>
                                        <td>$value3[uraian]</td>
                                        <td>$value3[TglKontrak]</td>
                                        <td>$value3[noKOntrak]</td>";
                                        $tmp_nosp2dp = array();
                                        foreach ($value3['sp2dpenunjang'] as $key4 => $value4) {
                                             $tmp_nosp2dp[] = $value4['nosp2d'];
                                        }
                                        $nosp2dp = implode('<br/>', $tmp_nosp2dp);
                                        
                                        $tmp2_tglsp2dp = array();
                                        foreach ($value3['sp2dpenunjang'] as $key5 => $value5) {
                                             $exp = explode('-', $value5['tglsp2d']);
                                             $tmp2_tglsp2dp[] = $exp[2]."/".$exp[1]."/".$exp[0];
                                        }
                                        $tglsp2dp = implode('<br/>', $tmp2_tglsp2dp);
                                        
                                        $tmp_nosp2d = array();
                                        foreach ($value3['sp2d'] as $key6 => $value6) {
                                             $tmp_nosp2d[] = $value6['nosp2d'];
                                        }
                                        $nosp2d = implode('<br/>', $tmp_nosp2d);
                                        
                                        $tmp_tglsp2d = array();
                                        foreach ($value3['sp2d'] as $key7 => $value7) {
                                             $exp2 = explode('-', $value7['tglsp2d']);
                                             $tmp_tglsp2d[] = $exp2[2]."/".$exp2[1]."/".$exp2[0];
                                        }
                                        $tglsp2d = implode('<br/>', $tmp_tglsp2d);
                                        
                                        $html.="<td>$tglsp2d</td>
                                        <td>$nosp2d</td>
                                        <td>$tglsp2dp</td>
                                        <td>$nosp2dp</td>
                                        <td style=\"text-align:right;\">".number_format($value3[satuan],2,",",".")."</td>
                                        <td style=\"text-align:right;\">".number_format($value3[bop],2,",",".")."</td>
                                        <td style=\"text-align:right;\">".number_format($value3[NilaiPerolehan],2,",",".")."</td>
                                        <td>$value3[tipeKontrak]</td>
                                        ";
                              $totalSatuan+= $value3[satuan];
                              $totalBop+= $value3[bop];
                              $totalNP+= $value3[NilaiPerolehan];
                              $no++;           
                         }

                         $html.="</tr>
                                   <tr>
                                        <td style=\"text-align: center; font-weight:bold;\" colspan=\"8\">Total</td>
                                        <td style=\"text-align: right;\">".number_format($totalSatuan,2,",",".")."</td>
                                        <td style=\"text-align: right;\">".number_format($totalBop,2,",",".")."</td>
                                        <td style=\"text-align: right;\">".number_format($totalNP,2,",",".")."</td>
                                        <td>&nbsp;</td>
                                   </tr>";
                         $num++;              
                    }
               }
          $html.="</table>";      
          $datHt[]=$html;
          return $datHt;
          }
     }

public function get_jabatan($satker,$jabatan){
	if($jabatan=="1")
		$namajabatan="Atasan Langsung";
	else if ($jabatan=="2")
		$namajabatan="Penyimpan Barang";
	else if ($jabatan=="3")
		$namajabatan="Pengurus Barang";
	else if ($jabatan=="4")
		$namajabatan="Pengguna Barang";
	else if ($jabatan=="5")
		$namajabatan="Kepala Daerah";
	else if ($jabatan=="6")
		$namajabatan="Pengelola BMD";
	$nip='';
	$nama_pejabat='';
	$query_getIDsatker="select Satker_ID from satker where kode='$satker' and Kd_Ruang is null";
	// echo $query_getIDsatker;
	$result=$this->retrieve_query($query_getIDsatker);	
	// pr($result);
	if($result!=""){
		foreach($result as $value){
			$Satker_ID=$value->Satker_ID;
		}
		$queryPejabat="select NIPPejabat, NamaPejabat,Jabatan from Pejabat where Satker_ID='$Satker_ID' and NamaJabatan='$namajabatan' limit 1";
		// echo $queryPejabat;
		$result2=$this->retrieve_query($queryPejabat);
		// pr($result2);
            if($result2!=""){
				foreach($result2 as $val){
					$nip=$val->NIPPejabat;
					$nama_pejabat=$val->NamaPejabat;
					$InfoJabatan=$val->Jabatan;
					
				}
			}
	}
	if($nip==""){
                    $nip='........................................';
               }
              if($nama_pejabat==""){
                   $nama_pejabat='........................................';
              }
              if($InfoJabatan==""){
                   $InfoJabatan='........................................';
              }
	return array($nip,$nama_pejabat,$InfoJabatan);
			
		
	}

  public function getNamaSatker($kodeSatker){

        $sqlSat="select sat.NamaSatker from Satker AS sat where sat.Kode='$kodeSatker' GROUP BY sat.Kode Limit 1";
        //   // echo $queryPejabat;
        // // pr($sqlSat);
          $resSat=$this->retrieve_query($sqlSat);
          // pr($resSat);
        // $sqlSat = array(
        //     'table'=>"Satker AS sat",
        //     'field'=>"sat.NamaSatker",
        //     'condition' => "sat.Kode='08' GROUP BY sat.Kode",
        //      );
        // // // //////////////////////////////////////pr($sqlSat);
        // $resSat = $this->db->lazyQuery($sqlSat,$debug);
        // pr($resSat);
        if ($resSat) return $resSat;
        return false;

    }
}

?>
