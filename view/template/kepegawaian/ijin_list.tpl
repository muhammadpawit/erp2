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
            <h3 class="box-title">Ijin Pegawai</h3>
            <div class="button pull-right">
									<a href="<?php echo $insert; ?>"><button type="button" class="btn btn-primary">Tambah</button></a>

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
                <form action="<?php echo $delete; ?>" method="post" enctype="multipart/form-data" id="form">
                  <table class="table table-bordered">
                    <thead>
                      <tr>
                        <th class="left">Nama Pegawai</th>
                        <th class="left">Tanggal Ijin</th>
                        <th class="left">Keperluan</th>

                        <th class="right"></th>
                      </tr>
                    </thead>
                    <tbody>

                      <?php if ($absensis) { ?>
                      <?php foreach ($absensis as $category) { ?>
                      <tr>
                        <td class="left"><?php echo $category['name']; ?></td>
                        <td class="left"><?php echo $category['tgl_awal']; ?> - <?php echo $category['tgl_akhir']; ?></td>
                        <td class="left"><?php echo $category['keperluan']; ?></td>
                        
                          <td class="right">
                            <?php
                            if($category['status'] == 1){
                            ?>
                            <?php foreach ($category['action'] as $action) { ?>
                            <a class="badge bg-green" href="<?php echo $action['href']; ?>"><?php echo $action['text']; ?></a>
                            <?php } ?>
                            <?php
                            }
                            if($category['status'] == 2){
                              echo 'Disetujui';
                            }
                            if($category['status'] == 3){
                              echo 'Dibatalkan';
                            }
                            ?>
                            </td>
                      </tr>
                      <?php } ?>
                      <?php } else { ?>
                      <tr>
                        <td class="center" colspan="8">Data ijin tidak ditemukan</td>
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
</script>
<script type="text/javascript"><!--
function filter() {
		url = 'index.php?route=kepegawaian/ijin&token=<?php echo $token; ?>';

	var filter_name = $('select[name=\'filter_name\']').val();

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
