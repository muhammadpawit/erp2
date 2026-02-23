<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title><?php echo $this->config->get('config_name'); ?> | Log in</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <!-- Bootstrap 3.3.6 -->
  <link rel="stylesheet" href="view/newreq/requires/bootstrap/css/bootstrap.min.css">
	<!-- Font Awesome -->
  <link rel="stylesheet" href="view/newreq/requires/font-awesome/css/font-awesome.min.css"><!-- Ionicons -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="view/newreq/main/AdminLTE.min.css">


  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->
</head>
<body class="hold-transition login-page">
<div class="login-box">
  <div class="login-logo">
    <b><?php echo $this->config->get('config_name'); ?></b>
  </div>
  <!-- /.login-logo -->
  <div class="login-box-body">
    <p class="login-box-msg"></p>
    <?php if ($error_warning) { ?>
    <div class="alert alert-danger alert-dismissible">
      <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
      <h4><i class="icon fa fa-ban"></i> Alert!</h4>
      <?php echo $error_warning; ?>
    </div>
    <?php
    }
    ?>
    <?php if ($success) { ?>
    <div class="alert alert-success alert-dismissible">
      <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
      <h4><i class="icon fa fa-check"></i> Success!</h4>
      <?php echo $success; ?>
    </div>
    <?php
    }

    ?>
    <div class="errordisplay"></div>
    <form action="<?php echo $action; ?>" enctype="multipart/form-data" method="post">
      <div class="form-group has-feedback">
        <input type="text" name="username" class="form-control" placeholder="Username">
        <span class="glyphicon glyphicon-user form-control-feedback"></span>
      </div>
      <div class="form-group has-feedback">
        <input type="password" name="password" class="form-control" placeholder="Password">
        <input type="hidden" name="locs" class="form-control" value="">
        <span class="glyphicon glyphicon-lock form-control-feedback"></span>
      </div>
      <div class="row">
        <div class="col-xs-8">
          <?php
          if($unregistered){
          ?>
          <a onclick="registerdevice()" class="btn btn-warning btn-block btn-flat">Request Register Device</a>
          <?php
          }
          ?>
        </div>
        <!-- /.col -->
        <div class="col-xs-4">
          <button type="submit" class="btn btn-primary btn-block btn-flat">Masuk</button>

        </div>
        <!-- /.col -->
      </div>
    </form>


    <a href="<?php echo $forgotten; ?>">Lupa Password</a><br>


  </div>
  <!-- /.login-box-body -->
</div>
<!-- /.login-box -->

<!-- jQuery 2.2.3 -->
<script src="view/newreq/requires/jquery/jquery-1.12.1.min.js"></script>
<script src="view/newreq/requires/bootstrap/js/bootstrap.min.js"></script>
<script>
$(function(){
  x="";
  if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(showPosition);


    } else {
        x = "Tidak Terdeteksi";
        $('input[name=\'locs\']').val(x);
    }
})
function showPosition(position) {
  lat=position.coords.latitude;
  lon=position.coords.longitude;
  latlon = lat + "," + lon;
  $.get("http://maps.googleapis.com/maps/api/geocode/json?latlng="+latlon+"&sensor=true")
  .success(function (address){
    x=address.results[0].formatted_address;
      $('input[name=\'locs\']').val(x);
  });
}
function registerdevice(){
  $(".error").remove();
  error=false;
  em ="";
  //username=;
  username=$('input[name=\'username\']').val();
  locs=$('input[name=\'locs\']').val();
  if(username == ""){
    error=true;
    em +="Username harus diisi ";
    //alert("error");
  }
//  alert(username);
  if(!error){
    url ='<?php echo $registerdevice; ?>';
    url +='&username='+username+"&location="+locs;
    location=url;
  }else{
    html='<div class="alert alert-danger alert-dismissible">';
    html +='<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>';
    html +='<h4><i class="icon fa fa-ban"></i> Alert!</h4>';
    html +=em;
    html +='</div>';

    $('.errordisplay').html(html);;
  }

}
</script>
</body>
</html>
