<?php echo $header; ?>
<div class="content-wrapper">
  <section class="content-header">
    <h1>

    </h1>

  </section>

  <section class="content">
    <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
    <div class="row">
      <div class="col-md-12">
        <div class="box">
          <div class="box-header with-border">
            <h3 class="box-title">Proses Produksi</h3>
            <div class="button pull-right">
              <a onclick="simpan()" ><button type="button" class="btn btn-primary">Simpan</button></a>
              <a href="<?php echo $cancel; ?>"><button type="button" class="btn btn-danger">Kembali</button></a>
						</div>
          </div>
          <div class="box-body">

            <div class="row">
              <div class="col-md-12">
                <div class="errordisplay"></div>
                <table class="table">
                  <tr>
                      <td>Nomor Surat:</td>
                      <td><?php echo $permintaan['no_surat'] ?><input type="hidden" class="form-control" name="permintaan" value="<?php echo $permintaan['id']; ?>"></td>
                  </tr>
                  <tr>
                      <td>Gudang:</td>
                      <td><?php echo $permintaan['nama'] ?><input type="hidden" class="form-control" name="gudang_id" value="<?php echo $permintaan['gudang_id']; ?>"></td>
                  </tr>
                  <?php
                  if($permintaan['jenis_produksi'] == 1){
                   ?>
                   <tr>
                      <td>No. SO:</td>
                      <td>
                        <?php
                        echo $permintaan['detailcust']['no_so']; ?>
                        <input type="hidden" class="form-control" name="no_so" value="<?php echo $permintaan['detailcust']['so_id']; ?>">
                      </td>
                  </tr>
                  <tr>
                     <td>Nama Customer:</td>
                     <td>
                       <?php
                       echo $permintaan['detailcust']['name']; ?>
                        <input type="hidden" class="form-control" name="customer_id" value="<?php echo $permintaan['detailcust']['customer_id']; ?>">
                     </td>
                 </tr>
                  <tr>
                    <td>Telephone:</td>
                    <td>
                      <?php
                      echo $permintaan['detailcust']['telephone']; ?></td>
                </tr>
                <tr>
                   <td>Email:</td>
                   <td>
                     <?php
                     echo $permintaan['detailcust']['email']; ?></td>
               </tr>

                   <?php
                  }
                  ?>
                  <tr>
                      <td>Tanggal Pesan:</td>
                      <td><?php echo date('d F Y',strtotime($permintaan['date_added'])) ?></td>
                  </tr>
                  <tr>
                      <td>Jam Pesan:</td>
                      <td><?php echo date('H:i:s',strtotime($permintaan['date_added'])) ?></td>
                  </tr>
                  
                 <tr>
                      <td>Keterangan:</td>
                      <td><input type="text" class="form-control" name="keterangan" value=""></td>
                  </tr>
                  <tr>
                     <td>Jenis Produksi:</td>
                     <td><?php echo $permintaan['jenis_produksi'] == 1?'MR':($permintaan['jenis_produksi'] == 2?'Stok':'MP'); ?>
                        <input type="hidden" class="form-control" name="jenis_produksi" value="<?php echo $permintaan['jenis_produksi']; ?>">
                     </td>
                 </tr>


                </table>
                <div class="callout callout-success lead">
                  <h4>Gas Produksi</h4>

                </div>
                <table class="table">
                    <thead>
                      <th>Jenis Gas</th>
                      <th>Ukuran Tabung</th>

                      <th>Quantity Pesan</th>
                      <th>Quantity Proses</th>
                      <th>Quantity Hasil</th>
                  </thead>
                    <tbody>
                      <?php
                      foreach($products as $p){
                      ?>
                      <tr>
                        <td><?php echo $p['name']; ?>
                          <input type="hidden" class="form-control" name="product_id" value="<?php echo $p['product_id']?>">
                        </td>
                        <td><?php echo $p['namaukuran']; ?><input type="hidden" class="form-control" name="ukuran_tabung" value="<?php echo $p['ukuran_tabung']?>"></td>

                      <td><?php echo $p['quantity']; ?><input type="hidden" class="form-control" name="quantitypesan" value="<?php echo $p['quantity']?>"></td>
                        <td>
                          <?php
                          $sisa=$p['quantity'] - $p['quantity_proses'];
                          ?>
                          <select name="quantityproses" class="form-control">
                            <?php
                            for($i=0;$i<= $sisa;$i++){
                            ?>
                            <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
                            <?php
                            }
                            ?>
                          </select>
                        </td>
                        <td>
                          <?php
                          $sisa=$p['quantity'] - $p['quantity_proses'];
                          ?>
                          <select name="quantityhasil" class="form-control">
                            <?php
                            for($i=0;$i<= $sisa;$i++){
                            ?>
                            <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
                            <?php
                            }
                            ?>
                          </select>
                        </td>
                        <td><?php echo $p['keterangan']; ?></td>
                      </tr>
                      <?php
                      }
                      ?>
                    </tbody>
                </table>
                <div class="callout callout-success lead">
                  <h4>Bahan Baku</h4>

                </div>
                <table class="table">
                    <thead>
                      <th>Bahan Baku</th>
                      <th>Presentase</th>
                      <!--th>Jam Mulai</th>
                      <th>Jam Selesai</th>
                      <th>Level Awal</th>
                      <th>Level Akhir</th>
                      <th>Penggembosan</th-->

                  </thead>
                    <tbody>
                      <?php
                      $row=1;
                      foreach($bahan as $p){
                      ?>
                      <tr>
                        <td><?php echo $p['name'];?><input type="hidden" class="form-control" name="bahan[<?php echo $row;?>][bahanbaku_id]" value="<?php echo $p['bahanbaku_id']; ?>"></td>
                        <td><?php echo $p['jumlah'];?>%<input type="hidden" class="form-control" name="bahan[<?php echo $row;?>][presentase]" value="<?php echo $p['jumlah']; ?>"></td>
                        <!--td><input type="text" class="form-control time" name="bahan[<?php echo $row;?>][jammulai]" value="<?php echo date('h:i:s');?>" readonly></td>
                        <td><input type="text" class="form-control time" name="bahan[<?php echo $row;?>][jamselesai]" value="<?php echo date('h:i:s');?>" readonly></td>
                        <td><input type="text" class="form-control" name="bahan[<?php echo $row;?>][levelawal]" value="<?php echo $p['level']; ?>" readonly></td>
                        <td><input type="text" class="form-control" name="bahan[<?php echo $row;?>][levelakhir]" value="0" ></td>
                        <td><input type="text" class="form-control" name="bahan[<?php echo $row;?>][penggembosan]" value="0" ></td-->
                      </tr>
                      <?php
                      $row++;
                      }
                      ?>
                    </tbody>
                </table>
                <?php
                if($permintaan['jenis_produksi'] == 3){
                ?>
                <div class="callout callout-success lead">
                  <h4>Nomor Tabung</h4>

                </div>
                <table class="table" id="list-product">
                    <thead>
                      <th>Nomor Tabung</th>
                      <th></th>
                  </thead>
                  <?php $product_row=0;?>
                  <tbody>

                  </tbody>
                  <tfoot>
                    <tr>
                      <td ></td>
                      <td class="left"><a onclick="addModule();" class="btn btn-success">Tambah</a></td>
                    </tr>

                  </tfoot>
                </table>
                <?php
                }
                ?>
                <?php
                if($permintaan['jenis_produksi'] == 2){
                ?>
                <div class="callout callout-success lead">
                  <h4>Tabung Gas</h4>

                </div>
                <table class="table" id="list-tabung-ms">
                    <thead>
                      <th></th>
                      <th>Tabung</th>
                      <th>Quantity</th>
                      <th></th>
                  </thead>
                  <?php $product_row=0;?>
                  <tbody>

                  </tbody>
                  <tfoot>
                    <tr>
                      <td ></td>
                      <td ></td>
                      <td ></td>
                      <td class="left"><a onclick="addModuleMs();" class="btn btn-success">Tambah</a></td>
                    </tr>

                  </tfoot>
                </table>
                <?php
                }
                ?>
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
  </form>
  </section>
