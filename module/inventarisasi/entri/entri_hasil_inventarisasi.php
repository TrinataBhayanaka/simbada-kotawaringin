<?php
include "../../../config/config.php";
$menu_id = 10;
            $SessionUser = $SESSION->get_session_user();
            ($SessionUser['ses_uid']!='') ? $Session = $SessionUser : $Session = $SESSION->get_session(array('title'=>'GuestMenu', 'ses_name'=>'menu_without_login')); 
            $USERAUTH->FrontEnd_check_akses_menu($menu_id, $Session);

// $get_data_filter = $RETRIEVE->retrieve_kontrak();
// pr($get_data_filter);
?>

<?php
	include"$path/meta.php";
	include"$path/header.php";
	include"$path/menu.php";
	
?>
	<!-- SQL Sementara -->
	<?php

		if(isset($_POST['kodeKelompok'])){
		    if($_POST['Aset_ID'] == "")
		    {
		      	$dataArr = $STORE->store_inventarisasi($_POST);
		    }  else
		    {
		      $dataArr = $STORE->store_edit_aset($_POST,$_POST['Aset_ID']);
		    }
		  }		

	?>
	<!-- End Sql -->
	<script type="text/javascript">
		$(document).ready(function() {
			$('#hrgmask,#total').autoNumeric('init');
			$("select").select2({
			});
			   
			$( "#tglPerolehan,#tglPembukuan,#tglSurat,#tglDokumen,#datepicker" ).datepicker({ format: 'yyyy-mm-dd' });
			$( "#tglPerolehan,#tglPembukuan,#tglSurat,#tglDokumen,#datepicker" ).mask('0000-00-00');
		});	

		function getCurrency(item){
	      $('#hrgSatuan').val($(item).autoNumeric('get'));
	    }
	</script>
	<section id="main">
		<ul class="breadcrumb">
			  <li><a href="#"><i class="fa fa-home fa-2x"></i>  Home</a> <span class="divider"><b>&raquo;</b></span></li>
			  <li><a href="#">Inventarisasi</a><span class="divider"><b>&raquo;</b></span></li>
			  <li class="active">Entri Inventarisasi</li>
			  <?php SignInOut();?>
			</ul>
			<div class="breadcrumb">
				<div class="title">Inventarisasi</div>
				<div class="subtitle">Entri Inventarisasi</div>
			</div>		

		<section class="formLegendInve">
			
			<div style="height:5px;width:100%;clear:both"></div>
			

			<div>
			<form action="" method="POST">
				 <div class="detailLeft">
						<ul>
							<li>
								<span class="span2">Kode Pemilik</span>
								<select id="kodepemilik" name="kodepemilik" style="width:255px">
									<option value="0">0 Pemerintah Pusat</option>
									<option value="11">11 Pemerintah Provinsi</option>
									<option value="12" selected>12 Pemerintah Kabupaten/Kota</option>
									<option value="13">13 Pemerintah Provinsi Lain</option>
									<option value="14">14 Pemerintah Kabupaten/Kota Lain</option>
									<option value="15">15 Non Pemerintah</option>
								</select>
							</li>
						</ul>
						<ul>
							<?=selectSatker('kodeSatker','255',true,false,'required');?>
						</ul>
						<ul>
							<?=selectRuang('kodeRuangan','kodeSatker','255',true,false);?>
						</ul>		
						<ul>	
							<?php selectAset('kodeKelompok','255',true,false,'required'); ?>
						</ul>
						<ul>
							<li>
								<span class="span2">Tgl. Pembelian</span>
								<div class="control">
									<div class="input-prepend">
										<span class="add-on"><i class="fa fa-calendar"></i></span>
										<input type="text" class="span2" placeholder="yyyy-mm-dd" name="tglPerolehan" id="tglPerolehan" required/>
									</div>
								</div>
							</li>
							<li>
								<span class="span2">Tgl. Pembukuan</span>
								<div class="control">
									<div class="input-prepend">
										<span class="add-on"><i class="fa fa-calendar"></i></span>
										<input type="text" class="span2" placeholder="yyyy-mm-dd" name="tglPembukuan" id="datepicker" required/>
									</div>
								</div>
							</li>
						</ul>

