<?php
ob_start();
include "../config/config.php";

$id=$_SESSION['user_id'];//Nanti diganti
// echo  $id;
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
#This code provided by:
#Andreas Hadiyono (andre.hadiyono@gmail.com)
#Gunadarma University

/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * Easy set variables
 */

/* Array of database columns which should be read and sent back to DataTables. Use a space where
 * you want to insert a non-database field (for example a counter or static image)
 */
//pr($_GET);
$aColumns = array('a.idus','a.kodeSatker','a.no_usul','a.tgl_usul','s.NamaSatker','a.status_usulan','a.status_penetapan','a.status_validasi','a.status_verifikasi','a.status_verifikasi');
//$test = count($aColumns);
  
// echo $aColumns; 
/* Indexed column (used for fast and accurate table cardinality) */
$sIndexColumn = "idus";

/* DB table to use */
$sTable = "usulan_rencana_pengadaaan";
//variabel ajax

$tahun=$_GET['tahun'];
if($_SESSION['ses_uaksesadmin']==1){
    $kodeSatker = $_SESSION['ses_satkerkode'];
    $sWhere =" WHERE YEAR(a.tgl_usul) ='{$tahun}' ";
    $condtn =" WHERE YEAR(tgl_usul) ='{$tahun}' ";
}else{
    $kodeSatker = $_SESSION['ses_satkerkode'];
    $sWhere =" WHERE a.kodeSatker='$kodeSatker' AND YEAR(a.tgl_usul) ='{$tahun}' ";
    $condtn =" WHERE kodeSatker='$kodeSatker' AND YEAR(tgl_usul) ='{$tahun}' ";
}

$addSwhere = ' AND s.Kd_Ruang is null' ;
// echo $tahun;
/* REMOVE THIS LINE (it just includes my SQL connection user/pass) */
//include( $_SERVER['DOCUMENT_ROOT'] . "/datatables/mysql.php" );


/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * If you just want to use the basic configuration for DataTables with PHP server-side, there is
 * no need to edit below this line
 */

/*
 * Local functions
 */
//pr($_GET);
function fatal_error($sErrorMessage = '') {
     header($_SERVER['SERVER_PROTOCOL'] . ' 500 Internal Server Error');
     
     die(mysql_error());
}

/*
/*
 * Paging
 */
$sLimit = "";
if (isset($_GET['iDisplayStart']) && $_GET['iDisplayLength'] != '-1') {
     $sLimit = "LIMIT " . intval($_GET['iDisplayStart']) . ", " .
             intval($_GET['iDisplayLength']);
}


/*
 * Ordering
 */
$sOrder = "";
if (isset($_GET['iSortCol_0'])) {
     $sOrder = "ORDER BY  ";
     for ($i = 0; $i < intval($_GET['iSortingCols']); $i++) {
          if ($_GET['bSortable_' . intval($_GET['iSortCol_' . $i])] == "true") {
               $sOrder .= "" . $aColumns[intval($_GET['iSortCol_' . $i])] . " " .
                       ($_GET['sSortDir_' . $i] === 'asc' ? 'asc' : 'desc') . ", ";
          }
     }

     $sOrder = substr_replace($sOrder, "", -2);
     if ($sOrder == "ORDER BY") {
          $sOrder = "ORDER BY tgl_usul desc, idus desc";
     }
}
//ECHO $sOrder;

/*
 * Filtering
 * NOTE this does not match the built-in DataTables filtering which does it
 * word by word on any field. It's possible to do here, but concerned about efficiency
 * on very large tables, and MySQL's regex functionality is very limited
 */
//$sWhere = "";

//exit;
if (isset($_GET['sSearch']) && $_GET['sSearch'] != "") {
     
	$sWhere .="AND (";
	
	
     // $sWhere.="(";
     for ($i = 0; $i < count($aColumns); $i++) {
          if (isset($_GET['bSearchable_' . $i]) && $_GET['bSearchable_' . $i] == "true") {
               $sWhere .= "" . $aColumns[$i] . " LIKE '%" . mysql_real_escape_string($_GET['sSearch']) . "%' OR ";
          }
     }
     $sWhere = substr_replace($sWhere, "", -3);
     $sWhere .= ')';
}


