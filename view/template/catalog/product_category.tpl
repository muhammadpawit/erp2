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
            <h3 class="box-title">Kategori Produk</h3>
            <div class="button pull-right">
                <a onclick="$('#form').submit();" ><button type="button" class="btn btn-primary">Simpan</button></a>
									<a href="<?php echo $cancel; ?>"><button type="button" class="btn btn-danger">Kembali</button></a>

								</div>
          </div>
          <div class="box-body">
            <div class="row">
              <div class="col-md-12">

              </div>
            </div>

            <div class="row">
              <div class="col-md-12">
                <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
                  <table class="table">
                    <tr>
                      <td>Kategori</td>
                      <td>
                        <a onclick="$(this).parent().find(':checkbox').attr('checked', true);">Pilih Semua</a> / <a onclick="$(this).parent().find(':checkbox').attr('checked', false);">Hapus Pilihan</a>
                        <div class="scrollbox" style="height:200px;overflow:scroll">
                          <?php $class = 'odd'; ?>
                          <?php foreach ($categories as $category) { ?>
                          <?php $class = ($class == 'even' ? 'odd' : 'even'); ?>
                          <div class="<?php echo $class; ?>">
                            <?php if (in_array($category['category_id'], $product_category)) { ?>
                            <input type="checkbox" name="product_category[]" value="<?php echo $category['category_id']; ?>" checked="checked" />
                            <?php echo $category['name']; ?>
                            <?php } else { ?>
                            <input type="checkbox" name="product_category[]" value="<?php echo $category['category_id']; ?>" />
                            <?php echo $category['name']; ?>
                            <?php } ?>
                          </div>
                          <?php } ?>
                        </div>
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
$('.sidebar-menu').find('#menu-produk').addClass('active');
$('.sidebar-menu').find('#menu-daftar-produk').addClass('active');
</script>

<?php echo $footer; ?>
