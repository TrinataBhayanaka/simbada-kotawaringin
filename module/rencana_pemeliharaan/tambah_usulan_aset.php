<?php
include "../../config/config.php";

$USERAUTH = new UserAuth();

$SESSION = new Session();

$menu_id = 74;
$SessionUser = $SESSION->get_session_user();
$USERAUTH->FrontEnd_check_akses_menu($menu_id, $SessionUser);

include"$path/meta.php";
include"$path/header.php";
include"$path/menu.php";
//pr($_GET);
$idus = $_GET['idus'];
$tgl_usul = $_GET['tgl_usul'];
$satker = $_GET['satker'];
$tahun  = $TAHUN_AKTIF;

?>
	<script>
	jQuery(function($){
	   
	   $("select").select2();
	   $('.numbersOnly').keyup(function () {
	    if (this.value != this.value.replace(/[^0-9\.]/g, '')) {
		       this.value = this.value.replace(/[^0-9\.]/g, '');
		    }
		});

	   $('#kodeKelompok').on('change', function(){
		var kodeKelompok = $('#kodeKelompok').val();
		var KodeSatkerAsal = $('#satker').val();
		var KodeSatkerTujuan = $('#satkerTujuanHide').val();
		var idus = $('#idus').val();	
		var status_barang = $('#status_barang').val();
		
		var KodeSatker = '';
		if(status_barang == 'Milik Sendiri'){
			KodeSatker = KodeSatkerAsal;
		}else{
			KodeSatker = KodeSatkerTujuan;	
		}


		$('#jml_usul').val('');
		$('#satuan_usul').val('');
		$('#jml_optml').val('');
		$('#kondisi_baik').val('');
		$('#kondisi_rusak_ringan').val('');
		$('#satuan_optml').val('');


			//validate jenis aset
			if(kodeKelompok !='' && idus !='' ){
			$.post('../../function/api/kodeKelompokexistPemeliharaan.php', {kodeKelompok:kodeKelompok,idus:idus,status_barang:status_barang}, function(result){
				//console.log(result);
				if(result == 1){
					//alert('Kode Program Telah Tersedia');
					$("#message2").show();
					$('#info2').html('Jenis Aset telah digunakan');
		            $('#info2').css("color","red");
					$('#simpan').attr('disabled','disabled');
		            $('#simpan').css("background","grey");
				}else{
					//console.log("here");
					$("#message2").show();
					$('#info2').html('Jenis Aset dapat digunakan'); 
					$('#info2').css("color","green");
					$('#simpan').removeAttr('disabled');
				    $('#simpan').css("background","#04c");

				    if(kodeKelompok !='' && KodeSatker !='' ){
						$.post('../../function/api/asetOptmlPml.php', {kodeKelompok:kodeKelompok,KodeSatker:KodeSatker}, function(result){
								//console.log(result);
								document.getElementById('jml_optml').value = result[0].total; 
								if(result[0].total > 0){
			            			$('#simpan').removeAttr('disabled');
					    			$('#simpan').css("background","#04c");
								}else{
									$('#simpan').attr('disabled','disabled');
			            			$('#simpan').css("background","grey");
								}
								document.getElementById('kondisi_baik').value = result[0].Baik; 
								document.getElementById('kondisi_rusak_ringan').value = result[0].RR;

						},"JSON")
			 	 	}

				}
			})	
			}	

			
		});

	   $('#satuan_usul').on('keyup', function(){
			document.getElementById('satuan_optml').value = $('#satuan_usul').val();
		});

	   $('#status_barang').on('change', function(){
		var status_barang = $('#status_barang').val();
			if(status_barang == 'Milik Sendiri'){
				$('#satkerTujuan').hide();
			}else{
 				$('#satkerTujuan').show();	
			}
		});

	   $('#SatkerTujuan').on('change', function(){
			document.getElementById('satkerTujuanHide').value = $('#SatkerTujuan').val(); 
			var kodeKelompok = $('#kodeKelompok').val();
			var KodeSatkerAsal = $('#satker').val();
			var KodeSatkerTujuan = $('#SatkerTujuan').val();
			var idus = $('#idus').val();	
			var status_barang = $('#status_barang').val();
			
			var KodeSatker = '';
			if(status_barang == 'Milik Sendiri'){
				KodeSatker = KodeSatkerAsal;
			}else{
				KodeSatker = KodeSatkerTujuan;	
			}

			if(kodeKelompok !=''){
				//validate jenis aset
				if(kodeKelompok !='' && idus !='' ){
				$.post('../../function/api/kodeKelompokexistPemeliharaan.php', {kodeKelompok:kodeKelompok,idus:idus,status_barang:status_barang}, function(result){
					//console.log(result);
					if(result == 1){
						//alert('Kode Program Telah Tersedia');
						$("#message2").show();
						$('#info2').html('Jenis Aset telah digunakan');
			            $('#info2').css("color","red");
						$('#simpan').attr('disabled','disabled');
			            $('#simpan').css("background","grey");
					}else{
						//console.log("here");
						$("#message2").show();
						$('#info2').html('Jenis Aset dapat digunakan'); 
						$('#info2').css("color","green");
						$('#simpan').removeAttr('disabled');
					    $('#simpan').css("background","#04c");

					    if(kodeKelompok !='' && KodeSatker !='' ){
							$.post('../../function/api/asetOptmlPml.php', {kodeKelompok:kodeKelompok,KodeSatker:KodeSatker}, function(result){
								//console.log(result);
								document.getElementById('jml_optml').value = result[0].total; 
								if(result[0].total > 0){
			            			$('#simpan').removeAttr('disabled');
					    			$('#simpan').css("background","#04c");
								}else{
									$('#simpan').attr('disabled','disabled');
			            			$('#simpan').css("background","grey");
								}
								document.getElementById('kondisi_baik').value = result[0].Baik; 
								document.getElementById('kondisi_rusak_ringan').value = result[0].RR;

							},"JSON")
				 	 	}
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
		  <li><a href="#">Usulan Rencana Pemeliharaan</a><span class="divider"></span></li>
		  <?php SignInOut();?>
		</ul>
		<div class="breadcrumb">
			<div class="title">Usulan Rencana Pemeliharaan</div>
			<div class="subtitle">Filter Usulan Rencana Pemeliharaan</div>
		</div>
		<div class="grey-container shortcut-wrapper">
			<a class="shortcut-link active" href="<?=$url_rewrite?>/module/rencana_pemeliharaan/">
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
			<a class="shortcut-link" href="<?=$url_rewrite?>/module/rencana_pemeliharaan/print_perencanaan_pemeliharaan.php">
				<span class="fa-stack fa-lg">
			      <i class="fa fa-circle fa-stack-2x"></i>
			      <i class="fa fa-inverse fa-stack-1x">4</i>
			    </span>
				<span class="text"> Cetak Dokumen Perencanaan Pemeliharaan</span>
			</a>
		</div>	
		<section class="formLegend">
		<form name="myform" method="post" action="add_usulan_aset.php">
			<ul>
				<li>
					<span class="span2">Status Barang</span>
					<select name="status_barang" required="" id="status_barang">
					  <option value="Milik Sendiri">Milik Sendiri</option>
					  <option value="Pinjam Pakai">Pinjam Pakai</option>
					</select>
				</li>
				<br/>
				<div style="display:none" id="satkerTujuan">
					<?=selectAllSatker('SatkerTujuan','260',true,false,false,false,1,'Kode Satker Tujuan');?>
				</div>
				<br>
				<li>
					<?php selectAset('kodeKelompok','255',true,false,'required'); ?>
				</li>
				<br/>
				<li style="display:none" id="message2">
                	<span  class="span2">&nbsp;</span>
                		<div class="">
                  	<em id="info2"></em>
                </div>
                </li>
				<li><span class="span2">&nbsp;</span>
				*)Kondisi Barang Baik dan Rusak Ringan</li>
				<li>
					<p><b>Usulan Barang Milik Daerah</b></p>
				</li>
				<li>
					<span class="span2">Jml Usulan</span>
					<input type="text" class="span1 numbersOnly" name="jml_usul" id="jml_usul" value="" required/>
				</li>
				
				<li>
					<span class="span2">Satuan Usulan</span>
					<input type="text" name="satuan_usul" id="satuan_usul" value="" required/>
				</li>
				<li>
					<p><b>Data Daftar Barang yang dapat di optimalkan</b></p>
				</li>
				<li>
					<span class="span2">Jml Total Optimal</span>
					<input type="text" class="span1" name="jml_optml" id="jml_optml" value="" readonly=""/>
				</li>
				<li>
					<span class="span2">Jml Kondisi Baik</span>
					<input type="text" class="span1" name="kondisi_baik" id="kondisi_baik" value="" readonly=""/>
				</li>
				<li>
					<span class="span2">Jml Kondisi Rusak Ringan</span>
					<input type="text" class="span1" name="kondisi_rusak_ringan" id="kondisi_rusak_ringan" value="" readonly=""/>
				</li>
				<li>
					<span class="span2">Satuan Optimal</span>
					<input type="text" name="satuan_optml" id="satuan_optml" value="" readonly=""/>
				</li>
				<!--<li>
					<span class="span2">Status Barang</span>
					<input type="text" name="status_barang" id="status_barang" value="" />
				</li>-->
				<li>
					<span class="span2">Nama Pemelihara</span>
					<input type="text" name="pemeliharaan" id="pemeliharaan" value="" />
				</li>
				<li>
					<span class="span2">Keterangan</span>
					<textarea rows="3" cols="30" name="ket"></textarea>
				</li>
				<br/>
				<li>
					<span class="span2">&nbsp;</span>
					<input type="submit" class="btn btn-primary " id="simpan" value="simpan" name="submit"/ >
					<input type="hidden" name="idus" id="idus" value="<?=$idus;?>">
					<input type="hidden" name="satker" id="satker" value="<?=$satker;?>">
					<input type="hidden" name="satkerTujuanHide" id="satkerTujuanHide" value="">
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