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
            <h3 class="box-title">Tambah/Edit Kecamatan</h3>
            <div class="button pull-right">
									<a onclick="$('#form').submit();" class="button"><button type="button" class="btn btn-primary">Simpan</button></a>
                  <a href="<?php echo $cancel; ?>"><button type="button" class="btn btn-warning">Kembali</button></a>
								</div>
          </div>
          <div class="box-body">
            <div class="row">
              <div class="col-md-12">
                <?php if ($error_warning) { ?>
                <div class="alert alert-danger alert-dismissible">
                  <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                  <h4><i class="icon fa fa-ban"></i> Alert!</h4>
                  <?php echo $error_warning; ?>
                </div>
                <?php
                }
                ?>
                <?php if (isset($success)) { ?>
                <div class="alert alert-success alert-dismissible">
                  <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                  <h4><i class="icon fa fa-check"></i> Success!</h4>
                  <?php echo $success; ?>
                </div>
                <?php
                }
                ?>
              </div>
            </div>
            <div class="row">
              <div class="col-md-12">
                <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
                  <table class="table table-stripped">
                    <tr>
                      <td><span class="required">*</span> Nama Kecamatan</td>
                      <td><input type="text" class="form-control" name="name" size="100" value="<?php echo $name; ?>" />
                        <?php if ($error_name) { ?>
                        <span class="help-block"><?php echo $error_name; ?></span>
                        <?php } ?></td>
                    </tr>
                    <tr>
                      <td><span class="required">*</span> Nama Provinsi</td>
                      <td><select class="form-control" name="country_id" onchange="countrys(this, '<?php echo $zone_id; ?>');">
                            <?php
                          foreach($countries as $c){
                          ?>
                          <option value="<?php echo $c['country_id']?>" <?php echo $c['country_id'] == $country_id?'selected':''; ?>><?php echo $c['name']?></option>
                          <?php
                          }
                          ?>
                      </select></td>
                    </tr>
                    <tr>
                      <td><span class="required">*</span> Kota/Kabupaten</td>
                      <td><select class="form-control" name="zone_id">
                          <?php foreach ($zones as $zone) { ?>
                          <?php if ($zone['zone_id'] == $zone) { ?>
                          <option value="<?php echo $zone['zone_id']; ?>" selected="selected"><?php echo $zone['name']; ?></option>
                          <?php } else { ?>
                          <option value="<?php echo $zone['zone_id']; ?>"><?php echo $zone['name']; ?></option>
                          <?php } ?>
                          <?php } ?>
                        </select>
                        </td>
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
$('.sidebar-menu').find('#menu-master-data').addClass('active');
$('.sidebar-menu').find('#menu-provinsi').addClass('active');
$(function(){
    $('select[name=\'country_id\']').trigger('change');
})
</script>
<script type="text/javascript"><!--
function countrys(element, zone_id) {
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

			html = '';

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
</script>


<?php echo $footer; ?>
