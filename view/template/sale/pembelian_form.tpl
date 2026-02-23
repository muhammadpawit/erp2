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
            <h3 class="box-title">Permintaan Pembelian</h3>
            <div class="button pull-right">
              <a onclick="simpan()" ><button type="button" class="btn btn-primary">Simpan</button></a>
                <a href="<?php echo $cancel; ?>"><button type="button" class="btn btn-danger">Kembali</button></a>

								</div>
          </div>
          <div class="box-body">

            <div class="row">
              <div class="col-md-12">
                <div class="errordisplay"></div>
                <?php if ($error_warning) { ?>
                <div class="alert alert-danger alert-dismissible">
                  <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                  <h4><i class="icon fa fa-ban"></i> Alert!</h4>
                  <?php echo $error_warning; ?>
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
                       <td>No. SO</td>
                       <td>
                         <input type="hidden" name="no_so" value="<?php echo $order['id']; ?>"><?php echo $order['no_so']; ?>
                       </td>
                     </tr>
                    <tr>
                       <td><span class="required">*</span>Gudang</td>
                       <td>
                         <input type="hidden" name="gudang_id" value="<?php echo $order['gudang_id']; ?>"><?php echo $order['namagudang']; ?>
                       </td>
                     </tr>

                     <tr>
                         <td>Jenis Pembelian</td>
                         <td>
                           <select class="form-control" name="jenis_pembelian">
                             <!--<option value="1" >Tunai</option>-->
                             <option value="2" >Lokal</option>
                             <option value="3" >Import</option>
                           </select>
                           <input type="hidden" name="jenis_barang" value="2">
                           <input type="hidden" name="jenis_aktiva" value="0">
                           <input type="hidden" name="divisi_asal" value="7">
                         </td>
                     </tr>
                     <!--<tr>
                         <td>Jenis Barang</td>
                         <td>
                           <select class="form-control" name="jenis_barang">
                             <option value="1" >Bahan Baku</option>
                             <option value="2" >Produk Dagang</option>
                             <option value="3" >ATK</option>
                             <option value="4" >Aset</option>
                             <option value="5" >Tabung Gas</option>
                           </status>
                         </td>
                     </tr>
                     <tr>
                         <td>Jenis Aktiva</td>
                         <td>
                           <select class="form-control" name="jenis_aktiva">
                             <option value="0" >Bukan Aktiva</option>
                             <?php
                             foreach($aktivas as $a){
                              ?>
                              <option value="<?php echo $a['no_akun']?>" ><?php echo $a['nama']; ?></option>
                              <?php
                             }
                             ?>
                           </status>
                         </td>
                     </tr>-->
                     <tr>
                         <td>Tujuan Pembelian</td>
                         <td><input type="text" name="tujuan_pembelian" class="form-control" value="" ></td>
                     </tr>

                  </table>
                  <table class="table" id="list-product" >
                    <thead>
                      <tr>
                        <th class="left">Nama Produk</th>
                        <th class="left">Spesifikasi</th>
                         <th class="right">Quantity</th>
                        <th class="right">Keterangan</td>

                      </tr>
                    </thead>
                    <?php $product_row=0;?>
                    <?php $option_row = 0; ?>
                    <?php $download_row = 0; ?>

                    <tbody>
                      <?php
                      foreach($products as $p){
                      ?>
                      <tr>
                        <td><input type="hidden" name="product[<?php echo $product_row; ?>][product_id]" value="<?php echo $p['product_id']; ?>"><input type="hidden" name="product[<?php echo $product_row; ?>][name]" value="<?php echo $p['name']; ?>"><?php echo $p['name']; ?></td>
                        <td><input type="text" class="form-control" name="product[<?php echo $product_row; ?>][spesifikasi]" value=""></td>
                        <td><input type="text" class="form-control" name="product[<?php echo $product_row; ?>][quantity]" value="<?php echo $p['quantity']; ?>"></td>
                        <td><input type="text" class="form-control" name="product[<?php echo $product_row; ?>][keterangan]" value=""></td>
                      </tr>
                      <?php
                      $product_row++;
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
$('.sidebar-menu').find('#menu-pembelian').addClass('active');
$('.sidebar-menu').find('#menu-permintaan-pembelian').addClass('active');
$('.sidebar-menu').find('#menu-surat-permintaan').addClass('active');

</script>

<script type="text/javascript"><!--
$(document).ready(function() {
	$('.date').datepicker({dateFormat: 'yy-mm-dd'});


});
//--></script>



<script type="text/javascript" src="view/javascript/jquery/ui/jquery-ui-timepicker-addon.js"></script>
<script type="text/javascript"><!--
$('.date').datepicker({dateFormat: 'yy-mm-dd'});
$('.datetime').datetimepicker({
	dateFormat: 'yy-mm-dd',
	timeFormat: 'h:m'
});
$('.time').timepicker({timeFormat: 'h:m'});
//--></script>
<script type="text/javascript"><!--
$('.vtabs a').tabs();
//--></script>
<script>
function simpan(){
  $(".error").remove();
  error=false;
  errdup=false;
  errqty=false;
  em='';

  cek = [];

  if(error){
    if(errdup){
      em+= "Terdapat duplikasi data produk.<br>";
    }
    if(errqty){
      em +=" Quantity produk harus lebih dari 0";
    }
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
