<?php
include "../../config/config.php";
$menu_id = 1;
            $SessionUser = $SESSION->get_session_user();
            ($SessionUser['ses_uid']!='') ? $Session = $SessionUser : $Session = $SESSION->get_session(array('title'=>'GuestMenu', 'ses_name'=>'menu_without_login')); 
            $USERAUTH->FrontEnd_check_akses_menu($menu_id, $Session);
?>
<?php
	include"$path/meta.php";
	include"$path/header.php";
	include"$path/menu.php";

	function cek_kelompok( $kodeKelompok ) {
	$cek=abs( $kodeKelompok );
	if ( $cek==1 ) {
		$tabel="tanah";
	}else if ( $cek==2 ) {
			$tabel="mesin";
		}else if ( $cek==3 ) {
			$tabel="bangunan";
		}else if ( $cek==4 ) {
			$tabel="jaringan";
		}else if ( $cek==5 ) {
			$tabel="asetlain";
		}else if ( $cek==6 ) {
			$tabel="kdp";
		}else if ( $cek==7 ) {
			$tabel="aset_07";
		}else if ( $cek==8 ) {
			$tabel="aset_07";
		}
	return $tabel;


}

	//SQL Sementara
	$idKontrak = $_GET['id'];
	$sql = mysql_query("SELECT * FROM kontrak WHERE id='{$idKontrak}'");
		while ($dataKontrak = mysql_fetch_assoc($sql)){
				$kontrak = $dataKontrak;
			}

	//getdata
	if($kontrak['tipeAset'] != 1)
	{

		$sql = "SELECT * FROM kapitalisasi WHERE idKontrak = '{$_GET['id']}'";
		$kap = $DBVAR->fetch($sql);

		$sql = mysql_query("SELECT * FROM {$kap['tipeAset']} WHERE Aset_ID = '{$kap['Aset_ID']}' AND noRegister = '{$kap['noRegister']}' LIMIT 1");
		while ($dataAset = mysql_fetch_assoc($sql)){
            $aset[] = $dataAset;
        }
        if($aset){
	        foreach ($aset as $key => $value) {
	            $sqlnmBrg = mysql_query("SELECT Uraian FROM kelompok WHERE Kode = '{$value['kodeKelompok']}' LIMIT 1");
	            while ($uraian = mysql_fetch_array($sqlnmBrg)){
	                    $tmp = $uraian;
	                    $aset[$key]['uraian'] = $tmp['Uraian'];
	                }
	        }
	    }
	}
	$RKsql = mysql_query("SELECT Aset_ID, noRegister, Satuan, kodeLokasi, kodeKelompok, NilaiPerolehan FROM aset WHERE noKontrak = '{$kontrak['noKontrak']}' AND ((StatusValidasi != 9 and StatusValidasi != 13) OR StatusValidasi IS NULL) AND ((Status_Validasi_Barang != 9 and Status_Validasi_Barang != 13) OR Status_Validasi_Barang IS NULL)");
	while ($dataRKontrak = mysql_fetch_assoc($RKsql)){
				$rKontrak[] = $dataRKontrak;
			}
	foreach ($rKontrak as $key => $value) {
		$sqlnmBrg = mysql_query("SELECT Uraian FROM kelompok WHERE Kode = '{$value['kodeKelompok']}' LIMIT 1");
		while ($uraian = mysql_fetch_assoc($sqlnmBrg)){
				$tmp = $uraian;
				$rKontrak[$key]['uraian'] = $tmp['Uraian'];
			}
		$sql = mysql_query("SELECT COUNT(*) AS rdist FROM transferaset WHERE kodeKelompok = '{$value['kodeKelompok']}' AND kodeLokasi = '{$value['kodeLokasi']}'");
		while ($sumpost = mysql_fetch_assoc($sql)){
					$distchek[] = $sumpost;
				}	
	}

	$sql = mysql_query("SELECT SUM(nilai) as total FROM sp2d WHERE idKontrak='{$idKontrak}' AND type = '2'");
		while ($dataSP2D = mysql_fetch_assoc($sql)){
				$sumsp2d = $dataSP2D;
			}

	//sum total 
	$sqlsum = mysql_query("SELECT SUM(NilaiPerolehan) as total FROM aset WHERE noKontrak = '{$kontrak['noKontrak']}' AND ((StatusValidasi != 9 and StatusValidasi != 13) OR StatusValidasi IS NULL) AND ((Status_Validasi_Barang != 9 and Status_Validasi_Barang != 13) OR Status_Validasi_Barang IS NULL)");
	while ($sum = mysql_fetch_assoc($sqlsum)){
				$sumTotal = $sum;
			}
	// pr($unpost);

	//unposting
	$sql = mysql_query("SELECT COUNT(*) AS dist FROM aset WHERE noKontrak = '{$kontrak['noKontrak']}' AND Status_Validasi_Barang = 1");
	while ($sumpost = mysql_fetch_assoc($sql)){
				$unpost = $sumpost;
			}

	$sql = mysql_query("SELECT COUNT(*) AS kap FROM kapitalisasi WHERE noKontrakAset = '{$kontrak['noKontrak']}'");	
	if ($sql){
		while ($sumpost = mysql_fetch_assoc($sql)){
				$kapchek = $sumpost;
			}
	}
	foreach ($distchek as $key => $value) {
		if(in_array(1, $value)){
			$countdist = $countdist + 1;
		} 
	}
	// pr($countdist);
	//end SQL
