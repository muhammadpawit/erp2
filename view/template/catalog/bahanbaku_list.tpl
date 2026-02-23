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
            <h3 class="box-title">Bahan Baku</h3>
            <div class="button pull-right">
									<a href="<?php echo $insert; ?>"><button type="button" class="btn btn-primary">Tambah</button></a>
                  <a onclick="$('#form').submit();" ><button type="button" class="btn btn-danger">Hapus</button></a>
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
                        <th>Nama Bahan Baku</th>
                        <th></td>
                      </tr>
                    </thead>
                    <tbody>
                    <tr>
                      <td><input type="text" class="form-control" name="filter_name" value="<?php echo $filter_name; ?>" />
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
                        <th class="left">ID</th>
                        <th class="left">Nama Bahan Baku</th>
          				      <th class="right">Quantity</th>
                        <th class="right">Level</th>
                        <th class="left">Tanggal Input</th>
                        <th class="right" width="375px"></th>
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
                                    <input type="checkbox" name="selected[]" value="<?php echo $product['id']; ?>" checked="checked" />
                                    <?php } else { ?>
                                    <input type="checkbox" name="selected[]" value="<?php echo $product['id']; ?>" />
                                    <?php }}

                    				//echo $product['stok'];
                    				?>
                    		</td>
                          <td class="left"><?php echo $product['id']; ?></td>
                      <td class="left"><?php echo $product['name']; ?></td>

                        <td class="right"><?php if ($product['quantity'] <= 0) { ?>
                          <span style="color: #FF0000;"><?php echo $product['quantity']; ?></span>
                          <?php } elseif ($product['quantity'] <= 5) { ?>
                          <span style="color: #FFA500;"><?php echo $product['quantity']; ?></span>
                          <?php } else { ?>
                          <span style="color: #008000;"><?php echo $product['quantity']; ?></span>
                          <?php } ?></td>
                          <td class="left"><?php echo $product['level']; ?></td>
                        <td class="left"><?php echo $product['date_added']; ?></td>
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
$('.sidebar-menu').find('#menu-persediaan-bahanbaku').addClass('active');
$('.sidebar-menu').find('#menu-daftar-bahanbaku').addClass('active');
</script>
<script type="text/javascript">
function filter() {
	url = 'index.php?route=catalog/bahanbaku&token=<?php echo $token; ?>';

	var filter_name = $('input[name=\'filter_name\']').val();

	if (filter_name) {
		url += '&filter_name=' + encodeURIComponent(filter_name);
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
			url: 'index.php?route=catalog/bahanbaku/autocomplete&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request.term),
			dataType: 'json',
			success: function(json) {
				response($.map(json, function(item) {
					return {
						label: item.name,
						value: item.id
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


//--></script>
<?php echo $footer; ?>
