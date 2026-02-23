<?php
/*
Created by morebit   | http://morebit.co   |  info@morebit.co
*/
class ModelSettingEmail extends Model {
  public function emailOrder($order_id){
    $this->load->model('setting/store');
		$store_info = $this->model_setting_store->getStore($data['store_id']);

    $this->load->model('sale/order');
    $order=$this->model_sale_order->getOrder($order_id);
    $subject  = $store_info['name']." Order #" . $order_id . "\n";

    $message  =  'ID Pesanan ' . $order_id . "\n";
    $message .= 'Tanggal ' . date('d/m/y', strtotime($order['date_added'])) . "\n\n";
    $message  =  'Nama '.$order['firstname'].' '.$order['lastname']. "\n";
    $message  =  'Email ' . $order['email'] . "\n";
    $message  =  'Telephone ' . $order['telephone'] . "\n\n";
    $message  =  "Produk \n";
    
  }
}
?>
