<?php
/**
 * Plugin Name: Cs Cart CartaSi X-Pay Payment Gateway
 * Plugin URI: 
 * Description: Extends Cs Cart with CartaSi X-Pay Payment Gateway.
 * Version: 1.0.1
 * Author: Guddoo Kumar Yadav
 * Text Domain: 
 *
 * Copyright: © 2018 Guddoo Kumar Yadav
 * License: GNU General Public License v3.0
 * License URI: http://www.gnu.org/licenses/gpl-3.0.html
 */
 
if (!defined('BOOTSTRAP')) { die('Access denied'); }

if (defined('PAYMENT_NOTIFICATION')) {
	//echo "hi";die;
	$orders = explode("_",$_REQUEST['codTrans']);
	
	$_REQUEST['order_id'] = $orders[0];
    if (!empty($_REQUEST['order_id'])) {
        fn_payments_set_company_id($_REQUEST['order_id']);
    }
    if (!empty($_REQUEST['esito'])) {
		$pp_response = array();
		if($_REQUEST['esito'] =="OK"){
			$pp_response['order_status'] = 'C';
            $pp_response['reason_text'] =  'Transaction completed';
		}else if($_REQUEST['esito'] =="ANNULLO"){
			$pp_response['order_status'] = 'I';
            $pp_response['reason_text'] =  'Transaction cancelled';
		}
		else if($_REQUEST['esito'] =="KO"){
			$pp_response['order_status'] = 'D';
            $pp_response['reason_text'] =  'Transaction declined';
		}
		$pp_response['transaction_id'] = $_REQUEST['codiceConvenzione'];
		fn_finish_payment($_REQUEST['order_id'], $pp_response,true);
	}
	fn_order_placement_routines('route', $_REQUEST['order_id']);
	exit;
} else {
    $form_url='';
    if ($processor_data['processor_params']['mode'] == 'T') {
		$form_url = $processor_data['processor_params']['liveurl'];
    } else {
		$form_url = $processor_data['processor_params']['productionurl'];
    }
	$importo = $order_info["total"];
	$importo = str_replace(".", "", $importo);
	$importo = str_replace(",", "", $importo);
    $merchant_url = $processor_data['processor_params']['notify_url'];
	//$url_back = 'http://averlo.eu/en/concludi-ordine-averlo-marketplace/';
	$url_back = $merchant_url;
	$session_id = time().'_'.$importo;
	$language_id = 'ITA';
	$order_id = $order_info['order_id'];
	$codetranse = $order_id.'_'.date('YmdHis');
	$cartasi_mac = trim($processor_data['processor_params']['mac']);
	$alias = trim($processor_data['processor_params']['alias']);
	$cartasi_mac = sha1('codTrans=' . $codetranse . 'divisa=EURimporto=' . $importo . $cartasi_mac);
	
	$form_data['alias'] 					= $alias;			
	$form_data['importo']					= trim($importo);
	$form_data['divisa'] 					= trim('EUR');
	$form_data['codTrans'] 					= trim($codetranse);
	$form_data['mail']						= trim($order_info['email']);
	$form_data['url']						= trim($merchant_url);
	$form_data['urlpost']					= trim($merchant_url);
	$form_data['session_id'] 				= trim($session_id);
	$form_data['url_back'] 					= trim($url_back);
	$form_data['languageId']				= trim($language_id);
	$form_data['mac'] 						= trim($cartasi_mac);
	$form_data['descrizione'] 				= trim('Ordine: ' . $order_id);
	$form_data['Note1'] 					= '1.0';
	fn_create_payment_form($form_url, $form_data, 'CartaSi', false);
    exit;
}
?>