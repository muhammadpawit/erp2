<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8" />
  <!--<title><?php echo $title; ?></title> -->
  <title><?php echo $this->config->get('config_name'); ?></title>
  <base href="<?php echo $base; ?>" />
  <!--<link rel="shortcut icon" href="https://nissonindonesia.com/sites/default/files/NISSON_8.png" type="image/png"/>-->
  <link rel="shortcut icon" href="http://erp2.nissonindonesia.com/image/data/NISSON_8.png" type="image/png"/>
  <?php if ($description) { ?>
  <meta name="description" content="<?php echo $description; ?>" />
  <?php } ?>
  <?php if ($keywords) { ?>
  <meta name="keywords" content="<?php echo $keywords; ?>" />
  <?php } ?>
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <link rel="stylesheet" href="view/newreq/requires/bootstrap/css/bootstrap.min.css">
  <link rel="stylesheet" href="view/newreq/main/AdminLTE.min.css">
  <!--<link rel="stylesheet" href="view/newreq/main/skins/green.css">-->
  <link rel="stylesheet" href="view/newreq/main/skins/red.css">
  <link rel="stylesheet" href="view/newreq/requires/font-awesome/css/font-awesome.min.css">
  <link rel="stylesheet" type="text/css" href="view/stylesheet/ajaxmask.css" />
  <link rel="stylesheet" type="text/css" href="view/stylesheet/select2.min.css" />
  <link rel="stylesheet" type="text/css" href="view/stylesheet/select2bootstrap.css" />
  <link rel="stylesheet" type="text/css" href="view/stylesheet/timepicker.css" />
  <link rel="stylesheet" type="text/css" href="view/stylesheet/datetime.css" />

  <!--<link rel="icon" type="image/png" href="view/newreq/main/images/manager-icon.png">-->
  <script src="view/newreq/requires/jquery/jquery-1.12.1.min.js"></script>
  <script src="view/newreq/requires/bootstrap/js/bootstrap.min.js"></script>
  <script src="view/newreq/main/app.min.js"></script>
  <script src="view/newreq/main/select2.full.min.js"></script>
  <script src="view/newreq/plugins/slimscroll/jquery.slimscroll.min.js"></script>

  <script src="view/javascript/fs/jquery.timer.js"></script>
  <script src="view/javascript/timepicker.js"></script>
  <script src="view/javascript/moment.js"></script>
    <script src="view/javascript/datetime.js"></script>
  <script src="view/javascript/fs/ajaxmask.js"></script>
  <script src="view/javascript/fs/custom.js"></script>
  <script type="text/javascript" src="view/javascript/jquery/ui/jquery-ui-1.8.16.custom.min.js"></script>
  <link type="text/css" href="view/javascript/jquery/ui/themes/ui-lightness/jquery-ui-1.8.16.custom.css" rel="stylesheet" />
  <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
  <!-- LOAD JQUERY-JEDITABLE -->
  <script src="view/newreq/requires/jquery/jquery.jeditable.js"></script>
  <!-- JEDITABLE PLUGINS -->
  <script src="view/newreq/requires/jquery/jquery.jeditable.autogrow.js"></script>
  <script src="view/newreq/requires/jquery/jquery.jeditable.charcounter.js"></script>
  <script src="view/newreq/requires/jquery/jquery.jeditable.checkbox.js"></script>
  <script src="view/newreq/requires/jquery/jquery.jeditable.datepicker.js"></script>
  <script src="view/newreq/requires/jquery/jquery.jeditable.masked.js"></script>
  <script src="view/newreq/requires/jquery/jquery.jeditable.time.js"></script>
  <!-- PRISM.JS TO DISPLAY THE CODE -->
  <link href="view/newreq/main/skins/prism.css" rel="stylesheet" />
  <script src="view/newreq/requires/jquery/prism.js"></script>
  <style>
  .box-body{
    overflow-x:scroll;
  }
  .print-only{
    display:none;
  }
  .btn-primary{
    background-color: #0494ab !important;
    border-color: #0494ab !important;
  }
  .btn-primary:hover{
    background-color: #7d1624 !important;
  }
  .btn-info{
    background-color: #289889 !important;
    border-color: #289889 !important;
  }
  .btn-info:hover{
    background-color: #a1232f !important;
    border-color: #a1232f !important;
  }
  /*@page{
    size: 21.59cm 13.97cm;
    margin:0;

  }*/
  /*@page{
    size: A4 Potrait;
    margin:5px;


  }*/
  @media print{
    html,body{
      font-family: Georgia;


    }
    .compname{
      font-size:16px;
    }

    /*.invoice{
      font-family: Arial;
      letter-spacing:1px;
    }*/
    .print-only{
      display:block;
    }
  }
  </style>
  
  <script type="text/javascript" src="view/javascript/jquery/tabs.js"></script>

  <script type="text/javascript">

  //-----------------------------------------
  // Confirm Actions (delete, uninstall)
  //-----------------------------------------
  $(document).ready(function(){
      // Confirm Delete
      $('#form').submit(function(){
          if ($(this).attr('action').indexOf('delete',1) != -1) {
              if (!confirm('Apakah anda yakin akan menghapus data? Data yang telah terhapus tidak dapat dikembalikan.')) {
                  return false;
              }
          }
      });

      // Confirm Uninstall
      $('a').click(function(){
        /*  if ($(this).attr('href') != null && $(this).attr('href').indexOf('uninstall', 1) != -1) {
              if (!confirm('Apakah anda yakin akan meng-uninstall data? Data yang telah ter-uninstall tidak dapat dikembalikan.')) {
                  return false;
              }
          }*/
          if ($(this).attr('href') != null && ($(this).attr('href').indexOf('batalkan', 1) != -1 || $(this).attr('href').indexOf('hapus', 1) != -1)) {
              if (!confirm('Apakah anda yakin akan menghapus/membatalkan data? Data yang telah dihapus/dikembalikan tidak dapat dikembalikan.')) {
                  return false;
              }
          }
      });
  });

  function progress(){
    swal("Belum tersedia!", "", "warning");
  }
  </script>

  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->

