<?php
include "../../config/config.php";

$MUTASI = new RETRIEVE_MUTASI;

 /*$aset_id=$_POST['mutasi_trans_filt_idaset'];
    $nama_aset=$_POST['mutasi_trans_filt_nmaset'];
    $nomor_kontrak=$_POST['mutasi_trans_filt_nokontrak'];
    $satker=$_POST['skpd_id'];
    $submit=$_POST['transfer'];*/
		// pr($_POST);
		$submit=$_POST['transfer'];
		$data['kd_idaset']= $_POST['mutasi_trans_filt_idaset'];
		$data['kd_namaaset'] = $_POST['mutasi_trans_filt_nmaset'];
		$data['kd_nokontrak'] = $_POST['mutasi_trans_filt_nokontrak'];
		$data['satker'] = $_POST['skpd_id'];
		$data['kd_tahun'] = $_POST['mutasi_trans_filt_thn'];
		$data['kelompok_id'] = $_POST['kelompok_id'];
		$data['lokasi_id'] = $_POST['lokasi_id'];
		$data['paging'] = $_GET['pid'];
		$data['sql'] = " Status_Validasi_Barang=1";
		//$data['sql'] = " StatusMenganggur=0 AND StatusMutasi=0";
		$data['sql_where'] = TRUE;
		$data['modul'] = "";
		// $getFilter = $HELPER_FILTER->filter_module($data);
		// pr($getFilter);
		// exit;
		//echo'ada';
    //open_connection();
        
     if (isset($submit)){
            if ($data['kd_idaset']=="" && $data['kd_namaaset']=="" && $data['kd_idaset']=="" && $data['kd_nokontrak']=="" && $data['kd_tahun']=="" && $data['satker']=="" && $data['kelompok_id']=="" && $data['lokasi_id']==""){
		?>
                <script>
                // var r=confirm('Tidak ada isian filter');
                //             if (r==false){
                //                 document.location="<?php echo "$url_rewrite/module/mutasi/"; ?>transfer_antar_skpd.php";
                //             }
                    </script>
        <?php
            }
        }
        ?>
