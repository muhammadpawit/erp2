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
            <h3 class="box-title">Permohonan Penyesuaian Deposit</h3>
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
                  <?php echo $error_warning; ?>
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
                        <td>Tanggal Permohonan</td>
                        <td>
                          <input type="text" class="date form-control" name="tanggal" value="<?php echo date('Y-m-d'); ?>" readonly>
                        </td>
                    </tr>
                    <tr>
                       <td><span class="required">*</span>Nama Customer</td>
                       <td><select style="width:100%;" name="customer_id" class="form-control lokasi-pameran">

                        </select>
                       </td>
                     </tr>

                     <tr>
                         <td>Keterangan</td>
                         <td><input type="text" name="keterangan" class="form-control" value="" ></td>
                     </tr>
                     <tr>
                         <td>Saldo Tersimpan</td>
                         <td><input type="text" name="nominal_tersimpan" readonly class="form-control" value="" ></td>
                     </tr>
                     <tr>
                         <td>Saldo Tersedia</td>
                         <td><input type="text" name="nominal_tersedia" class="form-control" value="" ></td>
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

<script type="text/javascript"><!--
$(document).ready(function() {
	$('.date').datepicker({dateFormat: 'yy-mm-dd',minDate:'<?php echo $locktanggal; ?>',maxDate:new Date()});
 

});
//--></script>

<script type="text/javascript" src="view/javascript/jquery/ui/jquery-ui-timepicker-addon.js"></script>
<script type="text/javascript"><!--
$(function(){
    $(".lokasi-pameran").select2({
    ajax: {
    url:"index.php?route=sale/customer/autocomplete&token=<?php echo $token; ?>",
    //url: "index.php?route=pamerantoko/toko/autocomplete&token=<?php echo $this->request->get['token']; ?>",
    dataType: 'json',
    data: function (params) {
      return {
        q: params.term, // search term

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
  }).on("select2:select",function(e){
    //console.log(e);
    //console.log($(this).val());
    id=$(this).val();
    //alert(id);
    if(id != undefined & id != null){
    $.ajax({
      url: 'index.php?route=sale/customer/detail&token=<?php echo $this->request->get['token']; ?>&customer_id=' + id,
      dataType: 'json',
      success: function(json) {
        //alert(JSON.stringify(json));
          //$("#deposit").after("");
          console.log(json);
        $("input[name='nominal_tersimpan']").val(json.deposit);
        
      }
    })
  }


  });
})
$('.datetime').datetimepicker({
	dateFormat: 'yy-mm-dd',
	timeFormat: 'h:m'
});
$('.time').timepicker({timeFormat: 'h:m'});
//--></script>
<script type="text/javascript"><!--
$('.vtabs a').tabs();
//--></script>
<script>
function simpan(){
  $(".error").remove();
  error=false;
  errdup=false;
  errqty=false;
  em='';

  cek = [];
  customer_id=$("select[name='customer_id']").val();
  
  if(customer_id < 1){
    error=true;
    em +="Customer harus dipilih untuk penyesuaian saldo deposit<br>";
  }
  tanggal=$("input[name='tanggal']").val();
  if(tanggal < '<?php echo $locktanggal;?>'){
    error=true;
    em+="Tanggal tidak   boleh kurang dari <?php echo $locktanggal;?> <br>";
  }

  tersimpan=$("input[name='nominal_tersimpan']").val();
  tersedia=$("input[name='nominal_tersedia']").val();
  if(tersimpan == ''){
      error=true;
      em +="Nominal tersimpan harus berupa angka.<br>";
  }
  if(tersedia == ''){
      error=true;
      em +="Nominal tersedia harus berupa angka.<br>";
  }
  if(!(/^\d*$/.test(tersimpan))){
      error=true;
      em +="Nominal tersimpan harus berupa angka.<br>";
  }
  
  if(!(/^\d*$/.test(tersedia))){
      error=true;
      em +="Nominal tersedia harus berupa angka.<br>";
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
<?php echo $footer; ?>
