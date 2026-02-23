<?php echo $header; ?>
<div class="content-wrapper" id="content">
  <section class="content-header">
    <h1>

    </h1>

  </section>

  <section class="content" >
    <div class="row">
      <div class="col-md-12">
        <div class="box">
          <div class="box-header with-border">
            <h3 class="box-title">Bahan Baku</h3>
            <div class="button pull-right">
                <a onclick="$('#form').submit();" ><button type="button" class="btn btn-primary">Simpan</button></a>
									<a href="<?php echo $cancel; ?>"><button type="button" class="btn btn-danger">Kembali</button></a>

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

              </div>
            </div>

            <div class="row">
              <div class="col-md-12">
                <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
                  <table class="table">
                    <tr>
                      <td><span class="required">*</span> Nama Bahan Baku</td>
                      <td><input type="text" name="name" size="100" value="<?php echo $name; ?>"  required/>
                        <input type="hidden" name="quantity" value="<?php echo $quantity; ?>" />
                        <input type="hidden" name="level" value="<?php echo $level; ?>" />
                        </td>
                    </tr>
                    <tr>
                      <td>Quantity</td>
                      <td><?php echo $quantity; ?></td>
                    </tr>
                    <tr>
                      <td>Satuan</td>
                      <td>
                        <select class="form-control" name="satuan">
                            <?php
                            foreach($satuans as $s){
                            ?>
                              <option value="<?php echo $s['id']; ?>" <?php echo $satuan == $s['id']?'selected':''; ?>><?php echo $s['name']; ?></option>
                            <?php
                            }
                            ?>
                          </select>
                      </td>
                    </tr>
                    <tr>
                      <td>Level</td>
                      <td><?php echo $level; ?></td>
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
$('.sidebar-menu').find('#menu-persediaan').addClass('active');
$('.sidebar-menu').find('#menu-persediaan-bahanbaku').addClass('active');
$('.sidebar-menu').find('#menu-daftar-bahanbaku').addClass('active');
</script>

<?php echo $footer; ?>
