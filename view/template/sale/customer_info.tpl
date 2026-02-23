<?php echo $header; ?>
<div id="content" class="content-wrapper">
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
                    <a onclick="location = '<?php echo $cancel; ?>';" class="btn btn-danger">Kembali</a>
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

                <?php if ($error) { ?>
                <div class="alert alert-danger alert-dismissible">
                  <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                  <h4><i class="icon fa fa-check"></i> Alert!</h4>
                  <?php
                  foreach($error as $e){
                   echo $e."<br>";
                 }
                   ?>
                </div>
                <?php
                }
                ?>
              </div>
            </div>

            <div class="row">

                <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
                  <div class="col-md-6">
                  <table class="table">
                    <tr>
                         <td>Sales</td>
                         <td>
                           <?php
                           $this->load->model('user/user');
                           $s=$this->model_user_user->getUser($customer['sales']);
                           echo $s['firstname'];
                           ?>
                         </td>
                     </tr>

                    <tr>
                      <td>Customer Group</td>
                      <td>
                        <?php
                        $this->load->model('sale/customer_group');
                        echo $this->model_sale_customer_group->getCustomerGroupName($customer['customer_group_id']);
                        ?>
                      </td>
                    </tr>
                    <tr>
                      <td>Area</td>
                      <td></td>
                    </tr>

                    <tr>
                      <td> Nama Customer</td>
                      <td><?php
                        $this->load->model('catalog/title');
                        $this->load->model('localisation/country');
                        $this->load->model('localisation/zone');
                        $this->load->model('localisation/city');
                        echo $this->model_catalog_title->getTitle($customer['title']).' '.$customer['name'];?></td>
                    </tr>


                    <tr>
                      <td>Email</td>
                      <td><?php echo $customer['email']; ?></td>
                    </tr>
                    <tr>
                      <td>Fax</td>
                      <td><?php echo $customer['fax']; ?></td>
                    </tr>

                    <tr>
                      <td>Alamat</td>
                      <td>
                        <?php echo $customer['alamat']; ?>

                        </td>
                    </tr>
                    <tr>
                      <td> Propinsi</td>
                      <td>
                        <?php echo $this->model_localisation_country->getCountryName($customer['country']); ?>
                      </td>
                    </tr>
                    <!-- tokocepat -->
                    <tr>
                      <td> Kota/Kabupaten</td>
                      <td>
                        <?php echo $this->model_localisation_zone->getCountryName($customer['zone']); ?>
                        </td>
                    </tr>
                    <tr>
                      <td> Kecamatan</td>
                      <td>
                        <?php echo $this->model_localisation_city->getCountryName($customer['city']); ?>
                        </td>
                    </tr>

                    <tr>
                      <td>Telephone</td>
                      <td><?php echo $customer['telephone']; ?>
                        </td>
                    </tr>
                    <tr>
                      <td>Nomor SIUP</td>
                      <td><?php echo $customer['siup']; ?>
                        </td>
                    </tr>

                    <tr>
                      <td>Kadaluwarsa SIUP</td>
                      <td><?php echo date('d/m/y',strtotime($customer['siup_expire'])); ?>
                        </td>
                    </tr>
                    <tr>
                      <td>Nomor TDP</td>
                      <td><?php echo $customer['tdp']; ?>
                        </td>
                    </tr>

                    <tr>
                      <td>Kadaluwarsa TDP</td>
                      <td><?php echo date('d/m/y',strtotime($customer['tdp_expire'])); ?>
                        </td>
                    </tr>
                    <tr>
                      <td>Limit Piutang</td>
                      <td><?php echo $this->currency->format($customer['limit_piutang']); ?></td>
                    </tr>

                  </table>
                  </div>
                  <div class="col-md-6">
                    <table class="table table-bordered" style="border:2px solid #000">
                      <tr>
                        <td> Nama NPWP</td>
                        <td><?php echo $customer['namanpwp']; ?>
                          </td>
                      </tr>
                      <tr>
                        <td> Nomor NPWP</td>
                        <td><?php echo $customer['npwp']; ?>
                          </td>
                      </tr>
                      <tr>
                        <td> Alamat NPWP</td>
                        <td><?php echo $customer['alamatnpwp']; ?>
                          </td>
                      </tr>
                      <tr>
                        <td> Nama KTP</td>
                        <td><?php echo $customer['namaktp']; ?>
                          </td>
                      </tr>
                      <tr>
                        <td> Nomor KTP</td>
                        <td><?php echo $customer['noktp']; ?>
                          </td>
                      </tr>
                      <tr>
                        <td> Alamat KTP</td>
                        <td><?php echo $customer['alamatktp']; ?>
                          </td>
                      </tr>
                    </table>
                    <table class="table">
                      <tr>
                        <td> Nama Pemilik/Penanggungjawab</td>
                        <td><?php echo $customer['nama_pemilik']; ?>
                          </td>
                      </tr>
                      <tr>
                        <td>Telp. Pemilik</td>
                        <td><?php echo $customer['telp_pemilik']; ?></td>
                      </tr>
                      <tr>
                        <td>No. HP Pemilik</td>
                        <td><?php echo $customer['hp_pemilik']; ?></td>
                      </tr>
                      <tr>
                        <td>Alamat Pemilik</td>
                        <td><?php echo $customer['alamat_pemilik']; ?>
                          </td>
                      </tr>
                        <td>Tempat Lahir</td>
                        <td><?php echo $customer['tempat_lahir']; ?>
                          </td>
                      </tr>
                      <tr>
                        <td>Tanggal Lahir</td>
                        <td><?php echo date('d/m/y',strtotime($customer['tgllahir'])); ?>
                          </td>
                      </tr>

                      <tr>
                        <td>Status Kawin</td>
                        <td>
                          <?php
                            if($customer['status_perkawinan'] == 1){
                              echo 'Belum Menikah';
                            }
                            if($customer['status_perkawinan'] == 2){
                              echo 'Sudah Menikah';
                            }
                            if($customer['status_perkawinan'] == 3){
                              echo 'Janda/Duda';
                            }
                          ?>
                          </td>
                      </tr>

                    </table>
                  </div>
                </form>

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
<script type="text/javascript"><!--
function countrys(element, zone_id) {
  $.ajax({
		url: 'index.php?route=sale/customer/country&token=<?php echo $token; ?>&country_id=' + element.value,
		dataType: 'json',
		beforeSend: function() {
			$('select[name=\'country\']').after('<span class="wait">&nbsp;<img src="view/image/loading.gif" alt="" /></span>');
		},
		complete: function() {
			$('.wait').remove();
		},
		success: function(json) {

			html = '<option value=""><?php echo $text_select; ?></option>';

			if (json['zone'] != '') {
				for (i = 0; i < json['zone'].length; i++) {
        			html += '<option value="' + json['zone'][i]['zone_id'] + '"';

					if (json['zone'][i]['zone_id'] == zone_id) {
	      				html += ' selected="selected"';
	    			}

	    			html += '>' + json['zone'][i]['name'] + '</option>';
				}
			} else {
				html += '<option value="0"><?php echo $text_none; ?></option>';
			}

			$('select[name=\'zone\']').html(html);
      $('select[name=\'zone\']').trigger('change');

		},
		error: function(xhr, ajaxOptions, thrownError) {
			alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
		}
	});

}



