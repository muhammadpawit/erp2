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
            <h3 class="box-title">Customer to Excel</h3>
            <div class="button pull-right">
                  <?php
                  $route=$this->request->get['route'];
                  $r=explode('/',$route);
                  if($r[1] == 'customer'){
                  ?>
                    <a onclick="location = '<?php echo $insert; ?>'" class="btn btn-info">Tambah </a>
                  <?php
                  }
                  ?>
                    <a href="<?php echo $export ?>" target="_blank" class="btn btn-primary">Export to Excel</a>
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
            <div class="row">
              <div class="col-xs-12">
                <form action="" method="post" enctype="multipart/form-data" id="form">
                  <table class="table table-bordered">
                    <thead>
                      <tr>
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
                        <!--<th class="left">Area</th>-->
                        <th class="left">Telephone</th>
                        <th class="left">Alamat</th>
                        <th class="left" style="width:10%">Total Penjualan 6 Bulan Terakhir</th>

                      </tr>
                    </thead>
                    <tbody>

                      <?php if ($customers) { ?>
                      <?php foreach ($customers as $customer) { ?>
                      <tr>
                            <td class="left"><?php echo $customer['sales']; ?>
                            <td class="left"><?php echo $customer['customer_group']; ?>
                        <td class="left"><?php echo $customer['name']; ?>
                          <br><small><?php echo $customer['email']; ?></small>
                        </td>
                        <!--<td class="left"><?php echo $customer['area']; ?></td>-->
                        <td class="left"><?php echo $customer['telephone']; ?></td>
                        <td class="left"><?php echo $customer['alamat']; ?></td>
                        <td class="left"><?php echo $customer['penjualanterakhir']; ?></td>
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
  url = 'index.php?route=sale/customerexcel&token=<?php echo $token; ?>';
  <?php
}else{
  ?>
  url = 'index.php?route=sale/customerexcel&token=<?php echo $token; ?>';
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
