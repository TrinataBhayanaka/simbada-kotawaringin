<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
#This code provided by:
#Andreas Hadiyono (andre.hadiyono@gmail.com)
#Gunadarma University
include "../config/config.php";
$id=$_GET[id];
$get_data_penyusutan= $PENYUSUTAN->getDataPenyusutan($id);
// pr($get_data_penyusutan);
foreach($get_data_penyusutan as $val){
     $status_running=$val['StatusRunning'];
     $id=$val['id'];
     $kelompok=$val['KelompokAset'];
     $UserNm=$val['UserNm'];
     $Tahun=$val['Tahun'];
     $kodeSatker=$val['kodeSatker'];
 }
 // pr($val);
 // exit;
	              
session_write_close();                    
  if($status_running==0){
          switch ($kelompok) {
               case "Peralatan dan Mesin (B)":
                    $query="update  penyusutan_tahun  set StatusRunning=1 where id=$id";
                    $DBVAR->query($query) or die($DBVAR->error());
                    $status=exec("php penyusutan_tahun_pertama.php B $Tahun $kodeSatker $id >> $path/log/revisi-2006-penyusutan-B-$kodeSatker.txt &");
					header('Location: index_penyusutan_pertama.php');
					break;
			case "Gedung dan Bangunan (C)":
                   $query="update  penyusutan_tahun  set StatusRunning=1 where id=$id";
                   $DBVAR->query($query) or die($DBVAR->error());
                   $status=   exec("php penyusutan_tahun_pertama.php C $Tahun $kodeSatker $id >> $path/log/revisi-2006-penyusutan-C-$kodeSatker.txt &");
                   header('Location: index_penyusutan_pertama.php');
				   break;

              case "Jalan, Irigrasi, dan Jaringan (D)":
                   $query="update  penyusutan_tahun  set StatusRunning=1 where id=$id";
                   $DBVAR->query($query) or die($DBVAR->error());
                   $status=   exec("php penyusutan_tahun_pertama.php D $Tahun $kodeSatker $id >> $path/log/revisi-2006-penyusutan-D-$kodeSatker.txt &");
                   header('Location: index_penyusutan_pertama.php');
				   break;
			}
         // echo $status;
}
 /*else if($status_running==1){
        echo "<script>alert('Data $kelompok sedang disusutkan');"
     . "       window.location.href=\"$url_rewrite/module/penyusutan/index_penyusutan_pertama.php\"</script>";
 }
 else if($status_running==2){
              echo "<script>alert('Data $kelompok telah berhasil disusutkan');"
     . "       window.location.href=\"$url_rewrite/module/penyusutan/index_penyusutan_pertama.php\"</script>";

 }*/
 
