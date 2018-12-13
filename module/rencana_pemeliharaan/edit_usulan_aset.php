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
$idr = $_GET['idr'];
$idus = $_GET['idus'];
$tgl_usul = $_GET['tgl_usul'];
$satker = $_GET['satker'];
$tahun  = $TAHUN_AKTIF;

//temp sql
$dataUsulan = mysql_query("select * from usulan_rencana_pemeliharaan_aset where idr = '{$idr}'");
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

	    var status_barang = $('#status_barangHidden').val();
	    if(status_barang == 'Milik Sendiri'){
			$('#satkerTujuan').hide();
		}else{
			$('#satkerTujuan').show();	
		}

	   	$('#kodeKelompok').on('change', function(){
			var kodeKelompok = $('#kodeKelompok').val();
			var kodeKelompok_Old = $('#kodekelompokHidden').val();
			var KodeSatkerAsal = $('#satker').val();
			var KodeSatkerTujuan = $('#satkerTujuanHidden').val();
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

			var status_barang = $('#status_barang').val();
			var status_barang_Old = $('#status_barangHidden').val();
			
			if(status_barang == status_barang_Old && kodeKelompok == kodeKelompok_Old){
				//no proses
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
			}else{
				//proses
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
				            $('#jml_optml').val('');
							$('#kondisi_baik').val('');
							$('#kondisi_rusak_ringan').val('');
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

	   $('#satuan_usul').on('keyup', function(){
			var satuan_usul = $('#satuan_usul').val();
			document.getElementById('satuan_optml').value = satuan_usul; 
		});

	   $('#status_barang').on('change', function(){
			var status_barang = $('#status_barang').val();
			var status_barang_Old = $('#status_barangHidden').val();
			var idus = $('#idus').val();
			var kodeKelompok = $('#kodeKelompok').val();
			var KodeSatkerAsal = $('#satker').val();
			var KodeSatkerTujuan = $('#satkerTujuanHidden').val();
			var KodeSatker = '';
			
			if(status_barang == 'Milik Sendiri'){
				$('#satkerTujuan').hide();
				KodeSatker = KodeSatkerAsal;
			}else{
 				$('#satkerTujuan').show();	
 				KodeSatker = KodeSatkerTujuan;
			}
			
			if(status_barang == status_barang_Old){
				//no proses
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
			}else{
				//proses
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
				            $('#jml_optml').val('');
							$('#kondisi_baik').val('');
							$('#kondisi_rusak_ringan').val('');
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
		
	   	$('#SatkerTujuan').on('change', function(){
			document.getElementById('satkerTujuanHidden').value = $('#SatkerTujuan').val(); 
			var kodeKelompok = $('#kodeKelompok').val();
			var KodeSatkerAsal = $('#satker').val();
			var KodeSatkerTujuan = $('#SatkerTujuan').val();
			var idus = $('#idus').val();	
			var status_barang = $('#status_barang').val();
			var status_barang_Old = $('#status_barangHidden').val();
			
			var KodeSatker = '';
			if(status_barang == 'Milik Sendiri'){
				KodeSatker = KodeSatkerAsal;
			}else{
				KodeSatker = KodeSatkerTujuan;	
			}

			if(kodeKelompok !=''){
				//validate jenis aset
				if(status_barang == status_barang_Old){
				//no proses
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
				}else{
					//proses
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
					            $('#jml_optml').val('');
								$('#kondisi_baik').val('');
								$('#kondisi_rusak_ringan').val('');
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
		<form name="myform" method="post" action="update_usulan_aset.php">
			<ul>
				<li>
					<span class="span2">Status Barang</span>
					<select name="status_barang" readonly id="status_barang">
					  <option value="Milik Sendiri" <?=($data[status_barang] == 'Milik Sendiri') ? 'selected': ''?>>Milik Sendiri</option>
					  <option value="Pinjam Pakai" <?=($data[status_barang] == 'Pinjam Pakai') ? 'selected': ''?>>Pinjam Pakai</option>
					</select>
				</li>
				<br/>
				<div style="display:none" id="satkerTujuan">
					<?=selectAllSatker('SatkerTujuan','260',true,(isset($data[SatkerTujuan])) ? $data[SatkerTujuan]: false,false,false,1,'Kode Satker Tujuan');?>
				</div>
				<br/>
				<li>
					<?php //selectAset('kodeKelompok','255',true,(isset($data[kodeKelompok])) ? $data[kodeKelompok]: false,'readonly'); ?>
					<?php selectAset('kodeKelompok','255',true,(isset($data[kodeKelompok])) ? $data[kodeKelompok]: false,'required'); ?>
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
					<input type="text" class="span1 numbersOnly" name="jml_usul" id="jml_usul" 
					value="<?=$data[jml_usul]?>" required/>
				</li>
				
				<li>
					<span class="span2">Satuan Usulan</span>
					<input type="text" name="satuan_usul" id="satuan_usul" 
					value="<?=$data[satuan_usul]?>" required/>
				</li>
				<li>
					<p><b>Data Daftar Barang yang dapat di optimalkan</b></p>
				</li>
				<li>
					<span class="span2">Jml Total Optimal</span>
					<input type="text" class="span1" name="jml_optml" id="jml_optml" 
					value="<?=$data[jml_optml]?>" readonly=""/>
				</li>
				<li>
					<span class="span2">Jml Kondisi Baik</span>
					<input type="text" class="span1" name="kondisi_baik" id="kondisi_baik" value="<?=$data[jml_baik]?>" readonly=""/>
				</li>
				<li>
					<span class="span2">Jml Kondisi Rusak Ringan</span>
					<input type="text" class="span1" name="kondisi_rusak_ringan" id="kondisi_rusak_ringan" value="<?=$data[jml_rusak_ringan]?>" readonly=""/>
				</li>
				<li>
					<span class="span2">Satuan Optimal</span>
					<input type="text" name="satuan_optml" id="satuan_optml" 
					value="<?=$data[satuan_optml]?>" readonly=""/>
				</li>
				<!--<li>
					<span class="span2">Status Barang</span>
					<input type="text" name="status_barang" id="status_barang" value="<?=$data[status_barang]?>" />
				</li>-->
				<li>
					<span class="span2">Nama Pemelihara</span>
					<input type="text" name="pemeliharaan" id="pemeliharaan" value="<?=$data[pemeliharaan]?>" />
				</li>
				<li>
					<span class="span2">Keterangan</span>
					<textarea rows="3" cols="30" name="ket"><?=$data[ket]?></textarea>
				</li>
				<br/>
				<li>
					<span class="span2">&nbsp;</span>
					<input type="submit" class="btn btn-primary " id="simpan" value="simpan" name="submit"/ >
					<input type="hidden" name="idr" id="idr" value="<?=$idr;?>">
					<input type="hidden" name="idus" id="idus" value="<?=$idus;?>">
					<input type="hidden" name="satker" id="satker" value="<?=$satker;?>">
					<input type="hidden" name="kodekelompokHidden" id="kodekelompokHidden" value="<?=$data[kodeKelompok];?>">
					<input type="hidden" name="tgl_usul_param" id="tgl_usul" value="<?=$tgl_usul;?>">
					<input type="hidden" name="status_barangHidden" id="status_barangHidden" value="<?=$data[status_barang];?>">
					<input type="hidden" name="satkerTujuanHidden" id="satkerTujuanHidden" value="<?=$data[SatkerTujuan];?>">
					<!--<input type="reset" name="reset" class="btn" value="Bersihkan Data">-->
				</li>
			</ul>
		</form>

		</section>     
	</section>
	
<?php
	include"$path/footer.php";
?>