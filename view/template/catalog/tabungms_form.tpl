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
            <h3 class="box-title">Tabung Gas</h3>
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
                  foreach($error_warning as $e){
                    echo $e.' <br>';
                  }
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
                      <td>Ukuran Tabung</td>
                      <td>
                          <select class="form-control" name="ukuran_tabung">
                          <?php
                          foreach($ukurans as $c){
                          ?>
                            <option value="<?php echo $c['product_options_id']; ?>" <?php echo $c['product_options_id']==$ukuran_tabung?'selected':''; ?>><?php echo $c['name']; ?></option>
                          <?php
                          }
                          ?>
                          </select>
                      </td>
                    </tr>
                    <?php
                    if(!isset($this->request->get['id'])){
                    ?>

                    <tr >
                      <td>Jenis Gas</td>
                      <td><select name="product_id" class="form-control lokasi-pameran">

                      </select></td>
                    </tr>
                    <?php
                  }else{
                  ?>
                  <tr >
                    <td>Jenis Gas</td>
                    <td><?php echo $namaproduct; ?><input type="hidden" name="product_id" value="<?php echo $product_id;?>"></td>
                  </tr>
                  <?php
                  }
                    ?>
                    <tr>
                      <td>Status</td>
                      <td><select class="form-control status" name="status">
                            <?php
                            //if($hargabeli > 0){
                            ?>
                            <option value="1" <?php echo $status == 1?'selected':''; ?>>Tersedia (Terisi)</option>
                            <option value="3" <?php echo $status == 3?'selected':''; ?>>Hilang</option>
                            <?php
                            //}
                            ?>
                            <option value="2" <?php echo $status == 2?'selected':''; ?>>Tidak Tersedia</option>
                            <option value="4" <?php echo $status == 4?'selected':''; ?>>Tersedia (Kosong)</option>
                            <option value="5" <?php echo $status == 5?'selected':''; ?>>Proses Produksi/Pengisian</option>
                            <option value="6" <?php echo $status == 6?'selected':''; ?>>Dipinjam</option>


                        </select></td>
                    </tr>

                    <tr class="dipinjam">
                      <td>Tanggal Peminjaman</td>
                      <td><input class="form-control date" readonly type="text" name="tglpeminjaman" value="<?php echo date('Y-m-d'); ?>" /></td>
                    </tr>
                    <tr class="dipinjam">
                      <td>Customer</td>
                      <td>
                        <select name="customer_id" style="width:300px;" class="customer form-control">

                          </select>
                      </td>
                    </tr>
                    <tr class="dipinjam">
                      <td>Keterangan</td>
                      <td><input type="text" class="form-control" name="keterangan" value=""  /></td>
                    </tr>
                    <tr class="dipinjam">
                      <td>Biaya Sewa</td>
                      <td><input type="text" class="form-control" name="biayasewa" value="0"  /></td>
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
$('.sidebar-menu').find('#menu-persediaan-tabung').addClass('active');
</script>
<script>

$(function(){
  $(".dipinjam").hide();
  $('.date').datepicker({dateFormat: 'yy-mm-dd'});
  $(".status").on('change',function(){
    stat=$(".status").val();
    if(stat == 6){
      $(".dipinjam").show();
    }else{
      $(".dipinjam").hide();
    }

  });
  $(".lokasi-pameran").select2({
    ajax: {
    url:"index.php?route=catalog/product/autocompleteprod&token=<?php echo $token; ?>",
    //url: "index.php?route=pamerantoko/toko/autocomplete&token=<?php echo $this->request->get['token']; ?>",
    dataType: 'json',
    data: function (params) {
      return {
        q: params.term,
        statustabung:1,
        kategori:200

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
  $(".customer").select2({
    ajax: {
    url:"index.php?route=sale/customer/autocomplete&token=<?php echo $this->request->get['token']; ?>",
      dataType: 'json',
    data: function (params) {
      return {
        q: params.term,
        s:$("select[name='sales']").val()

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
  })
});
</script>
<?php echo $footer; ?>
