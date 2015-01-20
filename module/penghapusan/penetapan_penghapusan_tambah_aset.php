<?php
include "../../config/config.php";

	$menu_id = 39;
    $SessionUser = $SESSION->get_session_user();
    ($SessionUser['ses_uid']!='') ? $Session = $SessionUser : $Session = $SESSION->get_session(array('title'=>'GuestMenu', 'ses_name'=>'menu_without_login')); 
    $USERAUTH->FrontEnd_check_akses_menu($menu_id, $SessionUser);
?>

<?php
	include"$path/meta.php";
	include"$path/header.php";
	include"$path/menu.php";
	
?><script type="text/javascript" src="<?php echo "$url_rewrite/"; ?>JS/jquery.min.js"></script>
                <script type="text/javascript" src="<?php echo "$url_rewrite/"; ?>JS/jquery-ui.min.js"></script> 
                <script type="text/javascript" src="<?php echo "$url_rewrite/"; ?>JS/jquery.ui.datepicker-id.js"></script>
                <script>
                    $(function()
                    {
                    $('#tanggal1').datepicker($.datepicker.regional['id']);
                    $('#tanggal2').datepicker($.datepicker.regional['id']);
                    $('#tanggal3').datepicker($.datepicker.regional['id']);
                    $('#tanggal4').datepicker($.datepicker.regional['id']);
                    $('#tanggal5').datepicker($.datepicker.regional['id']);
                    $('#tanggal6').datepicker($.datepicker.regional['id']);
                    $('#tanggal7').datepicker($.datepicker.regional['id']);
                    $('#tanggal8').datepicker($.datepicker.regional['id']);
                    $('#tanggal9').datepicker($.datepicker.regional['id']);
                    $('#tanggal10').datepicker($.datepicker.regional['id']);
                    $('#tanggal11').datepicker($.datepicker.regional['id']);
                    $('#tanggal12').datepicker($.datepicker.regional['id']);
                    $('#tanggal13').datepicker($.datepicker.regional['id']);
                    $('#tanggal14').datepicker($.datepicker.regional['id']);
                    $('#tanggal15').datepicker($.datepicker.regional['id']);

                    }

                    );
                </script>   
	<section id="main">
		<ul class="breadcrumb">
		  <li><a href="#"><i class="fa fa-home fa-2x"></i>  Home</a> <span class="divider"><b>&raquo;</b></span></li>
		  <li><a href="#">Penghapusan</a><span class="divider"><b>&raquo;</b></span></li>
		  <li class="active">Daftar Usulan Penetapan Penghapusan</li>
		  <?php SignInOut();?>
		</ul>
		<div class="breadcrumb">
			<div class="title">Daftar Usulan Penetapan Penghapusan</div>
			<div class="subtitle">Filter Data</div>
		</div>
		<section class="formLegend">
			
			 <form method="POST" action="<?php echo"$url_rewrite";?>/module/penghapusan/penetapan_penghapusan_tambah_lanjut.php?pid=1" onsubmit="return requiredFilter(false,false)">
			<ul>
							<!-- <li>
								<span class="span2">ID&nbsp;Aset&nbsp;(System&nbsp;ID)</span>
								<input style="width: 200px;" id="bup_pp_sp_asetid" name="bup_pp_sp_asetid" type="text" >
							</li>
							<li>
								<span class="span2">Nama&nbsp;Aset</span>
								<input isdatepicker="true" style="width: 480px;"  name="bup_pp_sp_namaaset"  type="text">
							</li> -->
							<li>
								<span class="span2">Nomor&nbsp;Usulan</span>
								<input isdatepicker="true" style="width: 200px;" id="bup_pp_sp_nousulan" name="bup_pp_sp_nousulan"  type="text">
							</li>
							<li>
								<span class="span2">Tanggal&nbsp;Usulan</span>
								<input id="tanggal13" name="bup_pp_sp_tglusul" id="bup_pp_sp_tglusul"  type="text" >
							</li>

							<!-- <li>
                                <span class="span2">Jenis Aset</span>
                                <input type="checkbox" name="jenisaset[]" value="1" class="jenisaset1">Tanah
                                <input type="checkbox" name="jenisaset[]" value="2" class="jenisaset2">Mesin
                                <input type="checkbox" name="jenisaset[]" value="3" class="jenisaset3">Bangunan
                                <input type="checkbox" name="jenisaset[]" value="4" class="jenisaset4">Jaringan
                                <input type="checkbox" name="jenisaset[]" value="5" class="jenisaset5">Aset Lain
                                <input type="checkbox" name="jenisaset[]" value="6" class="jenisaset6">KDP
                            </li>     -->
                            <li>&nbsp;</li>
							<?=selectSatker('kodeSatker',$width='205',$br=true,(isset($kontrak)) ? $kontrak[0]['kodeSatker'] : false);?>
                            <li>&nbsp;</li>
							<!-- <li>
								<span class="span2">Kelompok</span>
								<div class="input-append">
										<input type="text" name="pem_kelompok" id="pem_kelompok" class="span5" readonly="readonly" value="">
										<input type="button" name="idbtnlookupkelompok" id="idbtnlookupkelompok" class="btn" value="Pilih" onclick = "showSpoiler(this);">
										<div class="inner" style="display:none;">
											
											<?php
												//include "$path/function/dropdown/function_kelompok.php";
												$alamat_simpul_kelompok="$url_rewrite/function/dropdown/radio_simpul_kelompok.php";
												$alamat_search_kelompok="$url_rewrite/function/dropdown/radio_search_kelompok.php";
												js_radiokelompok($alamat_simpul_kelompok, $alamat_search_kelompok,"pem_kelompok","kelompok_id",'kelompok','pemkelompokfilter');
												$style="style=\"width:525px; height:220px; overflow:auto; border: 1px solid #dddddd;\"";
												radiokelompok($style,"kelompok_id",'kelompok','pemkelompokfilter');
											?>
										</div>
								</div>
							</li> -->
							<!-- <li>
								<span class="span2">Pilih Lokasi</span>
								<div class="input-append">
									<input type="text" name="entri_lokasi" id="entri_lokasi" class="span5" readonly="readonly" placeholder="(Semua Kelompok)">
									<input type="button" name="idbtnlookuplokasi" id="idbtnlookuplokasi" class="btn" value="Pilih" onclick = "showSpoiler(this);">
										<div class="inner" style="display:none;">
												
														<?php
													   // include "$path/function/dropdown/radio_function_lokasi_pengadaan.php";
														$alamat_simpul_lokasi="$url_rewrite/function/dropdown/radio_simpul_lokasi_pengadaan.php";
														$alamat_search_lokasi="$url_rewrite/function/dropdown/radio_search_lokasi_pengadaan.php";
														js_radiopengadaanlokasi($alamat_simpul_lokasi, $alamat_search_lokasi,"entri_lokasi","lokasi_id",'lokasi','p_provinsi','p_kabupaten','p_kecamatan','p_desa','lok');
														$style1="style=\"width:525px; height:220px; overflow:auto; border: 1px solid #dddddd;\"";
														radiopengadaanlokasi($style1,"lokasi_id",'lokasi',"lok");
														?>
											</div>
								</div>
							</li> 
							<li>
								<span class="span2">SKPD</span>
								<div class="input-append">
									<input type="text" name="lda_skpd" id="lda_skpd" class="span5" readonly="readonly" placeholder="<?php echo $_SESSION['ses_satkername'] ; ?>">
									<input type="button" name="idbtnlookupkelompok" id="idbtnlookupkelompok" class="btn" value="Pilih" onclick = "showSpoiler(this);">
									<div class="inner" style="display:none;">

										<?php
											$alamat_simpul_skpd="$url_rewrite/function/dropdown/radio_simpul_skpd.php";
											$alamat_search_skpd="$url_rewrite/function/dropdown/radio_search_skpd.php";
											js_radioskpd($alamat_simpul_skpd, $alamat_search_skpd,"lda_skpd","skpd_id",'skpd1','sk');
											$style2="style=\"width:525px; height:220px; overflow:auto; border: 1px solid #dddddd;\"";
											radioskpd($style2,"skpd_id",'skpd1','sk');
										?>
									</div>
								</div>
							</li>-->
							<li>
								<span class="span2">&nbsp;</span>
								<input type="submit" name="submit" class="btn btn-primary" value="Tampilkan Data" />
								<input type="reset" name="reset" class="btn" value="Bersihkan Data">
							</li>
						</ul>
						<table style="display: none">
							<tr>
								<td><input type="text" id="p_skpd" name="p_skpd" value="" readonly="readonly"></td>
								<td><input type="text" id="p_noreg_satker" name="p_noreg_satker" value="00.00"  size=5 maxlength="5"  id="posisiKolom3" readonly="readonly"/></td>
							</tr>
						</table>
						<table border="0" cellspacing="6" style="display: none">
                                                <tr>
                                                    <td>Desa</td>
                                                    <td>Kecamatan</td> 
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <input type="text" id="p_desa" name="p_desa" value="" size="45"  readonly="readonly">
                                                    </td>
                                                    <td>
                                                        <input type="text" id="p_kecamatan" name="p_kecamatan" value="" size="45" readonly="readonly" >
                                                    </td>

                                                </tr>
                                                <tr>
                                                    <td>Kabupaten</td>
                                                    <td>Provinsi</td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <input type="text" id="p_kabupaten" name="p_kabupaten" value=""size="45" readonly="readonly" >
                                                    </td>
                                                    <td>
                                                        <input type="text" id="p_provinsi" name="p_provinsi" value=""size="45" readonly="readonly" >
                                                    </td>
                                                    <td></td>
                                                </tr>
                                            </table>
						</form>
			
		</section>     
	</section>
	
<?php
	include"$path/footer.php";
?>