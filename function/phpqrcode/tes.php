<?php

include "qrlib.php";   

QRcode::png('PHP QR Code :)');

/*
// here our data 
$email = 'john.doe@example.com'; 
$subject = 'question'; 
$body = 'please write your question here'; 
 
// we building raw data 
$codeContents = 'mailto:'.$email; 
 
$tempDir = '/srv/www/htdocs/phpqrcode/output'; 

// generating 
QRcode::png($codeContents, $tempDir.'022.png', QR_ECLEVEL_L, 3); 
    
$EXAMPLE_TMP_URLRELPATH = 'output/';    
// displaying 
echo '<img src="'.$EXAMPLE_TMP_URLRELPATH.'022.png" />'; */
?>