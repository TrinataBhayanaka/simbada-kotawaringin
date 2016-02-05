<?php

$CONFIG['default']['db_host'] = 'localhost';
$CONFIG['default']['db_user'] = 'root';
$CONFIG['default']['db_pass'] = 'root123root';
// $CONFIG['default']['db_name'] = 'simbada_fix';
// $CONFIG['default']['db_name'] = 'simbada_try_to_fix_v5';
// $CONFIG['default']['db_name'] = 'simbada_try_to_fix_v7';
$CONFIG['default']['db_name'] = 'simbada_full_v1';


function open_connection(){ 
	global $CONFIG;	

 $link=mysql_connect($CONFIG['default']['db_host'],$CONFIG['default']['db_user'],$CONFIG['default']['db_pass'])  
 or die ("Koneksi Database gagal"); 
 mysql_select_db($CONFIG['default']['db_name']);
 return $link; 
}


function get_auto_increment($tablename){
    
    $next_increment = 0;
    $qShowStatus = "SHOW TABLE STATUS LIKE '$tablename'";
    $qShowStatusResult = mysql_query($qShowStatus) or die ( "Query failed: " . mysql_error() . "<br/>" . $qShowStatus );

    $row = mysql_fetch_assoc($qShowStatusResult);
    $next_increment = $row['Auto_increment'];

    return $next_increment;
}
?> 
