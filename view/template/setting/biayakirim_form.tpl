<?php echo $header; ?>

<div class="content-wrapper" >
  <section class="content-header">
    <h1>

    </h1>

  </section>
  <section class="content" id="content">
    <div class="row">
      <div class="col-md-12">
        <div class="box">
          <div class="box-header with-border">
            <h3 class="box-title">Tambah Biaya Pengiriman</h3>
            <div class="button pull-right">
              <!--<a onclick="simpan()" class="btn btn-info">Simpan</a>-->
              <a onclick="simpan()" class="btn btn-info">Simpan</a>
              <a onclick="cancel()" class="btn btn-danger">Cancel</a>
						</div>
          </div>
          <div class="box-body">
            <div class="row">
              <div class="col-md-12">
                <form method="post" action="<?php echo $simpan?>">
                    <div class="form-group">
                      <label>Tanggal Mulai Berlaku</th>
                      <?php if(isset($this->request->get['id'])){?>
                        <?php foreach($biayas as $b){?>
                          <?php if($b['id']==$this->request->get['id']){ ?>
                            <input type="text" id="tglmulaiberlaku" name="tglmulaiberlaku" class="form-control date" value="<?php echo $b['tglmulaiberlaku'] ?>" disabled/>
                          <?php } ?>
                        <?php } ?>
                      <?php }else{ ?>
                        <input type="text" id="tglmulaiberlaku" name="tglmulaiberlaku" class="form-control date" value=""/>
                      <?php } ?>
                    </div>
                    <table class="table table-bordered" id="special">
                      <thead>
                        <tr>
                            <!--<th>Mulai berlaku</th>-->
                            <th>Metode Pengiriman</th>
                            <th>Tangerang</th>
                            <th>Surabaya</th>
                            <th></th>
                        </tr>
                      </thead>
                      <?php 
                      //if(isset($this->request->get['id'])){
                        $special_row =0;
                      /*}else{
                        $special_row =0;
                      }*/
                      ?>
                      <tbody>
                      <?php if(isset($this->request->get['id'])){ ?>
                        <?php foreach($biayasdetail as $bd){ ?>
                          <?php if($this->request->get['id']==$bd['id_biayakirim']){?>
                          <tr>
                              <td>
                                <?php 
                                
                                   if($bd['metode_pengiriman']==1){
                                                  echo "Diambil";
                                                }else if($bd['metode_pengiriman']==2){
                                                  echo "Diantar truck";
                                                }else if($bd['metode_pengiriman']==3){
                                                  echo "Diantar motor";
                                                }else{
                                                  
                                                }

                                ?>
                              </td>
                              <input type="hidden" name="product_special[<?php echo $special_row ?>][id]" class="form-control" value="<?php echo $bd['id'] ?>"/>
                              <input type="hidden" name="product_special[<?php echo $special_row ?>][metode_pengiriman]" class="form-control" value="<?php echo $bd['metode_pengiriman'] ?>"/>
                              <td><input type="text" name="product_special[<?php echo $special_row ?>][kirimtgr]" class="form-control" value="<?php echo $bd['kirimtgr'] ?>"/></td>
                              <td><input type="text" name="product_special[<?php echo $special_row ?>][kirimsby]" class="form-control" value="<?php echo $bd['kirimsby'] ?>"/></td>
                          </tr>
                          <?php $special_row++ ?>
                          <?php } ?>
                        <?php } ?>
                      <?php } ?>
                      </tbody>
                      <tfoot>
                        <tr>
                          <td colspan="3"></td>
                          <td>
                            <?php if(isset($this->request->get['id'])){?>

                            <?php }else{ ?>
                            <a onclick="addSpecial();"  class="btn btn-info">Tambah</a>
                            <?php }?>
                          </td>
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
$('.sidebar-menu').find('#menu-system').addClass('active');
$('.sidebar-menu').find('#menu-setting').addClass('active');

var special_row =<?php echo $special_row ?>;
function addSpecial() {
	html  = '<tbody id="special-row' + special_row + '">';
	html += '<tr>';
  //html += '<td class="left"><input type="text" class="date" name="product_special[' + special_row + '][date]" value="" /></td>';
	html += '<td class="left">';
  html += '<input type="checkbox" name="product_special[' + special_row + '][metode_pengiriman]" value="1" /> Diambil<br>';
  html += '<input type="checkbox" name="product_special[' + special_row + '][metode_pengiriman]" value="2" /> Diantar Truck<br>';
  html += '<input type="checkbox" name="product_special[' + special_row + '][metode_pengiriman][]" value="3" /> Diantar Motor';
  html +='</td>';
  html += '<td class="left"><input type="text" name="product_special[' + special_row + '][kirimtgr]" value="" /></td>';
  html += '<td class="left"><input type="text" name="product_special[' + special_row + '][kirimsby]" value="" /></td>';
	html += '<td class="left"><a onclick="$(\'#special-row' + special_row + '\').remove();" class="btn btn-warning">Hapus</a></td>';
	html += '</tr>';
  html += '</tbody>';
	$('#special tfoot').before(html);
	$('#special-row' + special_row + ' .date').datepicker({dateFormat: 'yy-mm-dd'});
	special_row++;
}

</script>

<script type="text/javascript">
$('.date').datepicker({dateFormat: 'yy-mm-dd'});
function cancel(){
  url='index.php?route=setting/biayakirim&token=<?php echo $token; ?>',
  location = url;
}
function simpan(){
    var tgl=$("#tglmulaiberlaku").val();
    if(tgl!=''){
      $("form").submit();
    }else{
      alert("tanggal harus diisi");
      $("#tglmulaiberlaku").focus();
      return false;
    }
}
function hapus(id){
  var r = confirm("Apakah anda yakni akan menghapus ini?");
  if (r == true) {
    $.ajax({
      url: 'index.php?route=setting/biayakirim/hapus&token=<?php echo $token; ?>&id=' +  encodeURIComponent(id),
      //dataType: 'json',
      success: function(data) {
        if(data!='gagal'){
          alert("Berhasil dihapus");
          location.reload();
        }else{
          alert("gagal menghapus!");
        }
      }
    });
  } else {
    return false;
  }
}
</script>
<script type="text/javascript"><!--
$('#form input').keydown(function(e) {
	if (e.keyCode == 13) {
		filter();
	}
});
//--></script>
<script type="text/javascript"><!--
$('#tabs a').tabs();
$('#timepicker1').timepicker();
//--></script>
<?php echo $footer; ?>
