<?php
error_reporting( 0 );
include "../config/config.php";
/*
$query_cek="select aset_id from aset where
 TipeAset in ('B','C','D') and Tahun<'2007' and
 (AkumulasiPenyusutan!=0 or AkumulasiPenyusutan is not null)
 and
 NilaiPerolehan>AkumulasiPenyusutan ";

echo "<br/><br/>Data Aset_ID pada tabel aset:<br/><br/>";
$ExeQuery = $DBVAR->query($query_cek) or die($DBVAR->error());
$i=0;
while($Data = $DBVAR->fetch_array($ExeQuery)){
		$Aset_ID = $Data['aset_id'];
		if($i>0)
			echo ",$Aset_ID";
		else echo "$Aset_ID";
		$i++;
	}

echo "Jumlah data=$i<br/><br/>Data Aset_ID pada tabel mesin:<br/><br/>";
$query_cek_mesin="select aset_id from mesin where Tahun<'2007' and
 (AkumulasiPenyusutan!=0 or AkumulasiPenyusutan is not null)
 and NilaiPerolehan>AkumulasiPenyusutan";
$ExeQuery = $DBVAR->query($query_cek_mesin) or die($DBVAR->error());
$i=0;
while($Data = $DBVAR->fetch_array($ExeQuery)){
		$Aset_ID = $Data['aset_id'];
		if($i>0)
			echo ",$Aset_ID";
		else echo "$Aset_ID";
		$i++;
	}

echo "Jumlah data=$i<br/><br/>Data Aset_ID pada tabel bangunan:<br/><br/>";
$query_cek_bangunan="select aset_id from bangunan where Tahun<'2007' and
 (AkumulasiPenyusutan!=0 or AkumulasiPenyusutan is not null)
 and NilaiPerolehan>AkumulasiPenyusutan";
$ExeQuery = $DBVAR->query($query_cek_bangunan) or die($DBVAR->error());
$i=0;
while($Data = $DBVAR->fetch_array($ExeQuery)){
		$Aset_ID = $Data['aset_id'];
		if($i>0)
			echo ",$Aset_ID";
		else echo "$Aset_ID";
		$i++;
	}


echo "Jumlah data=$i<br/><br/>Data Aset_ID pada tabel jaringan:<br/><br/>";
$query_cek_jaringan="select aset_id from jaringan where Tahun<'2007' and
 (AkumulasiPenyusutan!=0 or AkumulasiPenyusutan is not null)
 and NilaiPerolehan>AkumulasiPenyusutan";
$ExeQuery = $DBVAR->query($query_cek_jaringan) or die($DBVAR->error());
$i=0;
while($Data = $DBVAR->fetch_array($ExeQuery)){
		$Aset_ID = $Data['aset_id'];
		if($i>0)
			echo ",$Aset_ID";
		else echo "$Aset_ID";
		$i++;
	}
echo "Jumlah data=$i";*/


$query_cek_mesin="select aset_id from mesin where Tahun<'2007' and
 (AkumulasiPenyusutan!=0 or AkumulasiPenyusutan is not null)
 and NilaiPerolehan>AkumulasiPenyusutan";
$query_delete_penyusutan_mesin="delete from log_mesin where	
		aset_id in ($query_cek_mesin) and
		kd_riwayat in (49,50,51,52) ";
$update_log="update log_mesin set NilaiBuku=0,PenyusutanPertahun=0,
 AkumulasiPenyusutan=0,MasaManfaat=0,UmurEkonomis=0,
 TahunPenyusutan=0,NilaiBuku_Awal=0,
 PenyusutanPerTahun_Awal=0,AkumulasiPenyusutan_Awal=0
 where aset_id in ($query_cek_mesin)";

//echo "$query_cek_mesin<br/><br/>";
echo "$query_delete_penyusutan_mesin<br/><br/>";
echo "$update_log<br/><br/>";


$query_cek_bangunan="select aset_id from bangunan where Tahun<'2007' and
 (AkumulasiPenyusutan!=0 or AkumulasiPenyusutan is not null)
 and NilaiPerolehan>AkumulasiPenyusutan";
$query_delete_penyusutan_bangunan="delete from log_bangunan where
		aset_id in ($query_cek_bangunan) and
		kd_riwayat in (49,50,51,52) ";
$update_log="update log_bangunan set NilaiBuku=0,PenyusutanPertahun=0,
 AkumulasiPenyusutan=0,MasaManfaat=0,UmurEkonomis=0,
 TahunPenyusutan=0,NilaiBuku_Awal=0,
 PenyusutanPerTahun_Awal=0,AkumulasiPenyusutan_Awal=0
 where aset_id in ($query_cek_bangunan)";

//echo "$query_cek_bangunan<br/><br/>";
echo "$query_delete_penyusutan_bangunan<br/><br/>";
echo "$update_log<br/><br/>";



$query_cek_jaringan="select aset_id from jaringan where Tahun<'2007' and
 (AkumulasiPenyusutan!=0 or AkumulasiPenyusutan is not null)
 and NilaiPerolehan>AkumulasiPenyusutan";
$query_delete_penyusutan_jaringan="delete from log_jaringan where
		aset_id in ($query_cek_jaringan) and
		kd_riwayat in (49,50,51,52) ";
$update_log="update log_jaringan set NilaiBuku=0,PenyusutanPertahun=0,
 AkumulasiPenyusutan=0,MasaManfaat=0,UmurEkonomis=0,
 TahunPenyusutan=0,NilaiBuku_Awal=0,
 PenyusutanPerTahun_Awal=0,AkumulasiPenyusutan_Awal=0
 where aset_id in ($query_cek_jaringan)";
//echo "$query_cek_jaringan<br/><br/>";
echo "$query_delete_penyusutan_jaringan<br/><br/>";
echo "$update_log<br/><br/>";



//reset akumulasi mahasiswa

$query_reset="update aset set NilaiBuku=0,PenyusutanPertaun=0,
 AkumulasiPenyusutan=0,MasaManfaat=0,UmurEkonomis=0,
 TahunPenyusutan=0 where
 TipeAset in ('B','C','D')  and Tahun<'2007' and
 (AkumulasiPenyusutan!=0 or AkumulasiPenyusutan is not null)
 and NilaiPerolehan>AkumulasiPenyusutan";
echo "$query_reset<br/><br/>";


$query_reset="update mesin set NilaiBuku=0,PenyusutanPertahun=0,
 AkumulasiPenyusutan=0,MasaManfaat=0,UmurEkonomis=0,
 TahunPenyusutan=0 where
 Tahun<'2007' and
 (AkumulasiPenyusutan!=0 or AkumulasiPenyusutan is not null)
 and NilaiPerolehan>AkumulasiPenyusutan";
echo "$query_reset<br/><br/>";


$query_reset="update bangunan set NilaiBuku=0,PenyusutanPertahun=0,
 AkumulasiPenyusutan=0,MasaManfaat=0,UmurEkonomis=0,
 TahunPenyusutan=0 where
 Tahun<'2007' and
 (AkumulasiPenyusutan!=0 or AkumulasiPenyusutan is not null)
 and NilaiPerolehan>AkumulasiPenyusutan";
echo "$query_reset<br/><br/>";

$query_reset="update jaringan set NilaiBuku=0,PenyusutanPertahun=0,
 AkumulasiPenyusutan=0,MasaManfaat=0,UmurEkonomis=0,
 TahunPenyusutan=0 where
 Tahun<'2007' and
 (AkumulasiPenyusutan!=0 or AkumulasiPenyusutan is not null)
 and NilaiPerolehan>AkumulasiPenyusutan";
echo "$query_reset<br/><br/>";
?>
