<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Myshortcart
 *
 * @lordsanjay
 */

// Heading
$_['heading_title']                = 'Myshortcart';
                                   
// Text                            
$_['text_payment']                 = 'Payment';
$_['text_success']                 = 'Success: You have modified Myshortcart account details!';
$_['text_myshortcart']             = '<a onclick="window.open(\'https://www.myshortcart.com\');"><img src="view/image/payment/myshortcart.png" alt="Myshortcart" title="Myshortcart" style="border: 1px solid #EEEEEE;" /><br /></a>';
                                   
// Parameter                       
$_['server_params']                = 'Payment Server Parameter';
$_['opencart_params']              = 'Opencart Server Parameter';
$_['paymentchannel_params']        = 'Payment Channel Parameter';
                                   
// Entry                           
$_['entry_storeid']                = 'Store ID :';
$_['entry_sharedkey']              = 'Shared Key :';
$_['entry_prefix']                 = 'Prefix :';
$_['entry_order_status']           = 'Order Status:';
$_['entry_geo_zone']               = 'Geo Zone:';
$_['entry_status']                 = 'Status:';
$_['entry_sort_order']             = 'Sort Order:';
$_['entry_payment_name']           = 'Myshortcart name:';
$_['entry_minimal_amount']         = 'Minimal amount to process:';
                                   
// Error
$_['error_permission']             = 'Warning: You do not have permission to modify payment Myshortcart!';
$_['error_storeid']                = 'Store ID Required!';
$_['error_sharedkey']              = 'Shared Key Required!';
$_['error_prefix']                 = 'Prefix Required!';
$_['error_payment_method']         = 'Payment Method Name Required!';
$_['error_minimal_amount']         = 'Minimal Amount Required!';
$_['error_payment_name']           = 'Myshortcart Name Required!';
                                   
// URL                             
$myserverpath = explode ( "/", $_SERVER['PHP_SELF'] );
if ( $myserverpath[1] <> 'admin' ) 
{
    $serverpath = '/' . $myserverpath[1];    
}
else
{
    $serverpath = '';
}

if((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') || $_SERVER['SERVER_PORT'] == 443)
{
    $myserverprotocol = "https";
}
else
{
    $myserverprotocol = "http";    
}

$myservername                = $_SERVER['SERVER_NAME'] . $serverpath;
$_['url_title']              = 'URL to be set on Myshortcart System';
$_['url_verify']             = $myserverprotocol.'://'.$myservername.'/index.php?route=payment/myshortcart/myverify';
$_['url_notify']             = $myserverprotocol.'://'.$myservername.'/index.php?route=payment/myshortcart/mynotify';
$_['url_redirect']           = $myserverprotocol.'://'.$myservername.'/index.php?route=payment/myshortcart/myredirect';
$_['url_cancel']             = $myserverprotocol.'://'.$myservername.'/index.php?route=payment/myshortcart/mycancel';

?>
