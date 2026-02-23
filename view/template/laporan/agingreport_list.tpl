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
            <h3 class="box-title">Aging Report</h3>
            <div class="button pull-right">

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
              <div class="col-md-4 col-xs-12">
                <table class="table table-stripped">
                  <tr>
                    <td>Customer</td>
                    <td>
                    <select name="filter_customer_id" class="form-control lokasi-pameran">
                      <option value="*" <?php echo empty($filter_customer_id)?'selected':'';?>>Semua Customer</option>


                    </select>
                  </td>
                  </tr>
                  <tr>
                    <td>Tanggal</td>
                    <td> <input type="text" class="form-control date" readonly name="filter_tanggal" value="<?php echo $filter_tanggal; ?>" /></td>
                  </tr>
                  <tr>
                    <td>Perhitungan Berdasar</td>
                    <td>
                      <select class="form-control" name="filter_type">
                        <option value="1" <?php echo $filter_type==1?'selected':''; ?>>Tanggal Jatuh Tempo</option>
                        <option value="2" <?php echo $filter_type==2?'selected':''; ?>>Tanggal Invoice</option>
                      </select>
                    </td>
                  </tr>
                  <tr>
                    <td>Gudang</td>
                    <td>
                      <select class="form-control" name="filter_gudang_id">
                        <option value="*">Semua Lokasi</option>
                        <?php
                        foreach($gudangs as $g){
                        ?>
                          <option value="<?php echo $g['gudang_id']; ?>" <?php echo $g['gudang_id']==$filter_gudang_id?'selected':''; ?>><?php echo $g['nama']; ?></option>
                        <?php
                        }
                        ?>
                      </select>
                    </td>
                  </tr>
                    <tr>
                    <td><a onclick="filter();" class="btn btn-info">Filter</a></td>
                    <td><a onclick="cetak()" class="btn btn-info">Cetak</a> <!--<a onclick="cetakexcel()" class="btn btn-info">Cetak Excel</a>--></td>
                  </tr>

                </table>

              </div>
              <div class="col-md-8 col-xs-12" style="max-height:300px;overflow-y:scroll">
                <p>*) Klik untuk melihat rincian invoice</p>
                <form action="<?php echo $delete; ?>" method="post" enctype="multipart/form-data" id="form">
                  <table class="table table-bordered">
                    <thead>
                      <tr>
                        <th class="left">
                          <?php if ($sort == 'customer.name') { ?>
                            <a href="<?php echo $sort_customer; ?>" class="<?php echo strtolower($order); ?>">Nama Customer</a>
                            <?php } else { ?>
                            <a href="<?php echo $sort_customer; ?>">Nama Customer</a>
                            <?php } ?>
                        </th>

                        <th class="left">
                          <?php if ($sort == 'invoice.totaltagihan') { ?>
                            <a href="<?php echo $sort_tagihan; ?>" class="<?php echo strtolower($order); ?>">Jumlah</a>
                            <?php } else { ?>
                            <a href="<?php echo $sort_tagihan; ?>">Jumlah</a>
                            <?php } ?>
                        </th>
                        <th>Deposit</th>
                        <th class="left">
                          Sisa Harus Bayar
                        </th>
                        <th>0-15</th>
                        <th>16-30</th>
                        <th>31-60</th>
                        <th>61-90</th>
                        <th>91-120</th>
                        <th>>120</th>
                        <th>Follow Up terakhir</th>
                        <th>Sales</th>
                      </tr>
                      </tr>
                    </thead>
                    <tbody>


                      <?php if ($penjualans) { ?>
                      <?php foreach ($penjualans as $product) { ?>
                      <tr class="invoice" data-id="<?php echo $product['customer_id']; ?>" data-faktur="<?php echo $product['name']; ?>" id="list-invoice-<?php echo $product['customer_id']; ?>" data-jadwal="<?php if(isset($product['jadwal'])){ ?>
                    <?php if(!empty($product['jadwal'])){ ?>
                      Jadwal Penagihan :
                      <?php
                        foreach($product['jadwal'] as $h){
                          echo $h['namahari'].", ";
                        }
                      ?><br>

                    <?php } ?>
                  <?php } ?>
                  <?php if(isset($product['jadwal_penagihan'])){ ?>
                    <?php if(!empty($product['jadwal_penagihan'])){ ?>
                      <?php
                        foreach($product['jadwal_penagihan'] as $h){
                          echo "Jam Penagihan : ".$h['jam_penagihan']."<br>";
                          echo "Cara Penagihan : ".$h['cara_penagihan']."<br>";
                        }
                      ?><br>
                      
                    <?php } ?>
                  <?php } ?>
                  ">
                        <td class="left"><?php echo $product['name']; ?>
                        </td>
                        <td><?php echo $product['totaltagihan']; ?></td>
                        <td><?php echo $product['deposit']; ?></td>
                        <td>
                          <?php if($this->user->getUsername()=="pawitx"){?>
                            <?php echo $product['historybayar']; ?>
                          <?php } ?>
                          <?php echo $product['sisabayar']; ?>
                        </td>
                        <td><?php echo $product['sisabayar15']; ?></td>
                        <td><?php echo $product['sisabayar30']; ?></td>
                        <td><?php echo $product['sisabayar60']; ?></td>
                        <td><?php echo $product['sisabayar90']; ?></td>
                        <td><?php echo $product['sisabayar120']; ?></td>
                        <td><?php echo $product['sisabayar121']; ?></td>
                        <td><small><?php echo $product['followup']; ?></small></td>
                        <td><?php echo $product['sales']; ?></td>

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
            <div class="row">
              <div class="col-md-12">
                <div class="pull-right" style="margin-top:30px;margin-bottom:30px"><?php echo $pagination; ?></div>
              </div>
            </div>
            <div class="row">
              <div class="col-xs-12 table-responsive" >
                <div class="callout callout-success lead">
                  <h4>Rincian Invoice <span id="display-faktur"></span></h4>
                  <span id="jadwal"></span>
                </div>
                <table class="table table-bordered">
                  <thead>
                    <th>No. Faktur</th>
                    <th>Total</th>
                    <th>Sisa Belum Bayar</th>
                    <th>Tanggal Invoice</th>
                    <th>Jatuh Tempo</th>
                    <th>t.Hari</th>
                    <th>0-15</th>
                    <th>16-30</th>
                    <th>31-60</th>
                    <th>61-90</th>
                    <th>91-120</th>
                    <th> > 120</th>
                  </thead>
                  <?php if ($penjualans) { ?>
                  <?php foreach ($penjualans as $p) {
                    $deposit=$p['plaindeposit'];
                    ?>
                  <tbody class="list-product" id="list<?php echo $p['customer_id']; ?>">
                    <?php
                      foreach($p['invoice'] as $product){
                        $totalhari=0;
                        $dateadd=strtotime($filter_tanggal);
                  			$tglinvoice=strtotime($product['date_added']);
                  			$jatuhtempo=strtotime($product['jatuhtempo']);

                  			if($filter_type == 1){
                  				$selisih=$dateadd - $jatuhtempo;
                  				$totalhari=floor($selisih / (60 * 60 * 24));
                  			}else{
                  				$selisih=$dateadd - $tglinvoice;
                  				$totalhari=floor($selisih / (60 * 60 * 24));
                  			}

                        $tagihan=$product['totaltagihan']-$product['totalbayar'];
                        /*if($deposit > 0){

                          if($tagihan >= $deposit){
                            $tagihan -= $deposit;
                            $deposit=0;
                          }else{
                            $deposit -= $tagihan;
                            $tagihan=0;
                          }
                        }*/
                        //if(){}
                    ?>
                    <tr >
                      <td><a target="_blank" href="<?php echo $this->url->link('sale/invoice/tampil', 'token=' . $this->session->data['token'].'&view=1&order_id='.$product['id'], 'SSL'); ?>"><?php echo $product['no_faktur']; ?></a></td>
                      <td><?php echo $this->currency->format($product['totaltagihan']); ?></td>
                      <td><?php echo $this->currency->format($tagihan); ?></td>
                      <td><?php echo date('d/m/y',strtotime($product['date_added'])); ?></td>
                      <td><?php echo date('d/m/y',strtotime($product['jatuhtempo'])); ?></td>
                      <td><?php echo $totalhari; ?></td>
                      <td><?php
                        if($totalhari <= 15){
                          echo $this->currency->format($tagihan);
                        }
                         ?></td>
                     <td><?php
                       if($totalhari > 15 & $totalhari <=30){
                         echo $this->currency->format($tagihan);
                       }
                        ?></td>
                     <td><?php
                       if($totalhari > 30 & $totalhari <=60){
                         echo $this->currency->format($tagihan);
                       }
                        ?></td>
                      <td><?php
                        if($totalhari > 60 & $totalhari <=90){
                          echo $this->currency->format($tagihan);
                        }
                         ?></td>
                     <td><?php
                       if($totalhari > 90 & $totalhari <=120){
                         echo $this->currency->format($tagihan);
                       }
                        ?></td>
                      <td><?php
                        if($totalhari > 120){
                          echo $this->currency->format($tagihan);
                        }
                         ?></td>
                    </tr>
                  <?php
                  }
                  ?>
                  </tbody>
                  <?php
                  }}
                  ?>
                </table>
              </div>

            </div>


          </div>
          <div class="box-footer">
            <div class="row">
              <div class="col-xs-12 table-responsive" >
                <div class="callout callout-warning lead">
                  <h4>Total</h4>

                </div>
                <table class="table table-bordered">
                  <thead>
                    <th>Total Tagihan</th>
                    <th>Total Deposit</th>
                    <th>Total Belum Bayar <small>(setelah dikurangi deposit)</small></th>
                    <th>0-15</th>
                    <th>16-30</th>
                    <th>31-60</th>
                    <th>61-90</th>
                    <th>91-120</th>
                    <th> > 120</th>
                  </thead>

                  <tbody >
                    <tr>
                      <td><?php echo $this->currency->format($jumlah['totaltagihan']); ?></td>
                      <td><?php echo $this->currency->format($jumlah['totaldeposit']); ?></td>
                      <td><?php echo $this->currency->format($jumlah['total']); ?></td>
                      <td><?php echo $jumlah15; ?></td>
                      <td><?php echo $jumlah30; ?></td>
                      <td><?php echo $jumlah60; ?></td>
                      <td><?php echo $jumlah90; ?></td>
                      <td><?php echo $jumlah120; ?></td>
                      <td><?php echo $jumlah121; ?></td>
                    </tr>

                  </tbody>

                </table>
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
$('.sidebar-menu').find('#menu-laporan').addClass('active');
$('body').addClass('sidebar-collapse');

</script>
<script>
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
    $("#list-invoice-"+id+" td").css('background-color','#ccc');
    $("#list-invoice-"+id+" td").css('font-weight','bold');

    $('.list-product').hide();
    $('.total-transaksi').hide();

    $("#list"+id).show();
    $("#total"+id).show();
    jadwal=$(this).data('jadwal');
    $("#jadwal").html(jadwal);
  });
})
</script>
<script type="text/javascript"><!--
function filter() {
	url = "index.php?route=laporan/agingreport&token=<?php echo $token; ?>";

	var filter_customer_id = $('select[name=\'filter_customer_id\']').val();

	if (filter_customer_id != '*') {
		url += '&filter_customer_id=' + encodeURIComponent(filter_customer_id);
	}

  var filter_type = $('select[name=\'filter_type\']').val();

	if (filter_type != '*') {
		url += '&filter_type=' + encodeURIComponent(filter_type);
	}

  var filter_gudang_id = $('select[name=\'filter_gudang_id\']').val();

	if (filter_gudang_id != '*') {
		url += '&filter_gudang_id=' + encodeURIComponent(filter_gudang_id);
	}

  var filter_tanggal = $('input[name=\'filter_tanggal\']').val();

	if (filter_tanggal) {
		url += '&filter_tanggal=' + encodeURIComponent(filter_tanggal);
	}




	location = url;
}
function cetak() {
	url = "index.php?route=laporan/agingreport&print=1&token=<?php echo $token; ?>";

	var filter_customer_id = $('select[name=\'filter_customer_id\']').val();

  if (filter_customer_id != '*') {
    url += '&filter_customer_id=' + encodeURIComponent(filter_customer_id);
  }

  var filter_type = $('select[name=\'filter_type\']').val();

  if (filter_type != '*') {
    url += '&filter_type=' + encodeURIComponent(filter_type);
  }

  var filter_gudang_id = $('select[name=\'filter_gudang_id\']').val();
  
  if (filter_gudang_id != '*') {
    url += '&filter_gudang_id=' + encodeURIComponent(filter_gudang_id);
  }

  var filter_tanggal = $('input[name=\'filter_tanggal\']').val();

  if (filter_tanggal) {
    url += '&filter_tanggal=' + encodeURIComponent(filter_tanggal);
  }



	//location = url;
  window.open(
  url,
  '_blank' // <- This is what makes it open in a new window.
);
}
function cetakexcel() {
	url = "index.php?route=laporan/agingreport&excel=1&token=<?php echo $token; ?>";

	var filter_customer_id = $('select[name=\'filter_customer_id\']').val();

	if (filter_customer_id != '*') {
		url += '&filter_customer_id=' + encodeURIComponent(filter_customer_id);
	}

  var filter_type = $('select[name=\'filter_type\']').val();

	if (filter_type != '*') {
		url += '&filter_type=' + encodeURIComponent(filter_type);
	}

  var filter_gudang_id = $('select[name=\'filter_gudang_id\']').val();

	if (filter_gudang_id != '*') {
		url += '&filter_gudang_id=' + encodeURIComponent(filter_gudang_id);
	}
  var filter_tanggal = $('input[name=\'filter_tanggal\']').val();

	if (filter_tanggal) {
		url += '&filter_tanggal=' + encodeURIComponent(filter_tanggal);
	}



	//location = url;
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

<?php echo $footer; ?>
