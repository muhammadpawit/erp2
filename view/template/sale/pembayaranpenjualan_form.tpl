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
            <h3 class="box-title">Pembayaran Penjualan Tunai</h3>
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
                        <td>Tangal Bayar</td>
                        <td><input type="text" name="date_added" class="date form-control" value="<?php echo date('Y-m-d'); ?>" readonly ></td>
                    </tr>
                    <tr>
                       <td><span class="required">*</span>No. Invoice</td>
                       <td ><select name="no_po" class="form-control nosurat">

                   		</select>

                       </td>
                     </tr>
                     <tr>
                        <td><span class="required">*</span>Bank/Kas</td>
                        <td ><select name="bank_id" class="form-control bank">

                    		</select>
                        </td>
                      </tr>

                     <tr>
                         <td>Jumlah</td>
                         <td><input type="text" onblur="updatetotal()" id=jumlah name="jumlah" class="form-control" value="<?php echo $jumlah; ?>" ></td>
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
$('.sidebar-menu').find('#menu-pembelian').addClass('active');
$('.sidebar-menu').find('#menu-pembelian-kredit').addClass('active');
function updatetotal(){
  jumlah=$("#jumlah").val();

  pajak=0.1*Number(jumlah);
  total=Number(jumlah) + Number(pajak);

  $("#pajak").val(pajak);
  $("#total").val(total);
}
function simpan(){
  //updatetotal();
  $('#form').submit();
}
</script>

<script type="text/javascript"><!--
$(document).ready(function() {
	$('.date').datepicker({dateFormat: 'yy-mm-dd'});
  $(".nosurat").select2({
    ajax: {
    url:"index.php?route=sale/invoice/autocomplete&token=<?php echo $this->request->get['token']; ?>",
    dataType: 'json',
    data: function (params) {
      return {
        q: params.term,
        p:1


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
})
$(".bank").select2({
  ajax: {
  url:"index.php?route=keuangan/bank/autocomplete&token=<?php echo $this->request->get['token']; ?>",
  dataType: 'json',
  data: function (params) {
    return {
      q: params.term,
      c:1

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
})
});
//--></script>

<?php echo $footer; ?>
