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
            <h3 class="box-title">Buka Produksi</h3>
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
                         <td>Tanggal Mulai:</td>
                         <td><input type="text" class="form-control date" name="tanggalmulai" value="<?php echo date('Y-m-d',time()); ?>" readonly></td>
                     </tr>
                     <tr>
                         <td>Jam Mulai:</td>
                         <td><input type="text" class="form-control time" name="waktumulai" value="<?php echo date('h:i:s',time()); ?>" readonly>

                         </td>
                     </tr>
                    <tr>
                         <td>Keterangan</td>
                         <td><input type="text" name="keterangan" class="form-control" value="" ></td>
                     </tr>

                  </table>
                  <div class="callout callout-success lead">
                    <h4>Bahan Baku</h4>

                  </div>
                  <table class="table">
                      <thead>
                        <th>Bahan Baku</th>
                        <th>Level Awal</th>
                        <th>Quantity Awal</th>

                    </thead>
                      <tbody>
                        <?php
                        $row=1;
                        foreach($bahan as $p){
                        ?>
                        <tr>
                          <td><?php echo $p['name'];?><input type="hidden" class="form-control" name="bahan[<?php echo $row;?>][bahanbaku_id]" value="<?php echo $p['id']; ?>"></td>
                          <td><input type="text" class="form-control" name="bahan[<?php echo $row;?>][levelawal]" value="<?php echo $p['level']; ?>" readonly></td>
                          <td><input type="text" class="form-control" name="bahan[<?php echo $row;?>][qtyawal]" value="<?php echo $p['quantity']; ?>" readonly></td>

                        </tr>
                        <?php
                        $row++;
                        }
                        ?>
                      </tbody>
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
$('.sidebar-menu').find('#menu-produksi').addClass('active');


</script>

<script type="text/javascript"><!--
$(document).ready(function() {
	$('.date').datepicker({dateFormat: 'yy-mm-dd'});


});
//--></script>
<script type="text/javascript"><!--
    var product_row = <?php echo $product_row; ?>;

function addModule() {
	html  = '<tbody id="product-row' + product_row + '">';
	html += '  <tr>';
  html +='<td class="center" style="width: 3px;"><img src="view/image/delete.png" onclick="$(\'#product-row'+product_row+'\').remove()" style="cursor: pointer;" /></td>';

	html += '    <td class="left"><input type="text" data-id="'+product_row+'" onkeyup="komplit('+product_row+')" class="product-name" name="product[' + product_row + '][name]" value=""/><input type="hidden" name="product[' + product_row + '][product_id]" value="0" /></td>';
  html += '    <td class="right"><input type="text" name="product[' + product_row + '][spesifikasi]" value=""/></td>';
  html += '    <td class="right"><input type="text" name="product[' + product_row + '][quantity]" value="1" /></td>';
        html += '    <td class="right"><input type="text" name="product[' + product_row + '][keterangan]" value="" /></td>';

	html += '  </tr>';
	html += '</tbody>';

	$('#list-product tfoot').before(html);

	product_row++;
}


//--></script>


<script type="text/javascript" src="view/javascript/jquery/ui/jquery-ui-timepicker-addon.js"></script>
<script type="text/javascript"><!--
$('.date').datepicker({dateFormat: 'yy-mm-dd'});
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

  if($("input[name='tanggalmulai']").val() == ""){
    em +="Tanggal Buka Produksi harus diisi";
    error=true;
  }
  if($("input[name='waktumulai']").val() == ""){
    em +="Jam Buka Produksi harus diisi";
    error=true;
  }

  //cek

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
