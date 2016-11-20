<?php
include "../../config/config.php";
		
//cek akses menu 
$menu_id = 73;

($SessionUser['ses_uid']!='') ? $Session = $SessionUser : $Session = $SESSION->get_session(array('title'=>'GuestMenu', 'ses_name'=>'menu_without_login')); 
$SessionUser = $SESSION->get_session_user();
$USERAUTH->FrontEnd_check_akses_menu($menu_id, $SessionUser);
session_start();

//pr($_GET);
if($_GET){
	$tgl_usul = $_GET['tgl_usul'];
	$satker = $_GET['satker'];
	$idus = $_GET['idus'];
}else{
	/*if($_POST){
		$tgl_usul = $_POST['tgl_usul'];
		$satker = $_POST['kodeSatker'];
	}*/
}
//sql temp
$par_data_table="tgl_usul=$tgl_usul&satker=$satker&idus=$idus";
$ketkodeSatker = mysql_query("select NamaSatker from satker where kode ='{$satker}'");
$dataKetkodeSatker = mysql_fetch_assoc($ketkodeSatker);

$ketusulan = mysql_query("select us.*,p.* from usulan_rencana_pengadaaan as us 
						inner join program as p on p.idp = us.idp
						where 
						us.idus ='{$idus}'");
$dataKetUsulan = mysql_fetch_assoc($ketusulan);
//pr($dataKetUsulan);
$ketKegiatan = mysql_query("select kd_kegiatan,kegiatan from kegiatan  
						where 
						idp ='{$dataKetUsulan[idp]}' and idk ='{$dataKetUsulan[idk]}' ");
$dataKetKegiatan = mysql_fetch_assoc($ketKegiatan);
//pr($dataKetKegiatan);
include"$path/meta.php";
include"$path/header.php";
include"$path/menu.php";
	
?>
	<script>
	jQuery(function($){
	   $("select").select2();
	});
	</script>

	<section id="main">
		<ul class="breadcrumb">
		  <li><a href="#"><i class="fa fa-home fa-2x"></i>  Home</a> <span class="divider"><b>&raquo;</b></span></li>
		  <li><a href="#">Usulan Rencana Pengadaan</a><span class="divider"></span></li>
		  <?php SignInOut();?>
		</ul>
		<div class="breadcrumb">
			<div class="title">Usulan Rencana Pengadaan</div>
			<div class="subtitle">Filter Usulan Rencana Pengadaan</div>
		</div>
		<div class="grey-container shortcut-wrapper">
			<a class="shortcut-link" href="<?=$url_rewrite?>/module/rencana_pengadaan/">
				<span class="fa-stack fa-lg">
			      <i class="fa fa-circle fa-stack-2x"></i>
			      <i class="fa fa-inverse fa-stack-1x">1</i>
			    </span>
				<span class="text">Usulan Rencana Pengadaan</span>
			</a>
			<a class="shortcut-link active" href="<?=$url_rewrite?>/module/rencana_pengadaan/filter_penetapan.php">
					<span class="fa-stack fa-lg">
				      <i class="fa fa-circle fa-stack-2x"></i>
				      <i class="fa fa-inverse fa-stack-1x">2</i>
				    </span>
					<span class="text">Penetapan Rencana Pengadaan</span>
				</a>
			<a class="shortcut-link" href="<?=$url_rewrite?>/module/pejabat/export.php">
				<span class="fa-stack fa-lg">
			      <i class="fa fa-circle fa-stack-2x"></i>
			      <i class="fa fa-inverse fa-stack-1x">2</i>
			    </span>
				<span class="text">Validasi</span>
			</a>
		</div>	
		
		<section class="formLegend">
			<script>
			$(document).ready(function() {
				  $('#dftrprogram').dataTable(
						   {
							"aoColumnDefs": [
								 { "aTargets": [2] }
							],
							"aoColumns":[
								 {"bSortable": false},
								 {"bSortable": true},
								 {"bSortable": true},
								 {"bSortable": true},
								 {"bSortable": true},
								 {"bSortable": true},
								 {"bSortable": false}],
							"sPaginationType": "full_numbers",

							"bProcessing": true,
							"bServerSide": true,
							"sAjaxSource": "<?=$url_rewrite?>/api_list/view_penetapan_rencana_pegadaan_aset.php?<?php echo $par_data_table?>"
					   }
						  );
			  });
			  
			</script>
			<div class="detailLeft">
				<ul>
					<li>
						<span class="labelInfo">Satker</span>
						<input type="text" class="span3" value="<?='['.$satker.'] '.$dataKetkodeSatker['NamaSatker']?>" disabled/>
					</li>
					<li>
						<span class="labelInfo">No Usulan</span>
						<input type="text" class="span3" value="<?=$dataKetUsulan['no_usul']?>" disabled/>
					</li>
					<li>
						<span class="labelInfo">Program </span>
						<input type="text" class="span5" value="<?=$dataKetUsulan['kd_program']." ".$dataKetUsulan['program'] ?>" disabled/>
					</li>
					<li>
						<span class="labelInfo">kegiatan</span>
						<input type="text" class="span5" value="<?=$dataKetKegiatan['kd_kegiatan']." ".$dataKetKegiatan['kegiatan'] ?>" disabled/>
					</li>
				</ul>		
			</div>

			<div class="detailRight">
				<ul>
					<li>&nbsp;</li>
					<li>&nbsp;</li>
					<li>&nbsp;</li>
					<li>&nbsp;</li>
					<li>&nbsp;</li>
					<li>&nbsp;</li>
					<li>&nbsp;</li>
					<li>&nbsp;</li>
				</ul>		
				<a id="" class="btn btn-small" href="list_usulan.php?tgl_usul=<?=$tgl_usul?>&satker=<?=$satker?>" >
				<i class="" align="center"></i>
				  Kembali ke halaman Sebelumnya : List Usulan
				</a>
			</div>
			&nbsp;
			<div id="demo">
			<table cellpadding="0" cellspacing="0" border="0" class="display" id="dftrprogram">
				<thead>
					<tr>
						<th>No</th>
						<th>KodeBarang</th>
						<th>Jml barang usulan</th>
						<th>Jml barang maksimal</th>
						<th>Jml barang optimal</th>
						<th>Jml barang riil</th>
						<th>Aksi</th>
					</tr>
				</thead>
				<tbody>			
					<tr>
                        <td colspan="7">Data Tidak di temukan</td>
                    </tr>
                    
				</tbody>
				<tfoot>
					<tr>
						<th>&nbsp;</th>
						<th>&nbsp;</th>
						<th>&nbsp;</th>
						<th>&nbsp;</th>
						<th>&nbsp;</th>
						<th>&nbsp;</th>
						<th>&nbsp;</th>
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