?>
	
	<section id="main">
		<ul class="breadcrumb">
			  <li><a href="#"><i class="fa fa-home fa-2x"></i>  Home</a> <span class="divider"><b>&raquo;</b></span></li>
			  <li><a href="#">Perolehan Aset</a><span class="divider"><b>&raquo;</b></span></li>
			  <li><a href="#">Kontrak</a><span class="divider"><b>&raquo;</b></span></li>
			  <li class="active">Posting</li>
			  <?php SignInOut();?>
			</ul>
			<div class="breadcrumb">
				<div class="title">Posting</div>
				<div class="subtitle">Daftar Kontrak</div>
			</div>	

			<div class="grey-container shortcut-wrapper">
				<a class="shortcut-link" href="<?=$url_rewrite?>/module/perolehan/kontrak_simbada.php">
					<span class="fa-stack fa-lg">
				      <i class="fa fa-circle fa-stack-2x"></i>
				      <i class="fa fa-inverse fa-stack-1x">1</i>
				    </span>
					<span class="text">Kontrak</span>
				</a>
				<a class="shortcut-link" href="<?=$url_rewrite?>/module/perolehan/kontrak_rincian.php">
					<span class="fa-stack fa-lg">
				      <i class="fa fa-circle fa-stack-2x"></i>
				      <i class="fa fa-inverse fa-stack-1x">2</i>
				    </span>
					<span class="text">Rincian Barang</span>
				</a>
				<a class="shortcut-link" href="<?=$url_rewrite?>/module/perolehan/kontrak_sp2d.php">
					<span class="fa-stack fa-lg">
				      <i class="fa fa-circle fa-stack-2x"></i>
				      <i class="fa fa-inverse fa-stack-1x">3</i>
				    </span>
					<span class="text">SP2D</span>
				</a>
				<a class="shortcut-link" href="<?=$url_rewrite?>/module/perolehan/kontrak_penunjang.php">
					<span class="fa-stack fa-lg">
				      <i class="fa fa-circle fa-stack-2x"></i>
				      <i class="fa fa-inverse fa-stack-1x">4</i>
				    </span>
					<span class="text">Penunjang</span>
				</a>
				<a class="shortcut-link" href="<?=$url_rewrite?>/module/perolehan/kontrak_posting.php">
					<span class="fa-stack fa-lg">
				      <i class="fa fa-circle fa-stack-2x"></i>
				      <i class="fa fa-inverse fa-stack-1x">5</i>
				    </span>
					<span class="text">Posting</span>
				</a>
			</div>		
		
		<section class="formLegend">
			<div style="height:5px;width:100%;clear:both"></div>
			<div class="detailLeft">
						
						<ul>
							<li>
								<span class="labelInfo">No. Kontrak</span>
								<input type="text" value="<?=$kontrak['noKontrak']?>" disabled/>
							</li>
							<li>
								<span class="labelInfo">Tgl. Kontrak</span>
								<input type="text" value="<?=$kontrak['tglKontrak']?>" disabled/>
							</li>
							<li>
								<span class="labelInfo">Nilai SPK</span>
								<input type="text" value="<?=number_format($kontrak['nilai'],2)?>" disabled/>
							</li>
						</ul>
							
					</div>
			<div class="detailRight">
						
						<ul>
							<li>
								<span  class="labelInfo">Total Rincian Barang</span>
								<?php if($kontrak['tipeAset'] == 1 || $kontrak['tipeAset'] == 2){ ?>
								<input type="text" value="<?=isset($sumTotal) ? number_format($sumTotal['total']-$sumsp2d['total'],2) : '0'?>" disabled/>
								<?php } else { ?>
								<input type="text" value="<?=isset($sumTotal) ? number_format($sumTotal['total']-$sumsp2d['total']-$aset[0]['NilaiPerolehan'],2) : '0'?>" disabled/>
								<?php } ?>
							</li>
							<li>
								<span  class="labelInfo">Total Penunjang</span>
								<input type="text" value="<?=isset($sumsp2d) ? number_format($sumsp2d['total'],2) : '0'?>" disabled/>
							</li>
							<li>
								<span class="labelInfo">Total Perolehan</span>
								<?php if($kontrak['tipeAset'] == 1 || $kontrak['tipeAset'] == 2){ ?>
								<input type="text" value="<?=number_format($sumTotal['total']-$sumsp2d['total']+$sumsp2d['total'],2)?>" disabled/>
								<?php } else { ?>
								<input type="text" value="<?=number_format($sumTotal['total']-$sumsp2d['total']+$sumsp2d['total']-$aset[0]['NilaiPerolehan'],2)?>" disabled/>
								<?php } ?>
							</li>
						</ul>
							
					</div>

			<div style="height:5px;width:100%;clear:both"></div>
			
			<?php
				if($kontrak['tipeAset'] != 1)
				{
	        ?>
	        		<div class="search-options clearfix">
	        			<strong style="margin-right:20px;"><?=($kontrak['tipeAset'] == 2)? 'Kapitalisasi Aset' : 'Rubah Status'?></strong>
	        			<hr style="padding:0px;margin:0px">
						<table border='0' width="100%" style="font-size:12">
							<tr>
								<th>Kode Kelompok</th>
								<th>Nama Barang</th>
								<th>Kode Satker</th>
								<th>Kode Lokasi</th>
								<th>NoReg</th>
								<th>Nilai</th>
								<th>Nilai Setelah Kapitalisasi</th>
							</tr>
							<?php
							//pr($aset[0]);
							$status_kontrak=$kontrak['n_status'];
							$kodeKel=$aset[0]['kodeKelompok'];
							$tmp=explode( ".", $kodeKel );
							$kelompok=$tmp[0];
							$tabel=cek_kelompok( $kelompok );
							if($status_kontrak=="0"){
								$nilai_sblm=$aset[0]['NilaiPerolehan'];
								$nilai_sesudah=$aset[0]['NilaiPerolehan']+$kontrak['nilai'];
							}else{
								if($kontrak['tipeAset'] == 2){
									$sql = mysql_query("SELECT NilaiPerolehan,NilaiPerolehan_Awal
									   FROM log_$tabel WHERE aset_id='{$aset[0]['Aset_ID']}' 
									   	AND kd_riwayat = '2' and TglPerubahan='{$kontrak['tglKontrak']}'");
									while ($data_kap = mysql_fetch_assoc($sql)){
											$nilai_sesudah = $data_kap['NilaiPerolehan'];
											$nilai_sblm = $data_kap['NilaiPerolehan_Awal'];
										}

								}
							}
							//pr($kontrak)
							?>
							<tr>
								<td align="center"><?=$aset[0]['kodeKelompok']?></td>
								<td align="center"><?=$aset[0]['uraian']?></td>
								<td align="center"><?=$aset[0]['kodeSatker']?></td>
								<td align="center"><?=$aset[0]['kodeLokasi']?></td>
								<td align="center"><?=$aset[0]['noRegister']?>  </td>
								<td align="center">
									<?php
										if($kontrak['tipeAset'] == 3) {
											echo number_format($sumTotal['total']-$kontrak['nilai'],2);
										} else {
											//echo number_format($aset[0]['NilaiPerolehan']-$sumTotal['total'],2);
											echo number_format($nilai_sblm,2);
										}
									?>
								</td>
								<td align="center">
								<?php
									if($kontrak['tipeAset'] == 3) {
										echo number_format($sumTotal['total'],2);
									} else {
										//echo number_format($aset[0]['NilaiPerolehan'],2);
										echo number_format($nilai_sesudah,2);
									}
								?>
								</td>
							</tr>	
						</table>	

					</div><!-- /search-option -->
	        <?php
				}	
			?>
					
			<div style="height:5px;width:100%;clear:both"></div>
			
			<?php
				if($unpost['dist'] == 0){
					if($kontrak['tipeAset'] == 1){
						if($kapchek['kap'] == 0){
							if($countdist == 0){ 
			?>		
				<p><a href="kontrak_unpost.php?id=<?=$_GET['id']?>" class="btn btn-danger btn-small" onclick="return confirm('Anda akan melakukan unposting data. Data tidak dapat diproses jika belum di posting. Lanjutkan?');"><i class="icon-download icon-white"></i>&nbsp;&nbsp;Unpost</a>
				&nbsp;</p>	
			<?php			} else {echo "* Data sudah dipergunakan di distribusi barang";}
						}
					}
				}
			?>
			<div id="demo">
			<table cellpadding="0" cellspacing="0" border="0" class="display" id="example">
				<thead>
					<tr>
						<th>No</th>
						<th>Kode Barang</th>
						<th>Nama Barang</th>
						<th>No. Register</th>
						<th>Harga Satuan</th>
						<th>Penunjang</th>
						<th>Total Perolehan</th>
					</tr>
				</thead>
				<tbody>
				<?php
					if($rKontrak){
						$i = 1;
						$j = 0;
						$bopsisa = $sumsp2d['total'];
						foreach ($rKontrak as $key => $value) {
							$j++;
							if(count($rKontrak) == $j){
								$bop = $bopsisa;
							} else{
								$bopsisa = $bopsisa - ceil($value['NilaiPerolehan']/$sumTotal['total']*$sumsp2d['total']);	
								$bop = ceil($value['NilaiPerolehan']/$sumTotal['total']*$sumsp2d['total']);
							}
							
							$satuan = $value['NilaiPerolehan']-$bop;
								
						
				?>
					<tr class="gradeA">
						<td><?=$i?></td>
						<td><?=$value['kodeKelompok']?></td>
						<td><?=$value['uraian']?></td>
						<td><?=$value['noRegister']?></td>
						<td>
						<?php
							if($kontrak['tipeAset'] == 3) {
								echo number_format($aset[0]['NilaiPerolehan'],2);
							} else {
								echo number_format($satuan,2);
							} 
						?>
						</td>
						<td><?=number_format($bop)?></td>
						<td>
						<?php
							if($kontrak['tipeAset'] == 3) {
								echo number_format($aset[0]['NilaiPerolehan']+$bop,2);
							} else {
								echo number_format($satuan+$bop,2);
							} 
						?></td>
						
					</tr>
				<?php
					$i++;
					}
				}
				?>
				</tbody>
				<tfoot>
					<tr>
						<th colspan="5">&nbsp;</th>
					</tr>
				</tfoot>
			</table>
			</div>
			<div class="spacer"></div>
			
			
		</section> 
		<div id="myModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
				<div id="titleForm" class="modal-header" >
				  <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				  <h3 id="myModalLabel">&nbsp;</h3>
				</div>
			<form action="" method="POST">
				<div class="modal-body">
				 <div class="formKontrak">
						<p>Sukses</p>
							
					</div>
					
			</div>
			<div class="modal-footer">
			  <button class="btn" data-dismiss="modal">Kembali</button>
			  <button class="btn btn-primary">Ok</button>
			</div>

		</div>        
	</section>
	
<?php
	include"$path/footer.php";
?>