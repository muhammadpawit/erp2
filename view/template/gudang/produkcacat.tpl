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
            <h3 class="box-title">Produk Cacat</h3>
            <div class="button pull-right">
                <a href="<?php echo $insert; ?>" ><button type="button" class="btn btn-primary">Tambah</button></a>

								</div>
          </div>
          <div class="box-body">
						<div class="row">
              <div class="col-md-12">
                <table class="table">
                <tr>
                  <td>Tanggal Mulai
                    <input type="text" name="filter_date_start" value="<?php echo $filter_date_start; ?>" id="date-start" size="12" readonly /></td>
                  <td>Tanggal Selesai
                    <input type="text" name="filter_date_end" value="<?php echo $filter_date_end; ?>" id="date-end" size="12" readonly /></td>

                   <td>Gudang
                    	<select name="filter_gudang_id">
                    		<option value="">--Pilih Gudang--</option>
                    		<?php
                    		foreach($gudangs as $g){
                    		?>
                    			<option value="<?php echo $g['gudang_id']; ?>" <?php echo $g['gudang_id'] == $filter_gudang_id?'selected':''; ?>><?php echo $g['nama']; ?></option>
                    		<?php
                    		}
                    		?>
                    	</select>
                  </td>

                   <td>Product Name
                    <input type="text" name="filter_product_name" />
                    <input type="hidden" name="filter_product_id" />
                  </td>


                  <td ><a onclick="filter();" class="btn btn-success">Filter</a></td>
                </tr>

              </table>
              </div>
            </div>
            <div class="row">
              <div class="col-md-12">
                <table class="table">
                  <thead>
                    <tr>
                      <th class="left">Gudang</th>
                    	<th class="left">Tanggal</th>
                      <th class="left">Nama Produk</th>
                     <th class="left">Ukuran</th>
                       <th class="right">Quantity</th>
                       <th class="left">Nilai Barang</th>

                    </tr>
                  </thead>
                  <tbody>
                    <?php if (isset($products)) { ?>
                    <?php foreach ($products as $product) {
                      ?>
                    <tr>
                      <td class="left"><?php echo $product['nama']; ?></td>
                      <td class="left"><?php echo $product['date_added']; ?></td>
                      <td class="left"><?php echo $product['name']; ?></td>
                     <td class="left"><?php echo $product['model']; ?></td>
                      <td class="right"><?php echo $product['quantity']; ?></td>
                      <td class="left"><?php echo $product['nilaibarang']; ?></td>
                    </tr>
                    <?php

                    } ?>
                    <?php } else { ?>
                    <tr>
                      <td class="center" colspan="5">Data tidak ditemukan</td>
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
                <?php echo $pagination; ?>
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
$('.sidebar-menu').find('#menu-produk-cacat').addClass('active');
$('.sidebar-menu').find('#menu-daftar-persediaan').addClass('active');

</script>

<script type="text/javascript"><!--
function filter() {
	url = 'index.php?route=gudang/productcacat&token=<?php echo $token; ?>';

	var filter_date_start = $('input[name=\'filter_date_start\']').val();

	if (filter_date_start) {
		url += '&filter_date_start=' + encodeURIComponent(filter_date_start);
	}

	var filter_date_end = $('input[name=\'filter_date_end\']').val();

	if (filter_date_end) {
		url += '&filter_date_end=' + encodeURIComponent(filter_date_end);
	}

    var filter_product_id = $('input[name=\'filter_product_id\']').val();

	if (filter_product_id) {
		url += '&filter_product_id=' + filter_product_id;
	}

	var filter_gudang_id = $('select[name=\'filter_gudang_id\']').val();

	if (filter_gudang_id) {
		url += '&filter_gudang_id=' + filter_gudang_id;
	}


	location = url;
}
//--></script>
<script type="text/javascript"><!--
$(document).ready(function() {
	$('#date-start').datepicker({dateFormat: 'yy-mm-dd'});

	$('#date-end').datepicker({dateFormat: 'yy-mm-dd'});
});
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
						model: item.model,
						option: item.option,
						price: item.price
					}
				}));
			}
		});
	},
	select: function(event, ui) {
		$('input[name=\'filter_product_name\']').attr('value', ui.item['label']);
		$('input[name=\'filter_product_id\']').attr('value', ui.item['value']);



		return false;
	},
	focus: function(event, ui) {
      	return false;
   	}
});
//--></script>
<?php echo $footer; ?>
