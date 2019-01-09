<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
#This code provided by:
#Andreas Hadiyono (andre.hadiyono@gmail.com)
#Gunadarma University
ob_start();
require_once('../../../config/config.php');
define("_JPGRAPH_PATH", "$path/function/mpdf/jpgraph/src/"); // must define this before including mpdf.php file
$JpgUseSVGFormat = true;
define('_MPDF_URI',"$url_rewrite/function/mpdf/"); 	// must be  a relative or absolute URI - not a file system path
include "../../report_engine.php";
require ('../../../function/mpdf/mpdf.php');
/*$tglawal = $_GET['tanggalAwal'];
if($tglawal != ''){
	$tglawalUsulan = $tglawal;
}else{
	$tglawalUsulan = '0000-00-00';
}
$tglakhirUsulan = $_GET['tanggalAkhir'];*/
$tahun = $_GET['tahun'];
$skpd_id = $_GET['skpd_id'];
$tipe=$_GET['tipe_file'];
// pr($_REQUEST);
// exit;
$tahun_neraca = $TAHUN_AKTIF + 1;
$thnPejabat = $TAHUN_AKTIF;

$REPORT=new report_engine();
$data=array(
    "modul"=>$modul,
    "mode"=>$mode,
	"tglawalperolehan"=>$tglawalperolehan,
    "tglakhirperolehan"=>$tglakhirperolehan,
    "skpd_id"=>$skpd_id,
	"tab"=>$tab
);
$REPORT->set_data($data);
$nama_kab = $NAMA_KABUPATEN;
$nama_prov = $NAMA_PROVINSI;
$gambar = $FILE_GAMBAR_KABUPATEN;
//head satker
$detailSatker = $REPORT->get_satker($skpd_id);
// pr($detailSatker);
// exit;
$NoBidang = $detailSatker[0];
$NoUnitOrganisasi = $detailSatker[1];
$NoSubUnitOrganisasi = $detailSatker[2];
$NoUPB = $detailSatker[3];
if($NoBidang !=""){
	$paramKodeLokasi = $NoBidang;
}
if($NoBidang !="" && $NoUnitOrganisasi != ""){
	$paramKodeLokasi = $NoUnitOrganisasi;
}
if($NoBidang !="" && $NoUnitOrganisasi != "" && $NoSubUnitOrganisasi !=""){
	$paramKodeLokasi = $NoUnitOrganisasi.".".$NoSubUnitOrganisasi;
}
if($NoBidang !="" && $NoUnitOrganisasi != "" && $NoSubUnitOrganisasi !="" && $NoUPB !=""){
	$paramKodeLokasi = $NoUnitOrganisasi.".".$NoSubUnitOrganisasi.".".$NoUPB;
}
$Bidang = $detailSatker[4][0];
$UnitOrganisasi = $detailSatker[4][1];
$SubUnitOrganisasi = $detailSatker[4][2];
$UPB = $detailSatker[4][3];

if($tipe == 1){
	$gmbr = "<img style=\"width: 80px; height: 85px;\" src=\"$gambar\">";
	$head = "<table style=\"text-align: left; width: 100%;\" border=\"0\"
			 cellpadding=\"2\" cellspacing=\"2\">
			  <tbody>
				<tr>
				  <td style=\"width: 150px; text-align: LEFT;\">$gmbr</td>
				  <td style=\"width: 902px; text-align: center;\">
				  <h3>RENCANA KEBUTUHAN PEMELIHARAAN BARANG MILIK DAERAH</h3>
				  <h3>PENGGUNA BARANG $UPB</h3>
				  <h3>TAHUN $tahun_neraca</h3>
				  </td>
				</tr>
			  </tbody>
			</table>";
}else{
	$gmbr ="";
	$head = "<table style=\"text-align: center; width: 100%;\" border=\"0\"
			 cellpadding=\"0\" cellspacing=\"0\">
			  <tbody>
				<tr>
					<td><p>RENCANA KEBUTUHAN BARANG MILIK DAERAH</p></td>
				</tr>
				<tr>
					<td><p>PENGGUNA BARANG $UPB</p></td>
				</tr>
				<tr>
					<td><p>TAHUN $tahun_neraca</p></td>
				</tr>	
			  </tbody>
			</table>";
}

