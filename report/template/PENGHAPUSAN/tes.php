<?php
//print_r($_POST);
//exit();
ob_start();
require_once('../../../config/config.php');

define("_JPGRAPH_PATH", "$path/function/mpdf/jpgraph/src/"); // must define this before including mpdf.php file
$JpgUseSVGFormat = true;
define('_MPDF_URI', "$url_rewrite/function/mpdf/");  // must be  a relative or absolute URI - not a file system path

include ('../../../function/tanggal/tanggal.php');
include "../../report_engine_daftar.php";
require_once('../../../function/mpdf/mpdf.php');
require_once('../../../function/phpqrcode/qrlib.php');

$id = $_POST['idusulan'];
//$sk = $_POST['noUsul'];

//mendeklarasikan report_engine. FILE utama untuk reporting
$REPORT_DAFTAR = new report_engine_daftar();
// pr($_GET);

//if($_POST['passphrase'] = '0-[p0-[p'){

	if($tglHapus != ''){
		$tanggalCetak = format_tanggal($tglHapus);	
	}else{
		$tglcetak = date("Y-m-d");
		$tanggalCetak = format_tanggal($tglcetak);	
	}
	$gambar = $FILE_GAMBAR_KABUPATEN;

	$TitleSk="PERMOHONAN USULAN";
	$data = $RETRIEVE_REPORT->daftar_barang_berdasarkan_usulan_penghapusan($id);
	$sk = $RETRIEVE_REPORT->usulan($id);
	
	$html=$REPORT_DAFTAR->retrieve_daftar_sk($data, $gambar, $sk['NoUsulan'],$tanggalCetak,$TitleSk);
	
	$REPORT_DAFTAR->show_status_download();
	$mpdf=new mPDF('','','','',15,15,16,16,9,9,'L');
	$mpdf->AddPage('L','','','','',15,15,16,16,9,9);
	//$mpdf->setFooter('{PAGENO}') ;
	$mpdf->progbar_heading = '';
	$mpdf->StartProgressBarOutput(2);
	$mpdf->useGraphs = true;
	$mpdf->list_number_suffix = ')';
	$mpdf->hyphenate = true;
	$mpdf->debug = true;
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

	//$waktu=date("d-m-y_h-i-s");
	//$namafile="$path/report/output/sk_penghapusan_barang-$waktu.pdf";
	$namafile="$path/report/output/sk_pmd_$id.pdf";
	$fileDS ="sk_pmd_".$id."_signed.pdf";

		// how to save PNG codes to server 
	    $tempDir = $path.'/report/output/'; 
	    $codeContents = $url_rewrite.'/report/output/'.$fileDS; 
	    //$Updtpath = '/report/output/'.$fileDS;
	    // we need to generate filename somehow,  
	    // with md5 or with database ID used to obtains $codeContents... 
	    $fileName = $id.'_'.md5($codeContents).'.png'; 
	     
	    $pngAbsoluteFilePath = $tempDir.$fileName; 
	    $urlRelativeFilePath = $path.'/report/output/'.$fileName; 
	     
	    // generating 
	    if (!file_exists($pngAbsoluteFilePath)) { 
	        QRcode::png($codeContents, $pngAbsoluteFilePath, QR_ECLEVEL_L, 3); 
	        //echo 'File generated!'; 
	        //echo '<hr />'; 
	    } else { 
	        //echo 'File already generated! We can use this cached file to speed up site on common codes!'; 
	        //echo '<hr />'; 
	    } 

	$mpdf->SetHTMLFooter('<hr>
						<div class="row">
							<div style="float:left;width:30%">
								<br>
								<img src="'.$urlRelativeFilePath.'" />
							</div>
							<div style="float:left;width:70%">
								Catatan : 
								<ul>
								  <li>UU ITE No. 11 Tahun 2008 Pasal 5 ayat 1 <br/><i>"informasi Elektronik dan/atau hasil cetaknya merupakan alat bukti hukum yang sah"</i></li>
								  <li>Dokumen ini telah ditandatangani <b>secara elektronik</b> menggunakan sertifikat elektronik yang diterbitakn <b>BSre</b></li>
								  <li>Dokumen ini dapat dibuktikan keasliannya dengan memindai QRCODE yang telah tersedia menggunakan aplikasi <b>Very DS</b> yang dapat diunduh melalui Google Playstore.</li>
								</ul>
							</div>
						</div>');

	$mpdf->Output("$namafile",'F');
	chmod($namafile,0755);
	$path_jar = '../../../function/digsign/jsignpdf/JSignPdf.jar';
	        $path_pdf = $namafile;
	        $path_p12 = '../../../function/digsign/cert-dummy/dummy.p12';
	        $path_img = '../../../function/digsign/logo_bsre.png';
	        $passphrase = $_POST['passphrase'];
	        //$passphrase = '0-[p0-[p';
	        $signed_pdf = '../../../report/output/';
	        $tsa_url = "http://tsa-osd.lemsaneg.go.id/";
	        $tanggal_signing = date("d-m-Y h-i-s");
	        //visible signature with TS
	        
	        $command = "java -jar " . $path_jar . " -kst PKCS12 -V --bg-path " . $path_img . " -d " . $signed_pdf . " -pg -1 -llx 120 -lly 150 -urx 280 -ury 0 --bg-scale -1 --l2-text  '' -ksf " . $path_p12 . " -ksp " . $passphrase . " " . $path_pdf . " 2>&1";	
			exec($command, $output);

	$err_status = 1;	
	foreach ($output as $message) {
	    $findme   = 'ERROR';
	    $pos = strpos($message, $findme);

	    if ($pos === false) {
	        $err_status = 0;
	    } else {
	        $err_status = 1;
	        break;
	    }
	}
	if ($err_status == 1) {
	    echo "<script>alert('passphrase tidak sesuai. Mohon masukan kembali')</script>";
	    echo "<script>history.back(-1)</script>";
	    } else {
	    $data = $RETRIEVE_REPORT->TTD($id,$fileDS);
		
	    $namafile_web="$url_rewrite/report/output/$fileDS";
		echo "<script>window.location.href='$namafile_web';</script>";
	}

	exit;
//}

?>
