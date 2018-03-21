<?php

class RETRIEVE_MUTASI extends RETRIEVE{

	public function __construct()
	{
		parent::__construct();
                $this->db = new DB;
	}
	
    //revisi
     public function retrieve_daftar_usulan($data,$debug=false)
    {
        //pr($data);
        $tahun = $data['tahun'];
        $cndtn = trim($data['condition']);
        $limit= $data['limit'];
        $order= $data['order'];
        $paramWhere = '';
        $filter = "";
        
        if($cndtn != ''){
            $paramWhere = "AND $cndtn";
        }else{
            $paramWhere = "";
        }
         
        if($_SESSION['ses_uaksesadmin']==1){

            $kodeSatker = $_SESSION['ses_satkerkode'];
            $filter .= " AND YEAR(TglUpdate) ='{$tahun}' $paramWhere";
        }else{
            $UserName=$_SESSION['ses_uoperatorid'];
            $kodeSatker = $_SESSION['ses_satkerkode'];
           
            if ($kodeSatker) $filter .= " AND Usl.SatkerUsul LIKE '{$kodeSatker}%' AND YEAR(TglUpdate) ='{$tahun}' $paramWhere";
        }
        $sql = array(
                'table'=>'usulan as Usl,satker AS s,satker AS st',
                'field'=>"SQL_CALC_FOUND_ROWS Usl.*,s.NamaSatker as NamaSatkerAsal,st.NamaSatker as NamaSatkerTujuan",
                'condition' => "Usl.FixUsulan=1 AND Usl.Jenis_Usulan='MTS'{$filter} GROUP BY Usl.Usulan_ID $order ",
                'joinmethod' => ' INNER JOIN ',
                'limit'=>"$limit",
                'join' => 'Usl.SatkerUsul=s.kode, Usl.SatkerTujuan=st.kode'
                );
        $res = $this->db->lazyQuery($sql,$debug);
        if ($res) return $res;
        return false;
    
    }

    public function countUsulan($id){
        
        $daftarAset = array();
        $Fixdata = array();
        $count = '';
        $listAsetid = '';

        $query = "SELECT Aset_ID FROM usulanaset WHERE Usulan_ID = '{$id}'";
        $exec = $this->query($query) or die($this->error());
        while ($data = mysql_fetch_assoc($exec)) {
            $daftarAset[] = $data['Aset_ID'];
        }
        if($daftarAset){
            $count = count($daftarAset);
            $listAsetid = implode(",", $daftarAset);
            $query2 = "SELECT SUM(NilaiPerolehan) as nilai FROM aset WHERE 
                Aset_ID IN ($listAsetid)";
            $data = $this->fetch($query2);
            $Fixdata = array('count'=>$count,'nilai'=>$data['nilai']);
            //pr($Fixdata);
            return $Fixdata;    
        }else{
            $Fixdata = array('count'=>0,'nilai'=>0);
            return $Fixdata;
        }
        
    }
    
    public function create_usulan($data,$debug=false){
        //pr($data);
        //exit();
        $SatkerUsul = $data['SatkerUsul'];
        $SatkerTujuan = $data['SatkerTujuan'];
        $NoUsulan = $data['NoUsulan'];
        $TglUpdate = $data['TglUpdate'];
        $KetUsulan = addslashes($data['KetUsulan']);
        $UserNm = $_SESSION['ses_uoperatorid'];
        
        //begin transaction
        $this->db->begin();
        $sql = array(
                    'table'=>'Usulan',
                    'field'=>'SatkerUsul,SatkerTujuan,NoUsulan,TglUpdate,KetUsulan,Jenis_Usulan, UserNm, FixUsulan',
                    'value' => "'$SatkerUsul','$SatkerTujuan','$NoUsulan','$TglUpdate','$KetUsulan', 'MTS', '$UserNm','1'",
                    );
            
        $res = $this->db->lazyQuery($sql,$debug,1);
        if(!$res){
                //rollback transaction
                $this->db->rollback();
                echo "<script>
                alert('Input Data Usulan Gagal. Silahkan coba lagi');
                document.location='list_usulan.php';
                </script>";
                exit();
        }
        //commit transaction
        $this->db->commit();  
    }

    public function DataUsulan($id){

             $sql = array(
                    'table'=>'usulan',
                    'field'=>" * ",
                    'condition' => "Usulan_ID='{$id}' ORDER BY Usulan_ID desc",
                    );

            $res = $this->db->lazyQuery($sql,$debug);

        if ($res) return $res;
        return false;
    }

     public function update_usulan_penghapusan_asetid($data,$debug=false)
    {
      
        $usulanID=$data['usulanID'];
        $noUsulan=$data['NoUsulan'];
        $SatkerUsul=$data['SatkerUsul'];
        $SatkerTujuan=$data['SatkerTujuan'];
        $ketUsulan=$data['KetUsulan'];
        $olah_tgl=$data['tanggalUsulan'];    
        
        //begin transaction
        $this->db->begin();
        $sql = array(
                            'table'=>'usulan',
                            'field'=>"NoUsulan='$noUsulan',KetUsulan='$ketUsulan',TglUpdate='$olah_tgl',SatkerUsul='$SatkerUsul',SatkerTujuan='$SatkerTujuan'",
                            'condition' => "Usulan_ID='$usulanID'",
                            );
        $res = $this->db->lazyQuery($sql,$debug,2);
        if(!$res){
            //rollback transaction
            $this->db->rollback();
            echo "<script>
                    alert('Update Data Usulan Gagal. Silahkan coba lagi');
                    document.location='list_usulan.php';
                    </script>";
            exit();
        }
        //commit transaction
        $this->db->commit(); 
        if($res){
            return true;
        }else{
            return false;
        }

    }

    public function apl_userasetlistHPS($data){
        $ses_user=$_SESSION['ses_utoken'];
        $sql_apl = array(
                'table'=>"apl_userasetlist",
                'field'=>"aset_list",
                'condition' => "aset_action='{$data}' AND UserSes='{$ses_user}'",
                 );
          
        $res_apl = $this->db->lazyQuery($sql_apl,$debug);
        if ($res_apl) return $res_apl;
        return false;

    }

    public function apl_userasetlistHPS_filter($data){
        $data=explode(",",$data[0]['aset_list'] );
        foreach ($data as $key => $value) {
            if($value!=""){
                $dataku[]=$value;
            }
        }
        if ($dataku) return $dataku;
        return false;
    }
    public function apl_userasetlistHPS_del($data){

        $ses_user=$_SESSION['ses_utoken'];
        $query2="DELETE FROM apl_userasetlist WHERE aset_action='{$data}' AND UserSes='{$ses_user}'";
        $exec2=$this->query($query2) or die($this->error());
     
        if($exec2){
            return true;
        }else{
            return false;
        }

    }

    function getTableKibAliasRev($type=1)
        {
        $listTableAlias = array(1=>'t',2=>'m',3=>'b',4=>'j',5=>'al',6=>'kdp');
        $listTableAbjad = array(1=>'A',2=>'B',3=>'C',4=>'D',5=>'E',6=>'F');

        $listTable = array(
                        1=>'tanah AS t',
                        2=>'mesin AS m',
                        3=>'bangunan AS b',
                        4=>'jaringan AS j',
                        5=>'asetlain AS al',
                        6=>'kdp AS kdp');

        $listTableField = array(
                        1=>'t.NoSertifikat',
                        2=>'m.Merk,m.Model',
                        3=>'b.TglPakai',
                        4=>'j.Konstruksi,j.NoDokumen',
                        5=>'al.Pengarang,al.Penerbit,al.TahunTerbit,al.ISBN',
                        6=>'kdp.NoSertifikat,kdp.TglSertifikat');
        $listTable = array(
                        1=>'tanah AS t',
                        2=>'mesin AS m',
                        3=>'bangunan AS b',
                        4=>'jaringan AS j',
                        5=>'asetlain AS al',
                        6=>'kdp AS kdp');


        $FieltableGeneral="{$listTableAlias[$type]}.Aset_ID,{$listTableAlias[$type]}.kodeKelompok,{$listTableAlias[$type]}.kodeSatker,{$listTableAlias[$type]}.kodeLokasi,{$listTableAlias[$type]}.noRegister,{$listTableAlias[$type]}.TglPerolehan,{$listTableAlias[$type]}.TglPembukuan,{$listTableAlias[$type]}.Status_Validasi_Barang,{$listTableAlias[$type]}.StatusTampil,{$listTableAlias[$type]}.Tahun,{$listTableAlias[$type]}.NilaiPerolehan,{$listTableAlias[$type]}.Alamat,{$listTableAlias[$type]}.Info,{$listTableAlias[$type]}.AsalUsul,{$listTableAlias[$type]}.kondisi,{$listTableAlias[$type]}.CaraPerolehan";
        $listTableOri = array(
                        1=>'tanah',
                        2=>'mesin',
                        3=>'bangunan',
                        4=>'jaringan',
                        5=>'asetlain',
                        6=>'kdp');

        $data['listTable'] = $listTable[$type];
        $data['listTableAlias'] = $listTableAlias[$type];
        $data['listTableAbjad'] = $listTableAbjad[$type];
        $data['listTableOri'] = $listTableOri[$type];
        $data['listTableField'] = $listTableField[$type];
        $data['FieltableGeneral'] = $FieltableGeneral;

        return $data;
    }

    public function SelectKIB($asetid,$TipeAset){

         $TableAbjadlist = array('A'=>1,'B'=>2,'C'=>3,'D'=>4,'E'=>5,'F'=>6);

         $table = $this->getTableKibAliasRev($TableAbjadlist[$TipeAset]);

        ////////////////////////////////////////////////pr($table);
        $listTable = $table['listTable'];
        $listTableAlias = $table['listTableAlias'];
        $listTableAbjad = $table['listTableAbjad'];
        $listTableField = $table['listTableField'];
        $FieltableGeneral= $table['FieltableGeneral'];

        $sqlKIb = array(
                    'table'=>"{$listTable}",
                    'field'=>"{$FieltableGeneral},{$listTableField}",
                    'condition' => "{$listTableAlias}.Aset_ID='{$asetid}' GROUP BY {$listTableAlias}.Aset_ID",
                    );
        // ////////////////////////////////////////pr($sqlKIb);
        $resKIb = $this->db->lazyQuery($sqlKIb,$debug);
        //pr($resKIb);
        if ($resKIb) return $resKIb;
        return false;
    }

     public function getNamaSatker($kodeSatker){
        $sqlSat = array(
            'table'=>"Satker AS sat",
            'field'=>"sat.NamaSatker",
            'condition' => "sat.Kode='$kodeSatker' GROUP BY sat.Kode",
             );
        $resSat = $this->db->lazyQuery($sqlSat,$debug);
        if ($resSat) return $resSat;
        return false;

    }