//jabatan
function get_jabatan($satker,$jabatan,$thnPejabat){
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
	//echo $query_getIDsatker;
	$result = mysql_query($query_getIDsatker) or die(mysql_error());
	while ($data = mysql_fetch_assoc($result)) {
		$Satker_ID=$data['Satker_ID'];
	}
	$queryPejabat="select NIPPejabat, NamaPejabat,GUID  from Pejabat where Satker_ID='$Satker_ID' and NamaJabatan='$namajabatan' and Tahun = '$thnPejabat' limit 1";
	//echo $queryPejabat;
	$result2 = mysql_query($queryPejabat) or die(mysql_error());
	while ($data2 = mysql_fetch_assoc($result2)) {
		$nip=$data2['NIPPejabat'];
		$nama_pejabat=$data2['NamaPejabat'];
		$InfoJabatan=$data2['GUID'];
	}	
	return array($nip,$nama_pejabat,$InfoJabatan);
}

function set_footer_to_png($path,$url_rewrite,$data){
			 

		$waktu=date("dymhis");
		$random=  rand();
		$nama_html="footer$waktu$random";
		
		$detect = strpos($_SERVER["HTTP_USER_AGENT"], 'Windows');
		$path_data_program="$path/report/wkhtmltoimage";

		$path_data_txt="$path/report/footer/data/$nama_html.html";

		$path_data_txt_url="../../footer/data/$nama_html.html";
		$fp = fopen($path_data_txt, 'w');
		fwrite($fp, $data);
		fclose($fp);
		chmod($path_data_txt,0777);

		$program="$path_data_program --enable-plugins $path_data_txt  $path_data_txt.png";
		$exec = exec("$program");
		$hasil="<img src='$path_data_txt_url.png'>";

		return  $hasil;

} 

//pejabat
list($nip_pengguna,$nama_jabatan_pengguna,$InfoJabatanPengguna)= get_jabatan($skpd_id,"4",$thnPejabat);
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

/*$ex = explode('.',$skpd_id);
$hit = count($ex);
if($hit == 1){
	$header = "<tr>
          <td style=\"width: 200px; font-weight: bold; text-align: left;\">BIDANG</td>
          <td style=\"text-align: center; font-weight: bold; width: 10px;\">:</td>
          <td style=\"width: 873px; font-weight: bold;\">$Bidang</td>
        </tr>
		";
}elseif($hit == 2){
	$header = "<tr>
          <td style=\"width: 200px; font-weight: bold; text-align: left;\">BIDANG</td>
          <td style=\"text-align: center; font-weight: bold; width: 10px;\">:</td>
          <td style=\"width: 873px; font-weight: bold;\">$Bidang</td>
        </tr>
		<tr>
          <td style=\"width: 200px; font-weight: bold; text-align: left;\">UNIT ORGANISASI</td>
          <td style=\"text-align: center; font-weight: bold; width: 10px;\">:</td>
          <td style=\"width: 873px; font-weight: bold;\">$UnitOrganisasi</td>
        </tr>";
}elseif($hit == 3){
	$header = "<tr>
          <td style=\"width: 200px; font-weight: bold; text-align: left;\">BIDANG</td>
          <td style=\"text-align: center; font-weight: bold; width: 10px;\">:</td>
          <td style=\"width: 873px; font-weight: bold;\">$Bidang</td>
        </tr>
		<tr>
          <td style=\"width: 200px; font-weight: bold; text-align: left;\">UNIT ORGANISASI</td>
          <td style=\"text-align: center; font-weight: bold; width: 10px;\">:</td>
          <td style=\"width: 873px; font-weight: bold;\">$UnitOrganisasi</td>
        </tr>
		<tr>
          <td style=\"width: 200px; font-weight: bold; text-align: left;\">SUB UNIT ORGANISASI</td>
          <td style=\"text-align: center; font-weight: bold; width: 10px;\">:</td>
          <td style=\"width: 873px; font-weight: bold;\">$SubUnitOrganisasi</td>
        </tr>";
}elseif($hit == 4){
	$header = "<tr>
          <td style=\"width: 200px; font-weight: bold; text-align: left;\">BIDANG</td>
          <td style=\"text-align: center; font-weight: bold; width: 10px;\">:</td>
          <td style=\"width: 873px; font-weight: bold;\">$Bidang</td>
        </tr>
		<tr>
          <td style=\"width: 200px; font-weight: bold; text-align: left;\">UNIT ORGANISASI</td>
          <td style=\"text-align: center; font-weight: bold; width: 10px;\">:</td>
          <td style=\"width: 873px; font-weight: bold;\">$UnitOrganisasi</td>
        </tr>
		<tr>
          <td style=\"width: 200px; font-weight: bold; text-align: left;\">SUB UNIT ORGANISASI</td>
          <td style=\"text-align: center; font-weight: bold; width: 10px;\">:</td>
          <td style=\"width: 873px; font-weight: bold;\">$SubUnitOrganisasi</td>
        </tr>
		<tr>
          <td style=\"width: 200px; font-weight: bold; text-align: left;\">UPB</td>
          <td style=\"text-align: center; font-weight: bold; width: 10px;\">:</td>
          <td style=\"width: 873px; font-weight: bold;\">$UPB</td>
        </tr>";
}*/
// echo $head;
// exit;
//begin 
//$paramSql = "u.tgl_usul >='$tglawalUsulan' AND u.tgl_usul <='$tglakhirUsulan'";
$paramSql = "YEAR(u.tgl_usul) ='$tahun' AND kodeSatker = '$skpd_id'";
$paramStatus =" AND u.status_usulan = 1 AND u.status_verifikasi = 1 AND u.status_penetapan = 1 AND u.status_validasi = 1
				AND a.status_verifikasi = 1 AND a.status_penetapan = 1 AND a.status_validasi = 1";
