<?php
include "../../config/config.php";
//insert program 
//pr($_POST);
/*$query	  = "INSERT INTO usulan_rencana_pemeliharaan (idp,idk,idot,kodeSatker,
						 no_usul,tgl_usul,status_usulan) 
			VALUES ('$_POST[program]','$_POST[kegiatan]','$_POST[output]',
					'$_POST[KodeSatker]','".addslashes(html_entity_decode($_POST[no_usul]))."',
					'$_POST[tgl_usul]','0')";*/
//program					
$queryProgram ="SELECT kd_program,program FROM program WHERE idp='{$_POST[program]}'";
$row = mysql_fetch_assoc(mysql_query($queryProgram));
$dataProgram = $row;
$KDPROGRAM = $dataProgram['kd_program'];
$NMPROGRAM = $dataProgram['program'];

//kegiatan
$queryKegiatan ="SELECT kd_kegiatan,kegiatan FROM kegiatan WHERE idk='{$_POST[kegiatan]}'";
$row2 = mysql_fetch_assoc(mysql_query($queryKegiatan));
$dataKegiatan = $row2;
$KDGIAT = $dataKegiatan['kd_kegiatan'];
$NMGIAT = $dataKegiatan['kegiatan'];

//output
$queryKegiatan ="SELECT kd_output,output FROM output WHERE idot='{$_POST[output]}'";
$row3 = mysql_fetch_assoc(mysql_query($queryKegiatan));
$dataOutput = $row3;
$KDOUTPUT = $dataOutput['kd_output'];
$NMOUTPUT = $dataOutput['output'];

/*pr($KDPROGRAM);
pr($NMPROGRAM);

pr($KDKEGIATAN);
pr($NMKEGIATAN);*/

$query	  = "INSERT INTO usulan_rencana_pemeliharaan (idp,idk,idot,kodeSatker,
						 no_usul,tgl_usul,KDPROGRAM,NMPROGRAM,KDGIAT,NMGIAT,KDOUTPUT,NMOUTPUT,tahun) 
			VALUES ('$_POST[program]','$_POST[kegiatan]','$_POST[output]',
					'$_POST[KodeSatker]','".addslashes(html_entity_decode($_POST[no_usul]))."',
					'$_POST[tgl_usul]','{$KDPROGRAM}','{$NMPROGRAM}','{$KDGIAT}','{$NMGIAT}','{$KDOUTPUT}','{$NMOUTPUT}','$_POST[tahun]')";						
//pr($query);
//exit;
$exec =  mysql_query($query);
  	echo "<script>
			alert('Data Berhasil Disimpan');
		</script>";
	
	/*echo "<script>
	window.location = '{$url_rewrite}/module/rencana_pemeliharaan/list_usulan.php?tgl_usul={$_POST['tgl_usul_param']}&satker={$_POST['KodeSatker']}'
	</script>";*/	

	echo "<script>
	window.location = '{$url_rewrite}/module/rencana_pemeliharaan/index.php'
	</script>";	
?>