<?php
include "../../config/config.php";

	$PENGHAPUSAN = new RETRIEVE_PENGHAPUSAN;
$menu_id = 10;
            $SessionUser = $SESSION->get_session_user();
            ($SessionUser['ses_uid']!='') ? $Session = $SessionUser : $Session = $SESSION->get_session(array('title'=>'GuestMenu', 'ses_name'=>'menu_without_login')); 
            $USERAUTH->FrontEnd_check_akses_menu($menu_id, $Session);

$get_data_filter = $RETRIEVE->retrieve_kontrak();
// //pr($get_data_filter);
?>

<?php
	include"$path/meta.php";
	include"$path/header.php";
	include"$path/menu.php";
	
?>
	<!-- SQL Sementara -->
	<?php
		// $data = $PENGHAPUSAN->retrieve_daftar_penetapan_penghapusan($_POST);
		$data = $PENGHAPUSAN->retrieve_daftar_penetapan_penghapusan_validasi_pms($_POST);
		 $sql = mysql_query("SELECT * FROM kontrak ORDER BY id ");
        while ($dataKontrak = mysql_fetch_assoc($sql)){
                $kontrak[] = $dataKontrak;
            }
	?>
	<!-- End Sql -->

	<script language="Javascript" type="text/javascript">  
			function enable(){  
			var tes=document.getElementsByTagName('*');
			var button=document.getElementById('submit');
			var boxeschecked=0;
			for(k=0;k<tes.length;k++)
			{
				if(tes[k].className=='checkbox')
					{
						//
						tes[k].checked == true  ? boxeschecked++: null;
					}
			}
			//alert(boxeschecked);
			if(boxeschecked!=0)
				button.disabled=false;
			else
				button.disabled=true;
			}
	</script>
	<script>
		function AreAnyCheckboxesChecked () 
		{
			setTimeout(function() {
		  if ($("#Form2 input:checkbox:checked").length > 0)
			{
			    $("#submit").removeAttr("disabled");
			}
			else
			{
			   $('#submit').attr("disabled","disabled");
			}}, 100);
		}
		</script>
	<section id="main">
		<ul class="breadcrumb">
			  <li><a href="#"><i class="fa fa-home fa-2x"></i>  Home</a> <span class="divider"><b>&raquo;</b></span></li>
			  <li><a href="#">Penghapusan</a><span class="divider"><b>&raquo;</b></span></li>
			  <li class="active">Daftar Penetapan Penghapusan Pemusnahan</li>
			  <?php SignInOut();?>
			</ul>
			<div class="breadcrumb">
				<div class="title">Penetapan Penghapusan Pemusnahan</div>
				<div class="subtitle">Daftar Penetapan Penghapusan Pemusnahan</div>
			</div>	

		<div class="grey-container shortcut-wrapper">
				<a class="shortcut-link" href="<?=$url_rewrite?>/module/penghapusan/dftr_usulan_pms.php">
					<span class="fa-stack fa-lg">
				      <i class="fa fa-circle fa-stack-2x"></i>
				      <i class="fa fa-inverse fa-stack-1x">1</i>
				    </span>
					<span class="text">Usulan Penghapusan</span>
				</a>
				<a class="shortcut-link " href="<?=$url_rewrite?>/module/penghapusan/dftr_penetapan_pms.php">
					<span class="fa-stack fa-lg">
				      <i class="fa fa-circle fa-stack-2x"></i>
				      <i class="fa fa-inverse fa-stack-1x">2</i>
				    </span>
					<span class="text">Penetapan Penghapusan</span>
				</a>
				<a class="shortcut-link active" href="<?=$url_rewrite?>/module/penghapusan/dftr_validasi_pms.php">
					<span class="fa-stack fa-lg">
				      <i class="fa fa-circle fa-stack-2x"></i>
				      <i class="fa fa-inverse fa-stack-1x">3</i>
				    </span>
					<span class="text">Validasi Penghapusan</span>
				</a>
			</div>		

		<section class="formLegend">
			
			<!-- <p><a href="filter_usulan_pms.php" class="btn btn-info btn-small"><i class="icon-plus-sign icon-white"></i>&nbsp;&nbsp;Tambah Penetapan Penghapusan</a>
			&nbsp;</p>	 -->

			<div id="demo">
			<form name="form" method="POST" ID="Form2" action="<?php echo "$url_rewrite/module/penghapusan/"; ?>validasi_proses_pms.php">
			
			<table cellpadding="0" cellspacing="0" border="0" class="display" id="example">
				<thead>
					<tr>
						<td colspan="8" align="Left">
								<span><button type="submit" name="submit" id="submit" value="tetapkan" class="btn btn-info " id="submit" disabled/><i class="icon-plus-sign icon-white"></i>&nbsp;&nbsp;Validasi Barang</button></span>
						</td>
					</tr>
					<tr>
						<th>No</th>
						<th class="checkbox-column">
							<input type="checkbox" class="icheck-input" onchange="return AreAnyCheckboxesChecked();">
						</th>
						<th>Nomor SK Penghapusan</th>
						<!-- <th>Satker</th> -->
						<th>Jumlah Usulan</th>
						<th>Tgl Penetapan</th>
						<th>Keterangan</th>
						<th>Tindakan</th>
					</tr>
				</thead>
				<tbody>		
							 
				 <?php
                                                                            
				 // '<pre>';
				//print_r($data['dataArr']);
				// echo '</pre>';
				
				$nomor = 1;
				if (!empty($data))
				{
				   
				foreach($data as $key => $row)
					{
					?>
						  
					<tr class="gradeA">
						<td><?php echo "$nomor";?></td>
						<td class="checkbox-column">
							<input type="checkbox" class="checkbox" onchange="enable()" name="ValidasiPenghapusan[]" value="<?php echo $row[Penghapusan_ID];?>" >
							
						</td>
						<td>
							<?php echo "$row[NoSKHapus]";?>
						</td>
						<!-- <td></td> -->
						<td>
							<?php 
							$jmlUsul=explode(",", $row[Usulan_ID]);
							echo count($jmlUsul);

							?>
						
						</td>
						<td><?php $change=$row[TglHapus]; $change2=  format_tanggal_db3($change); echo "$change2";?></td>
						<td><?php echo "$row[AlasanHapus]";?></td>
						<td align="center">	
						 <!--<a href="<?php echo "$url_rewrite/report/template/PENGHAPUSAN/";?>tes_class_penetapan_aset_yang_dihapuskan.php?menu_id=39&mode=1&id=<?php echo "$row[Penghapusan_ID]";?>" target="_blank">Cetak</a> ||--> 
						<a href="<?php echo "$url_rewrite/module/penghapusan/"; ?>dftr_review_edit_penetapan_usulan_validasi_pms.php?id=<?php echo "$row[Penghapusan_ID]";?>" class="btn btn-success"><i class="fa fa-pencil-square-o"></i> View</a>&nbsp;
						<!-- <a href="<?php echo "$url_rewrite/module/penghapusan/"; ?>penetapan_penghapusan_daftar_hapus_pms.php?id=<?php echo "$row[Penghapusan_ID]";?>" class="btn btn-danger"> <i class="fa fa-trash"></i>
 Hapus</a>   -->
						</td>
					</tr>
					<?php $nomor++;} }?>
				</tbody>
				<tfoot>
					<tr>
						<th>&nbsp;</th>
						<th>&nbsp;</th>
						<!-- <th>&nbsp;</th> -->
						<th>&nbsp;</th>
						<th>&nbsp;</th>
						<th>&nbsp;</th>
						<th>&nbsp;</th>
					</tr>
				</tfoot>
			</table>
			</form>
			</div>
			<div class="spacer"></div>
			    
		</section> 
		 
	</section>
	
<?php
	include"$path/footer.php";
?>