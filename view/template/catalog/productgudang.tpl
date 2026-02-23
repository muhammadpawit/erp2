<?php echo $header; ?>
<!-- Modal -->
<div id="upex" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Upload Harga Terendah</h4>
      </div>
      <div class="modal-body">
        <form action="<?php echo $uploadex ?>" method="post" name="frmExcelImport" id="frmExcelImport" enctype="multipart/form-data">
            <div>
                <label>Choose Excel
                    File</label> <input type="file" name="file"
                    id="file" accept=".xls,.xlsx">
                <button type="submit" id="submit" name="import"
                    class="btn-submit">Import</button>
        
            </div>
        
        </form>
        <br>
        Download format excel <a href="http://erp2.nissonindonesia.com/Format_Excel.xlsx">disini</a>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
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
            			<a href="<?php echo $cetak; ?>" target="_blank"><button type="button" class="btn btn-primary"><i class="fa fa fa-file-excel-o"></i>&nbsp;Export to Excel</button></a>
                  <?php if($sethargaterendah==1){?>
                  <button type="button" class="btn btn-info" data-toggle="modal" data-target="#upex">Import Data</button>
                  <?php } ?>
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

                    </tr>
                  </tbody>
                  </table>
              </div>
            </div>
            <div class="row">
              <div class="col-md-12">
                <table class="table table-stripped">
                    <thead>
                      <tr>
                        <th style="width:200px">Gudang</th>
                        <th>Quantity</th>

                        <th>Urutkan</th>
                        <th></td>
                      </tr>
                    </thead>
                    <tbody>
                    <tr>
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
                      <td><select name="filter_qty" class="form-control">
                  					<option value="*">Tampil Semua</option>
                  					<option value="1" <?php echo $filter_qty == 1?'selected':''; ?>>Lebih dari 0</option>
                            <option value="2" <?php echo $filter_qty == 2?'selected':''; ?>>0</option>
                  				</select>
                      </td>

                      <td><select name="filter_urutkan" class="form-control">
                              <option value="*"></option>
                              <option value="1" <?php echo $filter_urutkan == 1?'selected':'';?>>Product ID (Terbaru)</option>
                              <option value="2" <?php echo $filter_urutkan == 2?'selected':'';?>>Product ID (Terlama)</option>
                              <option value="3" <?php echo $filter_urutkan == 3?'selected':'';?>>Product Name (A-Z)</option>
                              <option value="4" <?php echo $filter_urutkan == 4?'selected':'';?>>Product Name (Z-A)</option>
                              <option value="5" <?php echo $filter_urutkan == 5?'selected':'';?>>Quantity Terbesar</option>
                              <option value="6" <?php echo $filter_urutkan == 6?'selected':'';?>>Quantity Terkecil</option>
                                <option value="8" <?php echo $filter_urutkan == 8?'selected':'';?>>Gudang (A-Z)</option>
                                <option value="7" <?php echo $filter_urutkan == 7?'selected':'';?>>Gudang (Z-A)</option>

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
          				      <th class="right">Quantity</th>
                        <th class="right">Free Stok</th>
                        <th class="right" width="375px"></th>
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
                        <td><?php echo ($product['quantity']-$product['freestok']); ?></td>
                        <td class="right"><?php foreach ($product['action'] as $action) { ?>
                           <a href="<?php echo $action['href']; ?>" class="badge bg-yellow"><?php echo $action['text']; ?></a>
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
<?php echo $footer; ?>
