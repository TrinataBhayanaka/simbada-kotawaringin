<?php
include "../../config/config.php";
include "../../import/excel_reader.php";
//pr($_POST);
//pr($_FILES);
$tahun = $_POST['tahun'];
$DBVAR->begin();
$sqlProgram = "DELETE FROM `program` WHERE tahun = '{$tahun}'";
$ExeP = $DBVAR->query($sqlProgram);
if(!$ExeP){
	$DBVAR->rollback();
	echo "<script>
		alert('Hapus Data Program gagal masuk #001. Silahkan coba lagi');
	</script>";	
	redirect($url_rewrite.'/module/program/import.php');
	exit();
}	

$sqlKegiatan = "DELETE FROM `kegiatan` WHERE tahun = '{$tahun}'";
$ExeK = $DBVAR->query($sqlKegiatan);
if(!$ExeK){
	$DBVAR->rollback();
	echo "<script>
		alert('Hapus Data kegiatan gagal masuk #002. Silahkan coba lagi');
	</script>";	
	redirect($url_rewrite.'/module/program/import.php');
	exit();
}

$data = new Spreadsheet_Excel_Reader($_FILES['myFile']['tmp_name']);
//pr($data);        
// membaca jumlah baris dari data excel
$baris = $data->rowcount($sheet_index=0);
$count = 0;
for ($i=2;$i<=$baris;$i++){

	$listArr[$count]['KDPR'] = $data->val($i,1);
	$listArr[$count]['KDGIAT'] = $data->val($i,2); 
	$listArr[$count]['NMSKPD'] = $data->val($i,3); 
	$listArr[$count]['KDSKPD'] = $data->val($i,4); 
	$listArr[$count]['NMPR'] = $data->val($i,5); 
	$listArr[$count]['NMGIAT'] = $data->val($i,6); 

$count++;
}

sort($listArr);

//$KDGIAT
foreach ($listArr as $key => $value) {
	$KDGIAT[$value['KDSKPD'].'-'.$value['KDPR'].'-'.$value['KDGIAT']]['ID'] = $value['KDSKPD'].'-'.$value['KDPR'].'-'.$value['KDGIAT']; 
	$KDGIAT[$value['KDSKPD'].'-'.$value['KDPR'].'-'.$value['KDGIAT']]['KDSKPD'] = $value['KDSKPD'];
	$KDGIAT[$value['KDSKPD'].'-'.$value['KDPR'].'-'.$value['KDGIAT']]['NMSKPD'] = $value['NMSKPD'];
	$KDGIAT[$value['KDSKPD'].'-'.$value['KDPR'].'-'.$value['KDGIAT']]['KDPR'] = $value['KDPR'];
	$KDGIAT[$value['KDSKPD'].'-'.$value['KDPR'].'-'.$value['KDGIAT']]['NMPR'] = $value['NMPR'];
	$KDGIAT[$value['KDSKPD'].'-'.$value['KDPR'].'-'.$value['KDGIAT']]['KDGIAT'] = $value['KDGIAT'];
	$KDGIAT[$value['KDSKPD'].'-'.$value['KDPR'].'-'.$value['KDGIAT']]['NMGIAT'] = $value['NMGIAT'];
}

//$KDPR
foreach ($KDGIAT as $key2 => $value2) {
	//pr($value2);

	$KDPR[$value2['KDSKPD'].'-'.$value2['KDPR']]['ID'] = $value2['KDSKPD'].'-'.$value2['KDPR']; 
	$KDPR[$value2['KDSKPD'].'-'.$value2['KDPR']]['KDSKPD'] = $value2['KDSKPD'];
	$KDPR[$value2['KDSKPD'].'-'.$value2['KDPR']]['KDPR'] = $value2['KDPR'];
	$KDPR[$value2['KDSKPD'].'-'.$value2['KDPR']]['NMPR'] = $value2['NMPR'];
	$KDPR[$value2['KDSKPD'].'-'.$value2['KDPR']]['DATA'][] = $value2;
}
//$KDSKPD
foreach ($KDPR as $key3 => $value3) {
	//pr($value2);

	$KDSKPD[$value3['KDSKPD']]['ID'] = $value3['KDSKPD']; 
	$KDSKPD[$value3['KDSKPD']]['KDSKPD'] = $value3['KDSKPD'];
	$KDSKPD[$value3['KDSKPD']]['DATA'][] = $value3;
}


foreach ($KDSKPD as $key4 => $value4) {
	# code...
	$KDSKPD = $value4['KDSKPD'];
	$NMSKPD = $value4['NMSKPD'];
	/*echo "KDSKPD = ".$KDSKPD;
	echo "<br/>";*/

	//$idp = 1;
	foreach ($value4['DATA'] as $key5 => $value5) {
		# code...
		$KDPR = $value5['KDPR'];
		$NMPR = $value5['NMPR'];
		/*echo "KDPR = ".$KDPR;
		echo "<br/>";
		echo "NMPR = ".$NMPR;
		echo "<br/>";*/
		$sql = "INSERT INTO `program`(`tahun`, `kodeSatker`, `kd_program`, `program`) 
				VALUES 
				('{$tahun}','{$KDSKPD}','{$KDPR}','{$NMPR}')";	
		//pr($sql);
		$Exe1 = $DBVAR->query($sql);
		if(!$Exe1){
			$DBVAR->rollback();
			echo "<script>
				alert('Data Program gagal masuk #003. Silahkan coba lagi');
			</script>";	
			redirect($url_rewrite.'/module/program/import.php');
			exit();
		}
		$idp = $DBVAR->insert_id();
		foreach ($value5['DATA'] as $key6 => $value6) {
			# code...
			$KDGIAT = $KDPR.'.'.$value6['KDGIAT'];
			$NMGIAT = $value6['NMGIAT'];
			/*echo "KDGIAT = ".$KDGIAT;
			echo "<br/>";
			echo "NMGIAT = ".$NMGIAT;
			echo "<br/>";*/
			$sql2 = "INSERT INTO `kegiatan`(`idp`, `tahun`, `kodeSatker`, `kd_kegiatan`, `kegiatan`) 
					VALUES ('{$idp}','{$tahun}','{$KDSKPD}','{$KDGIAT}','{$NMGIAT}')";
			$Exe2 = $DBVAR->query($sql2);
			if(!$Exe2){
				$DBVAR->rollback();
				echo "<script>
					alert('Data kegiatan gagal masuk #004. Silahkan coba lagi');
				</script>";	
				redirect($url_rewrite.'/module/program/import.php');
				exit();
			}	
		}
		//$idp++;
	}
}
$DBVAR->commit();
 	echo "<script>
			alert('Data Berhasil Disimpan');
		</script>";
	
	redirect($url_rewrite.'/module/program/import.php');	
?>	