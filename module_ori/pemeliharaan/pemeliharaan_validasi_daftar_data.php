<?php
    include "../../config/config.php";
    $menu_id = 20;
	$SessionUser = $SESSION->get_session_user();
	$USERAUTH->FrontEnd_check_akses_menu($menu_id,$SessionUser);
	$paging = $LOAD_DATA->paging($_GET['pid']);
	
	if($_POST['pem_ia'] == "" && $_POST['pem_na'] == "" && $_POST['pem_nk'] == "" && $_POST['pem_tp'] == "" && $_POST['kelompok_id'] == "" && $_POST['lokasi_id'] == "" && $_POST['skpd_id'] == ""){
			?>
			<script type="text/javascript" charset="utf-8">
			var r=confirm('Tidak ada isian filter');
			if (r==false)
			{
				document.location="<?php echo "$url_rewrite/module/pemeliharaan/";?>pemeliharaan_validasi_filter.php";
			}
			</script>
	<?php
	}
	
	if (isset($_POST['submit']))
	    {
			unset($_SESSION['ses_retrieve_filter_'.$parameter['menuID'].'_'.$SessionUser->UserSes['ses_uid']]);
			list($get_data_filter,$dataAsetUser)  = $RETRIEVE->retrieve_pemeliharaan_validasi_filter(array('param'=>$_POST, 'menuID'=>$menu_id, 'type'=>'', 'paging'=>$paging));
		
	    }else
		{
			$sess = $_SESSION['ses_retrieve_filter_'.$parameter['menuID'].'_'.$SessionUser->UserSes['ses_uid']];
			list($get_data_filter,$dataAsetUser) = $RETRIEVE->retrieve_pemeliharaan_validasi_filter(array('param'=>$sess, 'menuID'=>$menu_id, 'type'=>'', 'paging'=>$paging));
	    }
    
    
        ?>


<html>
    <?php
        include "$path/header.php";
    ?>
    <body>
	                          
        <div id="content">
            <?php
                include "$path/title.php";
                include "$path/menu.php";
            ?>
            <div id="tengah1">	
                <div id="frame_tengah1">
                    <div id="frame_gudang">
                        <div id="topright">
                            Pemeliharaan
                        </div>
						
                        <div id="bottomright">
			    <table width="100%" height="4%" border="1" style="border-collapse:collapse;">
				<tr>
				    <th colspan="2" align="left" style="font-weight:bold;">Filter data : <?php echo $_SESSION['parameter_sql_total']?> Record</th>
				</tr>
			    </table>
			    <br>
					    
				<div style="margin-bottom:10px; float:right;">
					<a href="pemeliharaan_validasi_filter.php"><input type="submit" value="Kembali ke Halaman Pencarian"></a>
				</div>
				
		<script type="text/javascript" charset="utf-8">
			$(document).ready(function() {
			$('#example').dataTable( {
					"aaSorting": [[ 1, "asc" ]]
				} );
				
			} );
			
		</script>
	
	<?php
	$offset = @$_POST['record'];
	$query_apl = "SELECT aset_list FROM apl_userasetlist WHERE aset_action = 'validasiPemeliharaan[]'";
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
	
?>
<!-- Begin frame -->
<!-- Begin frame -->

<table width='100%' border='1' style="border-collapse:collapse;border: 1px solid #dddddd;">
    <tr>
        <td colspan ="3" align="right">
			<table border="0" width="100%">
				<tr>
					<td align="right" width="200px">
							<input type="hidden" class="hiddenpid" value="<?php echo @$_GET['pid']?>">
							<input type="hidden" class="hiddenrecord" value="<?php echo @$_SESSION['parameter_sql_total']?>">
							<span><input type="button" value="<< Prev" class="buttonprev"/>
							Page
							<input type="button" value="Next >>" class="buttonnext"/></span>
						
					</td>
				</tr>
			</table>
        </td>
    </tr>
</table>

<div id="demo">
<table cellpadding="0" cellspacing="0" border="0" class="display" id="example" width="100%">
	<thead>
		<tr>
			<th style="background-color: #eeeeee; border: 1px solid #dddddd;" width='20px'>No</th>
			<th style="background-color: #eeeeee; border: 1px solid #dddddd; font-weight:bold;">Informasi Aset</th>
		</tr>
	</thead>
	<?php
	if (!empty($get_data_filter))
    {
    ?>
		
	<tbody>
		<?php
		$nomor = 1;
		foreach ($get_data_filter as $key => $value)
		{
		?>
			<tr class="<?php if($nomor == 1) echo ' '?>">
				<td align="center" style="border: 1px solid #dddddd;"><?php echo $nomor?></td>
				<td style="border: 1px solid #dddddd;">
					<table width='100%'>
						<tr>
							<td>
								<span style="padding:1px 5px 1px 5px; background-color:#eeeeee; border: 1px solid #cccccc;font-weight:bold;"><?php echo$value->Aset_ID?></span>
								( Aset ID - System Number )
							</td>
							<td align="right">		
								<?php
									  if (($_SESSION['ses_uaksesadmin'] == 1)){
										  ?>
										  <span style="padding:1px 5px 1px 5px; background-color:#eeeeee; border: 1px solid #cccccc;horizontal-align:'right';font-weight:bold;">
												<a href="pemeliharaan_validasi_pemeliharaan.php?id=<?php echo $value->Aset_ID?>">Validasi Pemeliharaan</a>
											</span>
										  <?php
									  }else{
										  if ($dataAsetUser){
										  if (in_array($value->Aset_ID, $dataAsetUser)){
										  ?>
										  <span style="padding:1px 5px 1px 5px; background-color:#eeeeee; border: 1px solid #cccccc;horizontal-align:'right';font-weight:bold;">
												<a href="pemeliharaan_validasi_pemeliharaan.php?id=<?php echo $value->Aset_ID?>">Validasi Pemeliharaan</a>
										  </span>
										  <?php
										  }
									  }
									  }
									  
								 ?>
							</td>
						</tr>
						<tr>
							<td style="font-weight:bold;"><?php echo $value->NomorReg?></td>
						</tr>
						<tr>
							<td style="font-weight:bold;"><?php echo $value->Kode?></td>
						</tr>
						<tr>
							<td style="font-weight:bold;"><?php echo $value->NamaAset?></td>
						</tr>
					</table>
				<br>
				<hr />
				<table>
					<tr>
						<td width="20%"> No.Kontrak</td><td width="2%"></td> <td width="78%"><?php echo $value->NoKontrak?></td>
					</tr>
					<tr>
						<td>Satker</td><td></td><td><?php echo '['.$value->KodeSatker.'] '.$value->NamaSatker?></td>
					</tr>
					<tr>
						<td>Lokasi</td><td></td> <td><?php echo $value->NamaLokasi?></td>
					</tr>
					<tr>
						<td>Status</td><td></td><td><?php echo $value->Kondisi_ID. '-' .$value->InfoKondisi?></td>
					</tr>

				</table>
			 </td>
		</tr>
		<?php
			$nomor++;
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
			<th style="background-color: #eeeeee; border: 1px solid #dddddd;">No</th>
			<th style="background-color: #eeeeee; border: 1px solid #dddddd; font-weight:bold;">Informasi Aset</th>
			
		</tr>
	</tfoot>
</table>
			</div>
			<div class="spacer"></div>
<!-- End Frame -->

</div>
</div>
</div>
</div>                            
        <?php 
        include "$path/footer.php";
        ?>
    </body>
</html>	
