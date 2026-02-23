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
            <h3 class="box-title">Generate Laba Rugi


            </h3>
            <div class="button pull-right">
              <a href="<?php echo $insert; ?>"><button type="button" class="btn btn-primary">Tambah</button></a>
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
                    <th>Periode</th>
                    <th></th>
                  </thead>
                  <tbody>
                    <tr>
                      <td><select name="filter_periode"  class="periode form-control">

                        </select></td>

                      <td><a onclick="filter();" class="btn btn-info">Filter</a></td>
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
                        <th class="left">Range Tanggal</th>
                        <th class="left">Laba Rugi</th>
                        <th class="left">Tanggal Dibuat</th>
                        <th></th>

                      </tr>
                    </thead>
                    <tbody>

                      <?php if ($labarugis) { ?>
                      <?php foreach ($labarugis as $lr) { ?>
                      <tr>
                        <td><?php echo $lr['tglawal']?> - <?php echo $lr['tglselesai']?></td>
                        <td><?php echo $lr['labarugi']; ?></td>
                        <td><?php echo $lr['date_added']; ?></td>
                        <td class="right"><?php foreach ($lr['action'] as $action) { ?>
                          <a class="badge bg-yellow" href="<?php echo $action['href']; ?>"><?php echo $action['text']; ?></a>
                          <?php } ?></td>
                      </tr>

                      <?php } ?>
                      <?php } else { ?>
                      <tr>
                        <td class="center" colspan="4"><?php echo $text_no_results; ?></td>
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
$('.sidebar-menu').find('#menu-keuangan').addClass('active');
$('.sidebar-menu').find('#menu-generate-labarugi').addClass('active');
//--></script>
<script type="text/javascript"><!--
$('#form input').keydown(function(e) {
	if (e.keyCode == 13) {
		filter();
	}
});
$(function(){
  $(".periode").select2({
    ajax: {
    url:"index.php?route=kepegawaian/periode/autocomplete&token=<?php echo $this->request->get['token']; ?>",
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
function filter() {
		url = 'index.php?route=keuangan/generatelabarugi&token=<?php echo $this->request->get['token']; ?>';

var filter_periode = $('select[name=\'filter_periode\']').val();

	if (filter_periode != null) {
		url += '&filter_periode=' + encodeURIComponent(filter_periode);
	}


	location = url;
}
//--></script>
<?php echo $footer; ?>
