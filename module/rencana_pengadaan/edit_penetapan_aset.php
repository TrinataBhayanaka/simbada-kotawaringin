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
//pr($_GET);
$idr = $_GET['idr'];
$idus = $_GET['idus'];
$tgl_usul = $_GET['tgl_usul'];
$satker = $_GET['satker'];
$tahun  = $TAHUN_AKTIF;

//temp sql
$dataUsulan = mysql_query("select * from usulan_rencana_pengadaaan_aset where idr = '{$idr}'");
$data = mysql_fetch_assoc($dataUsulan);
//pr($data);
?>
	<script>
	jQuery(function($){
	   $("select").select2();
	   $('.numbersOnly').keyup(function () {
	    if (this.value != this.value.replace(/[^0-9\.]/g, '')) {
		       this.value = this.value.replace(/[^0-9\.]/g, '');
		    }
		});

	   var revisi_jml_rill = $('#jml_rill_rev').val();
	   //console.log(revisi_jml_rill);
	   if($('#jml_rill_rev').val().length != 0){
	   		if(revisi_jml_rill == 0){
	   			//console.log("rill 0");
   				$("#jml_usul_rev").prop("readonly", true);
				$("#satuan_usul_rev").prop("readonly", true);
				$('.infoTolak').show();
      		//$('#infoTolak').html('Optimalisasi BMD (Revisi Jml Maksimal < Jml Optimal)');
      		$('#infoTolak').html('Optimalisasi BMD');
            	$('#infoTolak').css("color","red");
            	//$("#simpan").prop("disabled", true);
		    }else{
		   		//console.log("rill not 0");
	   			$("#jml_usul_rev").prop("readonly", false);
				$("#satuan_usul_rev").prop("readonly", false); 
				$('.infoTolak').hide();		
		   }	
	   }else{
			//console.log("rill kosong");
   			$("#jml_usul_rev").prop("readonly", false);
			$("#satuan_usul_rev").prop("readonly", false); 
	   }
	   

	   $('#jml_max_rev').on('change', function(){
		var jml_max = $('#jml_max_rev').val();
		var jml_optml = $('#jml_optml').val();
		var hasil = parseInt(jml_max) - parseInt(jml_optml);
			if(parseInt(hasil) <= 0){
				document.getElementById('jml_rill_rev').value = 0;
				document.getElementById('jml_usul_rev').value = 0;
				//readonly jml dan satuan rencana
				$("#jml_usul_rev").prop("readonly", true);
				$("#satuan_usul_rev").prop("readonly", true);
				$('.infoTolak').show();
      			//$('#infoTolak').html('Optimalisasi BMD (Revisi Jml Maksimal < Jml Optimal)');
      			$('#infoTolak').html('Optimalisasi BMD (Revisi Jml Maksimal < Jml Optimal)');
            	$('#infoTolak').css("color","red");
            	$("#simpan").prop("disabled", true);	
			}else{
				document.getElementById('jml_rill_rev').value = hasil;
				//unreadonly jml dan satuan rencana
				$("#jml_usul_rev").prop("readonly", false);
				$("#satuan_usul_rev").prop("readonly", false); 
				$('.infoTolak').hide();
				$("#simpan").prop("disabled", false);	
			}	
		});

	   $('#jml_usul_rev').on('change', function(){
	   	var jml_usul_rev = $('#jml_usul_rev').val();
	   	var jml_rill_rev = $('#jml_rill_rev').val();
	   		if(parseInt(jml_usul_rev) > parseInt(jml_rill_rev)){
	   			$('.infoReklas').show();
          		$('#infoReklas').html('Jumlah Rencana > Revisi Jumlah Riil');
            	$('#infoReklas').css("color","red");
            	$("#simpan").prop("disabled", true);
	   		}else{
	   			$('.infoReklas').hide();
	   			$("#simpan").prop("disabled", false);
	      		
	   		}
	   	});

	   $('#jml_ekstra_rev').on('change', function(){
	   		//jml_intra
	   		var jml_intra = $('#jml_intra').val();
	   		var jml_ekstra = $('#jml_ekstra_rev').val();
	   		var hasil = parseInt(jml_intra) + parseInt(jml_ekstra);
	   		document.getElementById('jml_optml').value = hasil; 

	   		//validate
	   		var jml_optml = $('#jml_optml').val();
	   		var jml_max = $('#jml_max_rev').val();
	   		if(jml_max){
	   			var hasil2 = parseInt(jml_max) - parseInt(jml_optml);
		   		if(parseInt(hasil2) <= 0){
					document.getElementById('jml_rill').value = 0;
					alert("Kebutuhan Barang Maksimal < Kebutuhan Optimal");
					$('#simpan').attr('disabled','disabled');
	            	$('#simpan').css("background","grey"); 
				}else{
					document.getElementById('jml_rill').value = hasil2; 
					$('#simpan').removeAttr('disabled');
					$('#simpan').css("background","#04c");
				}	
	   		}else{
	   			//nothing todo
	   		}
	   		

	   	});

	   $('#satuan_usul_rev').on('change', function(){
		var satuan_usul_rev = $('#satuan_usul_rev').val();
			document.getElementById('satuan_usul').value = satuan_usul_rev; 
			document.getElementById('satuan_max').value = satuan_usul_rev; 
			document.getElementById('satuan_optml').value = satuan_usul_rev; 
			document.getElementById('satuan_rill').value = satuan_usul_rev; 
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
			<a class="shortcut-link " href="<?=$url_rewrite?>/module/rencana_pengadaan/">
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
		<form name="myform" method="post" action="update_penetapan_aset.php">
			<ul>
				<li>
					<?php selectAset('kodeKelompok','255',true,(isset($data[kodeKelompok])) ? $data[kodeKelompok]: false,'readonly'); ?>
				</li>
				<br/>
				<li>
					<p><b>Usulan Barang Milik Daerah</b></p>
				</li>
				<li>
					<span class="span2">Jml Usulan</span>
					<input type="text" class="span1 numbersOnly" name="jml_usul" id="jml_usul" 
					value="<?=$data[jml_usul]?>" readonly/>
				</li>
				
				<li>
					<span class="span2">Satuan Usulan</span>
					<input type="text" name="satuan_usul" id="satuan_usul" 
					value="<?=$data[satuan_usul]?>" readonly/>
				</li>
				<li>
					<p><b>Data Daftar Barang yang dapat di optimalkan</b></p>
				</li>
				<li>
					<span class="span2">Jml Optimal</span>
					<input type="text" class="span1" name="jml_optml" id="jml_optml" 
					value="<?=$data[jml_optml]?>" readonly=""/>
				</li>
				<li>
					<span class="span2">Aset Intra Kompatabel</span>
					<input type="text" class="span1" name="jml_intra" id="jml_intra" value="<?=$data[jml_intra]?>" readonly=""/>
				</li>
				<li>
					<span class="span2">Aset Ekstra Kompatabel</span>
					<input type="text" class="span1 numbersOnly" name="jml_ekstra" id="jml_ekstra" value="<?=$data[jml_ekstra]?>" readonly=""/>
				</li>
				<li>
					<span class="span2">Revisi Aset Ekstra Kompatabel</span>
					<input type="text" class="span1 numbersOnly" name="jml_ekstra_rev" id="jml_ekstra_rev" value="<?=$data[jml_ekstra_rev]?>"/>
				</li>
				<li>
					<span class="span2">Satuan Optimal</span>
					<input type="text" name="satuan_optml" id="satuan_optml" 
					value="<?=$data[satuan_optml]?>" readonly=""/>
				</li>
				<li>
					<p><b>Kebutuhan Maksimal</b></p>
				</li>
				<li>
					<span class="span2">Jml Maksimal</span>
					<input type="text" class="span1 numbersOnly" name="jml_max" id="jml_max" 
					value="<?=$data[jml_max]?>" readonly/>
				</li>
				<li>
					<span class="span2">Revisi Jml Maksimal</span>
					<input type="text" class="span1 numbersOnly" name="jml_max_rev" id="jml_max_rev"
					value="<?=$data[jml_max_rev]?>" />
				</li>
				<li>
					<span class="span2">Satuan Maksimal</span>
					<input type="text" name="satuan_max" id="satuan_max" 
					value="<?=$data[satuan_max]?>"  readonly="" />
				</li>
				<li>
					<p><b>Kebutuhan Riil Barang Milik Daerah</b></p>
				</li>
				<li>
					<span class="span2">Jml Rill</span>
					<input type="text" class="span1" name="jml_rill" id="jml_rill" 
					value="<?=$data[jml_rill]?>" readonly=""/>
				</li>
				<li>
					<span class="span2">Revisi Jml Rill</span>
					<input type="text" class="span1" name="jml_rill_rev" id="jml_rill_rev" 
					value="<?=$data[jml_rill_rev]?>" readonly=""/>
				</li>
				<li>
					<span class="span2">Satuan Rill</span>
					<input type="text" name="satuan_rill" id="satuan_rill" 
					value="<?=$data[satuan_rill]?>" readonly=""/>
				</li>
				<li>
					<p><b>Rencana Pengadaan Kebutuhan Barang Milik Daerah</b></p>
				</li>
				<li>
					<span class="span2">Jml Rencana</span>
					<input type="text" class="span1 numbersOnly" name="jml_usul_rev" id="jml_usul_rev" 
					value="<?=$data[jml_usul_rev]?>" required/>
				</li>
				<li style="display:none" class="infoReklas">
					<span class="">&nbsp;</span>
					 <em id="infoReklas"></em>
				</li>
				<li>
					<span class="span2">Satuan Rencana</span>
					<input type="text" name="satuan_usul_rev" id="satuan_usul_rev" 
					value="<?=$data[satuan_usul_rev]?>" required />
				</li>
				<li style="display:none" class="infoTolak">
					<span class="">&nbsp;</span>
					 <em id="infoTolak"></em>
				</li>
				<li>
					<span class="span2">Keterangan</span>
					<textarea rows="3" cols="30" name="ket"><?=$data[ket]?></textarea>
				</li>
				<li>
					<span class="span2">Cara Pemenuhan</span>
					<!--<input type="text" name="cara" id="cara" value="<?=$data[cara]?>" required/>-->
					<select name="cara" style="width: 200px;">
						<?php
							if($data[cara] == 'Pembelian'){	
								$flag_pembelian = 'selected';
								$flag_sewa = '';
								$flag_optml = '';
							}elseif($data[cara] == 'Sewa'){
								$flag_pembelian = '';
								$flag_sewa = 'selected';
								$flag_optml = '';
							}elseif($data[cara] == 'Pengoptimalan BMD'){
								$flag_pembelian = '';
								$flag_sewa = '';
								$flag_optml = 'selected';
							}
						?>
					<option value="Pembelian" <?=$flag_pembelian?>>Pembelian</option>
				    <option value="Sewa" <?=$flag_sewa?>>Sewa</option>
				    <option value="Pengoptimalan BMD" <?=$flag_optml?>>Pengoptimalan BMD</option>
				  </select>
				</li>
				<br/>
				<li>
					<span class="span2">&nbsp;</span>
					<input type="submit" class="btn btn-primary " id="simpan" value="simpan" name="submit"/ >
					<input type="hidden" name="idr" id="idr" value="<?=$idr;?>">
					<input type="hidden" name="idus" id="idus" value="<?=$idus;?>">
					<input type="hidden" name="satker" id="satker" value="<?=$satker;?>">
					<input type="hidden" name="tgl_usul_param" id="tgl_usul" value="<?=$tgl_usul;?>">
					<!--<input type="reset" name="reset" class="btn" value="Bersihkan Data">-->
				</li>
			</ul>
		</form>

		</section>     
	</section>
	
<?php
	include"$path/footer.php";
?>