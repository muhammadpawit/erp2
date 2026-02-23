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
            <h3 class="box-title">Setting Sales <?php echo $namasales?></h3>
            <div class="button pull-right">
                    <a onclick="$('#form').submit();" class="btn btn-info">Simpan</a>
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

            <div class="content">

                <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
                  <input type="hidden" name="user_id" value="<?php echo $this->request->get['user_id']?>">
                  <input type="hidden" name="namasales" value="<?php echo $namasales?>">
                  <input type="hidden" name="idsetting" value="<?php echo $idsetting?>">
                  <table class="table table-bordered">
                      <thead>
                        <tr>
                          <th>Provinsi</th>
                          <th>Category Customer</th>
                          <th>Lama Non-Aktif (Dalam hari)</th>
                        </tr>
                        <tr>
                          <th>
                            <div id="filter-prop" style="height:350px;overflow:scroll;">
                              <?php
                              
                              foreach($countries as $c){
                              ?>
                                <?php if(in_array($c['country_id'], $pro)) { ?>
                                <input type="checkbox" name="filter_provinsi[]" value="<?php echo $c['country_id'] ?>" checked="checked"/><?php echo $c['name'] ?><br>
                              <?php }else{ ?>
                                <input type="checkbox" name="filter_provinsi[]" value="<?php echo $c['country_id'] ?>"/> <?php echo $c['name'] ?><br>
                              <?php } ?>
                              
                              <?php
                              }
                              ?>
                            </div>
                          </th>
                          <th>
                            <div id="filter-cust" style="height:350px;overflow:scroll;">
                              <?php
                              foreach($customer_groups as $c){
                              ?>
                                 <?php if(in_array($c['customer_group_id'], $cat)) { ?>
                                <input type="checkbox" name="filter_customer_group[]" value="<?php echo $c['customer_group_id'] ?>" checked="checked"/><?php echo $c['name'] ?><br>
                              <?php }else{ ?>
                                <input type="checkbox" name="filter_customer_group[]" value="<?php echo $c['customer_group_id'] ?>"/> <?php echo $c['name'] ?><br>
                              <?php } ?>
                              <?php
                              }
                              ?>
                            </div>
                          </th>
                          <td valign="top"><input type="text" name="lamahari" value="<?php echo $info['lamanonaktif']?>" class="form-control" required></td>
                        </tr>
                      </thead>
                  </table>
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
<script type="text/javascript">
function hapusjadwal(id){
  var r=window.confirm("apakah yakin?");
  if(r==true){
    $.ajax({
      url: 'index.php?route=sale/customer/hapusjadwal&token=<?php echo $token; ?>&customer_id=' + id,
      success: function(json) {
        console.log(json);
        if(json>0){
          alert("Berhasil Dihapus!");
          location.reload();
        }else{
          alert("Gagal Dihapus!");
        }
      },
    });
  }else{
    return false;
  }
}
$( ".jam" ).keypress(function(e) {
  var key = e.keyCode;
  if (key >= 48 && key <= 57 || key>=45 && key<=46 || key==32) {
    console.log(key);
  }else{
    e.preventDefault();
    swal("hanya boleh memasukan numeric,titik dan strip !");
  }
});
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
  $('.date').datepicker({dateFormat: 'yy-mm-dd',changeMonth: true,
      changeYear: true});
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

//function simpan()
//--></script>


<?php echo $footer; ?>
