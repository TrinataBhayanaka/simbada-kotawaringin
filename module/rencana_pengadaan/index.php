<?php
include "../../config/config.php";

$USERAUTH = new UserAuth();

$SESSION = new Session();

$menu_id = 73;
$SessionUser = $SESSION->get_session_user();
$USERAUTH->FrontEnd_check_akses_menu($menu_id, $SessionUser);

include"$path/meta.php";
include"$path/header.php";
include"$path/menu.php";

$tahun= $_GET['tahun'];
if($tahun=="") $tahun=$TAHUN_AKTIF;
$par_data_table="tahun=$tahun";
$satker = $_SESSION['ses_satkerkode'];
//pr($satker);
?>
	<script>
	jQuery(function($){
	   $("#datepicker").mask("0000-00-00");    
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
				<a class="shortcut-link active" href="<?=$url_rewrite?>/module/rencana_pengadaan/">
					<span class="fa-stack fa-lg">
				      <i class="fa fa-circle fa-stack-2x"></i>
				      <i class="fa fa-inverse fa-stack-1x">1</i>
				    </span>
					<span class="text">Usulan Rencana Pengadaan</span>
				</a>
				<a class="shortcut-link" href="<?=$url_rewrite?>/module/rencana_pengadaan/list_penetapan.php">
					<span class="fa-stack fa-lg">
				      <i class="fa fa-circle fa-stack-2x"></i>
				      <i class="fa fa-inverse fa-stack-1x">2</i>
				    </span>
					<span class="text">Penetapan Rencana Pengadaan</span>
				</a>
				<a class="shortcut-link" href="<?=$url_rewrite?>/module/rencana_pengadaan/list_validasi.php">
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
	         $('#list_usulan_rkbmd').dataTable(
	                   {
	                   	"aoColumnDefs": [
	                         { "aTargets": [2] }
	                    ],
	                    "aoColumns":[
	                         {"bSortable": false, "sWidth": "5%"},
	                         {"bSortable": true, "sWidth": "25%"},
	                         {"bSortable": false, "sWidth": "25%"},
	                         {"bSortable": true, "sWidth": "10%"},
	                         {"bSortable": false, "sWidth": "10%"},
	                         {"bSortable": false, "sWidth": "25%"}],
	                    "sPaginationType": "full_numbers",

	                    "bProcessing": true,
	                    "bServerSide": true,
	                    "sAjaxSource": "<?=$url_rewrite?>/api_list/list_usulan_rkbmd.php?<?php echo $par_data_table?>"
	               }
	        );  
	    });
	    </script>
	    <?php
	    if($_SESSION['ses_uaksesadmin'] !=1){
	    	echo "<p><a href='$url_rewrite/module/rencana_pengadaan/tambah_usulan.php?satker=$satker' class=\"btn btn-info btn-small\"><i class=\"icon-plus-sign icon-white\"></i>&nbsp;&nbsp;Tambah Usulan</a>";
	    }
	    ?>
		<h4>Tahun Usulan :
			<?=$tahun?>
		</h4>
		<?php
		$tahun_akhir=$tahun;
		for($tahun=2017;$tahun<=$tahun_akhir;$tahun++){
		?><a href="?tahun=<?=$tahun?>" class="btn btn-info btn-small"><i class="icon-plus-sign icon-white"></i>&nbsp;&nbsp;<?=$tahun?></a>
		
		&nbsp;
		<?php
		}
		?>
		</p>
		<div id="demo">
			<table cellpadding="0" cellspacing="0" border="0" class="display" id="list_usulan_rkbmd">
				<thead>
					<tr>
						<th>No</th>
						<th>No Usulan</th>
						<th>Satker</th>
						<th>Tgl Usulan</th>
						<th>Status</th>
						<th>Aksi</th>
					</tr>
				</thead>
				<tbody>			
					<tr>
                        <td colspan="6">Data Tidak di temukan</td>
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
					</tr>
				</tfoot>
			</table>
		</div>

		</section>     
	</section>
	
<?php
	include"$path/footer.php";
?>