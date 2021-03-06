<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<?php
include "../../config/config.php";
?>
<html>
    <head>
        <title>Help SIMBADA</title>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
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
        
        <div id="tengah1">	
            <div id="frame_tengah1">
                <div id="frame_gudang">
                    <div id='topright'><label>Help &raquo;</label><label> Page Admin &raquo;</label><label> Menu Admin</label></div>
                        <div id='bottomright' style='border:0px'>
					<h1 style="font-weight:bold;font-size:15px">PAGE ADMIN</h1>
					<br>
                    <ul style="padding-left:15px">
						<li style="padding-left:5px; font-size:13px; font-weight:bold">Menu Admin</li>
						<br>
						<p style="padding-left:5px">Pada bagian menu ini, terdiri dari 2 fungsi hak akses yaitu Tampilkan dan Harus Login.</p>
						<br>
						<div style="text-align: center;"><img src="../image/page_admin/menu_admin.png" width="80%" style="padding-left:10px"></div>
						<br><br><br>
						<p style="padding-left:5px"><span style="font-weight: bold;">Tampilkan</span> : fungsi ini digunakan untuk menampilkan menu SIMBADA baik disaat sudah login maupun disaat belum login, jadi semua user hak aksesnya akan berpengaruh dengan fungsi ini.</p>
						<br>
						<div style="text-align: center;"><img src="../image/page_admin/tampilkan.png" width="80%" style="padding-left:15px"></div>
						<br><br><br>
						<p style="padding-left:5px"><span style="font-weight: bold;">Harus Login</span> : fungsi ini digunakan hanya untuk menampilkan menu SIMBADA disaat SIMBADA belum melakukan login.</p>
						<br>
						<div style="text-align: center;"><img src="../image/page_admin/harus_login.png" width="70%" style="padding-left:15px"></div>
						<br><br>
					</ul>	
						
					<br>
					<br>
					<br>
					<br>
					<br>
						<p style="text-align: center;">
							<a href="index.php" style="color: rgb(0, 0, 255); font-size: 18px;">Prev</a> &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; 
							<a href="kelompok_jabatan.php" style="color: rgb(0, 0, 255); font-size: 18px;">Next</a></p>
						</div>
						</div>
                    </div>
                </div>
            </div>
        </div>
        
        </div>
            <div id="footer">Sistem Informasi Barang Daerah ver. 0.x.x <br />
            Powered by BBSDM Team 2012
            </div>
        </div>
    </body>
</html>
