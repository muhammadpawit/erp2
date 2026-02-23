<?php echo $header; ?>
<div id="content" class="content-wrapper">
  <section class="content-header">
    <h1>

    </h1>

  </section>

  <section class="content">
    <div class="row">
      <div class="col-md-12">
        <div class="box">
          <div class="box-header with-border">
            <h3 class="box-title">Tambah Riwayat Transaksi </h3>
            <div class="button pull-right">
									<a onclick="simpan();" class="button"><button type="button" class="btn btn-primary">Simpan</button></a>
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
                  <input class="form-control"  type="hidden" name="vendor_id" value="<?php echo $this->request->get['id']?>" autocomplete="off" />
                  <table class="table table-stripped">
                    <tr>
                      <td>Tanggal</td>
                      <td><input class="form-control date" id="date_trans" type="text" name="date_trans" autocomplete="off" /></td>
                    </tr>
                    <tr>
                      <td>Keterangan</td>
                      <td><input class="form-control" id="keterangan"  type="text" name="keterangan" size="100" value="" />
                        </td>
                    </tr>
                    <tr>
                      <td>Saldo Masuk</td>
                      <td><input class="form-control" id="saldomasuk" type="number" name="saldomasuk" size="100" value="0" />
                        </td>
                    </tr>
                    <tr>
                      <td>Saldo Keluar</td>
                      <td><input class="form-control" id="saldokeluar" type="number" name="saldokeluar" size="100" value="0" />
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
$('.sidebar-menu').find('#menu-pembelian').addClass('active');
$('.sidebar-menu').find('#menu-vendor').addClass('active');
$('.sidebar-menu').find('#menu-vendor-lokal').addClass('active');
</script>
<script>
$(function(){$('.date').datepicker({dateFormat:'yy-mm-dd'});})

function simpan(){
  tgl=$("#date_trans").val();
  keterangan=$("#keterangan").val();
  saldomasuk=$("#saldomasuk").val();
  saldokeluar=$("#saldokeluar").val();
  if(tgl==''){
    alert('tanggal transaksi harus diisi');
    $("#date_trans").focus();
    return false;
  }
  if(keterangan==''){
    alert('keterangan transaksi harus diisi');
    $("#keterangan").focus();
    return false;
  }
  if(saldomasuk > 0 && saldokeluar > 0){
    alert("harus diisi salah satu. saldomasuk / saldokeluarnya");
    return false;
  }
  if(saldomasuk == 0 && saldokeluar == 0){
    alert("harus diisi salah satu. saldomasuk / saldokeluarnya");
    return false;
  }
  $("form").submit();
}
</script>

<?php echo $footer; ?>
