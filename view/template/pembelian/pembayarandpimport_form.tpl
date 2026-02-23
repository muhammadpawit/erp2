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
            <h3 class="box-title">Pembayaran Pembelian Import</h3>
            <div class="button pull-right">
              <a onclick="$('#form').submit()" ><button type="button" class="btn btn-primary">Simpan</button></a>
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
                       <td><span class="required">*</span>No. Purchased Order</td>
                       <td colspan="2"><select name="no_po" class="form-control nosurat">

                   		</select>

                       </td>
                     </tr>
                     <tr>
                        <td><span class="required">*</span>Bank/Kas</td>
                        <td colspan="2"><select name="bank_id" class="form-control bank">

                    		</select>
                        </td>
                      </tr>

                     <tr>
                         <td>Jumlah<br><small>(dalam USD)</small></td>
                         <td colspan="2"><input type="text" name="jumlah" class="form-control" value="<?php echo $jumlah; ?>" ></td>
                     </tr>

                     <tr>
                         <td>Kurs Bank</td>
                         <td colspan="2"><input type="text" name="kursbank" class="form-control" value="" ></td>
                     </tr>

                     <tr>
                         <td>Biaya Bank</td>
                         <td>
                           <select name="currency" class="form-control">
                             <option value="1">Rp</option>
                             <option value="2">$</option>
                           </select>
                         </td>
                         <td>

                           <input type="text" name="biaya_bank" class="form-control" value="0" ></td>

                     </tr>
                     <tr style="display:none">
                         <td>Kurs Tengah BI</td>
                         <td><input type="text" name="kursbi" class="form-control" value="0" ></td>
                     </tr>
                     <tr style="display:none">
                         <td>Kurs KMK</td>
                         <td><input type="text" name="kurskmk" class="form-control" value="0" ></td>
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
$('.sidebar-menu').find('#menu-pembelian-import').addClass('active');

</script>

<script type="text/javascript"><!--
$(document).ready(function() {
	$('.date').datepicker({dateFormat: 'yy-mm-dd'});
  $(".nosurat").select2({
    ajax: {
    url:"index.php?route=pembelian/pembelianimport/autocomplete&token=<?php echo $this->request->get['token']; ?>",
    dataType: 'json',
    data: function (params) {
      return {
        q: params.term,
        j: 2// search term
        f:1

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
      c:2

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
