<?php echo $header;
//print_r($fulldetail);
?>
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
        <div class="col-xs-3 col-sm-2 no-print">
          <img class="img-responsive" src="<?php echo HTTP_IMAGE . $this->config->get('config_logo'); ?>">
            <!--<small class="pull-right">Date: <?php echo date('d/m/y',strtotime($order['date_added']))?></small>-->

        </div>
        <div class="col-xs-4 col-sm-4 invoice-col">
          <address>
            <span class="compname"><strong><?php echo $this->config->get('config_name'); ?></strong></span><br>
            <?php echo $this->config->get('config_address'); ?><br>
            Email: <?php echo $this->config->get('config_email'); ?>
          </address>
        </div>
        <div class="col-xs-5 text-right">
          <strong>DELIVERY ORDER</strong><br>
          <small>No: <?php echo $order['no_do']; ?>
          
          </small>
        </div>
        <!-- /.col -->
      </div>
      <!-- info row -->
      <div class="row invoice-info">

        <!-- /.col -->
        <div class="col-xs-8 ">

          <b>Metode Pengiriman:</b> <?php echo $order['pengiriman'] == 1?'Diambil':'Diantar'; ?><br>
         
         
           <strong>No. Surat Jalan: </strong><br><?php echo $order['suratjalan']; ?>
        </div>
        <!-- /.col -->
        <div class="col-xs-4 ">
           <b>Tanggal:</b> <?php echo date('d/m/y',strtotime($order['date_added']))?><br>
          <b>Kendaraan:</b> <?php echo $order['no_pol']; ?><br>
          <b>Sopir:</b> <?php echo $order['sopir']; ?><br>
          <b>Kernet:</b><br>
          <?php echo $order['kernet1'].', '.$order['kernet2'].', '.$order['kernet3']; ?>

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
              <th>No. Tabung</th>
              <th>Keterangan</th>
              <th>Status</th>
              
            </tr>
            </thead>
            <tbody>
            <?php
            foreach($tabungs as $p){
            ?>
            <tr>
              <td>
                <?php echo $p['no_tabung']; ?><br>
                
              </td>
              <td>
                <?php echo $p['keterangan']; ?><br>
                
              </td>
            <td>
                <?php 
                if($p['status'] == 1){
                    echo 'Belum Diterima';
                }
                
                if($p['status'] == 2){
                    echo 'Diterima';
                }
                if($p['status'] == 3){
                    echo 'Retur';
                }
                if($p['status'] == 4){
                    echo 'Dibatalkan';
                }
                ?>
            </td>

            </tr>
            <?php
            }
            ?>

            </tbody>
          </table>
        </div>
        <!-- /.col -->
      </div>
      <div class="row">
        <div class="col-xs-6">
            
          <p class="text-muted well well-sm no-shadow" style="margin-top: 10px;">
            Total Tabung <?php echo $order['totaltabung']; ?> <br>
            Keterangan <br>
            
          </p>
        </div>
      </div>

      <div class="row print-only">
        <div class="col-xs-4">
          Diterima Oleh
          <br><br><br><br>
          (_______________)
        </div>
        <div class="col-xs-4">
          Gudang
          <br><br><br><br>
          (_______________)
        </div>
        <div class="col-xs-4">
          Distribusi
          <br><br><br><br>
          (_______________)
        </div>
      </div>
      <p></p>
      <div class="row print-only">
        <div class="col-xs-12">

          <p class="text-muted well well-sm no-shadow" style="margin-top: 10px;">
            Klaim Hanya dapat dilayani dalam waktu 2x24 jam setelah barang diterima<br>
            Tgl. Cetak: <?php echo date('d/m/y H:i:s'); ?><br>
            Surat Jalan telah dicetak sebanyak: <?php echo $order['cetak']; ?> kali
          </p>
        </div>
      </div>


      <!-- this row will not appear when printing -->
      <?php
      /*if($order['cetak'] < 1 | ($order['cetak'] >= 1 & $order['cetakulang'] == 1)){
      ?>
      <div class="row no-print">
        <div class="col-xs-12">
            <table class="table">
              <tr>
                <td>Ukuran Kertas</td>
                <td>
                  <select id="kertas" class="form-control">
                    <option value="1">1/2 A4</option>
                    <option value="2">Full A4</option>
                  </select>
                </td>
              </tr>
            </table>
        </div>
      </div>
      <div class="row no-print">
        <div class="col-xs-12 text-right">
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

    }*/
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
function cetak(){
  window.print();
}

