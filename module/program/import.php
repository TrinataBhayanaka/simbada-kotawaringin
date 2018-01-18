<?php
include "../../config/config.php";

$USERAUTH = new UserAuth();

$SESSION = new Session();
//pr($TAHUN_AKTIF);
$TahunRencana = $TAHUN_AKTIF + 1; 
//pr($TahunRencana);

$menu_id = 71;
$SessionUser = $SESSION->get_session_user();
$USERAUTH->FrontEnd_check_akses_menu($menu_id, $SessionUser);

include"$path/meta.php";
include"$path/header.php";
include"$path/menu.php";
?>
	<script>
	jQuery(function($){
	   $("#tahun").mask("9999");
	   $("select").select2();
	});
	</script>
	<section id="main">
		<ul class="breadcrumb">
		  <li><a href="#"><i class="fa fa-home fa-2x"></i>  Home</a> <span class="divider"><b>&raquo;</b></span></li>
		  <li><a href="#">Program</a><span class="divider"></span></li>
		  <?php SignInOut();?>
		</ul>
		<div class="breadcrumb">
			<div class="title">Import</div>
			<div class="subtitle">Import Program dan Kegiatan</div>
		</div>
			
		<div class="grey-container shortcut-wrapper">
			<a class="shortcut-link" href="<?=$url_rewrite?>/module/program/index.php">
				<span class="fa-stack fa-lg">
			      <i class="fa fa-circle fa-stack-2x"></i>
			      <i class="fa fa-inverse fa-stack-1x">1</i>
			    </span>
				<span class="text">Program</span>
			</a>
			<a class="shortcut-link" href="<?=$url_rewrite?>/module/kegiatan/index.php">
				<span class="fa-stack fa-lg">
			      <i class="fa fa-circle fa-stack-2x"></i>
			      <i class="fa fa-inverse fa-stack-1x">2</i>
			    </span>
				<span class="text">Kegiatan</span>
			</a>
			<a class="shortcut-link" href="<?=$url_rewrite?>/module/output/index.php">
				<span class="fa-stack fa-lg">
			      <i class="fa fa-circle fa-stack-2x"></i>
			      <i class="fa fa-inverse fa-stack-1x">3</i>
			    </span>
				<span class="text">Output</span>
			</a>
			<a class="shortcut-link active" href="<?=$url_rewrite?>/module/program/import.php">
				<span class="fa-stack fa-lg">
			      <i class="fa fa-circle fa-stack-2x"></i>
			      <i class="fa fa-inverse fa-stack-1x">4</i>
			    </span>
				<span class="text">Import Program dan Kegiatan</span>
			</a>
		</div>	

		<section class="formLegend">
		<form name="myform" method="post" action="import_proses.php" enctype="multipart/form-data">
			<ul>
				<li>
					<span class="span2">Tahun</span>
					<input name="tahun" id="tahun" class="span1"  type="text" value="<?=$TahunRencana?>" readonly="">
				</li>
				<li>
					<span class="span2">Browse File </span>
					<input id="file-1" type="file" class="file" name="myFile" required="required">
				</li>
				<li>
					<span class="span2">&nbsp;</span>
					<input type="submit" class="btn btn-primary" value="Import" name="submit"/>
					<input type="reset" name="reset" class="btn" value="Bersihkan Data">
				</li>
			</ul>
		</form>

		</section>     
	</section>
	
<?php
	include"$path/footer.php";
?>