$sql = "SELECT u.*,a.*,k.Kode,k.Uraian FROM `usulan_rencana_pemeliharaan` as u 
				INNER JOIN usulan_rencana_pemeliharaan_aset as a on a.idus = u.idus 
				INNER JOIN kelompok as k on k.Kode = a.kodeKelompok
				WHERE $paramSql $paramStatus 
				ORDER BY u.KDPROGRAM asc";	
//pr($sql);				
$result = mysql_query($sql) or die(mysql_error());
while ($data = mysql_fetch_assoc($result)) {
	$list[] = $data;
}
//pr($list);
//exit();
//level kodebarang
foreach ($list as $ky => $vl) {
	# code...
	//pr($key);
	//pr($vl);
	$data[$vl['KDPROGRAM'].'-'.$vl['KDGIAT'].'-'.$vl['KDOUTPUT'].'-'.$vl['kodeKelompok']]['ID'] = $vl['KDPROGRAM'].'-'.$vl['KDGIAT'].'-'.$vl['KDOUTPUT'].'-'.$vl['kodeKelompok']; 		
	$data[$vl['KDPROGRAM'].'-'.$vl['KDGIAT'].'-'.$vl['KDOUTPUT'].'-'.$vl['kodeKelompok']]['KDPROGRAM'] = $vl['KDPROGRAM']; 		
	$data[$vl['KDPROGRAM'].'-'.$vl['KDGIAT'].'-'.$vl['KDOUTPUT'].'-'.$vl['kodeKelompok']]['NMPROGRAM'] = $vl['NMPROGRAM']; 		
	$data[$vl['KDPROGRAM'].'-'.$vl['KDGIAT'].'-'.$vl['KDOUTPUT'].'-'.$vl['kodeKelompok']]['KDGIAT'] = $vl['KDGIAT']; 	
	$data[$vl['KDPROGRAM'].'-'.$vl['KDGIAT'].'-'.$vl['KDOUTPUT'].'-'.$vl['kodeKelompok']]['NMGIAT'] = $vl['NMGIAT']; 		
	$data[$vl['KDPROGRAM'].'-'.$vl['KDGIAT'].'-'.$vl['KDOUTPUT'].'-'.$vl['kodeKelompok']]['KDOUTPUT'] = $vl['KDOUTPUT']; 		
	$data[$vl['KDPROGRAM'].'-'.$vl['KDGIAT'].'-'.$vl['KDOUTPUT'].'-'.$vl['kodeKelompok']]['NMOUTPUT'] = $vl['NMOUTPUT'];
	$data[$vl['KDPROGRAM'].'-'.$vl['KDGIAT'].'-'.$vl['KDOUTPUT'].'-'.$vl['kodeKelompok']]['kodeKelompok'] = $vl['kodeKelompok'];
	$data[$vl['KDPROGRAM'].'-'.$vl['KDGIAT'].'-'.$vl['KDOUTPUT'].'-'.$vl['kodeKelompok']]['Uraian'] = $vl['Uraian'];
	$data[$vl['KDPROGRAM'].'-'.$vl['KDGIAT'].'-'.$vl['KDOUTPUT'].'-'.$vl['kodeKelompok']]['jml_usul_rev'] = $vl['jml_usul_rev'];
	$data[$vl['KDPROGRAM'].'-'.$vl['KDGIAT'].'-'.$vl['KDOUTPUT'].'-'.$vl['kodeKelompok']]['satuan_usul_rev'] = $vl['satuan_usul_rev'];
	$data[$vl['KDPROGRAM'].'-'.$vl['KDGIAT'].'-'.$vl['KDOUTPUT'].'-'.$vl['kodeKelompok']]['pemeliharaan'] = $vl['pemeliharaan'];
	$data[$vl['KDPROGRAM'].'-'.$vl['KDGIAT'].'-'.$vl['KDOUTPUT'].'-'.$vl['kodeKelompok']]['jml_usul'] = $vl['jml_usul'];
	$data[$vl['KDPROGRAM'].'-'.$vl['KDGIAT'].'-'.$vl['KDOUTPUT'].'-'.$vl['kodeKelompok']]['jml_baik'] = $vl['jml_baik'];
	$data[$vl['KDPROGRAM'].'-'.$vl['KDGIAT'].'-'.$vl['KDOUTPUT'].'-'.$vl['kodeKelompok']]['jml_rusak_ringan'] = $vl['jml_rusak_ringan'];
	$data[$vl['KDPROGRAM'].'-'.$vl['KDGIAT'].'-'.$vl['KDOUTPUT'].'-'.$vl['kodeKelompok']]['satuan_usul'] = $vl['satuan_usul'];
	$data[$vl['KDPROGRAM'].'-'.$vl['KDGIAT'].'-'.$vl['KDOUTPUT'].'-'.$vl['kodeKelompok']]['ket'] = $vl['ket'];
	$data[$vl['KDPROGRAM'].'-'.$vl['KDGIAT'].'-'.$vl['KDOUTPUT'].'-'.$vl['kodeKelompok']]['status_barang'] = $vl['status_barang'];
						
}	
//level output
foreach ($data as $key => $value) {
	# code...
	//pr($key);
	//pr($value);
	$dataOutput[$value['KDPROGRAM'].'-'.$value['KDGIAT'].'-'.$value['KDOUTPUT']]['ID'] = $value['KDPROGRAM'].'-'.$value['KDGIAT'].'-'.$value['KDOUTPUT']; 		
	$dataOutput[$value['KDPROGRAM'].'-'.$value['KDGIAT'].'-'.$value['KDOUTPUT']]['KDPROGRAM'] = $value['KDPROGRAM']; 		
	$dataOutput[$value['KDPROGRAM'].'-'.$value['KDGIAT'].'-'.$value['KDOUTPUT']]['NMPROGRAM'] = $value['NMPROGRAM']; 		
	$dataOutput[$value['KDPROGRAM'].'-'.$value['KDGIAT'].'-'.$value['KDOUTPUT']]['KDGIAT'] = $value['KDGIAT']; 		
	$dataOutput[$value['KDPROGRAM'].'-'.$value['KDGIAT'].'-'.$value['KDOUTPUT']]['NMGIAT'] = $value['NMGIAT']; 		
	$dataOutput[$value['KDPROGRAM'].'-'.$value['KDGIAT'].'-'.$value['KDOUTPUT']]['KDOUTPUT'] = $value['KDOUTPUT']; 		
	$dataOutput[$value['KDPROGRAM'].'-'.$value['KDGIAT'].'-'.$value['KDOUTPUT']]['NMOUTPUT'] = $value['NMOUTPUT']; 		
	$dataOutput[$value['KDPROGRAM'].'-'.$value['KDGIAT'].'-'.$value['KDOUTPUT']]['DATA'][] = $value; 		
				
}
//level kegiatan
foreach ($dataOutput as $key2 => $value2) {
	# code...
	$dataKegiatan[$value2['KDPROGRAM'].'-'.$value2['KDGIAT']]['ID'] = $value2['KDPROGRAM'].'-'.$value2['KDGIAT'];
	$dataKegiatan[$value2['KDPROGRAM'].'-'.$value2['KDGIAT']]['KDPROGRAM'] = $value2['KDPROGRAM']; 		
	$dataKegiatan[$value2['KDPROGRAM'].'-'.$value2['KDGIAT']]['NMPROGRAM'] = $value2['NMPROGRAM']; 		
	$dataKegiatan[$value2['KDPROGRAM'].'-'.$value2['KDGIAT']]['KDGIAT'] = $value2['KDGIAT']; 		
	$dataKegiatan[$value2['KDPROGRAM'].'-'.$value2['KDGIAT']]['NMGIAT'] = $value2['NMGIAT']; 		
	//$dataKegiatan[$value2['KDPROGRAM'].'-'.$value2['KDGIAT']]['KDOUTPUT'] = $value2['KDOUTPUT']; 		
	//$dataKegiatan[$value2['KDPROGRAM'].'-'.$value2['KDGIAT']]['NMOUTPUT'] = $value2['NMOUTPUT']; 		
	$dataKegiatan[$value2['KDPROGRAM'].'-'.$value2['KDGIAT']]['DATA'][] = $value2; 		
				
}
//level program
foreach ($dataKegiatan as $key3 => $value3) {
	# code...
	$dataProgram[$value3['KDPROGRAM']]['ID'] = $value3['KDPROGRAM'];
	$dataProgram[$value3['KDPROGRAM']]['KDPROGRAM'] = $value3['KDPROGRAM']; 		
	$dataProgram[$value3['KDPROGRAM']]['NMPROGRAM'] = $value3['NMPROGRAM']; 		
	//$dataProgram[$value3['KDPROGRAM']]['KDGIAT'] = $value3['KDGIAT']; 		
	//$dataProgram[$value3['KDPROGRAM']]['NMGIAT'] = $value3['NMGIAT']; 		
	//$dataProgram[$value3['KDPROGRAM']]['KDOUTPUT'] = $value3['KDOUTPUT']; 		
	//$dataProgram[$value3['KDPROGRAM']]['NMOUTPUT'] = $value3['NMOUTPUT']; 		
	$dataProgram[$value3['KDPROGRAM']]['DATA'][] = $value3; 		
				
}
//pr($dataProgram);
//html
$head ="<head>
			  <meta content=\"text/html; charset=UTF-8\"http-equiv=\"content-type\">
			  <title></title>
			 <style>
				table
				{
					font-size:9pt;
					border-collapse: collapse;											
					border-spacing:0;
				}
				h3
				{
					font-size:12pt;			
				}
				p
				{
					font-size:10pt;
				}
				</style> 
			</head>
			<body>
			$head
			<br>
			<table style=\"text-align: left; width: 100%;\" border=\"0\" cellpadding=\"2\" cellspacing=\"2\">
			  <tbody>
				<tr>
				  <td style=\"width: 200px; font-weight: bold; text-align: left;\">KABUPATEN / KOTA</td>
				  <td style=\"text-align: center; font-weight: bold; width: 10px;\">:</td>
				  <td style=\"width: 873px; font-weight: bold;\">$nama_kab </td>
				</tr>
				<tr>
				  <td style=\"width: 200px; font-weight: bold; text-align: left;\">PROVINSI</td>
				  <td style=\"text-align: center; font-weight: bold; width: 10px;\">:</td>
				  <td style=\"width: 873px; font-weight: bold;\">$nama_prov</td>
				</tr>
				$header
			  </tbody>
			</table>
				<br>";
