<?php
class RETRIEVE_PENGGUNAAN extends RETRIEVE{

    var $db = "";
	public function __construct()
	{
		parent::__construct();

        $this->db = new DB;
	}
	
	
    public function update_daftar_penetapan_penggunaan($data,$debug=false)
    {
        
        $id=$data['Penggunaan_ID'];
        $tgl_aset=$data['penggu_penet_eks_tglpenet'];
        $change_tgl=  format_tanggal_db2($tgl_aset);
        $noaset=$data['penggu_penet_eks_nopenet'];
        $ket=$data['penggu_penet_eks_ket'];

        $sql = array(
                'table'=>'Penggunaan',
                'field'=>"TglSKKDH='{$change_tgl}', NoSKKDH='{$noaset}', Keterangan='{$ket}', TglUpdate='{$change_tgl}'",
                'condition' => "Penggunaan_ID='$id'",
                'limit' => '1',
                );

        $res = $this->db->lazyQuery($sql,$debug,2);
        if ($res) return $res;
        return false;
    }

    public function delete_daftar_penetapan_penggunaan($data,$debug=false)
    {
        
        $id=$data['Penggunaan_ID'];
        $asetid=$data['Aset_ID'];

        // $query="UPDATE Penggunaan SET FixPenggunaan=0 WHERE Penggunaan_ID='$id'";
        // $exec=$this->query($query) or die($this->error());

        // $query2="UPDATE Aset SET NotUse=0 WHERE LastPenggunaan_ID='$id'";
        // $exec2=$this->query($query2) or die($this->error());

        // $query3="DELETE FROM PenggunaanAset WHERE Penggunaan_ID='$id' AND Status=0 AND StatusMenganggur=0";
        // $exec3=$this->query($query3) or die($this->error());

        // $query4="UPDATE Aset SET LastPenggunaan_ID=NULL WHERE LastPenggunaan_ID='$id'";
        // $exec4=$this->query($query4) or die($this->error());
        
        
        $sql = array(
                'table'=>'Penggunaan',
                'field'=>"Status=0",
                'condition' => "Penggunaan_ID='$id'",
                'limit' => '1',
                );

        $res = $this->db->lazyQuery($sql,$debug,2);
        
        $sql = array(
                'table'=>'Penggunaanaset',
                'field'=>"Aset_ID",
                'condition' => "Penggunaan_ID='$id'",
                );

        $res = $this->db->lazyQuery($sql,$debug);
        if ($res){

            foreach ($res as $key => $value) {

                $sql1 = array(
                        'table'=>'Aset',
                        'field'=>"NotUse=NULL",
                        'condition' => "Aset_ID='{$value[Aset_ID]}'",
                        'limit' => '1',
                        );

                $res1 = $this->db->lazyQuery($sql1,$debug,2); 
            }
            
        }
        

        if ($res && $res1) return true;
        return false;
    }

    public function store_penetapan_penggunaan($data,$debug=false)
    {
        
        $UserNm=$_SESSION['ses_uoperatorid'];// usernm akan diganti jika session di implementasikan
        $nmaset=$data['penggu_nama_aset'];
        $nmasetsatker=$data['penggu_satker_aset'];
        $penggunaan_id=get_auto_increment("penggunaan");
        $ses_uid=$_SESSION['ses_uid'];

        $penggu_penet_eks_ket=$data['penggu_penet_eks_ket'];   
        $penggu_penet_eks_nopenet=$data['penggu_penet_eks_nopenet'];   
        $penggu_penet_eks_tglpenet=$data['penggu_penet_eks_tglpenet']; 
        $olah_tgl=  format_tanggal_db2($penggu_penet_eks_tglpenet);

        $panjang=count($nmaset);

        // $query="insert into Penggunaan (Penggunaan_ID, NoSKKDH , TglSKKDH, 
        //                                     Keterangan, NotUse, TglUpdate, UserNm, FixPenggunaan, GUID) 
        //                                 values (null,'$penggu_penet_eks_nopenet','$olah_tgl', '$penggu_penet_eks_ket','','$olah_tgl','$UserNm','1','$ses_uid')";
        $sql = array(
                'table'=>'Penggunaan',
                'field'=>'NoSKKDH , TglSKKDH, Keterangan, NotUse, TglUpdate, UserNm, FixPenggunaan, GUID',
                'value' => "'{$penggu_penet_eks_nopenet}','{$olah_tgl}', '{$penggu_penet_eks_ket}','0','{$olah_tgl}','{$UserNm}','0','{$ses_uid}'",
                );
        $res = $this->db->lazyQuery($sql,$debug,1);

        for($i=0;$i<$panjang;$i++){

            $tmp=$nmaset[$i];
            $tmp_olah=explode("<br>",$tmp);
            $asset_id[$i]=$tmp_olah[0];
            $no_reg[$i]=$tmp_olah[1];
            $nm_barang[$i]=$tmp_olah[2];

            // $query1="insert into PenggunaanAset(Penggunaan_ID,Aset_ID) values('$penggunaan_id','$asset_id[$i]')  ";
            $sql1 = array(
                'table'=>'Penggunaanaset',
                'field'=>"Penggunaan_ID,Aset_ID, kodeSatker",
                'value' => "'{$penggunaan_id}','{$nmaset[$i]}', '{$nmasetsatker[$i]}'",
                );
            $res = $this->db->lazyQuery($sql1,$debug,1);

            // $query2="UPDATE Aset SET NotUse=1, LastPenggunaan_ID='$penggunaan_id' WHERE Aset_ID='$asset_id[$i]'";
            
            
            $sql2 = array(
                'table'=>'Aset',
                'field'=>"NotUse=1",
                'condition' => "Aset_ID='{$asset_id[$i]}'",
                'limit' => '1',
                );
            $res = $this->db->lazyQuery($sql2,$debug,2);
           
        }

        

       
        if ($res) return $res;
        return false;

        
    }

