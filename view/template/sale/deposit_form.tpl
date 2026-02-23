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
            <h3 class="box-title">Deposit Customer</h3>
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
                        <td><span class="required">*</span>Tanggal Deposit</td>
                        <td><input type="text" name="date_trans"  class="form-control date" value="" readonly></td>
                    </tr>
                    <tr>
                        <td><span class="required">*</span>Bank/Kas</td>
                        <td ><select name="bank_id" class="form-control bank">

                    		</select>
                        </td>
                      </tr>

                     <tr>
                         <td>Jumlah</td>
                         <td><input type="text" name="nominal" onkeypress='return event.charCode >= 48 && event.charCode <= 57' class="form-control" value="<?php echo $nominal; ?>" ></td>
                     </tr>
                     <tr>
                         <td>Keterangan</td>
                         <td><input type="text" name="keterangan"  class="form-control" value="" ></td>
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
$('.sidebar-menu').find('#menu-customer').addClass('active');
$('.sidebar-menu').find('#menu-customer-list').addClass('active');
function simpan(){
  $(".error").remove();
  error=false;

  em='';
  
  
  tanggal=$("input[name='date_trans']").val();
  if(tanggal < '<?php echo $locktanggal;?>'){
    error=true;
    em+="Tanggal tidak   boleh kurang dari <?php echo $locktanggal;?> <br>";
  }
  if(error){

    html='<div class="alert alert-danger alert-dismissible">';
    html +='<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>';
    html +='<h4><i class="icon fa fa-ban"></i> Alert!</h4>';
    html +=em;
    html +='</div>';

    $('.errordisplay').html(html);;
  }else{
    $('#form').submit();
  }
}
</script>

<script type="text/javascript"><!--
$(document).ready(function() {
$('.date').datepicker({dateFormat: 'yy-mm-dd',minDate:'<?php echo $locktanggal; ?>',maxDate:new Date()});


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
