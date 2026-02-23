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
            <h3 class="box-title">Aset</h3>
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
                  <?php
                  /*
                  foreach($error_warning as $e){
                    echo $e.' <br>';
                  }*/
                  echo $error_warning;
                  ?>
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
                      <td><span class="required">*</span> Kode Aset</td>
                      <td><input type="text" name="kode" size="100" value="<?php echo $kode; ?>" />
                      </td>
                    </tr>
                    <tr>
                      <td><span class="required">*</span> Nama Aset</td>
                      <td><input type="text" name="name" size="100" value="<?php echo $name; ?>" />
                      </td>
                    </tr>
                    <tr>
                      <td><span class="required"></span> Jumlah</td>
                      <td><input type="text" name="jumlah" size="100" placeholder="0" value="<?php echo $jumlah; ?>" />
                      </td>
                    </tr>
                    <tr>
                      <td><span class="required"></span> Harga</td>
                      <td><input type="text" name="harga" size="100" placeholder="0" value="<?php echo $harga; ?>" />
                      </td>
                    </tr>
                    <!--<tr >
                      <td>Harga Beli</td>
                      <td><input type="text" name="hargabeli" value="<?php echo $hargabeli; ?>" /></td>
                    </tr>
                    <tr >
                      <td>Tanggal Pembeliaan</td>
                      <td><input type="text" class="date" name="tglpembelian" value="<?php echo $tglpembelian; ?>" /></td>
                    </tr>-->
                    <tr>
                      <td>Jenis Aktiva</td>
                      <td>
                          <select name="jenis_aktiva">
                          <?php
                          foreach($aktivas as $c){
                          ?>
                            <option value="<?php echo $c['no_akun']; ?>" <?php echo $c['no_akun']==$jenis_aktiva?'selected':''; ?>><?php echo $c['nama']; ?></option>
                          <?php
                          }
                          ?>
                          </select>
                      </td>
                    </tr>

                    <tr>
                      <td>Kelompok Aset</td>
                      <td>
                          <select name="kelompok_aset">
                          <?php
                          foreach($asets as $c){
                          ?>
                            <option value="<?php echo $c['kelompok_aset_id']; ?>" <?php echo $c['kelompok_aset_id']==$kelompok_aset?'selected':''; ?>><?php echo $c['jenis_aset'] == 1?'Bukan Bangunan':'Bangunan'; ?> <?php echo $c['name']; ?></option>
                          <?php
                          }
                          ?>
                          </select>
                      </td>
                    </tr>

                    <tr>
                      <td>Status</td>
                      <td><select name="status">
                            <?php
                            if($hargabeli > 0){
                            if($status == 3){
                            ?>
                            <option value="3" <?php echo $status == 3?'selected':''; ?>>Hilang</option>
                            <?php
                          }else{
                            ?>
                            <option value="1" <?php echo $status == 1?'selected':''; ?>>Tersedia</option>
                            <option value="3" <?php echo $status == 3?'selected':''; ?>>Hilang</option>
                            <option value="2" <?php echo $status == 2?'selected':''; ?>>Tidak Tersedia</option>

                            <?php
                            }
                          }else{
                            ?>
                            <option value="2" <?php echo $status == 2?'selected':''; ?>>Tidak Tersedia</option>
                            <?php
                          }
                            ?>

                        </select></td>
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
$('.sidebar-menu').find('#menu-persediaan-aset').addClass('active');
</script>
<script>
$(function(){
  $('.date').datepicker({dateFormat: 'yy-mm-dd'});
})
</script>
<?php echo $footer; ?>
