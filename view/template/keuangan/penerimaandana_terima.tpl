<?php echo $header; ?>
<div class="content-wrapper" id="content">
  <section class="content-header">
    <h1>

    </h1>

  </section>

  <section class="content" >
    <div class="row">
      <div class="col-md-12">
        <div class="box">
          <div class="box-header with-border">
            <h3 class="box-title">Penerimaan Pembayaran Customer</h3>
            <div class="button pull-right">
              <a onclick="simpan()" ><button type="button" class="btn btn-primary">Simpan</button></a>
                <a href="<?php echo $cancel; ?>"><button type="button" class="btn btn-danger">Kembali</button></a>

								</div>
          </div>
          <div class="box-body">

            <div class="row">
              <div class="col-md-12">
                <div class="errordisplay"></div>
                <?php if ($error_warning) { ?>
                <div class="alert alert-danger alert-dismissible">
                  <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                  <h4><i class="icon fa fa-ban"></i> Alert!</h4>
                  <?php foreach($error_warning as $e){
                    echo $e.'<br>';
                  } ?>
                </div>
                <?php
                }
                ?>
              </div>
            </div>
            <div class="row">
              <div class="col-md-12">
                <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
                  <table class="table">
                    <tr>
                        <td>Nomor Penerimaan:</td>
                        <td><?php echo $penerimaan['no_pd']; ?></td>
                    </tr>
                    <tr>
                        <td>Jenis Pembayaran:</td>
                        <td><?php echo $penerimaan['jenis'] == 1?'Deposit Customer':'Pembayaran Tunai/COD'; ?></td>
                    </tr>
                    <?php
                    if($penerimaan['jenis'] == 2){
                    ?>
                    <tr>
                        <td>Nomor Invoice:</td>
                        <td><a href="<?php echo $penerimaan['href']; ?>" target="_blank"><?php echo $penerimaan['inv']['no_faktur']; ?></td>
                    </tr>
                    <?php
                    }
                    ?>
                    <tr>
                        <td>Metode Pembayaran:</td>
                        <td><?php
                          if($penerimaan['metode_pembayaran'] == 1){
                            echo 'Tunai';
                          }
                          if($penerimaan['metode_pembayaran'] == 2){
                            echo 'Transfer Bank';
                          }
                          if($penerimaan['metode_pembayaran'] == 3){
                            echo 'Giro';
                          }
                          if($penerimaan['metode_pembayaran'] == 4){
                            echo 'Cheque';
                          }
                          if($penerimaan['metode_pembayaran'] == 5){
                          echo 'Hutang Lain';
                        }
                        if($penerimaan['metode_pembayaran'] == 6){
                          echo 'Biaya';
                        }
                           ?></td>
                    </tr>
                    <?php
                    if($penerimaan['metode_pembayaran'] == 4){
                    ?>
                    <tr>
                        <td>Nomor Giro:</td>
                        <td><?php echo $penerimaan['no_giro']; ?></td>
                    </tr>
                    <?php
                    }
                    ?>
                    <tr>
                        <td>Tanggal Bayar:</td>
                        <td><?php echo date('d/m/y',strtotime($penerimaan['tgl_bayar'])); ?></td>
                    </tr>
                    <?php
                    if($penerimaan['status'] == 2){
                    ?>
                    <tr>
                        <td>Tanggal Diterima:</td>
                        <td><?php echo date('d/m/y',strtotime($penerimaan['tgl_diterima'])); ?></td>
                    </tr>
                    <?php
                    }
                    ?>
                    <tr>
                        <td>Customer:</td>
                        <td><?php echo $penerimaan['customer']; ?></td>
                    </tr>
                    <tr>
                        <td>Status:</td>
                        <td><?php echo $penerimaan['status'] == 1?'Disimpan':($penerimaan['status'] == 2?'Diterima':'Dibatalkan'); ?></td>
                    </tr>
                    <tr>
                        <td>Bank/Kas:</td>
                        <td><?php echo $penerimaan['name']; ?></td>
                    </tr>
                    <tr>
                        <td>Jumlah:</td>
                        <td><?php echo $this->currency->format($penerimaan['nominal']+$penerimaan['biaya_bank']+$penerimaan['biaya_lain']); ?></td>
                    </tr>
                    <tr>
                        <td>Biaya Administrasi Bank:</td>
                        <td><?php echo $this->currency->format($penerimaan['biaya_bank']); ?> </td>
                    </tr>
                    <tr>
                        <td>Biaya Lain-lain:</td>
                        <td><?php echo $this->currency->format($penerimaan['biaya_lain']); ?> </td>
                    </tr>
                    <tr>
                      <td>Pendapatan Lain-lain:</td>
                      <td><?php echo $this->currency->format($penerimaan['pendapatan_lain']); ?></td>
                  </tr>
                    <tr>
                        <td>Keterangan:</td>
                        <td><?php echo $penerimaan['keterangan']; ?><input type="hidden" name="keterangan" value="<?php echo trim(preg_replace('/\s+/', ' ', $penerimaan['keterangan']));  ?>"></td>
                    </tr>
                    <tr>
                      <td>Biaya Marketplace:</td>
                      <td><?php echo $this->currency->format($penerimaan['biayamarketplace']); ?></td>
                    </tr>
                      <tr >
                         <td><span class="required">*</span>Tanggal Diterima</td>
                         <td ><input type="text" name="tgl_diterima" class="date form-control" value="<?php echo date('Y-m-d'); ?>" readonly >

                         </td>
                       </tr>

                    </table>

                </form>
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
$('.sidebar-menu').find('#menu-keuangan').addClass('active');
function simpan(){

  $('#form').submit();
}
</script>

<script type="text/javascript"><!--
$(document).ready(function() {
  $('.date').datepicker({dateFormat: 'yy-mm-dd'});
})
//--></script>

<?php echo $footer; ?>
