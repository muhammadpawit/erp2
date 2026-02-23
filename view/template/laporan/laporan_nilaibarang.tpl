<?php echo $header; ?>
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
            <h3 class="box-title">Laporan Nilai Barang</h3>
            <div class="button pull-right">
									<a href="<?php echo $cetak; ?>" target="_blank"><button type="button" class="btn btn-success">Cetak</button></a>
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
                <table class="table table-stripped">
                    <thead>
                      <tr>
                        <th>Nama Produk</th>
                        <th>Kategori</th>
                        <th>Gudang</th>
                        <th></th>
                      </tr>
                    </thead>
                    <tbody>
                    <tr>
                      <td><input type="text" class="form-control" name="filter_product_name" value="<?php echo $filter_name; ?>" />
                      </td>

                      <td><select name="filter_category_id" class="select-ads">
            					<option value="*">Semua Kategori</option>
            					<?php
            					foreach($categories as $g){
            					?>
            						<option value="<?php echo $g['category_id'] ?>" <?php echo $filter_category_id == $g['category_id']?'selected':'';?>><?php echo $g['name'] ?></option>
            					<?php
            					}
            					?>
            				</select>
                      </td>
                      <td ><select style="width:200px" name="filter_gudang_id" class="select-ads">
            					<option value="*">Semua Gudang</option>
            					<?php
            					foreach($gudangs as $g){
            					?>
            						<option value="<?php echo $g['gudang_id'] ?>" <?php echo $filter_gudang_id == $g['gudang_id']?'selected':'';?>><?php echo $g['nama'] ?></option>
            					<?php
            					}
            					?>
            				</select>
                      </td>

                      <td ><a onclick="filter();" class="btn btn-info">Filter</a></td>

                    </tr>
                  </tbody>
                  </table>
              </div>
            </div>

            <div class="row">
              <div class="col-md-12">
                <table class="table table-bordered">
                    <thead>
                      <tr>
                        <th class="left">Gudang</th>
                        <th class="left">Nama Produk</th>
                        <th class="left">Quantity</th>
          				     <th class="left">Net Cost</th>

                      </tr>
                    </thead>
                    <tbody>

                      <?php if ($products) { ?>
                      <?php foreach ($products as $product) { ?>
                      <tr>
                        <td class="left" ><?php echo $product['nama']; ?></td>
                        <td class="left" ><?php echo $product['name']; ?></td>
                        <td class="left" ><?php echo $product['quantity']; ?></td>
                        <td class="left" ><?php echo $product['net_cost']; ?></td>


                      </tr>

                      <?php } }else { ?>
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
                <div class="pull-right"><?php echo $pagination; ?></div>
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
$('.sidebar-menu').find('#menu-laporan').addClass('active');

$(function(){
  $(".select-ads").select2({


      theme:"bootstrap"
    });
})
</script>
<script type="text/javascript"><!--
function filter() {
	url = 'index.php?route=laporan/laporannilaibarang&token=<?php echo $token; ?>';



	var filter_name = $('input[name=\'filter_product_name\']').val();

	if (filter_name) {
		url += '&filter_name=' + encodeURIComponent(filter_name);
	}

var gudang_id = $('select[name=\'filter_gudang_id\']').val();

	if (gudang_id !="*") {
		url += '&gudang_id=' + gudang_id;
	}

	var filter_category_id = $('select[name=\'filter_category_id\']').val();

	if (filter_category_id !="*") {
		url += '&filter_category_id=' + filter_category_id;
	}

    var filter_qty = $('select[name=\'filter_qty\']').val();



	location = url;
}
//--></script>
<script type="text/javascript"><!--
$('input[name=\'filter_product_name\']').autocomplete({
	delay: 500,
  minLength:3,
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

		//$('input[name=\'filter_product_id\']').attr('value', ui.item['value']);



		return false;
	},
	focus: function(event, ui) {
      	return false;
   	}
});
//--></script>
<?php echo $footer; ?>
