<?php echo $header;
//print_r($fulldetail);
?>
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
          <div class="box-header with-border no-print">
           <h3 class="box-title no-print">Surat Jalan Penjualan <?php echo $order['no_sj']; ?>
              <br>
              <?php
              if(isset($order['nama'])){
              ?>
              <small>Gudang: <?php echo $order['nama']; ?></small>
              <?php
              }
              ?>
            </h3>
            <div class="button pull-right no-print">
                <a onclick="simpan()" ><button type="button" class="btn btn-primary">Simpan</button></a>
               
                  <a href="<?php echo $cancel; ?>"><button type="button" class="btn btn-danger">Kembali</button></a>
								</div>
          </div>
          <p></p>
          <section class="invoice">
      <!-- title row -->
      <div class="row">
      <div class="errordisplay"></div>
      <div class="col-xs-12 col-md-6">
       <table class="table table-bordered">
            <tr>
                <td>No. Surat Jalan</td>
                <td><?php echo $order['no_sj']; ?></td>
            </tr>
            <tr>
                <td>Customer</td>
                <td><?php echo $order['name']; ?></td>
            </tr>
            <tr>
                <td>Alamat</td>
                <td><?php
                    if($order['address_id'] > 0){
                    ?>
                    <address>
                        
                        
                        <?php echo $address['address_1'].' '.$address['address_2']; ?><br>
                        <?php echo $address['city'].', '.$address['zone'].', '.$address['country'].', '.$address['postcode']; ?><br>
                        Phone: <?php echo !empty($address['company_id'])?$address['company_id']:$order['telephone']; ?><br>
                        Email: <?php echo $order['email']; ?>
                    </address>
                    <?php
                    }else{
                    ?>
                    <address>
                        
                        DIAMBIL
                    </address>
                    <?php
                    }
                    ?>
                </td>
            </tr>
            <tr>
                <td>No. DO</td>
                <td>
                <?php 
                if($order['do_id'] > 0){
                ?>
                <a target="_blank" href="<?php echo $order['hrefdo']; ?>">
                <?php echo $order['no_do']; ?>
                </a>
                <?php 
                }
                ?>
                </td>
            </tr>
            <tr>
                <td>Tanggal Terima</td>
                <td>
                <input type="text" class="date form-control" name="tglterima" value="<?php echo date('Y-m-d'); ?>" readonly>
                </td>
            </tr>
            <tr>
                <td>Total Tabung Dialokasikan</td>
                <td>
                <input type="text" class="form-control" name="totaltabung" value="0" readonly>
                </td>
            </tr>
        </table>
        </div>
        <div class="col-xs-12 col-md-6">
       <table class="table table-bordered">
           <tr>
                <td>Tanggal</td>
                <td><?php echo date('d/m/y',strtotime($order['date_added']))?></td>
            </tr>
            <tr>
                <td>Kendaraan</td>
                <td><?php echo $order['no_pol']; ?></td>
            </tr>
            <tr>
                <td>Sopir</td>
                <td><?php echo $order['sopir']; ?></td>
            </tr>
            <tr>
                <td>Kernet</td>
                <td><?php
          $i=1;
           foreach($order['kernets'] as $k){
             echo $i.". ".$k['firstname']."<br>";
             $i++;
           }
          ?></td>
            </tr>
        </table>
        </div>
      </div>
      <!-- info row -->
      
      <div class="row">
        <div class="col-xs-12 table-responsive">
        <div class="nav-tabs-custom">
              <ul class="nav nav-tabs">
                <li class="active"><a href="#detail" data-toggle="tab">Daftar Barang</a></li>
                <li><a href="#alokasitabung" data-toggle="tab">Alokasi Tabung</a></li>
            </ul>
            <div class="tab-content">
                <div class="tab-pane active " id="detail">
                    <table class="table table-bordered">
                        <thead>
                        <tr>
                        <th>Qty + Satuan</th>
                        <th>Nama Barang</th>
                        <th>No. SO</th>
                        
                        </tr>
                        </thead>
                        <tbody>
                        
                        <?php
                        
                        $totalproduct=0;
                        foreach($products as $p){
                        ?>
                        <tr>
                        <td><?php echo $p['quantity']; ?> <?php echo $p['namasatuan']; ?></td>
                        <!--<td><?php echo $p['product_id']; ?></td>-->

                        <td>
                            <?php echo $p['name']; ?><br>
                            <small><?php echo $p['no_tabung']; ?></small>
                        </td>
                        <td><?php echo $p['no_salesorder']; ?> </td>
                        

                        </tr>
                        <?php
                        $totalproduct +=$p['quantity'];
                        }
                        ?>

                        </tbody>
                    </table>
                </div>
                <div class="tab-pane" id="alokasitabung">
                    <table class="table" id="list-tabung">
                        <thead>
                            <tr>
                            <th></th>
                            <th class="left">No. Tabung</th>
                            <th class="right">Jenis Gas</th>
                           <th class="right">Keterangan</th>

                            </tr>
                        </thead>
                    <?php $tabung_row=0;?>
                    <tbody>

                    </tbody>
                    <tfoot>
                        <tr>
                        <td ></td>
                        <td ></td>
                        <td ></td>
                        <td class="left"><a onclick="addModule();" class="btn btn-success">Tambah</a></td>
                        </tr>

                    </tfoot>
                    </table>
                </div>
            </div>
        </div>
          
        </div>
        <!-- /.col -->
      </div>
      
     
    </section>
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
$('.sidebar-menu').find('#menu-penjualan').addClass('active');
$('.sidebar-menu').find('#menu-salesorder').addClass('active');
$('.date').datepicker({dateFormat: 'yy-mm-dd'});
$(document).on('keydown', function(e) {
    if((e.ctrlKey || e.metaKey) && (e.key == "p" || e.charCode == 16 || e.charCode == 112 || e.keyCode == 80) ){
        alert("Gunakan tombol cetak untuk mencetak surat jalan");
        e.cancelBubble = true;
        e.preventDefault();

        e.stopImmediatePropagation();
    }
});
function cetak(){
  window.print();
}

