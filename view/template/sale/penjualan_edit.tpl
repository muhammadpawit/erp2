<?php echo $header;
//print_r($fulldetail);
?>
<form method="post" action="<?php echo $simpanedit ?>" id="formedit">
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
            <h4>Edit Surat Jalan</h4>
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
                <a onclick="edit()" class="btn btn-success">Simpan</a>
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
          <strong>SURAT JALAN</strong><br>
          <small>No: <?php echo $order['no_sj']; ?></small>
        </div>
        <!-- /.col -->
      </div>
      <!-- info row -->
      <div class="row invoice-info">

        <!-- /.col -->
        <div class="col-xs-8 ">

          <strong>Kepada: <?php echo $order['name']; ?></strong><br>
          <?php
          if($order['address_id'] > 0){
          ?>
          <address>
            <!--
            <strong><?php echo $address['firstname']; ?></strong><br>-->
            <strong>Alamat:</strong><br>
            <?php echo $address['address_1'].' '.$address['address_2']; ?><br>
            <?php echo $address['city'].', '.$address['zone'].', '.$address['country'].', '.$address['postcode']; ?><br>
            Phone: <?php echo !empty($address['company_id'])?$address['company_id']:$order['telephone']; ?><br>
            Email: <?php echo $order['email']; ?>
          </address>
          <?php
        }else{
          ?>
          <address>
            <!--
            <strong><?php echo $address['firstname']; ?></strong><br>-->
            <strong>Alamat:</strong><br> DIAMBIL
          </address>
          <?php
        }
          ?>
        </div>
        <!-- /.col -->
        <div class="col-xs-4 ">
          <!--<b>No. Surat Jalan:</b> <?php echo $order['no_sj']; ?><br>
          <b>Metode Pengiriman:</b> <?php echo $order['pengiriman'] == 1?'Diambil':'Diantar'; ?><br>-->
          <b>Tanggal:</b> <?php echo date('d/m/y',strtotime($order['date_added']))?><br>
          <b>Kendaraan:</b> <?php echo $order['no_pol']; ?><br>
          <b>Sopir:</b><?php echo $order['sopir']; ?>
            <select name="sopir" class="sales form-control">
              <option value="*"><?php echo $order['sopir']; ?></option>
              <!-- <?php foreach($sopirs as $s){?>
              <option value="<?php echo $s['firstname'] ?>"><?php echo $s['firstname'] ?></option>
              <?php } ?> -->
            </select>
            <br>
          <b>Kernet:</b><br>
          <?php
          $i=1;
           foreach($order['kernets'] as $k){
             echo $i.". ".$k['firstname']."<br>";
             $i++;
           }
          ?>
          <select name="kernet[1]" class="kernet form-control">
            <option value="*"> <?php
          $i=1;
           foreach($order['kernets'] as $k){
             echo $i.". ".$k['firstname']."<br>";
             $i++;
           }
          ?></option>
          </select>
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
              <th>Qty + Satuan</th>
              <th>Nama Barang</th>
              <th>No. SO</th>
              <!--<th>Tabung</th>
              <th>Harga Satuan</th>
              <th>Diskon</th>
              <th>Pajak</th>
              <th>Total</th>-->
            </tr>
            </thead>
            
              <input type="hidden" name="id" value="<?php echo $_REQUEST['order_id'] ?>">
              <input type="hidden" name="no_sj" value="<?php echo $order['no_sj'] ?>">
              <input type="hidden" name="gudang_id" value="<?php echo $order['gudang_id'] ?>">
            <tbody>
            <?php
            $row=0;
            foreach($products as $p){
            ?>
            <input type="hidden" name="products[<?php echo $row ?>][sales_order_id]" value="<?php echo $p['nomor']; ?>">
            <input type="hidden" name="products[<?php echo $row ?>][product_id]" value="<?php echo $p['product_id']; ?>">
            <input type="hidden" name="products[<?php echo $row ?>][net_cost]" value="<?php echo $p['net_cost'] ?>">
            <input type="hidden" name="products[<?php echo $row ?>][price]" value="<?php echo $p['price'] ?>">
            <input type="hidden" name="products[<?php echo $row ?>][pajak]" value="<?php echo $p['price']/10 ?>">
            <input type="hidden" name="products[<?php echo $row ?>][qtykirimpertama]" value="<?php echo $p['quantity']; ?>">
            <input type="hidden" name="products[<?php echo $row ?>][quantitypesan]" value="<?php echo $p['quantitypesan']; ?>">
            <tr>
              <td><input type="text" name="products[<?php echo $row ?>][qty]" value="<?php echo $p['quantity']; ?>" class="form-control" readonly><?php echo $p['namasatuan']; ?></td>
              <!--<td><?php echo $p['product_id']; ?></td>-->

              <td>
                <?php echo $p['name']; ?><br>
                <small><?php echo $p['no_tabung']; ?></small>
              </td>
              <td><?php echo $p['no_salesorder']; ?> </td>
            </tr>
            <?php
            $row++;
            }
            ?>

            </tbody>
          </table>
        </div>
        <!-- /.col -->
      </div>
</form>      
      <div class="row">
        <div class="col-xs-6">

          <p class="text-muted well well-sm no-shadow" style="margin-top: 10px;">
            Keterangan <br>
            Metode Pengiriman: <?php echo $order['pengiriman'] == 1?'Diambil':'Diantar'; ?><br>

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
        <!-- <?php echo  $order['reqcetak']['firstname']; ?> Mengajukan permohonan cetak dengan alasan <b> <?php echo $order['alasan_cetak']; ?></b> -->


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
                <!-- <a onclick='setujui(<?php $order['id']; ?>)' class="btn btn-default">Proses</a> -->
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

function edit(){
  $("#formedit").submit();
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
<script>
$(function(){
  $(".sales").select2({
    ajax: {
    url:"index.php?route=user/user/autocomplete&token=<?php echo $token; ?>",
    //url: "index.php?route=pamerantoko/toko/autocomplete&token=<?php echo $this->request->get['token']; ?>",
    dataType: 'json',
    data: function (params) {
      return {
        q: params.term,
        j:22 // search term

      };
    },
    delay: 250,
    processResults: function (data) {
      return {
        results: data
      };
    },
    //cache: true
  },
    theme:"bootstrap"
  });
  $(".kernet").select2({
    ajax: {
      url:"index.php?route=user/user/autocomplete&token=<?php echo $token; ?>",
      //url: "index.php?route=pamerantoko/toko/autocomplete&token=<?php echo $this->request->get['token']; ?>",
      dataType: 'json',
      data: function (params) {
        return {
          q: params.term,
          j:23 // search term

        };
      },
      delay: 250,
      processResults: function (data) {
        return {
          results: data
        };
      },
      //cache: true
    },
    theme:"bootstrap"
  });
})
</script>
<?php echo $footer; ?>
