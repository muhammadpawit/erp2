<?php echo $header; ?>
<div class="content-wrapper">
  <section class="content-header">
    <h1>

    </h1>

  </section>

  <section class="content">
  <form method="POST" action="<?php echo $action?>" id="form">
    <div class="row">
      <div class="col-md-12">
        <div class="box">
          <div class="box-header with-border">
            <h3 class="box-title">Pembelian Lokal</h3>
            <div class="button pull-right">
                  <a onclick="simpan()" ><button type="button" class="btn btn-primary">Simpan</button></a>
                  <a href="<?php echo $cancel; ?>"><button type="button" class="btn btn-danger">Kembali</button></a>
								</div>
          </div>
          <div class="box-body">

            <div class="row">
              <div class="col-md-12">
                <div class="errordisplay"></div>
                <table class="table">
                  <tr>
                      <td>Nomor PO:</td>
                      <td><?php echo $permintaan['no_po'] ?>
                        <input class="form-control" type="hidden" name="pembelian_kredit_id" value="<?php echo $permintaan['id'] ?>">
                      </td>
                  </tr>
                  <tr>
                      <td>Gudang:</td>
                      <td><?php echo $gudang['nama'] ?>
                        <input class="form-control" type="hidden" name="gudang_id" value="<?php echo $gudang['gudang_id'] ?>">
                      </td>
                  </tr>
                  <tr>
                      <td>No. Surat Jalan:</td>
                      <td><input class="form-control" type="text" required name="no_suratjalan" value=""></td>
                  </tr>
                  <tr>
                      <td>Tanggal Surat Jalan:</td>
                      <td><input class="form-control date" readonly type="text" required name="tgl_surat" value="<?php echo date('Y-m-d'); ?>"></td>
                  </tr>
                  <tr>
                      <td>Tanggal Barang Datang:</td>
                      <td><input class="date form-control" type="text" required name="tgl_terima" readonly value="<?php echo date('Y-m-d'); ?>"></td>
                  </tr>
                  <tr>
                     <td>Jenis Persediaan</td>
                     <td >
                       <select name="jenispersediaan" class="form-control coa">

                       </select>
                     </td>
                   </tr>
                   <tr>
                      <td>No. Polisi:</td>
                      <td><input class="form-control" type="text" name="no_pol" value=""></td>
                  </tr>
                  <tr>
                     <td>Penerima:</td>
                     <td>
                       <select name="penerima_id" class="penerima form-control">

                         </select>
                       <input class="form-control" type="hidden" name="penerima" value=""></td>
                 </tr>
                 <tr>
                    <td>Pengangkut:</td>
                    <td>
                      <select name="pengangkut_id" class="penerima form-control">

                        </select>
                      <input class="form-control" type="hidden" name="pengangkut" value="">
                    </td>
                </tr>
                <?php
                if($permintaan['jenis_barang'] == 1){
                ?>
                <tr>
                   <td>Tangal Mulai Pengisian:</td>
                   <td><input class="form-control date" type="text" name="tglawal" value="<?php echo date('Y-m-d'); ?>"></td>
               </tr>
                 <tr>
                    <td>Jam Mulai Pengisian:</td>
                    <td><input class="form-control jam" type="text" name="jamawal" value="<?php echo date('h:i:s'); ?>"></td>
                </tr>
                <tr>
                   <td>Tangal Selesai Pengisian:</td>
                   <td><input class="form-control date" type="text" name="tglakhir" value="<?php echo date('Y-m-d'); ?>"></td>
               </tr>
               <tr>
                  <td>Jam Selesai Pengisian:</td>
                  <td><input class="form-control jam" type="text" name="jamakhir" value="<?php echo date('h:i:s'); ?>"></td>
              </tr>
                <?php
                }
                ?>


            </table>
                <table class="table">
                    <thead>
                      <tr>
                      <th>Nama Produk</th>
                      <th>Quantity</th>
                      <th>Quantity Telah Diterima</th>
                      <th>Quantity Datang</th>
                      <?php
                      if($permintaan['jenis_barang'] == 1){
                      ?>
                      <th>Level Awal</th>
                      <th>Level Akhir</th>
                      <?php
                      }
                      ?>
                    </tr>
                    </thead>
                    <tbody>
                      <?php
                      $i=1;
                      foreach($products as $p){
                      ?>
                      <tr>
                        <td><?php echo $p['product_name']; ?>
                          <input class="form-control" type="hidden" name="products[<?php echo $i; ?>][id]" value="<?php echo $p['id']; ?>">
                        </td>
                        <td><?php echo $p['quantity']; ?></td>
                        <td><?php echo $p['quantityterima']; ?></td>
                        <td>
                          <?php
                          $sisa= $p['quantity']-$p['quantityterima'];
                          if($sisa > 0){
                          ?>
                            <input class="form-control" type="text" name="products[<?php echo $i; ?>][quantityterima]" value="<?php echo $sisa;?>">
                          <?php
                        }else{
                          ?>
                          <input class="form-control" readonly  type="hidden" name="products[<?php echo $i; ?>][quantityterima]" value="0">
                          <?php
                        }
                          ?>
                          <input class="form-control" readonly  type="hidden" name="products[<?php echo $i; ?>][sisa]" value="<?php echo $sisa;?>">
                        </td>
                          <?php
                          if($permintaan['jenis_barang'] == 1){
                          ?>
                          <td><input class="form-control" type="text" name="products[<?php echo $i; ?>][levelawal]" value="<?php echo $p['detail']['level'];?>" readonly></td>
                          <td><input class="form-control" type="text" name="products[<?php echo $i; ?>][levelakhir]" value="0"></td>
                          <?php
                          }
                          ?>
                        </tr>
                        <?php
                        $i++;
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
$('.sidebar-menu').find('#menu-pembelian').addClass('active');
$('.sidebar-menu').find('#menu-pembelian-kredit').addClass('active');
$(function(){
  $('.jam').datetimepicker({
      format: 'LT'
  });
  $('.date').datepicker({dateFormat: 'yy-mm-dd'});
  $(".penerima").select2({
    ajax: {
    url:"index.php?route=user/user/autocomplete&token=<?php echo $this->request->get['token']; ?>",
      dataType: 'json',
    data: function (params) {
      return {
        q: params.term,
        //j:21

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
  $(".coa").select2({
    ajax: {
    url:"index.php?route=keuangan/coa/autocompletes&token=<?php echo $this->request->get['token']; ?>",
    dataType: 'json',
    data: function (params) {
      return {
        filter_name: params.term,
        p:1200

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

product_detail_row=<?php echo $i; ?>;
function simpan(){
  $(".error").remove();
  error=false;
  errdup=false;
  em='';

  cek = [];

  for(i=0;i<product_detail_row;i++ ){
    if($("input[name='products["+i+"][product_id]']").val() != undefined){
      qty=$("input[name='products["+i+"][quantityterima]']").val();
      sisa=$("input[name='products["+i+"][sisa]']").val();

      if(Number(qty) > Number(sisa)){
        error=true;
        em+="Quantity Terima melebihi quantity yang dipesan<br>";
      }
    }
  }



  if($("select[name='penerima_id']").val() == null){
    error=true;
    em+="Penerima Harus Dipilih<br>";
  }
  if($("select[name='jenispersediaan']").val() == null){
    error=true;
    em+="Jenis Persediaan Harus Dipilih<br>";
  }

  if($("select[name='pengangkut_id']").val() == null){
    error=true;
    em+="Pengangkut Harus Dipilih<br>";
  }

  if($("input[name='tgl_surat']").val() == ""){
    error=true;
    em+="Tanggal Surat Jalan harus diisi<br>";
  }

  if($("input[name='tgl_terima']").val() == ""){
    error=true;
    em+="Tanggal Barang Datang harus diisi<br>";
  }

  if($("input[name='no_suratjalan']").val() == ""){
    error=true;
    em+="Nomor Surat Jalan harus diisi<br>";
  }


  if(error){
    /*if(errdup){
      em+= "Terdapat duplikasi data Surat Jalan/Sales Order.<br>";
    }*/
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
