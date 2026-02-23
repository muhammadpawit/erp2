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
            <h3 class="box-title">Kabupaten/Kota</h3>
            <div class="button pull-right">
									<a href="<?php echo $insert; ?>"><button type="button" class="btn btn-primary">Tambah</button></a>
                  <a onclick="$('#form').submit();" ><button type="button" class="btn btn-danger">Hapus</button></a>
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
                <?php if ($success) { ?>
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
                <form action="<?php echo $delete; ?>" method="post" enctype="multipart/form-data" id="form">
                  <table class="table table-bordered">
                    <thead>
                      <tr>
                        <td width="1" style="text-align: center;">
                          <input type="checkbox" onclick="$('input[name*=\'selected\']').attr('checked', this.checked);" />
                        </td>
                        <th class="left">Kecamatan</th>
                        <th class="left">Provinsi</th>
                        <th class="left">Kabupaten/Kota</th>

                        <th class="right"></th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr>
                        <td></td>

                        <td><input type="text" class="form-control" name="filter_name" value="<?php echo $filter_name; ?>" /></td>

                        <td><select class="form-control" name="filter_country_id" onchange="countrys(this);">
                            <option value="*">Semua Provinsi</option>
                            <?php
                            foreach($countries as $c){
                            ?>
                            <option value="<?php echo $c['country_id']?>" <?php echo $c['country_id'] == $filter_country_id?'selected':''; ?>><?php echo $c['name']?></option>
                            <?php
                            }
                            ?>
                        </select></td>
                        <td>
                          <select class="form-control" name="filter_zone_id">
                              <option value="*">Semua Kabupaten/Kota</option>
                            </select>
                        </td>
                        <td align="right"><a onclick="filter();" class="btn btn-info">Filter</a></td>
                      </tr>
                      <?php if ($options) { ?>
                      <?php foreach ($options as $category) { ?>
                      <tr>
                        <td style="text-align: center;">
                          <?php
                          //if(empty($category['cek'])){
                          ?>
                          <?php if ($category['selected']) { ?>
                          <input type="checkbox" name="selected[]" value="<?php echo $category['id']; ?>" checked="checked" />
                          <?php } else { ?>
                          <input type="checkbox" name="selected[]" value="<?php echo $category['id']; ?>" />
                          <?php }
                        //}
                        ?></td>
                        <td class="left"><?php echo $category['name']; ?></td>
                        <td class="left"><?php echo $category['provinsi']; ?></td>
                        <td class="left"><?php echo $category['kabupaten']; ?></td>
                      <td class="right"><?php foreach ($category['action'] as $action) { ?>
                          <a href="<?php echo $action['href']; ?>" class="badge bg-blue"><?php echo $action['text']; ?></a>
                          <?php } ?></td>
                      </tr>
                      <?php } ?>
                      <?php } else { ?>
                      <tr>
                        <td class="center" colspan="4">Data kecamatan tidak ditemukan</td>
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
$('.sidebar-menu').find('#menu-master-data').addClass('active');
$('.sidebar-menu').find('#menu-city').addClass('active');
</script>
<script type="text/javascript"><!--
function filter() {
	url = 'index.php?route=catalog/city&token=<?php echo $token; ?>';

	var filter_name = $('input[name=\'filter_name\']').val();

	if (filter_name) {
		url += '&filter_name=' + encodeURIComponent(filter_name);
	}

  var filter_country_id = $('select[name=\'filter_country_id\']').val();

	if (filter_country_id != '*') {
		url += '&filter_country_id=' + encodeURIComponent(filter_country_id);
	}

  var filter_zone_id = $('select[name=\'filter_zone_id\']').val();

	if (filter_zone_id != '*') {
		url += '&filter_zone_id=' + encodeURIComponent(filter_zone_id);
	}



	location = url;
}
//--></script>
<script type="text/javascript"><!--
function countrys(element) {
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
      html = '<option value="*">Semua Kabupaten</option>';
      if (json['zone'] != '') {
        for (i = 0; i < json['zone'].length; i++) {
              html += '<option value="' + json['zone'][i]['zone_id'] + '"';



            html += '>' + json['zone'][i]['name'] + '</option>';
        }
      }
		    $('select[name=\'filter_zone_id\']').html(html);
		},
		error: function(xhr, ajaxOptions, thrownError) {
			alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
		}
	});

}
</script>
<?php echo $footer; ?>
