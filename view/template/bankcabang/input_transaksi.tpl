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
            <h3 class="box-title">Bank <?php echo $bank['name']; ?></h3>
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

                  <tr>
                    <td><span class="required">*</span> Jenis Transaksi</td>
                    <td><select name="transaksi" id="transaksi" class="form-control">
                        <option value="1">Kas Masuk</option> <?php //debet otomatis sesuai akun bank ?>
                        <option value="8">Kas Keluar</option> <?php //kredit otomatis sesuai akun bank ?>

                        <?php
                        if(count($banks) > 1){
                        ?>
                        <option value="7">Kas Transfer</option> <?php //debet otomatis sesuai akun bank, kredit otomatis bank tujuan ?>
                        <?php
                        }
                        ?>



                  </select>
                  </td>
                  </tr>
                  <tr id="aruskas">
                    <td><span class="required">*</span> Jenis Arus Saldo</td>
                    <td><select name="arus_kas" class="form-control">
                        <option value="1">Saldo Masuk</option>
                        <option value="2">Saldo Keluar</option>

                  </select>
                  </td>
                  </tr>
                  <tr id="transfer" style="display:none;">
                    <td><span class="required">*</span> Bank/Kas Tujuan</td>
                    <td><select name="tujuan" class="form-control">
                  <?php
                  foreach($banks as $b){
                  if($b['id'] != $this->request->get['bank_id']){
                  ?>
                  <option value="<?php echo $b['id'] ?>"><?php echo $b['name'] ?></option>
                  <?php
                  }
                  }
                  ?>
                  </select>
                  </td>
                  </tr >
                  <tr >
                    <td><span class="required">*</span> Tanggal Transaksi</td>
                    <td><input type="text" class="date form-control" id="date-end" name="date_trans" size="100" value="" />
                  <?php if (isset($error_tgl_transaksi)) { ?>
                  <span class="error"><?php echo $error_tgl_transaksi; ?></span>
                  <?php } ?>
                     </td>
                  </tr>
                  <tr>
                    <td> Keterangan</td>
                    <td><input type="text" class="form-control" name="keterangan" size="100" value="" />
                      <input type="hidden" class="form-control" name="bank_id" size="100" value="<?php echo $this->request->get['bank_id']; ?>" />
                     </td>
                  </tr>
                  <tr>
                  <td><span class="required">*</span> Nominal</td>
                  <td><input type="text" class="form-control" name="nominal" size="100" value="" />
                    <input type="hidden" name="bank_id" size="100" value="<?php echo $this->request->get['bank_id']; ?>" />
                  <?php if (isset($error_nominal)) { ?>
                  <span class="error"><?php echo $error_nominal; ?></span>
                  <?php } ?>
                  </td>
                  </tr>
                  </table>
                  <?php $debet=0;?>
                  <table class="table table-bordered" id="ref-debet">
                      <thead>
                        <tr>
                          <td></td>
                          <th>Akun</th>
                          <th>Nominal</th>
                          <th>Keterangan</th>
                        </tr>
                      </thead>
                      <tbody>
                        
                      </tbody>
                      <tfoot>
                        <td colspan="3"></td>
                        <td align="right"><a onclick="tambahrefdebet()" class="btn btn-primary">Tambah</a></td>
                      </tfoot>
                  </table>
                  <?php $kredit=0;?>
                  <table class="table table-bordered" id="ref-kredit">
                      <thead>
                        <tr>
                          <td></td>
                          <th>Akun</th>
                          <th>Nominal</th>
                          <th>Keterangan</th>
                        </tr>
                      </thead>
                      <tbody>
                        
                      </tbody>
                      <tfoot>
                        <td colspan="3"></td>
                        <td align="right"><a onclick="tambahrefkredit()" class="btn btn-primary">Tambah</a></td>
                      </tfoot>
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
$('.sidebar-menu').find('#menu-bank').addClass('active');

</script>
<script type="text/javascript"><!--
function simpan(){
  $(".error").remove();
  error=false;
  jenistransaksi=$('#transaksi').val();

  em='';
  //alert($("input[name='date_trans']").val());
  if($("input[name='date_trans']").val() == null |  $("input[name='date_trans']").val() == ""){
    error=true;
    em +="Tanggal Transaksi Harus Diisi <br>";
  }
  
  tanggal=$("input[name='date_trans']").val();
  if(tanggal < '<?php echo $locktanggal;?>'){
    error=true;
    em+="Tanggal tidak   boleh kurang dari <?php echo $locktanggal;?> <br>";
  }

  if($("input[name='nominal']").val() == null | $("input[name='nominal']").val() == ""){
    error=true;
    em +="Nominal Harus Diisi <br>";
  }

  if(jenistransaksi == 6){
    if($("select[name='ref_debet']").val() == null | $("select[name='ref_debet']").val() == undefined){
      error=false;
      //em +="Akun Debet Harus Dipilih <br>";
    }
    if($("select[name='ref_kredit']").val() == null | $("select[name='ref_kredit']").val() == undefined){
      error=true;
      em +="Akun Kredit Harus Dipilih <br>";
    }
  }

  if(jenistransaksi == 1){
    if($("select[name='ref_kredit']").val() == null | $("select[name='ref_kredit']").val() == undefined){
      error=false;
      //em +="Akun Kredit Harus Dipilih <br>";
    }
  }

  if(jenistransaksi == 8){
    if($("select[name='ref_debet']").val() == null | $("select[name='ref_debet']").val() == undefined){
      error=false;
      //em +="Akun Debet Harus Dipilih <br>";
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
  }
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

	$('.date').datepicker({dateFormat: 'yy-mm-dd',minDate:'<?php echo $locktanggal; ?>',maxDate:new Date()});
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

var penolong_row=0;
function tambahrefkredit(){
  html  = '<tbody id="penolong-row' + penolong_row + '">';
    html +='<tr>';
    html +='<td class="center" style="width: 3px;"><img src="view/image/delete.png" onclick="hapuspenolong('+penolong_row+')" style="cursor: pointer;" /></td>';
    html +='<td><select name="ref_kredit['+penolong_row+'][akun]" class="form-control coa"></select></td>';
    html +='<td><input type="text" name="ref_kredit['+penolong_row+'][nominal]" class="form-control" required></td>';
    html +='<td><input type="text" name="ref_kredit['+penolong_row+'][keterangan]" class="form-control" required value="-"></td>';
    html += '</tr>';
    html += '</tbody>';

    $('#ref-kredit tfoot').before(html);
    penolong_row++;
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
}

var debet_row=0;
function tambahrefdebet(){
  html  = '<tbody id="penolong-row' + debet_row + '">';
    html +='<tr>';
    html +='<td class="center" style="width: 3px;"><img src="view/image/delete.png" onclick="hapuspenolong('+debet_row+')" style="cursor: pointer;" /></td>';
    html +='<td><select name="ref_debet['+debet_row+'][akun]" class="form-control coa"></select></td>';
    html +='<td><input type="text" name="ref_debet['+debet_row+'][nominal]" class="form-control" required></td>';
    html +='<td><input type="text" name="ref_debet['+debet_row+'][keterangan]" class="form-control" required value="-"></td>';
    html += '</tr>';
    html += '</tbody>';

    $('#ref-debet tfoot').before(html);
    debet_row++;
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
}

function hapuspenolong(id){
  $("#penolong-row"+id).remove();
  $('#ref-kredit tbody').each(function(){
    parent=$(this).data('parent');
    if(parent == id){
      $(this).remove();
    }
  });
}

</script>
<?php echo $footer; ?>