    function delete_update_daftar_validasi_penggunaan($data, $debug=false)
    {

        $penggunaan_id = $data['id'];

        $sql2 = array(
            'table'=>'Penggunaan',
            'field'=>"Status=0, FixPenggunaan = 0",
            'condition' => "Penggunaan_ID='{$penggunaan_id}'",
            );
        $res2 = $this->db->lazyQuery($sql2,$debug,2);

        $sql2 = array(
            'table'=>'PenggunaanAset',
            'field'=>"Status=0",
            'condition' => "Penggunaan_ID='{$penggunaan_id}'",
            );
        $res2 = $this->db->lazyQuery($sql2,$debug,2);

        if ($res2) return true;
        return false;

    }


    public function update_validasi_penggunaan($data,$debug=false)
    {
        
        $tabeltmp = $_SESSION['penggunaan_validasi']['jenisaset'];
        $getTable = $this->getTableKibAlias($tabeltmp);
        $tabel = $getTable['listTableReal'];


        $explodeID = $data['ValidasiPenggunaan'];
        $cnt=count($explodeID);
            // echo "$cnt";
        for ($i=0; $i<$cnt; $i++){
            if($explodeID!=""){
            // $query="UPDATE Penggunaan SET Status=1 WHERE Penggunaan_ID='$explodeID[$i]'";
            $sql2 = array(
                'table'=>'Penggunaan',
                'field'=>"Status=1, FixPenggunaan = 1",
                'condition' => "Penggunaan_ID='{$explodeID[$i]}'",
                );
            $res2 = $this->db->lazyQuery($sql2,$debug,2);

            // $query1="UPDATE PenggunaanAset SET Status=1 WHERE Penggunaan_ID='$explodeID[$i]'";
            $sql3 = array(
                'table'=>'PenggunaanAset',
                'field'=>"Status=1",
                'condition' => "Penggunaan_ID='{$explodeID[$i]}'",
                );
            $res3 = $this->db->lazyQuery($sql3,$debug,2);
            }
        }


        /* Log It */

        foreach ($explodeID as $key => $value) {

            $sql = array(
                'table'=>'PenggunaanAset',
                'field'=>"Aset_ID",
                'condition' => "Penggunaan_ID='{$value}'",
                );
            $res[] = $this->db->lazyQuery($sql,$debug);
        }
        
        // pr($res);

        
        $listTable = array(
                        'A'=>'tanah',
                        'B'=>'mesin',
                        'C'=>'bangunan',
                        'D'=>'jaringan',
                        'E'=>'asetlain',
                        'F'=>'kdp');

        if ($res){
            foreach ($res as $key => $value) {

                foreach ($value as $val) {
                    
                    $sql = array(
                            'table'=>'aset',
                            'field'=>"TipeAset",
                            'condition' => "Aset_ID={$val['Aset_ID']}",
                            );
                    $result = $this->db->lazyQuery($sql,$debug);
                    $asetid[$val['Aset_ID']] = $listTable[implode(',', $result[0])];

                    $sql3 = array(
                        'table'=>'aset',
                        'field'=>"fixPenggunaan=1",
                        'condition' => "Aset_ID='{$val['Aset_ID']}'",
                        );
                    $res3 = $this->db->lazyQuery($sql3,$debug,2);

                }
                
            }

            foreach ($asetid as $key => $value) {
                logFile('log data penggunaan, Aset_ID ='.$key);
                $this->db->logIt($tabel=array($value), $Aset_ID=$key, 22);
            }
        }else{
            logFile('gagal log penggunaan');
        }

        
        // exit;
        // $query_hapus_apl="DELETE FROM apl_userasetlist WHERE aset_action='ValidasiPenggunaan[]' AND UserSes='$parameter[ses_uid]'";
        // $exec_hapus=  $this->query($query_hapus_apl) or die($this->error());
        
        if ($res2 && $res3) return true;
        return false;
        
    }

