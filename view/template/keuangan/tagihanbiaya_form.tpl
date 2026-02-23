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
            <h3 class="box-title">Tagihan Biaya</h3>
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
                       <td>Supplier</td>
                       <td colspan="2" >
                          <select style="width:350px" name="vendor_id" class="vendor">
                            <option value="0">Pilih Supplier</option>
                          </select>
                       </td>
                     </tr>
                    <tr>
                       <td><span class="required">*</span>No. Faktur</td>
                       <td colspan="2" >
                         <input type="text" name="no_faktur" class="form-control" value="<?php echo $no_faktur; ?>" >
                       </td>
                     </tr>
                     <!--tr>
                         <td>Masa Berlaku<br><small>(Dalam Bulan)</small></td>
                         <td colspan="2"><input type="text" name="masaberlaku" class="form-control" value="1" ></td>
                     </tr>
                     <tr>
                        <td>Jenis Hutang</td>
                        <td colspan="2" >
                          <select name="akun_hutang" class="form-control hutang">

                          </select>
                        </td>
                      </tr>
                      <tr>
                         <td>Jenis Biaya Dibayar Dimuka
                           <br><small>(Kosongkan jika bukan merupakan biaya dibayar dimuka)</small>
                         </td>
                         <td colspan="2" >
                           <select name="akun_biayadimuka" class="form-control biayadimuka">

                           </select>
                         </td>
                       </tr-->
                       <tr>
                          <td>Jenis Tagihan</td>
                          <td colspan="2" >
                            <select name="jenisbiaya" class="form-control">
                              <option value="1">Biaya Operasional</option>
                              <option value="2">Biaya Periodik</option>
                              <option value="3">Biaya Listrik</option>
                              <option value="4">Biaya Air</option>
                              <option value="5">Perawatan Aset</option>
                              <option value="6">Biaya Hpp Lain-lain</option>
                            </select>
                          </td>
                        </tr>

                      <tr id="akun-biaya">
                         <td>Akun Biaya</td>
                         <td colspan="2" >
                           <select name="akun_biaya" class="form-control coa">

                           </select>
                         </td>
                       </tr>
                       <tr>
                          <td>Dibayar Tunai</td>
                          <td colspan="2" >
                            <select name="tunai" id="tunai" class="form-control">
                              <option value="1">Ya</option>
                              <option value="2">Tidak</option>

                            </select>
                          </td>
                        </tr>
                        <tr id="bank">
                           <td><span class="required">*</span>Bank/Kas</td>
                           <td ><select name="bank_id" class="form-control bank">

                       		</select>
                           </td>
                         </tr>

                       <tr id="biaya-periodik">
                          <td>Tagihan Periodik</td>
                          <td colspan="2" >
                            <select style="width:300px!important;" name="ref" class="form-control ref">

                            </select>
                          </td>
                        </tr>

                        <tr id="referensi-aset">
                           <td>Referensi Perawatan Aset</td>
                           <td colspan="2" >
                             <select style="width:300px!important;" name="refaset" class="form-control refaset">

                             </select>
                           </td>
                         </tr>
                        <td>Keterangan</td>
                        <td colspan="2" >
                          <input type="text" name="keterangan" class="form-control" value="<?php echo $keterangan; ?>" >
                        </td>
                      </tr>
                     <tr>
                         <td>Jumlah</td>
                         <td colspan="2"><input type="text" name="nominal" class="form-control" value="<?php echo $nominal; ?>" ></td>
                     </tr>
                     <!--tr>
                         <td>Pajak Dibayar Dimuka</td>
                         <td style="width:350px"><select style="width:300px" name="pajakdimuka" class="form-control pajakdimuka">

                         </select></td>
                         <td><input type="text" name="ppn" class="form-control" value="0" ></td>
                     </tr-->
                     <tr>
                       <td>Hutang Pajak</td>
                       <td colspan="2"><select name="pajak" class="form-control">
                         <option value="0">Tanpa Pajak</option>
                         <option value="2501">PPh 21</option>
                         <option value="2502">PPh 23</option>
                         <option value="2503">PPh 25</option>
                         <option value="2504">PPh 29</option>
                         <option value="2505">PPN Keluaran</option>
                         <!--<option value="2506">PPh Final</option>-->
                         <option value="2506">Hutang pajak-PPh Ps 4 ay 2 atas Sewa</option>
                         <option value="2507">Hutang pajak-PPh Final ps.17 (2c) dividen</option>
                         <option value="2508">Hutang pajak-PPh Ps 4 ayat 2 - PP23</option>
                       </select></td>
                     </tr>
                     <tr>
                       <td>Status Pajak</td>
                       <td colspan="2"><select name="statuspajak" class="form-control">
                         <option value="0">Tanpa Pajak</option>
                         <option value="1">Potong Total</option>
                         <option value="2">Tidak Potong Total</option>

                       </select></td>
                     </tr>
                     <tr>
                         <td>Total Pajak</td>
                         <td colspan="2"><input type="text" name="nilaipajak" class="form-control" value="<?php echo $nilaipajak; ?>" ></td>
                     </tr>

                     <tr>
                       <td>Pajak Dibayar Dimuka</td>
                       <td colspan="2"><select name="pajakdimuka" class="form-control">
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
                       <td colspan="2"><select name="statuspajakdimuka" class="form-control">
                         <option value="0">Tanpa Pajak</option>

                         <option value="2">Belum Termasuk Total</option>
                         <option value="1">Termasuk Total</option>

                       </select></td>
                     </tr>
                     <tr>
                         <td>Total Pajak Dibayar Dimuka</td>
                         <td colspan="2"><input type="text" name="ppn" class="form-control" value="<?php echo $ppn; ?>" ></td>
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
$('.sidebar-menu').find('#menu-pencatatan-tagihan').addClass('active');
</script>

