<?php echo $header; ?>
<div class="content-wrapper">
  <section class="content-header">
    <h1>

    </h1>

  </section>

  <section class="content">
    <div class="row">
      <div class="col-md-12">
        <div class="box">
          <div class="box-header with-border">
            <h3 class="box-title">Proses Tukar Kran</h3>
            <div class="button pull-right">
              <a onclick="simpan()" ><button type="button" class="btn btn-primary">Simpan</button></a>
                <a href="<?php echo $cancel; ?>"><button type="button" class="btn btn-danger">Kembali</button></a>

                </div>
          </div>
          <div class="box-body">
            <div class="row">
              <div class="col-md-12">
                <div class="errordisplay"></div>

              </div>
            </div>
            <div class="row">
              <div class="col-md-12">
                <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
                <table class="table">
                  <tr>
                      <td>Tanggal Permintaan:</td>
                      <td><?php echo date('d F Y',strtotime($permintaan['tgl_tukar'])); ?></td>
                  </tr>

                  <tr>
                     <td>Gudang:</td>
                     <td><?php echo $permintaan['nama']; ?></td>
                 </tr>
                 <tr>
                    <td>Quantity Tukar:</td>
                    <td><?php echo $permintaan['quantity']; ?></td>
                </tr>
                <tr>
                   <td>Tambahan Harga:</td>
                   <td><?php echo $this->currency->format($permintaan['tambahan_harga']); ?></td>
               </tr>
               <tr>
                 <td>Keterangan</td>
                 <td><b><?php echo $permintaan['keterangan']; ?></b></td>
               </tr>

               <tr>
                   <td>Tanggal Proses:</td>
                   <td><input readonly type="text" class="date form-control" name="tglproses" value=""></td>
               </tr>




                </table>
                <table class="table">
                    <thead>
                      <th>Tabung Asal</th>
                      <th>Kran Asal</th>
                      <th>Tabung Hasil</th>
                      <th>Kran Yang Akan Dilepas</th>
                    </thead>
                    <tbody>

                      <tr>
                        <td><?php echo $tabung_a['name']; ?>
                          <br><small>(Qty Tersedia: <?php echo $tabung_a['quantity']; ?>)</small>
                          <input type="hidden" class="form-control" name="tabung_a" value="<?php echo $permintaan['tabung_a']; ?>">
                          <input type="hidden" class="form-control" name="kran_b" value="<?php echo $permintaan['kran_b']; ?>">
                          <input type="hidden" class="form-control" name="qty_a" value="<?php echo $tabung_a['quantity']; ?>">
                          <input type="hidden" class="form-control" name="qty_b" value="<?php echo $kran_b['quantity']; ?>">
                          <input type="hidden" class="form-control" name="quantity" value="<?php echo $permintaan['quantity']; ?>">
                        </td>
                        <td><?php echo $kran_b['name']; ?>
                          <br><small>(Qty Tersedia: <?php echo $kran_b['quantity']; ?>)</small>
                          </td>
                        <td><?php echo $tabung_b['name']; ?></td>
                        <td><?php echo $kran_lepasan['name']; ?></td>
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
$('.sidebar-menu').find('#menu-persediaan').addClass('active');
$('.sidebar-menu').find('#menu-persediaan-produkdagang').addClass('active');
$('.sidebar-menu').find('#menu-tukar-kran').addClass('active');
</script>
<script type="text/javascript"><!--
$('.date').datepicker({dateFormat: 'yy-mm-dd',minDate:'<?php echo $locktanggal; ?>',maxDate:new Date()});

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



  if(!$("input[name='tglproses']").val()){
    error=true;
    em +="Tanggal Pemrosesan Harus Diisi <br>";
  }

  qtytabungasal=$("input[name='qty_a']").val();
  tabung_a=$("input[name='tabung_a']").val();
  qtykranasal=$("input[name='qty_b']").val();
  kran_b=$("input[name='kran_b']").val();
  qtybutuh=$("input[name='quantity']").val();

  if(Number(qtytabungasal) < Number(qtybutuh)){
    error=true;
    em +="Quantity tabung asal tidak tersedia <br>";
  }

  if(kran_b > 0){
    if(Number(qtykranasal) < Number(qtybutuh)){
      error=true;
      em +="Quantity kran asal tidak tersedia <br>";
    }
  }

  tanggal=$("input[name='tglproses']").val();
  if(tanggal < '<?php echo $locktanggal;?>'){
    error=true;
    em+="Tanggal tidak   boleh kurang dari <?php echo $locktanggal;?> <br>";
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
