<?php echo $header; ?>
<div class="content-wrapper">
  <section class="content-header">
    <h1>

    </h1>

  </section>

  <section class="content">
    <form id="form" method="POST" action="<?php echo $action; ?>">
    <div class="row">
      <div class="col-md-12">
        <div class="box">
          <div class="box-header with-border">
            <h3 class="box-title">Invoice Pembelian Import</h3>
            <div class="button pull-right">
              <?php
              if(empty($permintaan['no_faktur'])){
              ?>
                <a onclick="simpan()" ><button type="button" class="btn btn-primary">Simpan Invoice</button></a>
              <?php
              }
              ?>
                  <a href="<?php echo $cancel; ?>"><button type="button" class="btn btn-danger">Kembali</button></a>
								</div>
          </div>
          <div class="box-body">

            <div class="row">
              <div class="col-md-12">
                <div class="errordisplay"></div>
                <table class="table">
                  <tr>
                      <td>Nomor PO:</td>
                      <td><?php echo $permintaan['no_po'] ?></td>
                  </tr>
                  <?php
                  if(empty($permintaan['no_faktur'])){
                  ?>
                  <tr>
                      <td>Tanggal Faktur:</td>
                      <td><input type="text" name="tglfaktur" value="<?php echo date('Y-m-d'); ?>" class="date form-control" required readonly></td>
                  </tr>
                  <tr>
                      <td>No. Faktur:</td>
                      <td><input type="text" name="no_faktur" value="" class="form-control" required></td>
                  </tr>
                  <tr>
                      <td>Jatuh Tempo:</td>
                      <td><input type="text" name="batasbayar" value="<?php echo date('Y-m-d'); ?>" class="date form-control"  required readonly></td>
                  </tr>

                  <?php
                }else{
                  ?>
                  <tr>
                      <td>Tanggal Faktur:</td>
                      <td><?php echo date('d/m/y',strtotime($permintaan['tglfaktur']))?></td>
                  </tr>
                  <tr>
                      <td>No. Faktur:</td>
                      <td><?php echo $permintaan['no_faktur']; ?></td>
                  </tr>

                  <tr>
                      <td>Jatuh Tempo:</td>
                      <td><?php echo date('d/m/y',strtotime($permintaan['batasbayar']))?></td>
                  </tr>
                  <?php
                }
                  ?>
                   <tr>
                      <td>Vendor:</td>
                      <td><?php echo $permintaan['name'] ?></td>
                  </tr>



                 <tr>
                   <td>Sub Total:</td>
                   <td>$<?php echo number_format($permintaan['sub_total'],2,'.',',') ?></td>
                  </tr>
                  <tr>
                     <td>Pajak:</td>
                     <td>$<?php echo number_format($permintaan['pajak'],2,'.','.') ?></td>
                 </tr>
                 <tr>
                    <td>Pembulatan:</td>
                    <td>$<?php echo number_format($permintaan['pembulatan'],2,'.','.') ?></td>
                </tr>
                 <tr>
                    <td>Total Pembelian:</td>
                    <td>$<?php echo number_format($permintaan['total_pembelian'],2,'.',',') ?></td>
                </tr>

            </table>
            <div class="nav-tabs-custom">
              <ul class="nav nav-tabs">
                <li class="active"><a href="#detail" data-toggle="tab">Daftar Barang</a></li>
                <?php
                if(empty($permintaan['no_faktur'])){
                ?>
                <li><a href="#biaya" data-toggle="tab">Estimasi Biaya</a></li>
                <?php
                }
                ?>

              </ul>
              <div class="tab-content">
                <div class="tab-pane active " id="detail">
                  <table class="table">
                      <thead>
                        <th>Nama Produk</th>
                        <th>Quantity</th>
                        <th>Quantity Terima</th>
                        <th>Harga</th>
                        <th>Pajak</th>
                        <th>Total</th>


                      </thead>
                      <tbody>
                        <?php
                        foreach($products as $p){
                        ?>
                        <tr>
                          <td><?php echo $p['product_name']; ?></td>
                          <td><?php echo $p['quantity']; ?></td>
                          <td><?php echo $p['quantityterima']; ?></td>
                          <td>$<?php echo number_format($p['harga'],2,'.',','); ?></td>
                          <td>$<?php echo number_format($p['ppn'],2,'.',','); ?></td>
                          <td>$<?php echo number_format(($p['harga']*$p['quantity'])+($p['ppn']*$p['quantity']),2,'.',','); ?></td>
                        </tr>
                        <?php
                        }
                        ?>
                      </tbody>
                  </table>
                </div>
                <?php
                if(empty($permintaan['no_faktur'])){
                ?>
                <div class="tab-pane " id="biaya">
                  <table class="table" id="list-biaya">
                    <thead>
                      <tr>
                        <th class="left">Nama Biaya</th>
                        <th class="right">Nominal</th>
                        <th class="right">Mata Uang</th>

                      </tr>
                    </thead>
                    <?php $biaya_row=0;?>

                    <tbody >
                    </tbody>
                    <tfoot>
                      <tr>
                      <tr>
                        <td colspan="4"></td>
                        <td class="left"><a onclick="addBiaya();" class="btn btn-success">Tambah Biaya</a>  </td>
                      </tr>

              </tfoot>
                  </table>
                </div>
                <?php
                }
                ?>
              </div>
            </div>


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
$('.sidebar-menu').find('#menu-pembelian').addClass('active');
$('.sidebar-menu').find('#menu-pembelian-import').addClass('active');
var biaya_row=<?php echo $biaya_row; ?>;
function addBiaya(){

	html = '  <tr id="biaya-row' + biaya_row + '">';
  html += '    <td class="left"><input class="form-control" type="text" data-id="'+biaya_row+'" name="biaya[' + biaya_row + '][nama]"  /></td>';

	html += '    <td class="right"><input class="form-control"  type="text" name="biaya[' + biaya_row + '][total]" value="0"  /></td>';
  html += '    <td class="right"><select class="form-control"  name="biaya[' + biaya_row + '][currency]" ><option value="1">USD</option><option value="2">IDR</option> </select></td>';


  html += '    <td class="right"><a class="btn btn-warning" onclick="$(\'#biaya-row'+biaya_row+'\').remove()" class="button">Hapus</a> <span></span></td>';

	html += '  </tr>';


	$('#list-biaya tbody').append(html);

  $(function(){
    $('.date').datepicker({dateFormat: 'yy-mm-dd'});
  })
	biaya_row++;

}
function hitungtotal(){
    totalbiaya=0;
    error=false;


    y = 0;

      while(y < biaya_row){
        nilaibiaya=$("input[name='biaya["+y+"][total]']").val();
        curr=$("select[name='biaya["+y+"][currency]']").val();
        //error=false;

        if($.isNumeric( Number(nilaibiaya) )){
            totalbiaya += Number(nilaibiaya);
        }else{
            error=true;
            alert("Nominal biaya harus berupa angka.");
        }
        y++;
      }



    if(!error){
      if(statuspajak == 1){
        pajak = total*0.1;
      }


      $("#totalbiaya").val(totalbiaya);

    }
}
function simpan(){
  $(".error").remove();
  error=false;
  errdup=false;
  em='';

  //kurskmk=$("input[name='kurskmk']").val();
  no_faktur=$("input[name='no_faktur']").val();
  tglfaktur=$("input[name='tglfaktur']").val();
  batasbayar=$("input[name='batasbayar']").val();

  /*if(kurskmk == undefined){
    error=true;
    em += "Nilai Kurs harus diisi.<br>";
  }else{
    if(kurskmk == 0){
      error=true;
      em += "Nilai Kurs harus lebih dari 0.<br>";
    }
  }
*/
  if(no_faktur == undefined){
    error=true;
    em += "Nomor faktur harus diisi.<br>";
  }else{
    if(no_faktur == ""){
      error=true;
      em += "Nomor faktur harus diisi.<br>";
    }
  }

  if(tglfaktur == undefined){
    error=true;
    em += "Tanggal faktur harus diisi.<br>";
  }else{
    if(tglfaktur == ""){
      error=true;
      em += "Tanggal faktur harus diisi.<br>";
    }
  }

  if(batasbayar == undefined){
    error=true;
    em += "Jatuh Tempo harus diisi.<br>";
  }else{
    if(batasbayar == ""){
      error=true;
      em += "Jatuh Tempo harus diisi.<br>";
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
</script>
<script type="text/javascript"><!--
$(document).ready(function() {
	$('.date').datepicker({dateFormat: 'yy-mm-dd'});

	$('#date-end').datepicker({dateFormat: 'yy-mm-dd'});
});
//--></script>

<?php echo $footer; ?>