$head.=" <table style=\"width: 100%; text-align: left; margin-left: auto; margin-right: auto; border-collapse:collapse\" border=\"1\" cellpadding=\"0\" cellspacing=\"0\; \">
	<tr>
		<td style=\" text-align: center; font-weight: bold; width: \" rowspan=\"3\" >No</td>
		<td style=\" text-align: center; font-weight: bold; width: \" rowspan=\"3\">Nama Pengguna Barang<br/>Program/Kegiatan/Output</td>
		<td style=\" text-align: center; font-weight: bold; width: \" colspan=\"8\" >Barang Yang Dipelihara</td>
		<td style=\" text-align: center; font-weight: bold; width: \" colspan=\"3\" >Usulan Kebutuhan Pemeliharaan</td>
		<td style=\" text-align: center; font-weight: bold; width: \" rowspan=\"3\" >Ket</td>
	</tr>
	<tr>
	   <td style=\" text-align: center; font-weight: bold; width: \" rowspan=\"2\">Kode Barang</td>
	   <td style=\" text-align: center; font-weight: bold; width: \" rowspan=\"2\">Nama Barang</td>
	   <td style=\" text-align: center; font-weight: bold; width: \" rowspan=\"2\">Jumlah</td>
	   <td style=\" text-align: center; font-weight: bold; width: \" rowspan=\"2\">Satuan</td>
	   <td style=\" text-align: center; font-weight: bold; width: \" rowspan=\"2\">Status Barang</td>

	   <td style=\" text-align: center; font-weight: bold; width: \" colspan=\"3\">Kondisi</td>

	   <td style=\" text-align: center; font-weight: bold; width: \" rowspan=\"2\">Nama Pemelihara</td>
	   <td style=\" text-align: center; font-weight: bold; width: \" rowspan=\"2\">Jumlah</td>
	   <td style=\" text-align: center; font-weight: bold; width: \" rowspan=\"2\">Satuan</td>
	</tr>
	<tr>
	   <td style=\" text-align: center; font-weight: bold; width: \">B</td>
	   <td style=\" text-align: center; font-weight: bold; width: \">RR</td>
	   <td style=\" text-align: center; font-weight: bold; width: \">RB</td>

	</tr>
	<tr>
		<td style=\" text-align: center; font-weight: bold; width: \">1</td>
		<td style=\" text-align: center; font-weight: bold; width: \">2</td>
		<td style=\" text-align: center; font-weight: bold; width: \">3</td>
		<td style=\" text-align: center; font-weight: bold; width: \">4</td>
		<td style=\" text-align: center; font-weight: bold; width: \">5</td>
		<td style=\" text-align: center; font-weight: bold; width: \">6</td>
		<td style=\" text-align: center; font-weight: bold; width: \">7</td>
		<td style=\" text-align: center; font-weight: bold; width: \">8</td>
		<td style=\" text-align: center; font-weight: bold; width: \">9</td>
		<td style=\" text-align: center; font-weight: bold; width: \">10</td>
		<td style=\" text-align: center; font-weight: bold; width: \">11</td>
		<td style=\" text-align: center; font-weight: bold; width: \">12</td>
		<td style=\" text-align: center; font-weight: bold; width: \">13</td>
		<td style=\" text-align: center; font-weight: bold; width: \">14</td>
	</tr>
	<tr>
		<td style=\" text-align: center; font-weight: bold; width: \">&nbsp;</td>
		<td style=\" text-align: center; font-weight: bold; width: \">&nbsp;</td>
		<td style=\" text-align: center; font-weight: bold; width: \">&nbsp;</td>
		<td style=\" text-align: center; font-weight: bold; width: \">&nbsp;</td>
		<td style=\" text-align: center; font-weight: bold; width: \">&nbsp;</td>
		<td style=\" text-align: center; font-weight: bold; width: \">&nbsp;</td>
		<td style=\" text-align: center; font-weight: bold; width: \">&nbsp;</td>
		<td style=\" text-align: center; font-weight: bold; width: \">&nbsp;</td>
		<td style=\" text-align: center; font-weight: bold; width: \">&nbsp;</td>
		<td style=\" text-align: center; font-weight: bold; width: \">&nbsp;</td>
		<td style=\" text-align: center; font-weight: bold; width: \">&nbsp;</td>
		<td style=\" text-align: center; font-weight: bold; width: \">&nbsp;</td>
		<td style=\" text-align: center; font-weight: bold; width: \">&nbsp;</td>
		<td style=\" text-align: center; font-weight: bold; width: \">&nbsp;</td>
	</tr>
	<tr>
		<td style=\" text-align: center; font-weight: bold; width: \">1</td>
		<td style=\" text-align: ; font-weight: bold; width: \">1. Kuasa Pengguna Barang</td>
		<td style=\" text-align: center; font-weight: bold; width: \">&nbsp;</td>
		<td style=\" text-align: center; font-weight: bold; width: \">&nbsp;</td>
		<td style=\" text-align: center; font-weight: bold; width: \">&nbsp;</td>
		<td style=\" text-align: center; font-weight: bold; width: \">&nbsp;</td>
		<td style=\" text-align: center; font-weight: bold; width: \">&nbsp;</td>
		<td style=\" text-align: center; font-weight: bold; width: \">&nbsp;</td>
		<td style=\" text-align: center; font-weight: bold; width: \">&nbsp;</td>
		<td style=\" text-align: center; font-weight: bold; width: \">&nbsp;</td>
		<td style=\" text-align: center; font-weight: bold; width: \">&nbsp;</td>
		<td style=\" text-align: center; font-weight: bold; width: \">&nbsp;</td>
		<td style=\" text-align: center; font-weight: bold; width: \">&nbsp;</td>
		<td style=\" text-align: center; font-weight: bold; width: \">&nbsp;</td>
	</tr>";
