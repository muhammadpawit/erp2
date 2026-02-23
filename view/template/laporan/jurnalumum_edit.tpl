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
            <h3 class="box-title">Edit Jurnal <?php echo $jurnal['keterangan']; ?></h3>
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
                  <?php foreach($error_warning as $e){
                    echo $e.'<br>';
                  } ?>
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
                       <td style="width:30%"><span class="required">*</span>Tanggal</td>
                       <td colspan="2"><input type="text" name="tanggal" class="date form-control" value="<?php echo $jurnal['tanggal']; ?>" readonly >
                       </td>
                     </tr>
                     <tr>
                      <td style="width:30%"><span class="required">*</span>Total Debet</td>
                      <td colspan="2"><input type="text" name="totaldebet" id="totaldebet" class="form-control" value="0" readonly >
                      </td>
                    </tr>
                    <tr>
                     <td style="width:30%"><span class="required">*</span>Total Kredit</td>
                     <td colspan="2"><input type="text" name="totalkredit" id="totalkredit" class="form-control" value="0" readonly >
                     </td>
                   </tr>
                   <tr>
                    <td style="width:30%"><span class="required">*</span>Nominal Kurang</td>
                    <td colspan="2"><input type="text" name="nominalkurang" id="nominalkurang" class="form-control" value="0" readonly >
                    </td>
                  </tr>
                  <tr>
                    <td style="width:30%"><span class="required">*</span>Keterangan</td>
                    <td colspan="2"><input type="text" name="keterangan" id="keterangan" class="form-control" value="<?php echo $jurnal['keterangan']; ?>" >
                    </td>
                  </tr>



                    </table>
                    <table class="table table-bordered"  id="list-product">
                      <thead>
                        <tr>
                          <th></th>
                          <th>Ref Akun</th>
                          <th>Debet</th>
                          <th>Kredit</th>
                          <th>Urutan Tampil</th>
                        </tr>
                      </thead>
                      <?php
                      $i=1;
                      foreach($detail as $d){
                      ?>
                      <tbody id="product-row<?php echo $i; ?>">

                        <tr>
                        <td class="center" style="width: 3px;"><img src="view/image/delete.png" onclick="$('#product-row<?php echo $i; ?>').remove()" style="cursor: pointer;" /></td>
                          <td><input type="text" class="form-control" name="detail[<?php echo $i; ?>][ref_akun]" value="<?php echo $d['ref_akun'];?>"><input type="hidden" class="form-control" name="detail[<?php echo $i; ?>][id]" value="<?php echo $d['id'];?>"></td>
                          <td><input type="text" class="form-control" onblur="updateTotal()" name="detail[<?php echo $i; ?>][debet]" value="<?php echo $d['debet'];?>"></td>
                          <td><input type="text" class="form-control" onblur="updateTotal()" name="detail[<?php echo $i; ?>][kredit]" value="<?php echo $d['kredit'];?>"></td>
                          <td><input type="text" class="form-control" name="detail[<?php echo $i; ?>][urutan]" value="<?php echo $d['urutan'];?>"></td>
                        </tr>

                      </tbody>
                      <?php
                      $i++;
                      }
                      ?>
                      <tfoot>
                        <tr>
                          <td colspan="4"></td>
                          <td class="left"><a onclick="addModule();" class="btn btn-success">Tambah</a></td>
                        </tr>
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
$('.sidebar-menu').find('#menu-akuntansi').addClass('active');
$('.sidebar-menu').find('#menu-jurnal').addClass('active');
</script>

<script type="text/javascript"><!--

    var product_row = <?php echo $i; ?>;

function addModule() {
	html  = '<tbody id="product-row' + product_row + '">';
	html += '  <tr>';
  html +='<td class="center" style="width: 3px;"><img src="view/image/delete.png" onclick="$(\'#product-row'+product_row+'\').remove()" style="cursor: pointer;" /></td>';

	html += '    <td class="left"><input type="text" class="form-control product-name" name="detail[' + product_row + '][ref_akun]" value=""/></td>';
  html += '    <td class="left"><input type="text" class="form-control product-name" name="detail[' + product_row + '][debet]" onblur="updateTotal()" value="0"/></td>';
  html += '    <td class="left"><input type="text" class="form-control product-name" name="detail[' + product_row + '][kredit]"  onblur="updateTotal()" value="0"/></td>';
  html += '    <td class="left"><input type="text" class="form-control product-name" name="detail[' + product_row + '][urutan]" value="0"/></td>';
  html += '  </tr>';
	html += '</tbody>';

	$('#list-product tfoot').before(html);

	product_row++;
}

function updateTotal(){
  totaldebet=0;
  totalkredit=0;

  //totalpembulatan=0;
  i = 0;
  while(i < product_row){
    //alert(i);
    harga=0;
  // alert($("input[name='product["+i+"][quantity]']").val());
    debet=$("input[name='detail["+i+"][debet]']").val();
    kredit=$("input[name='detail["+i+"][kredit]']").val();


    if($("input[name='detail["+i+"][ref_akun]']").val() != undefined){
      if($("input[name='detail["+i+"][ref_akun]']").val()){
        if($.isNumeric( Number(debet) ) ){
          if(debet != 0){
            totaldebet += Number(debet);
          }
        }

        if($.isNumeric( Number(kredit) ) ){
          if(kredit != 0){
            totalkredit += Number(kredit);
          }
        }
      }

    }
    i++;
  }
  $("#totaldebet").val(totaldebet);
    $("#totalkredit").val(totalkredit);
  $("#nominalkurang").val(Math.abs(totaldebet-totalkredit));

}

$(document).ready(function() {
  updateTotal();
	$('.date').datepicker({dateFormat: 'yy-mm-dd'});
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

});


function simpan(){
  $(".error").remove();
  error=false;
  updateTotal();
  em='';

  totaldebet=Number($("#totaldebet").val());
  totalkredit=Number($("#totalkredit").val());

  if(totaldebet != totalkredit){
    error=true;
    em +='Total Debet dan Kredit Tidak Sama.';
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
