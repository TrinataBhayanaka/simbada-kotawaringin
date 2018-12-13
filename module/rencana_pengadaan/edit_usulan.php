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


$tgl_usul = $_GET['tgl_usul'];
$satker = $_GET['satker'];
$tahun  = $TAHUN_AKTIF + 1;
$idus   = $_GET['idus'];
//sql temp
//get program
$program = mysql_query("select * from program where KodeSatker = '$satker' and tahun = '$tahun'");

//get usulan perencanaan
$usul = mysql_query("select * from usulan_rencana_pengadaaan where idus = '$idus'");
$usulan = mysql_fetch_assoc($usul);

?>
	<script>
	jQuery(function($){
	   $("select").select2();
	   hierachy();
	   function hierachy(){
	   		var i =0;
			var template 	= "";
			var programid 	= $("#program").val();
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
					var idk_h = $("#idk_h").val();
					var par_select;
					if(data[i].idk == idk_h){
						par_select = "selected";
					}else{
						par_select = "";
					}
					template+='<option value="'+data[i].idk+'" '+par_select+'>'+data[i].kd_kegiatan+" "+data[i].kegiatan+'</option>';
				}
				$("#kegiatan").html(template);
				$("#kegiatan").select2();
				secondhierachy();
			}
			},"JSON")
	   }

	   function secondhierachy(){
	   		var i =0;
			var template 	= "";
			var programid 	= $("#program").val();
			var kegiatanid 	= $("#kegiatan").val();
			var tahun 		= $("#tahun").val();
			var satker 		= $("#satker").val();

			$.post('../../function/api/selectOutput.php', {programid:programid,kegiatanid:kegiatanid,tahun:tahun,satker:satker}, function(data){
				
			$('#output')
			    .find('option')
			    .remove()
			    .end()
			;
			
			if (data){
				for(i=0;i<data.length;i++){
					var idot_h = $("#idot_h").val();
					var par_select;
					if(data[i].idot == idot_h){
						par_select = "selected";
					}else{
						par_select = "";
					}
					template+='<option value="'+data[i].idot+'" '+par_select+'>'+data[i].kd_output+" "+data[i].output+'</option>';
				}
				$("#output").html(template);
				$("#output").select2();
				validate();
			}
		},"JSON")

	}
			function validate(){
			var programid 	= $("#program").val();
			var kegiatanid 	= $("#kegiatan").val();
			var outputid 	= $("#output").val();
			var tahun 		= $("#tahun").val();
			var satker 		= $("#satker").val();	

			var idp_h = $("#idp_h").val();
			var idk_h = $("#idk_h").val();
			var idot_h = $("#idot_h").val();
			
			if(programid == idp_h && kegiatanid == idk_h && outputid == idot_h){
				//no process
				$("#message").hide();
				$('#simpan').removeAttr('disabled');	
			    $('#simpan').css("background","#04c");
			}else{
				if(programid != '' && kegiatanid != '' && outputid != ''){
					$.post('../../function/api/usulanExist.php', {programid:programid,kegiatanid:kegiatanid,outputid:outputid,tahun:tahun,satker:satker}, function(result){
						if(result == 1){
							//alert('Kode Program Telah Tersedia');
							$("#message").show();
							$('#info').html('Usulan dengan Program, Kegiatan dan Output telah digunakan');
				            $('#info').css("color","red");
							$('#simpan').attr('disabled','disabled');
				            $('#simpan').css("background","grey");
						}else{
							$("#message").show();
							$('#info').html('Usulan dengan Program, Kegiatan dan Output dapat digunakan'); 
							$('#info').css("color","green");
							$('#simpan').removeAttr('disabled');	
						    $('#simpan').css("background","#04c");
						}
					})
				}else{
					$('#simpan').attr('disabled','disabled');
		            $('#simpan').css("background","grey");
				}
			}
	 	}

	   $('.program').on('change', function(){
	   		hierachy();
	   });

		$('.kegiatan').on('change', function(){
   			secondhierachy();
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
		<form name="myform" method="post" action="update_usulan.php">
			<ul>
				<li>
					<span class="span2">No Usulan</span>
					<input type="text" name="no_usul" id="" value="<?=$usulan[no_usul]?>" />
				</li>
				<li>
					<span class="span2">Tanggal Usulan</span>
					<input type="text" placeholder="yyyy-mm-dd" name="tgl_usul" id="datepicker" value="<?=$usulan[tgl_usul]?>" />
				</li>
				<?=selectSatker('KodeSatker','470',true,(isset($usulan[kodeSatker])) ? $usulan[kodeSatker]: false,'readonly');?>
				<br/>
				<li>
					<span class="span2">Tahun Aktif + 1</span>
					<input type="text" class="span1" name="tahun" id="tahun" value="<?=$tahun;?>" readonly>
				</li>
				<li>
					<span class="span2">Program</span>
					<select name="program" class="span5 program" id="program" required="" >
					<!--<option value="" >Pilih Program</option>-->	
        			<?php
        			while($get_program = mysql_fetch_assoc($program)){
        				if($get_program[idp]==$usulan[idp]){
        					$param = "selected";
        				}else{
        					$param = "";
        				} 
        			echo "<option value='$get_program[idp]'$param>$get_program[kd_program] $get_program[program]</option>";
        			}
        			?>	
        			</select>
				</li>
				<br/>
				<br/>
				<li>
					<span class="span2">Kegiatan</span>
					<select name="kegiatan" class="span5 kegiatan " id="kegiatan" required="" >
						<option value="" ></option>	
        			</select>
				</li>
				<br/>
				<br/>
				<li>
					<span class="span2">Output</span>
					<select name="output" class="span5 output" id="output" required="" >
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
					<span class="span2">&nbsp;</span>
					<input type="submit" class="btn btn-primary " id="simpan" value="simpan" name="submit"/ >
					<input type="hidden" name="satker" id="satker" value="<?=$usulan[kodeSatker];?>">
					<input type="hidden" name="tgl_usul_param" id="tgl_usul" value="<?=$tgl_usul;?>">
					<input type="hidden" name="idp" id="idp_h" value="<?=$usulan[idp];?>">
					<input type="hidden" name="idk" id="idk_h" value="<?=$usulan[idk];?>">
					<input type="hidden" name="idus" id="idus" value="<?=$usulan[idus];?>">
					<input type="hidden" name="idot" id="idot_h" value="<?=$usulan[idot];?>">
					<!--<input type="reset" name="reset" class="btn" value="Bersihkan Data">-->
				</li>
			</ul>
		</form>

		</section>     
	</section>
	
<?php
	include"$path/footer.php";
?>