//level program
$i_prog = 1;
foreach ($dataProgram as $key => $value) {
		# code...
		$body.="<tr>
				<td style=\" text-align: center; font-weight: bold; width: \">&nbsp;</td>
				<td style=\" text-align: ; font-weight: bold; width: \">$i_prog. Program</td>
				<td style=\" text-align: ; font-weight: bold; width: \" colspan=\"12\">[$value[KDPROGRAM]] $value[NMPROGRAM]</td>
			</tr>";
	//level kegiatan	
	$i_kegt = 1;	
	foreach ($value['DATA'] as $keyKegt => $valueKegt) {
		$body.="<tr>
				<td style=\" text-align: center; font-weight: bold; width: \">&nbsp;</td>
				<td style=\" text-align: ; font-weight: bold; width: \">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$i_kegt. Kegiatan</td>
				<td style=\" text-align: ; font-weight: bold; width: \" colspan=\"12\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;[$valueKegt[KDGIAT]] $valueKegt[NMGIAT]</td>
			</tr>";
		//level output
		$i_out = 1;	
		foreach ($valueKegt['DATA'] as $keyOut => $valueOut) {
			# code...
			$body.="<tr>
				<td style=\" text-align: center; font-weight: bold; width: \">&nbsp;</td>
				<td style=\" text-align: ; font-weight: bold; width: \">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$i_out. Output</td>
				<td style=\" text-align: ; font-weight: bold; width: \" colspan=\"12\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;[$valueOut[KDOUTPUT]] $valueOut[NMOUTPUT]</td>
			</tr>";
			//level detail
			$i_loop=1;
			foreach ($valueOut['DATA'] as $keyBrng => $valueBrng) {
				# code...
				$body.="<tr>
					<td style=\" text-align: center; font-weight: bold; width: \">&nbsp;</td>
					<td style=\" text-align: center; font-weight: bold; width: \">$i_loop.</td>
					<td style=\" text-align: ; font-weight: bold; width: \">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$valueBrng[kodeKelompok]</td>
					<td style=\" text-align: ; font-weight: bold; width: \">$valueBrng[Uraian]</td>
					<td style=\" text-align: center; font-weight: bold; width: \">$valueBrng[jml_usul_rev]</td>
					<td style=\" text-align: center; font-weight: bold; width: \">$valueBrng[satuan_usul_rev]</td>
					<td style=\" text-align: center; font-weight: bold; width: \">$valueBrng[status_barang]</td>
					<td style=\" text-align: center; font-weight: bold; width: \">$valueBrng[jml_baik]</td>
					<td style=\" text-align: center; font-weight: bold; width: \">$valueBrng[jml_rusak_ringan]</td>
					<td style=\" text-align: ; font-weight: bold; width: \">&nbsp;</td>
					<td style=\" text-align: ; font-weight: bold; width: \">$valueBrng[pemeliharaan]</td>
					<td style=\" text-align: center; font-weight: bold; width: \">$valueBrng[jml_usul]</td>
					<td style=\" text-align: center; font-weight: bold; width: \">$valueBrng[satuan_usul]</td>
					<td style=\" text-align: ; font-weight: bold; width: \">$valueBrng[ket]</td>
				</tr>";
				$i_loop++;
			}
		$i_out++;	
		}
		$i_kegt++;			
	}
	$i_prog++;			
}
$foot="</table>";	
$foot.="<table border=\"0\">
			<tr>
				<td colspan=\"15\">&nbsp;</td>
			</tr>
		</table>";  