    public function retrieve_usulan($data,$debug=false)
    {
        //pr($data);
        //exit;
        $jenisaset = array($data['jenisaset']);
        $kodeSatker = $data['kodeSatker'];
        $kodePemilik = $data['kodepemilik'].'%';
        $kodeKelompok = $data['kodeKelompok'];
        $tahun = $data['bup_tahun'];
        $kondisi= trim($data['condition']);
        $limit= $data['limit'];
        $order= $data['order'];
        if($kondisi){
            $paramKondisi = " AND $kondisi";
        }else{
            $paramKondisi = "";
        }
        $filterkontrak = "";
        if ($kodeKelompok) $filterkontrak .= " AND ast.kodeKelompok = '{$kodeKelompok}' ";
        if ($kodePemilik) $filterkontrak .= " 
            AND ast.kodeLokasi LIKE '{$kodePemilik}' ";

        if ($kodeSatker){ 
            $filterkontrak .= " AND ast.kodeSatker = '{$kodeSatker}' "; 
        }else{
            $kodeSatker=$_SESSION['ses_satkerkode'];
            $filterkontrak .= " AND ast.kodeSatker = '{$kodeSatker}' "; 
        }
        if ($tahun) $filterkontrak .= " AND ast.Tahun = '{$tahun}' ";
        
            foreach ($jenisaset as $values) {
                //pr($value);
                
                
                //define array
                $dataArrListUsul = array();
                $ListUsul = array();
                $dataArr = array();

                $sql1 = array(
                        'table'=>'usulanaset',
                        'field'=>"Aset_ID",
                        'condition' => "Jenis_Usulan='MTS' AND StatusValidasi='0' AND (StatusKonfirmasi='0' OR StatusKonfirmasi='1') ORDER BY Usulan_ID DESC",
                        );
                
                $resUsul = $this->db->lazyQuery($sql1,$debug);
                
                if($resUsul){
                    foreach($resUsul as $asetidUsul){
                        //list Aset_ID yang pernah diusulkan
                        $dataArrListUsul[]=$asetidUsul[Aset_ID];
                    }
                    
                    //reverse array usulan 
                    foreach(array_values($dataArrListUsul) as $v){
                        $ListUsul[$v] = 1;
                    }
                   
                    $condition="ast.fixPenggunaan=1 AND ast.Status_Validasi_Barang=1 AND (ast.kondisi=0 OR ast.kondisi=1 OR ast.kondisi=2 OR ast.kondisi=3)";    
                }else{
                    $condition="ast.fixPenggunaan=1 AND ast.Status_Validasi_Barang=1 AND (ast.kondisi=0 OR ast.kondisi=1 OR ast.kondisi=2 OR ast.kondisi=3)";
                }
                
                //query asetid 
                //cara ke dua
                if($values == 7){
                    $TipeAset = 'G';
                    $sql2 = array(
                        'table'=>"Aset AS ast,kelompok AS k",
                        'field'=>"ast.Aset_ID,ast.KodeSatker,ast.noKontrak,k.Uraian",
                        'condition' => "ast.TipeAset = '{$TipeAset}' AND ast.fixPenggunaan=1 AND ast.Status_Validasi_Barang=1 AND ast.kondisi = '3' {$filterkontrak} {$paramKondisi} GROUP BY ast.Aset_ID $order",
                        'joinmethod' => ' INNER JOIN ',
                        'join' => " ast.kodeKelompok = k.Kode"
                         );
                }else{
                    $table = $this->getTableKibAliasRev($values);
                    //pr($table);
                    //exit;
                    $listTable = $table['listTable'];
                    $listTableAlias = $table['listTableAlias'];
                    $listTableAbjad = $table['listTableAbjad'];
                    $listTableField = $table['listTableField'];
                    $FieltableGeneral= $table['FieltableGeneral'];

                    $sql2 = array(
                        'table'=>"{$listTable},Aset AS ast,kelompok AS k",
                        'field'=>"ast.Aset_ID,ast.KodeSatker,ast.noKontrak,{$listTableField},{$FieltableGeneral},k.Uraian",
                        'condition' => "ast.TipeAset = '{$listTableAbjad}' AND {$condition} {$filterkontrak} {$paramKondisi} GROUP BY ast.Aset_ID $order",
                        'joinmethod' => ' LEFT JOIN ',
                        'join' => "{$listTableAlias}.Aset_ID=ast.Aset_ID,ast.kodeKelompok = k.Kode"
                         );
                }
                    
                $resAset = $this->db->lazyQuery($sql2,$debug);
                //pr($resAset);
                if($resAset){
                    //list Usulan Aset
                    if($ListUsul){
                        //list Aset
                        foreach($resAset as $asetidAset){
                            //list Aset_ID yang pernah diusulkan
                            $needle = $asetidAset[Aset_ID];
                            //matching
                            if (!isset($ListUsul[$needle])){
                                $dataArr[] = $needle;
                            }                        
                        }
                    }else{
                        $TmpdataArr = $resAset;
                        foreach ($TmpdataArr as $key => $value) {
                            $dataArr[] = $value['Aset_ID'];
                        }
                    }
                    if($dataArr){
                        $listAsetid = implode(',',$dataArr);  
                        //$count = count($dataArr);
                        if($listAsetid){
                              $query_aset_idin="AND   ast.Aset_ID IN ($listAsetid)";
                        }else{
                            
                            $query_aset_idin="";
                        }
                        if($values == 7){
                            $TipeAset = 'G';
                            $sqlFix = array(
                                'table'=>"Aset AS ast,kelompok AS k",
                                'field'=>"ast.Aset_ID,ast.kodeSatker,ast.noKontrak,k.Uraian,ast.TglPerolehan,ast.NilaiPerolehan,ast.noRegister,ast.kondisi,ast.CaraPerolehan,ast.kodeKelompok",
                                'condition' => "ast.TipeAset = '{$TipeAset}' AND ast.Status_Validasi_Barang=1 AND ast.kondisi = '3' {$query_aset_idin} GROUP BY ast.Aset_ID $order",
                                'joinmethod' => ' INNER JOIN ',
                                'join' => " ast.kodeKelompok = k.Kode"
                                 );  
                        }else{
                            $sqlFix = array(
                                'table'=>"{$listTable},Aset AS ast,kelompok AS k",
                                'field'=>"SQL_CALC_FOUND_ROWS ast.Aset_ID,ast.kodeSatker,ast.noKontrak,{$listTableField},{$FieltableGeneral},k.Uraian",
                                'condition' => "ast.TipeAset = '{$listTableAbjad}' {$query_aset_idin} GROUP BY ast.Aset_ID $order",
                                'limit'=>"$limit",
                                'joinmethod' => ' LEFT JOIN ',
                                'join' => "{$listTableAlias}.Aset_ID=ast.Aset_ID,ast.kodeKelompok = k.Kode"
                                 );    
                        }    
                        $resAsetFix = $this->db->lazyQuery($sqlFix,$debug); 
                    }
    
                }

        }
        if ($resAsetFix) return array("data"=>$resAsetFix,"count"=> $listAsetid);
        return false;
    }

    public function retrieve_daftar_usulan_edit_data($data,$debug=false){
        
        $id=$_GET['id'];
        $limit= $data['limit'];
        $order= $data['order'];
        $condition = trim($data['condition']);
        if($condition){
            $filter = "AND ".$condition;
        }else{
            $filter = "";
        }
        $sql1 = array(
                'table'=>'usulanaset',
                'field'=>" * ",
                'condition' => "Usulan_ID='$id' ORDER BY Usulan_ID desc",
                );

        $res1 = $this->db->lazyQuery($sql1,$debug);
        //pr($res1);
        $Aset_ID = array();
        foreach ($res1 as $value) {
            # code...
            $Aset_ID[] = $value['Aset_ID'];
        }
        //pr($Aset_ID);
        
        $listAset = implode(',', $Aset_ID);
        //pr($listAset);
        
        if($listAset){
            //$Aset_ID=$this->FilterDatakoma($res1[0]['Aset_ID']);
            $sqlUsulAst = array(   
                'table'=>'usulanaset AS b,Aset AS a,Kelompok AS k',
                'field'=>"SQL_CALC_FOUND_ROWS a.Aset_ID,a.kodeSatker,a.TglPerolehan,a.kodeKelompok,a.NilaiPerolehan,a.noKontrak,a.noRegister,a.TipeAset,a.kondisi,a.AsalUsul,b.StatusKonfirmasi,b.StatusPenetapan, k.Kode,k.Uraian",
                'condition' => "b.Usulan_ID='$id' AND b.Aset_ID IN ({$listAset}) {$filter} $kondisi GROUP BY b.Aset_ID  $order ",
                        'limit'=>"$limit",
                'joinmethod' => ' INNER JOIN ',
                'join' => 'b.Aset_ID=a.Aset_ID , a.kodeKelompok=k.Kode' 
                );

                $resUsulAst = $this->db->lazyQuery($sqlUsulAst,$debug);
                if ($res1) return $resUsulAst;
                return false;  
        }else{
            return false;
        }
    }

    public function retrieve_daftar_penetapan($data,$debug=false)
    {
        $tahun = $data['tahun'];
        $kondisi = trim($data['condition']);
        $limit= $data['limit'];
        $order= $data['order'];
        $paramWhere = '';
        $filter = "";
        
        if($kondisi != ''){
            $paramWhere = "AND $kondisi";
        }else{
            $paramWhere = "";
        }

        if($_SESSION['ses_uaksesadmin']==1){

            $kodeSatker = $_SESSION['ses_satkerkode'];
            $filter .= " AND YEAR(P.TglSKKDH) ='{$tahun}' $paramWhere";
        }else{
            $UserName=$_SESSION['ses_uoperatorid'];
            $kodeSatker = $_SESSION['ses_satkerkode'];
           
            if ($kodeSatker) $filter .= " AND P.SatkerUsul LIKE '{$kodeSatker}%' AND YEAR(P.TglSKKDH) ='{$tahun}' $paramWhere";
        }

         
            $sql = array(
                    'table'=>'mutasi as P,satker AS s',
                    'field'=>" SQL_CALC_FOUND_ROWS P.*,s.NamaSatker",
                    'condition' => "(P.FixMutasi = 0 or P.FixMutasi = 1) {$filter} GROUP BY P.Mutasi_ID  {$order} ",
                    'joinmethod' => ' INNER JOIN ',
                    'join' => 'P.SatkerUsul=s.kode',
                    'limit'=>"$limit",
                    );
            $res = $this->db->lazyQuery($sql,$debug);
        if ($res) return $res;
        return false;
        
    }

    public function totalNilaiMutasiAset($id){
        if($id){
             $sql = array(
                    'table'=>'mutasiaset as pa,aset as ast',
                    'field'=>" pa.Mutasi_ID,pa.Aset_ID,ast.NilaiPerolehan ",
                    'condition' => "pa.Mutasi_ID='{$id}' GROUP BY ast.Aset_ID ",
                'joinmethod' => ' LEFT JOIN ',
                'join' => 'pa.Aset_ID = ast.Aset_ID'
                    );

            $res = $this->db->lazyQuery($sql,$debug);
            $res1['TotalNilaiPerolehan']=0;
            
            foreach ($res as $keyAst => $valueAst) {
                $res1['TotalNilaiPerolehan']=$res1['TotalNilaiPerolehan']+$valueAst['NilaiPerolehan'];
           
                }
        }
        
        if ($res) return $res1;
        return false;
    }

     public function totalDataMutasiAset($id){

             $sql = array(
                    'table'=>'mutasiaset',
                    'field'=>" * ",
                    'condition' => "Mutasi_ID='{$id}' ORDER BY Mutasi_ID desc",
                    );

            $res = $this->db->lazyQuery($sql,$debug);
         

        if ($res) return $res;
        return false;
    }


    public function DataPenetapan($id){

             
            $sql = array(
                    'table'=>'mutasi',
                    'field'=>" * ",
                    'condition' => "Mutasi_ID='$id' ORDER BY Mutasi_ID desc",
                    );

            $res = $this->db->lazyQuery($sql,$debug);

        if ($res) return $res;
        return false;
    }

