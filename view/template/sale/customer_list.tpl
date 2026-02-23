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
                <?php
                  $route=$this->request->get['route'];
                  $r=explode('/',$route);
                  if($r[1] == 'customer'){
                  ?>
            <h3 class="box-title">Customer</h3>
            <?php }else{
              echo '<h3 class="box-title">Daftar Customer</h3>';
            } ?>
            <div class="button pull-right">
                  <?php
                  $route=$this->request->get['route'];
                  $r=explode('/',$route);
                  if($r[1] == 'customer'){
                  ?>
                    <a onclick="location = '<?php echo $insert; ?>'" class="btn btn-info">Tambah</a>
                    <a onclick="$('form').attr('action', '<?php echo $delete; ?>'); $('form').submit();" class="btn btn-danger">Hapus</a>
                  <?php
                  }
                  ?>
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
                  <?php if($this->user->getId()=="116" OR $this->user->getId()=="52000"){?>
                  <tr>
                    <td>status customer</td>
                    <td>
                        <!--<input type="checkbox" name="status" value="1" class="messageCheckbox"><label for="status"> Aktif</label><br>-->
                        <input type="checkbox" name="status" value="0" class="messageCheckbox"><label for="status"> Non-Aktif</label><br>
                        <a onclick="downloads()" id="downloadcust" class="btn btn-success">Download</a> <!--<a onclick="tampils()" target="_blank" id="tampil" class="btn btn-success">tampil</a>-->
                        
                    </td>
                  </tr>
                  <?php } ?>
                </table>
              </div>
            </div>
            <div class="row">
              <div class="col-xs-12">
                <form action="" method="post" enctype="multipart/form-data" id="form">
                  <table class="table table-bordered">
                    <thead>
                      <tr>

                        <td width="1" style="text-align: center;" style="width:5%"><input type="checkbox" onclick="$('input[name*=\'selected\']').attr('checked', this.checked);" /></td>
                        <th class="left"><?php if ($sort == 'date_added') { ?>
                          <a href="<?php echo $sort_date_added; ?>" class="<?php echo strtolower($order); ?>">Tanggal Input</a>
                          <?php } else { ?>
                          <a href="<?php echo $sort_date_added; ?>">Tanggal Input</a>
                          <?php } ?></th>
                        <th class="left"><?php if ($sort == 'tgllahir') { ?>
                          <a href="<?php echo $sort_tgllahir; ?>" class="<?php echo strtolower($order); ?>">Tanggal Lshir</a>
                          <?php } else { ?>
                          <a href="<?php echo $sort_tgllahir; ?>">Tanggal Lahir</a>
                          <?php } ?></th>
                          <th class="left"><?php if ($sort == 'sales') { ?>
                            <a href="<?php echo $sort_sales; ?>" class="<?php echo strtolower($order); ?>">Sales</a>
                            <?php } else { ?>
                            <a href="<?php echo $sort_sales; ?>">Sales</a>
                            <?php } ?></th>
                            <th class="left">Kategori Customer</th>
                        <th class="left"><?php if ($sort == 'name') { ?>
                          <a href="<?php echo $sort_name; ?>" class="<?php echo strtolower($order); ?>">Nama</a>
                          <?php } else { ?>
                          <a href="<?php echo $sort_name; ?>">Nama</a>
                          <?php } ?></th>
                        <th class="left">Nama PIC</th>
                        <th class="left">Telephone</th>
                        <th class="left">Alamat</th>
                        <th class="left">Provinsi</th>
                        <th class="left">Kab/Kota</th>
                        <th class="left">Kecamatan</th>
                        <th class="left">Kodepos</th>
                        <th class="left" style="width:10%"><?php if ($sort == 'deposit') { ?>
                          <a href="<?php echo $sort_deposit; ?>" class="<?php echo strtolower($order); ?>">Deposit</a>
                          <?php } else { ?>
                          <a href="<?php echo $sort_deposit; ?>">Deposit</a>
                          <?php } ?></th>
                        <th class="left" style="width:10%">Piutang</th>
                        <th class="right" style="width:10%"><?php //echo $column_action; ?></td>
                        <th class="right" style="width: 10%">status</th>
                      </tr>
                    </thead>
                    <tbody>

                      <?php if ($customers) { ?>
                      <?php foreach ($customers as $customer) { ?>
                        <?php 
                        
                        $tanggal = $customer['invterakhir'];
                        $tanggal_lahir  = strtotime($customer['invterakhir']);
                        $sekarang    = time(); // Waktu sekarang
                        $diff   = $sekarang - $tanggal_lahir;
                        //echo 'umur anda adalah ' . floor($diff / (60 * 60 * 24)) . ' Hari'; // Umur anda dalam hitungan hari
                        $status=floor($diff / (60 * 60 * 24)) ;
                        if($status<=60){
                          $status="<span class='badge bg-blue'>customer aktif</span>";
                        }else if($status>=61 && $status<18178){
                          $status="<span class='badge bg-red'>customer non aktif</span>";
                        }else if($status==18178){
                          $status="<span class='badge bg-yellow'>Belum Customer</span>";
                        }else{
                          $status="";
                        }
                        ?>
                      <tr>
                        <td style="text-align: center;"><?php if ($customer['selected']) { ?>
                          <input type="checkbox" name="selected[]" value="<?php echo $customer['customer_id']; ?>" checked="checked" />
                          <?php } else { ?>
                          <input type="checkbox" name="selected[]" value="<?php echo $customer['customer_id']; ?>" />
                          <?php } ?></td>
                        <td class="left"><?php echo $customer['date_added']; ?>
                          <td class="left"><?php echo $customer['tgllahir']; ?>
                            <td class="left"><?php echo $customer['sales']; ?>
                            <td class="left"><?php echo $customer['customer_group']; ?>
                        <td class="left"><?php echo $customer['name']; ?>
                          <br><small><?php echo $customer['email']; ?></small>
                        </td>
                        <td class="left"><?php echo $customer['namapicdepantengah'].' '.$customer['namapicbelakang']; ?></td>
                        <td class="left"><?php echo $customer['telephone']; ?></td>
                        <td class="left"><?php echo $customer['alamat']; ?></td>
                        <td class="left"><?php echo $customer['provinsi']; ?></td>
                        <td class="left"><?php echo $customer['kota']; ?></td>
                        <td class="left"><?php echo $customer['kecamatan']; ?></td>
                        <td class="left"><?php echo $customer['kodepos']; ?></td>
                        <td class="left"><?php echo $customer['deposit']; ?></td>
                        <td class="left"><?php echo $customer['piutang']; ?><br>
                          <small>Limit: <?php echo $customer['limit_piutang']; ?></small>
                        </td>

                        <td class="right"><?php foreach ($customer['action'] as $action) { ?>
                          <a href="<?php echo $action['href']; ?>" class="badge bg-green"><?php echo $action['text']; ?></a><br>
                          <?php } ?></td>
                        <td><?php echo $status; ?></td>
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
$('.sidebar-menu').find('#menu-customer-list').addClass('active');
$("input:checkbox").on('click', function() {
  // in the handler, 'this' refers to the box clicked on
  var $box = $(this);
  if ($box.is(":checked")) {
    var c=$('.messageCheckbox:checked').val();
    // the name of the box is retrieved using the .attr() method
    // as it is assumed and expected to be immutable
    var group = "input:checkbox[name='" + $box.attr("name") + "']";
    // the checked state of the group/box on the other hand will change
    // and the current value is retrieved using .prop() method
    $(group).prop("checked", false);
    $box.prop("checked", true);
    console.log(c);
   //swal($('.messageCheckbox:checked').val());
  } else {
    $box.prop("checked", false);
  }
});
function downloads(){
  var $box =$('.messageCheckbox:checked');
  if ($box.is(":checked")) {
    var c=$('.messageCheckbox:checked').val();
    url='index.php?route=sale/customer/custnonaktif&token=<?php echo $token; ?>';
    location = url;
    //swal(c);
  }else{
    swal('Mohon pilih status customer terlebih dulu.');
  }
}
function tampils(){
  var $box =$('.messageCheckbox:checked');
  if ($box.is(":checked")) {
    var c=$('.messageCheckbox:checked').val();
    swal("fitur belum tersedia");
  }else{
    swal('Mohon pilih status customer terlebih dulu.');
  }
}
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

// $(function(){
//   $(".alamat").select2({
//     ajax: {
//     url:"index.php?route=customer/customer/auto&token=<?php echo $this->request->get['token']; ?>",
//       dataType: 'json',
//     data: function (params) {
//       return {
//         q: params.term,
//         j:21

//       };
//     },
//     delay: 250,
//     processResults: function (data) {
//       return {
//         results: data
//       };
//     },
//     //cache: true
//   },
//   theme:"bootstrap"
//   });
// })
</script>

<script type="text/javascript"><!--
function filter() {
  <?php
    if($r[1] == 'customer'){
  ?>
  url = 'index.php?route=sale/customer&token=<?php echo $token; ?>';
  <?php
}else{
  ?>
  url = 'index.php?route=sale/daftarcustomer&token=<?php echo $token; ?>';
  <?php
}
  ?>

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
<?php echo $footer; ?>
