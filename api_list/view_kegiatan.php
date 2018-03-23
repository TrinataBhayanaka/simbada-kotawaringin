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

$aColumns = array('k.idk','k.kd_kegiatan','k.kegiatan','p.kd_program','p.program');
//$test = count($aColumns);
  
// echo $aColumns; 
/* Indexed column (used for fast and accurate table cardinality) */
$sIndexColumn = "k.idk";

/* DB table to use */
$sTable = "kegiatan as k";
$joinTable = "program as p";
//variabel ajax

$tahun=$_GET['tahun'];
$satker=$_GET['satker'];

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
          //$sOrder = "ORDER BY p.kd_program,k.kd_kegiatan";
          $sOrder = "ORDER BY k.idk";
     }
}
// ECHO $sOrder;

/*
 * Filtering
 * NOTE this does not match the built-in DataTables filtering which does it
 * word by word on any field. It's possible to do here, but concerned about efficiency
 * on very large tables, and MySQL's regex functionality is very limited
 */
$sWhere = "";

if ($satker != '' AND $tahun != ''){
	$sWhere=" WHERE k.tahun='$tahun' AND k.kodeSatker='$satker' ";
}else{
	$sWhere="";

}
//pr($sWhere);
//exit;
if (isset($_GET['sSearch']) && $_GET['sSearch'] != "") {
     //$sWhere = "WHERE (";
	if ($satker != '' AND $tahun != ''){
		$sWhere=" WHERE k.tahun='$tahun' AND k.kodeSatker='$satker'  AND (";
	}else{
		$sWhere.="(";
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

//pr($sWhere);
//echo $sWhere;
/*
 * SQL queries
 * Get data to display
 */
$sQuery = "
		SELECT SQL_CALC_FOUND_ROWS " . str_replace(" , ", " ", implode(", ", $aColumns)) . "
		FROM   $sTable
		INNER JOIN $joinTable ON p.idp = k.idp
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
$sQuery = "
		SELECT COUNT(" . $sIndexColumn . ")
		FROM   $sTable WHERE k.tahun='$tahun' AND k.kodeSatker='$satker'";
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
	$idk 			= $aRow['idk'];
	$kd_kegiatan 	= $aRow['kd_kegiatan'];
    $kegiatan 		= $aRow['kegiatan'];
    //$tahun 			= $aRow['tahun'];
    //$kodeSatker 	= $aRow['kodeSatker'];
	
	$kd_program 	= $aRow['kd_program'];
    $program 		= $aRow['program'];
    
	$delete="<a style=\"display:display\" 
			href=\"delete_kegiatan.php?idk={$idk}&tahun={$tahun}&satker={$satker}\" onclick=\"return confirm('Hapus Data?');\"
			class=\"btn btn-danger btn-circle\" id=\"\" value=\"\" title=\"Hapus\">
			
			<i class=\"fa fa-trash simbol\">&nbsp;Hapus</i></a>";
	
	$edit="<a style=\"display:display\"  
			href=\"edit_kegiatan.php?idk={$idk}&tahun={$tahun}&satker={$satker}\"class=\"btn btn-warning btn-small\" id=\"\" value=\"\" >
			 
			<i class=\"fa fa-pencil\" align=\"center\"></i>&nbsp;&nbsp;Edit</a>";
	  
	  $row[] ="<center>".$no."<center>";
	  $row[] ="<center>".$kd_program."<center>";
      $row[] =$program;
      $row[] ="<center>".$kd_kegiatan."<center>";
      $row[] =$kegiatan;
	  $row[] ="<center>".$edit."<br/><br/>".$delete."<center>";
      
	$no++;
     $output['aaData'][] = $row;
}

echo json_encode($output);

?>