    public function create_penetapan($data,$debug=false){
        $SatkerUsul = $data['SatkerUsul'];
        $NoSKKDH = $data['NoSKKDH'];
        $TglSKKDH = $data['TglSKKDH'];
        $Keterangan = addslashes($data['Keterangan']);
        $UserNm = $_SESSION['ses_uoperatorid'];
        //begin transaction
        $this->db->begin();
        $sql = array(
                    'table'=>'mutasi',
                    'field'=>'SatkerUsul,NoSKKDH,TglSKKDH,Keterangan,UserNm,FixMutasi',
                    'value' => "'$SatkerUsul','$NoSKKDH','$TglSKKDH','$Keterangan','$UserNm','0'",
                    );
            
        $res = $this->db->lazyQuery($sql,$debug,1);
        if(!$res){
                //rollback transaction
                $this->db->rollback();
                echo "<script>
                alert('Input Data Usulan Gagal. Silahkan coba lagi');
                document.location='list_penetapan.php';
                </script>";
                exit();
        }
        //commit transaction
        $this->db->commit();  
    }

    public function update_penetapan_penghapusan_asetid($data,$debug=false)
    {
      
        //pr($_POST);
        // exit;
        $Mutasi_ID=$data['Mutasi_ID'];
        $NoSKKDH=$data['NoSKKDH'];
        $Keterangan=$data['Keterangan'];
        $TglSKKDH=$data['TglSKKDH'];    
        $SatkerUsul=$data['SatkerUsul'];    
        //begin transaction
        $this->db->begin();
        $sql2 = array(
                            'table'=>'mutasi',
                            'field'=>"NoSKKDH='$NoSKKDH',Keterangan='$Keterangan',TglSKKDH='$TglSKKDH', SatkerUsul= '$SatkerUsul'",
                            'condition' => "Mutasi_ID='$Mutasi_ID'",
                            );
        $res2 = $this->db->lazyQuery($sql2,$debug,2);
        if(!$res2){
            //rollback transaction
            $this->db->rollback();
            echo "<script>
                    alert('Update Data Penetapan Gagal. Silahkan coba lagi');
                    document.location='list_penetapan.php';
                    </script>";
            exit();
        }
        //commit transaction
        $this->db->commit(); 
        if($res2 ){
            return true;
        }else{
            return false;
        }

    }

     public function retrieve_penetapan_list_usulan($data,$debug=false)
    { 
        //pr($data);

        $nousulan = $data['bup_pp_sp_nousulan'];
        $kodeSatker = $data['kodeSatker'];
        $jenis_usulan="MTS";

        $kondisi= trim($data['condition']);
        if($kondisi!="")$kondisi=" and $kondisi";
        $limit= $data['limit'];
        $order= $data['order'];

        if($data['bup_pp_sp_tglusul']){
            $tanggalmts=$data['bup_pp_sp_tglusul'];
        }else{
            $tanggalmts="";
        }
        
        $filterkontrak = "";
        if ($nousulan) $filterkontrak .= " AND us.NoUsulan = '{$nousulan}' ";
        if ($kodeSatker) $filterkontrak .= " AND us.SatkerUsul LIKE '{$kodeSatker}%' ";
        if ($tanggalmts) $filterkontrak .= " AND us.TglUpdate = '{$tanggalmts}' ";
          
        if($kodeSatker){
         
            $sql = array(
                            'table'=>'usulan AS us,usulanaset AS usa',
                            'field'=>" SQL_CALC_FOUND_ROWS us.Usulan_ID,us.StatusPenetapan,us.NoUsulan,us.SatkerUsul,us.TglUpdate,us.KetUsulan,COUNT(usa.Aset_ID) as jmlaset ",
                            'condition' => "us.FixUsulan=1 AND us.Jenis_Usulan='$jenis_usulan'  AND us.StatusPenetapan=0 {$filterkontrak} {$kondisi} GROUP BY us.Usulan_ID {$order} ",
                            'joinmethod' => ' INNER JOIN ',
                            'join' => 'us.Usulan_ID = usa.Usulan_ID',
                             'limit'=>"$limit"
                            );
            $res = $this->db->lazyQuery($sql,$debug);
                
        }
            if ($res) return $res;
            return false;
    } 

    public function TotalNilaiPerolehanRev($Usulan_ID){

            if($Usulan_ID){

                $sqlUsln = array(
                    'table'=>'usulanaset',
                    'field'=>" Aset_ID ",
                    'condition' => "Usulan_ID = '{$Usulan_ID}'"
                    );
            
                $resUsln = $this->db->lazyQuery($sqlUsln,$debug);
                $list = array();
                foreach ($resUsln as $value) {
                    $list[] = $value['Aset_ID'];
                }
                $listAset=implode(",", $list);
                $sqlAst = array(
                    'table'=>'aset',
                    'field'=>" sum(nilaiPerolehan) as nilai ",
                    'condition' => "Aset_ID in ({$listAset})"
                    );
            
                $resAst = $this->db->lazyQuery($sqlAst,$debug);

        if ($resAst) return $resAst['0']['nilai'];
        return false;

        }
    }

    public function  retrieve_penetapan_eksekusi($data,$debug=false)
    {
        //pr($data);
        $id = $data['id'];
        $condition = trim($data['condition']);
        $order = $data['order'];
        $limit = trim($data['limit']);
        //$listUsulan = explode(',', $id);
        $jenis_hapus="MTS";
        
        if($condition){
            $condt = $condition." AND ";
        }else{
            $condt ="";
        }
        
        $sqlUsl = array(
                    'table'=>'usulanaset',
                    'field'=>" Aset_ID ",
                    'condition' => "Usulan_ID IN ($id) AND Jenis_Usulan='$jenis_hapus' ORDER BY Aset_ID asc"
                    );
            
        $resUsl = $this->db->lazyQuery($sqlUsl,$debug);
        $list = array();
        if($resUsl){
            foreach ($resUsl as $key => $value) {
                # code...
                $list[] = $value['Aset_ID']; 
            }
            if($list){
                $listAset = implode(',', $list);
                $sqlUsulAst = array(   
                        'table'=>'aset AS a,kelompok AS k',
                        'field'=>"SQL_CALC_FOUND_ROWS a.Aset_ID,a.kodeSatker,a.TglPerolehan,a.kodeKelompok,a.NilaiPerolehan,a.noKontrak,a.noRegister,a.TipeAset,a.kondisi,a.AsalUsul, k.Kode,k.Uraian",
                        'condition' => "{$condt} a.Aset_ID IN ({$listAset}) 
                          {$order}",
                        'limit' => $limit,
                        'joinmethod' => ' INNER JOIN ',
                        'join' => ' a.kodeKelompok=k.Kode'
                        );
                $resUsulAst = $this->db->lazyQuery($sqlUsulAst,$debug);
            }

        }
        
        if ($resUsulAst) return $resUsulAst;
        return false;
        
    }

    public function retrieve_daftar_aset_penetapan($data,$debug=false){
        $id=$data['id'];
        $limit= $data['limit'];
        $order= $data['order'];
        $condition = trim($data['condition']);
        if($condition){
            $filter = "AND ".$condition;
        }else{
            $filter = "";
        }
        //pr($filter);
        $sql1 = array(
                'table'=>'mutasiaset',
                'field'=>" * ",
                'condition' => "Mutasi_ID='$id' ORDER BY Mutasi_ID desc",
                );

        $res1 = $this->db->lazyQuery($sql1,$debug);
        //pr($res1);
        $Aset_ID = array();
        if($res1){
            foreach ($res1 as $value) {
            # code...
            $Aset_ID[] = $value['Aset_ID'];
        }
            //pr($Aset_ID);
            $listAset = implode(',', $Aset_ID);
            //pr($listAset);
            if($listAset){
            $sqlUsulAst = array(   
                'table'=>'Aset AS a,Kelompok AS k',
                'field'=>"SQL_CALC_FOUND_ROWS a.Aset_ID,a.kodeSatker,a.TglPerolehan,a.kodeKelompok,a.NilaiPerolehan,a.noKontrak,a.noRegister,a.TipeAset,a.kondisi,a.AsalUsul, k.Kode,k.Uraian",
                'condition' => " a.Aset_ID IN ({$listAset}) {$filter} {$order} ",
                'joinmethod' => ' INNER JOIN ',
                'join' => 'a.kodeKelompok=k.Kode', 
                'limit'=>"$limit"
                );

                $resUsulAst = $this->db->lazyQuery($sqlUsulAst,$debug);
                //pr($resUsulAst);
                if ($resUsulAst) return $resUsulAst;
                return false;  
            }else{
                return false;
            }
        }
    }

    public function getStatusMutasi($Mutasi_ID){
        //pr($Penghapusan_ID);
        $sql1 = array(
                'table'=>'mutasi',
                'field'=>" FixMutasi",
                'condition' => "Mutasi_ID='$Mutasi_ID'",
                );

        $res = $this->db->lazyQuery($sql1,$debug);
        if ($res) return $res;
        return false;
        
    }


    public function retrieve_list_validasi($data,$debug=false){
        //pr($data);
        $tahun = $data['tahun'];
        $cndtn = trim($data['condition']);
        $limit= $data['limit'];
        $order= $data['order'];
        $filter = "";
        $jenis_hapus = 'PMD';
        if($cndtn != ''){
            $filter = "AND $cndtn";
        }else{
            $filter = "";
        }
         
        $sql = array(
            'table'=>'mutasi ',
            'field'=>"SQL_CALC_FOUND_ROWS * ",
            'condition' => "FixMutasi=1 AND YEAR(TglSKKDH) ='{$tahun}' {$filter} {$order}",
            'limit'=>$limit
            );
        $res = $this->db->lazyQuery($sql,$debug);
        if($res) return $res;
        return false;
    }

     public function retrieve_daftar_penetapan_mutasi_validasi($data,$debug=false)
    {
        
        $sql = array(
                'table'=>'mutasi',
                'field'=>" * ",
                'condition' => "FixMutasi=0 ORDER BY Mutasi_ID desc"
                );
        $res = $this->db->lazyQuery($sql,$debug);
        if($res){
            foreach ($res as $keySat => $valueSat) {
                $SatkerKodenama=$valueSat['SatkerUsul'];
                $sqlSat = array(
                    'table'=>'Satker',
                    'field'=>" NamaSatker ",
                    'condition' => "kode='$SatkerKodenama' GROUP BY kode"
                    );
                $resSat = $this->db->lazyQuery($sqlSat,$debug);

                $res[$keySat]['NamaSatkerUsul']=$resSat[0]['NamaSatker'];
            }    
        }
        if ($res) return $res;
        return false;
        
    }








    function removeAsetList($action)
    {
        $userid = $_SESSION['ses_uoperatorid'];
        $token = $_SESSION['ses_utoken'];
        $sql = "DELETE FROM apl_userasetlist WHERE aset_action = '{$action}' AND UserNm = {$userid} AND UserSes = '{$token}' LIMIT 1";
        $res = $this->db->query($sql);
        if ($res) return true;
        return false;
    }
    
    function getAsetList($action)
    {
        // pr($_SESSION);
        $userid = $_SESSION['ses_uoperatorid'];
        $token = $_SESSION['ses_utoken'];
        $sql = array(
                'table'=>"apl_userasetlist",
                'field'=>"aset_list",
                'condition'=>"aset_action = '{$action}' AND UserNm = {$userid} AND UserSes = '{$token}' LIMIT 1",
                );

        $res = $this->db->lazyQuery($sql,$debug);
        if ($res){
            $newData = explode(',', $res[0]['aset_list']);

            return $newData;
        }

        return false;
    }

    /*function removeAsetList($action)
    {
        $userid = $_SESSION['ses_uoperatorid'];
        $sql = "DELETE FROM apl_userasetlist WHERE aset_action = '{$action}' AND UserNm = {$userid} LIMIT 1";

        $res = $this->db->query($sql);
        if ($res){
            return true;
        }

        return false;
    } */

