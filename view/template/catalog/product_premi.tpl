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
            <h3 class="box-title">Premi <?php echo $product['name']; ?></h3>
            <div class="button pull-right">
                <a onclick="$('#form').submit();" ><button type="button" class="btn btn-primary">Simpan</button></a>
									<a href="<?php echo $cancel; ?>"><button type="button" class="btn btn-danger">Kembali</button></a>

								</div>
          </div>
          <div class="box-body">

            <div class="row">
              <div class="col-md-12">
                <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
                  <table id="special" class="table">
                    <thead>
                      <tr>
                        <th></th>
                        <th>Kode Premi</th>
                        <th>0 - 500</th>
                        <th>501 - 1000</th>
                        <th>1001 - 1500</th>
                        <th> > 1500</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr>
                        <td colspan="6"><b>Premi Jual</b></td>
                      </tr>
                      <tr>
                        <td><input type="radio" name="premijual" value="0" <?php echo empty($product['premijual'])?'checked':'';?>></td>
                        <td>0</td>
                        <td>0</td>
                        <td>0</td>
                        <td>0</td>
                        <td>0</td>
                      </tr>
                      <?php
                      foreach($premis as $p){
                      ?>
                      <tr>
                        <td><input type="radio" name="premijual" value="<?php echo $p['id']; ?>" <?php echo $product['premijual']==$p['id']?'checked':'';?>></td>
                        <td><?php echo $p['id']; ?></td>
                        <td><?php echo $p['kelompok']; ?></td>
                        <td><?php echo $p['kelompok2']; ?></td>
                        <td><?php echo $p['kelompok3']; ?></td>
                        <td><?php echo $p['kelompok4']; ?></td>

                      </tr>
                      <?php
                      }
                      ?>
                      <tr>
                        <td colspan="6"><b>Premi Kirim</b></td>
                      </tr>
                      <tr>
                        <td><input type="radio" name="premikirim" value="0" <?php echo empty($product['premikirim'])?'checked':'';?>></td>
                        <td>0</td>
                        <td>0</td>
                        <td>0</td>
                        <td>0</td>
                        <td>0</td>
                      </tr>
                      <?php
                      foreach($premis as $p){
                      ?>
                      <tr>
                        <td><input type="radio" name="premikirim" value="<?php echo $p['id']; ?>" <?php echo $product['premikirim']==$p['id']?'checked':'';?>></td>
                        <td><?php echo $p['id']; ?></td>
                        <td><?php echo $p['kelompok']; ?></td>
                        <td><?php echo $p['kelompok2']; ?></td>
                        <td><?php echo $p['kelompok3']; ?></td>
                        <td><?php echo $p['kelompok4']; ?></td>

                      </tr>
                      <?php
                      }
                      ?>
                      <tr>
                        <td colspan="6"><b>Premi Ambil</b></td>
                      </tr>
                      <tr>
                        <td><input type="radio" name="premiambil" value="0" <?php echo empty($product['premiambil'])?'checked':'';?>></td>
                        <td>0</td>
                        <td>0</td>
                        <td>0</td>
                        <td>0</td>
                        <td>0</td>
                      </tr>
                      <?php
                      foreach($premis as $p){
                      ?>
                      <tr>
                        <td><input type="radio" name="premiambil" value="<?php echo $p['id']; ?>" <?php echo $product['premiambil']==$p['id']?'checked':'';?>></td>
                        <td><?php echo $p['id']; ?></td>
                        <td><?php echo $p['kelompok']; ?></td>
                        <td><?php echo $p['kelompok2']; ?></td>
                        <td><?php echo $p['kelompok3']; ?></td>
                        <td><?php echo $p['kelompok4']; ?></td>

                      </tr>
                      <?php
                      }
                      ?>
                      <tr>
                        <td colspan="6"><b>Premi Bongkar</b></td>
                      </tr>
                      <tr>
                        <td><input type="radio" name="premibongkar" value="0" <?php echo empty($product['premibongkar'])?'checked':'';?>></td>
                        <td>0</td>
                        <td>0</td>
                        <td>0</td>
                        <td>0</td>
                        <td>0</td>
                      </tr>
                      <?php
                      foreach($premis as $p){
                      ?>
                      <tr>
                        <td><input type="radio" name="premibongkar" value="<?php echo $p['id']; ?>" <?php echo $product['premibongkar']==$p['id']?'checked':'';?>></td>
                        <td><?php echo $p['id']; ?></td>
                        <td><?php echo $p['kelompok']; ?></td>
                        <td><?php echo $p['kelompok2']; ?></td>
                        <td><?php echo $p['kelompok3']; ?></td>
                        <td><?php echo $p['kelompok4']; ?></td>

                      </tr>
                      <?php
                      }
                      ?>
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
$('.sidebar-menu').find('#menu-persediaan').addClass('active');
$('.sidebar-menu').find('#menu-persediaan-produkdagang').addClass('active');
$('.sidebar-menu').find('#menu-produk-gudang').addClass('active');
</script>




<?php echo $footer; ?>