/* tokocepat */

function zones(element, city_id) {
	$.ajax({
		url: 'index.php?route=sale/customer/zone&token=<?php echo $token; ?>&zone_id=' + element.value,
		dataType: 'json',
		beforeSend: function() {
			$('select[name=\'zone\']').after('<span class="wait">&nbsp;<img src="view/image/loading.gif" alt="" /></span>');
		},
		complete: function() {
			$('.wait').remove();
		},
		success: function(json) {
			html = '<option value=""><?php echo $text_select; ?></option>';

			if (json['city'] != '') {
				for (i = 0; i < json['city'].length; i++) {
        			html += '<option value="' + json['city'][i]['city_id'] + '"';

					if (json['city'][i]['city_id'] == city_id) {
	      				html += ' selected="selected"';
	    			}

	    			html += '>' + json['city'][i]['name'] + '</option>';
				}
			} else {
				html += '<option value="0"><?php echo $text_none; ?></option>';
			}

			$('select[name=\'city\']').html(html);
		},
		error: function(xhr, ajaxOptions, thrownError) {
			alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
		}
	});
}


$(function(){
  $('select[name=\'country\']').trigger('change');


})
//--></script>
<script>
$('.sidebar-menu').find('#menu-customer').addClass('active');
$('.sidebar-menu').find('#menu-customer-group').addClass('active');
$(function(){
  $(".select-ads").select2({


      theme:"bootstrap"
    });
})
</script>
<script>
$(function(){
  $('.date').datepicker({dateFormat: 'yy-mm-dd'});
})
</script>
<script type="text/javascript"><!--
$('#form input').keydown(function(e) {
	if (e.keyCode == 13) {
		filter();
	}
});
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
//--></script>


<?php echo $footer; ?>