	function retrieve_mutasi_filter($data,$debug=false)
	{
        
        
  	    $jenisaset = $data['jenisaset'];
        $nokontrak = $data['mutasi_trans_filt_nokontrak'];
        $kodeSatker = $data['kodeSatker'];
        $tahunAset=$data['mutasi_trans_filt_thn'];
        
	    $filterkontrak = "";
        if ($nokontrak) $filterkontrak .= " AND a.noKontrak = '{$nokontrak}' ";
        

        $kondisi= trim($data['condition']);
        if($kondisi!="")$kondisi=" and $kondisi";
        $limit= $data['limit'];
        $order= $data['order'];


        $getAsetList = $this->getAsetList('MUTASI');
        if (!$getAsetList) $getAsetList = array();
        // pr($getAsetList);
        
        if ($jenisaset){
            // get data kib
            $satker = "";
            foreach ($jenisaset as $value) {

                $filterKib = "";
                
                $table = $this->getTableKibAlias($value);
                $listTable = $table['listTable'];
                $listTableAlias = $table['listTableAlias'];
                $listTableAbjad = $table['listTableAbjad'];
                $alias[] = "'".strtoupper($listTableAbjad)."'";

                if ($tahunAset) $filterKib .= " AND {$listTableAlias}.Tahun = '{$tahunAset}' ";
                if ($kodeSatker) $filterKib .= " AND {$listTableAlias}.kodeSatker = '{$kodeSatker}' ";


                $sql = array(
                        'table'=>"{$listTable}, penggunaanaset AS pa",
                        'field'=>"{$listTableAlias}.*" ,
                        'condition'=>"{$listTableAlias}.StatusValidasi = 1 AND {$listTableAlias}.StatusTampil = 1 AND {$listTableAlias}.Status_Validasi_Barang = 1 AND pa.Status = 1 AND pa.StatusMenganggur = 0 AND pa.StatusMutasi = 0 {$filterKib} ",
                        'joinmethod' => 'INNER JOIN',
                        'join' => "{$listTableAlias}.Aset_ID = pa.Aset_ID"
                        );

                $res[$value] = $this->db->lazyQuery($sql,$debug);
            }

            if ($res){

                foreach ($res as $key => $value) {
                    if ($value){
                        foreach ($value as $val) {
                            $listKibAset[$val['Aset_ID']] = $val;
                        }
                    }
                    
                }
                
                if (!$listKibAset) return false;

                $asetIDKib = implode(',', array_keys($listKibAset));
                $implodeJenis = implode(',', $alias);
                
                if ($asetIDKib){

                    $paging = paging($data['page'], 100);
                    $merk = "";
                    
                    /*
                    $sql = array(
                            'table'=>"aset AS a, penggunaanaset AS pa, penggunaan AS p, {$listTable}, kelompok AS k, satker AS s",
                            'field'=>"SQL_CALC_FOUND_ROWS DISTINCT(a.Aset_ID), {$listTableAlias}.*, k.Uraian, k.kode, a.noKontrak, s.NamaSatker " ,
                            'condition'=>"a.TipeAset = '{$listTableAbjad}' AND pa.Status = 1 AND p.FixPenggunaan = 1 AND p.Status = 1 AND pa.StatusMenganggur = 0 AND pa.StatusMutasi = 0 {$filterkontrak} {$order} ",
                            'limit'=>"{$limit}",
                            'joinmethod' => 'INNER JOIN',
                            'join' => "a.Aset_ID = pa.Aset_ID, pa.Penggunaan_ID = p.Penggunaan_ID, pa.Aset_ID = {$listTableAlias}.Aset_ID, {$listTableAlias}.kodeKelompok = k.Kode, a.kodeSatker = s.kode"
                            );
                    */
                    $sql = array(
                            'table'=>"aset AS a, kelompok AS k, satker AS s",
                            'field'=>"SQL_CALC_FOUND_ROWS k.Uraian, k.kode, a.*, s.NamaSatker " ,
                            'condition'=>"a.Aset_ID IN ({$asetIDKib}) AND a.TipeAset IN ({$implodeJenis}) {$filterkontrak} $kondisi GROUP BY a.Aset_ID {$order} ",
                            'limit'=>"{$limit}",
                            'joinmethod' => 'INNER JOIN',
                            'join' => "a.kodeKelompok = k.Kode, a.kodeSatker = s.kode"
                            );

                    $res = $this->db->lazyQuery($sql,$debug);

                    // $total[] = $this->db->countData($sql,1);
                
                    $listAsetID = array_keys($listKibAset);

                    if ($res){

                        foreach ($res as $k => $value) {

                            if ($value){

                                
                                if (in_array($value['Aset_ID'], $getAsetList)) $res[$k]['checked'] = true;
                                else $res[$k]['checked'] = false;
                                // $res[$k]['Uraian'] = $value['Uraian'];    
                                // $res[$k]['kode'] = $value['kode'];
                                // $res[$k]['noKontrak'] = $value['noKontrak'];
                                // $res[$k]['NamaSatker'] = $value['NamaSatker'];
                            }
                            
                        }
                        
                        // pr($res);exit;
                        foreach ($res as $k => $value) {

                            if ($value){
                                
                                if ($value['NilaiPerolehan']) $res[$k]['NilaiPerolehan'] = number_format($value['NilaiPerolehan']);
                                
                            }
                            
                        }

                        if ($res) return $res;
                    }

                    
                }
            }

                

                
                
        }
        
        // pr($res);exit;
        
        return false;
	}


    function retrieve_mutasi_hasil_daftar($data,$debug=false)
    {
    

    $querypenggunaan ="SELECT Aset_ID FROM PenggunaanAset WHERE Aset_ID IN ({$dataImplode}) AND StatusMenganggur=0 AND StatusMutasi=0";     
     

    $jenisaset = $data['jenisaset'];
    $nokontrak = $data['nokontrak'];
    $kodeSatker = $data['kodeSatker'];

    $filterkontrak = "";
    if ($nokontrak) $filterkontrak .= " AND a.noKontrak = '{$nokontrak}' ";
    if ($kodeSatker) $filterkontrak .= " AND a.kodeSatker = '{$kodeSatker}' ";


    
    $table = $this->getTableKibAlias($jenisaset);

    // pr($jenisaset);
    $listTable = $table['listTable'];
    $listTableAlias = $table['listTableAlias'];
    $listTableAbjad = $table['listTableAbjad'];

    $sql = array(
            'table'=>"aset AS a, penggunaanaset AS pa, penggunaan AS p, {$listTable}, kelompok AS k, satker AS s",
            'field'=>"DISTINCT(a.Aset_ID), {$listTableAlias}.*, k.Uraian, a.noKontrak, s.NamaSatker",
            'condition'=>"a.TipeAset = '{$listTableAbjad}' AND pa.Status = 1 AND p.FixPenggunaan = 1 AND p.Status = 1 AND pa.StatusMenganggur = 0 AND pa.StatusMutasi = 0 {$filterkontrak}",
            // 'limit'=>'100',
            'joinmethod' => 'LEFT JOIN',
            'join' => "a.Aset_ID = pa.Aset_ID, pa.Penggunaan_ID = p.Penggunaan_ID, pa.Aset_ID = {$listTableAlias}.Aset_ID, {$listTableAlias}.kodeKelompok = k.Kode, a.kodeSatker = s.kode"
            );

    $res = $this->db->lazyQuery($sql,$debug);
    if ($res) return $res;
    return false;
    }

    
    function edit_usulan_mutasi($data, $debug=false)
    {


        $sql = array(
                'table'=>"mutasi AS m",
                'field'=>"m.*",
                'condition'=>"m.Mutasi_ID = {$data['id']}",
                'limit'=>'1',
                );

        $res = $this->db->lazyQuery($sql,$debug);
        if ($res) return $res;
        return false;
    }   

    function update_usulan_mutasi_barang($data, $debug=false)
    {

        $olah_tgl=$data['mutasi_trans_eks_tglproses'];
        
        $sql2 = array(
                'table'=>"mutasi",
                'field'=>"NoSKKDH = '{$data[mutasi_trans_eks_nodok]}', TglSKKDH = '{$olah_tgl}', Keterangan = '{$data[mutasi_trans_eks_alasan]}', SatkerTujuan = '{$data[kodeSatker]}', Pemakai = '{$data[mutasi_trans_eks_pemakai]}'",
                'condition'=>"Mutasi_ID='$data[Mutasi_ID]'",
                );

        $res2 = $this->db->lazyQuery($sql2,$debug,2);

        $sql2 = array(
                'table'=>"mutasiaset",
                'field'=>"SatkerTujuan = '{$data[kodeSatker]}'",
                'condition'=>"Mutasi_ID='$data[Mutasi_ID]'",
                );

        $res2 = $this->db->lazyQuery($sql2,$debug,2);
        // exit;
        if ($res2) return true;
        return false; 
    }

