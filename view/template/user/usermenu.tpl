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
            <h3 class="box-title">Akses Menu</h3>
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
                  <table class="table table-bordered">
                    <thead>
                      <tr>
                        <th>Master Data</th>
                        <th>Persediaan</th>
                      </tr>

                    </thead>
                    <tbody>
                    <tr>
                      <td>
                        <a onclick="$(this).parent().find(':checkbox').attr('checked', true);">Pilih Semua</a> / <a onclick="$(this).parent().find(':checkbox').attr('checked', false);">Hapus Pilihan</a>

                          <?php $class = 'odd'; ?>
                          <?php foreach ($masterdata as $category) { ?>
                          <?php $class = ($class == 'even' ? 'odd' : 'even'); ?>
                          <div class="<?php echo $class; ?>">
                            <?php if (in_array($category['url'], $user_menu)) { ?>
                            <input type="checkbox" name="user_menu[]" value="<?php echo $category['url']; ?>" checked="checked" />
                            <?php echo $category['nama']; ?>
                            <?php } else { ?>
                            <input type="checkbox" name="user_menu[]" value="<?php echo $category['url']; ?>" />
                            <?php echo $category['nama']; ?>
                            <?php } ?>
                          </div>
                          <?php } ?>

                        </td>
                        <td>
                          <a onclick="$(this).parent().find(':checkbox').attr('checked', true);">Pilih Semua</a> / <a onclick="$(this).parent().find(':checkbox').attr('checked', false);">Hapus Pilihan</a>

                            <?php $class = 'odd'; ?>
                            <?php foreach ($persediaan as $category) { ?>
                            <?php $class = ($class == 'even' ? 'odd' : 'even'); ?>
                            <div class="<?php echo $class; ?>">
                              <?php if (in_array($category['url'], $user_menu)) { ?>
                              <input type="checkbox" name="user_menu[]" value="<?php echo $category['url']; ?>" checked="checked" />
                              <?php echo $category['nama']; ?>
                              <?php } else { ?>
                              <input type="checkbox" name="user_menu[]" value="<?php echo $category['url']; ?>" />
                              <?php echo $category['nama']; ?>
                              <?php } ?>
                            </div>
                            <?php } ?>

                          </td>
                    </tr>
                    </tbody>
                  </table>
                  <table class="table table-bordered">
                    <thead>
                      <tr>
                        <th>Customer</th>
                        <th>Pembelian</th>
                      </tr>

                    </thead>
                    <tbody>
                    <tr>
                      <td>
                        <a onclick="$(this).parent().find(':checkbox').attr('checked', true);">Pilih Semua</a> / <a onclick="$(this).parent().find(':checkbox').attr('checked', false);">Hapus Pilihan</a>

                          <?php $class = 'odd'; ?>
                          <?php foreach ($customer as $category) { ?>
                          <?php $class = ($class == 'even' ? 'odd' : 'even'); ?>
                          <div class="<?php echo $class; ?>">
                            <?php if (in_array($category['url'], $user_menu)) { ?>
                            <input type="checkbox" name="user_menu[]" value="<?php echo $category['url']; ?>" checked="checked" />
                            <?php echo $category['nama']; ?>
                            <?php } else { ?>
                            <input type="checkbox" name="user_menu[]" value="<?php echo $category['url']; ?>" />
                            <?php echo $category['nama']; ?>
                            <?php } ?>
                          </div>
                          <?php } ?>

                        </td>
                        <td>
                          <a onclick="$(this).parent().find(':checkbox').attr('checked', true);">Pilih Semua</a> / <a onclick="$(this).parent().find(':checkbox').attr('checked', false);">Hapus Pilihan</a>

                            <?php $class = 'odd'; ?>
                            <?php foreach ($pembelian as $category) { ?>
                            <?php $class = ($class == 'even' ? 'odd' : 'even'); ?>
                            <div class="<?php echo $class; ?>">
                              <?php if (in_array($category['url'], $user_menu)) { ?>
                              <input type="checkbox" name="user_menu[]" value="<?php echo $category['url']; ?>" checked="checked" />
                              <?php echo $category['nama']; ?>
                              <?php } else { ?>
                              <input type="checkbox" name="user_menu[]" value="<?php echo $category['url']; ?>" />
                              <?php echo $category['nama']; ?>
                              <?php } ?>
                            </div>
                            <?php } ?>

                          </td>
                    </tr>
                    </tbody>
                  </table>
                  <table class="table table-bordered">
                    <thead>
                      <tr>
                        <th>Penjualan</th>
                        <th>Produksi</th>
                      </tr>

                    </thead>
                    <tbody>
                    <tr>
                      <td>
                        <a onclick="$(this).parent().find(':checkbox').attr('checked', true);">Pilih Semua</a> / <a onclick="$(this).parent().find(':checkbox').attr('checked', false);">Hapus Pilihan</a>

                          <?php $class = 'odd'; ?>
                          <?php foreach ($penjualan as $category) { ?>
                          <?php $class = ($class == 'even' ? 'odd' : 'even'); ?>
                          <div class="<?php echo $class; ?>">
                            <?php if (in_array($category['url'], $user_menu)) { ?>
                            <input type="checkbox" name="user_menu[]" value="<?php echo $category['url']; ?>" checked="checked" />
                            <?php echo $category['nama']; ?>
                            <?php } else { ?>
                            <input type="checkbox" name="user_menu[]" value="<?php echo $category['url']; ?>" />
                            <?php echo $category['nama']; ?>
                            <?php } ?>
                          </div>
                          <?php } ?>

                        </td>
                        <td>
                          <a onclick="$(this).parent().find(':checkbox').attr('checked', true);">Pilih Semua</a> / <a onclick="$(this).parent().find(':checkbox').attr('checked', false);">Hapus Pilihan</a>

                            <?php $class = 'odd'; ?>
                            <?php foreach ($produksi as $category) { ?>
                            <?php $class = ($class == 'even' ? 'odd' : 'even'); ?>
                            <div class="<?php echo $class; ?>">
                              <?php if (in_array($category['url'], $user_menu)) { ?>
                              <input type="checkbox" name="user_menu[]" value="<?php echo $category['url']; ?>" checked="checked" />
                              <?php echo $category['nama']; ?>
                              <?php } else { ?>
                              <input type="checkbox" name="user_menu[]" value="<?php echo $category['url']; ?>" />
                              <?php echo $category['nama']; ?>
                              <?php } ?>
                            </div>
                            <?php } ?>

                          </td>
                    </tr>
                    </tbody>
                  </table>
                  <table class="table table-bordered">
                    <thead>
                      <tr>
                        <th>Keuangan</th>
                        <th>Akuntansi</th>
                      </tr>

                    </thead>
                    <tbody>
                    <tr>
                      <td>
                        <a onclick="$(this).parent().find(':checkbox').attr('checked', true);">Pilih Semua</a> / <a onclick="$(this).parent().find(':checkbox').attr('checked', false);">Hapus Pilihan</a>

                          <?php $class = 'odd'; ?>
                          <?php foreach ($keuangan as $category) { ?>
                          <?php $class = ($class == 'even' ? 'odd' : 'even'); ?>
                          <div class="<?php echo $class; ?>">
                            <?php if (in_array($category['url'], $user_menu)) { ?>
                            <input type="checkbox" name="user_menu[]" value="<?php echo $category['url']; ?>" checked="checked" />
                            <?php echo $category['nama']; ?>
                            <?php } else { ?>
                            <input type="checkbox" name="user_menu[]" value="<?php echo $category['url']; ?>" />
                            <?php echo $category['nama']; ?>
                            <?php } ?>
                          </div>
                          <?php } ?>

                        </td>
                        <td>
                          <a onclick="$(this).parent().find(':checkbox').attr('checked', true);">Pilih Semua</a> / <a onclick="$(this).parent().find(':checkbox').attr('checked', false);">Hapus Pilihan</a>

                            <?php $class = 'odd'; ?>
                            <?php foreach ($akuntansi as $category) { ?>
                            <?php $class = ($class == 'even' ? 'odd' : 'even'); ?>
                            <div class="<?php echo $class; ?>">
                              <?php if (in_array($category['url'], $user_menu)) { ?>
                              <input type="checkbox" name="user_menu[]" value="<?php echo $category['url']; ?>" checked="checked" />
                              <?php echo $category['nama']; ?>
                              <?php } else { ?>
                              <input type="checkbox" name="user_menu[]" value="<?php echo $category['url']; ?>" />
                              <?php echo $category['nama']; ?>
                              <?php } ?>
                            </div>
                            <?php } ?>

                          </td>
                    </tr>
                    </tbody>
                  </table>
                  <table class="table table-bordered">
                    <thead>
                      <tr>
                        <th>Kepegawaian</th>
                        <th>Laporan</th>
                      </tr>

                    </thead>
                    <tbody>
                    <tr>
                      <td>
                        <a onclick="$(this).parent().find(':checkbox').attr('checked', true);">Pilih Semua</a> / <a onclick="$(this).parent().find(':checkbox').attr('checked', false);">Hapus Pilihan</a>

                          <?php $class = 'odd'; ?>
                          <?php foreach ($kepegawaian as $category) { ?>
                          <?php $class = ($class == 'even' ? 'odd' : 'even'); ?>
                          <div class="<?php echo $class; ?>">
                            <?php if (in_array($category['url'], $user_menu)) { ?>
                            <input type="checkbox" name="user_menu[]" value="<?php echo $category['url']; ?>" checked="checked" />
                            <?php echo $category['nama']; ?>
                            <?php } else { ?>
                            <input type="checkbox" name="user_menu[]" value="<?php echo $category['url']; ?>" />
                            <?php echo $category['nama']; ?>
                            <?php } ?>
                          </div>
                          <?php } ?>

                        </td>
                        <td>
                          <a onclick="$(this).parent().find(':checkbox').attr('checked', true);">Pilih Semua</a> / <a onclick="$(this).parent().find(':checkbox').attr('checked', false);">Hapus Pilihan</a>

                            <?php $class = 'odd'; ?>
                            <?php foreach ($laporan as $category) { ?>
                            <?php $class = ($class == 'even' ? 'odd' : 'even'); ?>
                            <div class="<?php echo $class; ?>">
                              <?php if (in_array($category['url'], $user_menu)) { ?>
                              <input type="checkbox" name="user_menu[]" value="<?php echo $category['url']; ?>" checked="checked" />
                              <?php echo $category['nama']; ?>
                              <?php } else { ?>
                              <input type="checkbox" name="user_menu[]" value="<?php echo $category['url']; ?>" />
                              <?php echo $category['nama']; ?>
                              <?php } ?>
                            </div>
                            <?php } ?>

                          </td>
                    </tr>
                    </tbody>
                  </table>
                  <table class="table table-bordered">
                    <thead>
                      <tr>
                        <th>Pajak</th>
                        <th>Pengaturan</th>

                      </tr>

                    </thead>
                    <tbody>
                    <tr>
                      <td>
                        <a onclick="$(this).parent().find(':checkbox').attr('checked', true);">Pilih Semua</a> / <a onclick="$(this).parent().find(':checkbox').attr('checked', false);">Hapus Pilihan</a>

                          <?php $class = 'odd'; ?>
                          <?php foreach ($pajak as $category) { ?>
                          <?php $class = ($class == 'even' ? 'odd' : 'even'); ?>
                          <div class="<?php echo $class; ?>">
                            <?php if (in_array($category['url'], $user_menu)) { ?>
                            <input type="checkbox" name="user_menu[]" value="<?php echo $category['url']; ?>" checked="checked" />
                            <?php echo $category['nama']; ?>
                            <?php } else { ?>
                            <input type="checkbox" name="user_menu[]" value="<?php echo $category['url']; ?>" />
                            <?php echo $category['nama']; ?>
                            <?php } ?>
                          </div>
                          <?php } ?>

                        </td>
                      <td>
                        <a onclick="$(this).parent().find(':checkbox').attr('checked', true);">Pilih Semua</a> / <a onclick="$(this).parent().find(':checkbox').attr('checked', false);">Hapus Pilihan</a>

                          <?php $class = 'odd'; ?>
                          <?php foreach ($pengaturan as $category) { ?>
                          <?php $class = ($class == 'even' ? 'odd' : 'even'); ?>
                          <div class="<?php echo $class; ?>">
                            <?php if (in_array($category['url'], $user_menu)) { ?>
                            <input type="checkbox" name="user_menu[]" value="<?php echo $category['url']; ?>" checked="checked" />
                            <?php echo $category['nama']; ?>
                            <?php } else { ?>
                            <input type="checkbox" name="user_menu[]" value="<?php echo $category['url']; ?>" />
                            <?php echo $category['nama']; ?>
                            <?php } ?>
                          </div>
                          <?php } ?>

                        </td>


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
$('.sidebar-menu').find('#menu-manajemen-pegawai').addClass('active');
</script>

<?php echo $footer; ?>
