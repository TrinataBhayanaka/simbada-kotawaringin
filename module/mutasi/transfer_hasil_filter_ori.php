<?php
    include "../../config/config.php";
    include "$path/header.php";
    include "$path/title.php";
    
    /*$aset_id=$_POST['mutasi_trans_filt_idaset'];
    $nama_aset=$_POST['mutasi_trans_filt_nmaset'];
    $nomor_kontrak=$_POST['mutasi_trans_filt_nokontrak'];
    $satker=$_POST['skpd_id'];
    $submit=$_POST['transfer'];*/
		//pr($_POST);
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
		$getFilter = $HELPER_FILTER->filter_module($data);
		// pr($getFilter);
		// exit;
		//echo'ada';
    //open_connection();
        
     if (isset($submit)){
            if ($data['kd_idaset']=="" && $data['kd_namaaset']=="" && $data['kd_idaset']=="" && $data['kd_nokontrak']=="" && $data['kd_tahun']=="" && $data['satker']=="" && $data['kelompok_id']=="" && $data['lokasi_id']==""){
		?>
                <script>var r=confirm('Tidak ada isian filter');
                            if (r==false){
                                document.location="<?php echo "$url_rewrite/module/mutasi/"; ?>transfer_antar_skpd.php";
                            }
                    </script>
        <?php
            }
        }
        ?>
							
	   <script type="text/javascript" charset="utf-8">
			$(document).ready(function() {
				$('#example').dataTable( {
					"aaSorting": [[ 1, "desc" ]]
				} );
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
	
        

<html>
    <body onload="enable()">
                        
        <div id="content">
            <?php
                
                include "$path/menu.php";
            ?>
            <div id="tengah1">	
                <div id="frame_tengah1">
                    <div id="frame_gudang">
                        <div id="topright">
                            Transfer Antar SKPD
                        </div>
                        
                            <?php
                                $param=  urlencode($_SESSION['parameter_sql_report']);
                                //echo "$param";
                            ?>
                            <!--<div style="margin-bottom:10px; float:right; clear:both;">
                                List Preview : <a href="<?php echo "$url_rewrite/report/template/MUTASI/tes_class_mutasi_transfer_cetak_seluruh.php?menu_id=22&mode=1&parameter=$param";?>" target="_blank"><input type="submit" value="Cetak Seluruh Data"></a>
                                <a href="<?php echo "$url_rewrite/report/template/MUTASI/tes_class_mutasi_transfer_dengan_pilihan.php?menu_id=22&mode=2";?>" target="_blank"><input type="submit" value="Cetak dari Daftar Anda"></a>
                            </div>-->
                            
                                        
                                        <?php
                                            $offset = @$_POST['record'];

											$param = $_SESSION['parameter_sql'];
											if (isset($_POST['search'])){
												
												$query="$param ORDER BY Aset_ID ASC ";
											}else{
												$paging = paging($_GET['pid']);
											
												$query="$param ORDER BY Aset_ID ASC ";
											}
											
											$res = mysql_query($query) or die(mysql_error());
											if ($res){
												$rows = mysql_num_rows($res);
												
												while ($data = mysql_fetch_array($res))
												{
													
													$dataArray[] = $data['Aset_ID'];
												}
											}
											
											if($dataArray){
												$dataImplode = implode(',',$dataArray);
											}
											$querypenggunaan ="SELECT Aset_ID FROM PenggunaanAset WHERE Aset_ID IN ({$dataImplode}) AND StatusMenganggur=0 AND StatusMutasi=0"; 	
											$res1 = mysql_query($querypenggunaan) or die(mysql_error());
											
											if ($res1){
												$rows1 = mysql_num_rows($res1);
												$jml=($rows1);
												//pr($jml);
												while ($data1 = mysql_fetch_array($res1))
												{
													
													$dataArray1[] = $data1['Aset_ID'];
												}
											}
											if($dataArray1){
												$dataImplode_1 = implode(',',$dataArray1);
											}
											if($dataImplode_1!=""){
											$viewTable = 'filter_mutasi_'.$SessionUser['ses_uoperatorid'];
											// $table = $DBVAR->is_table_exists($viewTable, 1);
											// if (!$table){
												$sql="CREATE OR REPLACE VIEW $viewTable AS SELECT a.LastSatker_ID, a.NamaAset, a.Aset_ID, a.NomorReg,c.NoKontrak, e.NamaSatker, f.NamaLokasi, g.Kode FROM Aset AS a 
													 LEFT JOIN KontrakAset AS d ON a.Aset_ID=d.Aset_ID 
													 LEFT JOIN Kontrak AS c ON d.Kontrak_ID=c.Kontrak_ID 
													 INNER JOIN Satker AS e ON a.LastSatker_ID=e.Satker_ID 
													 INNER JOIN Lokasi AS f ON a.Lokasi_ID=f.Lokasi_ID 
													 INNER JOIN Kelompok AS g ON a.Kelompok_ID=g.Kelompok_ID
													 WHERE a.Aset_ID IN ({$dataImplode_1}) ORDER BY a.Aset_ID asc";
												
											
												$exec=mysql_query($sql) or die(mysql_error());
											
												// }
												//pr($sql);
											
											$sql = "SELECT * FROM $viewTable LIMIT $paging, 100 ";
											$exec=mysql_query($sql) or die(mysql_error());
											$count=mysql_num_rows($exec);
											while ($dataAset = mysql_fetch_object($exec)){
												$row[] = $dataAset;
												}
											$dataAsetUser = $HELPER_FILTER->getAsetUser(array('Aset_ID'=>$dataImplode_1));	
											}
										
										$query_apl = "SELECT aset_list FROM apl_userasetlist WHERE aset_action = 'Mutasi[]'";
										$result_apl = $DBVAR->query($query_apl) or die ($DBVAR->error());
										$data_apl = $DBVAR->fetch_object($result_apl);
										
										$array = explode(',',$data_apl->aset_list);
											
										foreach ($array as $id)
										{
											if ($id !='')
											{
											$dataAsetList[] = $id;
											}
										}
										
										if ($dataAsetList !='')
										{
											$explode = array_unique($dataAsetList);
										}
                                         // pr($_SESSION);      
									?>
										
							<div id="bottomright">
							<table width="100%" height="4%" border="1" style="border-collapse:collapse;">
								<tr>
									<th colspan="2" align="left" style="font-weight:bold;">Filter data : <?php echo $jml?> Record</u></th>
								</tr>
							</table>
							<br>
						<div style="margin-bottom:10px; float:right;">
                                <a href="transfer_antar_skpd.php"><input type="submit" value="Kembali ke Halaman Utama: Cari Aset"></a>
                            </div>
                            <div style="margin-bottom:10px; float:right; clear:both;">
								<table>
									<tr>
										<td>
											<a href="<?php echo "$url_rewrite/module/mutasi/"; ?>transfer_hasil_daftar.php?pid=1"><input type="submit" value="Daftar Barang Mutasi"></a>
										</td>
									</tr>
								</table>
							</div>
							<form name="form" method="POST" action="<?php echo "$url_rewrite/module/mutasi/"; ?>transfer_eksekusi.php">
								<div>
								<table border='0' cellspacing="0" width="100%" style="padding:0px; margin-top:1px; border: 1px solid #dddddd;; border-width: 1px 1px 1px 1px; clear:both;">
									<tbody>
										<tr>
											<td width="130px"><span><a href="#" onclick="enable_submit()" id="pilihHalamanIni"><u>Pilih halaman ini</u></a></span></td>
											<td  align=left><a href="#" onclick="disable_submit()" id="kosongkanHalamanIni" ><u>Kosongkan halaman ini</u></a></td>
											
											<td  align="right">   
												<input type="submit" name="submit" value="Pengeluaran Barang" id="submit" disabled/>
											</td>
											<td width="200px" align="right"><input type="hidden" class="hiddenpid" value="<?php echo @$_GET['pid']?>">
												<input type="hidden" class="hiddenrecord" value="<?php echo @$count?>">
													<input type="button" value="<< Prev" class="buttonprev"/>
														Page
												<input type="button" value="Next >>" class="buttonnext"/>
											</td>	
										</tr>
									</tbody>
									</table>
									</div>
									</br>
									<div id="demo">
									<table cellpadding="0" cellspacing="0" border="0" class="display" id="example" width="100%">
										<thead>
											<tr>
												<th width=20px; style="background-color: #eeeeee; border: 1px solid #dddddd;">No</th>
												<th style="background-color: #eeeeee; border: 1px solid #dddddd;">&nbsp;</th>
												<th style="background-color: #eeeeee; border: 1px solid #dddddd; font-weight:bold;">Informasi Aset</th>
											</tr>
										</thead>
										<?php
											if (!empty($row))
											{
											?>
												
											<tbody>
												<?php
												// $no = 1;
												$no = 1;
												$page = @$_GET['pid'];
												if ($page > 1){
													$no = intval($page - 1 .'01');
												}else{
													$no = 1;
												}
												foreach ($row as $key => $value)
												{
										?>
										
                                        <tr>
                                            <td align="center" style="background-color: #; border: 1px solid #dddddd; color: black; font-weight: "><?php echo "$no.";?></td>
                                            <td align="center" style="background-color: #; border: 1px solid #dddddd; color: black; font-weight: bold;">
                                               	<?php
													// pr($_SESSION['ses_uaksesadmin']);
												if (($_SESSION['ses_uaksesadmin'] == 1)){
													?>
													<input type="checkbox" id="checkbox" class="checkbox" onchange="enable()" name="Mutasi[]" value="<?php echo $value->Aset_ID;?>" 
													<?php 
														for ($i = 0; $i <= count($explode); $i++){
															if ($explode[$i]==$value->Aset_ID) 
																echo 'checked';
														}?>>
													<?php
												}else{
													if ($dataAsetUser){
													if (in_array($value->Aset_ID, $dataAsetUser)){
													?>
													<input type="checkbox" id="checkbox" class="checkbox" onchange="enable()" name="Mutasi[]" value="<?php echo $value->Aset_ID;?>" <?php for ($i = 0; $i <= count($explode); $i++){if ($explode[$i]==$value->Aset_ID) echo 'checked';}?>>
													<?php
													}
												}
												}
												
												?>	
                                            </td>
                                            <td align="left" style="background-color: #; border: 1px solid #dddddd;height:100px; color: black; font-weight: bold;">
                                                <table width="100%">
                                                    <tr>
                                                        <td style="font-weight: bold;"> <?php echo "$value->Aset_ID";?></b> ( Aset ID - System Number )</td>
                                                    </tr>
                                                    <tr>
                                                        <td style="font-weight: bold;"><?php echo "$value->NomorReg";?></td>
                                                    </tr>
                                                    <tr>
                                                        <td style="font-weight: bold;"><?php echo "$value->Kode";?></td>
                                                    </tr>
                                                    <tr>
                                                        <td style="font-weight: bold;"><?php echo "$value->NamaAset";?></td>
                                                    </tr>
                                                    <tr>
                                                        <td><hr></td>
                                                    </tr>
												</table>	
												<table width="100%">
													<tr>
														
														<td width="20%">No. Kontrak</td>
														<td width="2%">&nbsp;</td>
														<td width="78%"><?php echo "$value->NoKontrak";?></td>
													</tr>
													<tr align="left">
														<td>Satker</td>
														<td>&nbsp;</td>
														<td><?php echo "$value->NamaSatker";?></td>
													</tr>
													<tr align="left">
														<td>Lokasi</td>
														<td>&nbsp;</td>
														<td><?php echo "$value->NamaLokasi";?></td>
													</tr>			
													<tr align="left">				
														<td>Status</td>
														<td>&nbsp;</td>
														<td>-</td>
													</tr>
												</table>
                                            </td>
                                        </tr>
                                        <?php $no++; 
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
													<th width=20px; style="background-color: #eeeeee; border: 1px solid #dddddd;">No</th>
													<th style="background-color: #eeeeee; border: 1px solid #dddddd;">&nbsp;</th>
													<th style="background-color: #eeeeee; border: 1px solid #dddddd; font-weight:bold;">Informasi Aset</th>
													
												</tr>
											</tfoot>
										</table>
										</div>
										<div class="spacer"></div>
                                </form>
                            
                            </div>
                        </div>
                    </div>
                </div>
            </div>
                
            <div id="footer">Sistem Informasi Barang Daerah ver. 0.x.x <br />
			Powered by BBSDM Team 2012
            </div>
    </body>
</html>	
