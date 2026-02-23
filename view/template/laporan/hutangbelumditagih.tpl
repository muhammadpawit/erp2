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
            <h3 class="box-title">Laporan Hutang Belum ditagih</h3>
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
              <div class="col-md-4">
                <table class="table table-stripped">
                    <thead>
                      <tr>
                        <td>Tanggal Awal</td>
                        <td><input type="text" class="form-control" placeholder="Tanggal Awal" name="filter_date_start" value="<?php echo $filter_date_start; ?>" id="date-start" size="12"  /></td>
                      </tr>
                      <tr>
                        <td>Tanggal Akhir</td>
                        <td><input type="text" class="form-control" placeholder="Tanggal Akhir" name="filter_date_end" value="<?php echo $filter_date_end; ?>" id="date-end" size="12"  /></td>
                      </tr>
                       <!--<tr>
                        <td>Nomor Faktur</td>
                        <td>
                          <select name="filter_no_faktur" class="form-control nosurat">

                          </select>
                        </td>
                      </tr>
                     -->
                      <tr>
                        <td>Nama Vendor</td>
                        <td>
                          <select style="width:200px" name="filter_vendor" class="vendor">
                            <option value="*">Semua Vendor</option>
                          </select>
                        </td>
                      </tr>
                      <tr>
                        <td>Nama Gudang</td>
                        <td>
                          <select style="width:200px" name="filter_gudang_id" class="form-control">
                            <option value="*">Semua</option>
                            <option value="1">Tangerang</option>
                            <option value="3">Surabaya</option>
                          </select>
                        </td>
                      </tr>
                      <tr>
                        <td></td>
                        <td><a onclick="filter();" class="btn btn-info">Filter</a></td>
                      </tr>
                    </thead>
                    <tbody>
                    
                  </tbody>
                  </table>
              </div>
            </div>

            <div class="row">
              <div class="col-md-12">
                <table class="table table-bordered datatables">
                    <thead>
                      <tr>
                        <th>No</th>
                        <th>Tanggal</th>
                        <th>Gudang</th>
                        <th>Nama Supplier</th>
                        <th>No.Surat Jalan</th>
                        <th>No.PO</th>
                        <th>Total</th>
                        <th>Keterangan</th>
                        <th></th>
                      </tr>
                    </thead>
                  </table>

              </div>
            </div>

          </div>
          <div class="box-footer">
            <div class="row">
              <div class="col-md-12">
                <table class="table">
                  <tr>
                      <td><b>Total</b></td>
                      <td><?php echo $total?></td>
                  </tr>
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
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/dt/dt-1.10.21/datatables.min.css"/>
<script type="text/javascript" src="https://cdn.datatables.net/v/dt/dt-1.10.21/datatables.min.js"></script>
<script>
$('.sidebar-menu').find('#menu-pembelian').addClass('active');
$('.sidebar-menu').find('#menu-pembelian-kredit').addClass('active');

$(function(){
  $(".nosurat").select2({
    ajax: {
    url:"index.php?route=pembelian/invoicepembeliandagang/autocomplete&token=<?php echo $this->request->get['token']; ?>",
    dataType: 'json',
    data: function (params) {
      return {
        q: params.term,
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
})
$(".suratpermintaan").select2({
  ajax: {
  url:"index.php?route=pembelian/permintaanpembelian/autocomplete&token=<?php echo $this->request->get['token']; ?>",
  dataType: 'json',
  data: function (params) {
    return {
      q: params.term,
      j: 2,
      status:5,
      s:1// search term

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
})
  $(".vendor").select2({
    ajax: {
    url:"index.php?route=catalog/vendorlokal/autocomplete&token=<?php echo $this->request->get['token']; ?>",
    dataType: 'json',
    data: function (params) {
      return {
        filter_name: params.term
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

</script>
<script type="text/javascript"><!--
$(document).ready(function() {
	$('#date-start').datepicker({dateFormat: 'yy-mm-dd'});

	$('#date-end').datepicker({dateFormat: 'yy-mm-dd'});
});
//--></script>
<script type="text/javascript"><!--
function filter() {
	url = 'index.php?route=laporan/hutangbelumditagih&token=<?php echo $token; ?>';

  var filter_date_start = $('input[name=\'filter_date_start\']').val();

	if (filter_date_start) {
		url += '&filter_date_start=' + encodeURIComponent(filter_date_start);
	}

  var filter_date_end = $('input[name=\'filter_date_end\']').val();

	if (filter_date_end) {
		url += '&filter_date_end=' + encodeURIComponent(filter_date_end);
	}

	var filter_no_faktur = $('select[name=\'filter_no_faktur\']').val();

	if (filter_no_faktur != '*' & filter_no_faktur != null) {
		url += '&filter_no_faktur=' + encodeURIComponent(filter_no_faktur);
	}
  var filter_vendor = $('select[name=\'filter_vendor\']').val();

	if (filter_vendor != '*' & filter_vendor != null) {
		url += '&filter_vendor=' + encodeURIComponent(filter_vendor);
	}


  var filter_status = $('select[name=\'filter_status\']').val();

	if (filter_status != '*' & filter_status != null) {
		url += '&filter_status=' + encodeURIComponent(filter_status);
	}

  location = url;
}
//--></script>
<script type="text/javascript"><!--
$(document).ready(function() {
	$('.date').datepicker({dateFormat: 'yy-mm-dd'});
  url = 'index.php?route=laporan/hutangbelumditagih/serversidebaru&token=<?php echo $token; ?>';

  var filter_date_start = $('input[name=\'filter_date_start\']').val();

	if (filter_date_start) {
		url += '&filter_date_start=' + encodeURIComponent(filter_date_start);
	}

  var filter_date_end = $('input[name=\'filter_date_end\']').val();

	if (filter_date_end) {
		url += '&filter_date_end=' + encodeURIComponent(filter_date_end);
	}

	var filter_no_faktur = $('select[name=\'filter_no_faktur\']').val();

	if (filter_no_faktur != '*' & filter_no_faktur != null) {
		url += '&filter_no_faktur=' + encodeURIComponent(filter_no_faktur);
	}
  var filter_vendor ='<?php echo $_REQUEST["filter_vendor"]?>';

	if (filter_vendor != '*' & filter_vendor != null) {
		url += '&filter_vendor=' + encodeURIComponent(filter_vendor);
	}


  var filter_status = $('select[name=\'filter_status\']').val();

	if (filter_status != '*' & filter_status != null) {
		url += '&filter_status=' + encodeURIComponent(filter_status);
	}
    $('.datatables').DataTable( {
        //"paging":   false,
        "ordering": false,
        //"info":     false,
        "searching":true,
        //"ajax": "index.php?route=laporan/hutang/serversidebaru&token=<?php echo $this->request->get['token']; ?>"
        "ajax":url
    });
    $(".dataTables_length").hide();
    console.log(url);
});
//--></script>
<script type="text/javascript"><!--
$('#form input').keydown(function(e) {
	if (e.keyCode == 13) {
		filter();
	}
});
//--></script>
<?php echo $footer; ?>
