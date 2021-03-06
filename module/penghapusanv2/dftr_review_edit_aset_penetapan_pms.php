<?php
include "../../config/config.php";
$PENGHAPUSAN = new RETRIEVE_PENGHAPUSAN;
$menu_id = 75;

$SessionUser = $SESSION->get_session_user();
($SessionUser['ses_uid']!='') ? $Session = $SessionUser : $Session = $SESSION->get_session(array('title'=>'GuestMenu', 'ses_name'=>'menu_without_login')); 
$USERAUTH->FrontEnd_check_akses_menu($menu_id, $Session);

include"$path/meta.php";
include"$path/header.php";
include"$path/menu.php";

$id=$_GET['id'];
$par_data_table = "id=$id";
if (isset($id)){
	unset($_SESSION['ses_retrieve_filter_'.$menu_id.'_'.$SessionUser['ses_uid']]);
	$parameter = array('id'=>$id);
	$data = $PENGHAPUSAN->DataPenetapan($id);
	$row=$data[0];	
	//pr($row);
}
?>
	
	<script>
        $(function(){
       		 $('#tanggal1').datepicker();
       		 $("#cekAll").click( function(){
   				if($(this).is(':checked')){
					//alert("checked");
					$("#submit").removeAttr("disabled");
				}else{
					$('#submit').attr('disabled','disabled');
				}   					 
			});
        });
        function confirmValidate(){	
			var r = confirm("Anda Yakin Menghapus Data!");
		    if (r == true) {
		        return true;
		    } else {
		        return false;
		    }		
		}

		function AreAnyCheckboxesChecked () 
		{
			setTimeout(function() {
		  if ($("#Form2 input:checkbox:checked").length > 0)
			{
			    $("#submit").removeAttr("disabled");
			    updDataCheckbox('DELASPMS');
			}
			else
			{
			   $('#submit').attr("disabled","disabled");
			    updDataCheckbox('DELASPMS');
			}}, 300);
		}
	</script>
		<script>
   		 $(document).ready(function() {
   		  AreAnyCheckboxesChecked();	
          $('#rvw_aset_usulan_pms_table').dataTable(
                   {
                    "aoColumnDefs": [
                         { "aTargets": [2] }
                    ],
                    "aoColumns":[
                         {"bSortable": false},
                         {"bSortable": false,"sClass": "checkbox-column" },
                         {"bSortable": false},
                         {"bSortable": false},
                         {"bSortable": true},
                         {"bSortable": false},
                         {"bSortable": true},
                         {"bSortable": false},
                         {"bSortable": false}],
                    "sPaginationType": "full_numbers",

                    "bProcessing": true,
                    "bServerSide": true,
                    "sAjaxSource": "<?=$url_rewrite?>/api_list/api_review_edit_aset_penetapan_pms_fix_rev.php?<?php echo $par_data_table?>"
               }
                  );
      });
    </script>
	<section id="main">
		<ul class="breadcrumb">
			  <li><a href="#"><i class="fa fa-home fa-2x"></i>  Home</a> <span class="divider"><b>&raquo;</b></span></li>
			  <li><a href="#">Penghapusan</a><span class="divider"><b>&raquo;</b></span></li>
			  <li class="active">Daftar Aset Usulan Penghapusan Pemusnahan</li>
			  <?php SignInOut();?>
			</ul>
			<div class="breadcrumb">
				<div class="title">Usulan Penghapusan Pemusnahan</div>
				<div class="subtitle">Review Aset yang akan dibuat Usulan</div>
			</div>	

		<div class="grey-container shortcut-wrapper">
				<a class="shortcut-link " href="<?=$url_rewrite?>/module/penghapusanv2/dftr_usulan_pms.php">
					<span class="fa-stack fa-lg">
				      <i class="fa fa-circle fa-stack-2x"></i>
				      <i class="fa fa-inverse fa-stack-1x">1</i>
				    </span>
					<span class="text">Usulan Penghapusan</span>
				</a>
				<a class="shortcut-link active" href="<?=$url_rewrite?>/module/penghapusanv2/dftr_penetapan_pms.php">
					<span class="fa-stack fa-lg">
				      <i class="fa fa-circle fa-stack-2x"></i>
				      <i class="fa fa-inverse fa-stack-1x">2</i>
				    </span>
					<span class="text">Penetapan Penghapusan</span>
				</a>
				<a class="shortcut-link" href="<?=$url_rewrite?>/module/penghapusanv2/dftr_validasi_pms.php">
					<span class="fa-stack fa-lg">
				      <i class="fa fa-circle fa-stack-2x"></i>
				      <i class="fa fa-inverse fa-stack-1x">3</i>
				    </span>
					<span class="text">Validasi Penghapusan</span>
				</a>
			</div>		

		<section class="formLegend">
			<form method="POST" action="<?php echo "$url_rewrite/module/penghapusanv2/"; ?>dftr_asetid_penetapan_proses_upd_pms.php"> 
			<?php
				if($row['Status']==0){
					$disabled = "";
				}else{
					$disabled = "readonly";
				}

			?>
			<div class="detailLeft">			
			<ul>
				<li>
					<span  class="labelInfo">No Penetapan</span>
					<input type="text" name="NoSKHapus" value="<?=$row['NoSKHapus']?>"  <?=$disabled?>/>
				</li>
				<li>
					<span class="labelInfo">Keterangan Penetapan</span>
					<textarea name="AlasanHapus" <?=$disabled?> ><?=$row['AlasanHapus']?></textarea>
				</li>
				<?php
				if($row['Status']==0){
					?>
				<li>
					<span  class="labelInfo">&nbsp;</span>
					<input type="submit" <?=$disabled?> value="Update Informasi Penetapan" class="btn"/>
				</li>
				<?php
					}
				?>
			</ul>
							
			</div>

			<div class="detailRight">
				<ul>
					<?php
							
							$TglUsulanTmp=explode("-", $row['TglHapus']);
							$TglUsulan=$TglUsulanTmp[1]."/".$TglUsulanTmp[2]."/".$TglUsulanTmp[0];

					?>
					<li>
						<span  class="labelInfo">Tanggal Penetapan</span>
							<div class="input-prepend">
								<span class="add-on"><i class="fa fa-calendar"></i></span>
								<input name="TglHapus" type="text" id="tanggal1" <?=$disabled?>  value="<?=$row['TglHapus']?>" required/>
							</div>
						
					</li>
					<li>
						<span  class="labelInfo">&nbsp;</span>
						<input type="hidden" name="Penghapusan_ID" value="<?=$id?>"/><br/>
					</li>	
				</ul>
			</div>
			
			<div style="height:5px;width:100%;clear:both"></div>
			</form>
			<form method="POST" ID="Form2" action="<?php echo "$url_rewrite/module/penghapusanv2/"; ?>dftr_asetid_penetapan_proses_hapus_pms.php" onsubmit="return confirmValidate()" > 
			<div id="demo">
			<table cellpadding="0" cellspacing="0" border="0" class="display  table-checkable" id="rvw_aset_usulan_pms_table">
				<thead>
					<?php
						if($row['Status']==0){
					?>
					<tr> 
						<td colspan="11" align="center">
							<h4>Ceklis dibawah ini untuk menghapus semua aset :</h4>
							 <label><input type="checkbox" value="1" name ="cekAll" id="cekAll"	><h4>Select All</h4></label>
						</td>
					</tr>
					<?php
						}
					?>
					<tr>
						<td colspan="7" align="Left">
							<?php
								if($row['Status']==0){
							?>
								<a href="filter_usulan_pms.php?id=<?=$id?>" class="btn btn-info " /><i class="icon-plus-sign icon-white"></i>&nbsp;&nbsp;TambahKan Penetapan</a>
								<span><button type="submit" name="submit"  class="btn btn-danger " id="submit" disabled/><i class="icon-trash icon-white"></i>&nbsp;&nbsp;Delete</button></span>
								<input type="hidden" name="Penghapusan_ID" value="<?=$row['Penghapusan_ID']?>"/>
							<?php
								}else{
									?>
								<?php

								}
							?>
						</td>
						<?php
								if($row['Status']==0){
							?>
						<td colspan="4" align="right">
							<?php
								}else{
							?>

						<td colspan="10" align="right">
							<?php
								}
							?>
							<a href="dftr_penetapan_pms.php" class="btn btn-success " /><i class="fa fa-reply"></i>&nbsp;&nbsp;Kembali Ke Daftar Usulan</a>
								
						</td>
					</tr>
					<tr>
						<th>No</th>
						<?php
								if($row['Status']==0){
							?>
						<th class="checkbox-column"><input type="checkbox" class="icheck-input" onchange="return AreAnyCheckboxesChecked();"></th>
							<?php
								}else{
									echo"<th>&nbsp;</th>";
								}
							?>
						<th>No Register</th>
						<th>Kode / Uraian</th>
						<th>Satker</th>
						<th>Tanggal Perolehan</th>
						<th>Nilai Perolehan</th>
						<th>Status</th>
						<th>Merk / Type</th>
					</tr>
				</thead>
				<tbody>		
					 <tr>
                        <td colspan="9">Data Tidak di temukkan</td>
                     </tr>
				</tbody>
				<tfoot>
					<tr>
						<th>&nbsp;</th>
						<th>&nbsp;</th>
						<th>&nbsp;</th>
						<th>&nbsp;</th>
						<th>&nbsp;</th>
						<th>&nbsp;</th>
						<th>&nbsp;</th>
						<th>&nbsp;</th>
						<th>&nbsp;</th>
					</tr>
				</tfoot>
			</table>
			</div>
			</form>
			<div class="spacer"></div>
			    
		</section> 
		     
	</section>
	
<?php
	include"$path/footer.php";
?>