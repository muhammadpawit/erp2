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
            <h3 class="box-title">Pembayaran Tagihan</h3>
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
                       <td><span class="required">*</span>Tanggal Bayar</td>
                       <td ><input type="text" name="tgl_bayar" class="date form-control" value="<?php echo $tgl_bayar; ?>" readonly >
                       </td>
                     </tr>
                    <tr>
                       <td><span class="required">*</span>No. Faktur</td>
                       <td ><select name="order_id" class="form-control nosurat">

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
                          <td>Keterangan</td>
                          <td><input type="text" name="keterangan" class="form-control" value="" ></td>
                      </tr>
                     <tr>
                         <td>Jumlah</td>
                         <td><input type="text" name="nominal" class="form-control" value="0" ><input readonly type="hidden" name="totalbayar" class="form-control" value="0" ></td>
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
    url:"index.php?route=pembelian/biayapembelianimport/autocompletepembayaran&token=<?php echo $this->request->get['token']; ?>",
    dataType: 'json',
    data: function (params) {
      return {
        q: params.term,

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

      invoice=$(".invoice").val();
    $.ajax({
      url: 'index.php?route=pembelian/biayapembelianimport/detailbiaya&token=<?php echo $this->request->get['token']; ?>&id='+id,
      dataType: 'json',
      success: function(json) {
        if(json){

          console.log(JSON.stringify(json));
          sisatagihan=Number(json.totalreal)-Number(json.totalbayar);
          $("input[name='totalbayar']").val(sisatagihan);
          $("input[name='nominal']").val(sisatagihan);

        }
      }
    })
  }


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

function simpan(){
  $(".error").remove();
  error=false;
  jenistransaksi=$('#transaksi').val();

  em='';
  //alert($("input[name='date_trans']").val());
  if($("input[name='tgl_bayar']").val() == null |  $("input[name='tgl_bayar']").val() == ""){
    error=true;
    em +="Tanggal Bayar Harus Diisi <br>";
  }

  if($("select[name='bank_id']").val() == null | $("select[name='bank_id']").val() == undefined){
    error=true;
    em +="Bank/Kas Harus Dipilih <br>";
  }

  if($("select[name='order_id']").val() == null | $("select[name='order_id']").val() == undefined){
    error=true;
    em +="Referensi Invoice Harus Dipilih <br>";
  }

  if($("input[name='nominal']").val() == null | $("input[name='nominal']").val() == ""){
    error=true;
    em +="Nominal Harus Diisi <br>";
  }else{
    nominal=$("input[name='nominal']").val();
    if(nominal == 0){
      error=true;
      em +="Nominal Harus Lebih dari 0 <br>";
    }

    if(Number(nominal) > $("input[name='totalbayar']").val()){
      error=true;
      em +="Nominal melebihi sisa tagihan <br>";
    }
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
//--></script>

<?php echo $footer; ?>
