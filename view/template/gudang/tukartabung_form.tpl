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
            <h3 class="box-title">Permintaan Tukar Kran</h3>
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
                       <td><span class="required">*</span>Gudang</td>
                       <td><select name="gudang_id" class="form-control">

                        <?php
                        foreach($gudangs as $g){
                        ?>
                          <option value="<?php echo $g['gudang_id']; ?>" ><?php echo $g['nama']; ?></option>
                        <?php
                        }
                        ?>
                      </select>
                       </td>
                     </tr>

                     <tr>
                         <td>Tanggal Tukar</td>
                         <td><input type="text" name="tgl_tukar" class="form-control date" readonly value="<?php date('Y-m-d'); ?>" ></td>
                     </tr>

                     <tr>
                         <td>Tambahan Harga per unit</td>
                         <td><input type="text" name="tambahan_harga" class="form-control" value="0" ></td>
                     </tr>

                     <tr>
                         <td>Quantity</td>
                         <td><input type="text" name="quantity" class="form-control" value="0" ></td>
                     </tr>

                     <tr>
                         <td>Tabung Asal</td>
                         <td><select  name="tabung_a" class="product form-control"></select></td>
                     </tr>

                     <tr>
                         <td>Kran Yang Akan Dipasang</td>
                         <td><select  name="kran_b" class="product form-control">
                           <option value="0">Tidak ada kran terpasang</option>
                         </select></td>
                     </tr>

                     <tr>
                         <td>Tabung Hasil</td>
                         <td><select  name="tabung_b" class="product form-control"></select></td>
                     </tr>

                    <tr>
                         <td>Kran Lepasan</td>
                         <td><select  name="kran_lepasan" class="product form-control"></select></td>
                     </tr>

                     <tr>
                        <td>Keterangan</td>
                        <td><textarea name="keterangan" class="form-control" rows="5"></textarea></td>
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
$('.sidebar-menu').find('#menu-persediaan').addClass('active');
$('.sidebar-menu').find('#menu-persediaan-produkdagang').addClass('active');
$('.sidebar-menu').find('#menu-tukar-kran').addClass('active');
</script>

<script type="text/javascript"><!--
$(document).ready(function() {
  $('.date').datepicker({dateFormat: 'yy-mm-dd',minDate:'<?php echo $locktanggal; ?>',maxDate:new Date()});

  $(".product").select2({
      ajax: {
      url:"index.php?route=catalog/product/autocompleteprod&token=<?php echo $this->request->get['token']; ?>",
        dataType: 'json',
      data: function (params) {
        return {
          q: params.term,

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
  }).on("select2:select",function(e){
      id=$(this).val();
      if(id != undefined & id != null){
        gudang_id=$('select[name=\'gudang_id\']').val();
      $.ajax({
        url: 'index.php?route=catalog/productgudang/detail&token=<?php echo $token; ?>&product_id=' + id+'&gudang_id='+gudang_id,
        dataType: 'json',
        success: function(json) {
        console.log(JSON.stringify(json));
        if(json.detail.name ==undefined){
          $(this).val(null);
          alert("Produk tidak ditemukan");
        }
      }
      })
      }
  })

});
//--></script>




<script type="text/javascript" src="view/javascript/jquery/ui/jquery-ui-timepicker-addon.js"></script>
<script type="text/javascript"><!--

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
  gudang_id=$("select[name='gudang_id']").val();

  if(gudang_id < 1){
    error=true;
    em +="Gudang harus dipilih <br>";
  }

  if($("select[name='tabung_a']").val() == null){
    error=true;
    em +="Tabung Asal harus dipilih <br>";
  }

  if($("select[name='kran_b']").val() == null){
    error=true;
    em +="Kran Asal harus dipilih <br>";
  }


  if($("select[name='tabung_b']").val() == null){
    error=true;
    em +="Tabung Hasil harus dipilih <br>";
  }

  if($("select[name='kran_lepasan']").val() == null){
    error=true;
    em +="Kran Lepasan harus dipilih <br>";
  }

  tanggal=$("input[name='tgl_tukar']").val();
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
