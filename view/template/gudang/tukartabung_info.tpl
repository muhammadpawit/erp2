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
            <h3 class="box-title">Tukar Tabung</h3>
            <div class="button pull-right">
                  <a href="<?php echo $cancel; ?>"><button type="button" class="btn btn-danger">Kembali</button></a>
                </div>
          </div>
          <div class="box-body">

            <div class="row">
              <div class="col-md-12">
                <table class="table">
                  <tr>
                      <td>Tanggal Permintaan:</td>
                      <td><?php echo date('d F Y',strtotime($permintaan['tgl_tukar'])); ?></td>
                  </tr>
                  <tr>
                      <td>Tanggal Proses:</td>
                      <td><?php echo !empty($permintaan['tgl_proses'])?date('d/m/y',strtotime($permintaan['tgl_proses'])):'Belum Diproses' ?></td>
                  </tr>
                  <tr>
                     <td>No Dokumen:</td>
                     <td><?php echo empty($permintaan['no_dokumen'])?'-':$permintaan['no_dokumen']; ?></td>
                 </tr>
                  <tr>
                     <td>Gudang:</td>
                     <td><?php echo $permintaan['nama']; ?></td>
                 </tr>
                 <tr>
                    <td>Quantity Tukar:</td>
                    <td><?php echo $permintaan['quantity']; ?></td>
                </tr>
                <tr>
                   <td>Tambahan Harga:</td>
                   <td><?php echo $this->currency->format($permintaan['tambahan_harga']); ?></td>
               </tr>

                   <tr>
                      <td>Status:</td>
                      <td><?php
                        if($permintaan['status'] == 3){
                          echo 'Ditolak/Dibatalkan';
                        }
                        if($permintaan['status'] == 1){
                          echo 'Menunggu Persetujuan';
                        }
                        if($permintaan['status'] == 2){
                          echo 'Selesai Diproses';
                        }



                      ?></td>
                  </tr>
               <tr>
                 <td>Keterangan</td>
                 <td><b><?php echo $permintaan['keterangan']; ?></b></td>
               </tr>



                </table>
                <table class="table">
                    <thead>
                      <th>Tabung Asal</th>
                      <th>Kran Yang Dipasang</th>
                      <th>Tabung Hasil</th>
                      <th>Kran Lepasan</th>

                    </thead>
                    <tbody>

                      <tr>
                        <td><?php echo $tabung_a['name']; ?></td>
                        <td><?php echo $kran_b['name']; ?></td>
                        <td><?php echo $tabung_b['name']; ?></td>
                        <td><?php echo $kran_lepasan['name']; ?></td>
                      </tr>

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
$('.sidebar-menu').find('#menu-persediaan').addClass('active');
$('.sidebar-menu').find('#menu-persediaan-produkdagang').addClass('active');
$('.sidebar-menu').find('#menu-tukar-kran').addClass('active');
</script>

<?php echo $footer; ?>
