<?php echo $header; ?>
<div class="content-wrapper">
  <section class="content-header">
    <h1>

    </h1>

  </section>

  <section class="content">
    <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
    <div class="row">
      <div class="col-md-12">
        <div class="box">
          <div class="box-header with-border">
            <h3 class="box-title">Tutup Produksi</h3>
            <div class="button pull-right">
              <a onclick="simpan()" ><button type="button" class="btn btn-primary">Simpan</button></a>
                  <a href="<?php echo $cancel; ?>"><button type="button" class="btn btn-danger">Kembali</button></a>
								</div>
          </div>
          <div class="box-body">

            <div class="row">
              <div class="col-md-12">
                <table class="table">
                  <tr>
                      <td>Tanggal Mulai:</td>
                      <td><?php echo date('d F Y',strtotime($permintaan['tanggalmulai'])) ?>
                        <input type="hidden" name="id" value="<?php echo $permintaan['id']; ?>">
                      </td>
                  </tr>
                  <tr>
                      <td>Jam Pesan:</td>
                      <td><?php echo date('H:i:s',strtotime($permintaan['tanggalmulai'])) ?></td>
                  </tr>
                  <tr>
                      <td>Tanggal Selesai:</td>
                      <td><input type="text" class="form-control date" name="tanggalselesai" value="<?php echo date('Y-m-d',time()); ?>" readonly></td>
                  </tr>
                  <tr>
                      <td>Jam Selesai:</td>
                    <td><input type="text" class="form-control time" name="waktuselesai" value="<?php echo date('h:i:s',time()); ?>" readonly>
                  </tr>
                  <tr>
                     <td>Gudang:</td>
                     <td><?php echo !empty($gudang)?$gudang['nama']:'Tanpa Gudang'; ?></td>
                 </tr>

                </table>

                <div class="callout callout-success lead">
                  <h4>Level Bahan Baku</h4>

                </div>
                <table class="table">
                    <thead>
                      <th>Bahan Baku</th>
                      <th>Level Awal</th>
                      <th>Level Akhir</th>
                      <th>Penggembosan</th>



                    </thead>
                    <tbody>
                      <?php
                      $row=1;
                      foreach($products as $p){
                      ?>
                      <tr>
                        <td><?php echo $p['name']; ?>
                          <input type="hidden" class="form-control" name="bahan[<?php echo $row;?>][id]" value="<?php echo $p['id']; ?>" >
                          <input type="hidden" class="form-control" name="bahan[<?php echo $row;?>][bahanbaku_id]" value="<?php echo $p['bahanbaku_id']; ?>" >
                          <input type="hidden" class="form-control" name="bahan[<?php echo $row;?>][levelawal]" value="<?php echo $p['levelawal']; ?>" >
                        </td>
                        <td><?php echo $p['levelawal']; ?></td>
                        <td><input type="text" class="form-control" name="bahan[<?php echo $row;?>][levelakhir]" value="0" ></td>
                        <td><input type="text" class="form-control" name="bahan[<?php echo $row;?>][penggembosan]" value="0" ></td-->

                      </tr>
                      <?php
                      $row++;
                      }
                      ?>
                    </tbody>
                </table>
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
  </form>
  </section>
</div>
</div>
<script>
$('.sidebar-menu').find('#menu-produksi').addClass('active');

</script>
<script type="text/javascript" src="view/javascript/jquery/ui/jquery-ui-timepicker-addon.js"></script>
<script type="text/javascript"><!--
$('.date').datepicker({dateFormat: 'yy-mm-dd'});
$('.datetime').datetimepicker({
	dateFormat: 'yy-mm-dd',
	timeFormat: 'h:m'
});
$('.time').timepicker({timeFormat: 'h:m'});
//--></script>
<script>
function simpan(){
  $(".error").remove();
  error=false;
  errdup=false;
  errqty=false;
  em='';

  if($("input[name='tanggalselesai']").val() == ""){
    em +="Tanggal Selesai Pemrosesan harus diisi";
    error=true;
  }
  if($("input[name='waktuselesai']").val() == ""){
    em +="Jam Selesai Pemrosesan harus diisi";
    error=true;
  }




  if(error){


    html='<div class="alert alert-danger alert-dismissible">';
    html +='<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>';
    html +='<h4><i class="icon fa fa-ban"></i> Alert!</h4>';
    html +=em;
    html +='</div>';

    $('.errordisplay').html(html);;
  }else{
    $('#form').submit();
  }
}
</script>
<?php echo $footer; ?>
