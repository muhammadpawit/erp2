<?php
/*
Created by morebit   | http://morebit.co   |  info@morebit.co
*/
class ModelSettingSms extends Model {
  public function sendSms($sms,$tujuan){
    $data_string=array(
      'pesan' => $sms,
      'tujuan'  => $tujuan
    );
    $curl = curl_init();
    curl_setopt_array($curl, array(
      CURLOPT_URL => API_SMS."kirimsms",
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => "",
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 30,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => "POST",
       CURLOPT_POSTFIELDS	=> $data_string,
      CURLOPT_HTTPHEADER => array(
        'Content-Type: application/x-www-form-urlencoded',
        'Content-Length: ' . strlen($data_string)
      ),
    ));

    $response = curl_exec($curl);
    $hasil=json_decode($response,true);
    $err = curl_error($curl);
    if($err){
      $hasil['status']=500;
        $hasil['error']=$err;
    }else{
      $hasil['status']=200;
    }
    return $hasil;
  }
}
?>
