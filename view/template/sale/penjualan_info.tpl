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
            <h3 class="box-title no-print">Surat Jalan Penjualan <?php echo $order['no_sj']; ?>
              <br>
              <?php
              if(isset($order['nama'])){
              ?>
              <small>Gudang: <?php echo $order['nama']; ?></small>
              <?php
              }
              ?>
            </h3>
            <div class="button pull-right no-print">

                  <a href="<?php echo $cancel; ?>"><button type="button" class="btn btn-danger">Kembali</button></a>
								</div>
          </div>
          <p></p>
          <section class="invoice">
      <!-- title row -->
      
      <!-- info row -->
      <div class="row invoice-info">
        <div class="col-xs-12 col-md-6">
       <table class="table table-bordered">
            <tr>
                <td>No. Surat Jalan</td>
                <td><?php echo $order['no_sj']; ?></td>
            </tr>
            <tr>
                <td>Customer</td>
                <td><?php echo $order['name']; ?></td>
            </tr>
            <tr>
                <td>Alamat</td>
                <td><?php
                    if($order['address_id'] > 0){
                    ?>
                    <address>
                        
                        
                        <?php echo trim($address['address_1'].' '.$address['address_2'],"'"); ?><br>
                        <?php echo $address['city'].', '.$address['zone'].', '.$address['country'].', '.$address['postcode']; ?><br>
                        Phone: <?php echo !empty($address['company_id'])?$address['company_id']:$order['telephone']; ?><br>
                        Email: <?php echo $order['email']; ?>
                    </address>
                    <?php
                    }else{
                    ?>
                    <address>
                        
                        DIAMBIL
                    </address>
                    <?php
                    }
                    ?>
                </td>
            </tr>
            <tr>
                <td>No. DO</td>
                <td>
                <?php 
                if($order['do_id'] > 0){
                ?>
                <a target="_blank" href="<?php echo $order['hrefdo']; ?>">
                <?php echo $order['no_do']; ?>
                </a>
                <?php 
                }
                ?>
                </td>
            </tr>
            <tr>
                <td>Tanggal Terima</td>
                <td>
                  <?php echo !empty($order['tglterima'])?date('d/m/Y',strtotime($order['tglterima'])):'Belum Diterima'; ?>
                </td>
            </tr>
            <tr>
                <td>Total Tabung Dialokasikan</td>
                <td>
                  <?php echo $order['totaltabung']; ?>
                </td>
            </tr>
        </table>
        </div>
        <div class="col-xs-12 col-md-6">
       <table class="table table-bordered">
           <tr>
                <td>Tanggal</td>
                <td><?php echo date('d/m/y',strtotime($order['date_added']))?></td>
            </tr>
            <tr>
                <td>Kendaraan</td>
                <td><?php echo $order['no_pol']; ?></td>
            </tr>
            <tr>
                <td>Sopir</td>
                <td><?php echo $order['sopir']; ?></td>
            </tr>
            <tr>
                <td>Kernet</td>
                <td><?php
          $i=1;
           foreach($order['kernets'] as $k){
             echo $i.". ".$k['firstname']."<br>";
             $i++;
           }
          ?></td>
            </tr>
        </table>
        </div>
        <!-- /.col -->
        
      </div>
      <!-- /.row -->
      <p></p>
      <div class="row">
        <div class="col-xs-12 table-responsive">
        <div class="nav-tabs-custom">
              <ul class="nav nav-tabs">
                <li class="active"><a href="#detail" data-toggle="tab">Daftar Barang</a></li>
                <li><a href="#alokasitabung" data-toggle="tab">Alokasi Tabung</a></li>
            </ul>
        </div>
        <div class="tab-content">
          <div class="tab-pane active " id="detail">
              <table class="table table-bordered">
                <thead>
                <tr>
                  <th>Qty + Satuan</th>
                  <th>Nama Barang</th>
                  <th>No. SO</th>
                  <th>Batalkan</th>
                  <!--<th>Tabung</th>
                  <th>Harga Satuan</th>
                  <th>Diskon</th>
                  <th>Pajak</th>
                  <th>Total</th>-->
                </tr>
                </thead>
                <tbody>
                <?php
                foreach($products as $p){
                ?>
                <tr>
                  <td><?php echo $p['quantity']; ?> <?php echo $p['namasatuan']; ?></td>
                  <!--<td><?php echo $p['product_id']; ?></td>-->

                  <td>
                    <?php echo $p['name']; ?><br>
                    <small><?php echo $p['no_tabung']; ?></small>
                  </td>
                  <td><?php echo $p['no_salesorder']; ?> </td>
                  <td>
                    <?php
                    if($order['status'] == 1){
                      if($p['invoice_id'] < 1){
                      if($canceldata){
                      ?>
                        <a class="badge bg-red" href="<?php echo $this->url->link('sale/penjualan/batalkanproduk', 'token=' . $this->session->data['token'] . '&order_id='.$order['id'].'&penjualan_product_id=' . $p['id'], 'SSL'); ?>">Batalkan</a>
                      <?php
                      }
                      }
                    }
                    ?>
                  </id>

                </tr>
                <?php
                }
                ?>

                </tbody>
            </table>
            </div>
              <div class="tab-pane" id="alokasitabung">
                <table class="table" id="list-tabung">
                    <thead>
                        <tr>
                        <th class="left">No. Tabung</th>
                        <th class="right">Ukuran</th>
                       

                        </tr>
                    </thead>
                <?php $tabung_row=0;?>
                <tbody>
                  <?php 
                  foreach($tabungs as $t){
                  ?>
                  <tr>
                      <td><?php echo $t['no_tabung']; ?></td>
                      <td><?php echo $t['name']; ?></td>
                     
                  </tr>
                  <?php
                  }
                  ?>
                </tbody>
                
                </table>
            </div>
          </div>
        </div>
        <!-- /.col -->
      </div>
      <div class="row">
        <div class="col-xs-6">

          <p class="text-muted well well-sm no-shadow" style="margin-top: 10px;">
            Keterangan : <?php echo $order['keterangan'] ?><br>
            Metode Pengiriman: <?php echo $order['pengiriman'] == 1?'Diambil':'Diantar'; ?><br>
            Surat Jalan telah dicetak sebanyak: <?php echo $order['cetak']; ?> kali&nbsp;
            Oleh <b><?php echo $namauser['firstname'] ?></b><br>
            <?php if($order['cetak']>1){ ?>
            Alasan cetak ulang : <?php echo $order['alasan_cetak'] ?>
            <?php } ?>
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
            Surat Jalan telah dicetak sebanyak: <?php echo $order['cetak']; ?> kali<br>
            Oleh : <b><?php echo $namauser['firstname'] ?></b>
          </p>
        </div>
      </div>


      <!-- this row will not appear when printing -->
      <?php
      if($order['cetak'] < 1 | ($order['cetak'] >= 1 & $order['cetakulang'] == 1)){
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
            <a onclick='cetakjkt(<?php echo json_encode($fulldetail) ?>)' class="btn btn-default"><i class="fa fa-print"></i> Cetak Tangerang</a>
            <a onclick='cetakjkt(<?php echo json_encode($fulldetail) ?>)'class="btn btn-default"><i class="fa fa-print"></i> Cetak Surabaya</a>

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
      url: 'index.php?route=sale/penjualan/jurnal&token=<?php echo $token; ?>&id=' +  encodeURIComponent(id),
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
    url: 'index.php?route=sale/penjualan/cetakulang&token=<?php echo $this->request->get['token']; ?>&id=<?php echo $order['id']; ?>&alasan='+$("#alasancetak").val(),
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
    url: 'index.php?route=sale/penjualan/setujui&token=<?php echo $this->request->get['token']; ?>&id=<?php echo $order['id']; ?>&status='+$("#status").val(),
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
          url: 'index.php?route=sale/penjualan/logcetak&token=<?php echo $this->request->get['token']; ?>&id=<?php echo $order['id']; ?>',
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
          url: 'index.php?route=sale/penjualan/logcetak&token=<?php echo $this->request->get['token']; ?>&id=<?php echo $order['id']; ?>',
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
          url: 'index.php?route=sale/penjualan/logcetak&token=<?php echo $this->request->get['token']; ?>&id=<?php echo $order['id']; ?>',
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
          url: 'index.php?route=sale/penjualan/logcetak&token=<?php echo $this->request->get['token']; ?>&id=<?php echo $order['id']; ?>',
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
</script>

<?php echo $footer; ?>
