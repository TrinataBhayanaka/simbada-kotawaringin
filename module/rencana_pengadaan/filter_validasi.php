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
				<a class="shortcut-link " href="<?=$url_rewrite?>/module/rencana_pengadaan/">
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
				<a class="shortcut-link active" href="<?=$url_rewrite?>/module/rencana_pengadaan/filter_validasi.php">
					<span class="fa-stack fa-lg">
				      <i class="fa fa-circle fa-stack-2x"></i>
				      <i class="fa fa-inverse fa-stack-1x">3</i>
				    </span>
					<span class="text">Validasi</span>
				</a>
			</div>		
		
		<section class="formLegend">
		<form name="myform" method="post" action="list_validasi.php">
			<ul>
				<li>
					<span class="span2">Tanggal Usulan</span>
					<input type="text" placeholder="yyyy-mm-dd" name="tgl_usul" id="datepicker" value="" />
				</li>
				<?=selectSatker('kodeSatker','235',true,false,'required');?>
				<br>
				<li>
					<span class="span2">&nbsp;</span>
					<?php
					if($SessionUser['ses_uaksesadmin'] == 1){
				echo "<input type=\"submit\" class=\"btn btn-primary\" value=\"Filter\" name=\"submit\">";
				echo "<input type=\"reset\" name=\"reset\" class=\"btn\" value=\"Bersihkan Data\">";
					}
					?>
				</li>
			</ul>
		</form>

		</section>     
	</section>
	
<?php
	include"$path/footer.php";
?>