<?php echo $header; ?>
<div class="content-wrapper">
  <section class="content-header">
    <h1>

    </h1>

  </section>

  <section class="content">
    <div class="row">
      <div class="col-md-12">
        <div class="box">
          <div class="box-header with-border">
            <h3 class="box-title">Customer</h3>
            <div class="button pull-right">                  
            </div>
          </div>
          <div class="box-body">
            <div class="row">
              <div class="col-md-12">
                <?php if ($success) { ?>
                <div class="alert alert-success alert-dismissible">
                  <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                  <h4><i class="icon fa fa-check"></i> Success!</h4>
                  <?php echo $success; ?>
                </div>
                <?php
                }
                ?>

                <?php if ($error_warning) { ?>
                <div class="alert alert-danger alert-dismissible">
                  <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                  <h4><i class="icon fa fa-check"></i> Alert!</h4>
                  <?php echo $error_warning; ?>
                </div>
                <?php
                }
                ?>
              </div>
            </div>
            <div class="row">
              <div class="col-xs-12">
                <table class="table table-bordered">
                  <tr>
                    <td colspan="2"><b>Filter Data</b></td>
                  </tr>
                  <tr>
                    <td>Nama</td>
                    <td><input type="text" class="form-control" name="filter_name" value="<?php echo $filter_name; ?>" /></td>
                  </tr>
                  <tr>
                    <td>Alamat</td>
                    <td>
                      <input type="text" name="filter_alamat" class="form-control" value="<?php echo $filter_alamat; ?>" >
                    </td>
                  </tr>
                  <tr>
                    <td>Sales</td>
                    <td>
                      <select name="filter_sales" class="sales form-control">

                      </select>
                    </td>
                  </tr>
                  <tr>
                    <td>Provinsi</td>
                    <td><div id="filter-prop" style="height:100px;overflow:scroll;">
                    <?php
                    foreach($countries as $c){
                    ?>
                      <input type="checkbox" name="filter_provinsi[]" <?php echo in_array($c['country_id'],$filter_provinsi)?'checked':''; ?> value="<?php echo $c['country_id']; ?>" /> <?php echo $c['name']; ?><br>
                    <?php
                    }
                    ?>
                  </div></td>
                  </tr>
                  <tr>
                    <td>Kategori Customer</td>
                    <td><div id="filter-cust" style="height:100px;overflow:scroll;">
                    <?php
                    foreach($customer_groups as $c){
                    ?>
                      <input type="checkbox" name="filter_customer_group[]" <?php echo in_array($c['customer_group_id'],$filter_customer_group)?'checked':''; ?> value="<?php echo $c['customer_group_id']; ?>" /> <?php echo $c['name']; ?><br>
                    <?php
                    }
                    ?>
                  </div></td>
                  </tr>
                  <tr>
                    <td>Ulang tahun <br></td>
                    <td>
                      <select name="filter_tgllahir" class="form-control">
                        <option  value="*">Semua Bulan</option>
                        <option <?php echo $filter_tgllahir==1?'selected':''; ?> value="1">Januari</option>
                        <option <?php echo $filter_tgllahir==2?'selected':''; ?> value="2">Februari</option>
                        <option <?php echo $filter_tgllahir==3?'selected':''; ?> value="3">Maret</option>
                        <option <?php echo $filter_tgllahir==4?'selected':''; ?> value="4">April</option>
                        <option <?php echo $filter_tgllahir==5?'selected':''; ?> value="5">Mei</option>
                        <option <?php echo $filter_tgllahir==6?'selected':''; ?> value="6">Juni</option>
                        <option <?php echo $filter_tgllahir==7?'selected':''; ?> value="7">Juli</option>
                        <option <?php echo $filter_tgllahir==8?'selected':''; ?> value="8">Agustus</option>
                        <option <?php echo $filter_tgllahir==9?'selected':''; ?> value="9">September</option>
                        <option <?php echo $filter_tgllahir==10?'selected':''; ?> value="10">Oktober</option>
                        <option <?php echo $filter_tgllahir==11?'selected':''; ?> value="11">November</option>
                        <option <?php echo $filter_tgllahir==12?'selected':''; ?> value="12">Desember</option>

                      </select>
                    </td>
                  </tr>
                  <tr>
                    <td></td>
                    <td><a onclick="filter();" class="btn btn-primary">Filter</a></td>
                  </tr>
                </table>
              </div>
            </div>
            <div class="row" style="overflow: auto;">
              <div class="col-xs-12">
                <form action="" method="post" enctype="multipart/form-data" id="form">
                  <table class="table table-bordered">
                    <thead>
                      <tr>

                        <td width="1" style="text-align: center;" style="width:5%"><input type="checkbox" onclick="$('input[name*=\'selected\']').attr('checked', this.checked);" /></td>
                        <th class="left"><?php if ($sort == 'tgllahir') { ?>
                          <a href="<?php echo $sort_tgllahir; ?>" class="<?php echo strtolower($order); ?>">Tanggal Lahir</a>
                          <?php } else { ?>
                          <a href="<?php echo $sort_tgllahir; ?>">Tanggal Lahir</a>
                          <?php } ?></th>
                          <th class="left"><?php if ($sort == 'sales') { ?>
                            <a href="<?php echo $sort_sales; ?>" class="<?php echo strtolower($order); ?>">Sales</a>
                            <?php } else { ?>
                            <a href="<?php echo $sort_sales; ?>">Sales</a>
                            <?php } ?></th>
                            <th class="left">Kategori Customer</th>
                        <th>Sapaan</th>
                        <th class="left"><?php if ($sort == 'name') { ?>
                          <a href="<?php echo $sort_name; ?>" class="<?php echo strtolower($order); ?>">Nama <i class="fa fa-angle-up"></i></a>
                          <?php } else { ?>
                          <a href="<?php echo $sort_name; ?>">Nama <i class="fa fa-angle-down"></i></a>
                          <?php } ?></th>
                        <th class="left">PIC <small>Nama Depan</small></th>
                        <th class="left">PIC <small>Nama Belakang</small></th>
                        <th class="left">Telephone</th>
                        <th class="left">Fax</th>
                        <th class="left">Email</th>
                        <th class="left">Alamat</th>
                        <th class="left">Provinsi</th>
                        <th class="left">Kabupaten/Kota</th>
                        <th class="left">Kecamatan</th>
                        <th class="left">Kode Pos</th>
                        <th class="left">Nama Pemilik</th>
                        <th class="left">Alamat Pemilik</th>
                        <th class="left">Telp Pemilik</th>
                        <th class="left">HP Pemilik</th>
                        <th class="left">NPWP</th>
                        <th class="left">Nama NPWP</th>
                        <th class="left">Alamat NPWP</th>
                        <th class="left">Nama KTP</th>
                        <th class="left">Alamat KTP</th>
                        <th class="left">No.KTP</th>
                        <th class="left">Telephone 2</th>

                      </tr>
                    </thead>
                    <tbody>

                      <?php if ($customers) { ?>
                      <?php $i=1;$j=1;foreach ($customers as $customer) { ?>
                      <tr>
                        <td style="text-align: center;"><?php if ($customer['selected']) { ?>
                          <input type="checkbox" name="selected[]" value="<?php echo $customer['customer_id']; ?>" checked="checked" />
                          <?php } else { ?>
                          <input type="checkbox" name="selected[]" value="<?php echo $customer['customer_id']; ?>" />
                          <?php } ?></td>
                          <td class="left tgllahir" id="<?php echo $customer['customer_id']; ?>"><?php echo $customer['tgllahir']; ?></td>
                            <td class="left"><?php echo $customer['sales']; ?></td>
                            <td class="left customer_group" id="<?php echo $customer['customer_id']; ?>"><?php echo $customer['customer_group']; ?></td>
                            <td class="left sapaan" id="<?php echo $customer['customer_id']; ?>"><?php echo $customer['sapaan']; ?></td>
                        <td class="left nama" id="<?php echo $customer['customer_id']; ?>"><?php echo $customer['name']; ?></td>
                        <td class="left namapicdepantengah" id="<?php echo $customer['customer_id']; ?>"><?php echo $customer['namapicdepantengah']; ?></td>
                        <td class="left namapicbelakang" id="<?php echo $customer['customer_id']; ?>"><?php echo $customer['namapicbelakang']; ?></td>
                        <td class="left telephone" id="<?php echo $customer['customer_id']; ?>"><?php echo $customer['telephone']; ?></td>
                        <td class="left fax" id="<?php echo $customer['customer_id']; ?>"><?php echo $customer['fax']; ?></td>
                        <td class="left email" id="<?php echo $customer['customer_id']; ?>"><?php echo $customer['email']; ?></td>
                        <td class="left alamat" id="<?php echo $customer['customer_id']; ?>"><?php echo $customer['alamat']; ?></td>
                        <td class="left provinsi" id="<?php echo $customer['customer_id']; ?>"><?php echo $customer['provinsi']; ?></td>
                        <td class="left kota<?php echo $i++?>" id="<?php echo $customer['customer_id']; ?>"><?php echo $customer['kota']; ?></td>
                        <td class="left kecamatan<?php echo $j++?>" id="<?php echo $customer['customer_id']; ?>"><?php echo $customer['kecamatan']; ?></td>
                        <td class="left kodepos" id="<?php echo $customer['customer_id']; ?>"><?php echo $customer['kodepos']; ?></td>
                        <td class="left nama_pemilik" id="<?php echo $customer['customer_id']; ?>"><?php echo $customer['nama_pemilik']; 
                        ?></td>
                        <td class="left alamat_pemilik" id="<?php echo $customer['customer_id']; ?>"><?php echo $customer['alamat_pemilik']; ?></td>
                        <td class="left telp_pemilik" id="<?php echo $customer['customer_id']; ?>"><?php echo $customer['telp_pemilik']; ?></td>
                        <td class="left hp_pemilik" id="<?php echo $customer['customer_id']; ?>"><?php echo $customer['hp_pemilik']; ?></td>
                        <td class="left npwp" id="<?php echo $customer['customer_id']; ?>"><?php echo $customer['npwp']; ?></td>
                        <td class="left namanpwp" id="<?php echo $customer['customer_id']; ?>"><?php echo $customer['namanpwp']; ?></td>
                        <td class="left alamatnpwp" id="<?php echo $customer['customer_id']; ?>"><?php echo $customer['alamatnpwp']; ?></td>
                        <td class="left namaktp" id="<?php echo $customer['customer_id']; ?>"><?php echo $customer['namaktp']; ?></td>
                        <td class="left alamatktp" id="<?php echo $customer['customer_id']; ?>"><?php echo $customer['alamatktp']; ?></td>
                        <td class="left noktp" id="<?php echo $customer['customer_id']; ?>"><?php echo $customer['noktp']; ?></td>
                        <td class="left telephone2" id="<?php echo $customer['customer_id']; ?>"><?php echo $customer['telephone2']; ?></td>
                      </tr>
                      <?php } ?>
                      <?php } else { ?>
                      <tr>
                        <td class="center" colspan="11"><?php echo $text_no_results; ?></td>
                      </tr>
                      <?php } ?>
                    </tbody>
                  </table>
                </form>
              </div>
            </div>
          </div>
          <div class="box-footer">
            <div class="row">
              <div class="col-md-12">
                <div class="pull-right"><?php echo $pagination; ?></div>
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
$('.sidebar-menu').find('#menu-customer').addClass('active');
$('.sidebar-menu').find('#menu-edit-customer').addClass('active');
$(function(){
  $(".select-ads").select2({


      theme:"bootstrap"
    });
    $('.date').datepicker({dateFormat: 'm-dd'});
})
$(function(){
  $(".sales").select2({
    ajax: {
    url:"index.php?route=user/user/autocomplete&token=<?php echo $this->request->get['token']; ?>",
      dataType: 'json',
    data: function (params) {
      return {
        q: params.term,
        j:21

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
})

</script>

<script type="text/javascript"><!--
function filter() {
  url = 'index.php?route=sale/editcustomer&token=<?php echo $token; ?>';

	var filter_name = $('input[name=\'filter_name\']').val();

	if (filter_name) {
		url += '&filter_name=' + encodeURIComponent(filter_name);
	}

  var filter_alamat = $('input[name=\'filter_alamat\']').val();

  if (filter_alamat != null) {
    url += '&filter_alamat=' + encodeURIComponent(filter_alamat);
  }

  var filter_provinsi = $("#filter-prop input:checkbox:checked").map(function(){
      return $(this).val();
    }).get(); // <----

    //alert(JSON.stringify(filter_statuss));
  if(filter_provinsi!= null){
    url+='&filter_provinsi=' +filter_provinsi;
  }
  var filter_customer_group = $("#filter-cust input:checkbox:checked").map(function(){
      return $(this).val();
    }).get(); // <----

    //alert(JSON.stringify(filter_statuss));
  if(filter_customer_group!= null){
    url+='&filter_customer_group=' +filter_customer_group;
  }
  var filter_tgllahir = $('select[name=\'filter_tgllahir\']').val();

	if (filter_tgllahir != '*') {
		url += '&filter_tgllahir=' + encodeURIComponent(filter_tgllahir);
	}

  var filter_sales = $('select[name=\'filter_sales\']').val();

	if (filter_sales != null) {
		url += '&filter_sales=' + encodeURIComponent(filter_sales);
	}

	location = url;
}

//--></script>

<script type="text/javascript"><!--
$('#form input').keydown(function(e) {
	if (e.keyCode == 13) {
		filter();
	}
});
//--></script>
<script type="text/javascript">
  $(document).ready(function() {
    $(".customer_group").editable('index.php?route=sale/editcustomer/edittable&token=<?php echo $token; ?>&column=customer_group_id', {
        type   : "select",
        //data   : '{"1":"Wiki","2":"Banana","Apple":"Apple", "Pear":"Pear"}',
        data : '{"0":"Pilih","32":"Agen Gas","30":"Aquascape","34":"Distribusi Alat Kesehatan","35":"Karoseri Ambulan","31":"Pabrik Gas \/ Pengisian Gas","36":"Pemadam Kebakaran","37":"Retail Industri","38":"Retail Medis","28":"Rumah Sakit","33":"Toko Alat Kesehatan","39":"Supplier Industri","40":"CNG/LNG"}',
        submitdata : function(data) { 
          return {
            text:data,
            something: 'else'
          };
        },
        style  : "inherit",
        id   : 'customer_id',
        name : 'customer_group_id',
    });
     $(".sapaan").editable('index.php?route=sale/editcustomer/edittable&token=<?php echo $token; ?>&column=title', {
        type   : "select",
        //data   : '{"1":"Wiki","2":"Banana","Apple":"Apple", "Pear":"Pear"}',
        data : '{"0":"Pilih","1":"Bpk","2":"Ibu","3":"Toko","4":"UD","5":"CV","6":"Firma","7":"PT","8":"","9":"PD","502":"Bengkel","503":"Pabrik","503":"Ekspedisi"}',
        //data:,
        submitdata : function(data) { 
          return {
            text:data,
            something: 'else'
          };
        },
        style  : "inherit",
        id   : 'customer_id',
        name : 'title',
    });
    $(".nama").editable('index.php?route=sale/editcustomer/edittable&token=<?php echo $token; ?>&column=name', {
        submit : 'Simpan',
        cancel : 'Cancel',
        cssclass : 'custom-class',
        cancelcssclass : 'badge bg-red',
        submitcssclass : 'badge bg-green',
        formid : 'abc-123',
        id   : 'customer_id',
        name : 'name',
    });
    $(".telephone").editable('index.php?route=sale/editcustomer/edittable&token=<?php echo $token; ?>&column=telephone', {
        submit : 'Simpan',
        cancel : 'Cancel',
        cssclass : 'custom-class',
        cancelcssclass : 'badge bg-red',
        submitcssclass : 'badge bg-green',
        formid : 'abc-123',
        id   : 'customer_id',
        name : 'telephone',
    });
     $(".kodepos").editable('index.php?route=sale/editcustomer/edittable&token=<?php echo $token; ?>&column=kodepos', {
        submit : 'Simpan',
        cancel : 'Cancel',
        cssclass : 'custom-class',
        cancelcssclass : 'badge bg-red',
        submitcssclass : 'badge bg-green',
        formid : 'abc-123',
        id   : 'customer_id',
        name : 'kodepos',
    });
    $(".fax").editable('index.php?route=sale/editcustomer/edittable&token=<?php echo $token; ?>&column=fax', {
        submit : 'Simpan',
        cancel : 'Cancel',
        cssclass : 'custom-class',
        cancelcssclass : 'badge bg-red',
        submitcssclass : 'badge bg-green',
        formid : 'abc-123',
        id   : 'customer_id',
        name : 'fax',
    });
    $(".email").editable('index.php?route=sale/editcustomer/edittable&token=<?php echo $token; ?>&column=email', {
        submit : 'Simpan',
        cancel : 'Cancel',
        cssclass : 'custom-class',
        cancelcssclass : 'badge bg-red',
        submitcssclass : 'badge bg-green',
        formid : 'abc-123',
        id   : 'customer_id',
        name : 'email',
    });
    $(".namapicdepantengah").editable('index.php?route=sale/editcustomer/edittable&token=<?php echo $token; ?>&column=namapicdepantengah', {
        submit : 'Simpan',
        cancel : 'Cancel',
        cssclass : 'custom-class',
        cancelcssclass : 'badge bg-red',
        submitcssclass : 'badge bg-green',
        formid : 'abc-123',
        id   : 'customer_id',
        name : 'namapicdepantengah',
    });
    $(".namapicbelakang").editable('index.php?route=sale/editcustomer/edittable&token=<?php echo $token; ?>&column=namapicbelakang', {
        submit : 'Simpan',
        cancel : 'Cancel',
        cssclass : 'custom-class',
        cancelcssclass : 'badge bg-red',
        submitcssclass : 'badge bg-green',
        formid : 'abc-123',
        id   : 'customer_id',
        name : 'namapicbelakang',
    });
    $(".siup").editable('index.php?route=sale/editcustomer/edittable&token=<?php echo $token; ?>&column=siup', {
        submit : 'Simpan',
        cancel : 'Cancel',
        cssclass : 'custom-class',
        cancelcssclass : 'badge bg-red',
        submitcssclass : 'badge bg-green',
        formid : 'abc-123',
        id   : 'customer_id',
        name : 'siup',
    });
    $(".siup_expire").editable('index.php?route=sale/editcustomer/edittable&token=<?php echo $token; ?>&column=siup_expire', {
        type :'datepicker',
        submit : 'Simpan',
        cancel : 'Cancel',
        cssclass : 'custom-class',
        cancelcssclass : 'badge bg-red',
        submitcssclass : 'badge bg-green',
        datepicker : {
            format: "yy-mm-dd"
        },
        id   : 'customer_id',
        name : 'siup_expire',
    });
    $(".tdp").editable('index.php?route=sale/editcustomer/edittable&token=<?php echo $token; ?>&column=tdp', {
        //type   : "autogrow",
        submit : 'Simpan',
        cancel : 'Cancel',
        cssclass : 'custom-class',
        cancelcssclass : 'badge bg-red',
        submitcssclass : 'badge bg-green',
        formid : 'abc-123',
        id   : 'customer_id',
        name : 'tdp',
        onblur    : "ignore"
    });
    $(".tdp_expire").editable('index.php?route=sale/editcustomer/edittable&token=<?php echo $token; ?>&column=tdp_expire', {
        type :'datepicker',
        submit : 'Simpan',
        cancel : 'Cancel',
        cssclass : 'custom-class',
        cancelcssclass : 'badge bg-red',
        submitcssclass : 'badge bg-green',
        datepicker : {
            format: "yy-mm-dd"
        },
        id   : 'customer_id',
        name : 'tdp_expire',
    });
    $(".alamat").editable('index.php?route=sale/editcustomer/edittable&token=<?php echo $token; ?>&column=alamat', {
        //type   : "autogrow",
        submit : 'Simpan',
        cancel : 'Cancel',
        cssclass : 'custom-class',
        cancelcssclass : 'badge bg-red',
        submitcssclass : 'badge bg-green',
        formid : 'abc-123',
        id   : 'customer_id',
        name : 'alamat',
        onblur    : "ignore"
    });
    $(".nama_pemilik").editable('index.php?route=sale/editcustomer/edittable&token=<?php echo $token; ?>&column=nama_pemilik', {
        submit : 'Simpan',
        cancel : 'Cancel',
        cssclass : 'custom-class',
        cancelcssclass : 'badge bg-red',
        submitcssclass : 'badge bg-green',
        formid : 'abc-123',
        id   : 'customer_id',
        name : 'nama_pemilik',
    });
    $(".provinsi").editable('index.php?route=sale/editcustomer/edittable&token=<?php echo $token; ?>&column=country', {
        type   : "select",
        //data   : '{"1":"Wiki","2":"Banana","Apple":"Apple", "Pear":"Pear"}',
        //data : '{"0":"Pilih","1":"Bpk","2":"Ibu","3":"Toko","4":"UD","5":"CV","6":"Firma","7":"PT","8":"","9":"PD","502":"Bengkel","503":"Pabrik","503":"Ekspedisi"}',
        data:'{<?php echo $provinsi?>}',
        //data:,
        submitdata : function(data) { 
          return {
            text:data,
            something: 'else'
          };
        },
        style  : "inherit",
        id   : 'customer_id',
        name : 'country',
    });
    <?php $i=1;foreach($customers as $c){?>
      var dar='<?php foreach($c['listkota'] as $lk){?><?php echo '"'.$lk['zone_id'].'":"'.$lk['name'].'",';?><?php } ?>';
    $(".kota<?php echo $i++?>").editable('index.php?route=sale/editcustomer/edittable&token=<?php echo $token; ?>&column=zone', {
        type   : "select",
        //data:'{<?php //echo substr($ctk,0,-1); ?>}',
        data:'{'+dar.substring(0, dar.length - 1)+'}',
        submitdata : function(data) { 
          return {
            text:data,
            something: 'else'
          };
        },
        style  : "inherit",
        id   : 'customer_id',
        name : 'zone',
    });
    <?php } ?>
    <?php $i=1;foreach($customers as $c){?>
      var kec='<?php foreach($c['listkecamatan'] as $lk){?><?php echo '"'.$lk['city_id'].'":"'.$lk['name'].'",';?><?php } ?>';
    $(".kecamatan<?php echo $i++?>").editable('index.php?route=sale/editcustomer/edittable&token=<?php echo $token; ?>&column=city', {
        type   : "select",
        //data   : '{"1":"Wiki","2":"Banana","Apple":"Apple", "Pear":"Pear"}',
        //data : '{"0":"Pilih","1":"Bpk","2":"Ibu","3":"Toko","4":"UD","5":"CV","6":"Firma","7":"PT","8":"","9":"PD","502":"Bengkel","503":"Pabrik","503":"Ekspedisi"}',
        data:'{'+kec.substring(0, kec.length - 1)+'}',
        //data:,
        submitdata : function(data) { 
          return {
            text:data,
            something: 'else'
          };
        },
        style  : "inherit",
        id   : 'customer_id',
        name : 'city',
    });
    <?php } ?>
    $(".alamat_pemilik").editable('index.php?route=sale/editcustomer/edittable&token=<?php echo $token; ?>&column=alamat_pemilik', {
        submit : 'Simpan',
        cancel : 'Cancel',
        cssclass : 'custom-class',
        cancelcssclass : 'badge bg-red',
        submitcssclass : 'badge bg-green',
        formid : 'abc-123',
        id   : 'customer_id',
        name : 'alamat_pemilik',
    });
    $(".telp_pemilik").editable('index.php?route=sale/editcustomer/edittable&token=<?php echo $token; ?>&column=telp_pemilik', {
        submit : 'Simpan',
        cancel : 'Cancel',
        cssclass : 'custom-class',
        cancelcssclass : 'badge bg-red',
        submitcssclass : 'badge bg-green',
        formid : 'abc-123',
        id   : 'customer_id',
        name : 'telp_pemilik',
    });
    $(".hp_pemilik").editable('index.php?route=sale/editcustomer/edittable&token=<?php echo $token; ?>&column=hp_pemilik', {
        submit : 'Simpan',
        cancel : 'Cancel',
        cssclass : 'custom-class',
        cancelcssclass : 'badge bg-red',
        submitcssclass : 'badge bg-green',
        formid : 'abc-123',
        id   : 'customer_id',
        name : 'hp_pemilik',
    });
    $(".tgllahir").editable('index.php?route=sale/editcustomer/edittable&token=<?php echo $token; ?>&column=tgllahir', {
        type :'datepicker',
        submit : 'Simpan',
        cancel : 'Cancel',
        cssclass : 'custom-class',
        cancelcssclass : 'badge bg-red',
        submitcssclass : 'badge bg-green',
        datepicker : {
            format: "yy-mm-dd"
        },
        id   : 'customer_id',
        name : 'tgllahir',
    });
    $(".npwp").editable('index.php?route=sale/editcustomer/edittable&token=<?php echo $token; ?>&column=npwp', {
        submit : 'Simpan',
        cancel : 'Cancel',
        cssclass : 'custom-class',
        cancelcssclass : 'badge bg-red',
        submitcssclass : 'badge bg-green',
        formid : 'abc-123',
        id   : 'customer_id',
        name : 'npwp',
    });
    $(".namanpwp").editable('index.php?route=sale/editcustomer/edittable&token=<?php echo $token; ?>&column=namanpwp', {
        submit : 'Simpan',
        cancel : 'Cancel',
        cssclass : 'custom-class',
        cancelcssclass : 'badge bg-red',
        submitcssclass : 'badge bg-green',
        formid : 'abc-123',
        id   : 'customer_id',
        name : 'namanpwp',
    });
    $(".alamatnpwp").editable('index.php?route=sale/editcustomer/edittable&token=<?php echo $token; ?>&column=alamatnpwp', {
        submit : 'Simpan',
        cancel : 'Cancel',
        cssclass : 'custom-class',
        cancelcssclass : 'badge bg-red',
        submitcssclass : 'badge bg-green',
        formid : 'abc-123',
        id   : 'customer_id',
        name : 'alamatnpwp',
    });
    $(".namaktp").editable('index.php?route=sale/editcustomer/edittable&token=<?php echo $token; ?>&column=namaktp', {
        submit : 'Simpan',
        cancel : 'Cancel',
        cssclass : 'custom-class',
        cancelcssclass : 'badge bg-red',
        submitcssclass : 'badge bg-green',
        formid : 'abc-123',
        id   : 'customer_id',
        name : 'namaktp',
    });
    $(".alamatktp").editable('index.php?route=sale/editcustomer/edittable&token=<?php echo $token; ?>&column=alamatktp', {
        submit : 'Simpan',
        cancel : 'Cancel',
        cssclass : 'custom-class',
        cancelcssclass : 'badge bg-red',
        submitcssclass : 'badge bg-green',
        formid : 'abc-123',
        id   : 'customer_id',
        name : 'alamatktp',
    });
    $(".noktp").editable('index.php?route=sale/editcustomer/edittable&token=<?php echo $token; ?>&column=noktp', {
        submit : 'Simpan',
        cancel : 'Cancel',
        cssclass : 'custom-class',
        cancelcssclass : 'badge bg-red',
        submitcssclass : 'badge bg-green',
        formid : 'abc-123',
        id   : 'customer_id',
        name : 'noktp',
    });
    $(".telephone2").editable('index.php?route=sale/editcustomer/edittable&token=<?php echo $token; ?>&column=telephone2', {
        submit : 'Simpan',
        cancel : 'Cancel',
        cssclass : 'custom-class',
        cancelcssclass : 'badge bg-red',
        submitcssclass : 'badge bg-green',
        formid : 'abc-123',
        id   : 'customer_id',
        name : 'telephone2',
    });
    
  });
</script>
<?php echo $footer; ?>