<script type="text/javascript"><!--
$(document).ready(function() {
	$('.date').datepicker({dateFormat: 'yy-mm-dd'});
  $("select[name='tunai']").on('change',function(){
    if($("select[name='tunai']").val() == 1){
      $("#bank").show();
    }else{
      $("#bank").hide();
    }
  })
  $("select[name='jenisbiaya']").on('change',function(){
    if($("select[name='jenisbiaya']").val() == 1){
      $("#akun-biaya").show();
      $("#biaya-periodik").hide();
      $("#referensi-aset").hide();
    }
    if($("select[name='jenisbiaya']").val() == 2){
      $("#akun-biaya").hide();
      $("#biaya-periodik").show();
      $("#referensi-aset").hide();
    }
    if($("select[name='jenisbiaya']").val() == 3 | $("select[name='jenisbiaya']").val() == 3){
      $("#akun-biaya").hide();
      $("#biaya-periodik").hide();
      $("#referensi-aset").hide();
    }
    if($("select[name='jenisbiaya']").val() == 5){
      $("#akun-biaya").hide();
      $("#biaya-periodik").hide();
      $("#referensi-aset").show();
    }

  });
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

  $("select[name='jenisbiaya']").trigger('change');
  $(".coa").select2({
    ajax: {
    url:"index.php?route=keuangan/coa/autocompletes&token=<?php echo $this->request->get['token']; ?>",
    dataType: 'json',
    data: function (params) {
      return {
        filter_name: params.term,
        t:11,
        p:'x'
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

$(".ref").select2({
  ajax: {
  url:"index.php?route=keuangan/iklanperiodik/autocomplete&token=<?php echo $this->request->get['token']; ?>",
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
})

$(".refaset").select2({
  ajax: {
  url:"index.php?route=catalog/pemeliharaanaset/autocompletes&token=<?php echo $this->request->get['token']; ?>",
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

$(".hutang").select2({
  ajax: {
  url:"index.php?route=keuangan/coa/autocompletes&token=<?php echo $this->request->get['token']; ?>",
  dataType: 'json',
  data: function (params) {
    return {
      filter_name: params.term,
      t:2,
      p:'x'
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

$(".pajakdimuka").select2({
  ajax: {
  url:"index.php?route=keuangan/coa/autocompletes&token=<?php echo $this->request->get['token']; ?>",
  dataType: 'json',
  data: function (params) {
    return {
      filter_name: params.term,
      p:'11.08.00'
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

$(".biayadimuka").select2({
  ajax: {
  url:"index.php?route=keuangan/coa/autocompletes&token=<?php echo $this->request->get['token']; ?>",
  dataType: 'json',
  data: function (params) {
    return {
      filter_name: params.term,
      p:'11.06.00'
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

$(".bank").select2({
  ajax: {
  url:"index.php?route=keuangan/bank/autocomplete&token=<?php echo $this->request->get['token']; ?>",
  dataType: 'json',
  data: function (params) {
    return {
      q: params.term,
      c:1

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

  em='';
  /*if($("select[name='akun_hutang']").val() == null | $("select[name='akun_hutang']").val() == undefined){
    error=true;
    em +="Jenis Hutang harus dipilih <br>";
  }*/

  if($("select[name='jenisbiaya']").val() == 1){

    if($("select[name='akun_biaya']").val() == null | $("select[name='akun_biaya']").val() == undefined){
      error=true;
      em +="Jenis Biaya harus dipilih <br>";
    }
    else{
      if($("select[name='akun_biaya']").val() =='6211'){
        error=true;
        em +="Mohon pilih jenis tagihan Biaya listrik <br>";
      }
      if($("select[name='akun_biaya']").val() =='6212'){
        error=true;
        em +="Mohon pilih jenis tagihan Biaya air <br>";
      }
      /*if($("select[name='akun_biaya']").val() =='6201'){
        error=true;
        em +="Biaya gaji tidak dapat diinput secara manual <br>";
      }
      if($("select[name='akun_biaya']").val() =='6220'){
        error=true;
        em +="Mohon pilih jenis tagihan periodik <br>";
      }*/
      if($("select[name='akun_biaya']").val() =='6223' | $("select[name='akun_biaya']").val() =='6224' | $("select[name='akun_biaya']").val() =='6222'){
        error=true;
        em +="Mohon pilih jenis tagihan pemeliharaan aset <br>";
      }
      /*if($("select[name='akun_biaya']").val() =='6261' |  $("select[name='akun_biaya']").val() =='6262'){
        error=true;
        em +="Mohon pilih jenis tagihan periodik <br>";
      }*/

    }
  }
  if($("select[name='jenisbiaya']").val() == 2){
    if($("select[name='ref']").val() == null | $("select[name='ref']").val() == undefined){
      error=true;
      em +="Referensi tagihan periodik harus dipilih <br>";
    }
  }
  if($("select[name='jenisbiaya']").val() == 5){
    if($("select[name='refaset']").val() == null | $("select[name='refaset']").val() == undefined){
      error=true;
      em +="Referensi tagihan pemeliharaan aset harus dipilih <br>";
    }
  }
  if($("input[name='no_faktur']").val() == null){
    error=true;
    em +="Nomor Faktur harus diisi <br>";
  }

  if($("input[name='nominal']").val() == null){
    error=true;
    em +="Jumlah tagihan harus diisi <br>";
  }
  if($("select[name='tunai']").val() == 1){
    if($("select[name='bank_id']").val() == null){
      error=true;
      em +="Bank/Kas harus dipilih <br>"
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
//--></script>

<?php echo $footer; ?>
