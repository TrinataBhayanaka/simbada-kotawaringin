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
/*$par_data_table="tgl_usul=$tgl_usul&satker=$satker&idus=$idus";
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
//pr($dataKetKegiatan);*/

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
$ketOutput = mysql_query("select kd_output,output from output  
						where 
						idot ='{$dataKetUsulan[idot]}'");
$dataKetOutput = mysql_fetch_assoc($ketOutput);
//pr($dataKetOutput);

$dataUsulan = mysql_query("select * from usulan_rencana_pengadaaan 
						where 
						idus ='$_GET[idus]'");
$dataKet = mysql_fetch_assoc($dataUsulan);
//pr($dataKet);
$tgl = explode('-',$dataKet['tgl_usul']);
$format_tgl = $tgl[2]."/".$tgl[1]."/".$tgl[0];
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
								 {"bSortable": true,"sWidth": '18%'},
								 {"bSortable": true,"sWidth": '10%'},
								 {"bSortable": true,"sWidth": '10%'},
								 {"bSortable": true,"sWidth": '10%'},
								 {"bSortable": true,"sWidth": '10%'},
								 {"bSortable": true,"sWidth": '10%'},
								 {"bSortable": false,"sWidth": '20%'},
								 {"bSortable": false,"sWidth": '10%'}],
							"sPaginationType": "full_numbers",

							"bProcessing": true,
							"bServerSide": true,
							"sAjaxSource": "<?=$url_rewrite?>/api_list/view_penetapan_rencana_pegadaan_aset.php?<?php echo $par_data_table?>"
					   }
						  );
			  });
			  
			</script>
			<!-- Info Usual-->
			<div id="info" class="modal hide fade  myModal3" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
			 <div class="modal-dialog modal-lg">
				<div id="titleForm" class="modal-header" >
				  <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				  <h3 id="myModalLabel">Info</h3>
				</div>
				<div class="modal-body">
					<!--<div class="detailLeft">-->
						<ul>
							<li>
								<span class="labelInfo " style="font-weight: bold;">Satker</span>
								<br/>
								<input type="text" class="span5" value="<?='['.$satker.'] '.$dataKetkodeSatker['NamaSatker']?>" disabled/>
							</li>
							<li>
								<span class="labelInfo " style="font-weight: bold;">Usulan</span>
								<br/>
								<input type="text" class="span5" value="<?=$dataKet['no_usul']?>" disabled/>
							</li>
								<span class="labelInfo " style="font-weight: bold;">Tanggal Usulan</span>
								<br/>
								<input type="text" class="span5" value="<?=$format_tgl?>" disabled/>
							</li>
							<li>
								<span class="labelInfo " style="font-weight: bold;">Program </span>
								<br/>
								<input type="text" class="span5" value="<?=$dataKetUsulan['kd_program']." ".$dataKetUsulan['program'] ?>" disabled/>
							</li>
							<li>
								<span class="labelInfo " style="font-weight: bold;">kegiatan</span>
								<br/>
								<input type="text" class="span5" value="<?=$dataKetKegiatan['kd_kegiatan']." ".$dataKetKegiatan['kegiatan'] ?>" disabled/>
							</li>
							<li>
								<span class="labelInfo " style="font-weight: bold;">Output</span>
								<br/>
								<input type="text" class="span5" value="<?=$dataKetOutput['kd_output']." ".$dataKetOutput['output'] ?>" disabled/>
							</li>
						</ul>
					<!--</div>-->
				</div>
				<div class="modal-footer">
				  <button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
				</div>
				</div>
			</div> 

			<div class="detailLeft">
				<!--<ul>
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
				</ul>-->		
				<ul>
					<li>
						<a style="display:display" data-toggle="modal" href="#info" class="btn btn-warning btn-small" id="addruangan"><i class="fa fa-eye" align="center"></i>&nbsp;&nbsp;Info</a>
					</li>
				</ul>
			</div>

			<div class="detailRight">
				<ul>
					<li>
						<a id="" class="btn btn-small" href="list_penetapan.php?tgl_usul=<?=$tgl_usul?>&satker=<?=$satker?>" >
						<i class="" align="center"></i>
						  Kembali ke halaman Sebelumnya : List Usulan
						</a>
					</li>
				</ul>		
				
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
						<th>Status</th>
						<th>Ket</th>
						<th>Aksi</th>
					</tr>
				</thead>
				<tbody>			
					<tr>
                        <td colspan="9">Data Tidak di temukan</td>
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