<?php echo $header; ?>
<!-- Modal -->
<div id="biayaPengiriman" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Tambah </h4>
      </div>
      <div class="modal-body">
        <form class="form-group" action="<?php echo $action ?>" method="post">
          <div class="form-group">
            <label>Gudang</label>
            <select name="gudang_id" class="form-control">
              <option value="*">Pilih</option>
              <option value="1">Tangerang</option>
              <option value="3">Surabaya</option>
            </select>
          </div>
          <div class="form-group">
            <label>Metode Pengiriman</label>
            <select name="metode_pengiriman" class="form-control">
              <option value="0">Pilih</option>
              <option value="1">Diambil</option>
              <option value="2">Diantar</option>
            </select>
          </div>
          <div class="form-group">
            <label>Dikirim menggunakan</label>
            <select name="dikirim_pake" class="form-control">
              <option value="0">Pilih</option>
              <option value="1">Mobil / Truck</option>
              <option value="2">Motor</option>
            </select>
          </div>
          <div class="form-group">
            <label>Biaya Pengiriman</label>
            <input type="text" name="nominal" class="form-control" value="0"/>
          </div>
          <div class="form-group">
            <label></label>
            <input type="submit" name="simpan" class="btn btn-primary" value="Simpan"/>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>
<div class="content-wrapper" >
  <section class="content-header">
    <h1>

    </h1>

  </section>
  <section class="content" id="content">
    <div class="row">
      <div class="col-md-12">
        <div class="box">
          <div class="box-header with-border">
            <h3 class="box-title">Biaya Pengiriman</h3>
            <div class="button pull-right">
              <!--
              <?php if($this->user->getUsername()=="pawit"){?>
                  <a href="<?php echo $insert ?>" class="btn btn-info">Tambah</a>
              <?php }else{ ?>
                <a data-toggle="modal" data-target="#biayaPengiriman" class="btn btn-info">Tambah</a>
              <?php } ?>-->
              <a href="<?php echo $insert ?>" class="btn btn-info">Tambah</a>
						</div>
          </div>
          <div class="box-body" style="overflow:hidden">
            <div class="row">
              <div class="col-md-12">
                <?php if ($success) { ?>
                <div class="alert alert-success alert-dismissible">
                  <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                  <h4><i class="icon fa fa-check"></i> Success!</h4>
                  <?php echo $success; ?>
                </div>
                <?php
                }
                ?>

                <?php if ($error_warning) { ?>
                <div class="alert alert-danger alert-dismissible">
                  <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                  <h4><i class="icon fa fa-check"></i> Alert!</h4>
                  <?php echo $error_warning; ?>
                </div>
                <?php
                }
                ?>
              </div>
            </div>

            <div class="row">
              <div class="col-md-12">
                <?php //if($this->user->getUsername()=="pawit"){?>
                <div class="col-md-12">
                  <div class="box-group" id="accordion">
                        <?php foreach($biayas as $bk){?>                  
                        <div class="panel box box-danger">
                          <div class="box-header with-border">
                            <h4 class="box-title">
                              <a data-toggle="collapse" data-parent="#accordion" href="#bkirim<?php echo $bk['id'] ?>" class="collapsed" aria-expanded="false">
                                Tanggal Berlaku <?php echo date('d/m/Y',strtotime($bk['tglmulaiberlaku'])) ?>
                              </a>
                              
                            </h4>
                          </div>
                          <div id="bkirim<?php echo $bk['id'] ?>" class="panel-collapse collapse" aria-expanded="false" style="height: 0px;">
                            <div class="box-body">
                              <div class="form-group pull-right">
                                <a href="<?php echo $edit ?>&id=<?php echo $bk['id'] ?>" class="badge bg-yellow"><i class="fa fa-edit"></i></a>
                                <a onclick="hap(<?php echo $bk['id'] ?>)" class="badge bg-red"><i class="fa fa-trash"></i></a>
                              </div>
                              <table class="table table-bordered">
                                <thead>
                                  <tr>
                                    <td>Metode</td>
                                    <td>Tangerang</td>
                                    <td>Surabaya</td>
                                  </tr>
                                </thead>
                                <tbody>
                                  <?php foreach($biayasdetail as $bd ){ ?>
                                      <?php if($bd['id_biayakirim']==$bk['id']){ ?>
                                        <tr>
                                          <td>
                                            <?php
                                                if($bd['metode_pengiriman']==1){
                                                  echo "Diambil";
                                                }else if($bd['metode_pengiriman']==2){
                                                  echo "Diantar truck";
                                                }else if($bd['metode_pengiriman']==3){
                                                  echo "Diantar motor";
                                                }else{
                                                  
                                                }
                                            ?>
                                          </td>
                                          <td><?php echo $this->currency->format($bd['kirimtgr']) ?></td>
                                          <td><?php echo $this->currency->format($bd['kirimsby']) ?></td>
                                        </tr>
                                      <?php }?>
                                  <?php } ?>
                                </tbody>
                              </table>
                            </div>
                          </div>
                        </div>
                        <?php } ?>
                      </div>
                </div>
                <?php //} ?>
                <!--
                <table class="table table-bordered">
                  <thead>
                    <tr>
                      <th>#</th>
                      <th>Gudang</th>
                      <th>Metode Pengiriman</th>
                      <th>Dikirim Menggunakan</th>
                      <th>Biaya</th>
                      <th></th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php $no=1; foreach($biaya as $b){?>
                      <tr>
                        <td><?php echo $no++ ?></td>
                        <td><?php echo $b['gudang_id']==1?'Tangerang':'Surabaya' ?></td>
                        <td><?php echo $b['metode_pengiriman']==2?'Diantar':'Diambil' ?></td>
                        <td>
                          <?php 
                              if($b['dikirim_pake']==1){
                                echo "Mobil / Truck";
                              }else if($b['dikirim_pake']==1){
                                echo "Motor";
                              }else{
                                echo "-";
                              }
                          ?>
                        </td>
                        <td><?php echo $this->currency->format($b['nominal']) ?></td>
                        <td><a onClick="hapus(<?php echo $b['id'] ?>)" class="badge bg-red">Hapus</a></td>
                      </tr>
                    <?php } ?>
                  </tbody>
                </table>
                -->
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
$('.sidebar-menu').find('#menu-system').addClass('active');
$('.sidebar-menu').find('#menu-setting').addClass('active');

