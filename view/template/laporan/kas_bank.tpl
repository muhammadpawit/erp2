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
            <h3 class="box-title">Laporan Kas dan Bank</h3>
            <div class="button pull-right">
                
            </div>
          </div>
          <div class="box-body">
            <div class="row">
              <div class="col-md-12">

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
                <table class="table">
                <tr>
                  <td>
                    <input name="filter_name" type="text" placeholder="Filter nama bank" class="form-control" value="<?php echo $filter_name; ?>" >
                  </td>


                  <td ><a onclick="filter();" class="btn btn-success">Filter</a></td>
                </tr>

              </table>
              </div>
            </div>
            <div class="row">
              <div class="col-md-12">
                  <form action="<?php echo $delete; ?>" method="post" enctype="multipart/form-data" id="form">
                <table class="table">
                  <thead>
                    <tr>
                      <td width="1" style="text-align: center;">
                        <input type="checkbox" onclick="$('input[name*=\'selected\']').attr('checked', this.checked);" />
                      </td>
                      <th class="left">Nama Bank</th>
                      <th class="left">Rekening</th>
                      <th class="left">Pemilik</th>
                      <th class="left">Mata Uang</th>
                      <th class="left">Display Order</th>
                      <th class="left">Saldo</th>
                      <th class="left">Hutang PRK</th>
                      <th class="right"> </th>

                    </tr>
                  </thead>
                  <tbody>
                    <?php if (isset($banks)) { ?>
                    <?php foreach ($banks as $product) {
                      ?>
                    <tr>
                      <td style="text-align: center;">

                        <?php
                        if($product['candelete']){
                        if ($product['selected']) { ?>
                        <input type="checkbox" name="selected[]" value="<?php echo $product['bank_id']; ?>" checked="checked" />
                        <?php } else { ?>
                        <input type="checkbox" name="selected[]" value="<?php echo $product['bank_id']; ?>" />
                        <?php }} ?></td>
                      <td class="left"><?php echo $product['nama_bank']; ?></td>
                      <td class="left"><?php echo $product['rekening']; ?></td>
                      <td class="left"><?php echo $product['pemilik']; ?></td>
                      <td class="left"><?php echo $product['currency']; ?></td>
                      <td class="left"><?php echo $product['display_order'] == 1?'Ya':'Tidak'; ?>
                        <br>
                        <small>Cabang: <?php echo $product['cabang']; ?></small><br>
                        <small>Kota: <?php echo $product['kota']; ?></small><br>
                        <small>Swift: <?php echo $product['swiftcode']; ?></small><br>
                      </td>
                      <td class="right"><?php echo $product['saldo']; ?></td>
                      <td class="left"><?php echo $product['hutangprk'] == 1?'Ya':'Tidak'; ?>
                        <br>
                        <small>Plafon: <?php echo $product['plafon']; ?></small><br>
                        <small>Total Hutang: <?php echo $product['totalhutang']; ?></small><br>
                        
                      </td>
                      <td class="right"><?php foreach ($product['action'] as $action) { ?>
                     <a href="<?php echo $action['href']; ?>" class="badge bg-yellow"><?php echo $action['text']; ?></a>
                    <?php } ?></td>
                    </tr>
                    <?php

                    } ?>
                    <?php } else { ?>
                    <tr>
                      <td class="center" colspan="8">Data tidak ditemukan</td>
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
$('.sidebar-menu').find('#menu-keuangan').addClass('active');
$('.sidebar-menu').find('#menu-bank').addClass('active');


$(function(){
  $(".lokasi-pameran").select2({
    ajax: {
    url: 'index.php?route=keuangan/bank/autocomplete&token=<?php echo $token; ?>',
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
})
</script>

<script type="text/javascript"><!--
function filter() {
	url = 'index.php?route=laporan/kasbank&token=<?php echo $token; ?>';

	var filter_name = $('input[name=\'filter_name\']').val();
	if (filter_name != '*') {
		url += '&filter_name=' + filter_name;
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
