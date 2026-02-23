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
            <h3 class="box-title">Kontak Customer</h3>
            <div class="button pull-right">
                    <a onclick="location = '<?php echo $insert; ?>'" class="btn btn-info">Tambah</a>
                    <a onclick="$('form').attr('action', '<?php echo $delete; ?>'); $('form').submit();" class="btn btn-danger">Hapus</a>
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
                <form action="" method="post" enctype="multipart/form-data" id="form">
                  <table class="table table-bordered">
                    <thead>
                      <tr>
                        <td width="1" style="text-align: center;"><input type="checkbox" onclick="$('input[name*=\'selected\']').attr('checked', this.checked);" /></td>
                        <th class="left">Nama</th>
                        <th class="left">Telephone</th>
                        <th class="left">Alamat</th>
                        <th class="left">Kecamatan</th>
                        <th class="left">Kota/Kabupaten</th>
                        <th class="left">Propinsi</th>


                      </tr>
                    </thead>
                    <tbody>

                      <?php if ($addresses) { ?>
                      <?php foreach ($addresses as $customer) { ?>
                      <tr>
                        <td style="text-align: center;"><?php if ($customer['selected']) { ?>
                          <input type="checkbox" name="selected[]" value="<?php echo $customer['address_id']; ?>" checked="checked" />
                          <?php } else { ?>
                          <input type="checkbox" name="selected[]" value="<?php echo $customer['address_id']; ?>" />
                          <?php } ?></td>
                        <td class="left"><?php echo $customer['firstname'].' '.$customer['lastname']; ?></td>
                        <td class="left"><?php echo $customer['telephone']; ?></td>
                        <td class="left"><?php echo $customer['address']; ?></td>
                        <td class="left"><?php echo $customer['city']; ?></td>
                        <td class="left"><?php echo $customer['zone']; ?></td>
                        <td class="left"><?php echo $customer['country']; ?></td>


                      </tr>
                      <?php } ?>
                      <?php } else { ?>
                      <tr>
                        <td class="center" colspan="10"><?php echo $text_no_results; ?></td>
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
$('.sidebar-menu').find('#menu-customer').addClass('active');
$('.sidebar-menu').find('#menu-customer-list').addClass('active');

//--></script>
<script type="text/javascript"><!--
$('#form input').keydown(function(e) {
	if (e.keyCode == 13) {
		filter();
	}
});
//--></script>
<?php echo $footer; ?>
