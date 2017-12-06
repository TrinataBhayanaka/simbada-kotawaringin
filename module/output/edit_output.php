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

$idot = $_GET['idot'];
$tahun = $_GET['tahun'];
$satker = $_GET['satker'];

//sql temp
$program = mysql_query("select * from program where KodeSatker = '$satker'");
$output = mysql_query("select * from output where idot = '$idot'");
$getOutput = mysql_fetch_assoc($output);
//pr($getOutput);
?>
	<script>
	jQuery(function($){
	    $("select").select2();
	    $("message").hide();
	    hierachy();

	    $('#kd_output').on('change', function(){
	    var program = $('#idp').val();
		var kegiatan = $('#kegiatan').val();
	    var kd_output = $('#kd_output').val();
	    
		var tahun = $('#tahun').val();
		var kodeSatker = $('#kodeSatker').val();
		
		if(kd_output !='' && kegiatan !='' && program !='' && tahun !='' && kodeSatker !=''){
		$.post('../../function/api/outputExist.php', {kd_output:kd_output,kegiatan:kegiatan,program:program,tahun:tahun,kodeSatker:kodeSatker}, function(result){
		if(result == 1){
			//alert('Kode Program Telah Tersedia');
			$("#message").show();
			$('#info').html('kode Output tidak dapat digunakan');
            $('#info').css("color","red");
			$('#simpan').attr('disabled','disabled');
            $('#simpan').css("background","grey");
		}else{
			$("#message").show();
			$('#info').html('kode Output dapat digunakan'); 
			$('#info').css("color","green");
			$('#simpan').removeAttr('disabled');
		    $('#simpan').css("background","#04c");
		}
		})
	 	 }
	});

	   function hierachy(){
	   		var i =0;
			var template 	= "";
			var programid 	= $("#idp").val();
			var tahun 		= $("#tahun").val();
			var satker 		= $("#satker").val();
					
			$.post('../../function/api/selectkegiatan.php', {programid:programid,tahun:tahun,satker:satker}, function(data){
					
			$('#kegiatan')
			    .find('option')
			    .remove()
			    .end()
			;
			
			if (data){
				for(i=0;i<data.length;i++){
					var idk = $("#idk").val();
					var par_select;
					if(data[i].idk == idk){
						par_select = "selected";
					}else{
						par_select = "";
					}
					template+='<option value="'+data[i].idk+'" '+par_select+'>'+data[i].kd_kegiatan+" "+data[i].kegiatan+'</option>';
				}
				$("#kegiatan").html(template);
				$("#kegiatan").select2();
			}
			},"JSON")
	   }

	$('.program').on('change', function(){
	   		hierachy();
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
			<a class="shortcut-link " href="<?=$url_rewrite?>/module/kegiatan/index.php">
				<span class="fa-stack fa-lg">
			      <i class="fa fa-circle fa-stack-2x"></i>
			      <i class="fa fa-inverse fa-stack-1x">2</i>
			    </span>
				<span class="text">Kegiatan</span>
			</a>
			<a class="shortcut-link active" href="<?=$url_rewrite?>/module/output/index.php">
				<span class="fa-stack fa-lg">
			      <i class="fa fa-circle fa-stack-2x"></i>
			      <i class="fa fa-inverse fa-stack-1x">3</i>
			    </span>
				<span class="text">Output</span>
			</a>
		</div>
		<section class="formLegend">
		<form name="myform" method="post" action="update_output.php">
			<ul>
				<li>
					<span class="span2">Tahun</span>
					<input name="tahun" id="tahun" class="span1"  type="text" value="<?=$getOutput[tahun];?>" readonly>
				</li>
				<?=selectSatker('kodeSatker','257',true,(isset($getOutput[kodeSatker])) ? $getOutput[kodeSatker]: false,'readonly');?>
				<br/>
              	<li>
					<span class="span2">Program</span>
					<select name="program" class="span5 program" id="idp" required="">
					<option value="" >Pilih Program</option>	
        			<?php
        			while($get_program = mysql_fetch_assoc($program)){
        				if($get_program[idp]==$getOutput[idp]){
        					$param = "selected";
        				}else{
        					$param = "";
        				} 
        				echo "<option value='$get_program[idp]' $param>$get_program[kd_program] $get_program[program]</option>";
        			}
        			?>	
        			</select>
				</li>
				<br/>
				<br/>
				<li>
					<span class="span2">Kegiatan</span>
					<select name="kegiatan" class="span5 kegiatan " id="kegiatan" required="">
        				<option value="" ></option>	
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
					<span class="span2">Kode Output</span>
					<input name="kd_output" id="kd_output" class="span2"  type="text" value="<?=$getOutput[kd_output]?>" 
					>
				</li>
				<li>
					<span class="span2">Output</span>
					<input name="output" id="output" class="span7"  type="text" value="<?=$getOutput[output]?>"
					>
				</li>
				<li>
					<span class="span2">&nbsp;</span>
					<input type="submit" class="btn btn-primary " id="simpan" value="simpan" name="submit"/ >
					<input type="hidden" name="idot" id="idot" value="<?=$getOutput['idot']?>">
					<input type="hidden" name="idk" id="idk" value="<?=$getOutput['idk']?>">
					<input type="hidden" name="satker" id="satker" value="<?=$getOutput['kodeSatker']?>">
					<!--<input type="reset" name="reset" class="btn" value="Bersihkan Data">-->
				</li>
			</ul>
		</form>

		</section>     
	</section>
	
<?php
	include"$path/footer.php";
?>