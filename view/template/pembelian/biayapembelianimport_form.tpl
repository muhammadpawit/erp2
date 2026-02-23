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
            <h3 class="box-title">Biaya Pembelian Import</h3>
            <div class="button pull-right">
              <a onclick="simpan()" ><button type="button" class="btn btn-primary">Proses</button></a>
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

                <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
                  <div class="row">
                    <div class="col-sm-12 col-xs-12">
                  <table class="table table-responsive">
                    <tr>
                        <td>Tanggal Faktur</td>
                        <td>
                          <input type="text" class="date form-control" name="tglfaktur" value="<?php echo date('Y-m-d'); ?>" readonly>
                        </td>
                    </tr>
                    <tr>
                      <td>Jatuh Tempo</td>
                      <td>
                        <input type="text" class="date form-control" name="jatuhtempo" value="<?php echo date('Y-m-d'); ?>" readonly>

                      </td>
                    </tr>
                    <tr>
                       <td><span class="required">*</span>Vendor</td>
                       <td ><select name="vendor_id" class="form-control vendor">

                       </select>
                       </td>
                     </tr>

                    <tr>
                       <td>Referensi Invoice Pembelian</td>
                       <td ><select name="order_id" class="form-control invoice">

                       </select>
                       </td>
                    </tr>
                    <tr>
                       <td>Nama Biaya</td>
                       <td ><select name="jenisbiaya_id" class="form-control biaya">

                       </select>
                       </td>
                    </tr>
                    <tr>
                        <td>Nomor Faktur</td>
                        <td>
                          <input type="text" class="form-control" name="no_faktur" value="" >
                        </td>
                    </tr>

                    <tr>
                        <td>Jumlah</td>
                        <td colspan="2"><input type="text" name="total" class="form-control" value="0" ></td>
                    </tr>

                    <tr>
                      <td>Hutang Pajak</td>
                      <td colspan="2"><select name="pajak" class="form-control">
                        <option value="0">Tanpa Pajak</option>
                        <option value="2501">PPh 21</option>
                        <option value="2502">PPh 23</option>
                        <option value="2503">PPh 25</option>
                        <option value="2504">PPh 29</option>
                        <option value="2505">PPN Keluaran</option>
                        <option value="2506">PPh Final</option>
                      </select></td>
                    </tr>
                    <tr>
                      <td>Status Pajak</td>
                      <td colspan="2"><select name="statuspajak" class="form-control">
                        <option value="0">Tanpa Pajak</option>
                        <option value="1">Potong Total</option>
                        <option value="2">Tidak Potong Total</option>

                      </select></td>
                    </tr>
                    <tr>
                        <td>Total Pajak</td>
                        <td colspan="2"><input type="text" name="nilaipajak" class="form-control" value="<?php echo $nilaipajak; ?>" ></td>
                    </tr>

                    <tr>
                      <td>Pajak Dibayar Dimuka</td>
                      <td colspan="2"><select name="pajakdimuka" class="form-control">
                        <option value="0">Tanpa Pajak</option>
                        <option value="1551">PPh 21</option>
                        <option value="1552">PPh 22</option>
                        <option value="1553">PPh 23</option>
                        <option value="1554">PPh 25</option>
                        <option value="1555">PPN Masukan</option>

                      </select></td>
                    </tr>
                    <tr>
                      <td>Status Pajak Dibayar Dimuka</td>
                      <td colspan="2"><select name="statuspajakdimuka" class="form-control">
                        <option value="0">Tanpa Pajak</option>

                        <option value="2">Belum Termasuk Total</option>
                        <option value="1">Termasuk Total</option>

                      </select></td>
                    </tr>
                    <tr>
                        <td>Total Pajak Dibayar Dimuka</td>
                        <td colspan="2"><input type="text" name="ppn" class="form-control" value="<?php echo $ppn; ?>" ></td>
                    </tr>
                  </table>
                </div>

                </div>

                </form>



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
$('.sidebar-menu').find('#menu-biaya-pembelian-import').addClass('active');