//--></script>
<script type="text/javascript">
$('.date').datepicker({dateFormat: 'yy-mm-dd'});

function hap(id){
  var r = confirm("Apakah anda yakni akan menghapus ini?");
  if (r == true) {
    $.ajax({
      url: 'index.php?route=setting/biayakirim/hapusnew&token=<?php echo $token; ?>&id=' +  encodeURIComponent(id),
      //dataType: 'json',
      success: function(data) {
        if(data!='gagal'){
          alert("Berhasil dihapus");
          location.reload();
        }else{
          alert("gagal menghapus!");
        }
      }
    });
  } else {
    return false;
  }
}

function hapus(id){
  var r = confirm("Apakah anda yakni akan menghapus ini?");
  if (r == true) {
    $.ajax({
      url: 'index.php?route=setting/biayakirim/hapus&token=<?php echo $token; ?>&id=' +  encodeURIComponent(id),
      //dataType: 'json',
      success: function(data) {
        if(data!='gagal'){
          alert("Berhasil dihapus");
          location.reload();
        }else{
          alert("gagal menghapus!");
        }
      }
    });
  } else {
    return false;
  }
}
</script>
<script type="text/javascript"><!--
$('#form input').keydown(function(e) {
	if (e.keyCode == 13) {
		filter();
	}
});
//--></script>
<script type="text/javascript"><!--
$('#tabs a').tabs();
$('#timepicker1').timepicker();
//--></script>
<?php echo $footer; ?>
