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
              <h3>Setting User Sales Inbond dan Outbond</h3>
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
                      <th>#</th>
                      <th>Nama Sales</th>
                      <th>Kelompok User</th>
                      <th>Provinsi</th>
                      <th>Kategory Customer</th>
                      <th>Lama Non-aktif (dalam hari)</th>
                      <th>Action</th>
                    </thead>
                    <tbody>
                      <?php $no=1;foreach($customers as $cust){?>
                      <tr>
                        <td><?php echo $no++?></td>
                        <td><?php echo $cust['name']?></td>
                        <td><?php echo $cust['group']?></td>
                        <td>
                          <ol>
                          <?php
                            if(!empty($cust['provinsi'])){
                              foreach($cust['provinsi'] as $pro){
                                  echo '<li>'.$this->model_daftarcustomer_nonaktif->getnamaprov($pro['provinsi'])."</li><br>";
                              }
                            }else{
                              echo "<span class='badge bg-red'>Belum disetting</span>";
                            }
                          ?>
                          </ol>
                        </td>
                        <td>
                          <ol>
                          <?php
                            if(!empty($cust['category'])){
                              foreach($cust['category'] as $pro){
                                  echo '<li>'.$this->model_daftarcustomer_nonaktif->getnamacat($pro['category'])."</li><br>";
                              }
                            }else{
                              echo "<span class='badge bg-red'>Belum disetting</span>";
                            }
                          ?>
                          </ol>
                        </td>
                        <td><?php echo $cust['lamanonaktif']?></td>
                        <td class="right"><?php foreach ($cust['action'] as $action) { ?>
                          <a href="<?php echo $action['href']; ?>" class="badge bg-blue"><?php echo $action['text']; ?></a>
                          <?php } ?>
                        </td>
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
                <div class="pull-right"><?php //echo $pagination; ?></div>
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
$('.sidebar-menu').find('#menu-daftar-customer-settinguser').addClass('active');
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
  url = 'index.php?route=daftarcustomer/settinguser&token=<?php echo $token; ?>';

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