	public function retrieve_validasi_penggunaan($data,$debug=false)
    {
        $tgl_awal=$data['tglawal'];
        $tgl_akhir=$data['tglakhir'];
        $tgl_awal_fix=format_tanggal_db2($tgl_awal);
        $tgl_akhir_fix=format_tanggal_db2($tgl_akhir);
        $no_penetapan_penggunaan=$data['nopenet'];
        $kodeSatker=$data['kodeSatker'];

        $filter = "";
        if ($tgl_awal) $filter .= " AND DATE(p.TglSKKDH) >= '{$tgl_awal}' ";
        if ($tgl_akhir) $filter .= " AND DATE(p.TglSKKDH) <= '{$tgl_akhir}' ";
        if ($kodeSatker) $filter .= " AND a.kodeSatker = '{$kodeSatker}' ";

        // $tabeltmp = $_SESSION['penggunaan_validasi']['jenisaset'];
        // $getTable = $this->getTableKibAlias($tabeltmp);
        // $tabel = $getTable['listTableAbjad'];

        // $TipeAset = 
        $sql = array(
                'table'=>'penggunaanaset AS pa, aset AS a, penggunaan AS p',
                'field'=>'p.*',
                'condition' => "p.FixPenggunaan = 0 AND p.Status IS NULL $filter group by p.Penggunaan_ID",
                'limit' => '100',
                'joinmethod' => 'LEFT JOIN',
                'join' => 'pa.Aset_ID=a.Aset_ID, pa.Penggunaan_ID = p.Penggunaan_ID'
                );

        $res = $this->db->lazyQuery($sql,$debug);
        if ($res) return $res;
        return false;
    }
    
	public function retrieve_penetapan_penggunaan_edit_data($data, $debug=false)
    {
        
        $Penggunaan_ID = intval($data['id']);

        $filter = "";
        if ($Penggunaan_ID) $filter .= " AND p.Penggunaan_ID = {$Penggunaan_ID} ";

        $sql = array(
                'table'=>'penggunaanaset AS pa, aset AS a, penggunaan AS p, kelompok AS k',
                'field'=>'p.*, a.*, k.Uraian',
                'condition' => "p.FixPenggunaan = 0 $filter",
                'limit' => '100',
                'joinmethod' => 'LEFT JOIN',
                'join' => 'pa.Aset_ID=a.Aset_ID, pa.Penggunaan_ID = p.Penggunaan_ID, a.kodeKelompok = k.kode'
                );

        $res = $this->db->lazyQuery($sql,$debug);
        if ($res) return $res;
        return false;
    }
    
	public function retrieve_daftar_penetapan_penggunaan($data=array(),$debug=false)
    {
        
        // pr($data);
        $tgl_awal=$data['tglawal'];
        $tgl_akhir=$data['tglakhir'];
        $tgl_awal_fix=format_tanggal_db2($tgl_awal);
        $tgl_akhir_fix=format_tanggal_db2($tgl_akhir);
        $no_penetapan_penggunaan=$data['nopenet'];
        $kodeSatker=$data['kodeSatker'];

        $filter = "";
        if ($tgl_awal) $filter .= " AND DATE(p.TglSKKDH) >= '{$tgl_awal}' ";
        if ($tgl_akhir) $filter .= " AND DATE(p.TglSKKDH) <= '{$tgl_akhir}' ";
        if ($kodeSatker) $filter .= " AND pa.kodeSatker = '{$kodeSatker}' ";

        $username = $_SESSION['ses_uoperatorid'];

        // $tabeltmp = $_SESSION['penggunaan_penetapan']['jenisaset'];
        // $getTable = $this->getTableKibAlias($tabeltmp);
        // $tabel = $getTable['listTableAbjad'];

        // pr($_SESSION);exit; AND a.UserNm = '{$username}'
        $sql = array(
                'table'=>'penggunaan AS p, Penggunaanaset AS pa',
                'field'=>'p.*',
                'condition' => "p.NotUse = 0 AND p.FixPenggunaan = 0 AND p.Status IS NULL $filter group by p.Penggunaan_ID ",
                'limit' => '100',
                'joinmethod' => "LEFT JOIN",
                'join' => "p.Penggunaan_ID = pa.Penggunaan_ID"
                );

        $res = $this->db->lazyQuery($sql,$debug);
        if ($res) return $res;
        return false;
    }
    
