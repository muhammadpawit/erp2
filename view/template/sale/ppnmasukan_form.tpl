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
            <h3 class="box-title">Input No. Faktur Pajak</h3>
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

                  </table>
                  <table class="table" id="list-product" >
                    <thead>
                      <tr>
                        <th class="left" style="width:300px">Invoice</th>
                        <th class="left">No. Faktur Pajak</th>

                          <td></td>

                      </tr>
                    </thead>
                    <tbody>

                    </tbody>

                  <tfoot>

                    <tr>
                      <td colspan="3"></td>
                      <td class="left"><a onclick="addModule();" class="btn btn-success">Tambah Invoice</a>  </td>
                    </tr>

                  </tfoot>
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
$('.sidebar-menu').find('#menu-pajak').addClass('active');

$('.sidebar-menu').find('#menu-faktur-pajak').addClass('active');
//$('.sidebar-menu').find('#menu-penjualan-website').addClass('active');
//$('.sidebar-menu').find('#menu-penjualan-detailorder').addClass('active');

</script>

<script type="text/javascript"><!--
$(document).ready(function() {
	$('.date').datepicker({dateFormat: 'yy-mm-dd'});


});
//--></script>
<script type="text/javascript" src="view/javascript/jquery/ui/jquery-ui-timepicker-addon.js"></script>
<script type="text/javascript"><!--
    var product_row = 0;

function addModule() {
	html = '  <tr id="product-row' + product_row + '">';
  html += '    <td class="left" style="width:300px"><select name="orders['+product_row+'][invoice_id]" class="order form-control"></select></td>';
	html += '    <td class="right"><input class="form-control" type="text" name="orders[' + product_row + '][no_fakturpajak]" /></td>';

  html += '    <td class="right"><a class="btn btn-warning" onclick="$(\'#product-row'+product_row+'\').remove()" class="button">Hapus</a> <span></span></td>';

	html += '  </tr>';


	$('#list-product tbody').append(html);
  $(document).ready(function() {
    $(".order").select2({
      ajax: {
      url:"index.php?route=sale/invoice/autocompletepajak&token=<?php echo $this->request->get['token']; ?>",
        dataType: 'json',
      data: function (params) {
        return {
          q: params.term,
          p: 1 // search term

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

  $('.date').datepicker({dateFormat: 'yy-mm-dd'});
  })

	product_row++;
}


function simpan(){
  $(".error").remove();
  error=false;
  errdup=false;
  em='';

  cek = [];

  total=0;
  for(i=0;i<product_row;i++){

    pid=$("select[name='orders["+i+"][invoice_id]']").val();

    if(pid != undefined){
      total++;
  		if(cek[pid] == undefined){

  			cek[pid] = i;

  		}
  		else{
  			errdup = true;
  			error=true;
  			//alert(product_id+' '+p);
  		}
      if(pid==""){
        error=true;
        em+="Order ID tidak boleh kosong<br>";
      }

		}

  }

  if(total < 1){
    error=true;
    em+="Data Faktur Pajak tidak boleh kosong<br>";
  }

  if(error){
    if(errdup){
      em+= "Terdapat duplikasi data produk.<br>";
    }
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
