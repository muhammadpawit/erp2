<?php //echo $header; ?>

?>
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
            <h3 class="box-title">Stok Gudang</h3>
            <div class="button pull-right">
						</div>
          </div>
          <div class="box-body">
            <div class="row">
              <div class="col-md-12">
                <?php
                if(isset($permission)){
              		if(!$permission){?>
                <div class="alert alert-danger alert-dismissible">
                  <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                  <h4><i class="icon fa fa-ban"></i> Alert!</h4>
                  Anda tidak diijinkan mengakses gudang yang dipilih.
                </div>
                <?php
              }}
                ?>
                <?php if ($success) { ?>
                <div class="alert alert-success alert-dismissible">
                  <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                  <h4><i class="icon fa fa-check"></i> Success!</h4>
                  <?php echo $success; ?>
                </div>
                <?php
                }
                ?>
              </div>
            </div>
            <div class="row">
              <div class="col-md-12">
                <table class="table table-bordered">
                    <thead>
                      <tr>
                        <th class="left">Gudang</th>
                        <th class="left">Nama Produk</th>
          				      <th class="right">Quantity</th>
                      </tr>
                    </thead>
                    <tbody>

                      <?php if ($products) { ?>
                      <?php foreach ($products as $product) { ?>
                      <tr>
                        <td class="left"><?php echo $product['nama'];
                          ?></td>
                        <td class="left"><?php echo $product['name']; ?></td>
          			            <td class="right"><?php if ($product['quantity'] <= 0) { ?>
                          <span style="color: #FF0000;"><?php echo $product['quantity']; ?></span>
                          <?php } elseif ($product['quantity'] <= 5) { ?>
                          <span style="color: #FFA500;"><?php echo $product['quantity']; ?></span>
                          <?php } else { ?>
                          <span style="color: #008000;"><?php echo $product['quantity']; ?></span>
                          <?php } ?></td>
                      </tr>
                      <?php } ?>
                      <?php } else { ?>
                      <tr>
                        <td class="center" colspan="9">Data tidak ditemukan</td>
                      </tr>
                      <?php } ?>
                    </tbody>
                  </table>

              </div>
            </div>

          </div>
          <div class="box-footer">
            <div class="row">
              <div class="col-md-12">
                <div class="pull-right"><?php //echo $pagination; ?></div>
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
$('.sidebar-menu').find('#menu-persediaan').addClass('active');
$('.sidebar-menu').find('#menu-persediaan-produkdagang').addClass('active');
$('.sidebar-menu').find('#menu-produk-gudang').addClass('active');
$(function(){
  $(".select-ads").select2({


      theme:"bootstrap"
    });
})
</script>
<script type="text/javascript"><!--
function filter() {
	url = 'index.php?route=catalog/productgudang&token=<?php echo $token; ?>';



	var filter_name = $('input[name=\'filter_product_name\']').val();

	if (filter_name) {
		url += '&filter_name=' + encodeURIComponent(filter_name);
	}

var filter_gudang_id = $('select[name=\'filter_gudang_id\']').val();

	if (filter_gudang_id !="*") {
		url += '&filter_gudang_id=' + filter_gudang_id;
	}

	var filter_category_id = $('select[name=\'filter_category_id\']').val();

	if (filter_category_id !="*") {
		url += '&filter_category_id=' + filter_category_id;
	}

    var filter_qty = $('select[name=\'filter_qty\']').val();

	if (filter_qty != '*') {
		url += '&filter_qty=' + filter_qty;
	}

  var filter_urutkan = $('select[name=\'filter_urutkan\']').val();

  if (filter_urutkan != '*') {
  url += '&filter_urutkan=' + filter_urutkan;
  }


	location = url;
}
//--></script>
<script type="text/javascript"><!--
$('input[name=\'filter_product_name\']').autocomplete({
	delay: 0,
	source: function(request, response) {
		$.ajax({
			url: 'index.php?route=catalog/product/autocomplete&token=<?php echo $token; ?>&filter_name=' + encodeURIComponent(request.term),
			dataType: 'json',
			success: function(json) {
				response($.map(json, function(item) {
					return {
						label: item.name,
						value: item.product_id,

					}
				}));
			}
		});
	},
	select: function(event, ui) {
		$('input[name=\'filter_product_name\']').val(ui.item['label']);

		//$('input[name=\'filter_product_id\']').attr('value', ui.item['value']);



		return false;
	},
	focus: function(event, ui) {
      	return false;
   	}
});
//--></script>
<?php //echo $footer; ?>
