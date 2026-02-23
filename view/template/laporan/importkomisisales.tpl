<?php echo $header; ?>
<!-- Modal -->
              <div id="myModal" class="modal fade" role="dialog">
                <div class="modal-dialog">

                  <!-- Modal content-->
                  <div class="modal-content">
                    <div class="modal-header">
                      <button type="button" class="close" data-dismiss="modal">&times;</button>
                      <h4 class="modal-title">Uplod file excel</h4>
                    </div>
                    <div class="modal-body">
                      <form action="<?php echo $uploadex ?>" method="post" name="frmExcelImport" id="frmExcelImport" enctype="multipart/form-data">
                          <div>
                              <label>Pilih File</label> <input type="file" name="file"
                                  id="file" accept=".xls,.xlsx">
                                  <br>
                              <button type="submit" id="submit" name="import"
                                  class="btn btn-primary btn-submit">Import</button>
                      
                          </div>
                      
                      </form>
                      <br>
                    </div>
                    <div class="modal-footer">
                      <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    </div>
                  </div>

                </div>
              </div>
                  <!-- Modal -->
              <div id="hargaterendah" class="modal fade" role="dialog">
                <div class="modal-dialog">

                  <!-- Modal content-->
                  <div class="modal-content">
                    <div class="modal-header">
                      <button type="button" class="close" data-dismiss="modal">&times;</button>
                      <h4 class="modal-title">Uplod file excel Harga Terendah</h4>
                    </div>
                    <div class="modal-body">
                      <form action="<?php echo $importhargaterendahnew ?>" method="post" name="frmExcelImport" id="frmExcelImport" enctype="multipart/form-data">
                          <div>
                              <label>Pilih File</label> <input type="file" name="file"
                                  id="file" accept=".xls,.xlsx">
                                  <br>
                              <button type="submit" id="submit" name="import"
                                  class="btn btn-primary btn-submit">Import</button>
                      
                          </div>
                      
                      </form>
                      <br>
                    </div>
                    <div class="modal-footer">
                      <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    </div>
                  </div>

                </div>
              </div>

              <!-- Modal Import Pelunasan -->
              <div id="importlunas" class="modal fade" role="dialog">
                <div class="modal-dialog">

                  <!-- Modal content-->
                  <div class="modal-content">
                    <div class="modal-header">
                      <button type="button" class="close" data-dismiss="modal">&times;</button>
                      <h4 class="modal-title">Uplod file </h4>
                    </div>
                    <div class="modal-body">
                      <form action="<?php echo $importlunas ?>" method="post" name="frmExcelImport" id="frmExcelImport" enctype="multipart/form-data">
                          <div>
                              <label>Pilih File</label> <input type="file" name="file"
                                  id="file" accept=".xls,.xlsx">
                                  <br>
                              <button type="submit" id="submit" name="import"
                                  class="btn btn-primary btn-submit">Import</button>
                      
                          </div>
                      
                      </form>
                      <br>
                    </div>
                    <div class="modal-footer">
                      <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    </div>
                  </div>

                </div>
              </div>
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
            <h3 class="box-title">Import Komisi Sales</h3>
            <div class="button pull-right">
                  <?php if($this->user->getUsername()=="pawit"){?>
                  <button type="button" class="btn btn-info" data-toggle="modal" data-target="#importlunas">Import Data Pelunasan</button>
                  <?php } ?>
                  <button type="button" class="btn btn-info" data-toggle="modal" data-target="#myModal">Import Data Penjualan</button>
                  <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#hargaterendah">Import Harga Terendah</button>
                  <a href="<?php echo $excel?>" class="btn btn-success" target="_blank">Download to Excel</a>
                  <a href="<?php echo $hapusinv?>" class="btn btn-warning">Hapus Invoice</a>
            </div>
          </div>
          <div class="box-body">
            <div class="row">
              <div class="col-md-2">
                  <div class="form-group">
                    <label>Tanggal Awal</label>
                    <input type="text" name="tanggal" value="<?php echo $filter_date_start?>" class="form-control datepicker"/>
                  </div>
              </div>
              <div class="col-md-2">
                  <div class="form-group">
                    <label>Tanggal Akhir</label>
                    <input type="text" name="tanggal2" value="<?php echo $filter_date_end?>" class="form-control datepicker"/>
                  </div>
              </div>
              <div class="col-md-2">
                  <div class="form-group">
                    <label>Gudang</label>
                    <select name="filter_gudang_id" class="form-control select">
                      <option value="*">Semua</option>
                      <option value="1" <?php echo $filter_gudang_id==1?'selected':''?>>Tangerang</option>
                      <option value="3" <?php echo $filter_gudang_id==3?'selected':''?>>Surabaya</option>
                    </select>
                  </div>
              </div>
              <div class="col-md-3">
                  <div class="form-group">
                    <label>Nama Sales</label>
                    <select name="filter_sales" class="form-control select">
                      <option value="*">Semua</option>
                      <?php foreach($saless as $s){?>
                        <option value="<?php echo $s['id']?>" <?php echo $s['id']==$filter_sales?'selected':'';?>><?php echo $s['namasales']?></option>
                      <?php } ?> 
                    </select>
                  </div>
              </div>
              <div class="col-md-2">
                  <div class="form-group">
                    <label>Status</label>
                    <select name="filter_status" class="form-control select">
                      <option value="*">Semua</option>
                      <option value="1" <?php echo $filter_status=='1'?'selected':''?>>Lunas</option>
                      <option value="2" <?php echo $filter_status=='2'?'selected':''?>>Belum Lunas</option>
                    </select>
                  </div>
              </div>
              <div class="col-md-1">
                  <div class="form-group">
                    <label>Filter</label><br>
                    <a onClick="filter()" class="btn btn-warning"><i class="fa fa-search"></i></a>
                  </div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-6">
                <?php //if($this->user->getUsername()=="pawit"){?>
                <div class="form-group">
                    <label>Total Invoice : </label>
                    <b><?php echo $is?></b>
                </div>
                <?php //} ?>
              </div>
            </div>
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
                <div>
                    <!--<label>Total Profit Margin Bersih : <?php echo $this->currency->format(0)?></label>-->
                </div>
                <table class="table table-bordered" id="MyTable">
                    <thead>
                      <tr>
                          <th>Tgl SO</th>
                          <th>Tgl Inv</th>
                          <th>Tgl Lunas</th>
                          <th>Nama Sales</th>
                          <th>Kode Customer</th>
                          <th>Nama Customer</th>
                          <th>Nama Barang</th>
                          <th>QTY</th>
                          <th>Poin Penjualan</th>
                          <th>Harga Satuan</th>
                          <th>Nilai Invoice</th>
                          <th>Harga Terendah</th>
                          <th>Total Profit Margin Kotor</th>
                          <th>Biaya Transport</th>
                          <th>Biaya Bunga Kredit</th>
                          <th>Invoice</th>
                          <th>Metode Pembayaran</th>
                          <th>Lama Bayar (Hari)</th>
                          <th>Status</th>
                          <th>Profit Margin Bersih</th>
                          <th>Keterangan</th>
                          <th> Profit Margin Kotor Setelah Pajak </th>
                          <th> Profit Margin Bersih</th>
                          <th>%</th>
                      <tr>
                    </thead>
                    <tbody>
                        <?php foreach($penjualans as $p){?>
                        <?php
                          $th=0; 
                          $tqty=0;
                          $tpoin=0;
                          $bersih=0;
                          foreach($p['products'] as $prd){
                            $th+=($prd['totalhargaterendah']);
                            $tqty+=($prd['qty']);
                            $tpoin+=($prd['poin']*$prd['qty']);
                                  //if($prd['status'] == 1 && $prd['tgllunas']<='2021-02-27'){
                                  if($prd['status'] == 1){
                                    $tanggal_lahir  = strtotime($prd['tglinvoice']);
                                    $sekarang    = strtotime($prd['tgllunas']); // Waktu sekarang
                                    $diff   = $sekarang - $tanggal_lahir;
                                    $h=floor($diff / (60 * 60 * 24)) . ' '; // Umur anda dalam hitungan hari
                                  }else{
                                    $tanggal_lahir  = strtotime($prd['tglinvoice']);
                                    $sekarang    = strtotime(date('Y-m-d')); // Waktu sekarang
                                    //$sekarang=strtotime('2021-02-27');
                                    $diff   = $sekarang - $tanggal_lahir;
                                    $h=floor($diff / (60 * 60 * 24)) . ' '; // Umur anda dalam hitungan hari
                                  }

                                  if($h>0){
                                    $biayabungakredit = round($th*0.025/30*$h);
                                  }else{
                                    $biayabungakredit=0;
                                  }

                                  $bersih=($p['total']-$th)-$p['bkirim']-$biayabungakredit;
                                  if($bersih>=0){
                                    $color="black";
                                  }else{
                                    $color="red";
                                  }
                          }
                          ?>
                          <tr style="background-color:#e3fbfc">
                              <?php 
                                $i=0;
                                if($p['tglinvoice']<'2022-04-01'){
                                  $totalprofitkotor=(($p['total']-$th)/1.1);
                                }else{
                                  $totalprofitkotor=(($p['total']-$th)/1.11);
                                }
                                
                                $bersihbaru=$totalprofitkotor-($p['bkirim']+$biayabungakredit);
                                  if($bersihbaru>=0){
                                    $color="black";
                                  }else{
                                    $color="red";
                                  }
                                ?>                              
                              <td></td>
                              <td><?php echo $p['tglinvoice']?></td>
                              <td><?php echo $p['tgllunas']=='1970-01-01'?'':$p['tgllunas']?></td>
                              <td>-</td>
                              <td><?php echo $p['kodecustomer']?></td>
                              <td><?php echo $p['namacustomer']?></td>
                              <td>-</td>
                              <td><?php echo $tqty?></td>
                              <td><?php echo $tpoin?></td>
                              <td></td>
                              <td><?php echo $this->currency->format($p['total']) ; ?></td>
                              <td><?php echo $this->currency->format($th) ; ?></td>
                              <td><?php echo $this->currency->format($p['total']-$th) ; ?></td>
                              <td><?php echo $this->currency->format($p['bkirim']);?></td>
                              <td><?php echo $this->currency->format($biayabungakredit);?></td>
                              <td></td>
                              <td></td>
                              <td><?php echo $h>=0?$h:0?></td>
                              <td></td>
                              <td style="color:<?php echo $color?>"><?php echo $this->currency->format($bersih);?></td>
                              <td><?php echo $p['customerbaru']=="Ya"?'Customer Baru':'';?></td>
                              <td><?php echo $this->currency->format(round($totalprofitkotor));?></td>
                              <td><?php echo $this->currency->format(round($bersihbaru));?></td>
                              <td><?php echo ($bersihbaru/$th) ?></td>
                              <?php foreach($p['products'] as $pr){?>
                              <tr>
                                <td><?php echo $pr['tglso']?></td>
                                <td><?php echo $pr['tglinvoice']?></td>
                                <td><?php echo $pr['tgllunas']=='1970-01-01'?'':$pr['tgllunas']?></td>
                                <td><?php echo $pr['namasales']?></td>
                                <td><?php echo $pr['kodecustomer']?></td>
                                <td><?php echo $pr['namacustomer']?></td>
                                <td><?php echo $pr['namabarang']?></td>
                                <td><?php echo $pr['qty']?></td>
                                <td><?php echo $pr['poin']*$pr['qty']?></td>
                                <td><?php echo $this->currency->format($pr['hargasatuan'])?></td>
                                <td></td>
                                <td><?php echo $this->currency->format($pr['harga_terendah'])?></td>
                                <td>0</td>
                                <td><?php //echo $pr['biayatransport'] ?></td>
                                <td>0</td>
                                <td><?php echo $pr['nomorinvoice']?></td>
                                <td><?php echo $pr['metodepembayaran']?></td>
                                <td>0</td>
                                <td>
                                  <?php
                                    if($pr['status']==1){
                                      echo "Lunas";
                                    }else{
                                      echo "Belum Lunas";
                                    }
                                    /*
                                    if($pr['status']==1 && $pr['tgllunas']<='2021-02-27'){
                                      echo "Lunas";
                                    }else{
                                      echo "Belum Lunas";
                                    }
                                    */
                                    ?>
                                </td>
                                <td>0</td>
                                <td></td>
                              </tr>
                              <?php } ?>
                          </tr>                     
                        <?php } ?>
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
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.20/css/jquery.dataTables.css">
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.js"></script>
<script>
$(document).ready( function () {
  $('.datepicker').datepicker({dateFormat: 'yy-mm-dd'});
    $('#myTable').DataTable( {
        "lengthChange": false,
        "bPaginate": false,
        "bFilter": false,
    } );
} );
</script>
<script>
$('.sidebar-menu').find('#menu-laporan').addClass('active');
function show(id){
  //alert(id);
   $('.collapse'+id).show();
   $('#plus'+id).hide();
   $('#minus'+id).show();
}
function hide(id){
  //alert(id);
   $('.collapse'+id).hide();
   $('#minus'+id).hide();
   $('#plus'+id).show();
}
$(function(){
  $('.date').datepicker({dateFormat: 'yy-mm-dd'});
  $('.list-product').hide();
  $('.total-transaksi').hide();

  $(".invoice").on('click',function(){
    id=$(this).data('id');
    faktur=$(this).data('faktur');

    $("#display-faktur").html(faktur);
    $(".invoice td").css('background-color','#fff');
    $(".invoice td").css('font-weight','normal');
    //$("#list-invoice-"+id+" td").css('background-color','#ccc');
    $("#list-invoice-"+id+" td").css('font-weight','bold');

    $('.list-product').hide();
    $('.total-transaksi').hide();

    $("#list"+id).show();
    $("#total"+id).show();
  });
});
$(".select").select2({
    //width: 'resolve' // need to override the changed default
    theme:"bootstrap"
});
$(".sales").select2({
    ajax: {
    url:"index.php?route=user/user/autocomplete&token=<?php echo $this->request->get['token']; ?>",
      dataType: 'json',
    data: function (params) {
      return {
        q: params.term,
        j:21

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
</script>
<script type="text/javascript"><!--
function filter() {
	url = "index.php?route=laporan/importkomisisales&token=<?php echo $token; ?>";

  var filter_gudang_id = $('select[name=\'filter_gudang_id\']').val();

	if (filter_gudang_id != '*') {
		url += '&filter_gudang_id=' + encodeURIComponent(filter_gudang_id);
	}

  var filter_sales= $('select[name=\'filter_sales\']').val();

	if (filter_sales != '*') {
		url += '&filter_sales=' + encodeURIComponent(filter_sales);
	}

  var filter_date_start = $('input[name=\'tanggal\']').val();

	if (filter_date_start) {
		url += '&filter_date_start=' + encodeURIComponent(filter_date_start);
	}

  var filter_date_end = $('input[name=\'tanggal2\']').val();

	if (filter_date_end) {
		url += '&filter_date_end=' + encodeURIComponent(filter_date_end);
	}

  var filter_status = $('select[name=\'filter_status\']').val();

	if (filter_status != '*') {
		url += '&filter_status=' + encodeURIComponent(filter_status);
	}

	location = url;
}
function cetak() {
	//url = "index.php?route=laporan/penjualandetail&print=1&token=<?php echo $token; ?>";
  url ="<?php echo $cetak ?>";

	var filter_customer_id = $('select[name=\'filter_customer_id\']').val();

	if (filter_customer_id != '*') {
		url += '&filter_customer_id=' + encodeURIComponent(filter_customer_id);
	}

  var filter_gudang_id = $('select[name=\'filter_gudang_id\']').val();

	if (filter_gudang_id != '*') {
		url += '&filter_gudang_id=' + encodeURIComponent(filter_gudang_id);
	}

  /*  var filter_status= $('select[name=\'filter_status\']').val();

	if (filter_status != '*') {
		url += '&filter_status=' + encodeURIComponent(filter_status);
	}*/

  var filter_date_start = $('input[name=\'filter_date_start\']').val();

	if (filter_date_start) {
		url += '&filter_date_start=' + encodeURIComponent(filter_date_start);
	}

  var filter_date_end = $('input[name=\'filter_date_end\']').val();

	if (filter_date_end) {
		url += '&filter_date_end=' + encodeURIComponent(filter_date_end);
	}

  var filter_statuss = $("#status input:checkbox:checked").map(function(){
      return $(this).val();
    }).get(); // <----

    //alert(JSON.stringify(filter_statuss));
    url+='&filter_status=' +filter_statuss;

  /*var filter_shipping_method = $('select[name=\'filter_shipping_method\']').val();

	if (filter_shipping_method != '*') {
		url += '&filter_shipping_method=' + encodeURIComponent(filter_shipping_method);
	}
*/


  window.open(
  url,
  '_blank' // <- This is what makes it open in a new window.
  );
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
<script>
function detail(id,faktur){
  //alert(id);
  $("#display-faktur").html(faktur);
  $(".invoice td").css('background-color','#fff');
  $(".invoice td").css('font-weight','normal');
  $("#list-invoice-"+id+" td").css('background-color','#ccc');
  $("#list-invoice-"+id+" td").css('font-weight','bold');

  $('.list-product').hide();
  $('.total-transaksi').hide();

  $("#list"+id).show();
  $("#total"+id).show();
}
</script>
<?php echo $footer; ?>
