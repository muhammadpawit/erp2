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
          <li class="treeview" id="menu-master-data">
            <a>
              <span>Master Data</span> <i class="fa fa-angle-left pull-right"></i>
            </a>
              <ul class="treeview-menu">
              <li id="menu-gudang"><a href="<?php echo $this->url->link('catalog/gudang', 'token=' . $this->session->data['token'], 'SSL'); ?>">Gudang</a></li>
              <li id="menu-marketplace"><a href="<?php echo $this->url->link('marketplace/marketplace', 'token=' . $this->session->data['token'], 'SSL'); ?>">Marketplace</a></li>
              <li id="menu-kategori"><a href="<?php echo $category; ?>">Kategori Produk</a></li>
              <li id="menu-kelompok-aset"><a href="<?php echo $kelompok_aset; ?>">Kelompok Aset</a></li>
              <li id="menu-pemeliharaan"><a href="<?php echo $this->url->link('catalog/pemeliharaan', 'token=' . $this->session->data['token'], 'SSL'); ?>">Jenis Pemeliharaan Aset</a></li>
              <li id="menu-option"><a href="<?php echo $options; ?>">Ukuran Tabung</a></li>
              <li id="menu-satuan"><a href="<?php echo $this->url->link('catalog/satuan', 'token=' . $this->session->data['token'], 'SSL'); ?>">Satuan</a></li>
              <li id="menu-title"><a href="<?php echo $this->url->link('catalog/title', 'token=' . $this->session->data['token'], 'SSL'); ?>">Sapaan</a></li>
              <li id="menu-area"><a href="<?php echo $this->url->link('catalog/area', 'token=' . $this->session->data['token'], 'SSL'); ?>">Area</a></li>
              <li id="menu-user-group"><a href="<?php echo $this->url->link('user/user_permission', 'token=' . $this->session->data['token'], 'SSL'); ?>">Kelompok User</a></li>
              <li id="menu-customer-group"><a href="<?php echo $this->url->link('sale/customer_group', 'token=' . $this->session->data['token'], 'SSL'); ?>">Kelompok Customer</a></li>
              <li id="menu-menu"><a href="<?php echo $this->url->link('website/menu', 'token=' . $this->session->data['token'], 'SSL'); ?>">Menu</a></li>
              <li id="menu-lokasi" class="treeview">
                <a>
                  <span>Lokasi</span> <i class="fa fa-angle-left pull-right"></i>
                </a>
                <ul class="treeview-menu">
                  <li id="menu-provinsi"><a href="<?php echo $this->url->link('catalog/provinsi', 'token=' . $this->session->data['token'], 'SSL'); ?>">Provinsi</a></li>
                  <li id="menu-kabupaten"><a href="<?php echo $this->url->link('catalog/kabupaten', 'token=' . $this->session->data['token'], 'SSL'); ?>">Kota/Kabupaten</a></li>
                  <li id="menu-kecamatan"><a href="<?php echo $this->url->link('catalog/city', 'token=' . $this->session->data['token'], 'SSL'); ?>">Kecamatan</a></li>


                </ul>
              </li>

            </ul>
          </li>
          <li id="menu-persediaan" class="treeview">
            <a>
              <span>Persediaan</span> <i class="fa fa-angle-left pull-right"></i>
            </a>
            <ul class="treeview-menu">
              <li id="menu-persediaan-bahanbaku"><a href="<?php echo $this->url->link('catalog/bahanbaku', 'token=' . $this->session->data['token'], 'SSL'); ?>">Bahan Baku </a>
              </li>
              <li id="menu-persediaan-produkdagang"><a  >Produk Dagang <i class="fa fa-angle-left pull-right"></i></a>
                <ul class="treeview-menu">
                  <li id="menu-daftar-produk"><a href="<?php echo $product; ?>">Daftar Persediaan</a></li>
                  <li id="menu-produk-daftarhargaterendah"><a href="<?php echo $this->url->link('catalog/productterendah', 'token=' . $this->session->data['token'], 'SSL'); ?>">Daftar Harga Terendah</a></li>
                  <li id="menu-produk-gudang"><a href="<?php echo $this->url->link('catalog/productgudang', 'token=' . $this->session->data['token'], 'SSL'); ?>">Stok Gudang</a></li>
                  <li id="menu-produk-gudang-tgl"><a href="<?php echo $this->url->link('catalog/productgudang/stokpertanggal', 'token=' . $this->session->data['token'], 'SSL'); ?>">Stok Gudang Per-Tanggal</a></li>
                  <li id="menu-produk-gudang"><a href="<?php echo $this->url->link('catalog/premijual', 'token=' . $this->session->data['token'], 'SSL'); ?>">Set Premi Jual</a></li>
                  <li id="menu-stok-opname"><a  >Stok Opname <i class="fa fa-angle-left pull-right"></i></a>
                    <ul class="treeview-menu">
                      <li ><a href="<?php echo $this->url->link('gudang/jurnalstokopname', 'token=' . $this->session->data['token'], 'SSL'); ?>">Pengaturan Jurnal</a></li>
                      <li ><a href="<?php echo $this->url->link('gudang/permohonanstokopname', 'token=' . $this->session->data['token'], 'SSL'); ?>">Permohonan Stok Opname</a></li>
                      <li ><a href="<?php echo $this->url->link('gudang/persetujuanstokopname', 'token=' . $this->session->data['token'], 'SSL'); ?>">Persetujuan Stok Opname</a></li>
                    </ul>
                  </li>
                  <!--li id="menu-stok-opname"><a href="<?php echo $this->url->link('gudang/stokopname', 'token=' . $this->session->data['token'], 'SSL'); ?>">Stok Opname</a></li-->
                  <li id="menu-transfer-item"><a href="<?php echo $this->url->link('gudang/transferitem', 'token=' . $this->session->data['token'], 'SSL'); ?>">Transfer Item</a></li>
                  <li id="menu-transfer-item"><a href="<?php echo $this->url->link('gudang/terimatransfer', 'token=' . $this->session->data['token'], 'SSL'); ?>">Terima Transfer Item</a></li>
                  <li id="menu-tukar-kran"><a href="<?php echo $this->url->link('gudang/tukartabung', 'token=' . $this->session->data['token'], 'SSL'); ?>">Tukar Kran</a></li>
                  <li id="menu-perakitan"><a href="<?php echo $this->url->link('perakitan/perakitan', 'token=' . $this->session->data['token'], 'SSL'); ?>">Perakitan</a></li>
                </ul>
              </li>
              <li id="menu-persediaan-tabung"><a  >Tabung Gas <i class="fa fa-angle-left pull-right"></i></a>
                <ul class="treeview-menu">
                  <li id="menu-persediaan-tabungmp"><a href="<?php echo $this->url->link('catalog/tabungmp', 'token=' . $this->session->data['token'], 'SSL'); ?>">Tabung MP</a></li>
                  <li id="menu-persediaan-tabungmr"><a href="<?php echo $this->url->link('catalog/tabungmr', 'token=' . $this->session->data['token'], 'SSL'); ?>">Tabung MR</a></li>
                  <li id="menu-persediaan-tabungms"><a href="<?php echo $this->url->link('catalog/tabungms', 'token=' . $this->session->data['token'], 'SSL'); ?>">Tabung Stok</a></li>

                </ul>
              </li>

              <li id="menu-persediaan-aset"><a  >Aset <i class="fa fa-angle-left pull-right"></i></a>
                <ul class="treeview-menu">
                  <li><a href="<?php echo $this->url->link('catalog/aset', 'token=' . $this->session->data['token'], 'SSL'); ?>">Daftar Aset</a></li>
                  <li><a href="<?php echo $this->url->link('gudang/transferaset', 'token=' . $this->session->data['token'], 'SSL'); ?>">Transfer Aset ke Produk Dagang</a></li>

                </ul>
              </li>

              <li id="menu-persediaan-atk"><a href="<?php echo $this->url->link('catalog/atk', 'token=' . $this->session->data['token'], 'SSL'); ?>">ATK </a>
              </li>

            </ul>
          </li>
          <li id="menu-customer" >
            <a>
              <span>Customer</span> <i class="fa fa-angle-left pull-right"></i>
            </a>
            <ul class="treeview-menu">
              <li id="menu-manajemen-customer"><a href="<?php echo $customer; ?>"><span>Customer</span></a></li>
              <li id="menu-daftar-customer"><a href="<?php echo $this->url->link('sale/daftarcustomer', 'token=' . $this->session->data['token'], 'SSL'); ?>"><span>Daftar Customer</span></a></li>
              <li id="menu-edit-customer"><a href="<?php echo $this->url->link('sale/editcustomer', 'token=' . $this->session->data['token'], 'SSL'); ?>"><span>Edit Customer</span></a></li>
              <li id="menu-daftar-customer-nonaktif"><a href="<?php echo $this->url->link('daftarcustomer/nonaktif', 'token=' . $this->session->data['token'], 'SSL'); ?>"><span>Daftar Customer Non-aktif</span></a></li>
               <li id="menu-daftar-customer-settinguser"><a href="<?php echo $this->url->link('daftarcustomer/settinguser', 'token=' . $this->session->data['token'], 'SSL'); ?>"><span>Setting User</span></a></li>
            </ul>
          </li>

          <li id="menu-pembelian" class="treeview">
            <a>
              <span>Pembelian</span> <i class="fa fa-angle-left pull-right"></i>
            </a>
            <ul class="treeview-menu">
              <li><a href="<?php echo $this->url->link('catalog/jenisbiaya', 'token=' . $this->session->data['token'], 'SSL'); ?>">Jenis Biaya Pembelian</a></li>
              <li class="treeview"><a  >Menu Pembelian Lama <i class="fa fa-angle-left pull-right"></i></a>
                <ul class="treeview-menu">
                  <li class="treeview" id="menu-pembelian-produk"><a href="<?php echo $pembelian_produk; ?>">Surat Permintaan <i class="fa fa-angle-left pull-right"></i></a>
                      <ul class="treeview-menu">
                        <li><a href="<?php echo $this->url->link('pembelian/permintaanpembelian', 'token=' . $this->session->data['token'], 'SSL'); ?>">Permintaan Pembelian</a></li>
                        <li><a href="<?php echo $this->url->link('pembelian/kasbon', 'token=' . $this->session->data['token'], 'SSL'); ?>">Kasbon</a></li>
                      </ul>
                  </li>
                  <li><a href="<?php echo $this->url->link('pembelian/pembeliankredit', 'token=' . $this->session->data['token'], 'SSL'); ?>">Purchased Order</a></li>
                  <li id="menu-pembelian-barangdatang-lokal" class="treeview"><a  >Barang Datang <i class="fa fa-angle-left pull-right"></i></a>
                    <ul class="treeview-menu">
                      <li><a href="<?php echo $this->url->link('pembelian/barangdatangaset', 'token=' . $this->session->data['token'], 'SSL'); ?>">Barang Datang Aset</a></li>
                      <li><a href="<?php echo $this->url->link('pembelian/barangdatang', 'token=' . $this->session->data['token'], 'SSL'); ?>">Barang Datang Produk</a></li>

                    </ul>
                  </li>
                  <li><a href="<?php echo $this->url->link('pembelian/invoicepembeliankredit', 'token=' . $this->session->data['token'], 'SSL'); ?>">Invoice</a></li>
                  <li><a href="<?php echo $this->url->link('pembelian/pembayaranpembeliankredit', 'token=' . $this->session->data['token'], 'SSL'); ?>">Alokasi Pembayaran</a></li>
                  <li><a href="<?php echo $this->url->link('pembelian/biayapembeliankredit', 'token=' . $this->session->data['token'], 'SSL'); ?>">Pembayaran Biaya</a></li>


                </ul>
              </li>
              <!--<li id="menu-pembelian-produk"><a href="<?php echo $this->url->link('pembelian/pembeliantunai', 'token=' . $this->session->data['token'], 'SSL'); ?>">Pembelian Tunai
            </a>

          </li>-->
              <li id="menu-pembelian-kredit" class="treeview"><a  >Pembelian Lokal <i class="fa fa-angle-left pull-right"></i></a>
                <ul class="treeview-menu">
                  <li id="menu-vendor-lokal"><a href="<?php echo $vendor_lokal; ?>">Vendor Lokal</a></li>

                  <li id="menu-pembelian-produkdagang-lokal" class="treeview"><a  >Produk Dagang <i class="fa fa-angle-left pull-right"></i></a>
                    <ul class="treeview-menu">
                      <li><a href="<?php echo $this->url->link('pembelian/permintaanpembeliandagang', 'token=' . $this->session->data['token'], 'SSL'); ?>">Permintaan Pembelian</a></li>
                      <li><a href="<?php echo $this->url->link('daftarpolokal/daftarpolokal', 'token=' . $this->session->data['token'], 'SSL'); ?>">Daftar Purchased Order</a></li>
                      <li><a href="<?php echo $this->url->link('pembelian/pembeliankreditdagang', 'token=' . $this->session->data['token'], 'SSL'); ?>">Purchased Order</a></li>
                      <li class="treeview"><a >Barang Datang <i class="fa fa-angle-left pull-right"></i></a>
                        <ul class="treeview-menu">
                          <li><a href="<?php echo $this->url->link('pembelian/barangdatangdagang', 'token=' . $this->session->data['token'], 'SSL'); ?>">Input SJ</a></li>
                          <li><a href="<?php echo $this->url->link('pembelian/terimabarangdagang', 'token=' . $this->session->data['token'], 'SSL'); ?>">Terima Barang</a></li>

                        </ul>
                      </li>
                      <li><a href="<?php echo $this->url->link('pembelian/invoicepembeliandagang', 'token=' . $this->session->data['token'], 'SSL'); ?>">Invoice</a></li>
                      <li id="menu-pembelian-pembayaran-lokal" class="treeview"><a  >Pembayaran <i class="fa fa-angle-left pull-right"></i></a>
                        <ul class="treeview-menu">
                          <li><a href="<?php echo $this->url->link('pembelian/pembayaranpembeliandagang', 'token=' . $this->session->data['token'], 'SSL'); ?>">Alokasi Pembayaran</a></li>
                          <li id="menu-pembelian-biaya-lokal" class="treeview"><a  >Biaya Pembelian <i class="fa fa-angle-left pull-right"></i></a>
                            <ul class="treeview-menu">
                              <li><a href="<?php echo $this->url->link('pembelian/biayapembeliankredit', 'token=' . $this->session->data['token'], 'SSL'); ?>">Daftar Biaya</a></li>
                              <li><a href="<?php echo $this->url->link('pembelian/tagihanbiayalokal', 'token=' . $this->session->data['token'], 'SSL'); ?>">Tagihan</a></li>

                            </ul>
                          </li>
                        </ul>
                      </li>
                    </ul>
                  </li>
                  <li id="menu-pembelian-produkdagang-lokal" class="treeview"><a  >Bahan Baku <i class="fa fa-angle-left pull-right"></i></a>
                    <ul class="treeview-menu">
                      <li><a href="<?php echo $this->url->link('pembelian/permintaanpembelianbahanbaku', 'token=' . $this->session->data['token'], 'SSL'); ?>">Permintaan Pembelian</a></li>
                      <li><a href="<?php echo $this->url->link('pembelian/pembeliankreditbahanbaku', 'token=' . $this->session->data['token'], 'SSL'); ?>">Purchased Order</a></li>
                      <li><a href="<?php echo $this->url->link('pembelian/invoicepembelianbahanbaku', 'token=' . $this->session->data['token'], 'SSL'); ?>">Invoice</a></li>
                      <li><a href="<?php echo $this->url->link('pembelian/pembayaranpembelianbahanbaku', 'token=' . $this->session->data['token'], 'SSL'); ?>">Alokasi Pembayaran</a></li>
                      <li><a href="<?php echo $this->url->link('pembelian/barangdatangbahanbaku', 'token=' . $this->session->data['token'], 'SSL'); ?>">Barang Datang</a></li>


                    </ul>
                  </li>
                  <li id="menu-pembelian-produkdagang-lokal" class="treeview"><a  >Non Produk Dagang <i class="fa fa-angle-left pull-right"></i></a>
                    <ul class="treeview-menu">
                      <li><a href="<?php echo $this->url->link('pembelian/permintaanpembeliannondagang', 'token=' . $this->session->data['token'], 'SSL'); ?>">Permintaan Pembelian</a></li>
                      <li><a href="<?php echo $this->url->link('pembelian/pembeliankreditnondagang', 'token=' . $this->session->data['token'], 'SSL'); ?>">Purchased Order</a></li>
                      <li><a href="<?php echo $this->url->link('pembelian/invoicepembeliannondagang', 'token=' . $this->session->data['token'], 'SSL'); ?>">Invoice</a></li>
                      <li><a href="<?php echo $this->url->link('pembelian/pembayaranpembeliannondagang', 'token=' . $this->session->data['token'], 'SSL'); ?>">Alokasi Pembayaran</a></li>
                      <li><a href="<?php echo $this->url->link('pembelian/barangdatangnondagang', 'token=' . $this->session->data['token'], 'SSL'); ?>">Barang Datang</a></li>


                    </ul>
                  </li>



                  <li id="menu-pembelian-pembayaran-lokal" class="treeview"><a  >Pembayaran <i class="fa fa-angle-left pull-right"></i></a>
                    <ul class="treeview-menu">
                      <li><a href="<?php echo $this->url->link('pembelian/pembayarandepositkredit', 'token=' . $this->session->data['token'], 'SSL'); ?>">Deposit Pembayaran</a></li>

                    </ul>
                  </li>
                </ul>
              </li>
              <li id="menu-pembelian-import" class="treeview"><a  >Pembelian Import <i class="fa fa-angle-left pull-right"></i></a>
                <ul class="treeview-menu">
                  <li><a href="<?php echo $this->url->link('pembelian/permintaanpembelian', 'token=' . $this->session->data['token'], 'SSL'); ?>">Permintaan Pembelian</a></li>

                  <li id="menu-vendor-import"><a href="<?php echo $vendor_import;  ?>">Vendor Import</a></li>
                  <li><a href="<?php echo $this->url->link('daftarpoimport/daftarpoimport', 'token=' . $this->session->data['token'], 'SSL'); ?>">Daftar PO Import</a></li>
                  <li><a href="<?php echo $this->url->link('pembelian/pembelianimport', 'token=' . $this->session->data['token'], 'SSL'); ?>">Purchased Order</a></li>
                  <li><a href="<?php echo $this->url->link('pembelian/invoicepembelianimport', 'token=' . $this->session->data['token'], 'SSL'); ?>">Invoice</a></li>

                  <li id="menu-pembelian-pembayaran-import" class="treeview"><a  >Pembayaran <i class="fa fa-angle-left pull-right"></i></a>
                    <ul class="treeview-menu">
                      <li><a href="<?php echo $this->url->link('pembelian/pembayarandepositimport', 'token=' . $this->session->data['token'], 'SSL'); ?>">Deposit Pembayaran</a></li>
                      <li><a href="<?php echo $this->url->link('pembelian/pembayaranpembelianimport', 'token=' . $this->session->data['token'], 'SSL'); ?>">Alokasi Pembayaran</a></li>
                      <li><a href="<?php echo $this->url->link('pembelian/pembayaranpib', 'token=' . $this->session->data['token'], 'SSL'); ?>">Pembayaran PIB</a></li>
                    </ul>
                  </li>
                  <li id="menu-pembelian-biaya-import" class="treeview"><a  >Biaya Pembelian <i class="fa fa-angle-left pull-right"></i></a>
                    <ul class="treeview-menu">
                      <li><a href="<?php echo $this->url->link('pembelian/biayapembelianimport', 'token=' . $this->session->data['token'], 'SSL'); ?>">Daftar Biaya</a></li>
                      <li><a href="<?php echo $this->url->link('pembelian/tagihanbiayaimport', 'token=' . $this->session->data['token'], 'SSL'); ?>">Tagihan dan Pembayaran</a></li>

                    </ul>
                  </li>
                  <li><a href="<?php echo $this->url->link('pembelian/barangdatangimport', 'token=' . $this->session->data['token'], 'SSL'); ?>">Barang Datang</a></li>
                </ul>
              </li>
            </ul>
          </li>
          <li id="menu-penjualan" class="treeview">
            <a>
              <span>Penjualan</span> <i class="fa fa-angle-left pull-right"></i>
            </a>
            <ul class="treeview-menu">

              <li id="menu-penawaran-harga"><a href="<?php echo $this->url->link('sale/penawaran', 'token=' . $this->session->data['token'], 'SSL'); ?>" >Penawaran Harga</a></li>
              <!--li id="menu-penjualan-mr" class="treeview"><a>
                <span>Penjualan MR</span> <i class="fa fa-angle-left pull-right"></i>
              </a>
              <ul class="treeview-menu">

                <li><a href="<?php echo $this->url->link('sale/tttkmr', 'token=' . $this->session->data['token'], 'SSL'); ?>">TTTK</a></li>
                <li><a href="<?php echo $this->url->link('sale/salesordermr', 'token=' . $this->session->data['token'], 'SSL'); ?>">Sales Order</a></li>
                <li><a href="<?php echo $this->url->link('sale/penjualanmr', 'token=' . $this->session->data['token'], 'SSL'); ?>">Surat Jalan</a></li>
              </ul>
            </li-->
            <li id="menu-penjualan-mp" class="treeview"><a>
              <span>Produk Dagang & Produksi</span> <i class="fa fa-angle-left pull-right"></i>
            </a>
            <ul class="treeview-menu">

              <li><a href="<?php echo $this->url->link('sale/tttk', 'token=' . $this->session->data['token'], 'SSL'); ?>">TTTK</a></li>
              <li><a href="<?php echo $this->url->link('sale/tttkmr', 'token=' . $this->session->data['token'], 'SSL'); ?>">TTTK MR</a></li>
              <li><a href="<?php echo $this->url->link('sale/salesorder', 'token=' . $this->session->data['token'], 'SSL'); ?>">Sales Order</a></li>
              <li><a href="<?php echo $this->url->link('sale/sogantung/sogantung', 'token=' . $this->session->data['token'], 'SSL'); ?>">Sales Order Gantung</a></li>
              <li><a href="<?php echo $this->url->link('sale/salesorderoperasional', 'token=' . $this->session->data['token'], 'SSL'); ?>">Sales Order Operasional</a></li>
              <li><a href="<?php echo $this->url->link('sale/penjualan', 'token=' . $this->session->data['token'], 'SSL'); ?>">Surat Jalan</a></li>
              <li><a href="<?php echo $this->url->link('sale/deliveryorder', 'token=' . $this->session->data['token'], 'SSL'); ?>">Delivery Order</a></li>
              <li><a href="<?php echo $this->url->link('sale/sjbelumdifaktur/belumdifaktur', 'token=' . $this->session->data['token'], 'SSL'); ?>">Surat Jalan belum difaktur</a></li>
              <li><a href="<?php echo $this->url->link('sale/returnpenjualan', 'token=' . $this->session->data['token'], 'SSL'); ?>">Return Penjualan</a></li>
              
            </ul>
          </li>
          <li id="menu-penjualan-bahanbaku" class="treeview"><a>
            <span>Penjualan Bahan Baku</span> <i class="fa fa-angle-left pull-right"></i>
          </a>
          <ul class="treeview-menu">

            <li><a href="<?php echo $this->url->link('sale/salesorderbahanbaku', 'token=' . $this->session->data['token'], 'SSL'); ?>">Sales Order</a></li>
            <li><a href="<?php echo $this->url->link('sale/penjualanbahanbaku', 'token=' . $this->session->data['token'], 'SSL'); ?>">Surat Jalan</a></li>
          </ul>
        </li>
        <li id="menu-proforma"><a href="<?php echo $this->url->link('sale/proforma', 'token=' . $this->session->data['token'], 'SSL'); ?>">Profoma Invoice</a></li>
        <li id="menu-invoice"><a href="<?php echo $this->url->link('sale/invoice', 'token=' . $this->session->data['token'], 'SSL'); ?>">Invoice</a></li>
        <li id="menu-pembayaran-invoice" class="treeview"><a>
          <span>Pembayaran</span> <i class="fa fa-angle-left pull-right"></i>
        </a>
        <ul class="treeview-menu">
          <li id="menu-pembayaran-tunai"><a href="<?php echo $this->url->link('sale/pembayaranpenjualan', 'token=' . $this->session->data['token'], 'SSL'); ?>">Pembayaran Tunai & COD</a></li>

          <li id="menu-pembayaran-kredit"><a href="<?php echo $this->url->link('sale/pembayarankredit', 'token=' . $this->session->data['token'], 'SSL'); ?>">Pembayaran Kredit</a></li>
        </ul>
      </li>
            </ul>
          </li>
          <li class="treeview">
            <a>
              <span>Produksi</span> <i class="fa fa-angle-left pull-right"></i>
            </a>
            <ul class="treeview-menu">
              <li><a href="<?php echo $this->url->link('gudang/bukastok', 'token=' . $this->session->data['token'], 'SSL'); ?>">Buka Stok Awal Bulan</a></li>

              <li><a href="<?php echo $this->url->link('produksi/bukaproduksi', 'token=' . $this->session->data['token'], 'SSL'); ?>">Buka Tutup Produksi</a></li>
              <li><a href="<?php echo $this->url->link('produksi/permintaanproduksi', 'token=' . $this->session->data['token'], 'SSL'); ?>">Permintaan Produksi</a></li>
              <li><a href="<?php echo $this->url->link('produksi/pemrosesanproduksi', 'token=' . $this->session->data['token'], 'SSL'); ?>">Pemrosesan Produksi</a></li>
              <li><a href="<?php echo $this->url->link('produksi/hasilproduksi', 'token=' . $this->session->data['token'], 'SSL'); ?>">Hasil Produksi</a></li>
              <li><a href="<?php echo $this->url->link('produksi/penggembosanproduksi', 'token=' . $this->session->data['token'], 'SSL'); ?>">Penggembosan Produksi Stok</a></li>

            </ul>
          </li>
          <li class="treeview" id="menu-keuangan">
            <a>
              <span>Keuangan</span> <i class="fa fa-angle-left pull-right"></i>
            </a>
            <ul class="treeview-menu">
              <li id="menu-bankpusat"><a href="<?php echo $this->url->link('keuangan/bank', 'token=' . $this->session->data['token'], 'SSL'); ?>">Bank Kas Pusat</a></li>
              <li id="menu-bankcabang"><a href="<?php echo $this->url->link('keuangan/bankcabang', 'token=' . $this->session->data['token'], 'SSL'); ?>">Bank Kas Cab.Surabaya</a></li>
              <li id="menu-menerimaan-dana"><a >Penerima Dana Customer <i class="fa fa-angle-left pull-right"></i></a>
                <ul class="treeview-menu">
                    <li><a href="<?php echo $this->url->link('keuangan/penerimaandana', 'token=' . $this->session->data['token'], 'SSL'); ?>">Input Penerimaan Dana</a></li>
                    <li id="penerimaan-dana-hutang-lain"><a href="<?php echo $this->url->link('keuangan/penerimaandanahutanglain', 'token=' . $this->session->data['token'], 'SSL'); ?>">Penerimaan Dana Hutang lain</a></li>
                    <li id="penerimaan-dana-hutang-lain"><a href="<?php echo $this->url->link('keuangan/daftarpenerimaandanahutanglain', 'token=' . $this->session->data['token'], 'SSL'); ?>">Daftar Penerimaan Dana Hutang lain</a></li>
                    <li><a href="<?php echo $this->url->link('keuangan/terimadana', 'token=' . $this->session->data['token'], 'SSL'); ?>">Terima Dana Customer</a></li>
                    <li><a href="<?php echo $this->url->link('keuangan/refunddeposit', 'token=' . $this->session->data['token'], 'SSL'); ?>">Refund Deposit Customer</a></li>
                    <li id="menu-menerimaan-dana"><a >Penyesuaian Saldo Deposit <i class="fa fa-angle-left pull-right"></i></a>
                    <ul class="treeview-menu">
                        <li><a href="<?php echo $this->url->link('keuangan/jurnalpenyesuaiandeposit', 'token=' . $this->session->data['token'], 'SSL'); ?>">Setting Jurnal</a></li>
                        <li><a href="<?php echo $this->url->link('keuangan/permohonanpenyesuaiandeposit', 'token=' . $this->session->data['token'], 'SSL'); ?>">Permohonan</a></li>
                        <li><a href="<?php echo $this->url->link('keuangan/persetujuanpenyesuaiandeposit', 'token=' . $this->session->data['token'], 'SSL'); ?>">Persetujuan</a></li>
                    </ul>
                  </li>
                </ul>
              </li>
              <li id="menu-followup-penagihan"><a href="<?php echo $this->url->link('keuangan/folowuppenagihan', 'token=' . $this->session->data['token'], 'SSL'); ?>">Folow Up Penagihan</a></li>
              <li><a href="<?php echo $this->url->link('catalog/pemeliharaanaset', 'token=' . $this->session->data['token'], 'SSL'); ?>">Pemeliharaan Aset</a></li>
              <li><a href="<?php echo $this->url->link('keuangan/iklanperiodik', 'token=' . $this->session->data['token'], 'SSL'); ?>">Biaya Periodik</a></li>
              <li id="menu-pencatatan-beban"><a >Pencatatan Tagihan <i class="fa fa-angle-left pull-right"></i></a>
                <ul class="treeview-menu">
                    <li><a href="<?php echo $this->url->link('keuangan/tagihanbiaya', 'token=' . $this->session->data['token'], 'SSL'); ?>">Daftar Tagihan</a>
                      <li><a href="<?php echo $this->url->link('keuangan/pembayarantagihan', 'token=' . $this->session->data['token'], 'SSL'); ?>">Pembayaran</a>
                </ul>
              </li>
              <li><a  >Tutup Bulan</a></li>
              <!--<li><a  >Setoran</a></li>
              <li><a  >Transfer</a></li>
              <li><a  >Pendapatan</a></li>
              <li><a  >Biaya</a></li>
              <li><a  >Pajak</a></li>
              <li><a  >Penyesuaian Kurs</a></li>
              <li><a  >Hutang</a></li>-->
            </ul>
          </li>
          <li id="menu-buku-besar" class="treeview">
            <a>
              <span>Akuntansi</span> <i class="fa fa-angle-left pull-right"></i>
            </a>
            <ul class="treeview-menu">
              <li id="menu-coa"><a href="<?php echo $this->url->link('keuangan/coa', 'token=' . $this->session->data['token'], 'SSL'); ?>">COA</a></li>
               <li id="menu-coa"><a href="<?php echo $this->url->link('keuangan/saldoawalcoa', 'token=' . $this->session->data['token'], 'SSL'); ?>">Saldo Awal Tahun COA</a></li>
              <li><a href="<?php echo $this->url->link('laporan/jurnalmanual', 'token=' . $this->session->data['token'], 'SSL'); ?>">Jurnal Memo</a></li>
              
              <li><a href="<?php echo $this->url->link('laporan/jurnalumum', 'token=' . $this->session->data['token'], 'SSL'); ?>">Jurnal Umum</a></li>
              <!--<li><a href="<?php echo $this->url->link('keuangan/bukubesar', 'token=' . $this->session->data['token'], 'SSL'); ?>">Buku Besar</a></li>-->
              <li><a href="<?php echo $this->url->link('keuangan/bukubesar/baru', 'token=' . $this->session->data['token'], 'SSL'); ?>">Buku Besar</a></li>

            </ul>
          </li>
          <li class="treeview">
            <a>
              <span>Kepegawaian</span> <i class="fa fa-angle-left pull-right"></i>
            </a>
            <ul class="treeview-menu">
              <li class="treeview">
                <a>
                  <span>Pegawai</span> <i class="fa fa-angle-left pull-right"></i>
                </a>
                <ul class="treeview-menu">
                  <li><a href="<?php echo $this->url->link('user/user', 'token=' . $this->session->data['token'], 'SSL'); ?>">Manajemen Pegawai</a></li>
                  <li><a href="<?php echo $this->url->link('user/daftarpegawai', 'token=' . $this->session->data['token'], 'SSL'); ?>">Daftar Pegawai</a></li>
                  <li><a href="<?php echo $this->url->link('user/daftarpegawai', 'token=' . $this->session->data['token'], 'SSL'); ?>">Kontrak Pegawai</a></li>
                  <li><a href="<?php echo $this->url->link('kepegawaian/absensi', 'token=' . $this->session->data['token'], 'SSL'); ?>">Absensi Pegawai</a></li>
                  <li><a href="<?php echo $this->url->link('kepegawaian/cuti', 'token=' . $this->session->data['token'], 'SSL'); ?>">Cuti</a></li>
                  <li><a href="<?php echo $this->url->link('kepegawaian/ijin', 'token=' . $this->session->data['token'], 'SSL'); ?>">Ijin</a></li>
                  <li><a href="<?php echo $this->url->link('kepegawaian/kasbon', 'token=' . $this->session->data['token'], 'SSL'); ?>">Kas Bon</a></li>
                  <li><a href="<?php echo $this->url->link('kepegawaian/pinjaman', 'token=' . $this->session->data['token'], 'SSL'); ?>">Pinjaman</a></li>

                  <li><a href="<?php echo $this->url->link('kepegawaian/settinggaji', 'token=' . $this->session->data['token'], 'SSL'); ?>">Setting Gaji Pegawai</a></li>

                </ul>
              </li>
              <li><a href="<?php echo $this->url->link('kepegawaian/periode', 'token=' . $this->session->data['token'], 'SSL'); ?>">Periode</a></li>
              <li><a href="<?php echo $this->url->link('kepegawaian/divisi', 'token=' . $this->session->data['token'], 'SSL'); ?>">Divisi</a></li>
              <li><a href="<?php echo $this->url->link('kepegawaian/jabatan', 'token=' . $this->session->data['token'], 'SSL'); ?>">Jabatan</a></li>
              <li class="treeview">
                <a>
                  <span>Pengaturan Gaji</span> <i class="fa fa-angle-left pull-right"></i>
                </a>
                <ul class="treeview-menu">

                  <li><a href="<?php echo $this->url->link('kepegawaian/mtunjangan', 'token=' . $this->session->data['token'], 'SSL'); ?>">Tunjangan</a></li>
                  <li><a href="<?php echo $this->url->link('kepegawaian/kodepremi', 'token=' . $this->session->data['token'], 'SSL'); ?>">Kode Premi</a></li>

                </ul>
              </li>
              <li class="treeview">
                <a>
                  <span>Penggajian</span> <i class="fa fa-angle-left pull-right"></i>
                </a>
                <ul class="treeview-menu">
                  <li><a href="<?php echo $this->url->link('kepegawaian/premijual', 'token=' . $this->session->data['token'], 'SSL'); ?>">Premi Jual</a></li>

                </ul>
              </li>

            </ul>
          </li>
          <li id="menu-laporan" class="treeview">
            <a>
              <span>Laporan</span> <i class="fa fa-angle-left pull-right"></i>
            </a>
            <ul class="treeview-menu">
              <li><a href="<?php echo $this->url->link('laporan/laporanstok', 'token=' . $this->session->data['token'], 'SSL'); ?>">Laporan Stok</a></li>
              <li id="menu-laporan-deposit"><a href="<?php echo $this->url->link('laporan/depositcustomer', 'token=' . $this->session->data['token'], 'SSL'); ?>">Laporan Deposit Customer</a></li>
              <li><a href="<?php echo $this->url->link('laporan/penjualan', 'token=' . $this->session->data['token'], 'SSL'); ?>">Penjualan Produk Dagang</a></li>
              <li><a href="<?php echo $this->url->link('laporan/penjualandetail', 'token=' . $this->session->data['token'], 'SSL'); ?>">Penjualan Detail</a></li>
              <li><a href="<?php echo $this->url->link('laporan/mutasipiutang', 'token=' . $this->session->data['token'], 'SSL'); ?>">Mutasi Piutang</a></li>
              <li><a href="<?php echo $this->url->link('laporan/mutasipiutangdetail', 'token=' . $this->session->data['token'], 'SSL'); ?>">Mutasi Piutang Detail</a></li>
              
              <li><a href="<?php echo $this->url->link('laporan/penjualandetailexportexcel', 'token=' . $this->session->data['token'], 'SSL'); ?>">Penjualan Detail to Excel</a></li>
              <li><a href="<?php echo $this->url->link('sale/customerexcel', 'token=' . $this->session->data['token'], 'SSL'); ?>">Laporan Customer to Excel</a></li>
              <li id="menu-laporan-pi"><a href="<?php echo $this->url->link('laporan/pi', 'token=' . $this->session->data['token'], 'SSL'); ?>">Laporan PI</a></li>
              <li class="treeview">
                <a>
                  <span>Penjualan Dengan HPP</span> <i class="fa fa-angle-left pull-right"></i>
                </a>
                <ul class="treeview-menu">
                  <li><a href="<?php echo $this->url->link('laporan/penjualanhpp', 'token=' . $this->session->data['token'], 'SSL'); ?>">Penjualan Produk Dagang</a></li>
                  <li><a href="<?php echo $this->url->link('laporan/penjualandetailhpp', 'token=' . $this->session->data['token'], 'SSL'); ?>">Penjualan Detail</a></li>
                  <
                </ul>
              </li>
              <li><a href="<?php echo $this->url->link('laporan/laporannilaibarang', 'token=' . $this->session->data['token'], 'SSL'); ?>">Laporan Nilai Barang</a></li>
              <li><a href="<?php echo $this->url->link('laporan/agingreport', 'token=' . $this->session->data['token'], 'SSL'); ?>">Aging Report</a></li>
              <li><a href="<?php echo $this->url->link('laporan/laporanlabarugi', 'token=' . $this->session->data['token'], 'SSL'); ?>">Laba Rugi</a></li>
              <li><a href="<?php echo $this->url->link('laporan/neraca', 'token=' . $this->session->data['token'], 'SSL'); ?>">Neraca</a></li>
              <li id="menu-laporan-pemeliharaan-asset"><a href="<?php echo $this->url->link('laporan/pemeliharaanaset', 'token=' . $this->session->data['token'], 'SSL'); ?>">Laporan Pemeliharaan Aset</a></li>
              <li id="menu-laporan-pemeliharaan-asset"><a href="<?php echo $this->url->link('laporan/kasbank', 'token=' . $this->session->data['token'], 'SSL'); ?>">Laporan Kas & Bank</a></li>
              <li id="menu-laporan-pemeliharaan-asset"><a href="<?php echo $this->url->link('laporan/komisisales', 'token=' . $this->session->data['token'].'&filter_status=1,2,3', 'SSL'); ?>">Laporan Komisi Sales</a></li>
              <!--<li id="menu-laporan-pembelian-lokal"><a href="<?php echo $this->url->link('laporan/pembelianlokal', 'token=' . $this->session->data['token'], 'SSL'); ?>">Laporan Pembelian Lokal</a></li>-->
              <li id="menu-laporan-followup-penagihan"><a href="<?php echo $this->url->link('laporan/followup', 'token=' . $this->session->data['token'], 'SSL'); ?>">Laporan Follow Up Penagihan</a></li>
              <li class="treeview" id="laporan-pembelian-detail">
                <a>
                  <span>Laporan Pembelian Detail</span> <i class="fa fa-angle-left pull-right"></i>
                </a>
                <ul class="treeview-menu">
                  <li id="laporan-pembelian-detail-lokal"><a href="<?php echo $this->url->link('laporan/pembeliandetaillokal', 'token=' . $this->session->data['token'], 'SSL'); ?>">Pembelian Lokal</a></li>
                  <li id="laporan-pembelian-detail-import"><a href="<?php echo $this->url->link('laporan/pembeliandetailimport', 'token=' . $this->session->data['token'], 'SSL'); ?>">Pembelian Import</a></li>
                </ul>
              </li>
            </ul>
          </li>
          <li id="menu-pajak" class="treeview">
            <a>
              <span>Pajak</span> <i class="fa fa-angle-left pull-right"></i>
            </a>
            <ul class="treeview-menu">
              <li id="menu-faktur-pajak"><a href="<?php echo $this->url->link('sale/ppnmasukan', 'token=' . $this->session->data['token'], 'SSL'); ?>">PPn Keluaran</a></li>
              <li id="menu-ppn-masukan"><a href="<?php echo $this->url->link('keuangan/ppnmasukan', 'token=' . $this->session->data['token'], 'SSL'); ?>">PPn Masukan</a></li>
              <li id="menu-daftar-pph"><a href="<?php echo $this->url->link('keuangan/daftarpph', 'token=' . $this->session->data['token'], 'SSL'); ?>">PPh</a></li>
              <li class="treeview">
                <a>
                  <span>E-Faktur</span> <i class="fa fa-angle-left pull-right"></i>
                </a>
                <ul class="treeview-menu">
                  <li><a href="<?php echo $this->url->link('efaktur/pajakkeluaran', 'token=' . $this->session->data['token'], 'SSL'); ?>">Pajak Keluaran</a></li>
                  <li><a onclick="progress()">Pajak Masukan</a></li>
                  <li><a onclick="progress()">Barang</a></li>
                  <
                </ul>
              </li>
            </ul>
          </li>
          <li class="treeview">
            <a>
              <span>Pengaturan</span> <i class="fa fa-angle-left pull-right"></i>
            </a>
            <ul class="treeview-menu">
              <li><a href="<?php echo $this->url->link('setting/setting', 'token=' . $this->session->data['token'], 'SSL'); ?>">Setting</a></li>
              <li><a href="<?php echo $this->url->link('user/aksesmenu', 'token=' . $this->session->data['token'], 'SSL'); ?>">Hak Akses dan Otoritas</a></li>
              <li><a href="<?php echo $this->url->link('user/deviceakses', 'token=' . $this->session->data['token'], 'SSL'); ?>">Akses Perangkat</a></li>
              <li><a href="<?php echo $this->url->link('setting/locktanggal', 'token=' . $this->session->data['token'], 'SSL'); ?>">Lock Tanggal</a></li>
              <li><a href="<?php echo $this->url->link('setting/biayakirim', 'token=' . $this->session->data['token'], 'SSL'); ?>">Komponen Komisi Sales</a></li>
            </ul>
          </li>
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