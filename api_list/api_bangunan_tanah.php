<?php
ob_start();
include "../config/config.php";

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
$aColumns = array('tanah_id', 'aset_id','TglPerolehan','NilaiPerolehan','kodeSatker',"kodeKelompok","noRegister","Tahun","LuasTotal");

/* Indexed column (used for fast and accurate table cardinality) */
$sIndexColumn = "tanah_id";

/* DB table to use */
$sTable = "tanah";
$sWhere=" Where StatusValidasi=1 and Status_Validasi_Barang=1 and StatusTampil=1";
$kodeSatker=$_GET['kodeSatker'];




/*
 * Paging
 */
$sLimit = "";
if (isset($_GET['iDisplayStart']) && $_GET['iDisplayLength'] != '-1') {
     $sLimit = "LIMIT " . intval($_GET['iDisplayStart']) . ", " .
             intval($_GET['iDisplayLength']);
}

if($kodeSatker=="")
    $sLimit=" limit 2";
else{
    $sWhere=" $sWhere and kodeSatker like '$kodeSatker%'";
}

/*
 * Ordering
 */
$sOrder = "";
if (isset($_GET['iSortCol_0'])) {
     $sOrder = "ORDER BY  ";
     for ($i = 0; $i < intval($_GET['iSortingCols']); $i++) {
          if ($_GET['bSortable_' . intval($_GET['iSortCol_' . $i])] == "true") {
               $sOrder .= "`" . $aColumns[intval($_GET['iSortCol_' . $i])] . "` " .
                       ($_GET['sSortDir_' . $i] === 'asc' ? 'asc' : 'desc') . ", ";
          }
     }

     $sOrder = substr_replace($sOrder, "", -2);
     if ($sOrder == "ORDER BY") {
          $sOrder = "";
     }
}


/*
 * Filtering
 * NOTE this does not match the built-in DataTables filtering which does it
 * word by word on any field. It's possible to do here, but concerned about efficiency
 * on very large tables, and MySQL's regex functionality is very limited
 */

//$sWhere = "";
if (isset($_GET['sSearch']) && $_GET['sSearch'] != "") {
     $sWhere = "WHERE (";
     for ($i = 0; $i < count($aColumns); $i++) {
          if (isset($_GET['bSearchable_' . $i]) && $_GET['bSearchable_' . $i] == "true") {
               $sWhere .= "`" . $aColumns[$i] . "` LIKE '%" . mysql_real_escape_string($_GET['sSearch']) . "%' OR ";
          }
     }
     $sWhere = substr_replace($sWhere, "", -3);
     $sWhere .= ')';
}

/* Individual column filtering */
for ($i = 0; $i < count($aColumns); $i++) {
     if (isset($_GET['bSearchable_' . $i]) && $_GET['bSearchable_' . $i] == "true" && $_GET['sSearch_' . $i] != '') {
          if ($sWhere == "") {
               $sWhere = "WHERE ";
          } else {
               $sWhere .= " AND ";
          }
          $sWhere .= "`" . $aColumns[$i] . "` LIKE '%" . mysql_real_escape_string($_GET['sSearch_' . $i]) . "%' ";
     }
}


/*
 * SQL queries
 * Get data to display
 */
$sQuery = "
		SELECT SQL_CALC_FOUND_ROWS `" . str_replace(" , ", " ", implode("`, `", $aColumns)) . "`
		FROM   $sTable
		$sWhere
		$sOrder
		$sLimit
		";

$rResult = $DBVAR->_fetch_array($sQuery, 1);

/* Data set length after filtering */
$sQuery = "
		SELECT FOUND_ROWS()
	";
$rResultFilterTotal = $DBVAR->query($sQuery);
$aResultFilterTotal =$DBVAR->fetch_array($rResultFilterTotal);
$iFilteredTotal = $aResultFilterTotal[0];

/* Total data set length */
$sQuery = "
		SELECT COUNT(`" . $sIndexColumn . "`)
		FROM   $sTable
	";
$rResultTotal = $DBVAR->query($sQuery);
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

foreach ($rResult as $key => $aRow) {
     $row = array();
     $tanah_id=$aRow['tanah_id'];
     

      $noRegister=$aRow['noRegister'];
      $kodeKelompok=$aRow['kodeKelompok'];
      $tmp_uraian=$DBVAR->query("select Uraian from kelompok where Kode='$kodeKelompok' limit 1");
      $text_uraian=$DBVAR->fetch_array($tmp_uraian);
     // pr($text_uraian);
      $Uraian=$text_uraian['Uraian'];
      $satker=$aRow['kodeSatker'];
      $tmp_uraian=$DBVAR->query("select NamaSatker from satker where kode='$satker' limit 1");
      $text_uraian=$DBVAR->fetch_array($tmp_uraian);
      $uraian_satker=$text_uraian[NamaSatker];

      $TglPerolehan=$aRow['TglPerolehan'];
      $NilaiPerolehan=$aRow['NilaiPerolehan'];
      $Tahun=$aRow['Tahun'];
      $LuasTotal=$aRow['LuasTotal'];
      $pilihan="<input type=\"button\" id=\"checkbox\"  "
            . " onclick=\"set_tanah($tanah_id)\""
            . " id=\"tanah_$tanah_id\""
             . "name=\"tanah_id$tanah_id\" value=\"ok\" > 
              <input type=\"hidden\" id='nilai_tanah_id$tanah_id' name='nilai_tanah_id' value=\"{$aRow['tanah_id']}|$kodeKelompok|$Uraian|$noRegister|$LuasTotal|$Tahun\" >
             ";
      
      $row[]=$pilihan;
      $row[]=$noRegister;
      $row[]="$kodeKelompok <br/> $Uraian";
      $row[]="$satker / $uraian_satker ";
      $row[]=$TglPerolehan;
      $row[]=number_format($NilaiPerolehan);
      $row[]=$LuasTotal;
      
             
						
     $output['aaData'][] = $row;
}

echo json_encode($output);

?>