</head>
<body class="skin-green fixed">
  <div class="wrapper">
    <header class="main-header"> <!-- Main Header -->
      <a href="#" class="logo">
        <span class="logo-lg"><?php echo $this->config->get('config_name'); ?></span>
      </a>

      <nav class="navbar navbar-static-top" role="navigation"> <!-- Header Navbar -->
        <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
          <span class="sr-only">Toggle navigation</span>
        </a>
        <?php if ($logged) { ?>
        <div class="navbar-custom-menu">
          <ul class="nav navbar-nav">

            <li>
              <a href="<?php echo $update_erp; ?>" title="Update ERP" onclick="return confirm('Apakah anda yakin ingin mengupdate ERP?');">
                <span class="glyphicon glyphicon-refresh" aria-hidden="true"></span>
              </a>
            </li>
            <li>
              <a href="#" title="Account Setting">
                <span class="glyphicon glyphicon-user" aria-hidden="true"></span>
              </a>
            </li>
            <li>
              <a href="<?php echo $logout; ?>" title="Logout">
                <span class="glyphicon glyphicon-log-out" aria-hidden="true"></span>
              </a>
            </li>
          </ul>
        </div>
        <?php
      }
        ?>
      </nav> <!-- End Header Navbar -->

    </header> <!-- End Main Header -->

    <aside class="main-sidebar">
      <section class="sidebar">
        <?php if ($logged) { ?>
        <ul class="sidebar-menu">
          <li><a href="<?php echo $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'); ?>"><i class="glyphicon glyphicon-home"></i> <span>Dashboard</span></a></li>
          <?php foreach($menu as $m){ ?>
            <li class="treeview" id="menu-master-data">
              <a>
                <span><?php echo $m['menu']?></span> <i class="fa fa-angle-left pull-right"></i>
              </a>
              <ul class="treeview-menu">
                <?php foreach($m['sub'] as $submenu){?>
                      <li id="menu-lokasi" class="treeview">
                        <a>
                          <span><?php echo $submenu['nama'] ?></span> <i class="fa fa-angle-left pull-right"></i>
                        </a>
                        <ul class="treeview-menu">
                          <?php $supersub=$this->load->model_website_menu->getsupersub($submenu['id'],$in) ?>
                          <?php foreach($supersub as $ssmenu){?>
                          <li id="menu-gudang"><a href="<?php echo $this->url->link($ssmenu['url'], 'token=' . $this->session->data['token'], 'SSL'); ?>"><?php echo $ssmenu['nama'] ?></a></li>
                          <?php } ?>
                          <?php $supersub=$this->load->model_website_menu->getsupersupersub($submenu['id']) ?>
                          <?php foreach($supersub as $ssb){?>
                            <li id="menu-stok-opname"><a  ><?php echo $ssb['nama']?><i class="fa fa-angle-left pull-right"></i></a>
                              <ul class="treeview-menu">
                                <?php $supersupermenu=$this->load->model_website_menu->supersupermenu($ssb['id'],$in) ?>
                                <?php foreach($supersupermenu as $ssm){?>
                                <li ><a href="<?php echo $this->url->link($ssm['url'], 'token=' . $this->session->data['token'], 'SSL'); ?>"><?php echo $ssm['nama'] ?></a></li>
                                <?php } ?>
                              </ul>
                            </li>
                          <?php } ?>
                        </ul>
                      </li>
                <?php } ?>
                <?php foreach($m['rincian'] as $rm){?>
                  <li id="menu-gudang"><a href="<?php echo $this->url->link($rm['menu_url'], 'token=' . $this->session->data['token'], 'SSL'); ?>"><?php echo $rm['nama']?></a></li>
                <?php } ?>
              </ul>
            </li>
          <?php } ?>
          <!--
          <li class="treeview" id="menu-master-data">
            <a>
              <span>Master Data</span> <i class="fa fa-angle-left pull-right"></i>
            </a>
              <ul class="treeview-menu">
              <li id="menu-gudang"><a href="<?php echo $this->url->link('catalog/gudang', 'token=' . $this->session->data['token'], 'SSL'); ?>">Gudang</a></li>
              
              <li id="menu-lokasi" class="treeview">
                <a>
                  <span>Lokasi</span> <i class="fa fa-angle-left pull-right"></i>
                </a>
                <ul class="treeview-menu">
                </ul>
              </li>

            </ul>
          </li>-->
          <li class="header"><a href="http://morebit.co">V 1.0 Powered by morebit</a></li>
        </ul>
        <?php
      }
        ?>
      </section>
    </aside>
          <!-- Modal -->
          <div id="jurnal" class="modal fade" role="dialog">
            <div class="modal-dialog modal-lg">

              <!-- Modal content-->
              <div class="modal-content">
                <div class="modal-header">
                  <button type="button" class="close" data-dismiss="modal">&times;</button>
                  <h3 class="modal-title">Jurnal</h3>
                </div>
                <div class="modal-body">
                  
                </div>
                <div class="modal-footer">
                </div>
              </div>
            </div>
          </div>