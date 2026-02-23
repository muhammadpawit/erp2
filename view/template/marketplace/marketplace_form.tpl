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
            <h3 class="box-title">Tambah Marketplace</h3>
            <div class="button pull-right">
                <a onclick="$('#form').submit();"><button type="button" class="btn btn-primary">Simpan</button></a>
                <a href="<?php echo $cancel?>" ><button type="button" class="btn btn-danger">Cancel</button></a>
            </div>
          </div>
          <div class="box-body">
            <div class="col-md-6 col-xs-12">
              <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
                <div class="form-group">
                  <label>Nama Marketplace</label>
                  <input type="text" name="nama" value="<?php echo $nama ?>" class="form-control">
                </div>
              </form>
            </div>

          </div>
          <div class="box-footer">
            <div class="row">
              <div class="col-md-12">
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
	url = 'index.php?route=keuangan/bank&token=<?php echo $token; ?>';

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
