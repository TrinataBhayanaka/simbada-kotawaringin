<?php
include "../../config/config.php";
require_once "$path/API/retrieve_regrouping.php";

//require_once "$path/function/tanggal/format_tanggal_with_explode.php";
$REGROUPING=new RETRIEVE_REGROUPING();
$menu_id = 64;
            $SessionUser = $SESSION->get_session_user();
            ($SessionUser['ses_uid']!='') ? $Session = $SessionUser : $Session = $SESSION->get_session(array('title'=>'GuestMenu', 'ses_name'=>'menu_without_login')); 
           $data= $USERAUTH->FrontEnd_check_akses_menu($menu_id, $Session);
     //      pr($Session);

$get_data_regrouping= $REGROUPING->getMergeData($_GET['kode']);
// pr($get_data_regrouping);

if (isset($_POST['submit'])){
     session_write_close();
     $oldSatker = $_POST['id'];
     $status = exec("php $path/api_list/merger_helper.php 2 $oldSatker > ../../log/merger-data-{$oldSatker}.txt &");
     echo "<script>window.location.href='merger_preview.php'</script>";
}
?>

<?php
	include"$path/meta.php";
	include"$path/header.php";
	include"$path/menu.php";
	
?>
	<!-- SQL Sementara -->
	<?php

	?>
	<!-- End Sql -->
	<section id="main">
		<ul class="breadcrumb">
			  <li><a href="#"><i class="fa fa-home fa-2x"></i>  Home</a> <span class="divider"><b>&raquo;</b></span></li>
			  <li><a href="#">Pindah Lokasi SKPD</a><span class="divider"><b>&raquo;</b></span></li>
			  <li class="active">Status Pindah Lokasi SKPD</li>
			  <?php SignInOut();?>
			</ul>
			<div class="breadcrumb">
				<div class="title">Status Pindah Lokasi SKPD</div>
				<div class="subtitle">Daftar SKPD</div>
			</div>	

			

		<section class="formLegend">
			
			<p><a href="merger_data.php" class="btn btn-info btn-small"><i class="icon-plus-sign icon-white"></i>&nbsp;&nbsp;Tambah Regrouping</a>
			&nbsp;
			<!-- <a class="btn btn-danger btn-small"><i class="icon-plus-sign icon-white"></i>&nbsp;&nbsp;Kontrak Simral</a>
			&nbsp; --></p>	
			<div id="demo">
			<table cellpadding="0" cellspacing="0" border="0" class="display" id="example">
				<thead>
					<tr>
						<th>No</th>
						<th>Kode Satker Lama</th>
						<th>Nama Satker Lama</th>
						<th>Kode Satker Baru</th>
                     <th>Nama Satker Baru</th>
                     
                     <th>Total Aset</th>

						<th>Action</th>
					</tr>
				</thead>
				<tbody>
					
				<?php
				if($get_data_regrouping){
					$no = 1;
					foreach($get_data_regrouping as $val){
					$text_status=array("0"=>"Belum regrouping","1"=>"Sedang regrouping","2"=>"Telah regrouping")
                                                                           
				?>
					<tr class="gradeA">
						<td><?=$no?></td>
						<td><?=$val['old_kodeSatker']?></td>
						<td><?=$val['old_NamaSatker']?></td>
						<td><?=$val['kodeSatker']?></td>
                        <td><?=$val['NamaSatker']?></td>
                        
                        <td><?=$val['Aset']?></td>
                        
                        <td>
                        	<form method="post" action="">
                        		<input type="hidden" name="id" value="<?=$val['id']?>">
                        		<input type="submit" name="submit" value="Lakukan Regroup" class="btn btn-info">
                        	</form>
						</td>
						
					</tr>
				<?php
					$no++;
					}
				}
				?>
			
				</tbody>
				<tfoot>
					<tr>
						<th colspan="5">&nbsp;</th>
					</tr>
				</tfoot>
			</table>
			</div>
			<div class="spacer"></div>
			    
		</section> 
		    
	</section>
	
<?php
	include"$path/footer.php";
?>