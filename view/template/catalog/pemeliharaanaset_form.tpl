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
            <h3 class="box-title">Pemeliharaan Aset</h3>
            <div class="button pull-right">
                <a onclick="simpan()" ><button type="button" class="btn btn-primary">Simpan</button></a>
									<a href="<?php echo $cancel; ?>"><button type="button" class="btn btn-danger">Kembali</button></a>

								</div>
          </div>
          <div class="box-body">
            <div class="row">
              <div class="col-md-12 errordisplay">
                <?php if ($error_warning) { ?>
                <div class="alert alert-danger alert-dismissible">
                  <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                  <h4><i class="icon fa fa-ban"></i> Alert!</h4>
                  <?php
                  foreach($error_warning as $e){
                    echo $e.' <br>';
                  }
                  ?>
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
                      <td><span class="required">*</span> Nama Aset</td>
                      <td><input type="text" class="form-control" name="name"  value="" required >
                        <input type="hidden" class="form-control" name="aset_id"  value="" required >
                      </td>
                    </tr>
                    <tr>
                      <td><span class="required">*</span> Tanggal Pemeliharaan</td>
                      <td><input type="text" class="form-control date" name="tanggal"  value="" required>

                      </td>
                    </tr>

                    <tr>
                      <td>Kategori Pemeliharaan</td>
                      <td>
                          <select name="akun" class="form-control">
                            <option value="6222">Pemeliharaan Kendaraan</option>
                            <option value="6223">Pemeliharaan Peralatan Kantor</option>
                            <option value="6224">Pemeliharaan Peralatan Produksi</option>
                            
                          </select>
                      </td>
                    </tr>

                    <tr>
                      <td>Jenis Pemeliharaan</td>
                      <td>
                         <!--  <select class="form-control pemeliharaan" name="pemeliharaan_id">
                          <?php
                          foreach($asets as $c){
                          ?>
                            <option value="<?php echo $c['id']; ?>" ><?php echo $c['name']; ?></option>
                          <?php
                          }
                          ?>
                          </select> -->
                          <input type="text" name="pemeliharaan_nama" class="form-control">
                          <input type="hidden" name="pemeliharaan_id" class="form-control">
                      </td>
                    </tr>

                    <tr>
                      <td><span class="required">*</span> Biaya</td>
                      <td><input type="text" class="form-control" name="biaya"  value="" />

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
$('.sidebar-menu').find('#menu-persediaan').addClass('active');
$('.sidebar-menu').find('#menu-pemeliharaan-aset').addClass('active');
</script>
<script>
function simpan(){
  var error=false;
  $(".error").remove();
  var em='';
  if($("input[name='aset_id']").val() == ""){
    error=true;
    em +="Nama aset harus dipilih <br>";
  }
  if($("select[name='bank_id']").val() == ""){
    error=true;
    em +="Bank harus dipilih <br>";
  }
  if($("input[name='tanggal']").val() == ""){
    error=true;
    em +="Tanggal harus dipilih <br>";
  }
  if($("input[name='biaya']").val() == ""){
    error=true;
    em +="Biaya harus dipilih <br>";
  }
  if(error){
    html='<div class="alert alert-danger alert-dismissible">';
    html +='<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>';
    html +='<h4><i class="icon fa fa-ban"></i> Alert!</h4>';
    html +=em;
    html +='</div>';

    $('.errordisplay').html(html);
  }else{
    $("#form").submit();
  }
  //alert($("input[name='aset_id']").val());
}
</script>
<script>
$(function(){
  //simpan();
  $('.date').datepicker({dateFormat: 'yy-mm-dd'});
  $("input[name='name'").autocomplete({
    delay: 0,
    source: function(request, response) {
      $.ajax({
        url: 'index.php?route=catalog/aset/autocompletefull&token=<?php echo $token; ?>&filter_name=' + encodeURIComponent(request.term),
        dataType: 'json',
        success: function(json) {
          //alert(JSON.stringify(json));
          response($.map(json, function(item) {
            return {
              label: item.text,
              value: item.id,
            }
          }));
        }


      });
    },
    select: function(event, ui) {
      //alert(JSON.stringify(ui));
    //  $("input[name='product["+coba+"][name]']").remove();
      $("input[name='name']").val(ui.item['label']);
      $("input[name='aset_id']").val(ui.item['value']);
      return false;
    },
    focus: function(event, ui) {
          return false;
      }
  });

   $("input[name='pemeliharaan_nama'").autocomplete({
    delay: 0,
    source: function(request, response) {
      $.ajax({
        url: 'index.php?route=catalog/pemeliharaanaset/autocompletejenispemeliharaan&token=<?php echo $token; ?>&filter_name=' + encodeURIComponent(request.term),
        dataType: 'json',
        success: function(json) {
          //alert(JSON.stringify(json));
          response($.map(json, function(item) {
            return {
              label: item.text,
              value: item.id,
            }
          }));
        }


      });
    },
    select: function(event, ui) {
      //alert(JSON.stringify(ui));
    //  $("input[name='product["+coba+"][name]']").remove();
      $("input[name='pemeliharaan_nama']").val(ui.item['label']);
      $("input[name='pemeliharaan_id']").val(ui.item['value']);
      return false;
    },
    focus: function(event, ui) {
          return false;
      }
  });


  $(".bank").select2({
    ajax: {
    url:"index.php?route=keuangan/bank/autocomplete&token=<?php echo $this->request->get['token']; ?>",
    dataType: 'json',
    data: function (params) {
      return {
        q: params.term,
        c:1

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
})
</script>

<?php echo $footer; ?>
