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
          <div class="box-header with-border no-print">
            <!--<h3 class="box-title no-print">Surat Jalan Penjualan <?php echo $order['no_sj']; ?>
              <br>
              <?php
              if(isset($order['nama'])){
              ?>
              <small>Gudang: <?php echo $order['nama']; ?></small>
              <?php
              }
              ?>
            </h3>-->
            <div class="button pull-right no-print">

                  <a href="<?php echo $cancel; ?>"><button type="button" class="btn btn-danger">Kembali</button></a>
								</div>
          </div>
          <p></p>
          <section class="invoice">
      <!-- title row -->
      <div class="row">
        <div class="col-xs-3 col-sm-2">
          <img class="img-responsive" src="<?php echo HTTP_IMAGE . $this->config->get('config_logo'); ?>">
            <!--<small class="pull-right">Date: <?php echo date('d/m/y',strtotime($order['date_added']))?></small>-->

        </div>
        <div class="col-xs-5 col-sm-4 invoice-col">
          <address>

            <strong><?php echo $this->config->get('config_name'); ?></strong><br>
            <?php echo $this->config->get('config_address'); ?><br>
            Email: <?php echo $this->config->get('config_email'); ?>
          </address>
        </div>
        <div class="col-xs-4 text-right">
          <strong>INVOICE</strong><br>
          Nomor: <?php //echo $order['no_faktur']; ?>
            <a onclick="detail('<?php echo $order['id']?>')" data-toggle="modal" data-target="#jurnal"><?php echo $order['no_faktur']; ?></a>
        </div>
        <!-- /.col -->
      </div>
      <!-- info row -->
      <div class="row invoice-info">

        <!-- /.col -->
        <div class="col-xs-8 ">
          <strong>Kepada Yth:<br><?php
            $this->load->model('catalog/title');
             echo $this->model_catalog_title->getTitle($order['title']).' '.$order['name']; ?></strong><br>
          <address>

            <?php echo $order['alamat']; ?><br>
            Phone: <?php echo !empty($address['company_id'])?$address['company_id']:$order['telephone']; ?><br>
            Email: <?php echo $order['email']; ?><br>
            NPWP: <?php echo $order['npwp']?>
          </address>
        </div>
        <!-- /.col -->
        <div class="col-xs-4 ">
          <!--<b>No. Surat Jalan:</b> <?php echo $order['no_sj']; ?><br>
          <b>Metode Pengiriman:</b> <?php echo $order['pengiriman'] == 1?'Diambil':'Diantar'; ?><br>-->
          <b>Tanggal Faktur:</b> <?php echo date('d/m/Y',strtotime($order['date_added']))?><br>
          <b>Batas Pembayaran:</b> <?php echo date('d/m/Y',strtotime($order['jatuhtempo']))?><br>

        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->
      <p></p>
      <div class="row">
        <div class="col-xs-12 table-responsive">
          <table class="table table-bordered">
            <thead>
            <tr>
              <th>Nama Barang</th>
              <th>Qty</th>
              <th>Satuan</th>
              <th>Harga Satuan</th>
              <th>Jumlah</th>

            </tr>
            </thead>
            <tbody>
            <?php
            foreach($products as $p){
            ?>
            <tr>
              <td><?php echo $p['name']; ?></td>
              <td><?php echo $p['quantity']; ?></td>
              <td><?php echo $p['namasatuan']; ?></td>
              <td><?php echo $this->currency->format($p['price']); ?></td>

              <td><?php echo $this->currency->format($p['price'] * $p['quantity']); ?></td>
            </tr>
            <?php
            }
            ?>

            </tbody>
            <tfoot>
              <tr >
                <td class="text-right" colspan="4">Harga Jual/Penggantian/Uang Muka</td>
                <td><?php echo $this->currency->format($order['sub_total']); ?></td>
              </tr>
              <tr >
                <td class="text-right" colspan="4">Potongan Harga</td>
                <td><?php echo $this->currency->format($order['diskon']); ?></td>
              </tr>

              <tr >
                <td class="text-right" colspan="4">Dasar Pengenaan Pajak</td>
                <td><?php echo $this->currency->format($order['sub_total'] - $order['diskon']); ?></td>
              </tr>
              <tr >
                <td class="text-right" colspan="4">PPN 10%</td>
                <td><?php echo $this->currency->format($order['pajak']); ?></td>
              </tr>
              <tr >
                <td class="text-right" colspan="4">Uang Muka yang Telah Diterima</td>
                <td><?php echo $this->currency->format($order['dp']); ?></td>
              </tr>
              <?php
              if($order['jenisinvoice'] == 1 | $order['jenisinvoice'] == 3){
              ?>
              <tr >
                <td class="text-right" colspan="4">Jumlah yang Harus Dibayar</td>
                <td><?php echo $this->currency->format($order['total']); ?></td>
              </tr>
              <?php
              }
              ?>
              <?php
              if($order['jenisinvoice'] == 2){
              ?>
              <tr >
                <td class="text-right" colspan="4">Total Tagihan</td>
                <td><?php echo $this->currency->format($order['total']); ?></td>
              </tr>
              <tr >
                <td class="text-right" colspan="4">Uang Muka yang Harus Dibayar</td>
                <td><?php echo $this->currency->format($order['totaltagihan']); ?></td>
              </tr>
              <?php
              }
              ?>
            </tfoot>
          </table>
        </div>
        <!-- /.col -->
      </div>
      <div class="row">
        <div class="col-xs-6">

          <p class="text-muted well well-sm no-shadow" style="margin-top: 10px;">
            Keterangan <br>
            <b>Metode Pembayaran: <?php //echo $order['metode_pembayaran'] == 1?'Tunai':($order['metode_pembayaran'] == 2?'COD':'Kredit'); ?>
                    <?php 
                      // 1 tunai, 2 cod, 3 kredit, 4 CBD
                      $metode = $order['metode_pembayaran'];
                      if($metode==1){
                        echo "Tunai";
                      }else if($metode==2){
                        echo "COD";
                      }else if($metode==3){
                        echo "Kredit";
                      }else{
                        echo "CBD";
                      }
                    ?></b><br>
            Referensi : <?php echo $order['ref']; ?><br>
            Invoice telah dicetak sebanyak: <?php echo $order['cetak']; ?> kali oleh <b><?php echo $namauser['firstname'];?></b><br>
            <?php if($order['cetak']>1){?>
              Alasan cetak ulang : <?php echo $order['alasan_cetak']?>
            <?php } ?>

          </p>
        </div>
        <div class="col-xs-6 text-right print-only">
          Hormat Kami
          <br><br><br><br>
          (_______________)
        </div>
      </div>


      <p></p>

      <!-- this row will not appear when printing -->
      <?php
      if($order['cetak'] < 1 | ($order['cetak'] >= 1 & $order['cetakulang'] == 1)){

      ?>
      <div class="row no-print">
        <div class="col-xs-12">
            <a onclick='cetakjkt(<?php echo json_encode($fulldetail); ?>)' class="btn btn-default"><i class="fa fa-print"></i> Cetak Tangerang</a>
            <a onclick='cetaksby(<?php echo json_encode($fulldetail); ?>)' class="btn btn-default"><i class="fa fa-print"></i> Cetak Surabaya</a>

        </div>
      </div>
      <?php
      }else{
        if($order['cetakulang'] == 2){
        if(!$order['setujui']){
        ?>
        <div class="row no-print">
          <div class="col-xs-6 pull-right">
            <div class="callout callout-danger lead">
            Menunggu Persetujuan Cetak Ulang

            </div>
          </div>
        </div>
        <?php
      }else{
      ?>
      <div class="row no-print">
        <div class="col-xs-6 pull-right">
          <div class="callout callout-warning lead">
          <?php echo  $order['reqcetak']['firstname']; ?> Mengajukan permohonan cetak dengan alasan <b> <?php echo $order['alasan_cetak']; ?></b>


          </div>
          <table class="table table-responsive">
            <tr>
                <td>Status Permintaan</td>
                <td>
                  <select class="form-control" id="status">
                    <option value="1">Setujui</option>
                    <option value="3">Tolak</option>
                  </select>
                </td>
            </tr>
            <tr>
                <td></td>
                <td>
                  <a onclick='setujui(<?php $order['id']; ?>)' class="btn btn-default">Proses</a>
                </td>
            </tr>
          </table>
        </div>

      </div>

      <?php
      }
      }else{
        if($order['cetak'] >= 1 ){
        if($order['cetakulang'] == 3){
          ?>
          <div class="row no-print">
            <div class="col-xs-6 pull-right">
              <div class="callout callout-danger lead">
              Permintaan cetak ulang ditolak oleh <?php
              echo $order['usersetujui']['firstname'];?>

              </div>
            </div>
          </div>
          <?php
        }else{
        ?>
        <div class="row no-print">
          <div class="col-xs-6 pull-right">
            <div class="callout callout-primary lead">
            Ajukan permohonan cetak ulang

            </div>
            <table class="table table-responsive">
              <tr>
                  <td>Alasan Cetak Ulang</td>
                  <td>
                    <input class="form-control" type="text" id="alasancetak">
                  </td>
              </tr>
              <tr>
                  <td></td>
                  <td>
                    <a onclick='cetakulang(<?php $order['id']; ?>)' class="btn btn-default">Ajukan Permintaan Cetak Ulang</a>
                  </td>
              </tr>
            </table>



          </div>
        </div>
        <?php
        }
        }
        }

      }
        ?>
    </section>
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
$('.sidebar-menu').find('#menu-penjualan').addClass('active');
$('.sidebar-menu').find('#menu-salesorder').addClass('active');
$(document).on('keydown', function(e) {
    if((e.ctrlKey || e.metaKey) && (e.key == "p" || e.charCode == 16 || e.charCode == 112 || e.keyCode == 80) ){
        alert("Gunakan tombol cetak untuk mencetak surat jalan");
        e.cancelBubble = true;
        e.preventDefault();

        e.stopImmediatePropagation();
    }
});
function detail(id){
    $.ajax({
      url: 'index.php?route=sale/invoice/jurnal&token=<?php echo $token; ?>&id=' +  encodeURIComponent(id),
      //dataType: 'json',
      success: function(json) {
        $('.modal-body').html(json);
      }
    });/**/
}
function cetak(){
  window.print();
}
function cetakulang(id){
  $.ajax({
    url: 'index.php?route=sale/invoice/cetakulang&token=<?php echo $this->request->get['token']; ?>&id=<?php echo $order['id']; ?>&alasan='+$("#alasancetak").val(),
    dataType: 'json',
    success: function(json) {
      if(json.status){
        location.reload();
      }else{
        alert("Invoice sudah pernah dicetak ulang");
      }
    }
  })
}
function setujui(id){
  $.ajax({
    url: 'index.php?route=sale/invoice/setujui&token=<?php echo $this->request->get['token']; ?>&id=<?php echo $order['id']; ?>&status='+$("#status").val(),
    dataType: 'json',
    success: function(json) {
      if(json.status){
        location.reload();
      }else{
        alert("Anda tidak diijinkan memproses permintaan");
      }
    }
  })
}
function cetakjkt(detail){
  totalcetak=<?php echo $order['totalcetak']; ?>;
  /*if(totalcetak > 0){
    alert("Surat jalan telah dicetak. Hubungi pimpinan untuk mencetak ulang");
  }else{*/
    $.ajax({
      url: '<?php echo PRINTER_JKT; ?>printinvoice',
      dataType: 'json',
      method:'POST',
      data:JSON.stringify(detail),
      success: function(json) {
        $.ajax({
          url: 'index.php?route=sale/invoice/logcetak&token=<?php echo $this->request->get['token']; ?>&id=<?php echo $order['id']; ?>',
          dataType: 'json',
          success: function(json) {
            alert("Invoice berhasil dicetak.");
            location.reload();
          }
        })


      }
    });
//  }
}

function cetaksby(detail){
  totalcetak=<?php echo $order['totalcetak']; ?>;
  /*if(totalcetak > 0){
    alert("Surat jalan telah dicetak. Hubungi pimpinan untuk mencetak ulang");
  }else{*/
    $.ajax({
      url: '<?php echo PRINTER_SBY; ?>printinvoice',
      dataType: 'json',
      method:'POST',
      data:JSON.stringify(detail),
      success: function(json) {
        $.ajax({
          url: 'index.php?route=sale/invoice/logcetak&token=<?php echo $this->request->get['token']; ?>&id=<?php echo $order['id']; ?>',
          dataType: 'json',
          success: function(json) {
            alert("Invoice berhasil dicetak.");
            location.reload();
          }
        })

      }
    });
//  }
}
</script>

<?php echo $footer; ?>
