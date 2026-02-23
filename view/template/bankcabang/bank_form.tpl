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
            <h3 class="box-title">Bank</h3>
            <div class="button pull-right">
                <a onclick="$('#form').submit();" ><button type="button" class="btn btn-primary">Simpan</button></a>
									<a href="<?php echo $cancel; ?>"><button type="button" class="btn btn-danger">Kembali</button></a>

								</div>
          </div>
          <div class="box-body">
						<div class="row">
              <div class="col-md-12">
								<?php if ($error) { ?>

                <div class="alert alert-danger alert-dismissible">
                  <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                  <h4><i class="icon fa fa-ban"></i> Alert!</h4>
									<?php
									foreach($error as $e){
						  		?>
						  			<p><?php echo $e; ?></p>
						  		<?php
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
                  <table id="options" class="table table-stripped" style="max-width:550px;">

											<tr>
						              <td>Nama Bank</td>
						              <td><input  class="form-control" type="text" name="name" value="<?php echo $name; ?>">

						              </td>
						          </tr>
											<tr>
						              <td>Nomor Rekening</td>
						              <td><input class="form-control" type="text" name="rekening" value="<?php echo $rekening; ?>">

						              </td>
						          </tr>
                      <?php
                      if(!isset($this->request->get['bank_id'])){
                      ?>
                      <tr>
						              <td>Saldo</td>
						              <td>
                            <input class="form-control" type="text" name="saldo" value="<?php echo $saldo; ?>">

						              </td>
						          </tr>
                      <?php
                    }
                      ?>
                      <tr>
						              <td>Pemilik</td>
						              <td><input class="form-control" type="text" name="pemilik" value="<?php echo $pemilik; ?>">

						              </td>
						          </tr>
                      <tr>
						              <td>Cabang</td>
						              <td><input class="form-control" type="text" name="cabang" value="<?php echo $cabang; ?>">

						              </td>
						          </tr>
                      <tr>
						              <td>Kota</td>
						              <td><input class="form-control" type="text" name="kota" value="<?php echo $kota; ?>">

						              </td>
						          </tr>
                      <tr>
						              <td>Swift Code</td>
						              <td><input class="form-control" type="text" name="swiftcode" value="<?php echo $swiftcode; ?>">

						              </td>
						          </tr>
                      <tr>
						              <td>Mata Uang</td>
						              <td>
                            <select class="form-control" name="currency">
                              <option value="1" <?php echo $currency == 1?'selected':''; ?>>Rupiah</option>
                              <option value="2" <?php echo $currency == 2?'selected':''; ?>>USD</option>
                            </select>
						              </td>
						          </tr>
                      <tr>
						              <td>Nomor Akun</td>
						              <td>
                            <?php
                            if(empty($this->request->get['bank_id'])){
                            ?>
                            <select name="rek_parent" class="form-control coa">

                            </select>
                            <?php
                          }else{
                            $this->load->model('keuangan/coa');
                            $akun=$this->model_keuangan_coa->getCategory($rek_parent);
                            echo $akun['kode_rek'].' '.$akun['name'];
                            ?>

                            <?php
                          }
                            ?>
						              </td>
						          </tr>
                      <?php
                      if(!empty($this->request->get['bank_id'])){
                      ?>
                      <tr>
						              <td>Ubah Nomor Akun</td>
						              <td>
                            <select name="rek_parent" class="form-control coa">

                            </select>

						              </td>
						          </tr>
                      <?php
                      }
                      ?>
                      <tr>
						              <td>Display Order <br>
                            <small>Rekening digunakan sebagai metode pembayaran customer?</small>
                          </td>
						              <td>
                            <select class="form-control" name="display_order">
                              <option value="1" <?php echo $display_order == 1?'selected':''; ?>>Ya</option>
                              <option value="0" <?php echo $display_order == 0?'selected':''; ?>>Tidak</option>
                            </select>
						              </td>
						          </tr>
                      <tr>
						              <td>Hutang PRK</td>
						              <td>
                            <select class="form-control" name="hutangprk">
                              <option value="1" <?php echo $hutangprk == 1?'selected':''; ?>>Ya</option>
                              <option value="2" <?php echo $hutangprk == 2?'selected':''; ?>>Tidak</option>
                            </select>
						              </td>
						          </tr>
                      <tr>
                          <td>Plafon PRK</td>
                          <td><input class="form-control" type="text" name="plafon" value="<?php echo $plafon; ?>">

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
$('.sidebar-menu').find('#menu-keuangan').addClass('active');
$('.sidebar-menu').find('#menu-bankcabang').addClass('active');
$(function(){
  $(".coa").select2({
    ajax: {
    url:"index.php?route=keuangan/coa/autocompletes&token=<?php echo $this->request->get['token']; ?>",
    dataType: 'json',
    data: function (params) {
      return {
        filter_name: params.term,
        p:1000
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
})

</script>
<?php echo $footer; ?>
