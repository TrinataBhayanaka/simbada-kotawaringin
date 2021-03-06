<!DOCTYPE php PUBLIC "-//W3C//DTD php 4.01//EN" "http://www.w3.org/TR/php4/strict.dtd">
<?php
include "../../config/config.php";
?>
<php>
    <head>
        <title>Help SIMBADA</title>
        <meta http-equiv="Content-Type" content="text/php; charset=utf-8" />
                <title><?=$title?></title>
                <!-- include css file -->
                
                <link rel="stylesheet" href="../../css/simbada.css" type="text/css" media="screen" />
                <link rel="stylesheet" href="../../css/jquery-ui.css" type="text/css">
                <link rel="stylesheet" href="../../css/example.css" TYPE="text/css" MEDIA="screen">
    </head>
    <body>
        <div>
            <div id="frame_header">
                <div id="header"></div>
            </div>
            <div id="list_header"></div>
            <div id="kiri">
            <div id="frame_kiri">
               <?php include '../menu_samping.php';?>
            </div>
        </div>
        
        <div id="tengah">	
            <div id="frame_tengah">
                <div id="" style="padding: 10px">
                    <fieldset style="padding: 5px;">
                    <label style='font-size:18px'>Help &raquo;</label>
					<br><br>
					<p style='font-size:17px' >Sub Menu Daftar Usulan Pemanfaatan</p>
					<br>
					<p style='font-size:14px'>Pada Sub Menu daftar usulan pemanfaatan ini mempunyai alur operasi sebagai berikut :</p>
					<br>
					<p align="center"><img  src="../pemanfaatan/images/daftar_usulan.jpg" width="600px" height="75px"/></p><br>
					<p style="padding: 3px">Sub menu ini digunakan untuk membuat usulan pemanfaatan atas aset yang menganggur maupun aset yang diperuntukkan untuk dioptimalkan dalam pemanfaatan aset. Langkahnya adalah sebagai berikut&nbsp;:</p>
					<ol style="padding: 18px"><li>Klik sub menu Daftar Usulan Pemanfaatan.<br><p align="center"><img  src="../pemanfaatan/images/pm1.jpg"></p><br>
					<li>SIMBADA akan menampilkan halaman seleksi pencarian data.<ul style="padding: 12px"><li>Isi ID Aset (System ID) dengan nomor ID Aset.</li>
					<li>Isi Nama Aset dengan teks nama aset atau bagian dari nama aset yang ingin ditampilkan.</li>
					<li>Isi Nomor Kontrak dengan nomor kontrak aset yang diinginkan.</li>
					<li>Pilih SKPD dari tabel SKPD sesuai cara yang sudah dijelaskan sebelumnya, untuk menampilkan data berdasarkan SKPD.</li>
					<li>Bersihkan Filter untuk membersihkan dari seleksi sebelumnya.<br><br><p><img  src="../pemanfaatan/images/pm7.jpg"></p></li>
					</ul></li>
					<li>Klik Tampilkan Data untuk menampilkan data berdasarkan seleksi. Bila tanpa seleksi atau filter dikosongkan, maka SIMBADA akan menampilkan semua data.<br><br><p><img  src="../pemanfaatan/images/pm7.jpg"></p></li>
					<li>Berikan tanda centang pada data aset yang ingin di pilih dan klik tombol Usul Pemanfaatan untuk melanjutkan proses.<br><br><p><img  src="../pemanfaatan/images/pm9.jpg"></p><br></li>
					<li>Klik Usulan Pemanfaatan untuk melanjutkan atau klik Batal untuk membatalkan proses. Setelah lanjut SIMBADA akan menampilkan daftar aset yang akan diusulkan.<br><br><p><img  src="../pemanfaatan/images/pm10.jpg"></p><br></li>
					<li>Klik Cetak Daftar Usulan Pemanfaatan untuk menampilkan Daftar Usulan Pemanfaatan dalam format PDF dan klik Kembali ke Menu Utama untuk kembali kehalaman utama.</li>
					</li></ol>
					<p align="center"><input type="button" value="<< Previous" onClick="window.location.href='penetapan_bmd_menganggur.php'"> &nbsp;<input type='BUTTON' value='TOC' onClick="window.location.href='../'">&nbsp;  <input type='BUTTON' value='Next >>' onClick="window.location.href='penetapan_pemanfaatan.php'"></p>
                    </fieldset>
                </div>
            </div>
        </div>
        
        </div>
            <div id="footer">Sistem Informasi Barang Daerah ver. 0.x.x <br />
            Powered by BBSDM Team 2012
            </div>
        </div>
    </body>
</php>
