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
            <h3 class="box-title">Penggembosan Produksi</h3>
            <div class="button pull-right">
                  <a href="<?php echo $cancel; ?>"><button type="button" class="btn btn-danger">Kembali</button></a>
								</div>
          </div>
          <div class="box-body">

            <div class="row">
              <div class="col-md-12">
                <table class="table">

                  <tr>
                      <td>Tanggal:</td>
                      <td><?php echo date('d F Y',strtotime($permintaan['date_added'])) ?></td>
                  </tr>

                  <tr>
                     <td>Gudang:</td>
                     <td><?php echo !empty($gudang)?$gudang['nama']:'Tanpa Gudang'; ?></td>
                 </tr>
                   <tr>
                      <td>Keterangan:</td>
                      <td><?php echo $permintaan['keterangan'] ?></td>
                  </tr>
                  <tr>
                     <td>Jenis Produksi:</td>
                     <td><?php echo $permintaan['jenis_produksi'] == 1?'MR':($permintaan['jenis_produksi'] == 2?'Stok':'MP'); ?></td>
                 </tr>
                 <tr>
                    <td>Jenis Gas:</td>
                    <td><?php echo $products[0]['name'] ?></td>
                </tr>
                <tr>
                   <td>Quantity:</td>
                   <td><?php echo $products[0]['quantity'] ?></td>
               </tr>


                </table>
                <?php
                if(!empty($tabungs)){
                ?>
                <div class="callout callout-success lead">
                  <h4>Tabung</h4>

                </div>
                <table class="table">
                    <thead>
                      <th>Nama/Nomor Tabung</th>
                      <th>Quantity</th>

                    </thead>
                    <tbody>
                      <?php
                      foreach($tabungs as $p){
                      ?>
                      <tr>
                        <td><?php echo $p['name']; ?></td>
                        <td><?php echo $p['quantity']; ?></td>

                      </tr>
                      <?php
                      }
                      ?>
                    </tbody>
                </table>
                <?php
                }
                ?>
                
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
$('.sidebar-menu').find('#menu-produksi').addClass('active');

</script>

<?php echo $footer; ?>