    function retrieve_mutasi_eksekusi($data,$debug=false)
    {

        // pr($data);
        if ($data['id']){

            $newData = $this->edit_usulan_mutasi($data, $debug);

        }else{
            $jenisaset = explode(',', $data['jenisaset']);
            $nokontrak = $data['nokontrak'];
            $kodeSatker = $data['kodeSatker'];

            $mutasi = implode(',', $data['Mutasi']);

            $filterkontrak = "";
            if ($nokontrak) $filterkontrak .= " AND a.noKontrak = '{$nokontrak}' ";
            if ($kodeSatker) $filterkontrak .= " AND a.kodeSatker = '{$kodeSatker}' ";
            if ($mutasi) $filterkontrak .= " AND a.Aset_ID IN ({$mutasi}) ";

            foreach ($jenisaset as $value) {

                $table = $this->getTableKibAlias($value);

                // pr($table);
                $listTable = $table['listTable'];
                $listTableAlias = $table['listTableAlias'];
                $listTableAbjad = $table['listTableAbjad'];

                $sql = array(
                        'table'=>"aset AS a, penggunaanaset AS pa, penggunaan AS p, {$listTable}, kelompok AS k, satker AS s",
                        'field'=>"DISTINCT(a.Aset_ID), {$listTableAlias}.*, k.Uraian, a.noKontrak, s.NamaSatker, a.TipeAset",
                        'condition'=>"a.TipeAset = '{$listTableAbjad}' AND pa.Status = 1 AND p.FixPenggunaan = 1 AND p.Status = 1 AND pa.StatusMenganggur = 0 AND pa.StatusMutasi = 0 {$filterkontrak} GROUP BY a.Aset_ID",
                        // 'limit'=>'100',
                        'joinmethod' => 'LEFT JOIN',
                        'join' => "a.Aset_ID = pa.Aset_ID, pa.Penggunaan_ID = p.Penggunaan_ID, pa.Aset_ID = {$listTableAlias}.Aset_ID, {$listTableAlias}.kodeKelompok = k.Kode, a.kodeSatker = s.kode"
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
            

            

            // pr($newData);
            if ($newData) return $newData;
            return false;
    }

    function getJenisAset($data, $debug=false)
    {
        // pr($data);

        
        foreach ($data as $key => $value) {

            
            $sqlSelect = array(
                    'table'=>"Aset",
                    'field'=>"TipeAset",
                    'condition'=>"Aset_ID = '{$value}'",
                    );

            $result[] = $this->db->lazyQuery($sqlSelect,$debug);
        }
        
        // pr($result);

        $revert = array('A'=>1, 'B'=>2, 'C'=>3, 'D'=>4, 'E'=>5, 'F'=>6);
        if ($result){
            foreach ($result as $key => $value) {
                $dataType[] = $revert[$value[0]['TipeAset']];
            }
        }
        // pr($dataType);

        return $dataType;

    }

    function retrieve_usulan_mutasi($data, $debug=false)
    {

        // pr($_SESSION);

        logFile(serialize($_SESSION));
        $ses_satkerkode = $_SESSION['ses_param_mutasi']['kodeSatker'];

        $filter = "";
        if ($ses_satkerkode) $filter .= "AND ma.SatkerAwal LIKE '{$ses_satkerkode}%'";

        $paging = paging($data['page'], 100);
        
        $sqlSelect = array(
            'table'=>"mutasiaset AS ma",
            'field'=>"ma.Mutasi_ID, ma.SatkerAwal, ma.NamaSatkerAwal, (SELECT NamaSatker FROM satker WHERE kode = ma.SatkerAwal LIMIT 1) AS NamaSatkerAwalAset, COUNT(ma.Aset_ID) AS Jumlah",
            'condition'=>"ma.SatkerTujuan !='' {$filter} GROUP BY ma.Mutasi_ID ORDER BY ma.Mutasi_ID DESC",
            // 'limit'=>"{$paging}, 100",
            );

        $result = $this->db->lazyQuery($sqlSelect,$debug);

        // pr($result);
        if ($result){

            

            foreach ($result as $key => $value) {

                $sortByMutasiID[$value['Mutasi_ID']] = $value; 

                $sqlSelect = array(
                    'table'=>"mutasi AS m, satker AS s",
                    'field'=>"m.*, s.NamaSatker",
                    'condition'=>" Mutasi_ID = '{$value[Mutasi_ID]}' AND s.Kd_Ruang IS NULL AND m.TglSKKDH > '2014-01-01' AND m.TglSKKDH IS NOT NULL ORDER BY m.TglSKKDH DESC",
                    'joinmethod' => 'LEFT JOIN',
                    'join'=>'m.SatkerTujuan = s.kode',
                    );

                $tmpRes = $this->db->lazyQuery($sqlSelect,$debug);
                if ($tmpRes){

                    foreach ($tmpRes as $key => $val) {
                        $mutasiNew[$val['Mutasi_ID']] = $val;
                    }
                    // logFile('data res before add '.serialize($tmpRes));
                    // $res[] = $tmpRes;
                    // $res[$key][0]['SatkerAwal'] = $value['SatkerAwal'];
                    // $res[$key][0]['NamaSatkerAwal'] = $value['NamaSatkerAwal'];
                    // $res[$key][0]['NamaSatkerAwalAset'] = $value['NamaSatkerAwalAset'];
                    // $res[$key][0]['Jumlah'] = intval($value['Jumlah']);

                    // logFile('data res after add '.serialize($tmpRes));
                }
                
            }
            
            if ($mutasiNew){

                foreach ($mutasiNew as $key => $value) {
                    $res[$value['Mutasi_ID']] = array_merge($value, $sortByMutasiID[$value['Mutasi_ID']]);
                }

                // pr($res);
                // pr($mutasiNew);
                if ($res){
                    foreach ($res as $value) {

                        if ($value){
                            
                            
                                if ($value['Mutasi_ID'])$newData[] = $value;
                             
                        }
                        
                    }
                    // pr($newData);
                    return $newData;  
                }
            }
            
            
        } 
        return false;
    }

    function store_usulan_mutasi_barang($data, $debug=false)
    {

        $jenisaset = $data['jenisaset'];

        $satker=$data['kodeSatker']; 
        $nodok=$data['mutasi_trans_eks_nodok'];
        $olah_tgl=$data['mutasi_trans_eks_tglproses'];
        // $olah_tgl=  format_tanggal_db2($tgl);
        $alasan=$data['mutasi_trans_eks_alasan'];
        $pemakai=$data['mutasi_trans_eks_pemakai'];
        $kodeKelompok = $data['kodeKelompok'];

        $satkerAwal=$data['lastSatker'];
        $kelompokAwal=$data['lastKelompok'];
        $lokasiAwal=$data['lastLokasi'];
        $registerAwal=$data['lastNoRegister'];
        $namaSatkerAwal=$data['lastNamaSatker'];

        $UserNm=$_SESSION['ses_uoperatorid'];// usernm akan diganti jika session di implementasikan
        $nmaset=$data['mutasi_nama_aset'];
        $asset_id=Array();
        $no_reg=Array();
        $nm_barang=Array();
        $asetKapitalisasi = array();
        $asetKapitalisasi = array_keys($_POST['asetKapitalisasi']);
        $asetKapitalisasiOri = array();
        $asetKapitalisasiOri = $_POST['asetKapitalisasi'];

        $mutasi_id=get_auto_increment("Mutasi");
        
        $this->db->autocommit(0);
        $this->db->begin();
        
        logFile('Start transaksi mutasi');
        // pr($jenisaset);

        $listTable = array(
                'A'=>'tanah',
                'B'=>'mesin',
                'C'=>'bangunan',
                'D'=>'jaringan',
                'E'=>'asetlain',
                'F'=>'kdp');


        $panjang=count($nmaset);

       
        $sql = array(
                'table'=>"Mutasi",
                'field'=>"NoSKKDH , TglSKKDH, Keterangan, SatkerTujuan, NotUse, TglUpdate, UserNm, FixMutasi, Pemakai",
                'value'=>"'$nodok','$olah_tgl', '$alasan','$satker',0,'$olah_tgl','$UserNm','0','$pemakai'",
                );

        $res = $this->db->lazyQuery($sql,$debug,1);
        if (!$res){
            logFile('rollback 1');
            $this->db->rollback();
            return false;   
        }

        for($i=0;$i<$panjang;$i++){
            
            $getJenisAset = $this->getJenisAset($nmaset);

            $getKIB = $this->getTableKibAlias($getJenisAset[$i]);

            $tmp=$nmaset[$i];
            $tmp_olah=explode("<br/>",$tmp);
            $asset_id[$i]=$tmp_olah[0];
            $no_reg[$i]=$tmp_olah[1];
            $nm_barang[$i]=$tmp_olah[2];
            
           
            $lokasiBaru = ubahLokasi($lokasiAwal[$i],$satker);
            
            
            
            $sqlSelect = array(
                    'table'=>"Aset",
                    'field'=>"MAX( CAST( noRegister AS SIGNED ) ) AS noRegister",
                    'condition'=>"kodeKelompok = '{$kelompokAwal[$i]}' AND kodeLokasi = '{$lokasiBaru}'",
                    );

            $result = $this->db->lazyQuery($sqlSelect,$debug);
            
            $gabung_nomor_reg_tujuan=intval(($result[0]['noRegister'])+1);

            logFile('Asetid = '.$asset_id[$i]);
            /*
            // log start
            $noDok = array('penggu_penet_eks_nopenet','mutasi_trans_eks_nodok');

            foreach ($_POST as $key => $value) {
                if(in_array($value, $noDok)) $noDokumen = $_POST[$value];
                else $noDokumen = '-';
            }
            
            logFile('start log');
            if (!in_array($asset_id[$i], $asetKapitalisasi)){
                $this->db->logIt($tabel=array($getKIB['listTableOri']), $Aset_ID=$asset_id[$i], $kd_riwayat=3, $noDokumen=$nodok, $tglProses =$olah_tgl, $text="Usulan Mutasi");
            }else{
                $this->db->logIt($tabel=array($getKIB['listTableOri']), $Aset_ID=$asset_id[$i], $kd_riwayat=28, $noDokumen=$nodok, $tglProses =$olah_tgl, $text="Usulan Mutasi dengan mode kapitalisasi", $tmpSatker=$asetKapitalisasiOri[$key]);
            }
            logFile('finish log');
            
            // end log
            */

            if (!in_array($asset_id[$i], $asetKapitalisasi)){
                $sql1 = array(
                        'table'=>"MutasiAset",
                        'field'=>"Mutasi_ID,Aset_ID,NamaSatkerAwal, NomorRegAwal,NomorRegBaru,SatkerAwal,SatkerTujuan",
                        'value'=>"'$mutasi_id','$asset_id[$i]','$namaSatkerAwal[$i]','$registerAwal[$i]','$gabung_nomor_reg_tujuan','$satkerAwal[$i]','$satker'",
                        );

                $res1 = $this->db->lazyQuery($sql1,$debug,1);
                if (!$res1){
                    logFile('rollback 3');
                    $this->db->rollback();
                    return false;   
                }   
            }else{

                 $sql1 = array(
                        'table'=>"MutasiAset",
                        'field'=>"Mutasi_ID,Aset_ID,NamaSatkerAwal, NomorRegAwal,NomorRegBaru,SatkerAwal,SatkerTujuan, Aset_ID_Tujuan",
                        'value'=>"'$mutasi_id','$asset_id[$i]','$namaSatkerAwal[$i]','$registerAwal[$i]','$gabung_nomor_reg_tujuan','$satkerAwal[$i]','$satker', {$asetKapitalisasiOri[$asset_id[$i]]}",
                        );

                $res1 = $this->db->lazyQuery($sql1,$debug,1);
                if (!$res1){
                    logFile('rollback 4');
                    $this->db->rollback();
                    return false;   
                }
            }

            $sql2 = array(
                    'table'=>"Aset",
                    'field'=>"StatusValidasi = 2",
                    'condition'=>"Aset_ID='$asset_id[$i]'",
                    );

            $res2 = $this->db->lazyQuery($sql2,$debug,2); 
            if (!$res2){
                logFile('rollback 5');
                $this->db->rollback();
                return false;   
            }
            // pr($getKIB);
            $sqlKib = array(
                    'table'=>"{$getKIB['listTableOri']}",
                    'field'=>"StatusValidasi = 2",
                    'condition'=>"Aset_ID='$asset_id[$i]'",
                    );

            $resKib = $this->db->lazyQuery($sqlKib,$debug,2);
            if (!$resKib){
                logFile('rollback 5');
                $this->db->rollback();
                return false;   
            }

            $sql3 = array(
                    'table'=>"PenggunaanAset",
                    'field'=>"StatusMutasi=1, Mutasi_ID='$mutasi_id'",
                    'condition'=>"Aset_ID='$asset_id[$i]'",
                    );

            $res3 = $this->db->lazyQuery($sql3,$debug,2);
            if (!$res3){
                logFile('rollback 6');
                $this->db->rollback();
                return false;   
            }
            
            $sql = array(
                    'table'=>'aset',
                    'field'=>"TipeAset",
                    'condition' => "Aset_ID={$asset_id[$i]}",
                    );
            $result = $this->db->lazyQuery($sql,$debug);
            $asetid[$asset_id[$i]] = $listTable[implode(',', $result[0])];
        
        }

        if ($result){
            
            $removeAsetList = $this->removeAsetList('MUTASI');
            if (!$removeAsetList) $this->db->rollback();
            
            logFile('commit transaksi mutasi');
            $this->db->commit();
            return true;
        } 

        logFile('Rollback transaksi mutasi');
        $this->db->rollback();
        return false;
    }

    function store_validasi_Mutasi($data, $TAHUN_AKTIF,$debug=false)
    {

        
        $jenisaset = $this->getJenisAset($data['aset_id']);

        // pr($jenisaset);exit;
        if ($jenisaset){

            $sleep = 1;
            foreach ($jenisaset as $key => $value) {
                
                $this->db->autocommit(0);
                $this->db->begin();
                $table = $this->getTableKibAlias($value);

                // cek dulu jika kapitalisasi atau aset baru
                $sql = array(
                        'table'=>"mutasiaset",
                        'field'=>"Aset_ID_Tujuan",
                        'condition'=>"Status = 0 AND Mutasi_ID = {$data[Mutasi_ID]} AND Aset_ID = {$data['aset_id'][$key]}",
                        );

                $res = $this->db->lazyQuery($sql,$debug);

                // ambil data aset asal
                $sql = array(
                        'table'=>"{$table['listTableOri']}",
                        'field'=>"*",
                        'condition'=>"Aset_ID={$data[aset_id][$key]}",
                        );

                $resultAwal = $this->db->lazyQuery($sql,$debug);
                
                $getLokasiTujuan = $this->get_satker_tujuan($data['Mutasi_ID'], $data['aset_id'][$key]);

                $dataSatkerAwalKib = $this->getSatkerData($resultAwal[0]['kodeSatker']);

                logFile(serialize($res));
                /*echo "Tabel:<br/>";
                pr($table );

                echo "Lokasi Asal:<br/>";
                pr($resultAwal );
                echo "Lokasi Tujuan:<br/>";
                pr($getLokasiTujuan );
                */
                if ($res){
                    /* 
                        cek apakah aset id tujuan ada atau tidak
                        Jika ada maka lakukan kapitalisasi nilai ke aset id tujuan
                    */

                    $NilaiPerolehan = 0;

                    if ($res[0]['Aset_ID_Tujuan']>0){
                        
                        // ambil nilai perolehan aset tujuan
                        $sql = array(
                                'table'=>"{$table['listTableOri']}",
                                'field'=>"*",
                                'condition'=>"Aset_ID={$res[0]['Aset_ID_Tujuan']}",
                                );

                        $result2 = $this->db->lazyQuery($sql,$debug);

                        
                         
                        $NilaiPerolehan = ($resultAwal[0]['NilaiPerolehan'] + $result2[0]['NilaiPerolehan']);
                        /**
                         * Pentuan apakah koreksi kapitalisasi atau kapitalisasi transfer
                         */
                            $MasaManfaat =$result2[0]['MasaManfaat'];
                            $AkumulasiPenyusutan =$result2[0]['AkumulasiPenyusutan'];
                            $Tahun_U_Range =$result2[0]['TahunPenyusutan'];
                            $TahunASet =$result2[0]['Tahun'];
                            $NilaiBuku_Tujuan =$result2[0]['NilaiBuku'];
                            $NilaiPerolehan_Tujuan=$result2[0]['NilaiPerolehan'];

                            //aset asal atau penambah 
                            $Tahun_Aset_Kapitalisasi=$resultAwal[0]['Tahun'];
                            $nilai_koreksi=$resultAwal[0]['NilaiPerolehan'];
                            //akhir aset asal atau penambah 
                           
                            if($Tahun_Aset_Kapitalisasi!=$TAHUN_AKTIF)
                            {
                                $kd_riwayat_asal=281;
                                $kd_riwayat_akhir=28;
                                $beban_penyusutan=round($nilai_koreksi/$MasaManfaat);
                                //Koreksi Kapitalisasi == riwayat =28
                                $rentang_penyusutan=$Tahun_U_Range-$TahunASet+1;
                                  if($rentang_penyusutan>$MasaManfaat){
                                     $rentang_penyusutan=$MasaManfaat;
                                  }
                                  $selisih_akumulasi =$beban_penyusutan*$rentang_penyusutan;
                                  $AkumulasiPenyusutan_Final =$AkumulasiPenyusutan+$selisih_akumulasi;
                                  $NilaiBuku_Tujuan_Final=$NilaiBuku_Tujuan-$AkumulasiPenyusutan_Final;
                                  logFile("Perhitungan koreksi kapitalisasi :\n 
                                    beban_penyusutan=round($nilai_koreksi/$MasaManfaat) \n
                                    selisih_akumulasi =$beban_penyusutan*$rentang_penyusutan;\n
                                    AkumulasiPenyusutan_Final =$AkumulasiPenyusutan+$selisih_akumulasi;\n
                                    NilaiBuku_Tujuan_Final=$NilaiBuku_Tujuan-$AkumulasiPenyusutan_Final; \n
                                    Kd_riwayat_asal = $kd_riwayat_asal \n 
                                    Kd_riwayat_akhir= $kd_riwayat_akhir
                                    ");
                            }else{
                                //Kapitalisasi == riwayat = 29
                                $kd_riwayat_asal=291;
                                $kd_riwayat_akhir=29;
                                $NilaiBuku_Tujuan_Final=$NilaiBuku_Tujuan + $nilai_koreksi;
                                $AkumulasiPenyusutan_Final=$AkumulasiPenyusutan;
                                logFile("Perhitungan transfer kapitalisasi :\n 
                                    NilaiBuku_Tujuan_Final=$NilaiBuku_Tujuan + $nilai_koreksi; \n
                                    AkumulasiPenyusutan_Final=$AkumulasiPenyusutan; \n
                                    Kd_riwayat_asal = $kd_riwayat_asal \n 
                                    Kd_riwayat_akhir= $kd_riwayat_akhir
                                    ");
                            }
                        /**
                         * Akhir dari Penentuan koreksi kapitalisasi atau kapitalisasi transfer
                         */
                        $olah_tgl =  $_POST['TglSKKDH'];
                        
                        // log aset id tujuan sebelum dilakukan penambahan nilai
                        $this->db->logIt($tabel=array($table['listTableOri']), $Aset_ID=$res[0]['Aset_ID_Tujuan'], 
                                    $kd_riwayat=$kd_riwayat_asal, 
                                    $noDokumen=$nodok, $tglProses =$olah_tgl,
                                    $text="Aset Penambahan kapitalisasi Mutasi",0);

                        
                        logFile('Nilai Perolehan awal : '.serialize($resultAwal));
                        logFile('Nilai Perolehan tujuan : '.serialize($result2));
                        logFile('Nilai Perolehan gabungan : '.$NilaiPerolehan);
                        if ($NilaiPerolehan > 0){
                            $sqlKib = array(
                                    'table'=>"{$table['listTableOri']}",
                                    'field'=>"NilaiPerolehan='{$NilaiPerolehan}',NilaiBuku='{$NilaiBuku_Tujuan_Final}',
                                            AkumulasiPenyusutan='{$AkumulasiPenyusutan_Final}'  ",
                                    'condition'=>"Aset_ID='{$res[0]['Aset_ID_Tujuan']}'",
                                    );

                            $resKib = $this->db->lazyQuery($sqlKib,$debug,2);
                            if (!$resKib) {$this->db->rollback(); return false;}
                            
                            $sqlKib = array(
                                    'table'=>"{$table['listTableOri']}",
                                    'field'=>"StatusTampil=2, Status_Validasi_Barang = 2",
                                    'condition'=>"Aset_ID='{$data[aset_id][$key]}'",
                                    );

                            $resKib = $this->db->lazyQuery($sqlKib,$debug,2);
                            if (!$resKib) {$this->db->rollback(); return false;}

                            $sql2 = array(
                                    'table'=>"Aset",
                                     'field'=>"NilaiPerolehan='{$NilaiPerolehan}',NilaiBuku='{$NilaiBuku_Tujuan_Final}',
                                            AkumulasiPenyusutan='{$AkumulasiPenyusutan_Final}'  ",
                                    'condition'=>"Aset_ID='{$res[0]['Aset_ID_Tujuan']}'",
                                    );

                            $res2 = $this->db->lazyQuery($sql2,$debug,2); 
                            if (!$res2) {$this->db->rollback(); return false;}

                            $sql3 = array(
                                    'table'=>"Aset",
                                    'field'=>"Status_Validasi_Barang = 3, NotUse = 3",
                                    'condition'=>"Aset_ID='{$data[aset_id][$key]}'",
                                    );

                            $res3 = $this->db->lazyQuery($sql3,$debug,2); 
                            if (!$res3) {$this->db->rollback(); return false;}

                            $nodok = $_POST['noDokumen'];
                            $olah_tgl =  $_POST['TglSKKDH'];
                            
                            $this->db->logIt_kapitalisasi($tabel=array($table['listTableOri']), $Aset_ID=$data['aset_id'][$key], 
                                    $kd_riwayat=$kd_riwayat_akhir, $noDokumen=$nodok, $tglProses =$olah_tgl, 
                                    $text="Sukses kapitalisasi Mutasi",$res[0]['Aset_ID_Tujuan'],
                                    $AkumulasiPenyusutan_Awal=$AkumulasiPenyusutan,
                                    $NilaiBuku_Awal=$NilaiBuku_Tujuan, 
                                    $NilaiPerolehan_Awal=$NilaiPerolehan_Tujuan,0);
                        
                        }else{

                            $this->db->rollback();
                            logFile('Nilai Perolehan kosong ketika kapitalisasi aset mutasi');
                            return false;
                        }
                        
                        $this->db->commit();

                    }else{
                        // ubah data baru


                        $tmpKodeLokasi = explode('.', $resultAwal[0]['kodeLokasi']);
                        $tmpKodeSatker = explode('.', $getLokasiTujuan[0]['SatkerTujuan']);

                        $prefix = $tmpKodeLokasi[0].'.'.$tmpKodeLokasi[1].'.'.$tmpKodeLokasi[2];
                        $prefixkodesatker = $tmpKodeSatker[0].'.'.$tmpKodeSatker[1];
                        $prefixTahun = substr($resultAwal[0]['Tahun'], 2,2);
                        $postfixkodeSatker = $tmpKodeSatker[2].'.'.$tmpKodeSatker[3];

                        $implLokasi = $prefix.'.'.$prefixkodesatker.'.'.$prefixTahun.'.'.$postfixkodeSatker;
                        
                        $noDok = array('penggu_penet_eks_nopenet','mutasi_trans_eks_nodok');
                        $nodok = $_POST['noDokumen'];
                        $olah_tgl =  $_POST['TglSKKDH'];

                        $info = "{$resultAwal[0]['Info']} ex {$dataSatkerAwalKib[0]['NamaSatker']}";                     
                        $this->db->logIt($tabel=array($table['listTableOri']), $Aset_ID=$data['aset_id'][$key], $kd_riwayat=3, $noDokumen=$nodok, $tglProses =$olah_tgl, $text="Data Mutasi sebelum diubah");

                        $sql = array(
                                'table'=>"{$table['listTableOri']}",
                                'field'=>"MAX( CAST( noRegister AS SIGNED ) ) AS noRegister",
                                'condition'=>"kodeKelompok = '{$resultAwal[0][kodeKelompok]}' AND kodeSatker = '{$getLokasiTujuan[0][SatkerTujuan]}' AND kodeLokasi = '{$implLokasi}'",
                                );
                        $result = $this->db->lazyQuery($sql,$debug);

                        // $sqlSelect = array(
                        //         'table'=>"Aset",
                        //         'field'=>"MAX(noRegister) AS noRegister",
                        //         'condition'=>"kodeKelompok = '{$resultAwal[0][kodeKelompok]}' AND kodeSatker = '{$resultAwal[0][kodeSatker]}' AND kodeLokasi = '{$resultAwal[0][kodeLokasi]}'",
                        //         );

                        // $result = $this->db->lazyQuery($sqlSelect,$debug);
                        // pr($result);

                        $gabung_nomor_reg_tujuan=intval(($result[0]['noRegister'])+1);

                        $sqlKib = array(
                                'table'=>"{$table['listTableOri']}",
                                'field'=>"kodeSatker='{$getLokasiTujuan[0]['SatkerTujuan']}', kodeLokasi = '{$implLokasi}', noRegister='$gabung_nomor_reg_tujuan', StatusValidasi = 1, Status_Validasi_Barang = 1, StatusTampil = 1, TglPembukuan = '{$olah_tgl}', Info = '{$info}'",
                                'condition'=>"Aset_ID='{$data[aset_id][$key]}'",
                                );

                        $resKib = $this->db->lazyQuery($sqlKib,$debug,2);

                        $sql2 = array(
                                'table'=>"Aset",
                                'field'=>"kodeSatker='{$getLokasiTujuan[0][SatkerTujuan]}', kodeLokasi = '{$implLokasi}', noRegister='$gabung_nomor_reg_tujuan',StatusValidasi = 1, Status_Validasi_Barang = 1, NotUse = 0, fixPenggunaan = 0, statusPemanfaatan = 0, TglPembukuan = '{$olah_tgl}', Info = '{$info}'",
                                'condition'=>"Aset_ID='{$data[aset_id][$key]}'",
                                );

                        $resAset = $this->db->lazyQuery($sql2,$debug,2); 

                        if ($resKib){
                            logFile('Data baru berhasil diubah kode satkernya di validasi mutasi');
                        }else{
                            logFile('Nilai Perolehan kosong ketika kapitalisasi aset mutasi');
                            $this->db->rollback();
                            return false;
                        }

                        if ($resAset){

                            $this->db->logIt($tabel=array($table['listTableOri']), $Aset_ID=$data['aset_id'][$key], $kd_riwayat=3, $noDokumen=$nodok, $tglProses =$olah_tgl, $text="Sukses Mutasi");
                            
                             
                        }else{

                            $this->db->rollback();
                            logFile('gagal log data mutasi aset '.$data['aset_id'][$key]);
                        } 

                        $this->db->commit();
                    }  
                

                }
                
                $sql = array(
                        'table'=>"mutasiaset",
                        'field'=>"Status=1",
                        'condition'=>"Aset_ID = '{$data[aset_id][$key]}' AND Mutasi_ID = '$data[Mutasi_ID]'",
                        );
                $res1 = $this->db->lazyQuery($sql,$debug,2); 

                
                $sleep++;

                if ($sleep == 20){
                    sleep(1);
                    $sleep = 1;
                }
            }
            // end looping (foreach)

            $sql = array(
                    'table'=>"mutasiaset",
                    'field'=>"COUNT(*) AS total",
                    'condition'=>"Status = 0 AND Mutasi_ID = {$data[Mutasi_ID]}",
                    );

            $res = $this->db->lazyQuery($sql,$debug);
            
            if ($res[0]['total']==0){
                $sql = array(
                            'table'=>"Mutasi",
                            'field'=>"FixMutasi=1",
                            'condition'=>"Mutasi_ID = '{$data[Mutasi_ID]}'",
                            );
                $res1 = $this->db->lazyQuery($sql,$debug,2); 
            }
            // exit;
            logFile('Commit transaction mutasi');
            // $this->db->commit();
            return true;   
        }
        
        // $this->db->rollback();
        return false;

        

    }

    function get_satker_tujuan($mutasiid=1, $Aset_ID=false)
    {   


        $table = $this->getTableKibAlias($jenis);

        $sql = array(
                'table'=>"mutasiaset",
                'field'=>"*",
                'condition'=>" Aset_ID = {$Aset_ID} AND Mutasi_ID = {$mutasiid} AND Status = 0",
                );

        $res = $this->db->lazyQuery($sql,$debug);
        if ($res) return $res;
        return false;
    }

    function store_mutasi_barang($data,$debug=false)
    {
            
            $jenisaset = $data['jenisaset'];

            $satker=$data['kodeSatker']; 
            $nodok=$data['mutasi_trans_eks_nodok'];
            $tgl=$data['mutasi_trans_eks_tglproses'];
            $olah_tgl=  format_tanggal_db2($tgl);
            $alasan=$data['mutasi_trans_eks_alasan'];
            $pemakai=$data['mutasi_trans_eks_pemakai'];
            $kodeKelompok = $data['kodeKelompok'];

            $satkerAwal=$data['lastSatker'];
            $kelompokAwal=$data['lastKelompok'];
            $lokasiAwal=$data['lastLokasi'];
            $registerAwal=$data['lastNoRegister'];
            $namaSatkerAwal=$data['lastNamaSatker'];

            $UserNm=$_SESSION['ses_uoperatorid'];// usernm akan diganti jika session di implementasikan
            $nmaset=$data['mutasi_nama_aset'];
            $asset_id=Array();
            $no_reg=Array();
            $nm_barang=Array();
            $asetKapitalisasi = array_keys($_POST['asetKapitalisasi']);
            $asetKapitalisasiOri = $_POST['asetKapitalisasi'];

            $mutasi_id=get_auto_increment("Mutasi");
            
            
            // pr($jenisaset);

            $listTable = array(
                    'A'=>'tanah',
                    'B'=>'mesin',
                    'C'=>'bangunan',
                    'D'=>'jaringan',
                    'E'=>'asetlain',
                    'F'=>'kdp');


            $panjang=count($nmaset);

            // $query="INSERT INTO Mutasi (Mutasi_ID, NoSKKDH , TglSKKDH, 
            //                     Keterangan, SatkerTujuan, NotUse, TglUpdate, 
            //                     UserNm, FixMutasi, Pemakai)
            //                 values ('','$nodok','$olah_tgl',
            //                        '$alasan','$satker','','$olah_tgl','$UserNm','1','$pemakai')";
            
            $sql = array(
                    'table'=>"Mutasi",
                    'field'=>"NoSKKDH , TglSKKDH, Keterangan, SatkerTujuan, NotUse, TglUpdate, UserNm, FixMutasi, Pemakai",
                    'value'=>"'$nodok','$olah_tgl', '$alasan','$satker',0,'$olah_tgl','$UserNm','0','$pemakai'",
                    );

            $res = $this->db->lazyQuery($sql,$debug,1);

            for($i=0;$i<$panjang;$i++){
                
                $getJenisAset = $this->getJenisAset($nmaset);

                $getKIB = $this->getTableKibAlias($getJenisAset[$i]);

                $tmp=$nmaset[$i];
                $tmp_olah=explode("<br/>",$tmp);
                $asset_id[$i]=$tmp_olah[0];
                $no_reg[$i]=$tmp_olah[1];
                $nm_barang[$i]=$tmp_olah[2];
                
                // $logData = $this->db->logIt(array($getKIB['listTableOri']), $asset_id[$i]);

                $lokasiBaru = ubahLokasi($lokasiAwal[$i],$satker);
                
                //buat gabung nomor registrasi akhir
                // $array=array($pemilik,$provinsi,$kabupaten,$row_kode_satker,$tahun,$row_kode_unit);
                
                
                    $sqlSelect = array(
                            'table'=>"Aset",
                            'field'=>"MAX(noRegister) AS noRegister",
                            'condition'=>"kodeKelompok = '{$kelompokAwal[$i]}' AND kodeLokasi = '{$lokasiBaru}'",
                            );

                    $result = $this->db->lazyQuery($sqlSelect,$debug);
                    // pr($result);

                    $gabung_nomor_reg_tujuan=intval(($result[0]['noRegister'])+1);

                /*
                echo "<pre>";
                print_r($gabung);
                echo "</pre>";
                */
                    $sql1 = array(
                            'table'=>"MutasiAset",
                            'field'=>"Mutasi_ID,Aset_ID,NamaSatkerAwal, NomorRegAwal,NomorRegBaru,SatkerAwal,SatkerTujuan",
                            'value'=>"'$mutasi_id','$asset_id[$i]','$namaSatkerAwal[$i]','$registerAwal[$i]','$gabung_nomor_reg_tujuan','$satkerAwal[$i]','$satker'",
                            );

                    $res1 = $this->db->lazyQuery($sql1,$debug,1);

                    if (!in_array($asset_id[$i], $asetKapitalisasi)){
                        $sql2 = array(
                                'table'=>"Aset",
                                'field'=>"kodeSatker='$satker', kodeLokasi = '{$lokasiBaru}', noRegister='$gabung_nomor_reg_tujuan', NotUse=0, StatusValidasi = 3, Status_Validasi_Barang = 3",
                                'condition'=>"Aset_ID='$asset_id[$i]'",
                                );

                        $res2 = $this->db->lazyQuery($sql2,$debug,2); 
                    }
                    
                    // pr($getKIB);
                    $sqlKib = array(
                            'table'=>"{$getKIB['listTableOri']}",
                            'field'=>"kodeSatker='$satker', kodeLokasi = '{$lokasiBaru}', noRegister='$gabung_nomor_reg_tujuan', StatusValidasi = 3, Status_Validasi_Barang = 3, StatusTampil = 3",
                            'condition'=>"Aset_ID='$asset_id[$i]'",
                            );

                    $resKib = $this->db->lazyQuery($sqlKib,$debug,2);


                    $sql3 = array(
                            'table'=>"PenggunaanAset",
                            'field'=>"StatusMutasi=1, Mutasi_ID='$mutasi_id'",
                            'condition'=>"Aset_ID='$asset_id[$i]'",
                            );

                    $res3 = $this->db->lazyQuery($sql3,$debug,2);

                    
                    $sql = array(
                            'table'=>'aset',
                            'field'=>"TipeAset",
                            'condition' => "Aset_ID={$asset_id[$i]}",
                            );
                    $result = $this->db->lazyQuery($sql,$debug);
                    $asetid[$asset_id[$i]] = $listTable[implode(',', $result[0])];
                
            }

            if ($result){
                
                $noDok = array('penggu_penet_eks_nopenet','mutasi_trans_eks_nodok');

                foreach ($_POST as $key => $value) {
                    if(in_array($value, $noDok)) $noDokumen = $_POST[$value];
                    else $noDokumen = '-';
                }
                

                foreach ($asetid as $key => $value) {
                    if (!in_array($key, $asetKapitalisasi)){
                        $this->db->logIt($tabel=array($value), $Aset_ID=$key, $kd_riwayat=3, $noDokumen=$nodok, $tglProses =$olah_tgl, $text="Mutasi Pending Status");
                    }else{
                        $this->db->logIt($tabel=array($value), $Aset_ID=$key, $kd_riwayat=25, $noDokumen=$nodok, $tglProses =$olah_tgl, $text="Mutasi Pending Status dengan mode kapitalisasi", $tmpSatker=$asetKapitalisasiOri[$key]);
                    }
                    
                }

                return true;
            } 

            return false;
    }

    function retrieve_mutasiPending($data, $debug=false)
    {
        
        // pr($_SESSION);
        logFile(serialize($_SESSION));
        $ses_satkerkode = $data['kodeSatker'];

        $filter = "";
        if ($ses_satkerkode) $filter .= "AND ma.SatkerAwal LIKE '{$ses_satkerkode}%'";

        $paging = paging($data['page'], 100);
        
        $sqlSelect = array(
            'table'=>"mutasiaset AS ma",
            'field'=>"ma.Mutasi_ID, ma.SatkerAwal, ma.NamaSatkerAwal, (SELECT NamaSatker FROM satker WHERE kode = ma.SatkerAwal LIMIT 1) AS NamaSatkerAwalAset, COUNT(ma.Aset_ID) AS Jumlah",
            'condition'=>"ma.SatkerTujuan !='' {$filter} GROUP BY ma.Mutasi_ID ORDER BY ma.Mutasi_ID DESC",
            //'limit'=>"{$paging}, 100",
            );

        $result = $this->db->lazyQuery($sqlSelect,$debug);

        // $MutasiID = 
        // pr($result);
        if ($result){

            

            foreach ($result as $key => $value) {

                $sortByMutasiID[$value['Mutasi_ID']] = $value;

                $sqlSelect = array(
                    'table'=>"mutasi AS m, satker AS s",
                    'field'=>"m.*, s.NamaSatker",
                    'condition'=>"Mutasi_ID = '{$value[Mutasi_ID]}' AND s.Kd_Ruang IS NULL AND m.TglSKKDH > '2014-01-01' AND m.TglSKKDH IS NOT NULL ORDER BY m.TglSKKDH DESC",
                    'joinmethod' => 'LEFT JOIN',
                    'join'=>'m.SatkerTujuan = s.kode',
                    );

                $tmpRes = $this->db->lazyQuery($sqlSelect,$debug);
                logFile(serialize($tmpRes));

                
                if ($tmpRes){
                    // $mutasiTmp[] = $value['Mutasi_ID'];
                    
                    foreach ($tmpRes as $key => $val) {
                        $mutasiNew[$val['Mutasi_ID']] = $val;
                    }

                    /*
                    $res[] = $tmpRes;
                    $res[$key][0]['SatkerAwal'] = $value['SatkerAwal'];
                    $res[$key][0]['NamaSatkerAwal'] = $value['NamaSatkerAwal'];
                    $res[$key][0]['NamaSatkerAwalAset'] = $value['NamaSatkerAwalAset'];
                    $res[$key][0]['Jumlah'] = $value['Jumlah'];
                    */
                    // if (in_array($tmpRes[0]['Mutasi_ID'], haystack))
                    // $newDataTmp[] = $tmpRes;

                }
            }



            // pr($res);
            if ($mutasiNew){

                foreach ($mutasiNew as $key => $value) {
                    $res[$value['Mutasi_ID']] = array_merge($value, $sortByMutasiID[$value['Mutasi_ID']]);
                }

                // pr($res);
                // pr($mutasiNew);
                if ($res){
                    foreach ($res as $value) {

                        if ($value){
                            
                            
                                if ($value['Mutasi_ID'])$newData[] = $value;
                             
                        }
                        
                    }
                    // pr($newData);
                    return $newData;  
                }
            }  
        } 
        return false;
    }

    function retrieve_detail_usulan_mutasi($data, $debug=false)
    {
        
        

        // pr($table);

        $TipeAset = array('A'=>1, 'B'=>2, 'C'=>3, 'D'=>4, 'E'=>5, 'F'=>6);
        $ses_satkerkode = $_SESSION['ses_param_mutasi']['kodeSatker'];

        // pr($_SESS    ION);
        $satkerAwal = "";
        if ($ses_satkerkode) $satkerAwal .= "AND ma.SatkerAwal = '{$ses_satkerkode}'";

        

        // pr($table);
        $sql = array(
                'table'=>"mutasi AS m, satker AS s",
                'field'=>"m.*, s.NamaSatker, s.kode",
                'condition'=>"m.Mutasi_ID = {$data[id]} GROUP BY m.Mutasi_ID",
                // 'limit'=>'100',
                'joinmethod' => 'LEFT JOIN',
                'join' => "m.SatkerTujuan = s.kode"
                );

        $res = $this->db->lazyQuery($sql,$debug);
        if ($res){

            foreach ($res as $key => $value) {
                $sql = array(
                        'table'=>"mutasiaset AS ma, satker AS s, aset AS a, kelompok AS k",
                        'field'=>"ma.*, s.NamaSatker AS NamaSatkerTujuan, s.kode, a.noKontrak, a.noRegister, a.NilaiPerolehan, a.Tahun, a.kodeKelompok, a.kodeLokasi, a.TipeAset, k.Uraian, k.kode, (SELECT NamaSatker FROM satker WHERE kode=ma.SatkerAwal LIMIT 1) AS satkerAwalAset",
                        'condition'=>"ma.Mutasi_ID = {$value[Mutasi_ID]} {$satkerAwal} GROUP BY ma.Aset_ID",
                        // 'limit'=>'100',
                        'joinmethod' => 'LEFT JOIN',
                        'join' => "ma.SatkerTujuan = s.kode, ma.Aset_ID = a.Aset_ID, a.kodeKelompok = k.kode"
                        );

                $resultAset = $this->db->lazyQuery($sql,$debug);
                $res[$key]['aset'] = $resultAset;

                foreach ($resultAset as $key => $value) {

                    $table = $this->getTableKibAlias($TipeAset[$value['TipeAset']]);
                    
                    $listTable = $table['listTable'];
                    $listTableAlias = $table['listTableAlias'];
                    $listTableAbjad = $table['listTableAbjad'];

                    $sqlkib = array(
                            'table'=>"{$listTable}",
                            'field'=>"*",
                            'condition'=>"Aset_ID = {$value['Aset_ID']}",
                            // 'limit'=>'100',
                            );

                    $resultKib = $this->db->lazyQuery($sqlkib,$debug);
                    $res[$key]['aset'][$key]['detail'] = $resultKib;
                }
                
            }
            
                
        }
        // pr($res);
        if ($res) return $res;
        return false;
    }

    function hapusUsulanMutasi($data, $debug=false)
    {

        $ses_satkerkode = $_SESSION['ses_satkerkode'];

        $filter = "";
        if ($ses_satkerkode) $filter .= "AND SatkerAwal = '{$ses_satkerkode}'";

        $aset_id = implode(',', $data['aset_id']);
        $sqlSelect = array(
                'table'=>"mutasiaset",
                'field'=>"Status = 3",
                'condition'=>"Mutasi_ID = '{$data[Mutasi_ID]}' AND Status = 0 AND Aset_ID IN ({$aset_id}) {$filter}",
                );

        $result = $this->db->lazyQuery($sqlSelect,$debug,2);

        if ($result){

            foreach ($data['aset_id'] as $key => $value) {
                $Aset_ID[] = $value;

                $sqlSelect = array(
                    'table'=>"aset",
                    'field'=>"TipeAset",
                    'condition'=>"Aset_ID = {$value}",
                    );
                $getKib[] = $this->db->lazyQuery($sqlSelect,$debug);

            }

            foreach ($getKib as $key => $value) {
                $kib[] = $value[0]['TipeAset'];

            }

            $arrTabel = array('A'=>1,'B'=>2,'C'=>3,'D'=>4,'E'=>5,'F'=>6);
            foreach ($Aset_ID as $key => $value) {
                
                $tabel = $this->getTableKibAlias($arrTabel[$kib[$key]]);
                
                
                $kibAset = $tabel['listTableOri'];

                $updateKib = array(
                        'table'=>"{$kibAset}",
                        'field'=>"StatusValidasi = 1",
                        'condition'=>"Aset_ID = {$value}",
                        );

                $result1 = $this->db->lazyQuery($updateKib,$debug,2);

                $updateAset = array(
                        'table'=>"aset",
                        'field'=>"StatusValidasi = 1, NotUse = 1",
                        'condition'=>"Aset_ID = {$value}",
                        );

                $result1 = $this->db->lazyQuery($updateAset,$debug,2);

                $sqlSelect = array(
                        'table'=>"mutasiaset",
                        'field'=>"Status = 3",
                        'condition'=>"Mutasi_ID = '{$data[mutasiid]}' AND Status = 0 AND Aset_ID IN ({$value})",
                        );

                $result = $this->db->lazyQuery($sqlSelect,$debug,2);

                $sql = array(
                        'table'=>"penggunaanaset",
                        'field'=>"StatusMutasi = 0, Mutasi_ID = 0",
                        'condition'=>"Aset_ID IN ({$value})",
                        );

                $result = $this->db->lazyQuery($sql,$debug,2);

            }
            
            // $aset_id = implode(',', $Aset_ID);

            

            $sql = array(
                    'table'=>'mutasi',
                    'field'=>"FixMutasi = 3",
                    'condition' => "Mutasi_ID = '{$data[mutasiid]}' ",
                    'limit' => '1',
                    );
            $res = $this->db->lazyQuery($sql,$debug,2);
            if ($res) return true;

        }

    /*
        $sql = array(
                'table'=>"penggunaanaset",
                'field'=>"StatusMutasi = 0, Mutasi_ID = 0",
                'condition'=>"Aset_ID IN ({$aset_id}) {$filter}",
                );

        $result = $this->db->lazyQuery($sql,$debug,2);
    */
        
        return false;
    }

    function retrieve_detailMutasi($data, $debug=false)
    {


        $TipeAset = array('A'=>1, 'B'=>2, 'C'=>3, 'D'=>4, 'E'=>5, 'F'=>6);

        // pr($table);

        $ses_satkerkode = $_SESSION['ses_mutasi_val_filter']['kodeSatker'];
        // pr($_SESSION);
        $satkerAwal = "";
        if ($ses_satkerkode) $satkerAwal .= "AND ma.SatkerAwal = '{$ses_satkerkode}'";

        

        $sql = array(
                'table'=>"mutasi AS m, satker AS s",
                'field'=>"m.*, s.NamaSatker, s.kode",
                'condition'=>"m.Mutasi_ID = {$data[id]} GROUP BY m.Mutasi_ID",
                // 'limit'=>'100',
                'joinmethod' => 'LEFT JOIN',
                'join' => "m.SatkerTujuan = s.kode"
                );

        $res = $this->db->lazyQuery($sql,$debug);
        if ($res){

            foreach ($res as $key => $value) {
                $sql = array(
                        'table'=>"mutasiaset AS ma, satker AS s, aset AS a, kelompok AS k",
                        'field'=>"ma.*, s.NamaSatker AS NamaSatkerTujuan, s.kode, a.noKontrak, a.noRegister, a.NilaiPerolehan, a.Tahun, a.kodeKelompok, a.kodeLokasi, a.TipeAset, k.Uraian, k.kode, (SELECT NamaSatker FROM satker WHERE kode=ma.SatkerAwal LIMIT 1) AS satkerAwalAset",
                        'condition'=>"ma.Mutasi_ID = {$value[Mutasi_ID]} {$satkerAwal} GROUP BY ma.Aset_ID",
                        // 'limit'=>'100',
                        'joinmethod' => 'LEFT JOIN',
                        'join' => "ma.SatkerTujuan = s.kode, ma.Aset_ID = a.Aset_ID, a.kodeKelompok = k.kode"
                        );

                // $res[$key]['aset'] = $this->db->lazyQuery($sql,$debug);

                $resultAset = $this->db->lazyQuery($sql,$debug);
                $res[$key]['aset'] = $resultAset;

                // pr($resultAset);
                foreach ($resultAset as $key => $value) {

                    $table = $this->getTableKibAlias($TipeAset[$value['TipeAset']]);
                    
                    $listTable = $table['listTable'];
                    $listTableAlias = $table['listTableAlias'];
                    $listTableAbjad = $table['listTableAbjad'];

                    $sqlkib = array(
                            'table'=>"{$listTable}",
                            'field'=>"*",
                            'condition'=>"Aset_ID = {$value['Aset_ID']}",
                            // 'limit'=>'100',
                            );

                    $resultKib = $this->db->lazyQuery($sqlkib,$debug);
                    $res[$key]['aset'][$key]['detail'] = $resultKib;
                }
            }
            
                
        }
        // pr($res);
        if ($res) return $res;
        return false;
    }

    



    function getTableKibAlias($type=1)
    {
        $listTableAlias = array(1=>'t',2=>'m',3=>'b',4=>'j',5=>'al',6=>'k');
        $listTableAbjad = array(1=>'A',2=>'B',3=>'C',4=>'D',5=>'E',6=>'F');

        $listTable = array(
                        1=>'tanah AS t',
                        2=>'mesin AS m',
                        3=>'bangunan AS b',
                        4=>'jaringan AS j',
                        5=>'asetlain AS al',
                        6=>'kdp AS k');

        $listTableOri = array(
                        1=>'tanah',
                        2=>'mesin',
                        3=>'bangunan',
                        4=>'jaringan',
                        5=>'asetlain',
                        6=>'kdp');

        $data['listTable'] = $listTable[$type];
        $data['listTableAlias'] = $listTableAlias[$type];
        $data['listTableAbjad'] = $listTableAbjad[$type];
        $data['listTableOri'] = $listTableOri[$type];

        return $data;
    }

    function getSatkerData($kode=false, $debug=false)
    {
        if (!$kode) return false;
        $sqlkib = array(
                'table'=>"satker",
                'field'=>"*",
                'condition'=>"kode = '{$kode}'",
                'limit' => 1
                );

        $resultKib = $this->db->lazyQuery($sqlkib,$debug);
        if ($resultKib) return $resultKib;
        return false;
    }
}
?>
