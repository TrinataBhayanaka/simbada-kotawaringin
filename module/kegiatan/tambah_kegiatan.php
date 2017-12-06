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

//sql temp
$program 	 = mysql_query("select * from program where KodeSatker = '$satker'");
?>
	<script>
	jQuery(function($){
	   $("select").select2();
	   $("message").hide();

	    $('#kd_kegiatan').on('change', function(){
		var kd_kegiatan = $('#kd_kegiatan').val();
		var idp = $('#idp').val();
		var tahun = $('#tahun').val();
		var KodeSatker = $('#kodeSatker').val();
		
		if(kd_kegiatan !='' && idp !='' && tahun !='' && KodeSatker !=''){
		$.post('../../function/api/kegiatanExist.php', {kd_kegiatan:kd_kegiatan,idp:idp,tahun:tahun,KodeSatker:KodeSatker}, function(result){
		if(result == 1){
			//alert('Kode Program Telah Tersedia');
			$("#message").show();
			$('#info').html('kode Kegiatan tidak dapat digunakan');
            $('#info').css("color","red");
			$('#simpan').attr('disabled','disabled');
            $('#simpan').css("background","grey");
		}else{
			$("#message").show();
			$('#info').html('kode Kegiatan dapat digunakan'); 
			$('#info').css("color","green");
			$('#simpan').removeAttr('disabled');
		    $('#simpan').css("background","#04c");
		}
		})
	 	 }
	});

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
			<div class="subtitle">Tambah Kegiatan</div>
		</div>
		<div class="grey-container shortcut-wrapper">
			<a class="shortcut-link" href="<?=$url_rewrite?>/module/program/index.php">
				<span class="fa-stack fa-lg">
			      <i class="fa fa-circle fa-stack-2x"></i>
			      <i class="fa fa-inverse fa-stack-1x">1</i>
			    </span>
				<span class="text">Program</span>
			</a>
			<a class="shortcut-link active" href="<?=$url_rewrite?>/module/kegiatan/index.php">
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
		<form name="myform" method="post" action="add_kegiatan.php">
			<ul>
				<li>
					<span class="span2">Tahun</span>
					<input name="tahun" id="tahun" class="span1"  type="text" value="<?=$tahun;?>" readonly>
				</li>
				<?=selectSatker('kodeSatker','470',true,(isset($satker)) ? $satker: false,'readonly');?>
				<br/>
              	<li>
					<span class="span2">Program</span>
					<select name="program" class="span5 program" id="idp" required="">
					<option value="" >Pilih Program</option>	
        			<?php
        			while($get_program = mysql_fetch_assoc($program)){
        				echo "<option value='$get_program[idp]'>$get_program[kd_program] $get_program[program]</option>";
        			}
        			?>	
        			</select>
				</li>
				<br/>
				<br/>
				<li style="display:none" id="message">
                	<span  class="span2">&nbsp;</span>
                		<div class="checkbox">
                  	<em id="info"></em>
                </div>
              	</li>
				<li>
					<span class="span2">Kode Kegiatan</span>
					<input name="kd_kegiatan" id="kd_kegiatan" class="span2"  type="text"
					>
				</li>
				<li>
					<span class="span2">Kegiatan</span>
					<input name="kegiatan" id="kegiatan" class="span7"  type="text"
					>
				</li>
				<li>
					<span class="span2">&nbsp;</span>
					<input type="submit" class="btn btn-primary " id="simpan" value="simpan" name="submit"/ >
					<input type="hidden" name="idp" id="idp" value="<?=$dataKetoutput['idp']?>">
					<!--<input type="reset" name="reset" class="btn" value="Bersihkan Data">-->
				</li>
			</ul>
		</form>

		</section>     
	</section>
	
<?php
	include"$path/footer.php";
?>