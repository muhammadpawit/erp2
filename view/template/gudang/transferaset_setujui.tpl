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
            <h3 class="box-title">Proses Transfer Aset</h3>
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
                      <td><?php echo date('d F Y',strtotime($permintaan['tanggal'])); ?></td>
                  </tr>

                  <tr>
                     <td>Gudang:</td>
                     <td><?php echo $permintaan['nama']; ?></td>
                 </tr>

                 <tr>
                    <td>Gudang:</td>
                    <td><?php echo $permintaan['nama']; ?></td>
                </tr>
                <tr>
                   <td>Keterangan:</td>
                   <td><?php echo $permintaan['keterangan']; ?></td>
               </tr>


               <tr>
                   <td>Tanggal Proses:</td>
                   <td><input readonly type="text" class="date form-control" name="tglproses" value="<?php echo date('Y-m-d'); ?>"></td>
               </tr>




                </table>
                <table class="table">
                    <thead>
                      <th>Nama Aset</th>
                      <th>Nama Produk</th>

                    </thead>
                    <tbody>

                      <tr>
                        <td><?php echo empty($aset['name'])?$aset['no_tabung']:$aset['name']; ?>


                        </td>
                        <td><?php echo $product['name']; ?>

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
$('.sidebar-menu').find('#menu-persediaan').addClass('active');
$('.sidebar-menu').find('#menu-persediaan-produkdagang').addClass('active');
$('.sidebar-menu').find('#menu-tukar-kran').addClass('active');
</script>
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



  if(!$("input[name='tglproses']").val()){
    error=true;
    em +="Tanggal Pemrosesan Harus Diisi <br>";
  }

  qtytabungasal=$("input[name='qty_a']").val();
  qtykranasal=$("input[name='qty_b']").val();
  qtybutuh=$("input[name='quantity']").val();

  if(Number(qtytabungasal) < Number(qtybutuh)){
    error=true;
    em +="Quantity tabung asal tidak tersedia <br>";
  }

  if(Number(qtykranasal) < Number(qtybutuh)){
    error=true;
    em +="Quantity kran asal tidak tersedia <br>";
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
