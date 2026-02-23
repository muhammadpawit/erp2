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
            <h3 class="box-title">Invoice</h3>
            <div class="button pull-right">
                <!-- <a href="<?php echo $export ?>" target="_blank"><button class="btn btn-default"><i class="fa fa-file-excel-o"></i> Exports</button></a> -->
              <a href="<?php echo $insert; ?>"><button type="button" class="btn btn-primary">Tambah Invoice</button></a>
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
                <table class="table table-condensed">
                  <tr>
                    <th>Tanggal Awal</th>
                    <th>Tanggal Akhir</th>
                    <th>Gudang</th>
                    <th></th>
                  </tr>
                  <tr>
                    <td>
                      <input type="text" class="form-control date" name="filter_tanggal" value="<?php echo $filter_tanggal; ?>" autocomplete="off" placeholder="awal"/>
                    </td>
                    <td>
                      <input type="text" class="form-control date" name="filter_tanggalakhir" value="<?php echo $filter_tanggalakhir; ?>" autocomplete="off" placeholder="akhir"/>
                    </td>
                    <td>
                          <select class="form-control" name="filter_gudang_id">
                            <option value="*">Semua Lokasi</option>
                            <?php
                            foreach($gudangs as $g){
                            ?>
                              <option value="<?php echo $g['gudang_id']; ?>" ><?php echo $g['nama']; ?></option>
                            <?php
                            }
                            ?>
                          </select>
                    </td>
                    <td><td>
                  </tr>
                  <tr>
                    <th>No.Invoice</th>
                    <th>Nama Customer</th>
                    <th>Status</th>
                    <th></th>
                  </tr>
                  <tr>
                    <td>
                       <select name="filter_order_id" class="salesorder form-control">
                          <option value="*">Semua No Faktur</option>
                        </select>
                    </td>
                    <td>
                          <select name="filter_customer_id" class="form-control lokasi-pameran">
                      			<option value="*" <?php echo empty($filter_customer_id)?'selected':'';?>>Semua Customer</option>


                      		</select>
                    </td>
                    <td>
                       <select name="filter_status" class="form-control">
                              <option value="*">Semua Status</option>
                              <option value="1" >Ditagih</option>
                              <option value="2" >Belum Lunas</option>
                              <option value="3" >Lunas</option>
                              <option value="4" >Dibatalkan</option>
                        </select>
                    </td>
                    <td><a onclick="filter();" class="btn btn-info"><i class="fa fa-search"></i></a></td>
                  </tr>
                </table>
              </div>
            </div>
            <div class="row">
              <div class="col-md-12">
                <form action="<?php echo $delete; ?>" method="post" enctype="multipart/form-data" id="form">
                  <table class="table table-bordered">
                    <thead>
                      <tr>
                        <th width="1" style="text-align: center;">Tanggal</th>
                        <th>Gudang</th>
                        <th class="left">No. Faktur</th>
                        <th class="left">No. Dokumen</th>
                        <th class="left">Nama Customer</th>
                        <th class="left">Total</th>
                        <th class="left">Total Dibayar</th>
                        <th class="left">Status</th>
                        <th class="right"></th>
                      </tr>
                    </thead>
                    <tbody>
                      <!--
                      <tr class="filter">
                        <td>
                          <input type="text" class="date" name="filter_tanggal" value="<?php echo $filter_tanggal; ?>" autocomplete="off" placeholder="awal"/><br>
                          <input type="text" class="date" name="filter_tanggalakhir" value="<?php echo $filter_tanggalakhir; ?>" autocomplete="off" placeholder="akhir"/>
                        </td>                        
                        <td>
                          <select class="form-control" name="filter_gudang_id">
                            <option value="*">Semua Lokasi</option>
                            <?php
                            foreach($gudangs as $g){
                            ?>
                              <option value="<?php echo $g['gudang_id']; ?>" ><?php echo $g['nama']; ?></option>
                            <?php
                            }
                            ?>
                          </select>
                        </td>
                        <td>
                          <select name="filter_order_id" class="salesorder form-control">
                            <option value="*">Semua No Faktur</option>
                          </select>
                        </td>
                        <td></td>

                        <td>
                          <select name="filter_customer_id" class="form-control lokasi-pameran">
                      			<option value="*" <?php echo empty($filter_customer_id)?'selected':'';?>>Semua Customer</option>


                      		</select>
                        </td>

                        <td>

                        </td>
                      <td>

                        </td>
                        <td>
                          <select name="filter_status" class="form-control">
                              <option value="*">Semua Status</option>
                              <option value="1" >Ditagih</option>
                              <option value="2" >Belum Lunas</option>
                              <option value="3" >Lunas</option>
                              <option value="4" >Dibatalkan</option>
                          </select>
                        </td>
          		          <td align="right"><a onclick="filter();" class="btn btn-info">Filter</a></td>
                      </tr>
                      -->
                      <?php if ($penjualans) { ?>
                      <?php foreach ($penjualans as $product) { ?>
                      <tr>
                        <td><?php echo $product['tanggal']; ?></td>
                        <td><?php echo $product['namagudang']; ?></td>
                        <td class="left"><?php echo $product['no_faktur']; ?></td>
                        <td class="left"><?php echo $product['no_dokumen']; ?></td>

                        <td class="left"><?php echo $product['name']; ?><br>
                          Email: <?php echo $product['email']; ?><br>
                          Telp: <?php echo $product['telephone']; ?>
                        </td>
                        <td><?php echo $product['total']; ?></td>
                        <td><?php echo $product['totalbayar']; ?></td>
                        <td><?php
                                if($product['status'] == 1){
                                  echo 'Ditagih';
                                }
                                if($product['status'] == 2){
                                  echo 'Belum Lunas';
                                }
                                if($product['status'] == 3){
                                  echo "Lunas<br>"."tgl. lunas:".$product['tgllunas'];
                                }
                                if($product['status'] == 4){
                                  echo 'Dibatalkan';
                                }

                        ?></td>

                         <td class="right"><?php foreach ($product['action'] as $action) { ?>
                          <a class="badge bg-green" href="<?php echo $action['href']; ?>" <?php echo $action['text']=='Lihat Jurnal'?'target="_blank"':''; ?>><?php echo $action['text']; ?></a><br><p style="margin-bottom:5px"></p>
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
$('.sidebar-menu').find('#menu-salesorder').addClass('active');
</script>
<script>
$(function(){
  $('.date').datepicker({dateFormat: 'yy-mm-dd'});
})
</script>
<script type="text/javascript"><!--
function filter() {
	url = "index.php?route=sale/invoice&token=<?php echo $token; ?>";

	var filter_customer_id = $('select[name=\'filter_customer_id\']').val();

	if (filter_customer_id != '*') {
		url += '&filter_customer_id=' + encodeURIComponent(filter_customer_id);
	}

  var filter_gudang_id = $('select[name=\'filter_gudang_id\']').val();

	if (filter_gudang_id != '*') {
		url += '&filter_gudang_id=' + encodeURIComponent(filter_gudang_id);
	}

    var filter_status= $('select[name=\'filter_status\']').val();

	if (filter_status != '*') {
		url += '&filter_status=' + encodeURIComponent(filter_status);
	}

  var filter_tanggal = $('input[name=\'filter_tanggal\']').val();

	if (filter_tanggal) {
		url += '&filter_tanggal=' + encodeURIComponent(filter_tanggal);
	}
  var filter_order_id = $('select[name=\'filter_order_id\']').val();

	if (filter_order_id != '*') {
		url += '&filter_order_id=' + encodeURIComponent(filter_order_id);
	}


  /*var filter_shipping_method = $('select[name=\'filter_shipping_method\']').val();

	if (filter_shipping_method != '*') {
		url += '&filter_shipping_method=' + encodeURIComponent(filter_shipping_method);
	}
*/

 var filter_tanggalakhir = $('input[name=\'filter_tanggalakhir\']').val();

	if (filter_tanggalakhir) {
		url += '&filter_tanggalakhir=' + encodeURIComponent(filter_tanggalakhir);
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
    url:"index.php?route=sale/invoice/autocomplete&token=<?php echo $token; ?>",
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

  $(".jenisorder").select2({
    ajax: {
    url:"index.php?route=catalog/product/autocompleteprod&token=<?php echo $this->request->get['token']; ?>",
      dataType: 'json',
    data: function (params) {
      return {
        q: params.term

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
