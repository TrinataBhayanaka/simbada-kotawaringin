<?php
include "../../config/config.php";
		
//cek akses menu 
$menu_id = 72;

($SessionUser['ses_uid']!='') ? $Session = $SessionUser : $Session = $SESSION->get_session(array('title'=>'GuestMenu', 'ses_name'=>'menu_without_login')); 
$SessionUser = $SESSION->get_session_user();
$USERAUTH->FrontEnd_check_akses_menu($menu_id, $SessionUser);
session_start();
//pr($_GET);
if($_GET){
	$idp	= trim($_GET['idp']);
	$tahun 	= trim($_GET['thn']);
	$satker = trim($_GET['satker']);
}else{
	//nothing
}

$par_data_table="tahun=$tahun&satker=$satker&idp=$idp";

$ketkodeSatker = mysql_query("select NamaSatker from satker where kode ='{$satker}'");
$dataKetkodeSatker = mysql_fetch_assoc($ketkodeSatker);

$ketoutput = mysql_query("select * from Program where idp ='{$idp}'");
$dataKetoutput = mysql_fetch_assoc($ketoutput);

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
		  <li><a href="#">Kegiatan</a><span class="divider"></span></li>
		  <?php SignInOut();?>
		</ul>
		<div class="breadcrumb">
			<div class="title">Daftar Kegiatan</div>
			<div class="subtitle">List Kegiatan</div>
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
								 {"bSortable": true,"sWidth": '63%'},
								 {"bSortable": false,"sWidth": '10%'}],
							"sPaginationType": "full_numbers",

							"bProcessing": true,
							"bServerSide": true,
							"sAjaxSource": "<?=$url_rewrite?>/api_list/view_kegiatan.php?<?php echo $par_data_table?>"
					   }
						  );
			  });
			  
			</script>
			<div class="detailLeft">
				<ul>
					<li>
						<span class="labelInfo ">Tahun</span>
						<input class="span1" type="text" value="<?=$tahun?>" disabled/>
					</li>
					<li>
						<span class="labelInfo">Satker</span>
						<input class="span4" type="text" value="<?="[".$satker."] ".$dataKetkodeSatker['NamaSatker']?>" disabled/>
					</li>
					<li>
						<span class="labelInfo">Kode Program</span>
						<input class="span1" type="text" value="<?=$dataKetoutput['kd_program']?>" disabled/>
					</li>
					<li>
						<span class="labelInfo">Program</span>
						<input class="span7" type="text" value="<?=$dataKetoutput['program']?>" disabled/>
					</li>
				</ul>
			</div>
			<div class="detailLeft">	
				<a style="display:display"  href="tambah_kegiatan.php?idp=<?=$idp?>&tahun=<?=$tahun?>&satker=<?=$satker?>" class="btn btn-info btn-small" id="addruangan"><i class="icon-plus-sign icon-white" align="center"></i>&nbsp;&nbsp;Tambah Kegiatan</a>
			</div>
			<div class="detailRight">
				<a id="addruangan" class="btn btn-small" href="list_program.php?tahun=<?=$tahun?>&satker=<?=$satker?>" >
				<i class="" align="center"></i>
				  Kembali ke halaman utama : Form Filter
				</a>
			</div>
			<div id="demo">
			<table cellpadding="0" cellspacing="0" border="0" class="display" id="dftrprogram">
				<thead>
					<tr>
						<th>No</th>
						<th>Kode Kegiatan</th>
						<th>Kegiatan</th>
						<th>Aksi</th>
					</tr>
				</thead>
				<tbody>			
					<tr>
                        <td colspan="4">Data Tidak di temukan</td>
                    </tr>
                    
				</tbody>
				<tfoot>
					<tr>
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