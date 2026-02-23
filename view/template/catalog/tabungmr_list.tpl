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
            <h3 class="box-title">Tabung Gas Milik Relasi</h3>
            <div class="button pull-right">
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
                        <th>Jenis Gas</th>
                        <th>Ukuran Tabung</th>
                        <th>Customer</th>
                        <th>Status</th>
                        <th></th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr>
                      <td>
                          <select name="filter_product_id" class="form-control lokasi-pameran">
                            <option value="*" <?php echo empty($filter_product_id)?'selected':'';?>>Semua Jenis Gas</option>


                          </select>
                        </td>

                        <td>
                          <select class="select-ads" style="width:200px" name="filter_ukuran_tabung" >
                            <option value="*">Semua Ukuran Tabung</option>
                            <?php
                            foreach($ukurans as $u){
                            ?>
                              <option value="<?php echo $u['product_options_id']?>" <?php echo $u['product_options_id'] == $filter_ukuran_tabung?'selected':''; ?>><?php echo $u['name']; ?></option>
                            <?php
                            }
                            ?>
                          </select>
                        </td>
                      </td>
                      <td>
                        <select class="customer" style="width:200px" name="filter_pemilik" >
                          <option value="*">Semua Customer</option>

                        </select>
                      </td>
                    </td>
                      <td><select name="filter_status" class="form-control">
                              <option value="*">Semua Status</option>
                              <option value="1" <?php echo $filter_status == 1?'selected':''; ?>>Tersedia</option>
                              <option value="2" <?php echo $filter_status == 2?'selected':''; ?>>Tidak Tersedia</option>
                              <option value="3" <?php echo $filter_status == 3?'selected':''; ?>>Hilang</option>

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
                        <th class="left">Jenis Gas</th>
                        <th class="left">Ukuran Tabung</th>
                        <th class="left">Customer</th>
                        <th class="left">Quantity</th>
                        <th class="left">Status</th>
                        <th class="right"></th>
                      </tr>
                    </thead>
                    <tbody>

                      <?php if ($products) { ?>
                      <?php foreach ($products as $product) { ?>
                      <tr <?php echo $product['pemilik']==2?'style="background:#eee"':'';?>>
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
                          <td class="left"><?php echo $product['jenisgas']; ?></td>
                          <td class="left"><?php echo $product['ukurantabung']; ?></td>
                          <td class="left"><?php echo $product['pemilik']; ?>
                          </td>
                          <td class="left"><?php echo $product['quantity']; ?></td>

                        <td class="left"><?php echo $product['status']; ?></td>
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
$('.sidebar-menu').find('#menu-persediaan-tabung').addClass('active');
$(function(){
  $(".select-ads").select2({


      theme:"bootstrap"
    });
})
</script>
<script type="text/javascript">
function filter() {
	url = 'index.php?route=catalog/tabungmr&token=<?php echo $token; ?>';

	var filter_status = $('select[name=\'filter_status\']').val();

	if (filter_status != '*') {
		url += '&filter_status=' + encodeURIComponent(filter_status);
	}

  var filter_ukuran_tabung = $('select[name=\'filter_ukuran_tabung\']').val();

  	if (filter_ukuran_tabung != '*') {
  		url += '&filter_ukuran_tabung=' + encodeURIComponent(filter_ukuran_tabung);
  	}

    var filter_product_id = $('select[name=\'filter_product_id\']').val();

    if (filter_product_id != '*') {
      url += '&filter_product_id=' + encodeURIComponent(filter_product_id);
    }

    var filter_pemilik = $('select[name=\'filter_pemilik\']').val();

    if (filter_pemilik != '*') {
      url += '&filter_pemilik=' + encodeURIComponent(filter_pemilik);
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
$(function(){
  $(".customer").select2({
    ajax: {
    url:"index.php?route=sale/customer/autocomplete&token=<?php echo $token; ?>",
    //url: "index.php?route=pamerantoko/toko/autocomplete&token=<?php echo $this->request->get['token']; ?>",
    dataType: 'json',
    data: function (params) {
      return {
        q: params.term, // search term

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
  $(".select-ads").select2({


      theme:"bootstrap"
    });
  $(".lokasi-pameran").select2({
    ajax: {
    url:"index.php?route=catalog/product/autocompleteprod&token=<?php echo $token; ?>",
    //url: "index.php?route=pamerantoko/toko/autocomplete&token=<?php echo $this->request->get['token']; ?>",
    dataType: 'json',
    data: function (params) {
      return {
        q: params.term,
        statustabung:2,
       // kategori:200

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
});
//--></script>

<?php echo $footer; ?>
