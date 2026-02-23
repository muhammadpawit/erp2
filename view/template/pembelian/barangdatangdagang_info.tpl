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
            <h3 class="box-title">Surat Jalan Pembelian Produk Dagang</h3>
            <div class="button pull-right">
                  <a href="<?php echo $cancel; ?>"><button type="button" class="btn btn-danger">Kembali</button></a>
								</div>
          </div>
          <div class="box-body">

            <div class="row">
              <div class="col-md-12">
                <table class="table">
                  <tr>
                      <td>Nomor Surat Jalan:</td>
                      <td><?php echo $permintaan['no_suratjalan'] ?></td>
                  </tr>
                  <tr>
                      <td>Gudang:</td>
                      <td><?php echo $permintaan['nama'] ?></td>
                  </tr>
                  <tr>
                      <td>Tanggal Dibuat:</td>
                      <td><?php echo date('d F Y',strtotime($permintaan['date_added'])) ?></td>
                  </tr>
                  <?php
                  if($permintaan['status'] == 2){
                  ?>
                  <tr>
                      <td>Tanggal Surat Jalan:</td>
                      <td><?php echo date('d F Y',strtotime($permintaan['tgl_surat'])) ?></td>
                  </tr>
                  <tr>
                      <td>Tanggal Diterima:</td>
                      <td><?php echo date('d F Y',strtotime($permintaan['tgl_terima'])) ?></td>
                  </tr>
                  <tr>
                      <td>Penerima:</td>
                      <td><?php echo $penerima['firstname'] ?></td>
                  </tr>
                  <tr>
                      <td>Pengangkut:</td>
                      <td><?php echo $pengangkut['firstname'] ?></td>
                  </tr>
                  <tr>
                      <td>No. Polisi:</td>
                      <td><?php echo $permintaan['no_pol'] ?></td>
                  </tr>

                  <?php
                  }
                  ?>
                  <tr>
                     <td>Status:</td>
                     <td><?php echo $permintaan['status'] == 1?'Belum Diterima':($permintaan['status'] == 2?'Sudah Diterima':Dibatalkan) ?></td>
                 </tr>

            </table>
            <div class="nav-tabs-custom">
              <ul class="nav nav-tabs">
                <li class="active"><a href="#detail" data-toggle="tab">Daftar Barang</a></li>
                <li><a href="#biaya" data-toggle="tab">Biaya</a></li>


              </ul>
              <div class="tab-content">
                <div class="tab-pane active " id="detail">
                  <table class="table table-responsive" id="list-product-detail" >
                    <thead>
                      <th>No. PO</th>
                      <th>Nama Produk</th>
                      <th>Quantity SJ</th>


                    </thead>
                    <tbody>
                      <?php
                      //print_r($products);

                      foreach($products as $p){

                      ?>
                      <tr>
                        <td><?php echo $p['no_po']; ?></td>
                        <td><?php echo $p['product_name']; ?></td>
                        <td><?php echo $p['qtyterima']; ?></td>

                      </tr>
                      <?php
                      }
                      ?>
                    </tbody>
                  </table>
                </div>
                <div class="tab-pane " id="biaya">
                  <table class="table" id="list-biaya">
                    <thead>
                      <tr>
                        <th class="left">Nama Biaya</th>
                        <th class="right">Nominal</th>

                      </tr>
                    </thead>

                    <tbody>
                      <?php
                      foreach($biayas as $p){
                      ?>
                      <tr>
                        <td><?php echo $p['name']; ?></td>
                        <td><?php
                          //print_r($p);
                          if($p['currency'] == 1){
                            echo '$'.$p['total'];
                          }else{
                            echo $this->currency->format($p['total']);
                          }
                           ?></td>
                      </tr>
                      <?php
                      }
                      ?>
                    </tbody>

                  </table>
                </div>
              </div>
            </div>

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
$('.sidebar-menu').find('#menu-pembelian-kredit').addClass('active');
('.sidebar-menu').find('#menu-barang-datang').addClass('active');

</script>

<?php echo $footer; ?>
