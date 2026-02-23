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
            <h3 class="box-title">Marketplace</h3>
            <div class="button pull-right">
                <a href="<?php echo $insert; ?>" ><button type="button" class="btn btn-primary">Tambah</button></a>
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
                    <input name="filter_name" type="text" placeholder="Filter nama" class="form-control" value="<?php echo $filter_name; ?>" >
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
                      <th class="left">No.</th>
                      <th class="left">Nama Marketplace</th>
                      <th class="right"> </th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php if (isset($products)) { ?>
                    <?php foreach ($products as $product) {
                      ?>
                    <tr>
                      <td class="left"><?php echo $product['no']; ?></td>
                      <td class="left"><?php echo $product['nama']; ?></td>
                      <td class="right"><?php foreach ($product['action'] as $action) { ?>
                     <a href="<?php echo $action['href']; ?>" class="badge bg-yellow"><?php echo $action['text']; ?></a>
                    <?php } ?> <a onclick="hapus(<?php echo $product['id']?>,'<?php echo $product['nama']?>')" class="badge bg-red">hapus</a></td>
                    </tr>
                    <?php

                    } ?>
                    <?php } else { ?>
                    <tr>
                      <td class="center" colspan="3">Data tidak ditemukan</td>
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
$('.sidebar-menu').find('#menu-master-data').addClass('active');
$('.sidebar-menu').find('#menu-marketplace').addClass('active');

function hapus(id,marketplace){
  var h = window.confirm("apakah yakin marketplace "+marketplace+" akan dihapus? data yang sudah dihapus tidak dapat dikembalikan lagi");
  if(h==true){
    $.ajax({
      url: 'index.php?route=marketplace/marketplace/hapus&token=<?php echo $token; ?>&id=' + id,
      //dataType: 'json',
      success: function(json) {
        if(json>0){
          alert("data berhasil dihapus!");
          location.reload();
        }else{
          alert("Gagal menghapus data");
        }
      }
    });
  }else{
    return false;
  }
}
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
	url = 'index.php?route=marketplace/marketplace&token=<?php echo $token; ?>';

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
