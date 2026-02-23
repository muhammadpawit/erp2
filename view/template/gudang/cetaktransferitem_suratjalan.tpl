<?php //echo $header; ?>
<!-- <body> -->
<body onload="window.print()">
<style>
        body {
        width: 100%;
        height: 100%;
        margin: 0;
        padding: 0;
        background-color: #FAFAFA;
        font: 12pt "Tahoma";
    }
    * {
        box-sizing: border-box;
        -moz-box-sizing: border-box;
    }
    .page {
        width: 210mm;
        /*min-height: 297mm;*/
        /*padding: 20mm;*/
       /* margin: 10mm auto;*/
        border: 1px #D3D3D3 solid;
        border-radius: 5px;
        background: white;
       /* box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);*/
    }
    .subpage {
        padding: 0.9cm;
       /* border: 5px red solid;*/
        /*height: 160mm;*/
        height: auto;
        /*outline: 2cm #FFEAEA solid;*/
    }
    
    @page {
        size: A4;
        margin: 0;
    }
    @media print {
        html, body {
            width: 210mm;
            height: 197mm;        
        }
        .page {
            margin: 0;
            border: initial;
            border-radius: initial;
            width: initial;
            min-height: initial;
            box-shadow: initial;
            background: initial;
            /*page-break-after: always;*/
        }
    }
  .table {
    border:0px !important;
    border-collapse: collapse;
    width: 100%;    
  }
  </style>    
<div class="book">
    <div class="page">
        <div class="subpage">
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
            <h3 class="box-title">PT.NISSON INDONESIA <br><br>Transfer Gudang</h3>
          </div>
          <div class="box-body">

            <div class="row">
              <div class="col-md-12">
                <table class="table" cellpadding="8">
                  <tr>
                      <td>Nomor Surat Jalan</td>
                      <td><?php echo $transfer['detail']['invoice_no'] ?></td>
                  </tr>
                  <tr>
                    <td>Nomor PO</td>
                    <td><?php echo empty($transfer['detail']['no_po'])?'-':$transfer['detail']['no_po'];?></td>
                  </tr>
                  <tr>
                      <td>Tanggal</td>
                      <td><?php echo date('d F Y',strtotime($transfer['detail']['date_added'])) ?></td>
                  </tr>
                   <tr>
                      <td>Gudang Asal</td>
                      <td><?php echo $transfer['detail']['asal'] ?></td>
                  </tr>
                   <tr>
                      <td>Gudang Tujuan</td>
                      <td><?php echo $transfer['detail']['gudang_tujuan'] ?></td>
                  </tr>
                  <tr>
                      <td>Tanggal Terima</td>
                      <td><?php echo ($transfer['detail']['tglterima']==null)?'belum diterima':date('d F Y',strtotime($transfer['detail']['tglterima'])) ?></td>
                  </tr>
                  <tr>
                      <td>Total Pengiriman Barang</td>
                      <td>(<?php echo $transfer['detail']['qtykirim']; ?> pcs)</td>
                  </tr>
                  <tr>
                     <td>Total Diterima</td>
                     <td>(<?php echo $transfer['detail']['qtyterima']; ?> pcs)</td>
                  </tr>
                  <tr>
                      <td>Status</td>
                      <td><?php echo ($transfer['detail']['status'] == 0)?'Barang Belum diterima':($transfer['detail']['status'] == 1?'Barang telah diterima':($transfer['detail']['status'] == 2?'Terdapat Selisih':'Transfer dibatalkan')); ?></td>
                  </tr>
                  <tr>
                    <td>No SJ Supplier</td>
                    <td><?php echo $transfer['detail']['keterangan']; ?></td>
                  </tr>
                  <tr>
                    <td>Alamat Expedisi</td>
                    <td><?php echo $transfer['detail']['alamatexpedisi']?></td>
                  </tr>
                  <tr>
                    <td>No.Polisi</td>
                    <td><?php echo $transfer['detail']['nopol']?></td>
                  </tr>
                  <?php //if(!empty($transfer['detail']['no_po'])) {?>
                  
                  <?php //} ?>

                </table><hr style="border:0.5px">
                <table class="table" cellpadding="5">
                    <tr>
                      <th align="left">Nama Produk</th>
                      <th align="left">Quantity Kirim</th>
                      <th align="left">Quantity Terima</th>
                    </tr>
                    <tbody>
                      <?php
                      foreach($transfer['products'] as $p){
                      ?>
                      <tr>
                        <td><?php echo $p['name']; ?></td>
                        <td><?php echo $p['quantity']; ?></td>
                        <td><?php echo $p['quantity_actual']; ?></td>
                      </tr>
                      <?php
                      }
                      ?>
                    </tbody>
                </table>
                <br>
                <br><br>
                <table class="table">
                  <tr>
                    <td align="center">Diterima oleh </td>
                    <td align="center">Satpam </td>
                    <td align="center">Gudang</td>
                    <td align="center">Distribusi</td>
                  </tr>
                  
                  <?php for($i=1;$i<=4;$i++){ ?>
                  <tr>
                    <td><br></td>
                  </tr>
                  <?php } ?>
                  <tr>
                    <td align="center">(_______________)</td>
                    <td align="center">(_______________)</td>
                    <td align="center">(_______________)</td>
                    <td align="center">(_______________)</td>
                  </tr>
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
        </div>    
    </div>
</div>
</body>
<script>
$('.sidebar-menu').find('#menu-persediaan').addClass('active');
$('.sidebar-menu').find('#menu-persediaan-produkdagang').addClass('active');
$('.sidebar-menu').find('#menu-transfer-item').addClass('active');

function cetakjakarta(detail){
  $.ajax({
    url: '<?php echo PRINTER_JKT; ?>cetaktransfer',
    dataType: 'json',
    method:'POST',
    data:JSON.stringify(detail),
    success: function(json) {
      alert("Surat jalan berhasil dicetak.");
      /*response($.map(json, function(item) {
        return {
          label: item.nama,
          value: item.atk_id
        }
      }));*/

    }
  });
}
function cetaksurabaya(detail){
  $.ajax({
    url: '<?php echo PRINTER_SBY; ?>cetaktransfer',
    dataType: 'json',
    method:'POST',
    success: function(json) {
      alert("Surat jalan berhasil dicetak.");
      /*response($.map(json, function(item) {
        return {
          label: item.nama,
          value: item.atk_id
        }
      }));*/

    }
  });
}

</script>

</body>
<?php // echo $footer; ?>
