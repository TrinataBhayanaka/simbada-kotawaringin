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
?>
	<script>
	jQuery(function($){
	   $(".datepicker").mask("0000-00-00"); 
	   $("#tahun").mask("9999");   
	   $("select").select2();
	});
	</script>
	<section id="main">
		<ul class="breadcrumb">
		  <li><a href="#"><i class="fa fa-home fa-2x"></i>  Home</a> <span class="divider"><b>&raquo;</b></span></li>
		  <li><a href="#">Usulan Rencana Pemeliharaan</a><span class="divider"></span></li>
		  <?php SignInOut();?>
		</ul>
		<div class="breadcrumb">
			<div class="title">Usulan Rencana Pemeliharaan</div>
			<div class="subtitle">Filter Usulan Rencana Pemeliharaan</div>
		</div>
		<div class="grey-container shortcut-wrapper">
				<a class="shortcut-link " href="<?=$url_rewrite?>/module/rencana_pemeliharaan/">
					<span class="fa-stack fa-lg">
				      <i class="fa fa-circle fa-stack-2x"></i>
				      <i class="fa fa-inverse fa-stack-1x">1</i>
				    </span>
					<span class="text">Usulan Rencana Pemeliharaan</span>
				</a>
				<a class="shortcut-link" href="<?=$url_rewrite?>/module/rencana_pemeliharaan/list_penetapan.php">
					<span class="fa-stack fa-lg">
				      <i class="fa fa-circle fa-stack-2x"></i>
				      <i class="fa fa-inverse fa-stack-1x">2</i>
				    </span>
					<span class="text">Penetapan Rencana Pemeliharaan</span>
				</a>
				<a class="shortcut-link" href="<?=$url_rewrite?>/module/rencana_pemeliharaan/list_validasi.php">
					<span class="fa-stack fa-lg">
				      <i class="fa fa-circle fa-stack-2x"></i>
				      <i class="fa fa-inverse fa-stack-1x">3</i>
				    </span>
					<span class="text">Validasi</span>
				</a>
				<a class="shortcut-link active" href="<?=$url_rewrite?>/module/rencana_pemeliharaan/print_perencanaan_Pemeliharaan.php">
					<span class="fa-stack fa-lg">
				      <i class="fa fa-circle fa-stack-2x"></i>
				      <i class="fa fa-inverse fa-stack-1x">4</i>
				    </span>
					<span class="text">Cetak Dokumen Perencanaan Pemeliharaan</span>
				</a>
			</div>		
		
		<section class="formLegend">
		<form name="myform" method="post" action="<?php echo "$url_rewrite/report/template/PERENCANAAN/rencana_pemeliharaan_aset.php"; ?>">
			<ul>
				<!--<li>
					<span class="span2">Tanggal Awal</span>
					<input type="text" placeholder="yyyy-mm-dd" name="tgl_usul_awal" class= "datepicker" id="datepicker" value="" required="" />
				</li>
				<li>
					<span class="span2">Tanggal Akhir</span>
					<input type="text" placeholder="yyyy-mm-dd" name="tgl_usul_akhir" class = "datepicker" id="datepicker" value="" required="" />
				</li>-->
				<li>
					<span class="span2">Tahun Usulan</span>
					<input name="tahun" id="tahun" class="span1"  type="text" required="">
				</li>
				<?=selectSatker('kodeSatker','235',true,false,'required');?>
				<br>
				<li>
					<span class="span2">&nbsp;</span>
					<input type="submit" class="btn btn-primary" value="Cetak" name="submit"/>
					<input type="reset" name="reset" class="btn" value="Bersihkan Data">
				</li>
			</ul>
		</form>

		</section>     
	</section>
	
<?php
	include"$path/footer.php";
?>