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
            <h3 class="box-title">Kontrak Pegawai</h3>
            <div class="button pull-right">
              <a onclick="location = '<?php echo $insert; ?>'" class="btn btn-info">Tambah</a>
              <a onclick="location = '<?php echo $cancel; ?>';" class="btn btn-warning">Kembali</a>
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
                      <th class="left">Tanggal Awal</th>
                      <th class="left">Tanggal Akhir</th>
                      <th class="left">Nama Pegawai</th>
                      <th></th>
                    </tr>
                  </thead>
                  <tbody>
                    <td><input type="text" class="form-control" id="date-start" name="filter_date_start" value="<?php echo $filter_date_start; ?>"></td>
                      <td><input type="text" class="form-control" id="date-end" name="filter_date_end" value="<?php echo $filter_date_end; ?>"></td>

                    <td>
                      <select name="filter_name"  class="sales form-control">

                        </select>
                    </td>
                    <td><a onclick="filter();" class="btn btn-info">Filter</a></td>
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
                        <th class="left">Nama Pegawai</th>
                        <th class="left">Tanggal Awal</th>
                        <th class="left">Tanggal Akhir</th>
                        <th class="left">No Kontrak</th>
                        <th class="left">File Kontrak</th>
                        <th class="left">Status</th>
                        <th class="left">Keterangan</th>
                        <th></th>
                      </tr>
                    </thead>
                    <tbody>

                      <?php if ($absensis) { ?>
                      <?php foreach ($absensis as $customer) { ?>
                      <tr>
                        <td class="left"><?php echo $customer['name']; ?></td>
                        <td class="left"><?php echo $customer['tglawal']; ?></td>
                        <td class="left"><?php echo $customer['tglakhir']; ?></td>
                        <td class="left"><?php echo $customer['no_kontrak']; ?></td>
                        <td class="left"><a href="<?php echo $customer['thumb']; ?>" target="_blank"><img src="<?php echo $customer['thumb']; ?>"></a></td>
                        <td class="left"><?php echo $customer['status']; ?></td>
                        <td class="left"><?php echo $customer['keterangan']; ?></td>
                        <td class="right"><?php foreach ($customer['action'] as $action) { ?>
                            <a href="<?php echo $action['href']; ?>" class="badge bg-blue"><?php echo $action['text']; ?></a>
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
$('.sidebar-menu').find('#menu-kepegawaian').addClass('active');
$('.sidebar-menu').find('#menu-kontrak').addClass('active');
$(function(){
  $('#date-start').datepicker({dateFormat: 'yy-mm-dd'});

	$('#date-end').datepicker({dateFormat: 'yy-mm-dd'});
  $(".sales").select2({
    ajax: {
    url:"index.php?route=kepegawaian/premijual/autocomplete&token=<?php echo $this->request->get['token']; ?>",
    //url: "index.php?route=pamerantoko/toko/autocomplete&token=<?php echo $this->request->get['token']; ?>",
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
});
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