<div id="tanah_bangunan" style="display:none">                                     
                        <ul >
                            <li>
								<span class="span2">Data Tanah</span>
								<button type="button" 
                                      id="load-data-tanah" class="btn btn-info btn-lg" data-toggle="modal" 
                                     data-target="#myModal">Open</button>
                                 <input type="hidden" name="data_satker" id="data_satker" value="">
                                 <input type="hidden" name="tanah_id" id="tanah_id" value="">

							</li>
                        </ul>
                        <ul id="detail_tanah_bangunan" style="display:none">
                        	<li>
							    <span class="span2">Kelompok Tanah</span>
                                <input type="text" name="kelompok_tanah" id="kelompok_tanah"
                                	 value="" readonly/>

							</li>
                        </ul>
                        <ul >
                        	<li>
							    <span class="span2">Luas Total (Tanah)</span>
                                <input type="text" name="luas_total" id="luas_total"
                                	 value="" />

							</li>
                        </ul>
                        <ul >
                        	<li>
							    <span class="span2">Status Tanah</span>
                                <select id="status_tanah" name="status_tanah" style="width:255px">
									<option value="">--</option>
									<option value="Tanah Milik Pemda">Tanah Milik Pemda</option>
									<option value="Tanah Milik Negara">Tanah Milik Negara</option>
									<option value="Tanah Milik Ulayat">Tanah Milik Negara</option>
									<option value="Tanah Hak Guna Bangunan">Tanah Hak Guna Bangunan</option>
									<option value="Tanah Hak Pakai">Tanah Hak Pakai</option>
									<option value="Tanah Hak Pengelolaan">Tanah Hak Pengelolaan</option>
									<option value="Tanah Hak Lainnya">Tanah Hak Lainnya</option>
									
								</select>

							</li>
                        </ul>


