<?php echo $header; ?>
<div id="content" class="content-wrapper">
  <section class="content-header">
    <h1>

    </h1>

  </section>

  <section class="content">
    <div class="row">
      <div class="col-md-12">
        <div class="box">
          <div class="box-header with-border">
            <h3 class="box-title">Generate Laba Rugi</h3>
            <div class="button pull-right">
									<a onclick="simpan()" class="button"><button type="button" class="btn btn-primary">Proses Laba Rugi</button></a>
                  <a href="<?php echo $cancel; ?>"><button type="button" class="btn btn-warning">Kembali</button></a>
								</div>
          </div>
          <div class="box-body">
            <div class="row">
              <div class="col-md-12">
                <?php if ($error_warning) { ?>
                <div class="alert alert-danger alert-dismissible">
                  <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                  <h4><i class="icon fa fa-ban"></i> Alert!</h4>
                  <?php echo $error_warning; ?>
                </div>
                <?php
                }
                ?>
                <?php if (isset($success)) { ?>
                <div class="alert alert-success alert-dismissible">
                  <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                  <h4><i class="icon fa fa-check"></i> Success!</h4>
                  <?php echo $success; ?>
                </div>
                <?php
                }
                ?>
                <div class="errordisplay"></div>
              </div>

            </div>
            <div class="row">
              <div class="col-md-12">
                <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
                  <table class="table table-stripped">
                    <tr>
                      <td><span class="required">*</span>  Nama Periode</td>
                      <td>
                        <select name="periode_id"  class="periode form-control">

                          </select>
                      </td>
                    </tr>
                    <tr>
                      <td>Tanggal Awal Periode</td>
                      <td><input type="text" name="tglawal" class="form-control" readonly value=""  />
                      </td>
                    </tr>
                    <tr>
                      <td>Tanggal Akhir Periode</td>
                      <td><input type="text" name="tglselesai" class="form-control" readonly value=""  />
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
$('.sidebar-menu').find('#menu-keuangan').addClass('active');
$('.sidebar-menu').find('#menu-generate-labarugi').addClass('active');
</script>
<script type="text/javascript"><!--
$(document).ready(function() {
	$('.date').datepicker({dateFormat: 'yy-mm-dd'});

  $(".periode").select2({
    ajax: {
    url:"index.php?route=kepegawaian/periode/autocomplete&token=<?php echo $this->request->get['token']; ?>",
    //url: "index.php?route=pamerantoko/toko/autocomplete&token=<?php echo $this->request->get['token']; ?>",
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
    //console.log(e);
    //console.log($(this).val());
    id=$(this).val();
    //alert(id);
    if(id != undefined & id != null){
    $.ajax({
      url: 'index.php?route=kepegawaian/periode/detail&token=<?php echo $this->request->get['token']; ?>&periode_id=' + id,
      dataType: 'json',
      success: function(json) {
        //alert(JSON.stringify(json));
        if(json){
          $("input[name='tglawal']").val(json.tgl_awal);
          $("input[name='tglselesai']").val(json.tgl_selesai);
        }
      }
    })
  }


  });;


});
function simpan(){
  $(".error").remove();
  error=false;
  errdup=false;
  errqty=false;
  em='';
  if($("select[name='periode_id']").val() == null){
    error=true;
    em +="Periode Harus Dipilih <br>";
  }

  if($("input[name='tglawal']").val() == null){
    error=true;
    em +="Tanggal Awal Perhitungan tidak Boleh Kosong <br>";
  }

  if($("input[name='tglselesai']").val() == null){
    error=true;
    em +="Tanggal Selesai Perhitungan tidak Boleh Kosong <br>";
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
//--></script>
<?php echo $footer; ?>