var tabung_row='<?php echo $tabung_row;?>';
var totalproduct='<?php echo $totalproduct; ?>';
function updatetotaltabung(){
  totaltabung=0;
  for(i=0;i<tabung_row;i++){
    tid=$("select[name='tabung["+i+"][doproduct_id]']").val();
    if(tid != undefined){
      totaltabung +=1;
    }


  }
  $("input[name='totaltabung']").val(totaltabung);
}

function hapus(row){
  $('#tabung-row'+row).remove();
   tabung=[];
  updatetotaltabung();
  
}

function addModule() {
   html  = '<tbody id="tabung-row' + tabung_row + '">';
	html += '  <tr>';
  html +='<td class="center" style="width: 3px;"><img src="view/image/delete.png" onclick="hapus('+tabung_row+')" style="cursor: pointer;" /></td>';
	html += '    <td class="left"><select data-id="'+tabung_row+'" name="tabung[' + tabung_row + '][doproduct_id]" class="tabung form-control"></select><input type="hidden" name="pemilik" value="1"><input type="hidden" name="tabung[' + tabung_row + '][tabung_id]" value="0"></td>';
	html += '    <td class="right" id="ukuran'+tabung_row+'"></td>';
  html += '    <td class="right"><input type="text" class="form-control" name="tabung[' + tabung_row + '][keterangan]" value="" /></td>';
	html += '  </tr>';
	html += '</tbody>';

	$('#list-tabung tfoot').before(html);

	tabung_row++;

  $(function(){

    $(".tabung").select2({
        ajax: {
        url:"index.php?route=sale/deliveryorder/autocompletetabung&token=<?php echo $this->request->get['token']; ?>",
          dataType: 'json',
        data: function (params) {
          row=$(this).data('row');
        
          return {
            q: params.term,
            statustabung:1,
            status:1,
            do_id:<?php echo $order['do_id']; ?>


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
        coba=$(this).data('id');
        if(id != undefined & id != null){
          $.ajax({
            url: 'index.php?route=sale/deliveryorder/detailtabung&token=<?php echo $token; ?>&product_id=' + id,
            dataType: 'json',
            success: function(json) {
            console.log(JSON.stringify(json));
            found=false;
            /*for(i=0;i<product_row;i++){
              if($("input[name='product["+i+"][pilih]']").is(":checked")){
                pid=$("input[name='product["+i+"][product_id]']").val();
                if(json.product_id == pid){
                  found=true;
                }
      

              


              }


            }
            if(!found){
              alert("Jenis gas tidak ditemukan pada surat jalan yang dipilih");
              hapus(coba);
            }else{
              $("#tabung-row"+coba).addClass("producttabung"+json.product_id);
              $("#ukuran"+coba).text(json.namaproduct);
              updatetotaltabung();
            }*/
            tabung=[];
            for(i=0;i<tabung_row;i++){
              tid=$("select[name='tabung["+i+"][doproduct_id]']").val();
              if(tid != undefined){
                if(tabung[tid] == undefined){
                  tabung[tid]=1;
                }else{
                  found=true;
                }
              }
              
            }

            if(found){
              alert("Terdapat duplikasi nomor tabung");
              hapus(coba);
              tabung=[];
            }else{
              $("#tabung-row"+coba).addClass("producttabung"+json.product_id);
              $("#ukuran"+coba).text(json.namaproduct);
              $("input[name='tabung["+coba+"][tabung_id]']").val(json.id);
              updatetotaltabung();
            }

            


            }
          })
      }
  })


})
}


function simpan(){
  $(".error").remove();
  error=false;
  errdup=false;
  errmax=false;
  errqty=false;
  em='';
  
  /*totalqty=0;
  for(i=0;i<product_row;i++){
    if($("input[name='product["+i+"][pilih]']").is(":checked")){
      qty=$("input[name='product["+i+"][quantity]']").val();
      
      totalqty +=Number(qty);

    


    }


  }

  cek=[];
  
  totaltabung=0;
  for(i=0;i<tabung_row;i++){
    tid=$("select[name='tabung["+i+"][doproduct_id]']").val();
    if(tid != undefined){
      totaltabung +=1;
    }
    if(cek[tid] == undefined){
      cek[tid]=1;
    }else{
      error=true;
      errdup=true;
    }


  }
  
  if(totalqty != totaltabung){
    error=true;
    em +="Jumlah tabung yang dikirim tidak sama dengan jumlah produk";
  }*/

  cek=[];
  
  for(i=0;i<tabung_row;i++){
    tid=$("select[name='tabung["+i+"][doproduct_id]']").val();
    
    if(tid != undefined){
    
    if(cek[tid] == undefined){
      cek[tid]=1;
    }else{
      error=true;
      errdup=true;
    }
    }


  }

 

  <?php 
  if($order['do_id'] > 0){
  ?>
   totaltabung=$("input[name='totaltabung']").val();
  if(Number(totaltabung) < 1){
    error=true;
    em += "Jumlah tabung yang dikirim harus lebih dari 0<br>";
  }
  if(Number(totaltabung) != Number(totalproduct)){
      error=true;
      em += "Jumlah alokasi tabung tidak sama dengan jumlah pembelian gas";
    }
  <?php 
  }else{
    if($order['jenispenjualan'] == 1){
    ?>
     totaltabung=$("input[name='totaltabung']").val();
      if(Number(totaltabung) < 1){
        error=true;
        em += "Jumlah tabung yang dikirim harus lebih dari 0<br>";
      }
      if(Number(totaltabung) != Number(totalproduct)){
          error=true;
          em += "Jumlah alokasi tabung tidak sama dengan jumlah pembelian gas";
        }
    <?php
    }
  }
  ?>

  


  if(error){
    if(errdup){
      em+= "Terdapat nomor tabung yang duplikat.<br>";
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