//echo $sWhere;
/*
 * SQL queries
 * Get data to display
 */
$sQuery = "
		SELECT SQL_CALC_FOUND_ROWS " . str_replace(" , ", " ", implode(", ", $aColumns)) . "
		FROM   $sTable as a
		INNER JOIN satker as s on s.kode = a.kodeSatker 
		$sWhere $addSwhere
		$sOrder
		$sLimit
		";
//echo $sQuery;
$rResult = $DBVAR->query($sQuery) or fatal_error('MySQL Error: ' . mysql_errno());

/* Data set length after filtering */
$sQuery = "
		SELECT FOUND_ROWS()
	";
// echo $sQuery;
$rResultFilterTotal = $DBVAR->query($sQuery) or fatal_error('MySQL Error: ' . mysql_errno());
$aResultFilterTotal = $DBVAR->fetch_array($rResultFilterTotal);
$iFilteredTotal = $aResultFilterTotal[0];

/* Total data set length */
$sQuery = "
		SELECT COUNT(" . $sIndexColumn . ")
		FROM   $sTable $condtn";
	// 	echo $sQuery;
$rResultTotal = $DBVAR->query($sQuery) or fatal_error('MySQL Error: ' . mysql_errno());
$aResultTotal = $DBVAR->fetch_array($rResultTotal);
$iTotal = $aResultTotal[0];


/*
 * Output
 */
$output = array(
    "sEcho" => intval($_GET['sEcho']),
    "iTotalRecords" => $iTotal,
    "iTotalDisplayRecords" => $iFilteredTotal,
    "aaData" => array()
);
$no=$_GET['iDisplayStart']+1;