<?php
	include"$path/meta.php";
	include"$path/header.php";
	include"$path/menu.php";
	
	// pr($_POST);
    if ($_POST['submit']){
		// unset($_SESSION['ses_mutasi_filter']);

		$_SESSION['ses_mutasi_val_filter'] = $_POST;
		
	}

	$dataParam = $_SESSION['ses_mutasi_val_filter'];
	$dataParam['page'] = intval($_GET['pid']);

	$data = $MUTASI->retrieve_mutasiPending($dataParam);
	// pr($data);
			?>
		 <script type="text/javascript" charset="utf-8">
			$(document).ready(function() {
				
				var tes=document.getElementsByTagName('*');
				var button=document.getElementById('submit');
				var boxeschecked=0;
				for(k=0;k<tes.length;k++)
				{
					if(tes[k].className=='checkbox')
						{
							//
							tes[k].checked == true  ? boxeschecked++: null;
						}
				}
			//alert(boxeschecked);
				if(boxeschecked!=0){
					button.disabled=false;
				}
				else {
					button.disabled=true;
				}
				
			} );
			
			function enable(){  
			var tes=document.getElementsByTagName('*');
			var button=document.getElementById('submit');
			var boxeschecked=0;
			for(k=0;k<tes.length;k++)
			{
				if(tes[k].className=='checkbox')
					{
						//
						tes[k].checked == true  ? boxeschecked++: null;
					}
			}
				//alert(boxeschecked);
				if(boxeschecked!=0)
					button.disabled=false;
				else
					button.disabled=true;
				}
				function disable_submit(){
					var enable = document.getElementById('pilihHalamanIni');
					var disable = document.getElementById('kosongkanHalamanIni');
					var button=document.getElementById('submit');
					if (disable){
						button.disabled=true;
					} 
				}
				function enable_submit(){
					var enable = document.getElementById('pilihHalamanIni');
					var disable = document.getElementById('kosongkanHalamanIni');
					var button=document.getElementById('submit');
					if (enable){
						button.disabled=false;
					} 
				}
			</script>
	
        

          <section id="main">
			<ul class="breadcrumb">
			  <li><a href="#"><i class="fa fa-home fa-2x"></i>  Home</a> <span class="divider"><b>&raquo;</b></span></li>
			  <li><a href="#">Mutasi</a><span class="divider"><b>&raquo;</b></span></li>
			  <li class="active">Transfer Antar SKPD</li>
			  <?php SignInOut();?>
			</ul>
			<div class="breadcrumb">
				<div class="title">Transfer Antar SKPD</div>
				<div class="subtitle">Daftar Data</div>
			</div>	
			 <?php
				$param=  urlencode($_SESSION['parameter_sql_report']);
				//echo "$param";
			?>
			 
		<section class="formLegend">
			
			<div class="detailLeft">
					<span class="label label-success">Filter data: <?php echo $jml?> filter (View seluruh data)</span>
			</div>
		
			<div class="detailRight" align="right">
						
						<ul>
							<li>
								<a href="<?php echo"$url_rewrite/module/mutasi/validasi_transfer_antar_skpd.php";?>" class="btn">
								Kembali ke Halaman Utama: Cari Aset</a>
								
							</li>
							<!--
							<li>
								<a href="<?php echo "$url_rewrite/module/mutasi/"; ?>transfer_hasil_daftar.php?pid=1" class="btn">
									   Daftar Barang Mutasi
								 </a>
							</li>-->
							<li>
								<input type="hidden" class="hiddenpid" value="<?php echo @$_GET['pid']?>">
								<input type="hidden" class="hiddenrecord" value="<?php echo @$count?>">
								   <ul class="pager">
								   	<?php 
								   		$prev = intval($_GET['pid']-1);
								   		$next = intval($_GET['pid']+1);
								   		?>
										<li><a href="<?php echo"$url_rewrite/module/mutasi/validasi_transfer_hasil_filter.php?pid=$prev";?>" class="buttonprev" >Previous</a></li>
										<li>Page</li>
										<li><a href="<?php echo"$url_rewrite/module/mutasi/validasi_transfer_hasil_filter.php?pid=$next";?>" class="buttonnext1">Next</a></li>
									</ul>
							</li>
						</ul>
							
					</div>
			<div style="height:5px;width:100%;clear:both"></div>
			
			
			<div id="demo">
			<form name="form" method="POST" action="<?php echo "$url_rewrite/module/mutasi/"; ?>validasi_transfer_eksekusi.php">
			<input type="hidden" name="jenisaset" value="<?php echo implode(',', $_POST['jenisaset'])?>">
			<table cellpadding="0" cellspacing="0" border="0" class="display" id="example">
				<thead>
					<!--
					<tr>
						<td width="130px"><span><a href="#" onclick="enable_submit()" id="pilihHalamanIni"><u>Pilih halaman ini</u></a></span></td>
						<td  align=left><a href="#" onclick="disable_submit()" id="kosongkanHalamanIni" ><u>Kosongkan halaman ini</u></a></td>
						
						<td  align="right">   
							<input type="submit" name="submit" value="Pengeluaran Barang" id="submit" disabled/>
						</td>
					</tr>-->
					<tr>
						<th>No</th>
						<th>NoSKKDH</th>
						<th>TglSKKDH</th>
						<th>Satker Awal</th>
						<th>Satker Tujuan</th>
						<th>Keterangan</th>
						<th>Jumlah Barang</th>
						<th>Status</th>
						<th>Detail</th>
					</tr>
				</thead>
				<tbody>		
							 
				<?php
					if (!empty($data))
					{
					
					
						// $no = 1;
						$no = 1;
						$page = @$_GET['pid'];
						if ($page > 1){
							$no = intval($page - 1 .'01');
						}else{
							$no = 1;
						}
						foreach ($data as $key => $value)
						{
				?>
				
					<tr class="gradeA">
						<!--
						<td>
							<input type="checkbox" id="checkbox" class="checkbox" onchange="enable()" name="Mutasi[]" value="<?php echo $value['Aset_ID'];?>" >
													
						</td>-->
						<td><?php echo "$no.";?></td>
						<td><?php echo "$value[NoSKKDH]";?></td>
						<td style="font-weight: bold;"> <?php echo "$value[TglSKKDH]";?> </td>
						<td style="font-weight: bold;"><?php echo "$value[SatkerAwal] - $value[NamaSatkerAwalAset]";?></td>
						<td style="font-weight: bold;"><?php echo "$value[SatkerTujuan] - $value[NamaSatker]";?></td>
						<td style="font-weight: bold;"> <?php echo "$value[Keterangan]";?> </td>
						<td style="font-weight: bold;" align="center"><?php echo "$value[Jumlah]";?></td>
						<td style="font-weight: bold;" align="center">
                            <?php 

                            $statusMutasi = $value['FixMutasi']; 
                            if ($statusMutasi == 0){
                                $labelStatus = "Pending Usulan";  
                            	$label = "label-info";
                            }    
                            
                            if ($statusMutasi == 1) {$labelStatus = "Usulan sudah divalidasi"; $label = "label-success";}
                            if ($statusMutasi == 3) {$labelStatus = "Usulan dihapus"; $label = "label-danger";}
                            ?>
                                
                            
                            <span class="label <?=$label?>"><?=$labelStatus?></span>
                        </td>
						<td style="font-weight: bold;"><a href="<?php echo "$url_rewrite/module/mutasi/"; ?>validasi_transfer_eksekusi.php?id=<?=$value[Mutasi_ID]?>" class="btn btn-info btn-small">Lihat Detail<a/></td>	
							
						
					</tr>
					 <?php 
						$no++; 
							} 
						}
						  else
						{
							$disabled = 'disabled';
						}
						?>
				</tbody>
				<tfoot>
					<tr>
						<th>&nbsp;</th>
						<th>&nbsp;</th>
						<th>&nbsp;</th>
					</tr>
				</tfoot>
			</table>
			</form>
			</div>
			<div class="spacer"></div>
			
			
		</section> 
	</section>
<?php
include "$path/footer.php";
?>
