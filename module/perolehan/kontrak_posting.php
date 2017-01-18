<?php
include "../../config/config.php";
$menu_id = 10;
            $SessionUser = $SESSION->get_session_user();
            ($SessionUser['ses_uid']!='') ? $Session = $SessionUser : $Session = $SESSION->get_session(array('title'=>'GuestMenu', 'ses_name'=>'menu_without_login')); 
            $USERAUTH->FrontEnd_check_akses_menu($menu_id, $Session);

 $tahun= $_GET['tahun'];
  if($tahun=="")
            	$tahun=$TAHUN_AKTIF;
$get_data_filter = $RETRIEVE->retrieve_kontrak($tahun);
//pr($SessionUser);
?>

<?php
	include"$path/meta.php";
	include"$path/header.php";
	include"$path/menu.php";
	
?>
	<!-- SQL Sementara -->
	<?php

		 $sql = mysql_query("SELECT * FROM kontrak ORDER BY id");
        while ($dataKontrak = mysql_fetch_assoc($sql)){
                $kontrak[] = $dataKontrak;
            }
	?>
	<!-- End Sql -->
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
				<a class="shortcut-link active" href="<?=$url_rewrite?>/module/perolehan/kontrak_posting.php">
					<span class="fa-stack fa-lg">
				      <i class="fa fa-circle fa-stack-2x"></i>
				      <i class="fa fa-inverse fa-stack-1x">5</i>
				    </span>
					<span class="text">Posting</span>
				</a>
			</div>		

		<section class="formLegend">
			
			<p>Tahun Kontrak:
				<?=$tahun?>
			</p>
			<?php
			$tahun_akhir=date("Y");
			for($tahun=2014;$tahun<=$tahun_akhir;$tahun++){


			?><a href="?tahun=<?=$tahun?>" class="btn btn-info btn-small"><i class="icon-plus-sign icon-white"></i>&nbsp;&nbsp;<?=$tahun?></a>
			&nbsp;
			<?php
			}
			?>
			<div id="demo">
			<table cellpadding="0" cellspacing="0" border="0" class="display" id="example">
				<thead>
					<tr>
						<th>No</th>
						<th>Nama Satker</th>
						<th>No. Dokumen</th>
						<th>Tanggal</th>
						<th>Jenis Dokumen</th>
						<th>Tipe Aset</th>
						<th>Nilai</th>
						<th>Status Posting</th>
						<th>Aksi</th>
					</tr>
				</thead>
				<tbody>
					
				<?php
				if($get_data_filter){
					$no = 1;
					foreach($get_data_filter as $val){
				?>
					<tr class="gradeA">
						<td><?=$no?></td>
						<td width="20%"><?=$val['NamaSatker']?></td>
						<td><?=$val['noKontrak']?></td>
						<td><?=$val['tglKontrak']?></td>
						<td><?=($val['tipe_kontrak'] == 2) ? 'Pembelian Langsung' : 'Kontrak'?></td>
						<td><?=$val['tipeAset']?></td>
						<td><?=number_format($val['nilai'],2)?></td>
						<td class="center"><?=($val['n_status']==1) ? '<span class="label label-success">SUDAH</span>' : '<span class="label label-Default">BELUM</span>'?></td>
						<td class="center">
						<?php
						if($val['n_status'] != 1){
						?>	
							<a href="kontrak_postingRincian.php?id=<?=$val['id']?>" class="btn btn-default btn-small"><i class="fa fa-book"></i>&nbsp;Posting</a>
						<?php
						} else {
						?>
						<?php
						$hak_akses_admin=$SessionUser['ses_uaksesadmin'];
						if($hak_akses_admin==1){
							if($val['tipeAset']=="Aset Baru")
							{
								$noKontrak=$val['noKontrak'];
								$nama_satker=$val['NamaSatker'];
								$tmp_kontrak=explode("-",$val['tglKontrak']);
								$tahun_kontrak=$tmp_kontrak[0];
								$id_kontrak=$val['id'];
								if($tahun_kontrak==$TAHUN_AKTIF){
											$batal_kontrak="<a href=\"batal_kontrak.php?id_kontrak=$id_kontrak\" class=\"btn btn-danger btn-small\" onclick=\"return confirm('Apakah anda akan membatalkan kontrak =$noKontrak pada $nama_satker')\">
										<i class=\"fa fa-delete\"></i>&nbsp;Batal Kontrak</a>";
											echo "$batal_kontrak<br/>";
								}
				
							}
						}
						?>
							<a href="kontrak_postingView.php?id=<?=$val['id']?>" class="btn btn-default btn-small"><i class="fa fa-eye"></i>&nbsp;View</a>
						<?php
						}
						?>
						</td>
						
					</tr>
				<?php
					$no++;
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
		       
	</section>
	
<?php
	include"$path/footer.php";
?>