	public function retrieve_penetapan_penggunaan_eksekusi($parameter, $debug=false)
    {
        $id = $_POST['Penggunaan'];
        $cols = implode(",",array_values($id));
        $jenisaset = explode(',', $parameter['jenisaset']);

        // pr($jenisaset);exit;
        logFile('Jenis aset :');
        logFile($parameter['jenisaset']);
        if ($jenisaset){

            foreach ($jenisaset as $value) {

                $table = $this->getTableKibAlias($value);
                $listTable = $table['listTable'];
                $listTableAlias = $table['listTableAlias'];
                // pr($listTable);
                 $sql = array(
                        'table'=>"aset AS a, {$listTable}, kelompok AS k",
                        'field'=>"{$listTableAlias}.*, k.Uraian",
                        'condition'=>"a.Aset_ID IN ({$cols})",
                        'limit'=>'100',
                        'joinmethod' => 'LEFT JOIN',
                        'join' => "a.Aset_ID = {$listTableAlias}.Aset_ID, {$listTableAlias}.kodeKelompok = k.Kode"
                        );

                $res[] = $this->db->lazyQuery($sql,$debug);
            }

            foreach ($res as $value) {

                if ($value){

                    foreach ($value as $val) {
                        $newData[] = $val;
                    } 
                }
                
            }

        }

        
        // pr($res);
        if ($newData) return $newData;
        return false;

    }
    
	public function retrieve_penetapan_penggunaan($parameter,$debug=false)
    {

        $jenisaset = $parameter['jenisaset'];
        $nokontrak = $parameter['nokontrak'];
        $kodeSatker = $parameter['kodeSatker'];

        $filterkontrak = "";
        if ($nokontrak) $filterkontrak .= " AND a.noKontrak = '{$nokontrak}' ";
        if ($kodeSatker) $filterkontrak .= " AND a.kodeSatker = '{$kodeSatker}' ";

        if (count($jenisaset)>0) $jenisaset = $jenisaset;
        else $jenisaset = array(1,2,3,4,5,6);
        
        if ($jenisaset){

            foreach ($jenisaset as $value) {
                
                $table = $this->getTableKibAlias($value);

                // pr($table);
                $listTable = $table['listTable'];
                $listTableAlias = $table['listTableAlias'];

                $paging = paging($parameter['page'], 100);

                $sql = array(
                        'table'=>"{$listTable}, aset AS a, kelompok AS k",
                        'field'=>"{$listTableAlias}.*, k.Uraian",
                        'condition'=>"a.Status_Validasi_Barang = 1 AND (a.NotUse IS NULL OR a.NotUse = 0) AND {$listTableAlias}.StatusTampil =1 AND {$listTableAlias}.Status_Validasi_Barang = 1 {$filterkontrak}",
                        'limit'=>"{$paging}, 100",
                        'joinmethod' => 'LEFT JOIN',
                        'join' => "{$listTableAlias}.Aset_ID = a.Aset_ID, {$listTableAlias}.kodeKelompok = k.Kode"
                        );

                $res[$value] = $this->db->lazyQuery($sql,$debug);

            }

            foreach ($res as $value) {

                if ($value){

                    foreach ($value as $val) {
                        $newData[] = $val;
                    } 
                }
                
            }
        }
        

        // pr($newData);
        
        if ($newData) return $newData;
        return false;

    }
    
    function getTableKibAlias($type=1)
    {
        $listTableAlias = array(1=>'t',2=>'m',3=>'b',4=>'j',5=>'al',6=>'kd');
        $listTableAbjad = array(1=>'A',2=>'B',3=>'C',4=>'D',5=>'E',6=>'F');

        $listTable = array(
                        1=>'tanah AS t',
                        2=>'mesin AS m',
                        3=>'bangunan AS b',
                        4=>'jaringan AS j',
                        5=>'asetlain AS al',
                        6=>'kdp AS kd');
        $listTable2 = array(
                        1=>'tanah',
                        2=>'mesin',
                        3=>'bangunan',
                        4=>'jaringan',
                        5=>'asetlain',
                        6=>'kdp');

        $data['listTable'] = $listTable[$type];
        $data['listTableAlias'] = $listTableAlias[$type];
        $data['listTableReal'] = $listTable2[$type];
        $data['listTableAbjad'] = $listTableAbjad[$type];


        return $data;
    }
}
?>