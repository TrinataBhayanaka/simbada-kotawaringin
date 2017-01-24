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

$aColumns = array('idr','idus','kodeKelompok','jml_usul','jml_optml','status_penetapan');
//$test = count($aColumns);
  
// echo $aColumns; 
/* Indexed column (used for fast and accurate table cardinality) */
$sIndexColumn = "idr";

/* DB table to use */
$sTable = "usulan_rencana_pemeliharaan_aset";
//variabel ajax
//$tgl_usul=$_GET['tgl_usul'];
$satker=$_GET['satker'];

$idus=$_GET['idus'];

$param_tgl_usul = $_GET['tgl_usul'];
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
          $sOrder = "ORDER BY idr desc";
     }
}
//ECHO $sOrder;

/*
 * Filtering
 * NOTE this does not match the built-in DataTables filtering which does it
 * word by word on any field. It's possible to do here, but concerned about efficiency
 * on very large tables, and MySQL's regex functionality is very limited
 */
$sWhere = "";
if($idus != ''){
	$sWhere=" WHERE idus='{$idus}'";
}else{
	$sWhere="";
}
//pr($sWhere);
//exit;
if (isset($_GET['sSearch']) && $_GET['sSearch'] != "") {
     //$sWhere = "WHERE (";
	if($idus != ''){
		$sWhere .=" WHERE idus='{$idus}' AND (";
	}else{
		$sWhere .="(";
	}
	
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
		FROM   $sTable 
		$sWhere
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
if($idus != ''){
	$condtn =" WHERE idus='{$idus}' ";
}else{
	$condtn="";
}

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
    
	   $row 			= array();
	   $idr 			= $aRow['idr'];
	   $idus 			= $aRow['idus'];
    $kodeKelompok 	= $aRow['kodeKelompok'];
    $jml_usul 		= $aRow['jml_usul'];
    $jml_max 		= $aRow['jml_max'];
    $jml_optml 		 = $aRow['jml_optml'];
    $jml_rill 		  = $aRow['jml_rill'];
   	$status_penetapan = $aRow['status_penetapan'];
   	$ketKodeKelompok = mysql_query("select Uraian from kelompok where Kode = '{$kodeKelompok}'");
   	$ket = mysql_fetch_assoc($ketKodeKelompok);

	$delete="<a style=\"display:display\" 
			href=\"delete_usulan_aset.php?idr={$idr}&idus={$idus}&tgl_usul={$param_tgl_usul}&satker={$satker}\" onclick=\"return confirm('Hapus Data?');\"
			class=\"btn btn-danger btn-circle\" id=\"\" value=\"\" title=\"Hapus\">
			
			<i class=\"fa fa-trash simbol\">&nbsp;Hapus</i></a>";
	
	$edit="<a style=\"display:display\"  
			href=\"edit_usulan_aset.php?idr={$idr}&idus={$idus}&tgl_usul={$param_tgl_usul}&satker={$satker}\"	class=\"btn btn-warning btn-small\" id=\"\" value=\"\" >
			 
			<i class=\"fa fa-pencil\" align=\"center\"></i>&nbsp;&nbsp;Edit</a>";

	  $row[] ="<center>".$no."<center>";
	  $row[] ="[".$kodeKelompok."]"."<br/>".$ket['Uraian'];
      $row[] ="<center>".$jml_usul."<center>";
      $row[] ="<center>".$jml_optml."<center>";

      $ceck = "SELECT status_usulan from usulan_rencana_pemeliharaan
               WHERE idus = '$idus'";
      $Exec = $DBVAR->query($ceck) or fatal_error('MySQL Error: ' . mysql_errno());
      $data = $DBVAR->fetch_array($Exec); 
      if($data['status_usulan'] == 0){
        $row[] ="<center>".$edit."<br/><br/>".$delete."<center>";
      }else{
        $row[]="";
      }

	$no++;
     $output['aaData'][] = $row;
}

echo json_encode($output);

?>

