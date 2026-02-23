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
            <h3 class="box-title">Toko/Alamat Customer</h3>
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
              <div class="col-md-12">
                <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
                  <table class="table">
                    <tr>
                      <td><span class="required">*</span> Nama Toko</td>
                      <td><input class="form-control" type="text" name="firstname" value="<?php echo $firstname; ?>" />
                        <?php if (isset($error_address_firstname)) { ?>
                        <span class="error"><?php echo $error_address_firstname; ?></span>
                        <?php } ?></td>
                    </tr>
                    <tr>
                      <td><span class="required">*</span> Nama Pengelola</td>
                      <td><input class="form-control" type="text" name="lastname" value="<?php echo $lastname; ?>" />
                        <?php if (isset($error_address_lastname)) { ?>
                        <span class="error"><?php echo $error_address_lastname; ?></span>
                        <?php } ?></td>
                    </tr>

                    <tr>
                      <td><span class="required">*</span> Alamat</td>
                      <td><input class="form-control" type="text" name="address_1" value="<?php echo $address_1; ?>" />
                        <?php if (isset($error_address_address_1)) { ?>
                        <span class="error"><?php echo $error_address_address_1; ?></span>
                        <?php } ?></td>
                    </tr>
                    <tr>
                      <td>Alamat (baris 2)</td>
                      <td><input class="form-control" type="text" name="address_2" value="<?php echo $address_2; ?>" /></td>
                    </tr>
                    <tr>
                      <td><span id="postcode-required<?php echo $address_row; ?>" class="required">*</span> Kodepos</td>
                      <td><input class="form-control" type="text" name="postcode" value="<?php echo $postcode; ?>" /></td>
                    </tr>
                    <tr>
                      <td><span class="required">*</span> Propinsi</td>
                      <td><select class="form-control" name="country_id" onchange="country(this, '<?php echo $zone_id; ?>');">
                          <option value=""><?php echo $text_select; ?></option>
                          <?php foreach ($countries as $country) { ?>
                          <?php if ($country['country_id'] == $country_id) { ?>
                          <option value="<?php echo $country['country_id']; ?>" selected="selected"><?php echo $country['name']; ?></option>
                          <?php } else { ?>
                          <option value="<?php echo $country['country_id']; ?>"><?php echo $country['name']; ?></option>
                          <?php } ?>
                          <?php } ?>
                        </select>
                        <?php if (isset($error_address_country)) { ?>
                        <span class="error"><?php echo $error_address_country; ?></span>
                        <?php } ?></td>
                    </tr>
                    <!-- tokocepat -->
                    <tr>
                      <td><span class="required">*</span> Kota/Kabupaten</td>
                      <td><select class="form-control" name="zone_id" onchange="zone(this, '<?php echo $city_id; ?>');">
                          <option value=""><?php echo $text_select; ?></option>
                          <?php foreach ($address['zones'] as $zone) { ?>
                          <?php if ($zone['zone_id'] == $zone_id) { ?>
                          <option value="<?php echo $zone['zone_id']; ?>" selected="selected"><?php echo $zone['name']; ?></option>
                          <?php } else { ?>
                          <option value="<?php echo $zone['zone_id']; ?>"><?php echo $zone['name']; ?></option>
                          <?php } ?>
                          <?php } ?>
                        </select>
                        <?php if (isset($error_address_zone)) { ?>
                        <span class="error"><?php echo $error_address_zone; ?></span>
                        <?php } ?></td>
                    </tr>
                    <tr>
                      <td><span class="required">*</span> Kecamatan</td>
                      <td><select class="form-control" name="city_id">
                          <option value=""><?php echo $text_select; ?></option>
                          <?php foreach ($address['cities'] as $city) { ?>
                          <?php if ($city['city_id'] == $city_id) { ?>
                          <option value="<?php echo $city['city_id']; ?>" selected="selected"><?php echo $city['name']; ?></option>
                          <?php } else { ?>
                          <option value="<?php echo $city['city_id']; ?>"><?php echo $city['name']; ?></option>
                          <?php } ?>
                          <?php } ?>
                        </select>
                        <?php if (isset($error_address_city)) { ?>
                        <span class="error"><?php echo $error_address_city; ?></span>
                        <?php } ?></td>
                    </tr>
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

//--></script>
<script type="text/javascript"><!--
$('#form input').keydown(function(e) {
	if (e.keyCode == 13) {
		filter();
	}
});
//--></script>
<script type="text/javascript"><!--
function country(element, zone_id) {
	$.ajax({
		url: 'index.php?route=sale/customer/country&token=<?php echo $token; ?>&country_id=' + element.value,
		dataType: 'json',
		beforeSend: function() {
			$('select[name=\'country_id\']').after('<span class="wait">&nbsp;<img src="view/image/loading.gif" alt="" /></span>');
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

			$('select[name=\'zone_id\']').html(html);
		},
		error: function(xhr, ajaxOptions, thrownError) {
			alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
		}
	});
}

$('select[name$=\'[country_id]\']').trigger('change');

/* tokocepat */

function zone(element, city_id) {
	$.ajax({
		url: 'index.php?route=sale/customer/zone&token=<?php echo $token; ?>&zone_id=' + element.value,
		dataType: 'json',
		beforeSend: function() {
			$('select[name=\'zone_id\']').after('<span class="wait">&nbsp;<img src="view/image/loading.gif" alt="" /></span>');
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

			$('select[name=\'city_id\']').html(html);
		},
		error: function(xhr, ajaxOptions, thrownError) {
			alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
		}
	});
}

$('select[name=\'[zone_id]\']').trigger('change');

//--></script>
<?php echo $footer; ?>
