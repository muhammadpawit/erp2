<?php
echo $header;
?>
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
            <h3 class="box-title">Periode</h3>
            <div class="button pull-right">
									<a href="<?php echo $insert; ?>"><button type="button" class="btn btn-primary">Tambah</button></a>
                  <a onclick="$('#form').submit();" ><button type="button" class="btn btn-danger">Hapus</button></a>
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
              <div class="col-md-12">
                <table class="table table-stripped">
                      <tr class="filter">
                           <td>Nama Periode: <input type="text" name="filter_name" value="<?php echo $filter_name; ?>" /></td>
                            <td>Tanggal Awal Periode: <input type="text" name="filter_date_start" id="filter_date_start" value="<?php echo $filter_date_start; ?>" /></td>
              		<td align="right"><a onclick="filter();" class="btn btn-success">Filter</a></td>
                          </tr>
                    </table>
              </div>
            </div>
            <div class="row">
              <div class="col-md-12">

                <form action="<?php echo $delete; ?>" method="post" enctype="multipart/form-data" id="form">
                  <table class="table table-bordered">
                    <thead>
                      <tr>
                        <td width="1" style="text-align: center;"><input type="checkbox" onclick="$('input[name*=\'selected\']').attr('checked', this.checked);" /></td>
                        <td class="left">Nama Periode</td>
                        <td class="left">Range Tanggal</td>
                        <td class="left">Status</td>


                        <td class="right">Action</td>
                      </tr>
                    </thead>
                    <tbody>

                      <?php if ($periodes) { ?>
                      <?php foreach ($periodes as $product) { ?>
                      <tr>
                        <td style="text-align: center;">

              <?php if ($product['selected']) { ?>
                          <input type="checkbox" name="selected[]" value="<?php echo $product['periode_id']; ?>" checked="checked" />
                          <?php } else { ?>
                          <input type="checkbox" name="selected[]" value="<?php echo $product['periode_id']; ?>" />
                          <?php } ?>
              </td>

                        <td class="left"><?php echo $product['nama']; ?></td>

                        <td class="left"><?php echo date('d F Y',strtotime($product['tgl_awal'])).' s/d '.date('d F Y',strtotime($product['tgl_selesai'])); ?></td>
                         <td class="left"><?php echo $product['status']; ?></td>
                        <td class="right"><?php foreach ($product['action'] as $action) { ?>
                          <a class="badge bg-yellow" href="<?php echo $action['href']; ?>"><?php echo $action['text']; ?></a>
                          <?php } ?></td>
                      </tr>
                      <?php } ?>
                      <?php } else { ?>
                      <tr>
                        <td class="center" colspan="4">No Results.</td>
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
$('.sidebar-menu').find('#menu-pengaturan-pegawai').addClass('active');
$('.sidebar-menu').find('#menu-periode').addClass('active');
</script>
<script type="text/javascript"><!--
function filter() {
	url = 'index.php?route=kepegawaian/periode&token=<?php echo $token; ?>';

	var filter_name = $('input[name=\'filter_name\']').val();

	if (filter_name) {
		url += '&filter_name=' + encodeURIComponent(filter_name);
	}




	location = url;
}
//--></script>
<script type="text/javascript"><!--
$(document).ready(function() {
	$('#filter_date_start').datepicker({dateFormat: 'yy-mm-dd'});

});
//--></script>
<script type="text/javascript"><!--
$('#form input').keydown(function(e) {
	if (e.keyCode == 13) {
		filter();
	}
});
//--></script>
<?php
echo $footer;
?>
