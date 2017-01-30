<?php

error_reporting( 0 );
include "../config/config.php";

$log_table="log_bangunan";
$table="bangunan";

$sql="select log_id,aset_id,TglPembukuan,kodesatker from log_bangunan 
		where Kd_Riwayat=18 and TglPerubahan>='2016-01-01' and kodesatker like '04.14.01%' order by kodesatker";
$result=mysql_query($sql) or die(mysql_error());
$i=0;
ob_clean();
while($row=mysql_fetch_array($result)){
	//echo "<pre>";
	//print_r($row);
	$Aset_ID=$row['aset_id'];
	$TglPembukuan=$row['TglPembukuan'];
	$log_id=$row['log_id'];
	$kodesatker=$row['kodesatker'];
	list($TglPembukuan_sblm,$sql)=get_tglPembukuan_sblm($log_id,$Aset_ID,$log_table);
	if($TglPembukuan!=$TglPembukuan_sblm && $TglPembukuan_sblm!="")
	{
		$i++;
		echo"--cek-$kodesatker-  $Aset_ID \n";
		echo"--query-- $sql\n";
		echo "update aset set TglPembukuan='$TglPembukuan_sblm' where aset_id='$Aset_ID';\n";
		echo "update $table set TglPembukuan='$TglPembukuan_sblm' where aset_id='$Aset_ID';\n\n";

	}
}
echo "Total Aset $i";

function get_tglPembukuan_sblm($log_id,$aset_id,$log_table){
	$sql="select TglPembukuan from $log_table where log_id<$log_id and Aset_ID='$aset_id' limit 1";
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