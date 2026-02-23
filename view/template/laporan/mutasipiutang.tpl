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
            <h3 class="box-title">Laporan Mutasi Piutang</h3>
            <div class="button pull-right">
              <table class="table">
                  <tr>
                    <th>Hal. Awal</th>
                    <th>Hal. Akhir</th>
                    <th></th>
                  </tr>
                  <tr>
                    <td>
                      <select name="filter_page_start" class="form-control">
                        <?php
                        for($i=1;$i<=$totalpage;$i++){
                        ?>
                          <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
                        <?php
                        }
                        ?>
                      </select>
                    </td>
                    <td>
                      <select name="filter_page_end" class="form-control">
                        <?php
                        for($i=1;$i<=$totalpage;$i++){
                        ?>
                          <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
                        <?php
                        }
                        ?>
                      </select>
                    </td>
                    <td>
                      <a onclick="cetak()"><button type="button" class="btn btn-success">Export to Excel</button></a>
                    </td>
                  </tr>
                </table>
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

                <?php if ($error_warning) { ?>
                <div class="alert alert-danger alert-dismissible">
                  <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                  <h4><i class="icon fa fa-check"></i> Alert!</h4>
                  <?php echo $error_warning; ?>
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
                        <th>Customer</th>
                        <th>Tanggal Awal</th>
                        <th>Tanggal Akhir</th>
                        <th></td>
                      </tr>
                    </thead>
                    <tbody>
                    <tr>
                    <td><input type="text" class="form-control" name="filter_name" value="<?php echo $filter_name; ?>" /></td>
					 <td><input type="text" class="form-control" name="filter_date_start" value="<?php echo $filter_date_start; ?>" id="date-start" size="12" readonly /></td>
                      <td><input type="text" class="form-control" name="filter_date_end" value="<?php echo $filter_date_end; ?>" id="date-end" size="12" readonly /></td>

                      <td ><a onclick="filter();" class="btn btn-info">Filter</a></td>
                    </tr>
                  </tbody>
                  </table>
              </div>
            </div>
            <div class="row">
              <div class="col-md-12">
                <form action="" method="post" enctype="multipart/form-data" id="form">
                  <table class="table table-bordered">
                    <thead>
                      <tr>
                        <th class="left">Customer ID</th>
                        <th class="left">Nama Customer</th>
                        <th class="left" >Saldo Awal</th>
                        <th class="left" >Penambahan Piutang</th>
                        <th class="left" >Pelunasan</th>
                        <th class="left" >Saldo Akhir</th>
                        
                      </tr>
                    </thead>
                    <tbody>

                      <?php if ($customers) { ?>
                      <?php foreach ($customers as $customer) { ?>
                      <tr>
                        <td class="left"><?php echo $customer['customer_id']; ?>
                        <td class="left"><?php echo $customer['name']; ?>
                          </td>
                        <td class="left"><?php echo $customer['saldoawal']; ?></td>
                        <td class="left"><?php echo $customer['penambahan'] ?></td>
                        <td class="left"><?php echo $customer['pelunasan'] ?></td>
                        <td class="left"><?php echo $customer['saldoakhir']; ?></td>
                        
                      </tr>
                      <?php } ?>
                      <?php } else { ?>
                      <tr>
                        <td class="center" colspan="5"><?php echo $text_no_results; ?></td>
                      </tr>
                      <?php } ?>
                      <tr>
                        <td colspan="2"><b>Total</b></td>
                        <td><b><?php echo $totalsaldoawal ?></b></td>
                        <td><b><?php echo $totalpenambahanpiutang ?></b></td>
                        <td><b><?php echo $totalpelunasan ?></b></td>
                        <td><b><?php echo $totalsaldoakhir ?></b></td>
                      </tr>
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
$('.sidebar-menu').find('#menu-laporan').addClass('active');
$('.sidebar-menu').find('#menu-laporan-mutasipiutang').addClass('active');
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
})
</script>
<script type="text/javascript"><!--
$(document).ready(function() {
	$('#date-start').datepicker({dateFormat: 'yy-mm-dd'});

	$('#date-end').datepicker({dateFormat: 'yy-mm-dd'});
});
//--></script>

<script type="text/javascript"><!--
function filter() {
	url = 'index.php?route=laporan/mutasipiutang&token=<?php echo $token; ?>';

	var filter_name = $('input[name=\'filter_name\']').val();

	if (filter_name) {
		url += '&filter_name=' + encodeURIComponent(filter_name);
	}
    var filter_date_start = $('input[name=\'filter_date_start\']').val();

	if (filter_date_start) {
		url += '&filter_date_start=' + encodeURIComponent(filter_date_start);
	}

  var filter_date_end = $('input[name=\'filter_date_end\']').val();

	if (filter_date_end) {
		url += '&filter_date_end=' + encodeURIComponent(filter_date_end);
	}
	location = url;
}

function cetak(){
  url = 'index.php?route=laporan/mutasipiutang/cetak&token=<?php echo $token; ?>';

	var filter_name = $('input[name=\'filter_name\']').val();

	if (filter_name) {
		url += '&filter_name=' + encodeURIComponent(filter_name);
	}
    var filter_date_start = $('input[name=\'filter_date_start\']').val();

	if (filter_date_start) {
		url += '&filter_date_start=' + encodeURIComponent(filter_date_start);
	}

  var filter_date_end = $('input[name=\'filter_date_end\']').val();

	if (filter_date_end) {
		url += '&filter_date_end=' + encodeURIComponent(filter_date_end);
	}
  var filter_page_start = $('select[name=\'filter_page_start\']').val();

  if (filter_page_start != null) {
    url += '&filter_page_start=' + filter_page_start;
  }

  var filter_page_end = $('select[name=\'filter_page_end\']').val();

  if (filter_page_end != null) {
    url += '&filter_page_end=' + filter_page_end;
  }
 window.open(url);
 }
//--></script>

<script type="text/javascript"><!--
$('#form input').keydown(function(e) {
	if (e.keyCode == 13) {
		filter();
	}
});
//--></script>
<?php echo $footer; ?>