$footer ="	     
 <table style=\"text-align: left; border-collapse: collapse; width: 1024px; height: 90px;\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\">

	  <tr>
		   <td style=\"text-align: center;\" colspan=\"8\" width=\"400px\"></td>
		   <td style=\"text-align: center;\" colspan=\"7\" width=\"400px\">Sampit, $tanggalCetak</td>
	  </tr>
	  
	  <tr>
		   <td style=\"text-align: center;\" colspan=\"8\"></td>
		   <td style=\"text-align: center;\" colspan=\"7\">$InfoJabatanPengguna</td>
	  </tr>
	   <tr>
		   <td style=\"text-align: center;\" colspan=\"8\"></td>
		   <td style=\"text-align: center;\" colspan=\"7\">$NAMA_KABUPATEN</td>
	  </tr>
	  <tr>
		   <td colspan=\"15\" style=\"height: 80px\"></td>
	  </tr>
	  
	  <tr>
		   <td style=\"text-align: center;\" colspan=\"8\"></td>
		   <td style=\"text-align: center;\" colspan=\"7\">$nama_jabatan_pengguna_fix</td>
	  </tr>
		   <tr>
		   <td style=\"text-align: center;\" colspan=\"8\"></td>
		   <td style=\"text-align: center;\" colspan=\"7\">______________________________</td>
	  </tr>
	  <tr>
		   <td style=\"text-align: center;\" colspan=\"8\"></td>
		   <td style=\"text-align: center;\" colspan=\"7\">NIP.&nbsp;$nip_pengguna_fix</td>
	  </tr>

 </table>";