while ($aRow = $DBVAR->fetch_array($rResult)) {
	//pr($aRow);
    $row 			= array();
	$idus 			= $aRow['idus'];
    $kodeSatker 	= $aRow['kodeSatker'];
    $no_usul 		= $aRow['no_usul'];
    $tgl_usul 		= $aRow['tgl_usul'];
    $temp = explode("-", $tgl_usul);
    $format_tgl = $temp['2'].'/'.$temp['1'].'/'.$temp['0']; 
    $status_usulan  = $aRow['status_usulan'];
    $status_penetapan  = $aRow['status_penetapan'];
    $status_validasi  = $aRow['status_validasi'];
    $status_verifikasi  = $aRow['status_verifikasi'];



	$detail="<a style=\"display:display\"  
		href=\"list_usulan_aset.php?idus={$idus}&satker={$kodeSatker}\"	class=\"btn btn-info btn-small\" id=\"\" value=\"\" >
			<i class=\"fa fa-eye\" align=\"center\"></i>&nbsp;&nbsp;Detail</a>";
	
	$delete="<a style=\"display:display\" 
			href=\"delete_usulan.php?idus={$idus}&satker={$kodeSatker}\" onclick=\"return confirm('Hapus Data?');\"
			class=\"btn btn-danger btn-circle\" id=\"\" value=\"\" title=\"Hapus\">
			
			<i class=\"fa fa-trash simbol\">&nbsp;Hapus</i></a>";
	
	$edit="<a style=\"display:display\"  
			href=\"edit_usulan.php?idus={$idus}&satker={$kodeSatker}\"	class=\"btn btn-warning btn-small\" id=\"\" value=\"\" >
			 
			<i class=\"fa fa-pencil\" align=\"center\"></i>&nbsp;&nbsp;Edit</a>";
	
	$cetak="<a style=\"display:display\"  
			href=\"$url_rewrite/report/template/PERENCANAAN/rencana_usulan_pengadaan_aset.php?idus={$idus}&satker={$kodeSatker}\"	class=\"btn btn-warning btn-small\" id=\"\" value=\"\" >
			 
			<i class=\"fa fa-download\" align=\"center\"></i>&nbsp;&nbsp;Cetak</a>";
	//add tombol proses
	//handler jika list aset usulan kosong		
	$ceck = "SELECT COUNT(idr) as jml from usulan_rencana_pengadaaan_aset 
		 	WHERE idus = '$idus'";
	$Exec = $DBVAR->query($ceck) or fatal_error('MySQL Error: ' . mysql_errno());
	$data = $DBVAR->fetch_array($Exec);	
	if($data['jml'] > 0){
		$proses="<a style=\"display:display\"  
		href=\"proses_usulan.php?idus={$idus}&satker={$kodeSatker}&flag=1\"	class=\"btn btn-success btn-small proses\" id=\"\" value=\"\" >
			<i class=\"fa fa-spinner\" align=\"center\"></i>&nbsp;&nbsp;Proses</a>";
	}else{
		$proses="";
	}

	//handler untuk unproses usulan, bisa dilakukan jika status_penetapan = 0
	if($status_verifikasi == 0){
		$unproses="<a style=\"display:display\"  
		href=\"proses_usulan.php?idus={$idus}&satker={$kodeSatker}&flag=2\"	class=\"btn btn-danger btn-small proses\" id=\"\" value=\"\" >
			<i class=\"fa fa-spinner\" align=\"center\"></i>&nbsp;&nbsp;Batal Proses</a>";
	}else{
		$unproses="";
	}
	  $row[] ="<center>".$no."<center>";
	  $row[] =$no_usul;
	  $row[] ='['.$kodeSatker.'] '.$aRow['NamaSatker'] ;
	  $row[] ="<center>".$tgl_usul."<center>";
      

      if($status_usulan == 1 && $status_verifikasi == 1 && $status_penetapan == 1 && 
      	$status_validasi == 1){

      	$wrd = "Usulan Divalidasi";
		$label ="label-success";
		$row[] = "<center><span class=\"label $label\">$wrd </span></center>";
      	$row[] ="<center>".$detail."&nbsp;".$cetak."<center>";
      }elseif($status_usulan == 1 && $status_verifikasi == 1 && $status_penetapan == 1 && $status_validasi == 0){

      	$wrd = "Usulan Ditetapkan";
		$label ="label-success";
		$row[] = "<center><span class=\"label $label\">$wrd </span></center>";
      	$row[] ="<center>".$detail."&nbsp;".$cetak."<center>";
      }elseif($status_usulan == 1 && $status_verifikasi == 1 && $status_penetapan == 0 && $status_validasi == 0){
      	$wrd = "Usulan Diverifikasi";
		$label ="label-success";
		$row[] = "<center><span class=\"label $label\">$wrd </span></center>";
      	$row[] ="<center>".$unproses."&nbsp;".$detail."&nbsp;".$cetak."<center>";
      }elseif($status_usulan == 1 && $status_verifikasi == 0 && $status_penetapan == 0 && $status_validasi == 0){
      	$wrd = "Pengajuan Usulan";
		$label ="label-success";
		$row[] = "<center><span class=\"label $label\">$wrd </span></center>";
      	$row[] ="<center>".$unproses."&nbsp;".$detail."&nbsp;".$cetak."<center>";
      }elseif($status_usulan == 0 && $status_verifikasi == 0 && $status_penetapan == 0 && $status_validasi == 0){
      	$wrd = "Usulan Belum Diajukan";
		$label ="label-warning";	
		$row[] = "<center><span class=\"label $label\">$wrd </span></center>";
      	$row[] ="<center>".$proses."&nbsp;".$detail."&nbsp;".$edit."<br/><br/>".$delete."<center>";
      }

      
	$no++;
     $output['aaData'][] = $row;
}

echo json_encode($output);

?>