</div>
					</div>

					<div style="height:5px;width:100%;clear:both"></div>

						<div class="detailLeft">
						<ul>
							<li>
								<span class="span2">Asal Usul</span>
								<select id="asalusul" name="asalusul" style="width:255px">
									<option value="Pembelian">Pembelian</option>
									<option value="Hibah">Hibah</option>
								</select>
							</li>
							<li>&nbsp;</li>
							<li>
								<span class="span2">Alamat</span>
								<textarea name="Alamat" class="span3" ><?=($kontrak[0]['tipeAset'] == 3)? $aset[0]['Alamat'] : ''?></textarea>
							</li>
							<li>
								<span class="span2">Jumlah</span>
								<input type="text" class="span3" name="Kuantitas" id="jumlah" value="<?=($kontrak[0]['tipeAset'] == 3)? 1 : ''?>" onchange="return totalHrg()" required/>
							</li>
							<li>
								<span class="span2">Harga Satuan</span>
								<!-- <input type="text" class="span3" name="Satuan" id="hrgSatuan" value="<?=($kontrak[0]['tipeAset'] == 3)? $aset[0]['NilaiPerolehan'] : ''?>" onchange="return totalHrg()" required/> -->
								<input type="text" class="span3" data-a-sign="Rp " id="hrgmask" data-a-dec="," data-a-sep="." value="<?=($kontrak[0]['tipeAset'] == 3)? $aset[0]['NilaiPerolehan'] : ''?>" onkeyup="return getCurrency(this);" onchange="return totalHrg();" required/>
								<input type="hidden" name="Satuan" id="hrgSatuan" value="<?=($kontrak[0]['tipeAset'] == 3)? $aset[0]['NilaiPerolehan'] : ''?>" >
							</li>
							<li>
								<span class="span2">Nilai Perolehan</span>
								<!-- <input type="text" class="span3" name="NilaiPerolehan" id="total" value="<?=($kontrak[0]['tipeAset'] == 3)? $aset[0]['NilaiPerolehan'] : ''?>" readonly/> -->
								<!--<input type="text" class="span3" name="NilaiPerolehan" data-a-sign="Rp " data-a-dec="," data-a-sep="." id="total" value="<?=($kontrak[0]['tipeAset'] == 3)? $aset[0]['NilaiPerolehan'] : ''?>" readonly/>
								<input type="hidden" name="NilaiPerolehan" id="nilaiPerolehan" value="<?=($kontrak[0]['tipeAset'] == 3)? $aset[0]['NilaiPerolehan'] : ''?>" >-->

								<input type="text" class="span3" name="tes" id="tes" value="<?=($kontrak[0]['tipeAset'] == 3)? $aset[0]['NilaiPerolehan'] : ''?>" readonly>
							</li>
							<li>
								<span class="span2">Info</span>
								<textarea name="Info" class="span3" ><?=($kontrak[0]['tipeAset'] == 3)? $aset[0]['Info'] : ''?></textarea>
							</li>
						</ul>
							
					</div>

					<div class="detailLeft">
						<ul class="tanah" style="display:none">
							<li>
								<span class="span2">Hak Tanah</span>
								<select id="hakpakai" name="HakTanah" style="width:255px" disabled>
									<option value="Hak Pakai">Hak Pakai</option>
									<option value="Hak Pengelolaan">Hak Pengelolaan</option>
								</select>
							</li>
							<li>&nbsp;</li>
							<li>
								<span class="span2">Luas (M2)</span>
								<input type="text" class="span3" name="LuasTotal" disabled/>
							</li>
							<li>
								<span class="span2">No. Sertifikat</span>
								<input type="text" class="span3" name="NoSertifikat" disabled/>
							</li>
							<li>
								<span class="span2">Tgl. Sertifikat</span>
								<input type="text" class="span2" name="TglSertifikat" id="datepicker" disabled/>
							</li>
							<li>
								<span class="span2">Penggunaan</span>
								<input type="text" class="span3" name="Penggunaan" disabled/>
							</li>

						</ul>
						<ul class="mesin" style="display:none">
							<li>
								<span class="span2">Kondisi</span>
								<select id="kondisiMesin" name="kondisi" style="width:155px" disabled>
									<option value="1">Baik</option>
									<option value="2">Rusak Ringan</option>
									<option value="3">Rusak Berat</option>
									<option value="5">Hibah</option>
								</select>
							</li>
							<li>&nbsp;</li>
							<li>
								<span class="span2">Merk</span>
								<input type="text" class="span3" name="Merk" disabled/>
							</li>
							<li>
								<span class="span2">Type</span>
								<input type="text" class="span3" name="Model" disabled/>
							</li>
							<li>
								<span class="span2">Ukuran / CC</span>
								<input type="text" class="span3" name="Ukuran" disabled/>
							</li>
							<li>
								<span class="span2">No. Pabrik</span>
								<input type="text" class="span3" name="Pabrik" disabled/>
							</li>
							<li>
								<span class="span2">No. Mesin</span>
								<input type="text" class="span3" name="NoMesin" disabled/>
							</li>
							<li>
								<span class="span2">No. Polisi</span>
								<input type="text" class="span3" name="NoSeri" disabled/>
							</li>
							<li>
								<span class="span2">No. BPKB</span>
								<input type="text" class="span3" name="NoBPKB" disabled/>
							</li>
							<li>
								<span class="span2">Bahan</span>
								<input type="text" class="span3" name="Material" disabled/>
							</li>
							<li>
								<span class="span2">No. Rangka</span>
								<input type="text" class="span3" name="NoRangka" disabled/>
							</li>
						</ul>
						<ul class="bangunan" style="display:none">
							<li>
								<span class="span2">Kondisi Bangunan</span>
								<select name="kondisi" style="width:155px" disabled>
									<option value="1">Baik</option>
									<option value="2">Rusak Ringan</option>
									<option value="3">Rusak Berat</option>
									<option value="5">Hibah</option>
								</select>
							</li>
							<li>&nbsp;</li>
							<li>
								<span class="span2">Beton / Tidak</span>
								<select name="Beton" style="width:155px" disabled>
									<option value="1">Beton</option>
									<option value="2">Tidak</option>
								</select>
							</li>
							<li>&nbsp;</li>
							<li>
								<span class="span2">Jumlah Lantai</span>
								<input type="text" class="span3" name="JumlahLantai" value="<?=($kontrak[0]['tipeAset'] == 3)? $aset[0]['JumlahLantai'] : ''?>" disabled/>
							</li>
							<li>
								<span class="span2">Luas Lantai (M2)</span>
								<input type="text" class="span3" name="LuasLantai" value="<?=($kontrak[0]['tipeAset'] == 3)? $aset[0]['LuasLantai'] : ''?>" disabled/>
							</li>
							<li>
								<span class="span2">No. Dokumen</span>
								<input type="text" class="span3" name="NoSurat" value="<?=($kontrak[0]['tipeAset'] == 3)? $aset[0]['LuasLantai'] : ''?>" disabled/>
							</li>
							<li>
								<span class="span2">Tgl. Dokumen</span>
								<input type="text" class="span2" placeholder="yyyy-mm-dd" name="tglSurat" id="tglSurat" disabled/>
							</li>
						</ul>
						<ul class="jaringan" style="display:none">
							<li>
								<span class="span2">Kondisi</span>
								<select name="kondisi" style="width:155px" >disabled
									<option value="1">Baik</option>
									<option value="2">Rusak Ringan</option>
									<option value="3">Rusak Berat</option>
									<option value="5">Hibah</option>
								</select>
							</li>
							<li>&nbsp;</li>
							<li>
								<span class="span2">Konstruksi</span>
								<input type="text" class="span3" name="Konstruksi" disabled/>
							</li>
							<li>
								<span class="span2">Panjang (KM)</span>
								<input type="text" class="span2" name="Panjang" disabled/>
							</li>
							<li>
								<span class="span2">Lebar (M)</span>
								<input type="text" class="span2" name="Lebar" disabled/>
							</li>
							<li>
								<span class="span2">Luas (M2)</span>
								<input type="text" class="span2" name="LuasJaringan" disabled/>
							</li>
							<li>
								<span class="span2">No. Dokumen</span>
								<input type="text" class="span3" name="NoDokumen" value="<?=($kontrak[0]['tipeAset'] == 3)? $aset[0]['LuasLantai'] : ''?>" disabled/>
							</li>
							<li>
								<span class="span2">Tgl. Dokumen</span>
								<input type="text" placeholder="yyyy-mm-dd" class="span2" name="tglDokumen" id="tglDokumen" disabled/>
							</li>
						</ul>
						<ul class="asetlain" style="display:none">
							<li>
								<span class="span2">Kondisi</span>
								<select name="kondisi" style="width:155px" disabled>
									<option value="1">Baik</option>
									<option value="2">Rusak Ringan</option>
									<option value="3">Rusak Berat</option>
									<option value="5">Hibah</option>
								</select>
							</li>
							<li>&nbsp;</li>
							<li>
								<span class="span2">Judul</span>
								<input type="text" class="span3" name="Judul" disabled/>
							</li>
							<li>
								<span class="span2">Pengarang</span>
								<input type="text" class="span3" name="Pengarang" disabled/>
							</li>
							<li>
								<span class="span2">Penerbit</span>
								<input type="text" class="span3" name="Penerbit" disabled/>
							</li>
							<li>
								<span class="span2">Spesifikasi</span>
								<input type="text" class="span3" name="Spesifikasi" disabled/>
							</li>
							<li>
								<span class="span2">Asal Daerah</span>
								<input type="text" class="span3" name="AsalDaerah" disabled/>
							</li>
							<li>
								<span class="span2">Bahan</span>
								<input type="text" class="span3" name="Material" disabled/>
							</li>
							<li>
								<span class="span2">Ukuran</span>
								<input type="text" class="span3" name="Ukuran" disabled/>
							</li>
						</ul>
						<ul class="kdp" style="display:none">
							<li>
								<span class="span2">Kondisi Bangunan</span>
								<select name="kondisi" style="width:155px" disabled>
									<option value="1">Baik</option>
									<option value="2">Rusak Ringan</option>
									<option value="3">Rusak Berat</option>
									<option value="5">Hibah</option>
								</select>
							</li>
							<li>&nbsp;</li>
							<li>
								<span class="span2">Beton / Tidak</span>
								<select name="Beton" style="width:155px" disabled>
									<option value="1">Beton</option>
									<option value="2">Tidak</option>
								</select>
							</li>
							<li>&nbsp;</li>
							<li>
								<span class="span2">Jumlah Lantai</span>
								<input type="text" class="span3" name="JumlahLantai" disabled/>
							</li>
							<li>
								<span class="span2">Luas Lantai</span>
								<input type="text" class="span3" name="LuasLantai" disabled/>
							</li>
						</ul>


					</div>
					<!-- hidden -->
					<input type="hidden" name="UserNm" value="<?=$_SESSION['ses_uoperatorid']?>">
					<input type="hidden" name="TipeAset" id="TipeAset" value="">
		<div style="height:5px;width:100%;clear:both"></div>		
		<ul>
			<li>
				<span class="span2">
				  <button class="btn" type="reset">Reset</button>
				  <button type="submit" id="submit" class="btn btn-primary">Simpan</button></span>
			</li>
		</ul>
					
			
		</form>
                            <!-- Modal -->
