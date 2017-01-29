<?php

error_reporting( 0 );
include "../config/config.php";

$kib = $argv[1];
$log_table="log_$kib";
$table="$kib";

$sql="select log_id,aset_id,kd_riwayat,tglperolehan,tglpembukuan,NilaiPerolehan,TglPerubahan,kodeSatker 
		from $log_table where TglPembukuan=TglPerubahan and Kd_Riwayat=18 and
		aset_id not in(select aset_id from $log_table where Kd_Riwayat=3 and TglPerubahan>='2016-01-01')";
$result=mysql_query($sql) or die(mysql_error());
$i=0;
ob_clean();
while($row=mysql_fetch_array($result)){
	//echo "<pre>";
	//print_r($row);
	$Aset_ID=$row['aset_id'];
	$TglPembukuan=$row['tglpembukuan'];
	$log_id=$row['log_id'];
	$kodesatker=$row['kodesatker'];
	list($TglPembukuan_sblm,$sql)=get_tglPembukuan_sblm($log_id,$Aset_ID,$log_table);
	if($TglPembukuan!=$TglPembukuan_sblm && $TglPembukuan_sblm!="")
	{
		$i++;
		echo"/*cek-$kodesatker-[$TglPembukuan!=$TglPembukuan_sblm] $Aset_ID; */\n";
		echo"/*--query-- $sql */\n";
		echo "update aset set TglPembukuan='$TglPembukuan_sblm' where aset_id='$Aset_ID';\n";
		echo "update $table set TglPembukuan='$TglPembukuan_sblm' where aset_id='$Aset_ID';\n";
		echo "update log_$table set TglPembukuan='$TglPembukuan_sblm' where aset_id='$Aset_ID' and log_id>=$log_id;\n\n";

	}
}
echo "/*--Total Aset $i */";

function get_tglPembukuan_sblm($log_id,$aset_id,$log_table){
	$sql="select TglPembukuan from $log_table where log_id<$log_id and Aset_ID='$aset_id' and kd_riwayat!=18 limit 1;";
	$result=mysql_query($sql) or die(mysql_error());
	while($row=mysql_fetch_array($result)){
		$TglPembukuan=$row['TglPembukuan'];
	/*echo "<pre>";
	print_r($row);
	exit();*/

	}
	return array($TglPembukuan,$sql);

}

?>