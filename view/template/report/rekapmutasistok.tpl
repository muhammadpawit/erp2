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
            <h3 class="box-title">Laporan Rekap Mutasi Stok</h3>
            <div class="button pull-right">
            			<a href="<?php echo $cetak; ?>" target="_blank"><button type="button" class="btn btn-primary"><i class="fa fa fa-file-excel-o"></i>&nbsp;Export to Excel</button></a>
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
                        <th>Tanggal</th>
                        <!--<th>Tanggal Akhir</th>-->
                        <th>Gudang</th>
                        <th></th>
                      </tr>
                    </thead>
                    <tbody>
                    <tr>
                      <td><input type="text" class="form-control" name="filter_product_name" value="<?php echo $filter_name; ?>" /></td>
                      <td><input type="text" name="filter_date_start" class="date form-control" value="<?php echo $filter_date_start?>" /></td>
                      <!--<td><input type="text" name="filter_date_end" class="date form-control" value="<?php echo $filter_date_end?>" /></td>-->
                      <td>
                        <select style="width:100%" name="filter_gudang_id" class="form-control">
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
                      <td align="left"><a onclick="filter();" class="btn btn-info"><i class="fa fa-search"></i></a></td>
                    </tr>
                  </tbody>
                  </table>
              </div>
            </div>
            <div class="row">
              <div class="col-md-12">
                <table class="table table-stripped">
                    <tbody>
                    <tr>
                      
                    </tr>
                  </tbody>
                  </table>
              </div>
            </div>
            <div class="row">
              <div class="col-md-12">
                <table class="table table-bordered table-hover">
                    <thead>
                      <tr>
                        <th class="left">Gudang</th>
                        <th class="text-center">Produk ID</th>
                        <th class="left">Nama Produk</th>
          				      <th class="text-center">Stok Awal</th>
                        <th class="text-center">Stok Masuk</th>
                        <th class="text-center">Stok Keluar</th>
                        <th class="text-center">Stok Akhir</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php if ($products) { ?>
                      <?php foreach ($products as $product) { ?>
                      <tr>
                        <td class="left"><?php echo $product['nama'];?></td>
                        <td class="text-center"><?php echo $product['product_id'];?></td>
                        <td class="left"><?php echo $product['name']; ?></td>
          			        <td align="center"><?php echo $product['quantityawal']; ?></td>
                        <td align="center"><?php echo $product['stokmasuk']; ?></td>
                        <td align="center"><?php echo $product['stokkeluar']; ?></td>
                        <td align="center"><?php echo $product['saldo']; ?></td>
                      </tr>
                      <?php } ?>
                      <?php } else { ?>
                      <tr>
                        <td class="center" colspan="9">Data tidak ditemukan</td>
                      </tr>
                      <?php } ?>
                      <tr>
                        <td colspan="3"><b>Total</b></td>
                        <td class="text-center"><b><?php echo $totalawal?></b></td>
                        <td class="text-center"><b><?php echo $totalmasuk?></b></td>
                        <td class="text-center"><b><?php echo $totalkeluar?></b></td>
                        <td class="text-center"><b><?php echo $totalsaldo?></b></td>
                      </tr>
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
	url = 'index.php?route=laporan/rekapmutasistok&token=<?php echo $token; ?>';



	var filter_name = $('input[name=\'filter_product_name\']').val();

	if (filter_name) {
		url += '&filter_name=' + encodeURIComponent(filter_name);
	}

  var filter_gudang_id = $('select[name=\'filter_gudang_id\']').val();

	if (filter_gudang_id !="*") {
		url += '&filter_gudang_id=' + filter_gudang_id;
	}

	var filter_date_start = $('input[name=\'filter_date_start\']').val();

	if (filter_date_start) {
		url += '&filter_date_start=' + filter_date_start;
	}

    var filter_date_end = $('input[name=\'filter_date_end\']').val();

	if (filter_date_end) {
		url += '&filter_date_end=' + filter_date_end;
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
