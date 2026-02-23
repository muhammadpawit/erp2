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
                  <a onclick="cetak()" target="_blank"><button type="button" class="btn btn-success">Download / Cetak PDF</button></a>

                  <a href="<?php echo $cancel; ?>"><button type="button" class="btn btn-danger">Kembali</button></a>
								</div>
          </div>
          <p></p>
          <section class="invoice" style="font-family: 'arial'; font-size: 13px !important;">
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
        <div class="col-xs-4 text-center">
        	<?php 
        		/*
        		if($order['customer_id']==629 || $order['customer_id']==620){
        			echo "<strong>INVOICE</strong><br>";
        		}else
        		{
        			echo "<strong>PROFORMA INVOICE</strong><br>";
        		}
        		*/
        	?>
        	<strong>PROFORMA INVOICE</strong><br>
          <?php echo $order['no_faktur']; ?>
        </div>
        <!-- /.col -->
      </div>
      <!-- info row -->
      <div class="row invoice-info">

        <!-- /.col -->
        <div class="col-xs-6 ">
          <!-- <strong>Kepada Yth:<br><?php
            $this->load->model('catalog/title');
             echo $this->model_catalog_title->getTitle($order['title']).' '.$order['name']; ?></strong><br>
          <address>

            <?php echo $order['alamat']; ?><br>
            Phone: <?php echo !empty($address['company_id'])?$address['company_id']:$order['telephone']; ?><br>
            Email: <?php echo $order['email']; ?><br>
            NPWP: <?php echo $order['npwp']?>
          </address> -->
          <b>Referensi :</b><br>
          <b>Metode Pembayaran : 
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
              ?>
              <?php //echo $order['metode_pembayaran'] == 1?'Tunai':($order['metode_pembayaran'] == 2?'COD':'Kredit'); ?></b><br>
          <b>Tanggal Faktur:</b> <?php echo date('d/m/Y',strtotime($order['date_added']))?><br>
          <b>Jatuh Tempo:</b> <?php echo date('d/m/Y',strtotime($order['jatuhtempo']))?><br>
          <b>Referensi:</b><br>
          <?php echo $so['no_so'] ?>
        </div>
        <!-- /.col -->
        <div class="col-xs-6 ">
          <strong>Kepada : Yth,<br><?php
            $this->load->model('catalog/title');
             echo $this->model_catalog_title->getTitle($order['title']).' '.$order['name']; ?></strong><br>
          <address>

            <?php echo $order['alamat']; ?><br>
            Phone: <?php echo !empty($address['company_id'])?$address['company_id']:$order['telephone']; ?><br>
            Email: <?php echo $order['email']; ?><br>
            NPWP: <?php echo $order['npwp']?>
          </address>
          <!--<b>No. Surat Jalan:</b> <?php echo $order['no_sj']; ?><br>
          <b>Metode Pengiriman:</b> <?php echo $order['pengiriman'] == 1?'Diambil':'Diantar'; ?><br>-->
          <!-- <b>Tanggal Faktur:</b> <?php echo date('d/m/y',strtotime($order['date_added']))?><br>
          <b>Batas Pembayaran:</b> <?php echo date('d/m/y',strtotime($order['jatuhtempo']))?><br> -->

        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->
      <p></p>
      <div class="row">
        <div class="col-xs-12 table-responsive">
          <table width="100%">
            <tbody>
            <tr style="border-top:1px solid #e6e6e6;border-bottom:1px solid #e6e6e6">
              <td><b>Nama Barang</b></td>
              <!--<td><b>No.SO</b></td>-->
              <td><b>Qty</b></td>
              <td><b>Satuan</b></td>
              <td><b>Harga Satuan</b></td>
              <td align="right"><b>Jumlah</b></td>
            </tr>
            <?php
            foreach($products as $p){
            ?>
            <tr>
              <td><?php echo $p['name']; ?></td>
              <!--<td>
                <b>
                  <?php echo $so['no_so'];?>
                </b>
              </td>-->
              <td><?php echo $p['quantity']; ?></td>
              <td><?php echo $p['namasatuan']; ?></td>
              <td><?php echo $this->currency->format($p['price']); ?></td>

              <td align="right"><?php echo $this->currency->format($p['price'] * $p['quantity']); ?></td>
            </tr>
            <?php
            }
            ?>
            <?php for($i=0;$i<=4;$i++){ ?>
            	<tr>
            		<td colspan="5">&nbsp;<br></td>
            	</tr>
            <?php } ?>
            </tbody>
            <tfoot>
              <tr style="border-top:1px solid #e6e6e6;">
                <td class="text-right" colspan="4">Harga Jual/Penggantian/Uang Muka</td>
                <td align="right"><?php echo $this->currency->format($order['sub_total']); ?></td>
              </tr>
              <tr >
                <td class="text-right" colspan="4">Potongan Harga</td>
                <td align="right"><?php echo $this->currency->format($order['diskon']); ?></td>
              </tr>

              <tr >
                <td class="text-right" colspan="4">Dasar Pengenaan Pajak</td>
                <td align="right"><?php echo $this->currency->format($order['sub_total'] - $order['diskon']); ?></td>
              </tr>
              <tr >
                <td class="text-right" colspan="4">PPN 10%</td>
                <td align="right"><?php echo $this->currency->format($order['pajak']); ?></td>
              </tr>
              <tr >
                <td class="text-right" colspan="4">Uang Muka yang Telah Diterima</td>
                <td align="right"><?php echo $this->currency->format($order['dp']); ?></td>
              </tr>
              <?php
              if($order['jenisinvoice'] == 1 | $order['jenisinvoice'] == 3){
              ?>
              <tr>
                <td class="text-right" colspan="4">Total</td>
                <td align="right"><?php echo $this->currency->format($order['total']); ?></td>
              </tr>
              <tr style="border-bottom:1px solid #e6e6e6">
                <td class="text-right" colspan="4">Jumlah yang Harus Dibayar</td>
                <td align="right"><?php echo $this->currency->format($order['total']); ?></td>
              </tr>
              <?php
              }
              ?>
              <?php
              if($order['jenisinvoice'] == 2){
              ?>
              <tr >
                <td class="text-right" colspan="4">Total Tagihan</td>
                <td align="right"><?php echo $this->currency->format($order['total']); ?></td>
              </tr>
              <tr >
                <td class="text-right" colspan="4">Uang Muka yang Harus Dibayar</td>
                <td align="right"><?php echo $this->currency->format($order['totaltagihan']); ?>sa</td>
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
        <div class="col-xs-8">

          <p class="text-muted well well-sm no-shadow" style="margin-top: 10px;">
            Keterangan <br>
            <!-- <b>Metode Pembayaran: <?php echo $order['metode_pembayaran'] == 1?'Tunai':($order['metode_pembayaran'] == 2?'COD':'Kredit'); ?></b><br>
            Referensi: <?php echo $order['ref']; ?><br> -->

            Pembayaran Harus Melalui Transfer Bank:<br>
            A.N. PT.NISSON INDONESIA<br>
            <b>BCA.KCP.BATU CEPER AC. 594.016.3333</b><br>
            <b>BANK MANDIRI. KCP.TANGERANG AHMAD YANI AC. 155.000.761.8237</b>
            <?php
            foreach($banks as $b){
            //echo $b['name'].' Cabang '.$b['cabang'].' Kota '.$b['kota'].' No. Rek '.$b['rekening'].'<br>';
            }
            ?>
            <br>
            Proforma Invoice ini Bukan Merupakan Bukti Pembayaran
          </p>
        </div>
        <div class="col-xs-4 text-right print-only">
        	<br>
          Hormat Kami
          <br><br><br><br><br>
          (_______________)
        </div>
      </div>


      <p></p>

      <!-- this row will not appear when printing -->
      <?php
      if($order['cetak'] < 1){
      ?>
      <div class="row no-print">
        <div class="col-xs-12">
            <a onclick='cetakjkt(<?php echo json_encode($fulldetail); ?>)' class="btn btn-default"><i class="fa fa-print"></i> Cetak Tangerang</a>
            <a onclick='cetaksby(<?php echo json_encode($fulldetail); ?>)' class="btn btn-default"><i class="fa fa-print"></i> Cetak Surabaya</a>

        </div>
      </div>
      <?php
      }else{
        ?>
        <div class="row no-print">
          <div class="col-xs-6 pull-right">
            <div class="callout callout-danger lead">
            Invoice telah dicetak. Hubungi Top Administrator untuk mencetak ulang.

            </div>
          </div>
        </div>
        <?php
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

function cetak(){
  window.print();
}
function cetakjkt(detail){
  totalcetak=<?php echo $order['totalcetak']; ?>;
  if(totalcetak > 0){
    alert("Proforma Invoice telah dicetak. Hubungi pimpinan untuk mencetak ulang");
  }else{
    $.ajax({
      url: '<?php echo PRINTER_JKT; ?>printinvoice',
      dataType: 'json',
      method:'POST',
      data:JSON.stringify(detail),
      success: function(json) {
        $.ajax({
          url: 'index.php?route=sale/proforma/logcetak&token=<?php echo $this->request->get['token']; ?>&id=<?php echo $order['id']; ?>',
          dataType: 'json',
          success: function(json) {
            alert("Invoice berhasil dicetak.");
            location.reload();
          }
        })


      }
    });
  }
}

function cetaksby(detail){
  totalcetak=<?php echo $order['totalcetak']; ?>;
  if(totalcetak > 0){
    alert("Proforma Invoice telah dicetak. Hubungi pimpinan untuk mencetak ulang");
  }else{
    $.ajax({
      url: '<?php echo PRINTER_SBY; ?>printinvoice',
      dataType: 'json',
      method:'POST',
      data:JSON.stringify(detail),
      success: function(json) {
        $.ajax({
          url: 'index.php?route=sale/proforma/logcetak&token=<?php echo $this->request->get['token']; ?>&id=<?php echo $order['id']; ?>',
          dataType: 'json',
          success: function(json) {
            alert("Invoice berhasil dicetak.");
            location.reload();
          }
        })

      }
    });
  }
}
</script>

<?php echo $footer; ?>
