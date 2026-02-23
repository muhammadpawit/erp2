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
            <h3 class="box-title">Akses Data</h3>
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
                        <th>Data</th>
                        <th></th>
                      </tr>

                    </thead>
                    <tbody>
                    <tr>
                      <td>Data Customer
                        <input type="hidden" value="1" name="user_menu[1][akses]" >
                      </td>
                      <td>
                        <select class="form-control" name="user_menu[1][nilai]">
                          <option value="0" >Belum Diatur</option>
                          <option value="1" <?php echo $user_menu[1]['nilai'] == 1?'selected':''; ?>>Semua Customer</option>
                          <option value="2" <?php echo $user_menu[1]['nilai'] == 2?'selected':''; ?>>Customer Sendiri</option>
                        </select>
                      </td>

                    </tr>
                    <tr>
                      <td>Menyetujui Permintaan Pembelian
                        <input type="hidden" value="2" name="user_menu[2][akses]" >
                      </td>
                      <td>
                        <select class="form-control" name="user_menu[2][nilai]">
                          <option value="0" >Belum Diatur</option>
                          <option value="1" <?php echo $user_menu[2]['nilai'] == 1?'selected':''; ?>>Ya</option>
                          <option value="2" <?php echo $user_menu[2]['nilai'] == 2?'selected':''; ?>>Tidak</option>
                        </select>
                      </td>

                    </tr>
                    <tr>
                      <td>Pembatalan Sales Order
                        <input type="hidden" value="3" name="user_menu[3][akses]" >
                      </td>
                      <td>
                        <select class="form-control" name="user_menu[3][nilai]">
                          <option value="0" >Belum Diatur</option>
                          <option value="1" <?php echo $user_menu[3]['nilai'] == 1?'selected':''; ?>>Ya</option>
                          <option value="2" <?php echo $user_menu[3]['nilai'] == 2?'selected':''; ?>>Tidak</option>
                        </select>
                      </td>

                    </tr>
                    <tr>
                      <td>Pembatalan Surat Jalan
                        <input type="hidden" value="4" name="user_menu[4][akses]" >
                      </td>
                      <td>
                        <select class="form-control" name="user_menu[4][nilai]">
                          <option value="0" >Belum Diatur</option>
                          <option value="1" <?php echo $user_menu[4]['nilai'] == 1?'selected':''; ?>>Ya</option>
                          <option value="2" <?php echo $user_menu[4]['nilai'] == 2?'selected':''; ?>>Tidak</option>
                        </select>
                      </td>

                    </tr>
                    <tr>
                      <td>Pembatalan Invoice
                        <input type="hidden" value="5" name="user_menu[5][akses]" >
                      </td>
                      <td>
                        <select class="form-control" name="user_menu[5][nilai]">
                          <option value="0" >Belum Diatur</option>
                          <option value="1" <?php echo $user_menu[5]['nilai'] == 1?'selected':''; ?>>Ya</option>
                          <option value="2" <?php echo $user_menu[5]['nilai'] == 2?'selected':''; ?>>Tidak</option>
                        </select>
                      </td>

                    </tr>
                    <tr>
                      <td>Menyetujui Cetak Ulang Invoice dan Surat Jalan
                        <input type="hidden" value="6" name="user_menu[6][akses]" >
                      </td>
                      <td>
                        <select class="form-control" name="user_menu[6][nilai]">
                          <option value="0" >Belum Diatur</option>
                          <option value="1" <?php echo $user_menu[6]['nilai'] == 1?'selected':''; ?>>Ya</option>
                          <option value="2" <?php echo $user_menu[6]['nilai'] == 2?'selected':''; ?>>Tidak</option>
                        </select>
                      </td>

                    </tr>
                    <tr>
                      <td> Edit dan Hapus Mutasi
                        <input type="hidden" value="7" name="user_menu[7][akses]" >
                      </td>
                      <td>
                        <select class="form-control" name="user_menu[7][nilai]">
                          <option value="0" >Belum Diatur</option>
                          <option value="1" <?php echo $user_menu[7]['nilai'] == 1?'selected':''; ?>>Ya</option>
                          <option value="2" <?php echo $user_menu[7]['nilai'] == 2?'selected':''; ?>>Tidak</option>
                        </select>
                      </td>

                    </tr>
                    <tr>
                      <td> Edit dan Hapus Jurnal
                        <input type="hidden" value="8" name="user_menu[8][akses]" >
                      </td>
                      <td>
                        <select class="form-control" name="user_menu[8][nilai]">
                          <option value="0" >Belum Diatur</option>
                          <option value="1" <?php echo $user_menu[8]['nilai'] == 1?'selected':''; ?>>Ya</option>
                          <option value="2" <?php echo $user_menu[8]['nilai'] == 2?'selected':''; ?>>Tidak</option>
                        </select>
                      </td>

                    </tr>
                    <tr>
                      <td> Input Stok Awal Produk
                        <input type="hidden" value="9" name="user_menu[9][akses]" >
                      </td>
                      <td>
                        <select class="form-control" name="user_menu[9][nilai]">
                          <option value="0" >Belum Diatur</option>
                          <option value="1" <?php echo $user_menu[9]['nilai'] == 1?'selected':''; ?>>Ya</option>
                          <option value="2" <?php echo $user_menu[9]['nilai'] == 2?'selected':''; ?>>Tidak</option>
                        </select>
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
