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
            <h3 class="box-title">Laporan Deposit Supplier Lokal</h3>
              <div class="button pull-right">
                <a href="<?php echo $excel?>" target="_blank" class="btn btn-success">Export to excel</a>
							</div>
          </div>
          <div class="box-body">
            <div class="row">
              <div class="col-md-12">
                <?php if ($error_warning) { ?>
                <div class="alert alert-danger alert-dismissible">
                  <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                  <h4><i class="icon fa fa-ban"></i> Alert!</h4>
                  <?php echo $warning; ?>
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
                <table class="table table-bordered">
                    <thead>
                      <th>Nama Supplier</th>
                      <th>Tanggal Awal</th>
                      <th>Tanggal Akhir</th>
                      <th></th>
                    </thead>
                    <tr>
                        <td><input type="text" name="filter_name" value="<?php echo $filter_name; ?>" class="form-control"/></td>
                        <td><input type="text" name="filter_date_start" value="<?php echo $filter_date_start; ?>" class="form-control date"/></td>
                        <td><input type="text" name="filter_date_end" value="<?php echo $filter_date_end; ?>" class="form-control date"/></td>
                        <td align="left"><a onclick="filter();" class="btn btn-info"><i class="fa fa-search"></i></a></td>
                      </tr>
                </table>
              </div>
            </div>
            <div class="row">
              <div class="col-md-12">
                <form action="<?php echo $delete; ?>" method="post" enctype="multipart/form-data" id="form">
                  <table class="table table-bordered table-hover">
                    <thead>
                      <tr>
                        <th class="left">Supplier ID</th>
                        <th class="left">Nama</th>
                        <th class="left">Deposit</th>
                        <th class="left">Giro Belum Cair</th>
                        <th class="left">Hutang</th>
                        <th class="left">Sisa Harus Bayar</th>
                        <th class="right"></th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php if ($vendors) { ?>
                      <?php foreach ($vendors as $category) { ?>
                      <tr>
                        <td class="left"><?php echo $category['id']; ?></td>
                        <td class="left"><?php echo $category['name']; ?></td>
                        <td class="left"><?php echo $category['deposit']; ?></td>
                        <td class="left"><?php echo $category['giro']?></td>
                        <td class="left"><?php echo $category['hutang']; ?></td>
                        <td class="left"><?php echo $this->currency->format($category['sisa']); ?></td>
                        <td class="right">
                          <?php foreach ($category['action'] as $action) { ?>
                          <a class="badge bg-green" href="<?php echo $action['href']; ?>" target="_blank"><?php echo $action['text']; ?></a>
                          <?php } ?>
                        </td>
                      </tr>
                      <?php } ?>
                      <?php } else { ?>
                      <tr>
                        <td class="center" colspan="8">Data vendor tidak ditemukan</td>
                      </tr>
                      <?php } ?>
                      <tr>
                          <td colspan="2"><b>Total</b></td>
                          <td><b><?php echo $totaldeposit?></b></td>
                          <td><b><?php echo $totalgiro?></b></td>
                          <td><b><?php echo $totalhutang?></b></td>
                          <td><b><?php echo $totalsisa?></b></td>
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
$('.sidebar-menu').find('#menu-pembelian').addClass('active');
$('.sidebar-menu').find('#menu-vendor').addClass('active');
$('.sidebar-menu').find('#menu-vendor-lokal').addClass('active');
$( function() {
    $( ".date" ).datepicker({dateFormat: "yy-mm-dd"});
  } );
</script>
<script type="text/javascript"><!--
function filter() {
	url = 'index.php?route=laporan/depositsupplierlokal&token=<?php echo $token; ?>';

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
//--></script>
<?php echo $footer; ?>