function cetakulang(id){
  $.ajax({
    url: 'index.php?route=sale/deliveryorder/cetakulang&token=<?php echo $this->request->get['token']; ?>&id=<?php echo $order['id']; ?>&alasan='+$("#alasancetak").val(),
    dataType: 'json',
    success: function(json) {
      if(json.status){
        location.reload();
      }else{
        alert("Delivery Order sudah pernah dicetak ulang");
      }
    }
  })
}
function setujui(id){
  $.ajax({
    url: 'index.php?route=sale/deliveryorder/setujui&token=<?php echo $this->request->get['token']; ?>&id=<?php echo $order['id']; ?>&status='+$("#status").val(),
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
  ukuran=$("#kertas").val();
  totalcetak=<?php echo $order['totalcetak']; ?>;
  /*if(totalcetak > 0){
    alert("Surat jalan telah dicetak. Hubungi pimpinan untuk mencetak ulang");
  }else{*/
  if(ukuran != 1){
    $.ajax({
      url: '<?php echo PRINTER_JKT; ?>printsjfull',
      dataType: 'json',
      method:'POST',
      data:JSON.stringify(detail),
      success: function(json) {

        $.ajax({
          url: 'index.php?route=sale/deliveryorder/logcetak&token=<?php echo $this->request->get['token']; ?>&id=<?php echo $order['id']; ?>',
          dataType: 'json',
          success: function(json) {
            alert("Surat jalan berhasil dicetak.");
            location.reload();
          }
        })


      }
    });
  }else{

    $.ajax({
      url: '<?php echo PRINTER_JKT; ?>printsj',
      dataType: 'json',
      method:'POST',
      data:JSON.stringify(detail),
      success: function(json) {
        $.ajax({
          url: 'index.php?route=sale/deliveryorder/logcetak&token=<?php echo $this->request->get['token']; ?>&id=<?php echo $order['id']; ?>',
          dataType: 'json',
          success: function(json) {
            alert("Surat jalan berhasil dicetak.");
            location.reload();
          }
        })


      }
    });
  }
  //}
}

function cetaksby(detail){
  ukuran=$("#kertas").val();
  totalcetak=<?php echo $order['totalcetak']; ?>;
  /*if(totalcetak > 0){
    alert("Surat jalan telah dicetak. Hubungi pimpinan untuk mencetak ulang");

  }else{*/
  if(ukuran != 1){
    $.ajax({
      url: '<?php echo PRINTER_SBY; ?>printsjfull',
      dataType: 'json',
      method:'POST',
      data:JSON.stringify(detail),
      success: function(json) {
        $.ajax({
          url: 'index.php?route=sale/deliveryorder/logcetak&token=<?php echo $this->request->get['token']; ?>&id=<?php echo $order['id']; ?>',
          dataType: 'json',
          success: function(json) {
            alert("Surat jalan berhasil dicetak.");
            location.reload();
          }
        })


      }
    });
  }else{
    $.ajax({
      url: '<?php echo PRINTER_SBY; ?>printsj',
      dataType: 'json',
      method:'POST',
      data:JSON.stringify(detail),
      success: function(json) {
        $.ajax({
          url: 'index.php?route=sale/deliveryorder/logcetak&token=<?php echo $this->request->get['token']; ?>&id=<?php echo $order['id']; ?>',
          dataType: 'json',
          success: function(json) {
            alert("Delivery Order berhasil dicetak.");
            location.reload();
          }
        })


      }
    });
  }
  //}
}
</script>

<?php echo $footer; ?>