</div>
</div>
<script>
$('.sidebar-menu').find('#menu-produksi').addClass('active');

</script>
<script>
var product_row='<?php echo $product_row;?>';
function addModule() {
  html  = '<tbody id="product-row' + product_row + '">';
	html += '  <tr>';
  html +='<td class="center" ><img src="view/image/delete.png" onclick="hapus('+product_row+')" style="cursor: pointer;" /></td>';
  html += '    <td class="left"><select data-row="'+product_row+'" name="tabung[' + product_row + '][tabung_id]" class="tabung form-control"></select></td>';
	html += '  <td></td><td></td></tr>';
	html += '</tbody>';

	$('#list-product tfoot').before(html);

	product_row++;

  $(function(){

    $(".tabung").select2({
        ajax: {
        url:"index.php?route=catalog/tabungmp/autocomplete&token=<?php echo $this->request->get['token']; ?>",
          dataType: 'json',
        data: function (params) {
          row=$(this).data('row');
          jenisgas=$("input[name='product_id']").val();
          //alert(jenisgas);
          return {
            q: params.term,
            statustabung:1,
            jenisgas:jenisgas,
            status:4


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
}

function addModuleMs() {
  html  = '<tbody id="product-row' + product_row + '">';
	html += '  <tr>';
  html +='<td class="center" ><img src="view/image/delete.png" onclick="hapus('+product_row+')" style="cursor: pointer;" /></td>';
  html += '    <td class="left"><select data-row="'+product_row+'" name="tabungms[' + product_row + '][product_id]" class="productms form-control"></select><input type="hidden" name="tabungms[' + product_row + '][net_cost]" value="0"></td>';
	html += '  <td><select class="form-control" name="tabungms[' + product_row + '][quantity]"></select></td><td></td></tr>';
	html += '</tbody>';

	$('#list-tabung-ms tfoot').before(html);

	product_row++;

  $(function(){


      $(".productms").select2({
          ajax: {
          url:"index.php?route=catalog/product/autocompleteprod&token=<?php echo $this->request->get['token']; ?>",
            dataType: 'json',
          data: function (params) {
            return {
              q: params.term,
              kategori:195
            //  statustabung:$(".statustabung").val(),
            //  kategori:$(".jenisorder").val()
              //customer_group_id:$('input[name=\'customer_group_id\']').val()

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
            coba=$(this).data('row');
            $.ajax({
              url: 'index.php?route=catalog/productgudang/detail&token=<?php echo $this->request->get['token']; ?>&product_id=' + id+'&gudang_id='+$("input[name='gudang_id']").val(),
              dataType: 'json',
              success: function(json) {
              console.log(JSON.stringify(json));
              //alert(json.detail.quantity);
              html='';
              for(i=0;i<=json.detail.quantity;i++){
                html +='<option value='+i+'>'+i+'</option>';
              }
              $("select[name='tabungms["+coba+"][quantity]']").html(html);
              $("input[name='tabungms["+coba+"][net_cost]']").val(json.detail.net_cost);
              /*$("input[name='product["+coba+"][price]']").val(json.lastprice);
              $("input[name='product["+coba+"][pricelist]']").val(json.pricelist);
              $("input[name='product["+coba+"][batasbawah]']").val(json.batasbawah);
              $("input[name='product["+coba+"][net_cost]']").val(json.detail.net_cost);
              //$("input[name='product["+coba+"][diskon]']").val(json.diskon);
              $

              total=Number($("input[name='product["+coba+"][quantity]']").val() * $("input[name='product["+coba+"][price]']").val());
              $("input[name='product["+coba+"][total]']").val(total);
              $("input[name='product["+coba+"][pajak]']").val(total*0.1);
              if(json.detail.jenistabung > 0){
                $("input[name='product["+coba+"][quantity]']").prop('readonly',true);
                $("input[name='product["+coba+"][jenistabung]']").val(json.detail.jenistabung);
              }
              updatetotal();*/

              }
            })
      })

})
}
function hapus(row){
  $('#product-row'+row).remove();

}
</script>
</script>
<script type="text/javascript" src="view/javascript/jquery/ui/jquery-ui-timepicker-addon.js"></script>
<script type="text/javascript"><!--
$('.date').datepicker({dateFormat: 'yy-mm-dd'});
$('.datetime').datetimepicker({
	dateFormat: 'yy-mm-dd',
	timeFormat: 'h:m'
});
$('.time').timepicker({timeFormat: 'h:m'});
//--></script>
<script>
function simpan(){
  $(".error").remove();
  error=false;
  errdup=false;
  errqty=false;
  em='';

  if($("input[name='tanggal']").val() == ""){
    em +="Tanggal Pemrosesan harus diisi";
    error=true;
  }
  if($("input[name='waktumulai']").val() == ""){
    em +="Jam Pemrosesan harus diisi";
    error=true;
  }

  if($("input[name='jenis_produksi']").val() == 3){
    if(product_row == 0){
      error=true;
      em +="Tabung Gas harus dipilih <br>";
    }
    cek = [];
    total=0;
    for(i=0;i<product_row;i++){
    //  pid=$("select[name='product["+i+"][product_id]']").val();
      tabung_id=$("select[name='tabung["+i+"][tabung_id]']").val();
      if(tabung_id != undefined){
        total++;
        if(cek[tabung_id] == undefined){
          cek[tabung_id]=i;
        }else{
    			errdup = true;
    			error=true;
    			//alert(product_id+' '+p);
    		}
      }
    }
    quantityhasil=$("select[name='quantityhasil']").val()
    if(total > Number(quantityhasil)){
      error=true;
      em +="Jumlah tabung melebihi jumlah hasil produksi <br>";
    }
    if(total < Number(quantityhasil)){
      error=true;
      em +="Jumlah tabung kurang dari jumlah hasil produksi <br>";
    }
    if(total == 0){
      error=true;
      em +="Tabung Gas harus dipilih <br>";
    }
  }

  if($("input[name='jenis_produksi']").val() == 2){
    if(product_row == 0){
      error=true;
      em +="Tabung Gas harus dipilih <br>";
    }
    cek = [];
    total=0;
    for(i=0;i<product_row;i++){
    //  pid=$("select[name='product["+i+"][product_id]']").val();
      tabung_id=$("select[name='tabungms["+i+"][product_id]']").val();

      if(tabung_id != undefined){
          qty=$("select[name='tabungms["+i+"][quantity]']").val();
        total+= Number(qty);
        if(cek[tabung_id] == undefined){
          cek[tabung_id]=i;
        }else{
    			errdup = true;
    			error=true;
    			//alert(product_id+' '+p);
    		}
      }
    }

    quantityhasil=$("select[name='quantityhasil']").val()
    if(total > Number(quantityhasil)){
      error=true;
      em +="Jumlah tabung melebihi jumlah hasil produksi <br>";
    }
    if(total < Number(quantityhasil)){
      error=true;
      em +="Jumlah tabung kurang dari jumlah hasil produksi <br>";
    }
    if(total == 0){
      error=true;
      em +="Tabung Gas harus dipilih <br>";
    }
  }


  if(error){
    if(errdup){
      em+= "Terdapat duplikasi data tabung gas.<br>";
    }

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
