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
            <h3 class="box-title">Rincian Penjualan & Pengiriman Barang Dagang</h3>
            <div class="button pull-right">
									<!--<a href="<?php echo $cetak; ?>" target="_blank"><button type="button" class="btn btn-success">Cetak</button></a>-->
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
              <div class="col-md-4 col-xs-12">
                <table class="table table-stripped">
                    <tr>
                      <td>Tanggal Awal</td>
                      <td><input type="text" readonly class="form-control" id="date-start" name="filter_date_start" value="<?php echo $filter_date_start; ?>"></td>
                    </tr>
                    <tr>
                      <td>Tanggal Akhir</td>
                      <td><input type="text" readonly class="form-control" id="date-end" name="filter_date_end" value="<?php echo $filter_date_end; ?>"></td>
                    </tr>
                    <tr>
                      <td>Gudang</td>
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
                    </tr>
                    <tr>
                      <td>Nama Produk</td>
                      <td><input type="text" class="form-control" name="filter_product_name" value="<?php echo $filter_name; ?>" />
                      </td>
                    </tr>
                    <tr>
                      <td colspan="2"><a onclick="filter();" class="btn btn-info">Filter</a></td>

                    </tr>

                  </table>
              </div>
              <div class="col-md-8 col-xs-12" style="height:400px;overflow-y:scroll">
                <div class="callout callout-warning lead">
                  <h4>Daftar Sales Order</h4>

                </div>
                <p>*Klik baris untuk melihat detail SJ dan INVOICE</p>
                <table class="table table-bordered">
                    <thead>
                      <tr>
                        <th class="left">Gudang</th>
                        <th class="left">No. SO</th>
                        <th class="left">Customer</th>
                        <th class="left">Nama Produk</th>
          				      <th class="right">Qty Terjual</th>
                        <th class="right">Harga</th>
                        <th class="right">Pajak</th>
                        <th class="right">Total</th>

                      </tr>
                    </thead>
                    <tbody>

                      <?php if ($products) { ?>
                      <?php foreach ($products as $product) { ?>
                      <tr class="product" id="product-<?php echo $product['product_id']?>-<?php echo $product['gudang_id']; ?>" data-id="<?php echo $product['product_id']?>-<?php echo $product['gudang_id']; ?>" data-nama="<?php echo $product['name']?>" data-gudang="<?php echo $product['nama']?>">
                        <td class="left"><?php echo $product['nama'];
                          ?></td>
                        <td class="left"><?php echo $product['name']; ?></td>
          			            <td class="right"><?php echo $product['quantity']; ?></td>
                            <td class="right"><?php echo $product['price']; ?></td>
                            <td class="right"><?php echo $product['pajak']; ?></td>
                            <td class="right"><?php echo $product['total']; ?></td>

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
            <div class="row">
              <div class="col-md-12">
                <div class="pull-right" style="margin-bottom:30px;margin-top:30px;"><?php echo $pagination; ?></div>
              </div>
            </div>

            <div class="row">
              <div class="col-md-6 col-xs-12">
                <div class="callout callout-success lead">
                  <h4>Total Penjualan</h4>

                </div>
                <table class="table table-bordered">
                  <thead>
                    <th class="left">Gudang</th>
                    <th class="right">Qty Terjual</th>
                    <th class="right">Harga</th>
                    <th class="right">Pajak</th>
                    <th class="right">Total</th>
                  </thead>
                  <tbody>
                    <?php
                    $qty=0;
                    $price=0;
                    $pajak=0;
                    $total=0;
                    foreach ($penjualangudang as $product) {
                      $qty += $product['quantity'];
                      $price += $product['price'];
                      $pajak += $product['pajak'];
                      $total += $product['total'];
                      ?>
                      <tr>
                      <td class="left"><?php echo $product['nama']; ?></td>
                          <td class="right"><?php echo $product['quantity']; ?></td>
                          <td class="right"><?php echo $this->currency->format($product['price']); ?></td>
                          <td class="right"><?php echo $this->currency->format($product['pajak']); ?></td>
                          <td class="right"><?php echo $this->currency->format($product['total']); ?></td>
                      </tr>
                    <?php
                    }
                    ?>
                    <tr>
                      <td><b>Total</b></td>
                      <td class="right"><b><?php echo $qty; ?></b></td>
                      <td class="right"><b><?php echo $this->currency->format($price); ?></b></td>
                      <td class="right"><b><?php echo $this->currency->format($pajak); ?></b></td>
                      <td class="right"><b><?php echo $this->currency->format($total); ?></b></td>
                    </tr>
                  </tbody>
                </table>
              </div>
              <div class="col-md-6 col-xs-12" style="max-height:300px;overflow-y:scroll">
                <div class="callout callout-warning lead">
                  <h4>Daftar SO <span id="display-faktur"></span></h4>

                </div>
                <table class="table table-bordered">
                  <thead>
                    <th class="left">Tanggal SO</th>
                    <th class="right">No. SO</th>
                    <th class="right">Qty</th>
                    <th class="right">Qty Terima</th>
                    <th class="right">Proforma Invoice</th>
                    <th class="right">DP Invoice</th>
                    <th class="right">Surat Jalan</th>

                  </thead>
                  <?php
                  foreach($products as $p){
                    foreach($p['listso'] as $l){
                    ?>
                    <tbody>
                      <tr class="listso listso-<?php echo $p['product_id']?>-<?php echo $p['gudang_id']?>">
                        <td><?php echo $l['date_added']?></td>
                        <td><a target="_blank" href="<?php echo $this->url->link('sale/salesorder', 'token=' . $this->session->data['token'].'&filter_order_id='.$l['sales_order_id'], 'SSL'); ?>"><?php echo $l['no_so']?></a></td>
                        <td><?php echo $l['quantity']?></td>
                        <td><?php echo $l['quantityterima']?></td>
                        <td><?php echo !empty($l['pinv'])?$lpinv['no_faktur'].'('.$this->curency->format($l['pinv']['totaltagihan']).')':''; ?></td>
                        <td><?php echo !empty($l['dpinv'])?$ldpinv['no_faktur'].'('.$this->curency->format($l['dpinv']['totaltagihan']).')':''; ?></td>
                        <td><?php
                          foreach($l['sj'] as $s){
                            $status='';
                            echo '<b>'.$s['date_added'].'<br>';
                            ?>
                            <a target="_blank" href="<?php echo $this->url->link('sale/penjualan', 'token=' . $this->session->data['token'].'&filter_order_id='.$s['id'], 'SSL'); ?>"><?php echo $s['no_sj'].'</b><br>'; ?></a>
                            <?php
                            if(!empty($s['invoice'])){
                              if($s['invoice']['status'] == 1){
                                $status='Ditagih';
                              }
                              if($s['invoice']['status'] == 2){
                                $status='Belum Lunas';
                              }
                              if($s['invoice']['status'] == 3){
                                $status='Lunas';
                              }
                              echo ' ('.$s['invoice']['no_faktur'].' '.$status.')<br>';
                            }
                          }
                          ?></td>

                      </tr>
                    </tbody>
                    <?php
                    }
                  }
                  ?>
                </table>
              </div>
            </div>
            <div class="row">

            </div>

          </div>
          <div class="box-footer">

          </div>
        </div>
      </div>
    </div>
  </section>
</div>
</div>
<script type="text/javascript"><!--
$(document).ready(function() {
	$('#date-start').datepicker({dateFormat: 'yy-mm-dd'});

	$('#date-end').datepicker({dateFormat: 'yy-mm-dd'});
  $('.listso').hide();

  $(".product").on('click',function(){
    id=$(this).data('id');
    name=$(this).data('nama');
    gudang=$(this).data('gudang');

    $("#display-faktur").html(name+' Gudang '+gudang);
    $(".product td").css('background-color','#fff');
    $(".product td").css('font-weight','normal');
    $("#product-"+id+" td").css('background-color','#ccc');
    $("#product-"+id+" td").find("td").css('font-weight','bold');

    $('.listso').hide();


    $(".listso-"+id).show();

  });
});
//--></script>
<script>
$('.sidebar-menu').find('#menu-laporan').addClass('active');

$(function(){
  $(".select-ads").select2({


      theme:"bootstrap"
    });
})
</script>
<script type="text/javascript"><!--
function filter() {
	url = 'index.php?route=laporan/penjualan&token=<?php echo $token; ?>';

  var filter_date_start = $('input[name=\'filter_date_start\']').val();

	if (filter_date_start) {
		url += '&filter_date_start=' + encodeURIComponent(filter_date_start);
	}

  var filter_date_end = $('input[name=\'filter_date_end\']').val();

	if (filter_date_end) {
		url += '&filter_date_end=' + encodeURIComponent(filter_date_end);
	}

	var filter_name = $('input[name=\'filter_product_name\']').val();

	if (filter_name) {
		url += '&filter_name=' + encodeURIComponent(filter_name);
	}

var filter_gudang_id = $('select[name=\'filter_gudang_id\']').val();

	if (filter_gudang_id !="*") {
		url += '&filter_gudang_id=' + filter_gudang_id;
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
