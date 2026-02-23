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
            <h3 class="box-title">Surat Jalan Pembelian Produk Dagang</h3>
            <div class="button pull-right">
                <a onclick="simpan()"><button type="button" class="btn btn-primary">Terima</button></a>
                <a href="<?php echo $cancel; ?>"><button type="button" class="btn btn-danger">Kembali</button></a>
              </div>
          </div>
          <div class="box-body">

            <div class="row">
              <form method="POST" action="<?php echo $action?>" id="form">
                <input type="hidden" name="id" value="<?php echo $permintaan['id'] ?>">
              <div class="col-md-12">
                <div class="errordisplay"></div>
                <table class="table">
                  <tr>
                      <td>Nomor Surat Jalan:</td>
                      <td><input type="text" name="no_suratjalan" value="<?php echo $permintaan['no_suratjalan'] ?>" class="form-control"></td>
                  </tr>
                  <tr>
                      <td>Gudang:</td>
                      <td><?php echo $permintaan['nama'] ?></td>
                  </tr>
            </table>
            <table class="table table-responsive" id="list-product-detail" >
              <thead>
                <th>No. PO</th>
                <th>Nama Produk</th>
                <th>Quantity SJ</th>


              </thead>
              <tbody>
                <?php
                //print_r($products);

                foreach($products as $p){

                ?>
                <tr>
                  <td><?php echo $p['no_po']; ?></td>
                  <td><?php echo $p['product_name']; ?></td>
                  <td><?php echo $p['qtyterima']; ?></td>

                </tr>
                <?php
                }
                ?>
              </tbody>
            </table>

              </div>
            </div>
          </form>
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
$('.sidebar-menu').find('#menu-pembelian-kredit').addClass('active');
('.sidebar-menu').find('#menu-barang-datang').addClass('active');

</script>
<script>
$(function(){
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
})
</script>
<script>
function simpan(){
  $(".error").remove();
  error=false;
  errdup=false;
  em='';


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
