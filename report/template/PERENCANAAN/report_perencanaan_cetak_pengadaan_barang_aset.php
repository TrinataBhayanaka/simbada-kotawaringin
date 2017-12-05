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
$tglawal = $_GET['tanggalAwal'];
if($tglawal != ''){
	$tglawalUsulan = $tglawal;
}else{
	$tglawalUsulan = '0000-00-00';
}
$tglakhirUsulan = $_GET['tanggalAkhir'];
$skpd_id = $_GET['skpd_id'];
$tipe=$_GET['tipe_file'];
// pr($_REQUEST);
// exit;
$ex = explode('-',$tglakhirUsulan);
$tahun_neraca = $ex[0];
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
if($tipe == 1){
	$gmbr = "<img style=\"width: 80px; height: 85px;\" src=\"$gambar\">";
}else{
	$gmbr ="";
}
$paramSql = "tgl_usul >='$tglawalUsulan' AND tgl_usul <='$tglakhirUsulan'";
//begin 
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
   
$ex = explode('.',$skpd_id);
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
}
// echo $head;
// exit;
$sql = "SELECT u.*,a.*,k.Kode,k.Uraian FROM `usulan_rencana_pengadaaan` as u 
				INNER JOIN usulan_rencana_pengadaaan_aset as a on a.idus = u.idus 
				INNER JOIN kelompok as k on k.Kode = a.kodeKelompok
				WHERE $paramSql
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
	$data[$vl['KDPROGRAM'].'-'.$vl['KDGIAT'].'-'.$vl['KDOUTPUT'].'-'.$vl['kodeKelompok']]['jml_usul'] = $vl['jml_usul'];
	$data[$vl['KDPROGRAM'].'-'.$vl['KDGIAT'].'-'.$vl['KDOUTPUT'].'-'.$vl['kodeKelompok']]['satuan_usul'] = $vl['satuan_usul'];
	$data[$vl['KDPROGRAM'].'-'.$vl['KDGIAT'].'-'.$vl['KDOUTPUT'].'-'.$vl['kodeKelompok']]['jml_max_rev'] = $vl['jml_max_rev'];
	$data[$vl['KDPROGRAM'].'-'.$vl['KDGIAT'].'-'.$vl['KDOUTPUT'].'-'.$vl['kodeKelompok']]['satuan_max'] = $vl['satuan_max'];
	$data[$vl['KDPROGRAM'].'-'.$vl['KDGIAT'].'-'.$vl['KDOUTPUT'].'-'.$vl['kodeKelompok']]['jml_optml'] = $vl['jml_optml'];
	$data[$vl['KDPROGRAM'].'-'.$vl['KDGIAT'].'-'.$vl['KDOUTPUT'].'-'.$vl['kodeKelompok']]['jml_rill'] = $vl['jml_rill_rev'];
	$data[$vl['KDPROGRAM'].'-'.$vl['KDGIAT'].'-'.$vl['KDOUTPUT'].'-'.$vl['kodeKelompok']]['satuan_rill'] = $vl['satuan_rill'];
	$data[$vl['KDPROGRAM'].'-'.$vl['KDGIAT'].'-'.$vl['KDOUTPUT'].'-'.$vl['kodeKelompok']]['ket'] = $vl['ket'];
						
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
			<table style=\"text-align: left; width: 100%;\" border=\"0\"
			 cellpadding=\"2\" cellspacing=\"2\">
			  <tbody>
				<tr>
				  <td style=\"width: 150px; text-align: LEFT;\">$gmbr</td>
				  <td style=\"width: 902px; text-align: center;\">
				  <h3>USULAN KEBUTUHAN PENGGUNA BARANG MILIK DAERAH</h3>
				  <h3>(RENCANA PENGADAAN)</h3>
				  <h3>PENGGUNA BARANG $UPB</h3>
				  <h3>TAHUN $tahun_neraca</h3>
				  </td>
				</tr>
			  </tbody>
			</table>
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
		<td style=\" text-align: center; font-weight: bold; width: \" rowspan=\"2\" >No</td>
		<td style=\" text-align: center; font-weight: bold; width: \" rowspan=\"2\">Nama Pengguna Barang<br/>Program/Kegiatan/Output</td>
		<td style=\" text-align: center; font-weight: bold; width: \" colspan=\"4\" >Usulan Barang Milik Daerah</td>
		<td style=\" text-align: center; font-weight: bold; width: \" colspan=\"2\" >Kebutuhan Maksimum</td>
		<td style=\" text-align: center; font-weight: bold; width: \" colspan=\"4\" >Data Daftar Barang yang Dapat Dioptimalisasi</td>
		<td style=\" text-align: center; font-weight: bold; width: \" colspan=\"2\" >Kebutuhan Riil Barang Milik Daerah</td>
		<td style=\" text-align: center; font-weight: bold; width: \" rowspan=\"2\" >Ket</td>
	</tr>
	<tr>
	   <td style=\" text-align: center; font-weight: bold; width: \">Kode Barang</td>
	   <td style=\" text-align: center; font-weight: bold; width: \">Nama Barang</td>
	   <td style=\" text-align: center; font-weight: bold; width: \">Jumlah</td>
	   <td style=\" text-align: center; font-weight: bold; width: \">Satuan</td>

	   <td style=\" text-align: center; font-weight: bold; width: \">Jumlah</td>
	   <td style=\" text-align: center; font-weight: bold; width: \">Satuan</td>

	   <td style=\" text-align: center; font-weight: bold; width: \">Kode Barang</td>
	   <td style=\" text-align: center; font-weight: bold; width: \">Nama Barang</td>
	   <td style=\" text-align: center; font-weight: bold; width: \">Jumlah</td>
	   <td style=\" text-align: center; font-weight: bold; width: \">Satuan</td>

	   <td style=\" text-align: center; font-weight: bold; width: \">Jumlah</td>
	   <td style=\" text-align: center; font-weight: bold; width: \">Satuan</td>
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
		<td style=\" text-align: center; font-weight: bold; width: \">13 = 7 - 11</td>
		<td style=\" text-align: center; font-weight: bold; width: \">14</td>
		<td style=\" text-align: center; font-weight: bold; width: \">15</td>
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
		<td style=\" text-align: center; font-weight: bold; width: \">&nbsp;</td>
	</tr>";	
	/*
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
		<td style=\" text-align: center; font-weight: bold; width: \">&nbsp;</td>
	</tr>
	*/
