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
            <h3 class="box-title">Kurs Barang Datang</h3>
            <div class="button pull-right">
                <a onclick="simpan()"><button type="button" class="btn btn-primary">Simpan</button></a>
                  <a href="<?php echo $cancel; ?>"><button type="button" class="btn btn-danger">Kembali</button></a>
								</div>
          </div>
          <div class="box-body">

            <div class="row">
              <div class="col-md-12">
                <div class="errordisplay"></div>
                <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
                <table class="table">
                  <tr>
                      <td>Nomor Faktur:</td>
                      <td><?php echo $permintaan['no_faktur'] ?>
                          <input type="hidden" class="form-control" name="id" value="<?php echo $permintaan['id']; ?>" >
                      </td>
                  </tr>
                   <tr>
                      <td>Vendor:</td>
                      <td><?php echo $permintaan['name'] ?></td>
                  </tr>

                 <tr>
                    <td>Jenis Barang:</td>
                    <td><?php echo $permintaan['jenisproduk'] == 1?'Bahan Baku':($permintaan['jenisproduk'] == 2?'Produk Dagang':($permintaan['jenisproduk'] == 3?'ATK':($permintaan['jenisproduk'] == 4?'Aset':'Tabung Gas'))); ?></td>
                </tr>

                <tr>
                    <td>Kurs Barang Datang:</td>
                    <td>
                        <input type="text" class="form-control" name="kursdatang" value="<?php echo $permintaan['kursdatang']; ?>" >
                    </td>
                </tr>

                <tr>
                    <td>Tanggal Kurs:</td>
                    <td>
                        <input type="text" class="date form-control" name="tglkursdatang" readonly value="<?php echo empty($permintaan['tglkursdatang'])?date('Y-m-d'):$permintaan['tglkursdatang']; ?>" >
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
$('.sidebar-menu').find('#menu-pembelian').addClass('active');
$('.sidebar-menu').find('#menu-pembelian-import').addClass('active');


$(document).ready(function() {
	$('.date').datepicker({dateFormat: 'yy-mm-dd'});
})
var klik=1;
function simpan(){
  $(".error").remove();
  error=false;
  em='';


  if($("input[name='kursdatang']").val() == ""){
    error=true;
    em +="Kurs harus diisi.<br>";
  }else{
    if(!$.isNumeric( Number($("input[name='kursdatang']").val()) )){
      error=true;
      em +="Nominal Kurs harus berupa angka.<br>";

    }else{
      if($("input[name='kursdatang']").val() < 1){
        error=true;
        em +="Nominal Kurs harus lebih dari samadengan 1.<br>";
      }
    }
  }

  if(!error){
    if(klik == 1){
      klik++;
      $("#form").submit();
    }
  }else{
    html='<div class="alert alert-danger alert-dismissible">';
    html +='<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>';
    html +='<h4><i class="icon fa fa-ban"></i> Alert!</h4>';
    html +=em;
    html +='</div>';

    $('.errordisplay').html(html);;
    klik=1;
  }
}

</script>

<?php echo $footer; ?>
