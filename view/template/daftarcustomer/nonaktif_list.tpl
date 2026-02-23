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
                <h3>Daftar Customer Non-Aktif</h3>
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
                    <td>
                      <input type="text" class="form-control" name="filter_name" value="<?php echo $filter_name; ?>" />
                      <!-- <select name="filter_customer_id" class="form-control lokasi-pameran">
                      <option value="*" <?php echo empty($filter_customer_id)?'selected':'';?>>Semua Customer</option> -->
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
                        <th class="right" style="width: 10%">status</th>
                        <!--<th class="right" style="width: 10%">inv terakhir(dalam hari)</th>-->
                        <th class="right" style="width: 10%">Tgl inv terakhir</th>
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
                        $hari=floor($diff / (60 * 60 * 24)) ;
                        if($status<60){
                          $status="<span class='badge bg-blue'>customer aktif</span>";
                        }else if($status>=60 && $status<18178){
                          $status="<span class='badge bg-red'>customer non aktif</span>";
                        }else if($status==18178){
                          $status="<span class='badge bg-yellow'>Belum Customer</span>";
                        }else{
                          $status="";
                        }
                        ?>
                        
                        <?php if($hari<18178 && $hari>=$lamahari && $hari>=60){?>
                        <?php //if($hari<=$lamahari && $lamahari<18178 && $hari>=60){?>
                      <tr>
                        <td style="text-align: center;"><?php echo $customer['no']?></td>
                        <td class="left"><?php echo $customer['sales']; ?>
                        <td class="left"><?php echo $customer['customer_group']; ?>
                        <td class="left"><?php echo $customer['name']; ?>
                          <br><small><?php echo $customer['email']; ?></small>
                        </td>
                        <td class="left"><?php echo $customer['namapicdepantengah'].' '.$customer['namapicbelakang']; ?></td>
                        <td class="left"><?php echo $customer['telephone']; ?></td>
                        <td class="left"><?php echo $customer['alamat']; ?></td>
                        <td class="left"><?php echo $customer['provinsi']; ?></td>
                        <td><?php echo $status; ?></td>
                        <!--<td><?php echo $hari; ?></td>-->
                        <td><?php echo $customer['tglinvterakhir']; ?></td>
                      </tr>
                      <?php } ?>
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
$('.sidebar-menu').find('#menu-daftar-customer-nonaktif').addClass('active');
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
  url = 'index.php?route=daftarcustomer/nonaktif&token=<?php echo $token; ?>';

  var filter_name = $('input[name=\'filter_name\']').val();

  if (filter_name) {
    url += '&filter_name=' + encodeURIComponent(filter_name);
  }

  var filter_customer_id = $('select[name=\'filter_customer_id\']').val();

  if (filter_customer_id != '*') {
    url += '&filter_customer_id=' + encodeURIComponent(filter_customer_id);
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

 $(".lokasi-pameran").select2({
    ajax: {
    url:"index.php?route=sale/customer/autocomplete&token=<?php echo $token; ?>",
    //url: "index.php?route=pamerantoko/toko/autocomplete&token=<?php echo $this->request->get['token']; ?>",
    dataType: 'json',
    data: function (params) {
      return {
        q: params.term, // search term

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
//--></script>
<?php echo $footer; ?>
