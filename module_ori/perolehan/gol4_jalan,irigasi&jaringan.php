
<table border="0" cellspacing="6">	
        <tr>
                <td>Konstruksi</td>
        </tr>
        <tr>
                <td>
                        <input type="text" name="p_jaringan_konstruksi" value=" "size=70 >
                </td>
        </tr>
</table>
<table border="0" cellspacing="6">
<script src="accounting.js"></script>
	<script type="text/javascript">
		function format_nilaiPanjang(){
		var get_nilai = document.getElementById('p_jaringan_panjang1');
		document.getElementById('p_jaringan_panjang').value=get_nilai.value;
		nilai = accounting.formatMoney(get_nilai.value, "", 0, ".", ",");
		get_nilai.value=nilai;
		
	}
		function format_nilaiLebar(){
		var get_nilai = document.getElementById('p_jaringan_lebar1');
		document.getElementById('p_jaringan_lebar').value=get_nilai.value;
		nilai = accounting.formatMoney(get_nilai.value, "", 0, ".", ",");
		get_nilai.value=nilai;
		
	}
		function format_nilaiLuas(){
		var get_nilai = document.getElementById('p_jaringan_luas1');
		document.getElementById('p_jaringan_luas').value=get_nilai.value;
		nilai = accounting.formatMoney(get_nilai.value, "", 0, ".", ",");
		get_nilai.value=nilai;
		
	}
</script>
        <tr>
                <td>Panjang</td>
                <td><input type="text" name="p_jaringan_panjang1" id="p_jaringan_panjang1" size='5' value="<?php echo number_format($dataArr->nilaiPanjang,0,',','.')?>"required=" required" onchange="return format_nilaiPanjang();";>m</td>
				<td><input type="hidden" name="p_jaringan_panjang" id="p_jaringan_panjang" size='5' value="<?=$dataArr->nilaiPanjang?>" ></td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>Lebar</td>
                <td><input type="text" name="p_jaringan_lebar1" id="p_jaringan_lebar1" size='5' value="<?php echo number_format($dataArr->nilaiLebar,0,',','.')?>"required=" required" onchange="return format_nilaiLebar();";>m</td>
				<td><input type="hidden" name="p_jaringan_lebar" id="p_jaringan_lebar" size='5' value="<?=$dataArr->nilaiLebar?>"></td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>Luas</td>
                <td><input type="text" name="p_jaringan_luas1" id="p_jaringan_luas1" size='5'  value="<?php echo number_format($dataArr->nilaiLuas,0,',','.')?>"required=" required" onchange="return format_nilaiLuas();";>m2</td>
				<td><input type="hidden" name="p_jaringan_luas" id="p_jaringan_luas" size='5'  value="<?=$dataArr->nilaiLuas?>"></td>
        </tr>
</table>
<table border="0" cellspacing="6">
        <tr>
                <td>No. Dokumen</td>
                <td>Tanggal Dokumen</td>
        </tr>
        <tr>
                <td>
                        <input type="text" name="p_jaringan_nodokumen" value="" >
                </td>
                <td>
                        <input id="tanggal3"type="text" name="p_jaringan_tgldookumen" value="" >
                </td>
        </tr>
</table>
<table border="0" cellspacing="6">
        <tr>
                <td>Tanggal Pemakaian</td>
        </tr>
        <tr>
                <td>
                <input id="tanggal2" type="text" name="p_jaringan_tglpemakaian" value="" >
                </td>
        </tr>
</table>
<table border="0" cellspacing="6">	
        <tr>
                <td>Status Tanah</td>
        </tr>
        <tr>
                <td>
                        <select name="p_jaringan_statustanah">
                                <option value="10" >Tanah Pemda</option>
                                <option value="20" >Tanah Negara</option>
                                <option value="30" >Tanah Ulayat/Adat</option>
                                <option value="41" >Tanah Hak Guna Bangunan</option>
                                <option value="42" >Tanah Pakai</option>
                                <option value="43" >Tanah Pengelolaan</option>
                                <option value="-" >Tanah Hak Lainnya</option>
                        </select>
                </td> 
        </tr>
</table>
<table border="0" cellspacing="6">		
        <tr>
                <td>Pilih Aset Tanah</td>
        </tr>
        <tr>
                <td>
                <input type="text" name="lda_kelompok" id="p_gjal_pilihaset_tanah" style="width:450px;" readonly="readonly" value="">
                        <input type="button" name="idbtnlookupasettanah" id="idbtnlookupasettanah" value="Pilih" onclick = "showSpoiler(this);">
                        <div class="inner" style="display:none;">
                                <style>
                                        .tabel th {
                                                background-color: #eeeeee;
                                                border: 1px solid #dddddd;
                                        }
                                        .tabel td {
                                                border: 1px solid #dddddd;
                                        }
                                </style>
                                <?php
                                            $alamat_aset_tanah="";
                                        $alamat_search_aset_tanah="";
                                        js_radioasetanah($alamat_aset_tanah, $alamat_search_aset_tanah,"p_gjal_pilihaset_tanah","aset_tanah_id_gol4",'aset_tanahgol4','aset_tanahgol4','p_gjal_nomorkode_tanah',"$url_rewrite/module/perolehan/api_aset_tanah.php?aset=");
                                        $style="style=\"width:525px; height:220px; overflow:auto; border: 1px solid #dddddd;\"";
                                        radioasetanah($style,"aset_tanah_id_gol4",'aset_tanahgol4','aset_tanahgol4');

                                ?>
                        </div>
                </td>
        </tr>
</table>
<table border="0" cellspacing="6">		
        <tr>
                <td>Nomor Kode  Tanah</td>
        </tr>
        <tr>
                <td>
                     <input type="text" name="p_jaringan_nomorkode_tanah" id="p_gjal_nomorkode_tanah" style="width:450px;" readonly="readonly" value="">
                        <input type="button" name="idbtnlookupkelompok" id="idbtnlookupkelompok" value="Pilih" onclick = "showSpoiler(this);">
                        <div class="inner" style="display:none;">
                                <style>
                                        .tabel th {
                                                background-color: #eeeeee;
                                                border: 1px solid #dddddd;
                                        }
                                        .tabel td {
                                                border: 1px solid #dddddd;
                                        }
                                </style>
                                <?php
                                        //include "$path/function/dropdown/function_kelompok.php";
                                        $alamat_simpul_kelompok="$url_rewrite/function/dropdown/simpul_kelompok.php";
                                        $alamat_search_kelompok="$url_rewrite/function/dropdown/search_kelompok.php";
                                        js_radiokelompoktanah($alamat_simpul_kelompok, $alamat_search_kelompok,"p_gjal_nomorkode_tanah","kelompok_tanah_idgol4",'kelompokgol4','ldakelompokfiltergol4');
                                        $style="style=\"width:525px; height:220px; overflow:auto; border: 1px solid #dddddd;\"";
                                        radiokelompoktanah($style,"kelompok_tanah_idgol4",'kelompokgol4','ldakelompokfiltergol4');
                                ?>
                        </div>

                </td>
        </tr>
</table>

