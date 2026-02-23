<?php echo $header; ?>
<div class="content-wrapper">
  <section class="content-header">
    <h1>

    </h1>

  </section>

  <section class="content">
    <div class="row">
      <div class="col-md-12">
        <div class="box">
          <div class="box-header with-border">
            <h3 class="box-title">Barang Datang Pembelian Import</h3>
            <div class="button pull-right">
                  <a href="<?php echo $cetak; ?>" target="_blank"><button type="button" class="btn btn-default"><i class="fa fa-print"></i> Cetak</button></a>
                  <a href="<?php echo $cancel; ?>"><button type="button" class="btn btn-danger">Kembali</button></a>
								</div>
          </div>
          <div class="box-body">

            <div class="row">
              <div class="col-md-12">
                <table class="table">
                  <tr>
                      <td>Nomor Faktur:</td>
                      <td><?php echo $permintaan['no_faktur'] ?></td>
                  </tr>
                  <tr>
                      <td>Vendor:</td>
                      <td><?php echo $permintaan['name'] ?></td>
                  </tr>
                  <tr>
                      <td>Tanggal:</td>
                      <td><?php echo date('d F Y',strtotime($permintaan['date_added'])) ?></td>
                  </tr>


            </table>
            <!--
            <div class="box-header with-border">
              <h3 class="box-title">Kurs Pembelian</h3>

            </div>
           <table class="table">
                <thead>
                  <th>Kurs Bank</th>
                  <th>Kurs Tengah BI</th>
                  <th>Kurs KMK</th>

                </thead>
                <tbody>

                  <tr>
                    <td><?php echo $this->currency->format($permintaan['total']*$permintaan['kursbank']); ?></td>
                    <td><?php echo $this->currency->format($permintaan['total']*$permintaan['kursbi']); ?></td>
                    <td><?php echo $this->currency->format($permintaan['total']*$permintaan['kurskmk']); ?></td>

                  </tr>

                </tbody>
            </table>-->
            <div class="box-header with-border">
              <h3 class="box-title">History Penerimaan</h3>

            </div>
            <table class="table">
                <thead>
                  <th>No. Surat Jalan</th>
                  <th>Tgl. Surat Jalan</th>
                  <th>Tgl. Terima</th>
                  <th>No. Polisi</th>
                  <th>Penerima</th>
                  <th>Pengangkut</th>
                  <th>Nama Produk</th>
                  <th>Quantity</th>
                  <th>Quantity Terima</th>


                </thead>
                <tbody>
                  <?php
                  //print_r($products);
                  $this->load->model('user/user');

                  foreach($products as $p){
                    $penerima='';
                    $pengangkut='';
                    if(!empty($p['penerima_id'])){
                      $pen=$this->model_user_user->getUser($p['penerima_id']);
                      $penerima=$pen['firstname'];
                    }

                    if(!empty($p['pengangkut_id'])){
                      $pen=$this->model_user_user->getUser($p['pengangkut_id']);
                      $pengangkut=$pen['firstname'];
                    }
                  ?>
                  <tr>
                    <td><?php echo $p['no_suratjalan']; ?></td>
                    <td><?php echo !empty($p['tgl_surat'])?date('d/m/y',strtotime($p['tgl_surat'])):date('d/m/y',strtotime($p['date_added'])); ?></td>
                    <td><?php echo !empty($p['tgl_terima'])?date('d/m/y',strtotime($p['tgl_terima'])):date('d/m/y',strtotime($p['date_added'])); ?></td>
                    <td><?php echo $p['no_pol']; ?></td>
                    <td><?php echo $penerima; ?></td>
                    <td><?php echo $pengangkut; ?></td>
                    <td><?php echo $p['product_name']; ?></td>
                    <td><?php echo $p['quantity']; ?></td>
                    <td><?php echo $p['qtyterima']; ?></td>
                  </tr>
                  <?php
                  }
                  ?>
                </tbody>
            </table>


              </div>
            </div>

          </div>
          <div class="box-footer">
            <div class="row">
              <div class="col-md-12">

              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
</div>
</div>
<script>
$('.sidebar-menu').find('#menu-pembelian').addClass('active');
$('.sidebar-menu').find('#menu-pembelian-import').addClass('active');
('.sidebar-menu').find('#menu-barang-datang').addClass('active');

</script>

<?php echo $footer; ?>
