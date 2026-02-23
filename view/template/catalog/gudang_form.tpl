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
            <h3 class="box-title">Tambah Gudang</h3>
            <div class="button pull-right">
									<a onclick="$('#form').submit();" class="button"><button type="button" class="btn btn-primary">Simpan</button></a>
                  <a href="<?php echo $cancel; ?>"><button type="button" class="btn btn-warning">Kembali</button></a>
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
                <?php if (isset($success)) { ?>
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
                <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
                  <table class="table table-stripped">
                      <tr>
                        <td><span class="required">*</span> Nama Gudang</td>
                        <td><input type="text" name="gudang[nama]" class="form-control" value="<?php echo isset($gudang) ? $gudang['nama'] : ''; ?>" />
                        </td>
                      </tr>
                      <tr>
                        <td><span class="required">*</span> Supplier</td>
                        <td>
                            <select name="gudang[supplier]" class="form-control">
                              <option value="0">Pilih salah satu</option>
                              <option value="1" <?php echo ($gudang['supplier']==1)?'selected':''; ?>>Ya</option>
                              <option value="0" <?php echo ($gudang['supplier']==0)?'selected':''; ?>>Bukan</option>
                            </select>
                        </td>
                      </tr>
                      <tr>
                        <td>Printer</td>
                        <td><input type="text" name="gudang[printer]" class="form-control" value="<?php echo isset($gudang) ? $gudang['printer'] : ''; ?>" />
                        </td>
                      </tr>


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
$('.sidebar-menu').find('#menu-master-data').addClass('active');
$('.sidebar-menu').find('#menu-gudang').addClass('active');
</script>

<?php echo $footer; ?>