</script>
<script type="text/javascript"><!--
//var error=false;
$(function(){
  $(".vendor").select2({
    ajax: {
    url:"index.php?route=catalog/vendorlokal/autocomplete&token=<?php echo $this->request->get['token']; ?>",
    dataType: 'json',
    data: function (params) {
      return {
        filter_name: params.term
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
  $(".biaya").select2({
      ajax: {
      url:"index.php?route=catalog/jenisbiaya/autocomplete&token=<?php echo $this->request->get['token']; ?>",
        dataType: 'json',
      data: function (params) {
        return {
          q: params.term,
        //  statustabung:$(".statustabung").val(),
        //  kategori:$(".jenisorder").val()
          //customer_group_id:$('input[name=\'customer_group_id\']').val()

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
        url: 'index.php?route=pembelian/invoicepembelianimport/detailbiaya&token=<?php echo $this->request->get['token']; ?>&invoice_id=' + invoice+'&id='+id,
        dataType: 'json',
        success: function(json) {
          if(json){

            console.log(JSON.stringify(json));
            //alert(json.statuspembayaran);
            $("input[name='total']").val(json.total);
            if(json.statuspembayaran != 0  | json.statuspembayaran == 'null'){
              if(json.statuspembayaran != 4 & json.statuspembayaran != undefined){
                $(".error").remove();
                html='<div class="alert alert-danger alert-dismissible">';
                html +='<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>';
                html +='<h4><i class="icon fa fa-ban"></i> Alert!</h4>';
                html +='Faktur jenis biaya yang dipilih telah dibuat. ';
                html +='</div>';

                $('.errordisplay').html(html);
              }
            }
          }
        }
      })
    }


    })

    $(".invoice").select2({
      ajax: {
      url:"index.php?route=pembelian/invoicepembelianimport/autocomplete&token=<?php echo $this->request->get['token']; ?>",
      dataType: 'json',
      data: function (params) {
        return {
          q: params.term,
          //p:3,
          //f:$("select[name='vendor_id']").val()
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
})
</script>
<script type="text/javascript"><!--
$(document).ready(function() {
	$('.date').datepicker({dateFormat: 'yy-mm-dd'});


});
//--></script>
<script type="text/javascript" src="view/javascript/jquery/ui/jquery-ui-timepicker-addon.js"></script>
<script type="text/javascript"><!--

function simpan(){
  $(".error").remove();
  error=false;
  errdup=false;
  em='';

  if($("select[name='order_id']").val() == null){
    error=true;
    em+="Nomor Invoice pembelian Harus Dipilih<br>";
  }

  if($("select[name='jenisbiaya_id']").val() == null){
    error=true;
    em+="Nama Biaya Harus Dipilih<br>";
  }

  if($("input[name='no_faktur']").val() == ""){
    error=true;
    em+="Nomor Faktur harus diisi<br>";
  }

  if($("input[name='tglfaktur']").val() == ""){
    error=true;
    em+="Tanggal Faktur harus diisi<br>";
  }
  if($("input[name='jatuhtempo']").val() == ""){
    error=true;
    em+="Tanggal Jatuh Tempo harus diisi<br>";
  }

  if(!$.isNumeric( Number($("input[name='total']").val()) )){
    error=true;
    em+="Jumlah biaya harus berupa angka <br>";
  }else{
    if(Number($("input[name='total']").val()) <= 0){
      error=true;
      em+="Jumlah biaya harus lebih dari 0 <br>";
    }
  }

  if(!$.isNumeric( Number($("input[name='ppn']").val()) )){
    error=true;
    em+="Total pajak dibayar dimuka harus berupa angka <br>";
  }else{
    if(Number($("input[name='ppn']").val()) < 0){
      error=true;
      em+="Total pajak dibayar dimuka harus lebih dari sama dengan 0 <br>";
    }
  }

  if(!$.isNumeric( Number($("input[name='nilaipajak']").val()) )){
    error=true;
    em+="Total hutang pajak harus berupa angka <br>";
  }else{
    if(Number($("input[name='nilaipajak']").val()) < 0){
      error=true;
      em+="Total hutang pajak harus lebih dari sama dengan 0 <br>";
    }
  }


  if(error){
    /*if(errdup){
      em+= "Terdapat duplikasi data Surat Jalan/Sales Order.<br>";
    }*/
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


</script>

<script type="text/javascript"><!--
$('.date').datepicker({dateFormat: 'yy-mm-dd'});
$('.datetime').datetimepicker({
	dateFormat: 'yy-mm-dd',
	timeFormat: 'h:m'
});
$('.time').timepicker({timeFormat: 'h:m'});
//--></script>
<script type="text/javascript"><!--
$('.vtabs a').tabs();
//--></script>

<?php echo $footer; ?>
