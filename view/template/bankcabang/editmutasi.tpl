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
            <h3 class="box-title">Mutasi Bank <?php echo $bank['name'].' Dengan Keterangan '.$mutasi['keterangan']; ?></h3>
            <div class="button pull-right">
                <a onclick="simpan();" ><button type="button" class="btn btn-primary">Simpan</button></a>
									<a href="<?php echo $cancel; ?>"><button type="button" class="btn btn-danger">Kembali</button></a>

								</div>
          </div>
          <div class="box-body">
						<div class="row">
              <div class="col-md-12">
                <div class="errordisplay"></div>
								<?php if ($error) { ?>

                <div class="alert alert-danger alert-dismissible">
                  <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                  <h4><i class="icon fa fa-ban"></i> Alert!</h4>
									<?php
									foreach($error as $e){
						  		?>
						  			<p><?php echo $e; ?></p>
						  		<?php
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
                  <table id="options" class="table table-stripped">


                  <tr >
                    <td><span class="required">*</span> Tanggal Transaksi</td>
                    <td><input type="text" class="form-control" id="date-end" name="date_trans" size="100" value="<?php echo date('Y-m-d',strtotime($mutasi['date_trans']))?>" readonly />
                      
                     </td>
                  </tr>


                  <tr>
                  <td><span class="required">*</span> Saldo Masuk</td>
                  <td><input type="text" class="form-control" name="saldomasuk" size="100" value="<?php echo $mutasi['saldomasuk'];?>" />

                  </td>
                  </tr>

                  <tr>
                  <td><span class="required">*</span> Saldo Keluar</td>
                  <td><input type="text" class="form-control" name="saldokeluar" size="100" value="<?php echo $mutasi['saldokeluar'];?>" />

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
$('.sidebar-menu').find('#menu-bankcabang').addClass('active');

</script>
<script type="text/javascript"><!--
function simpan(){
  /*$(".error").remove();
  error=false;
  jenistransaksi=$('#transaksi').val();

  em='';
  //alert($("input[name='date_trans']").val());
  if($("input[name='date_trans']").val() == null |  $("input[name='date_trans']").val() == ""){
    error=true;
    em +="Tanggal Transaksi Harus Diisi <br>";
  }

  if($("input[name='nominal']").val() == null | $("input[name='nominal']").val() == ""){
    error=true;
    em +="Nominal Harus Diisi <br>";
  }

  if(jenistransaksi == 6){
    if($("select[name='ref_debet']").val() == null | $("select[name='ref_debet']").val() == undefined){
      error=true;
      em +="Akun Debet Harus Dipilih <br>";
    }
    if($("select[name='ref_kredit']").val() == null | $("select[name='ref_kredit']").val() == undefined){
      error=true;
      em +="Akun Kredit Harus Dipilih <br>";
    }
  }

  if(jenistransaksi == 1){
    if($("select[name='ref_kredit']").val() == null | $("select[name='ref_kredit']").val() == undefined){
      error=true;
      em +="Akun Kredit Harus Dipilih <br>";
    }
  }

  if(jenistransaksi == 8){
    if($("select[name='ref_debet']").val() == null | $("select[name='ref_debet']").val() == undefined){
      error=true;
      em +="Akun Debet Harus Dipilih <br>";
    }
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
  }*/
  $('#form').submit();
}
$(document).ready(function() {
  $(".coa").select2({
    ajax: {
    url:"index.php?route=keuangan/coa/autocompletes&token=<?php echo $this->request->get['token']; ?>",
    dataType: 'json',
    data: function (params) {
      return {
        filter_name: params.term,

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

	$('#date-end').datepicker({dateFormat: 'yy-mm-dd'});
	$('#transaksi').change(function(){
		valu=$(this).val();
		if(valu == 7){
			$('#transfer').removeAttr('style');
      $('#aruskas').attr('style','display:none');
      $('#customer').attr('style','display:none');
      $('#shipping').attr('style','display:none');

      $('#ref-kredit').attr('style','display:none');
      $('#ref-debet').attr('style','display:none');
		}
    else if(valu == 6){
      $('#transfer').attr('style','display:none');
      $('#aruskas').attr('style','display:none');
      $('#customer').attr('style','display:none');
      $('#shipping').attr('style','display:none');

      $('#ref-debet').removeAttr('style');
      $('#ref-kredit').removeAttr('style');
    }

		else{
      if(valu == 1){
        $('#ref-debet').attr('style','display:none');
        $('#ref-kredit').removeAttr('style');
      }
      if(valu == 8){
        $('#ref-kredit').attr('style','display:none');
        $('#ref-debet').removeAttr('style');
      }
			$('#transfer').attr('style','display:none');
      $('#aruskas').attr('style','display:none');
      //$('#aruskas').removeAttr('style');
      $('#customer').attr('style','display:none');
      $('#shipping').attr('style','display:none');
		}
	});
$('#transaksi').trigger('change');
});

//--></script>
<?php echo $footer; ?>