if($tipe!="2"){
	$footer=  set_footer_to_png($path, $url_rewrite, $footer);	
	$html = $head.$body.$foot.$footer;
}else{
	$html = $head.$body.$foot;
}
//echo $html;
//exit();
//untuk print output html
// echo $html; 
// exit;
if($tipe!="2"){
$REPORT->show_status_download_kib();
$mpdf=new mPDF('','','','',15,15,16,16,9,9,'L');
$mpdf->AddPage('L','','','','',15,15,16,16,9,9);
$mpdf->setFooter('{PAGENO}') ;
$mpdf->progbar_heading = '';
$mpdf->StartProgressBarOutput(2);
$mpdf->useGraphs = true;
$mpdf->list_number_suffix = ')';
$mpdf->hyphenate = true;
//$mpdf->debug = true;
//print output pdf
$mpdf->WriteHTML($html);
$count = count($html);
	for ($i = 0; $i < $count; $i++) {
		 if($i==0)
			  $mpdf->WriteHTML($html[$i]);
		 else
		 {
			   $mpdf->AddPage('L','','','','',15,15,16,16,9,9);
			   $mpdf->WriteHTML($html[$i]);
			   
		 }
	}
$waktu=date("d-m-y_h-i-s");
$namafile="$path/report/output/Rencana_Pemeliharaan_$waktu.pdf";
$mpdf->Output("$namafile",'F');
$namafile_web="$url_rewrite/report/output/Rencana_Pemeliharaan_$waktu.pdf";
echo "<script>window.location.href='$namafile_web';</script>";
exit;
}
else
{
	$waktu=date("d-m-y_h:i:s");
	$filename ="Rencana_Pemeliharaan_$waktu.xls";
	header('Content-type: application/ms-excel');
	header('Content-Disposition: attachment; filename='.$filename);
	echo $html; 
}
?>