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
            <h3 class="box-title">Laporan Mutasi Deposit Customer</h3>
            <div class="button pull-right">
              <a href="<?php echo $exportexcel?>" target="_blank" class="btn btn-success">Export to Excel</a>
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
                <table class="table table-bordered">
                  <thead>
                    <tr>
                      <th class="left">Nama</th>
                      <th class="left">Tanggal Awal</th>
                      <th class="left">Tanggal Akhir</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr>
                      <td><input type="text" class="form-control" name="filter_name" value="<?php echo $filter_name; ?>" /></td>
                      <td>
                        <input type="text" name="filter_date_start" class="form-control date" value="<?php echo $filter_date_start ?>">
                      </td>
                      <td><input type="text" name="filter_date_end" class="form-control date" value="<?php echo $filter_date_end?>"></td>
                      <td align="left"><a onclick="filter();" class="btn btn-primary"><i class="fa fa-search"></i></a></td>
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
                        <th class="left">Nama</th>
                        <th class="left" >Saldo Awal</th>
                        <th class="left" >Saldo Masuk</th>
                        <th class="left" >Saldo Keluar</th>
                        <th class="left" >Sisa Saldo</th>
                      </tr>
                    </thead>
                    <tbody>

                      <?php if ($customers) { ?>
                      <?php foreach ($customers as $customer) { ?>
                      <tr>
                        <td class="left"><?php echo $customer['customer_id']; ?>
                        <td class="left"><?php echo $customer['name']; ?></td>
                        <td class="left"><?php echo $customer['awal']; ?></td>
                        <td class="left"><?php echo $customer['saldomasuk'] ?></td>
                        <td class="left"><?php echo $customer['saldokeluar'] ?></td>
                        <td class="left"><?php echo $customer['sisasaldo']; ?></td>
                      </tr>
                      <?php } ?>
                      <?php } else { ?>
                      <tr>
                        <td class="center" colspan="5"><?php echo $text_no_results; ?></td>
                      </tr>
                      <?php } ?>
                      <!--<tr>
                        <td colspan="2"><b>Total</b></td>
                        <td><?php echo $this->currency->format($totaldeposit) ?></td>
                        <td><?php echo $this->currency->format($totalgiro) ?></td>
                        <td><?php echo $this->currency->format($totalpiutang) ?></td>
                        <td><?php echo $this->currency->format($totalsisaharusbayar) ?></td>
                      </tr>-->
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
$('.sidebar-menu').find('#menu-laporan-deposit').addClass('active');
$('.date').datepicker({dateFormat: 'yy-mm-dd'});
$(function(){
  $(".select-ads").select2({


      theme:"bootstrap"
    });
})
</script>

<script type="text/javascript"><!--
function filter() {
	url = 'index.php?route=laporan/mutasidepositcustomer&token=<?php echo $token; ?>';

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

  var deposit = $('select[name=\'deposit\']').val();

  if (deposit != '*') {
    url += '&deposit=' + encodeURIComponent(deposit);
  }



	location = url;
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
