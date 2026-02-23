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
            <h3 class="box-title">Tanda Terima Tabung Kosong</h3>
            <div class="button pull-right">
              <a href="<?php echo $insert; ?>"><button type="button" class="btn btn-primary">Tambah TTTK</button></a>
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
                <form action="<?php echo $delete; ?>" method="post" enctype="multipart/form-data" id="form">
                  <table class="table table-bordered">
                    <thead>
                      <tr>
                        <th width="1" style="text-align: center;">Tanggal</th>
                        <th class="left">No. TTTK</th>
                        <th class="left">TTTK Manual</th>
                        <th class="left">Nama</th>
                        <th class="left">Jenis Pengambilan</th>
                        <th class="left">Status</th>
                         <th class="right"></th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr class="filter">
                        <td> 
                          <input type="text" class="date" name="filter_tanggal" value="<?php echo $filter_tanggal; ?>" placeholder="awal" /><br>
                          <input type="text" class="date" name="filter_tanggalakhir" value="<?php echo $filter_tanggalakhir; ?>" placeholder="akhir"/>
                        </td>
                        <td>
                          <select name="filter_order_id" class="salesorder form-control">
                              <option value="*">Semua Order ID</option>


                            </select>
                        </td>
                        <td></td>
                        <td>
                          <select name="filter_customer_id" class="form-control lokasi-pameran">
                      			<option value="*" <?php echo empty($filter_customer_id)?'selected':'';?>>Semua Customer</option>


                      		</select>
                        </td>
                        <td>
                          <select name="filter_shipping_method" class="form-control">
                              <option value="*">Semua Pengiriman</option>
                              <option value="1" >Dijemput</option>
                              <option value="2" >Diantar</option>

                          </select>
                        </td>
                        <td></td>
                        <td align="right"><a onclick="filter();" class="btn btn-info">Filter</a></td>
                      </tr>

                      <?php if ($penjualans) { ?>
                      <?php foreach ($penjualans as $product) { ?>
                      <tr>
                        <td><?php echo $product['tanggal']; ?></td>
                        <td class="left"><?php echo $product['no_so']; ?>
                        </td>
                        <td class="left"><?php echo $product['tttk_manual']; ?>
                        </td>
                        <td class="left"><?php echo $product['name']; ?></td>
                        <td><?php
                                if($product['status_pengiriman'] == 1){
                                  echo 'Dijemput';
                                }
                                if($product['status_pengiriman'] == 2){
                                  echo 'Diantar';
                                }

                        ?></td>
                         <td><?php
                                if($product['status'] == 1){
                                  echo '<span class="badge bg-green">Disimpan</span>';
                                }
                                if($product['status'] ==3){
                                  echo '<span class="badge bg-red">Dibatalkan</span> <br><small><i>'.$product['alasan_batal'].'</i></smal>';
                                }

                        ?></td>
                         <td class="right"><?php foreach ($product['action'] as $action) { ?>
                          <a class="badge <?php echo $action['text'] == 'Batalkan'?'bg-red':'bg-green'; ?>" href="<?php echo $action['href']; ?>"><?php echo $action['text']; ?></a>
                          <?php } ?></td>
                      </tr>
                      <?php } ?>
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
$('.sidebar-menu').find('#menu-penjualan').addClass('active');
$('.sidebar-menu').find('#menu-tttk').addClass('active');
</script>
<script>
$(function(){
  $('.date').datepicker({dateFormat: 'yy-mm-dd'});
})
</script>
<script type="text/javascript"><!--
function filter() {
	url = "index.php?route=sale/tttk&token=<?php echo $token; ?>";

	var filter_customer_id = $('select[name=\'filter_customer_id\']').val();

	if (filter_customer_id != '*') {
		url += '&filter_customer_id=' + encodeURIComponent(filter_customer_id);
	}


  var filter_tanggal = $('input[name=\'filter_tanggal\']').val();

	if (filter_tanggal) {
		url += '&filter_tanggal=' + encodeURIComponent(filter_tanggal);
	}
  var filter_tanggalakhir = $('input[name=\'filter_tanggalakhir\']').val();

	if (filter_tanggalakhir) {
		url += '&filter_tanggalakhir=' + encodeURIComponent(filter_tanggalakhir);
	}
  var filter_order_id = $('select[name=\'filter_order_id\']').val();

	if (filter_order_id != '*') {
		url += '&filter_order_id=' + encodeURIComponent(filter_order_id);
	}
  var filter_shipping_method = $('select[name=\'filter_shipping_method\']').val();

	if (filter_shipping_method != '*') {
		url += '&filter_shipping_method=' + encodeURIComponent(filter_shipping_method);
	}

  if(filter_tanggal){
    if(filter_tanggalakhir==''){
      alert("tanggal akhir harus diisi");
      return false;
    }
  }



	location = url;
}
$(function(){
  $(".select-ads").select2({


      theme:"bootstrap"
    });
  $(".lokasi-pameran").select2({
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

  $(".salesorder").select2({
    ajax: {
    url:"index.php?route=sale/tttk/autocomplete&token=<?php echo $token; ?>",
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


})
//--></script>
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
			url: 'index.php?route=catalog/atk/autocomplete&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request.term),
			dataType: 'json',
			success: function(json) {
				response($.map(json, function(item) {
					return {
						label: item.nama,
						value: item.atk_id
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
