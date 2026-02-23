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
            <h3 class="box-title">Laporan Deposit Customer</h3>
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
                      <th class="left">Urutkan</th>
                      <th class="right"></th >
                    </tr>
                  </thead>
                  <tbody>
                    <tr>
                      <td><input type="text" class="form-control" name="filter_name" value="<?php echo $filter_name; ?>" /></td>
                      <td>
                        <select name="deposit" class="form-control">
                          <option value="*">Tampil Semua</option>
                          <option value="1" <?php echo ($deposit==1)?'selected':'' ?>>Diatas 0</option>
                          <option value="2" <?php echo ($deposit==2)?'selected':'' ?>>Dibawah 0</option>
                          <option value="3" <?php echo ($deposit==3)?'selected':'' ?>>Dibawah 1000</option>
                          <option value="4" <?php echo ($deposit==4)?'selected':'' ?>>Nama A-Z</option>
                          <option value="5" <?php echo ($deposit==5)?'selected':'' ?>>Nama Z-A</option>
                          <option value="6" <?php echo ($deposit==6)?'selected':'' ?>>Deposit Terbesar</option>
                          <option value="7" <?php echo ($deposit==7)?'selected':'' ?>>Deposit Terkecil</option>
                          <option value="8" <?php echo ($deposit==8)?'selected':'' ?>>Tidak sama dengan 0</option>
                        </select>
                      </td>
                      <td align="right"><a onclick="filter();" class="btn btn-primary">Filter</a></td>
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
                        <th class="left" >Deposit</th>
                        <th class="left" >Giro Belum Cair</th>
                        <th class="left" >Piutang</th>
                        <th class="left" >Sisa harus bayar</th>
                        <?php if($this->user->getUsername()=="pawit"){?>
                        <?php } ?>
                        <th class="right"><?php echo $column_action; ?></td>
                      </tr>
                    </thead>
                    <tbody>

                      <?php if ($customers) { ?>
                      <?php foreach ($customers as $customer) { ?>
                      <tr>
                        <td class="left"><?php echo $customer['customer_id']; ?>
                        <td class="left"><?php echo $customer['name']; ?>
                          <br><small><?php //echo $customer['email']; ?></small>
                          <br><small><?php //echo $customer['telephone']; ?></small>
                        </td>
                        <td class="left"><?php echo $customer['deposit']; ?></td>
                        <td class="left"><?php echo $customer['nominal'] ?></td>
                        <td class="left"><?php echo $customer['piutang'] ?></td>
                        <td class="left"><?php echo $customer['sisa']; ?></td>
                        <?php if($this->user->getUsername()=="pawit"){?>
                        <?php } ?>
                        <td class="right"><?php foreach ($customer['action'] as $action) { ?>
                          <a href="<?php echo $action['href']; ?>" class="badge bg-green"><?php echo $action['text']; ?></a><br>
                          <?php } ?></td>
                      </tr>
                      <?php } ?>
                      <?php } else { ?>
                      <tr>
                        <td class="center" colspan="5"><?php echo $text_no_results; ?></td>
                      </tr>
                      <?php } ?>
                      <tr>
                        <td colspan="2"><b>Total</b></td>
                        <td><?php echo $this->currency->format($totaldeposit) ?></td>
                        <td><?php echo $this->currency->format($totalgiro) ?></td>
                        <td><?php echo $this->currency->format($totalpiutang) ?></td>
                        <td><?php echo $this->currency->format($totalsisaharusbayar) ?></td>
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
$('.sidebar-menu').find('#menu-laporan-deposit').addClass('active');
$(function(){
  $(".select-ads").select2({


      theme:"bootstrap"
    });
})
</script>

<script type="text/javascript"><!--
function filter() {
	url = 'index.php?route=laporan/depositcustomer&token=<?php echo $token; ?>';

	var filter_name = $('input[name=\'filter_name\']').val();

	if (filter_name) {
		url += '&filter_name=' + encodeURIComponent(filter_name);
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
