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
            <h3 class="box-title">Tagihan Biaya Pembelian</h3>
            <div class="button pull-right">
              <a onclick="simpan()" ><button type="button" class="btn btn-primary">Simpan</button></a>
                <a href="<?php echo $cancel; ?>"><button type="button" class="btn btn-danger">Kembali</button></a>

								</div>
          </div>
          <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
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
              <div class="col-xs-12 col-md-6">

                  <table class="table">
                      <tr>
                       <td style="width:30%"><span class="required">*</span>Tanggal Tagihan</td>
                       <td colspan="2"><input type="text" name="tgl_tagihan" class="date form-control" value="<?php echo $tgl_tagihan; ?>" readonly >
                       </td>
                     </tr>
                     <tr>
                      <td><span class="required">*</span>Tanggal Jatuh Tempo</td>
                      <td colspan="2"><input type="text" name="jatuhtempo" class="date form-control" value="<?php echo $jatuhtempo; ?>" readonly >
                      </td>
                    </tr>
                    <tr>
                       <td><span class="required">*</span>No. Invoice</td>
                       <td colspan="2" >
                         <input type="text" name="no_faktur" class="form-control" value="<?php echo $no_faktur; ?>" >
                       </td>
                     </tr>
                     <tr>
                        <td><span class="required">*</span>Vendor</td>
                        <td ><select name="vendor_id" class="form-control vendor">

                        </select>
                        </td>
                      </tr>

                     <tr>
                        <td>Referensi Surat Jalan Pembelian</td>
                        <td ><select name="ref" class="form-control invoice">

                        </select>
                        </td>
                     </tr>
                    <tr>
                        <td>Keterangan</td>
                        <td colspan="2" >
                          <input type="text" name="keterangan" class="form-control" value="<?php echo $keterangan; ?>" >
                        </td>
                      </tr>

                    </table>
                </div>
                <div class="col-xs-12 col-md-6">
                  <table class="table">

                       <tr>
                           <td>Sub Total</td>
                           <td colspan="2"><input type="text" readonly name="nominal" class="form-control" value="0" ></td>
                       </tr>

                       <tr>
                         <td>Hutang Pajak</td>
                         <td colspan="2"><select onchange="updatetotal()" name="pajak" class="form-control">
                           <option value="0">Tanpa Pajak</option>
                           <option value="2501">PPh 21</option>
                           <option value="2502">PPh 23</option>
                           <option value="2503">PPh 25</option>
                           <option value="2504">PPh 29</option>
                           <option value="2505">PPN Keluaran</option>
                           <option value="2506">PPh Final</option>
                         </select></td>
                       </tr>
                       <tr>
                         <td>Status Pajak</td>
                         <td colspan="2"><select onchange="updatetotal()" name="statuspajak" class="form-control">
                           <option value="0">Tanpa Pajak</option>
                           <option value="1">Potong Total</option>
                           <option value="6251">Beban Pajak Penghasilan</option>
                           <option value="6252">Beban Pajak dan Retribusi</option>

                         </select></td>
                       </tr>
                       <tr>
                           <td>Total Pajak</td>
                           <td colspan="2"><input type="text" onblur="updatetotal()" name="nilaipajak" class="form-control" value="<?php echo $nilaipajak; ?>" ></td>
                       </tr>

                       <tr>
                         <td>Pajak Dibayar Dimuka</td>
                         <td colspan="2"><select onchange="updatetotal()"  name="pajakdimuka" class="form-control">
                           <option value="0">Tanpa Pajak</option>
                           <option value="1551">PPh 21</option>
                           <option value="1552">PPh 22</option>
                           <option value="1553">PPh 23</option>
                           <option value="1554">PPh 25</option>
                           <option value="1555">PPN Masukan</option>

                         </select></td>
                       </tr>
                       <tr>
                         <td>Status Pajak Dibayar Dimuka</td>
                         <td colspan="2"><select  onchange="updatetotal()" name="statuspajakdimuka" class="form-control">
                           <option value="0">Tanpa Pajak</option>

                           <option value="2">Belum Termasuk Total</option>
                           <option value="1">Termasuk Total</option>

                         </select></td>
                       </tr>
                       <tr>
                           <td>Total Pajak Dibayar Dimuka</td>
                           <td colspan="2"><input type="text" name="ppn" onblur="updatetotal()" class="form-control" value="0" ></td>
                       </tr>
                       <tr>
                           <td>Total Tagihan</td>
                           <td colspan="2"><input type="text" onblur="updatetotal()" readonly name="total" class="form-control" value="0" ></td>
                       </tr>
                       <tr>
                           <td>Total Estimasi</td>
                           <td colspan="2"><input type="text" readonly name="totalestimasi" class="form-control" value="0" ></td>
                       </tr>
                       <tr>
                           <td>Total Biaya Pajak</td>
                           <td colspan="2"><input type="text" onblur="updatetotal()" readonly name="biaya_pajak" class="form-control" value="0" ></td>
                       </tr>
                      </table>
                  </div>
                </div>
                <div class="row">
                  <div class="col-xs-12">
                    <table class="table" id="list-biaya">
                        <thead>
                          <th>Nama Biaya</th>
                          <th>Total</th>
                        </thead>
                        <?php
                        $row=0;
                        ?>
                        <tbody>
                        </tbody>
                        <tfoot>
                          <tr>
                          <tr>
                            <td colspan="3"></td>
                            <td class="left"><a onclick="addBiaya();" class="btn btn-success">Tambah Biaya</a>  </td>
                          </tr>

                        </tfoot>
                    </table>

              </div>
            </div>

          </div>
          </form>
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
$('.sidebar-menu').find('#menu-pembelian').addClass('active');
$('.sidebar-menu').find('#menu-pembelian-kredit').addClass('active');
</script>
<script>
var biaya_row=<?php echo $row; ?>;
function hapus(row){
  $('#biaya-row'+row).remove();
  updatetotal();

}
function updatetotal(){
  pajak=$("input[name='nilaipajak']").val();
  ppn=$("input[name='ppn']").val();

  statuspajak=$("select[name='statuspajak']").val();
  statuspajakdimuka=$("select[name='statuspajakdimuka']").val();

  error=false;
  i = 0;

  totalbiaya=0;
  totalestimasi=0;


  while(i < biaya_row){
    if($("select[name='biaya["+i+"][jenisbiaya_id]']").val() != undefined){
      totalreal=$("input[name='biaya["+i+"][totalreal]']").val();
      total=$("input[name='biaya["+i+"][total]']").val();

      error=false;
      if($.isNumeric( Number(totalreal) ) & $.isNumeric( Number(total) )){
        totalbiaya += Number(totalreal);
        totalestimasi += Number(total);
      }
    }
    i++;
  }

  grand=0;
  if(!error){
    $("input[name='nominal']").val(totalbiaya);
    $("input[name='totalestimasi']").val(totalestimasi);
    grand +=Number(totalbiaya);
    if(statuspajakdimuka != 1){
      grand += Number(ppn);
    }

    if(statuspajak == 1){
      grand -= Number(pajak);
    }else{
        $("input[name='biaya_pajak']").val(pajak);
    }
      $("input[name='total']").val(grand);
  }
}
function addBiaya(){
  if($(".invoice").val() != null){
	html = '  <tr id="biaya-row' + biaya_row + '">';
  html += '    <td class="left"><select style="width:300px" data-id="'+biaya_row+'" name="biaya[' + biaya_row + '][jenisbiaya_id]" class="biaya form-control"></select></td>';
  html += '    <td class="right"><input class="form-control" onblur="updatetotal()"  type="text" name="biaya[' + biaya_row + '][totalreal]" value="0" /><input class="form-control"  type="hidden"  name="biaya[' + biaya_row +'][total]" value="0" /><input class="form-control"  type="hidden" name="biaya[' + biaya_row + '][id]" value="0" /></td>';
  html += '    <td class="right"><a class="btn btn-warning" onclick="hapus('+biaya_row+')" class="button">Hapus</a> <span></span></td>';

	html += '  </tr>';


	$('#list-biaya tbody').append(html);

  $(function(){
    $('.date').datepicker({dateFormat: 'yy-mm-dd'});
  })
  $(function(){
    $(".biaya").select2({
        ajax: {
        url:"index.php?route=catalog/jenisbiaya/autocomplete&token=<?php echo $this->request->get['token']; ?>",
          dataType: 'json',
        data: function (params) {
          return {
            q: params.term,
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
          coba=$(this).data('id');
          //alert(id);
          if(id != undefined & id != null){

            invoice=$(".invoice").val();
          $.ajax({
            url: 'index.php?route=pembelian/barangdatangdagang/detailbiaya&token=<?php echo $this->request->get['token']; ?>&invoice_id=' + invoice+'&id='+id,
            dataType: 'json',
            success: function(json) {
              if(json){

                console.log(JSON.stringify(json));
                //alert(json.statuspembayaran);

                if(json.statuspembayaran == 0  | json.statuspembayaran == 'null' | json.statuspembayaran == 4 | json.statuspembayaran == undefined){

                      if(json.total == null){
                        json.total=0;
                      }
                      $("input[name='biaya["+coba+"][totalreal]']").val(json.total);
                      $("input[name='biaya["+coba+"][total]']").val(json.total);
                      $("input[name='biaya["+coba+"][id]']").val(json.id);

                      updatetotal();
                }else{
                  if(json.statuspembayaran == 1 | json.statuspembayaran == 2 | json.statuspembayaran == 3 | json.tagihan_id > 0){
                    hapus(coba);
                    alert("Tagihan untuk biaya yang dipilih telah dibuat.");
                  }
                }
              }
            }
          })
        }


        })
  })
	biaya_row++;
  }
  else{
    alert("Mohon pilih referensi invoice terlebih dahulu");
  }
}
</script>
<script type="text/javascript"><!--
$(document).ready(function() {
	$('.date').datepicker({dateFormat: 'yy-mm-dd'});
  $(".vendor").select2({
    ajax: {
    url:"index.php?route=catalog/vendorlokal/autocomplete&token=<?php echo $this->request->get['token']; ?>",
    dataType: 'json',
    data: function (params) {
      return {
        filter_name: params.term
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
});


$(".invoice").select2({
  ajax: {
  url:"index.php?route=pembelian/barangdatangdagang/autocomplete&token=<?php echo $this->request->get['token']; ?>",
  dataType: 'json',
  data: function (params) {
    return {
      q: params.term,
      //p:3,
      //f:$("select[name='vendor_id']").val()
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
  updatetotal();
  $(".error").remove();
  error=false;
  errdup=false;
  errkos=false;

  em='';
  /*if($("select[name='akun_hutang']").val() == null | $("select[name='akun_hutang']").val() == undefined){
    error=true;
    em +="Jenis Hutang harus dipilih <br>";
  }*/

  if($("input[name='no_faktur']").val() == ''){
    error=true;
    em +="No Invoice harus diisi.<br>";
  }

  if($("select[name='vendor_id']").val() == null){
    error=true;
    em +="Vendor harus dipilih.<br>";
  }

  if($("select[name='ref']").val() == null){
    error=true;
    em +="Referensi Invoice harus dipilih.<br>";
  }
  if(biaya_row == 0){
    error=true;
    em += "Daftar biaya tidak boleh kosong.<br>";

  }
  cek=[]
  for(i=0;i<biaya_row;i++){
    //alert($("select[name='biaya["+i+"][jenisbiaya_id]']").val());
    if($("select[name='biaya["+i+"][jenisbiaya_id]']").val() != undefined){
      if($("select[name='biaya["+i+"][jenisbiaya_id]']").val() != null){
        id=$("select[name='biaya["+i+"][jenisbiaya_id]']").val();

        if(cek[id] != undefined){
          error=true;
          errdup=true;
        }else{
          cek[id]=i;
        }
      }else{
        error=true;
        errkos=true;
      }
    }
  }
  //alert(errkos);
  if(error){
    if(errdup){
      em +="Terdapat duplikasi jenis biaya. Mohon dicek kembali.<br>";
    }
    if(errkos){
      em +="Terdapat jenis biaya tidak valid. Mohon dicek kembali.<br>";
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
//--></script>

<?php echo $footer; ?>
