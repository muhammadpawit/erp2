<?php echo $header; ?>
<div class="content-wrapper">
  <section class="content-header">
    <h1>

    </h1>
  <!-- Modal -->
  <div id="lihatso" class="modal fade" role="dialog">
    <div class="modal-dialog">
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Sales Order</h4>
        </div>
        <div class="modal-body">
          
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>
  </section>

  <section class="content">
    <div class="row">
      <div class="col-md-12">
        <div class="box">
          <div class="box-header with-border">
            <h3 class="box-title">Produk Dagang</h3>
            <div class="button pull-right">
									<a href="<?php echo $insert; ?>"><button type="button" class="btn btn-primary">Tambah</button></a>
                  <a onclick="$('#form').submit();" ><button type="button" class="btn btn-danger">Hapus</button></a>
                  <a href="<?php echo $exporttoexcel ?>" target="_blank"><button type="button" class="btn btn-info">Export to excel</button></a>						      
            </div>
          </div>
          <div class="box-body">
            <div class="row">
              <div class="col-md-12">
                <?php if ($error_warning) { ?>
                <div class="alert alert-danger alert-dismissible">
                  <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                  <h4><i class="icon fa fa-ban"></i> Alert!</h4>
                  <?php echo $error_warning; ?>
                </div>
                <?php
                }
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
                        <th>Status</th>
                        <th>Urutkan</th>
                        <th></td>
                      </tr>
                    </thead>
                    <tbody>
                    <tr>
                      <td><input type="text" class="form-control" name="filter_name" value="<?php echo $filter_name; ?>" />
                      </td>

                      <td><select name="filter_category_id" class="form-control">
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
                    <td><select name="filter_urutkan" class="form-control">
                              <option value="*"></option>
                              <option value="1" <?php echo $filter_urutkan == 1?'selected':'';?>>Product ID (Terbaru)</option>
                              <option value="2" <?php echo $filter_urutkan == 2?'selected':'';?>>Product ID (Terlama)</option>
                              <option value="3" <?php echo $filter_urutkan == 3?'selected':'';?>>Product Name (A-Z)</option>
                              <option value="4" <?php echo $filter_urutkan == 4?'selected':'';?>>Product Name (Z-A)</option>
                              <option value="5" <?php echo $filter_urutkan == 5?'selected':'';?>>Quantity Terbesar</option>
                              <option value="6" <?php echo $filter_urutkan == 6?'selected':'';?>>Quantity Terkecil</option>

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
                <form action="<?php echo $delete; ?>" method="post" enctype="multipart/form-data" id="form">
                  <table class="table table-bordered">
                    <thead>
                      <tr>
                        <td width="1" style="text-align: center;"><input type="checkbox" onclick="$('input[name*=\'selected\']').attr('checked', this.checked);" /></td>
                        <th class="left">Product ID</th>
                        <th class="left">Nama Produk</th>
          				      <th class="left">Barcode</th>
                        <th class="right">Quantity</th>
                        <th class="right">Free Stok</th>
                        <th class="right">Sales Order</th>
                        <th class="right" width="75px"></th>
                      </tr>
                    </thead>
                    <tbody>

                      <?php if ($products) { ?>
                      <?php foreach ($products as $product) { ?>
                      <tr>
                        <td style="text-align: center;">
                    		<?php
                    		if($product['quantity'] == 0){
                    		?>
                    		<?php if ($product['selected']) { ?>
                                    <input type="checkbox" name="selected[]" value="<?php echo $product['product_id']; ?>" checked="checked" />
                                    <?php } else { ?>
                                    <input type="checkbox" name="selected[]" value="<?php echo $product['product_id']; ?>" />
                                    <?php }}

                    				//echo $product['stok'];
                    				?>
                    		</td>
                          <td class="left"><?php echo $product['product_id']; ?></td>
                      <td class="left"><?php echo $product['name']; ?></td>
          			           <td class="left"><?php echo $product['barcode']; ?></td>

                        <td class="right"><?php if ($product['quantity'] <= 0) { ?>
                          <span style="color: #FF0000;"><?php echo $product['quantity']; ?></span>
                          <?php } elseif ($product['quantity'] <= 5) { ?>
                          <span style="color: #FFA500;"><?php echo $product['quantity']; ?></span>
                          <?php } else { ?>
                          <span style="color: #008000;"><?php echo $product['quantity']; ?></span>
                          <?php } ?> <?php echo $product['satuan']; ?></td>
                        <td class="right">
                          <?php 
                            echo ($product['quantity'] -$product['freestok']) 

                            ?> <?php echo $product['satuan']; ?></td>
                        <td><a onclick="detail(<?php echo $product['product_id']?>)" data-toggle="modal" data-target="#lihatso">Lihat SO</a>
                        <td class="right"><?php foreach ($product['action'] as $action) { ?>
                           <a href="<?php echo $action['href']; ?>" class="label label-primary"><?php echo $action['text']; ?></a><br>
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
                </form>
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
$('.sidebar-menu').find('#menu-daftar-produk').addClass('active');
function detail(id){
  //alert(id);
    $.ajax({
      url: 'index.php?route=catalog/product/getso&token=<?php echo $token; ?>&id=' +  encodeURIComponent(id),
      //dataType: 'json',
      success: function(json) {
        $('.modal-body').html(json);
      }
    });/**/
}
</script>
<script type="text/javascript">
function filter() {
	url = 'index.php?route=catalog/product&token=<?php echo $token; ?>';

	var filter_name = $('input[name=\'filter_name\']').val();

	if (filter_name) {
		url += '&filter_name=' + encodeURIComponent(filter_name);
	}

var filter_category_id = $('select[name=\'filter_category_id\']').val();

	if (filter_category_id != '*') {
		url += '&filter_category_id=' + encodeURIComponent(filter_category_id);
	}

    var filter_urutkan = $('select[name=\'filter_urutkan\']').val();

	if (filter_urutkan != '*') {
		url += '&filter_urutkan=' + encodeURIComponent(filter_urutkan);
	}

	location = url;
}
</script>
<script type="text/javascript"><!--
$('#form input').keydown(function(e) {
	if (e.keyCode == 13) {
		filter();
	}
});
//--></script>
<script type="text/javascript"><!--
$('input[name=\'filter_name\']').autocomplete({
	delay: 0,
	source: function(request, response) {
		$.ajax({
			url: 'index.php?route=catalog/product/autocomplete&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request.term),
			dataType: 'json',
			success: function(json) {
				response($.map(json, function(item) {
					return {
						label: item.name,
						value: item.product_id
					}
				}));
			}
		});
	},
	select: function(event, ui) {
		$('input[name=\'filter_name\']').val(ui.item.label);

		return false;
	},
	focus: function(event, ui) {
      	return false;
   	}
});

$('input[name=\'filter_model\']').autocomplete({
	delay: 0,
	source: function(request, response) {
		$.ajax({
			url: 'index.php?route=catalog/product/autocomplete&token=<?php echo $token; ?>&filter_model=' +  encodeURIComponent(request.term),
			dataType: 'json',
			success: function(json) {
				response($.map(json, function(item) {
					return {
						label: item.model,
						value: item.product_id
					}
				}));
			}
		});
	},
	select: function(event, ui) {
		$('input[name=\'filter_model\']').val(ui.item.label);

		return false;
	},
	focus: function(event, ui) {
      	return false;
   	}
});
//--></script>
<?php echo $footer; ?>
