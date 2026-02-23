<?php echo $header; ?>
<div class="content-wrapper">
  <section class="content-header">
    <h1>

    </h1>

  </section>
<!-- Modal -->
<div id="myModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Import Data Pengiriman Supir dan Kenek</h4>
      </div>
      <div class="modal-body">
        <form method="post" action="<?php echo $action?>" enctype="multipart/form-data">
          <div class="form-group">
            <label>Upload File</label>
            <input type="file" name="file" id="file" accept=".xls,.xlsx" class="form-control">
          </div>
          <div class="form-group">
            <label>Aksi</label>
            <button type="submit" class="btn btn-success">Upload</button>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>
  <section class="content">
    <div class="row">
      <div class="col-md-12">
        <div class="box">
          <div class="box-header with-border">
            <h3 class="box-title">Akumulasi Premi Jual <?php echo isset($periode)?'Periode '.$periode['nama']:'';?></h3>
            <div class="button pull-right">
              <?php //if($this->user->getUsername()=="pawit"){?>
              <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#myModal">Import</button>
              <?php //} ?>
              <span class="badge bg-green">
              <?php
              if(isset($periode)){
              ?>
              Range Data Perhitungan Premi: <?php echo $date_start; ?> - <?php echo $date_end; ?>
              <?php
              }
              ?>
            </span>
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
                    <th>Nama Pegawai</th>
                    <th>Periode</th>
                    <th></th>
                  </thead>
                  <tbody>
                    <tr>
                      <td><select name="filter_name"  class="sales form-control">

                        </select></td>
                      <td>
                        <select class="form-control" name="filter_periode">
                          <option value="0">Pilih Periode</option>
                          <?php
                          foreach($periodes as $p){
                          ?>
                          <option value="<?php echo $p['periode_id']; ?>"><?php echo $p['nama']; ?></option>
                          <?php
                          }
                          ?>
                        </select>
                      </td>
                      <td>
                        <a onclick="filter();" class="btn btn-info">Filter</a>                        
                        <a href="<?php echo $downloadrincian?>" class="btn btn-primary btn-sm" target="_blank">Download Breakdown</a>
                      </td>
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
                        <th class="left">Nama</th>
                        <th class="left">Kode Premi</th>
                        <th class="left">Akumulasi</th>
                        <th class="left">Premi</th>

                      </tr>
                    </thead>
                    <tbody>

                      <?php if ($users) { ?>
                      <?php foreach ($users as $user) { ?>
                      <tr>
                        <td class="left" colspan="3"><b><?php echo $user['firstname']; ?></b></td>
                        <td><b><?php echo $user['total']; ?></b></td>
                      </tr>
                      <?php
                      foreach($user['akumulasisopir'] as $a){
                        if(!empty($a)){
                      ?>
                      <tr>
                        <td></td>
                        <td><?php echo $a['kodepremi']; ?></td>
                          <td><?php echo $a['total']+$a['totalkernet']; ?></td>
                          <td><?php echo $this->currency->format($a['premikernet']+$a['premisopir']); ?></td>
                      </tr>
                      <?php
                        }
                      }
                      ?>
                      <?php } ?>
                      <?php } else { ?>
                      <tr>
                        <td class="center" colspan="5"><?php echo $text_no_results; ?></td>
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
$('.sidebar-menu').find('#menu-setting').addClass('active');
$('.sidebar-menu').find('#menu-user').addClass('active');
$('.sidebar-menu').find('#menu-daftar-user').addClass('active');

//--></script>
<script type="text/javascript"><!--
$('#form input').keydown(function(e) {
	if (e.keyCode == 13) {
		filter();
	}
});
$(function(){
  $(".sales").select2({
    ajax: {
    url:"index.php?route=laporan/premijual/autocomplete&token=<?php echo $this->request->get['token']; ?>",
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
		url = 'index.php?route=laporan/premijual&token=<?php echo $this->request->get['token']; ?>';

	var filter_name = $('select[name=\'filter_name\']').val();

	if (filter_name) {
		url += '&filter_name=' + encodeURIComponent(filter_name);
	}
  var filter_periode = $('select[name=\'filter_periode\']').val();

	if (filter_periode != 0) {
		url += '&filter_periode=' + encodeURIComponent(filter_periode);
	}


	location = url;
}
//--></script>
<?php echo $footer; ?>