//level program
$i_prog = 1;
foreach ($dataProgram as $key => $value) {
		# code...
		$body.="<tr>
				<td style=\" text-align: center; font-weight: bold; width: \">&nbsp;</td>
				<td style=\" text-align: ; font-weight: bold; width: \">$i_prog. Program</td>
				<td style=\" text-align: ; font-weight: bold; width: \" colspan=\"13\">[$value[KDPROGRAM]] $value[NMPROGRAM]</td>
			</tr>";
	//level kegiatan	
	$i_kegt = 1;	
	foreach ($value['DATA'] as $keyKegt => $valueKegt) {
		$body.="<tr>
				<td style=\" text-align: center; font-weight: bold; width: \">&nbsp;</td>
				<td style=\" text-align: ; font-weight: bold; width: \">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$i_kegt. Kegiatan</td>
				<td style=\" text-align: ; font-weight: bold; width: \" colspan=\"13\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;[$valueKegt[KDGIAT]] $valueKegt[NMGIAT]</td>
			</tr>";
		//level output
		$i_out = 1;	
		foreach ($valueKegt['DATA'] as $keyOut => $valueOut) {
			# code...
			$body.="<tr>
				<td style=\" text-align: center; font-weight: bold; width: \">&nbsp;</td>
				<td style=\" text-align: ; font-weight: bold; width: \">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$i_out. Output</td>
				<td style=\" text-align: ; font-weight: bold; width: \" colspan=\"13\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;[$valueOut[KDOUTPUT]] $valueOut[NMOUTPUT]</td>
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
					<td style=\" text-align: center; font-weight: bold; width: \">$valueBrng[jml_usul]</td>
					<td style=\" text-align: center; font-weight: bold; width: \">$valueBrng[satuan_usul]</td>
					<td style=\" text-align: center; font-weight: bold; width: \">$valueBrng[jml_max_rev]</td>
					<td style=\" text-align: center; font-weight: bold; width: \">$valueBrng[satuan_max]</td>
					<td style=\" text-align: ; font-weight: bold; width: \">$valueBrng[kodeKelompok]</td>
					<td style=\" text-align: ; font-weight: bold; width: \">$valueBrng[Uraian]</td>
					<td style=\" text-align: center; font-weight: bold; width: \">$valueBrng[jml_optml]</td>
					<td style=\" text-align: center; font-weight: bold; width: \">$valueBrng[satuan_max]</td>
					<td style=\" text-align: center; font-weight: bold; width: \">$valueBrng[jml_rill]</td>
					<td style=\" text-align: center; font-weight: bold; width: \">$valueBrng[satuan_rill]</td>
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
$html = $head.$body.$foot;
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
$namafile="$path/report/output/Rencana Pengadaan_$waktu.pdf";
$mpdf->Output("$namafile",'F');
$namafile_web="$url_rewrite/report/output/Rencana Pengadaan_$waktu.pdf";
echo "<script>window.location.href='$namafile_web';</script>";
exit;
}
else
{
	$waktu=date("d-m-y_h:i:s");
	$filename ="Rencana_Pengadaan_$waktu.xls";
	header('Content-type: application/ms-excel');
	header('Content-Disposition: attachment; filename='.$filename);
	echo $html; 
}
?>