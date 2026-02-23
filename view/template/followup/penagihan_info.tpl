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
            <h3 class="box-title">Info detail follow up customer</h3>
            <div class="button pull-right">
              <a href="<?php echo $cancel; ?>"><button type="button" class="btn btn-danger">Kembali</button></a>

            </div>
          </div>
          <div class="box-body">

            <div class="row">
              <div class="col-md-12">
                <table class="table">
                  <tr>
                      <td>Tanggal Follow Up:</td>
                      <td><?php echo $product['tanggal']; ?></td>
                  </tr>
                  <tr>
                      <td>Nama Customer:</td>
                      <td><?php echo $namacustomer; ?></td>
                  </tr>
                  <tr>
                      <td>Media Follow Up:</td>
                      <td>
                        <?php
                          $color='';
                          if($product['media'] == 1){
                            echo '<i class="fa fa-whatsapp"></i>&nbsp;Whatsapp';
                            $color='blue';
                          }
                          if($product['media'] == 2){
                            echo '<i class="fa fa-phone"></i>&nbsp;Telephone';
                            $color='gray';
                          }
                          if($product['media'] == 3){
                            echo '<i class="fa fa-envelope"></i>&nbsp;E-mail';
                            $color='green';
                          }
                          if($product['media'] == 4){
                            echo '<i class="fa fa-user"></i>&nbsp;Sales';
                            $color='yellow';
                          }
                          if($product['media'] == 5){
                            echo '<i class="fa fa-envelope"></i>&nbsp;Surat';
                            $color='purple';
                          }
                          if($product['media'] == 6){
                            echo '<i class="fa fa-user"></i>&nbsp;Kurir';
                            $color='purple';
                          }
                        ?>
                      </td>
                  </tr>
                  
                  <tr>
                      <td>Keterangan:</td>
                      <td><div class="alert bg-<?php echo $color ?>"><?php echo $product['hasil_pembicaraan']; ?></div></td>
                  </tr>

                  <tr>
                      <td>Di Follow Up Oleh:</td>
                      <td><?php echo $user ?> pada <?php echo date('d/m/Y H:i:s',strtotime($product['tanggal']))?> wib.</td>
                  </tr>

                </table>


              </span>
            </span>

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

function cetakjakarta(detail){
  $.ajax({
    url: '<?php echo PRINTER_JKT; ?>cetakpenerimaandana',
    dataType: 'json',
    method:'POST',
    data:JSON.stringify(detail),
    success: function(json) {
      alert("Penerimaan dana berhasil dicetak.");
      /*response($.map(json, function(item) {
        return {
          label: item.nama,
          value: item.atk_id
        }
      }));*/

    }
  });
}
function cetaksby(detail){
  $.ajax({
    url: '<?php echo PRINTER_SBY; ?>cetakpenerimaandana',
    dataType: 'json',
    method:'POST',
    success: function(json) {
      alert("Penerimaan Dana berhasil dicetak.");
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

<?php echo $footer; ?>
