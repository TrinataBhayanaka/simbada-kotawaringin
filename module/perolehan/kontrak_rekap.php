<?php
include "../../config/config.php";

$menu_id = 1;
$SessionUser = $SESSION->get_session_user();
($SessionUser['ses_uid']!='') ? $Session = $SessionUser : $Session = $SESSION->get_session(array('title'=>'GuestMenu', 'ses_name'=>'menu_without_login'));
$USERAUTH->FrontEnd_check_akses_menu($menu_id, $Session);

$idKontrak = $_GET['id'];
$sql = mysql_query("SELECT * FROM kontrak WHERE id='{$idKontrak}'");
while ($dataKontrak = mysql_fetch_assoc($sql)){
    $kontrak = $dataKontrak;
}

$RKsql = mysql_query("SELECT Aset_ID, Satuan, kodeLokasi, kodeKelompok, noRegister, NilaiPerolehan FROM aset WHERE noKontrak = '{$kontrak['noKontrak']}' AND ((StatusValidasi != 9 and StatusValidasi != 13) OR StatusValidasi IS NULL) AND ((Status_Validasi_Barang != 9 and Status_Validasi_Barang != 13) OR Status_Validasi_Barang IS NULL)");
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

$sql = mysql_query("SELECT SUM(nilai) as total FROM sp2d WHERE idKontrak='{$idKontrak}' AND type = '2'");
while ($dataSP2D = mysql_fetch_assoc($sql)){
    $sumsp2d = $dataSP2D;
}

$sql = mysql_query("SELECT SUM(nilai) as total FROM sp2d WHERE idKontrak='{$idKontrak}' AND type = '1'");
while ($datatermin = mysql_fetch_assoc($sql)){
    $sumtermin = $datatermin;
}

//sum total
$sqlsum = mysql_query("SELECT SUM(NilaiPerolehan) as total FROM aset WHERE noKontrak = '{$kontrak['noKontrak']}' AND ((StatusValidasi != 9 and StatusValidasi != 13) OR StatusValidasi IS NULL) AND ((Status_Validasi_Barang != 9 and Status_Validasi_Barang != 13) OR Status_Validasi_Barang IS NULL)");
while ($sum = mysql_fetch_assoc($sqlsum)){
    $sumTotal = $sum;
}
//pr($rKontrak);exit();
ob_start();
header("Content-Type: application/vnd.ms-excel");

?>
<HTML>
<body>
    <span style="font-size: 25px;">Rekap Data Nilai Kontrak</span><br>
    <span><strong>No Kontrak</strong> : <?=$kontrak['noKontrak']?></span><br>
    <span><strong>Nilai Kontrak</strong> : <?=number_format($kontrak['nilai'], 2)?></span>
    <br>
    <table border="1">
        <thead>
            <tr>
                <th>No</th>
                <th>Kode Barang</th>
                <th>Nama Barang</th>
                <th>No. Register</th>
                <th>Harga Satuan</th>
                <th>Penunjang</th>
                <th>Total Perolehan</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if($rKontrak){
                $i = 1;
                $j = 0;
                $bopsisa = $sumsp2d['total'];
                $totSatuan = 0;
                $totPenunjang = 0;
                $totPerolehan = 0;
                foreach ($rKontrak as $key => $value) {
                    $j++;
                    if(count($rKontrak) == $j){
                        $bop = $bopsisa;
                    } else{
                        $bopsisa = $bopsisa - ceil($value['NilaiPerolehan']/$sumTotal['total']*$sumsp2d['total']);
                        $bop = ceil($value['NilaiPerolehan']/$sumTotal['total']*$sumsp2d['total']);
                    }
                    ?>
                    <tr class="">
                        <td><?=$i?></td>
                        <td><?=$value['kodeKelompok']?></td>
                        <td><?=$value['uraian']?></td>
                        <td><?=$value['noRegister']?></td>
                        <td><?=number_format($value['Satuan']-$bop,2)?></td>
                        <td><?=number_format($bop,2)?></td>
                        <td><?=number_format($value['satuan'],2)?></td>
                    </tr>
                    <?php
                    $totSatuan += $value['Satuan'];
                    $totPenunjang += $bop;
                    $totPerolehan += $value['NilaiPerolehan']+$bop;
                    $i++;
                }
            }
            ?>
        </tbody>
        <tfoot>
        <tr>
            <th colspan="4">Total Harga Satuan</th>
            <th><?=number_format($totSatuan, 2)?></th>
            <th>&nbsp;</th>
            <th>&nbsp;</th>
        </tr>
        <tr>
            <th colspan="4">Total Penunjang</th>
            <th>&nbsp;</th>
            <th><?=number_format($totPenunjang, 2)?></th>
            <th>&nbsp;</th>
        </tr>
        <tr>
            <th colspan="4">Total Perolehan</th>
            <th>&nbsp;</th>
            <th>&nbsp;</th>
            <th><?=number_format($totPerolehan, 2)?></th>
        </tr>
        </tfoot>
    </table>
</body>
</HTML>

<?php
header("Content-disposition: attachment; filename=spreadsheet.xls");


