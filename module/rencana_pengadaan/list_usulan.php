<?php
include "../../config/config.php";
		
//cek akses menu 
$menu_id = 73;

($SessionUser['ses_uid']!='') ? $Session = $SessionUser : $Session = $SESSION->get_session(array('title'=>'GuestMenu', 'ses_name'=>'menu_without_login')); 
$SessionUser = $SESSION->get_session_user();
$USERAUTH->FrontEnd_check_akses_menu($menu_id, $SessionUser);
session_start();

if($_GET){
	$tgl_usul = $_GET['tgl_usul'];
	$satker = $_GET['satker'];
}else{
	if($_POST){
		$tgl_usul = $_POST['tgl_usul'];
		$satker = $_POST['kodeSatker'];
	}
}

$par_data_table="tgl_usul=$tgl_usul&satker=$satker";
$ketkodeSatker = mysql_query("select NamaSatker from satker where kode ='{$satker}'");
$dataKetkodeSatker = mysql_fetch_assoc($ketkodeSatker);

include"$path/meta.php";
include"$path/header.php";
include"$path/menu.php";
	
?>
	<script>
	function gets(){
		console.log("testtes");
   		//alert("tes");
		//get id 
		//var param = ;
		var proses = $('#proses').val();
		console.log(proses);
		//split 
		//var val_split = param.split("_");
		//console.log(val_split);   		
	}
	jQuery(function($){
	   $("select").select2();
	   
	   $('#proses_2').on('click', function(){
	   		console.log("testtes");
	   		alert("tes");
	   	});


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
			<a class="shortcut-link active" href="<?=$url_rewrite?>/module/rencana_pengadaan/">
				<span class="fa-stack fa-lg">
			      <i class="fa fa-circle fa-stack-2x"></i>
			      <i class="fa fa-inverse fa-stack-1x">1</i>
			    </span>
				<span class="text">Usulan Rencana Pengadaan</span>
			</a>
			<a class="shortcut-link" href="<?=$url_rewrite?>/module/rencana_pengadaan/filter_penetapan.php">
					<span class="fa-stack fa-lg">
				      <i class="fa fa-circle fa-stack-2x"></i>
				      <i class="fa fa-inverse fa-stack-1x">2</i>
				    </span>
					<span class="text">Penetapan Rencana Pengadaan</span>
				</a>
			<a class="shortcut-link" href="<?=$url_rewrite?>/module/rencana_pengadaan/filter_validasi.php">
				<span class="fa-stack fa-lg">
			      <i class="fa fa-circle fa-stack-2x"></i>
			      <i class="fa fa-inverse fa-stack-1x">3</i>
			    </span>
				<span class="text">Validasi</span>
			</a>
			<a class="shortcut-link" href="<?=$url_rewrite?>/module/rencana_pengadaan/print_perencanaan_pengadaan.php">
					<span class="fa-stack fa-lg">
				      <i class="fa fa-circle fa-stack-2x"></i>
				      <i class="fa fa-inverse fa-stack-1x">4</i>
				    </span>
					<span class="text">Cetak Dokumen Perencanaan Pengadaan</span>
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
								 {"bSortable": false,"sWidth": '2%'},
								 {"bSortable": true,"sWidth": '15%'},
								 {"bSortable": true,"sWidth": '30%'},
								 {"bSortable": true,"sWidth": '20%'},
								 {"bSortable": false,"sWidth": '33%'}],
							"sPaginationType": "full_numbers",

							"bProcessing": true,
							"bServerSide": true,
							"sAjaxSource": "<?=$url_rewrite?>/api_list/view_usul_rencana_pegadaan.php?<?php echo $par_data_table?>"
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
						<a style="display:display"  href="tambah_usulan.php?tgl_usul=<?=$tgl_usul?>&satker=<?=$satker?>" class="btn btn-info btn-small" id="addruangan"><i class="icon-plus-sign icon-white" align="center"></i>&nbsp;&nbsp;Tambah Usulan</a>
					
					</li>
					<!--<li>
						<button type="button" id="" name="print" value="" 
						class="btn btn-success btn-small fa fa-spinner proses">Download pdf</button>

					</li>-->
				</ul>

				
				<!--<a style="display:display" data-toggle="modal" href="#addruang" class="btn btn-info btn-small" id="addruangan" title="Tambah Ruangan"><i class="icon-plus-sign">Tambah Ruangan</i></a>-->
			</div>	
			&nbsp;
			<div id="demo">
			<table cellpadding="0" cellspacing="0" border="0" class="display" id="dftrprogram">
				<thead>
					<tr>
						<th>No</th>
						<th>Tgl Usulan</th>
						<th>No Usulan</th>
						<th>Status</th>
						<th>Aksi</th>
					</tr>
				</thead>
				<tbody>			
					<tr>
                        <td colspan="5">Data Tidak di temukan</td>
                    </tr>
                    
				</tbody>
				<tfoot>
					<tr>
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