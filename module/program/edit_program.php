<?php
include "../../config/config.php";
$USERAUTH = new UserAuth();

$SESSION = new Session();

$menu_id = 71;
$SessionUser = $SESSION->get_session_user();
$USERAUTH->FrontEnd_check_akses_menu($menu_id, $SessionUser);

include"$path/meta.php";
include"$path/header.php";
include"$path/menu.php";

$idp = $_GET['idp'];
$tahun = $_GET['tahun'];
$satker = $_GET['satker'];
//sql sementara
$program = "select * from program where idp='{$idp}'";
$exe = mysql_query($program);
while ($data = mysql_fetch_assoc($exe)) {
	$dataProgram = $data; 
}
?>
	<script>
	jQuery(function($){
	   $("select").select2();
	   $("message").hide();

	   $('#kd_program').on('keyup', function(){
		var kd_program = $('#kd_program').val();
		var tahun = $('#tahun').val();
		var KodeSatker = $('#KodeSatker').val();
		var kd_program = $('#kd_program').val();
		var kd_program_old = $('#kd_program_old').val();		
		if(kd_program !=''){
			if(kd_program == kd_program_old){
				$("#message").show();
				$('#info').html('kode Program dapat digunakan'); 
				$('#info').css("color","green");
				$('#simpan').removeAttr('disabled');
			    $('#simpan').css("background","#04c");
			}else{
				$.post('../../function/api/programExist.php', {kd_program:kd_program,tahun:tahun,KodeSatker:KodeSatker}, function(result){
			    	if(result == 1){
						$("#message").show();
						$('#info').html('kode Program tidak dapat digunakan');
			            $('#info').css("color","red");
						$('#simpan').attr('disabled','disabled');
			            $('#simpan').css("background","grey");
					}else{
						$("#message").show();
						$('#info').html('kode Program dapat digunakan'); 
						$('#info').css("color","green");
						$('#simpan').removeAttr('disabled');
					    $('#simpan').css("background","#04c");
					}
				})
			}
	 	}
	});

	});
	</script>
	<section id="main">
		<ul class="breadcrumb">
		  <li><a href="#"><i class="fa fa-home fa-2x"></i>  Home</a> <span class="divider"><b>&raquo;</b></span></li>
		  <li><a href="#">Program</a><span class="divider"></span></li>
		  <?php SignInOut();?>
		</ul>
		<div class="breadcrumb">
			<div class="title">Daftar Program</div>
			<div class="subtitle">Edit Program</div>
		</div>
		<div class="grey-container shortcut-wrapper">
			<a class="shortcut-link active" href="<?=$url_rewrite?>/module/program/index.php">
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
		</div>
		<section class="formLegend">
		<form name="myform" method="post" action="update_program.php">
			<ul>
				<li>
					<span class="span2">Tahun</span>
					<input name="tahun" id="tahun" class="span1"  type="text" value="<?=$dataProgram['tahun'];?>" readonly>
				</li>
				<?=selectSatker('KodeSatker','470',true,(isset($dataProgram)) ? $dataProgram['kodeSatker'] : false,'readonly');?>
				<br/>
				<li style="display:none" id="message">
                	<span  class="span2">&nbsp;</span>
                		<div class="checkbox">
                  	<em id="info"></em>
                </div>
              	</li>
				<li>
					<span class="span2">Kode Program</span>
					<input name="kd_program" id="kd_program" class="span2"  type="text" value="<?=$dataProgram['kd_program']?>">
					<input name="kd_program_old" id="kd_program_old" class="span1"  type="hidden" value="<?=$dataProgram['kd_program']?>">
				</li>
				<li>
					<span class="span2">Program</span>
					<input name="program" id="program" class="span7"  type="text"
					 value="<?=$dataProgram['program']?>">
				</li>
				<li>
					<span class="span2">&nbsp;</span>
					<input type="submit" class="btn btn-primary " id="simpan" value="simpan" name="submit"/ >
					<input type="hidden" name="idp" value="<?=$dataProgram['idp']?>">
					<!--<input type="reset" name="reset" class="btn" value="Bersihkan Data">-->
				</li>
			</ul>
		</form>

		</section>     
	</section>
	
<?php
	include"$path/footer.php";
?>