<div id="myModal" class="modal fade" role="dialog" style=" width: 90%;max-width:900px;left:40%">
  <div class="modal-dialog modal-lg" >

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Data Tanah <p id="hasil_pilihan_tanah"></p></h4>
      </div>
      <div class="modal-body">
          <table cellpadding="0" cellspacing="0" border="0" class="display table-checkable" id="daftar_tanah_bangunan">
				<thead>
					<tr>
						
						<th>Pilihan</th>
                                                <th>No Register</th>
						<th>Kode Kelompok</th>
						<th>Satker</th>
						<th>Tgl Perolehan</th>
						<th>Nilai Perolehan</th>
						<th>Luas Total</th>
					</tr>
				</thead>
				<tbody>		
							 
				<tr>
                    <td colspan="10">Data Tidak di temukkan</td>
               	</tr>
				</tbody>
				<tfoot>
					<tr>
						<th>&nbsp;</th>
						<th>&nbsp;</th>
					</tr>
				</tfoot>
			</table>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>
		</div>  
			    
		</section> 
		     
	</section>
	
<?php
	include"$path/footer.php";
?>

<script type="text/javascript">
       
	$(document).on('change','#kodeKelompok', function(){

		var kode = $('#kodeKelompok').val();
		var gol = kode.split(".");

		if(gol[0] == '01')
		{
                         $("#tanah_bangunan,#detail_tanah_bangunan").hide('');
			$("#TipeAset").val('A');
			$(".mesin,.bangunan,.jaringan,.asetlain,.kdp").hide('');
			$(".mesin li > input,.bangunan li > input,.jaringan li > input,.asetlain li > input,.kdp li > input").attr('disabled','disabled');
			$(".mesin li > select,.bangunan li > select,.jaringan li > select,.asetlain li > select,.kdp li > select").attr('disabled','disabled');
			$(".tanah li > select,.tanah li > input").removeAttr('disabled');
			$(".tanah").show('');
		} else if(gol[0] == '02')
		{
                         $("#tanah_bangunan,#detail_tanah_bangunan").hide('');
			$("#TipeAset").val('B');
			$(".tanah,.bangunan,.jaringan,.asetlain,.kdp").hide('');
			$(".tanah li > input,.bangunan li > input,.jaringan li > input,.asetlain li > input,.kdp li > input").attr('disabled','disabled');
			$(".tanah li > select,.bangunan li > select,.jaringan li > select,.asetlain li > select,.kdp li > select").attr('disabled','disabled');
			$(".mesin li > input,.mesin li > select").removeAttr('disabled');
			$(".mesin").show('');
		} else if(gol[0] == '03')
		{
                        $("#tanah_bangunan,#detail_tanah_bangunan").show('');
			$("#TipeAset").val('C');
			$(".tanah,.mesin,.jaringan,.asetlain,.kdp").hide('');
			$(".tanah li > input,.mesin li > input,.jaringan li > input,.asetlain li > input,.kdp li > input").attr('disabled','disabled');
			$(".tanah li > select,.mesin li > select,.jaringan li > select,.asetlain li > select,.kdp li > select").attr('disabled','disabled');
			$(".bangunan li > input,.bangunan li > select").removeAttr('disabled');
			$(".bangunan").show('');
		} else if(gol[0] == '04')
		{
			$("#TipeAset").val('D');
                         $("#tanah_bangunan,#detail_tanah_bangunan").hide('');
			$(".tanah,.mesin,.bangunan,.asetlain,.kdp").hide('');
			$(".tanah li > input,.mesin li > input,.bangunan li > input,.asetlain li > input,.kdp li > input").attr('disabled','disabled');
			$(".tanah li > select,.mesin li > select,.bangunan li > select,.asetlain li > select,.kdp li > select").attr('disabled','disabled');
			$(".jaringan li > input,.jaringan li > select").removeAttr('disabled');
			$(".jaringan").show('');
		} else if(gol[0] == '05')
		{
			$("#TipeAset").val('E');
			$(".tanah,.mesin,.bangunan,.jaringan,.kdp").hide('');
                         $("#tanah_bangunan,#detail_tanah_bangunan").hide('');
			$(".tanah li > input,.mesin li > input,.bangunan li > input,.jaringan li > input,.kdp li > input").attr('disabled','disabled');
			$(".tanah li > select,.mesin li > select,.bangunan li > select,.jaringan li > select,.kdp li > select").attr('disabled','disabled');
			$(".asetlain li > input,.asetlain li > select").removeAttr('disabled');
			$(".asetlain").show('');
		} else if(gol[0] == '06')
		{
			$("#TipeAset").val('F');
			$(".tanah,.mesin,.bangunan,.asetlain,.jaringan").hide('');
                         $("#tanah_bangunan,#detail_tanah_bangunan").hide('');
			$(".tanah li > input,.mesin li > input,.bangunan li > input,.asetlain li > input,.jaringan li > input").attr('disabled','disabled');
			$(".tanah li > select,.mesin li > select,.bangunan li > select,.asetlain li > select,.jaringan li > select").attr('disabled','disabled');
			$(".kdp li > input,.kdp li > select").removeAttr('disabled');
			$(".kdp").show('');
		} else {
			$("#TipeAset").val('G');
			$(".tanah,.mesin,.bangunan,.asetlain,.jaringan,.kdp").hide('');
                         $("#tanah_bangunan,#detail_tanah_bangunan").hide('');
			$(".tanah li > input,.mesin li > input,.bangunan li > input,.asetlain li > input,.jaringan li > input,.kdp li > input").attr('disabled','disabled');
			$(".tanah li > select,.mesin li > select,.bangunan li > select,.asetlain li > select,.jaringan li > select,.kdp li > select").attr('disabled','disabled');
		}			
		
	})

	$(document).on('submit', function(){
		var perolehan = $("#total").val();
		var total = $("#totalRB").val();
		var spk = $("#spk").val();
		var str = parseInt(spk.replace(/[^0-9\.]+/g, ""));
		var rb = parseInt(total.replace(/[^0-9\.]+/g, ""));

		var diff = parseInt(perolehan) + parseInt(rb);

		if(diff > str) {
			alert("Total rincian barang melebihi nilai SPK");
			return false;	
		}
        var kode = $('#kodeKelompok').val();
        var gol = kode.split(".");
        if (gol[0] == "02" && perolehan < 1000000) {
            alert("Maaf Nilai Perolehan Mesin kurang dari batas kapitalisasi 1.000.000 sehingga akan menjadi ektrakom");

        } else if (gol[0] == "03" && perolehan < 25000000) {
            alert("Maaf Nilai Perolehan Banguna kurang dari batas kapitalisasi 25.000.000 sehingga akan menjadi ektrakom");

        }
	})

	function totalHrg(){
		var jml = $("#jumlah").val();
		var hrgSatuan = $("#hrgSatuan").val();
		var total = jml*hrgSatuan;
		$("#total").val(total);
		var format = total.toFixed(2);
		//var format2 = numberWithCommas(format);
		var fix = total.toLocaleString(['ban', 'id']);
		var ext = "Rp ";
		var format = ext.concat(fix); 
		$("#tes").val(format);
	}

	/*function numberWithCommas(x) {
		console.log("here");
    	return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
	}
	function numberWithCommas(x) {
    var parts = x.toString().split(".");
    parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ",");
    return parts.join(".");
	}*/
