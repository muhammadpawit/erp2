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
            <h3 class="box-title">Stok Pameran & Toko</h3>
            <div class="button pull-right">
                <a href="<?php echo $cetak; ?>" target="_blank"><button type="button" class="btn btn-primary">Cetak</button></a>

								</div>
          </div>
          <div class="box-body">
						<div class="row">
              <div class="col-md-12">
                <table class="table">
                <tr>
                  <td>Product Name
                    <input type="text" name="filter_product_name" value="<?php echo $filter_name;?>" />
                    <input type="hidden" name="filter_product_id" />
                  </td>
                  <td>Quantity
                    <select name="filter_qty">
                				<option value="*" <?php echo empty($filter_qty)?'selected':'';?>>Tampil Semua</option>
                				<option value="1" <?php echo $filter_qty == 1?'selected':'';?>>Lebih Dari 0</option>
                				<option value="2" <?php echo $filter_qty == 2?'selected':'';?>>0</option>
                				<option value="3" <?php echo $filter_qty == 3?'selected':'';?>>Kurang dari 0</option>
                			</select>
                  </td>

                  <td>Toko/Pameran
                    <select name="filter_toko">
                			<option value="*" <?php echo empty($filter_toko)?'selected':'';?>>Semua Toko dan Pameran</option>
                			<option value="1" <?php echo $filter_toko == 1?'selected':'';?>>Pameran</option>
                			<option value="2" <?php echo $filter_toko == 2?'selected':'';?>>Toko</option>

                		</select>

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
                      <th class="left">Jenis</th>
                      <th class="left">Lokasi Toko/Pameran</th>
                      <th class="left">Nama Produk</th>
                      <th class="right">Harga</th>
                       <th class="right">Quantity</th>
                        <th class="right"> </th>

                    </tr>
                  </thead>
                  <tbody>
                    <?php if (isset($products)) { ?>
                    <?php foreach ($products as $product) {
                      ?>
                    <tr>
                      <td class="left"><?php echo $product['jenis']; ?></td>
                      <td class="left"><?php echo $product['lokasi']; ?></td>
                      <td class="left"><?php echo $product['product_name']; ?></td>
                      <td class="right"><?php echo $product['price']; ?></td>
                      <td class="right"><?php echo $product['qty']; ?></td>
                      <td class="right"><?php foreach ($product['action'] as $action) { ?>
                    [ <a href="<?php echo $action['href']; ?>"><?php echo $action['text']; ?></a> ]
                    <?php } ?></td>
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
                <?php if(isset($products)){echo $pagination;} ?>
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
$('.sidebar-menu').find('#menu-stok-toko-pameran').addClass('active');
$('.sidebar-menu').find('#menu-daftar-persediaan').addClass('active');
</script>

<script type="text/javascript"><!--
function filter() {
	url = 'index.php?route=pamerantoko/produktoko&token=<?php echo $token; ?>';

	var filter_product_id = $('input[name=\'filter_product_id\']').val();


	var filter_name = $('input[name=\'filter_product_name\']').val();
	if (filter_name) {
		url += '&filter_name=' + filter_name;
	}

	var filter_toko = $('select[name=\'filter_toko\']').val();

	if (filter_toko != '*') {
		url += '&filter_toko=' + filter_toko;
	}

	/*var filter_gudang_id = $('select[name=\'filter_gudang_id\']').val();

	if (filter_gudang_id != '*') {
		url += '&filter_pameran_id=' + filter_gudang_id;
	}*/

	var filter_qty = $('select[name=\'filter_qty\']').val();

	if (filter_qty != '*') {
		url += '&filter_qty=' + filter_qty;
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
						model: item.model,
						option: item.option,
						price: item.price
					}
				}));
			}
		});
	},
	select: function(event, ui) {
		$('input[name=\'filter_product_name\']').val(ui.item['label']);
		$('input[name=\'filter_product_id\']').val(ui.item['value']);



		return false;
	},
	focus: function(event, ui) {
      	return false;
   	}
});
//--></script>
<?php echo $footer; ?>