</script>

<script>
            
                    $.fn.dataTableExt.oApi.fnReloadAjax = function(oSettings, sNewSource, fnCallback, bStandingRedraw)
                    {
                         // DataTables 1.10 compatibility - if 1.10 then versionCheck exists.
                         // 1.10s API has ajax reloading built in, so we use those abilities
                         // directly.
                         if ($.fn.dataTable.versionCheck) {
                              var api = new $.fn.dataTable.Api(oSettings);

                              if (sNewSource) {
                                   api.ajax.url(sNewSource).load(fnCallback, !bStandingRedraw);
                              }
                              else {
                                   api.ajax.reload(fnCallback, !bStandingRedraw);
                              }
                              return;
                         }

                         if (sNewSource !== undefined && sNewSource !== null) {
                              oSettings.sAjaxSource = sNewSource;
                         }

                         // Server-side processing should just call fnDraw
                         if (oSettings.oFeatures.bServerSide) {
                              this.fnDraw();
                              return;
                         }

                         this.oApi._fnProcessingDisplay(oSettings, true);
                         var that = this;
                         var iStart = oSettings._iDisplayStart;
                         var aData = [];

                         this.oApi._fnServerParams(oSettings, aData);

                         oSettings.fnServerData.call(oSettings.oInstance, oSettings.sAjaxSource, aData, function(json) {
                              /* Clear the old information from the table */
                              that.oApi._fnClearTable(oSettings);

                              /* Got the data - add it to the table */
                              var aData = (oSettings.sAjaxDataProp !== "") ?
                                      that.oApi._fnGetObjectDataFn(oSettings.sAjaxDataProp)(json) : json;

                              for (var i = 0; i < aData.length; i++)
                              {
                                   that.oApi._fnAddData(oSettings, aData[i]);
                              }

                              oSettings.aiDisplay = oSettings.aiDisplayMaster.slice();

                              that.fnDraw();

                              if (bStandingRedraw === true)
                              {
                                   oSettings._iDisplayStart = iStart;
                                   that.oApi._fnCalculateEnd(oSettings);
                                   that.fnDraw(false);
                              }

                              that.oApi._fnProcessingDisplay(oSettings, false);

                              /* Callback user function - for event handlers etc */
                              if (typeof fnCallback == 'function' && fnCallback !== null)
                              {
                                   fnCallback(oSettings);
                              }
                         }, oSettings);
                    };
                    var oTable;
                    $(document).on('click','#load-data-tanah',function() {
                         var satker = $('#kodeSatker').val();
                                var kelompok = $('#kodeKelompok').val();
                         var data_satker=$('#data_satker').val();
                         if(data_satker!="")
                         {
                         	oTable.fnReloadAjax("<?=$url_rewrite?>/api_list/api_bangunan_tanah.php?kodeSatker="+satker+"&kodeKelompok="+kelompok);
                         }else{
	                         oTable = $('#daftar_tanah_bangunan').dataTable({
	                              "aoColumns": [
	                                   {"bSortable": false},
	                                   {"bSortable": true},
	                                   {"bSortable": true},
	                                   {"bSortable": true},
	                                   {"bSortable": true},
	                                   {"bSortable": true},
	                                   
	                                   {"bSortable": true}],
	 
	                              "bProcessing": true,
	                              "bServerSide": true,
	                              "sAjaxSource": "<?=$url_rewrite?>/api_list/api_bangunan_tanah.php?kodeSatker="+satker

	                         });
	                         $('#data_satker').val(satker);
	                      }


                      
                    });
                   function set_tanah(id){
                   		var hasil=$("#nilai_tanah_id"+id).val();
                   		var final_hasil=hasil.split("|");
                   		//value=\"{$aRow['tanah_id']}|$kodeKelompok|$Uraian|$noRegister|$LuasTotal|$Tahun\" 
                   		var tanah_id=final_hasil[0];
                   		var kodeKelompok=final_hasil[1];
                   		var Uraian=final_hasil[2];
                   		var noRegister=final_hasil[3];
                   		var LuasTotal=final_hasil[4];
                   		var Tahun=final_hasil[5];
                   		$("#tanah_id").val(tanah_id);
                   		$("#kelompok_tanah").val(kodeKelompok);
                   		$("#luas_total").val(LuasTotal);
                   		$("#myModal").modal('hide');





	

                   }

                  
               </script>