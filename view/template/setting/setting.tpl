<?php echo $header; ?>

<div class="content-wrapper" >
  <section class="content-header">
    <h1>

    </h1>

  </section>
  <section class="content" id="content">
    <div class="row">
      <div class="col-md-12">
        <div class="box">
          <div class="box-header with-border">
            <h3 class="box-title">Settings</h3>
            <div class="button pull-right">
                    <a onclick="$('#form').submit();" class="btn btn-info">Simpan</a>

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
                  <div id="tab-general">
                    <table class="table table-bordered">
                      <tr>
                        <td style="width:400px"><span class="required">*</span> <?php echo $entry_name; ?></td>
                        <td><input type="text" class="form-control" name="config_name" value="<?php echo $config_name; ?>" size="40" />
                          <?php if ($error_name) { ?>
                          <span class="error"><?php echo $error_name; ?></span>
                          <?php } ?></td>
                      </tr>
                      <tr style="display:none">
                        <td><span class="required">*</span> <?php echo $entry_owner; ?></td>
                        <td><input type="text" class="form-control" name="config_owner" value="<?php echo $config_owner; ?>" size="40" />
                          <?php if ($error_owner) { ?>
                          <span class="error"><?php echo $error_owner; ?></span>
                          <?php } ?></td>
                      </tr>
                      <tr>
                        <td><?php echo $entry_logo; ?></td>
                        <td><div class="image"><img src="<?php echo $logo; ?>" alt="" id="thumb-logo" />
                            <input type="hidden" name="config_logo" value="<?php echo $config_logo; ?>" id="logo" />
                            <br />
                            <a onclick="image_upload('logo', 'thumb-logo');"><?php echo $text_browse; ?></a>&nbsp;&nbsp;|&nbsp;&nbsp;<a onclick="$('#thumb-logo').attr('src', '<?php echo $no_image; ?>'); $('#logo').attr('value', '');"><?php echo $text_clear; ?></a></div></td>
                      </tr>
                      <tr>
                        <td><?php echo $entry_icon; ?></td>
                        <td><div class="image"><img src="<?php echo $icon; ?>" alt="" id="thumb-icon" />
                            <input type="hidden" name="config_icon" value="<?php echo $config_icon; ?>" id="icon" />
                            <br />
                            <a onclick="image_upload('icon', 'thumb-icon');"><?php echo $text_browse; ?></a>&nbsp;&nbsp;|&nbsp;&nbsp;<a onclick="$('#thumb-icon').attr('src', '<?php echo $no_image; ?>'); $('#icon').attr('value', '');"><?php echo $text_clear; ?></a></div></td>
                      </tr>
                      <tr>
                        <td><span class="required">*</span> <?php echo $entry_address; ?></td>
                        <td><textarea name="config_address" cols="40" rows="5"><?php echo $config_address; ?></textarea>
                          <?php if ($error_address) { ?>
                          <span class="error"><?php echo $error_address; ?></span>
                          <?php } ?></td>
                      </tr>
                      <tr>
                        <td><span class="required">*</span> <?php echo $entry_email; ?></td>
                        <td><input type="text" class="form-control" name="config_email" value="<?php echo $config_email; ?>" size="40" />
                          <?php if ($error_email) { ?>
                          <span class="error"><?php echo $error_email; ?></span>
                          <?php } ?></td>
                      </tr>
                      <tr>
                        <td><span class="required">*</span> <?php echo $entry_telephone; ?></td>
                        <td><input type="text" class="form-control" name="config_telephone" value="<?php echo $config_telephone; ?>" />
                          <?php if ($error_telephone) { ?>
                          <span class="error"><?php echo $error_telephone; ?></span>
                          <?php } ?></td>
                      </tr>
                      <tr>
                        <td><?php echo $entry_fax; ?></td>
                        <td><input type="text" class="form-control" name="config_fax" value="<?php echo $config_fax; ?>" /></td>
                      </tr>
                      
                      <tr>
                        <td>Status Printer</td>
                        <td>
                          <select class="form-control" name="config_printer_status">
                            <option value="1" <?php echo $config_printer_status ==1?'selected':''; ?>>Aktif</option>
                            <option value="1" <?php echo $config_printer_status ==0?'selected':''; ?>>Tidak Aktif</option>
                          </select>
                        </td>
                      </tr>
                      <tr style="display:none">
                        <td><span class="required">*</span> <?php echo $entry_title; ?></td>
                        <td><input type="text" class="form-control" name="config_title" value="<?php echo $config_title; ?>" />
                          <?php if ($error_title) { ?>
                          <span class="error"><?php echo $error_title; ?></span>
                          <?php } ?></td>
                      </tr>
                      <tr style="display:none">
                        <td><?php echo $entry_meta_description; ?></td>
                        <td><textarea name="config_meta_description" cols="40" rows="5"><?php echo $config_meta_description; ?></textarea></td>
                      </tr>
                      <tr>
                        <td><?php echo $entry_mail_protocol; ?></td>
                        <td><select class="form-control" name="config_mail_protocol">
                            <?php if ($config_mail_protocol == 'mail') { ?>
                            <option value="mail" selected="selected"><?php echo $text_mail; ?></option>
                            <?php } else { ?>
                            <option value="mail"><?php echo $text_mail; ?></option>
                            <?php } ?>
                            <?php if ($config_mail_protocol == 'smtp') { ?>
                            <option value="smtp" selected="selected"><?php echo $text_smtp; ?></option>
                            <?php } else { ?>
                            <option value="smtp"><?php echo $text_smtp; ?></option>
                            <?php } ?>
                          </select></td>
                      </tr>
                      <tr>
                        <td><?php echo $entry_mail_parameter; ?></td>
                        <td><input type="text" class="form-control" name="config_mail_parameter" value="<?php echo $config_mail_parameter; ?>" /></td>
                      </tr>
                      <tr>
                        <td><?php echo $entry_smtp_host; ?></td>
                        <td><input type="text" class="form-control" name="config_smtp_host" value="<?php echo $config_smtp_host; ?>" /></td>
                      </tr>
                      <tr>
                        <td><?php echo $entry_smtp_username; ?></td>
                        <td><input type="text" class="form-control" name="config_smtp_username" value="<?php echo $config_smtp_username; ?>" /></td>
                      </tr>
                      <tr>
                        <td><?php echo $entry_smtp_password; ?></td>
                        <td><input type="text" class="form-control" name="config_smtp_password" value="<?php echo $config_smtp_password; ?>" /></td>
                      </tr>
                      <tr>
                        <td><?php echo $entry_smtp_port; ?></td>
                        <td><input type="text" class="form-control" name="config_smtp_port" value="<?php echo $config_smtp_port; ?>" /></td>
                      </tr>
                      <tr>
                        <td><?php echo $entry_smtp_timeout; ?></td>
                        <td><input type="text" class="form-control" name="config_smtp_timeout" value="<?php echo $config_smtp_timeout; ?>" /></td>
                      </tr>
                      <tr style="display:none">
                        <td><?php echo $entry_alert_mail; ?></td>
                        <td><?php if ($config_alert_mail) { ?>
                          <input type="radio" name="config_alert_mail" value="1" checked="checked" />
                          <?php echo $text_yes; ?>
                          <input type="radio" name="config_alert_mail" value="0" />
                          <?php echo $text_no; ?>
                          <?php } else { ?>
                          <input type="radio" name="config_alert_mail" value="1" />
                          <?php echo $text_yes; ?>
                          <input type="radio" name="config_alert_mail" value="0" checked="checked" />
                          <?php echo $text_no; ?>
                          <?php } ?></td>
                      </tr>
                      <tr style="display:none">
                        <td><?php echo $entry_account_mail; ?></td>
                        <td><?php if ($config_account_mail) { ?>
                          <input type="radio" name="config_account_mail" value="1" checked="checked" />
                          <?php echo $text_yes; ?>
                          <input type="radio" name="config_account_mail" value="0" />
                          <?php echo $text_no; ?>
                          <?php } else { ?>
                          <input type="radio" name="config_account_mail" value="1" />
                          <?php echo $text_yes; ?>
                          <input type="radio" name="config_account_mail" value="0" checked="checked" />
                          <?php echo $text_no; ?>
                          <?php } ?></td>
                      </tr>
                      <tr style="display:none">
                        <td><?php echo $entry_alert_emails; ?></td>
                        <td><textarea name="config_alert_emails" cols="40" rows="5"><?php echo $config_alert_emails; ?></textarea></td>
                      </tr>
                      <tr style="display:none">
                        <td><?php echo $entry_use_ssl; ?></td>
                        <td><?php if ($config_use_ssl) { ?>
                          <input type="radio" name="config_use_ssl" value="1" checked="checked" />
                          <?php echo $text_yes; ?>
                          <input type="radio" name="config_use_ssl" value="0" />
                          <?php echo $text_no; ?>
                          <?php } else { ?>
                          <input type="radio" name="config_use_ssl" value="1" />
                          <?php echo $text_yes; ?>
                          <input type="radio" name="config_use_ssl" value="0" checked="checked" />
                          <?php echo $text_no; ?>
                          <?php } ?></td>
                      </tr>
                      <tr style="display:none">
                        <td><?php echo $entry_seo_url; ?></td>
                        <td><?php if ($config_seo_url) { ?>
                          <input type="radio" name="config_seo_url" value="1" checked="checked" />
                          <?php echo $text_yes; ?>
                          <input type="radio" name="config_seo_url" value="0" />
                          <?php echo $text_no; ?>
                          <?php } else { ?>
                          <input type="radio" name="config_seo_url" value="1" />
                          <?php echo $text_yes; ?>
                          <input type="radio" name="config_seo_url" value="0" checked="checked" />
                          <?php echo $text_no; ?>
                          <?php } ?></td>
                      </tr>
                      <tr style="display:none">
                        <td><?php echo $entry_maintenance; ?></td>
                        <td><?php if ($config_maintenance) { ?>
                          <input type="radio" name="config_maintenance" value="1" checked="checked" />
                          <?php echo $text_yes; ?>
                          <input type="radio" name="config_maintenance" value="0" />
                          <?php echo $text_no; ?>
                          <?php } else { ?>
                          <input type="radio" name="config_maintenance" value="1" />
                          <?php echo $text_yes; ?>
                          <input type="radio" name="config_maintenance" value="0" checked="checked" />
                          <?php echo $text_no; ?>
                          <?php } ?></td>
                      </tr>
                      <tr style="display:none">
                        <td><?php echo $entry_encryption; ?></td>
                        <td><input type="text" class="form-control" name="config_encryption" value="<?php echo $config_encryption; ?>" /></td>
                      </tr>
                      <tr style="display:none">
                        <td><?php echo $entry_compression; ?></td>
                        <td><input type="text" class="form-control" name="config_compression" value="<?php echo $config_compression; ?>" size="3" /></td>
                      </tr>
                      <tr style="display:none">
                        <td><?php echo $entry_error_display; ?></td>
                        <td><?php if ($config_error_display) { ?>
                          <input type="radio" name="config_error_display" value="1" checked="checked" />
                          <?php echo $text_yes; ?>
                          <input type="radio" name="config_error_display" value="0" />
                          <?php echo $text_no; ?>
                          <?php } else { ?>
                          <input type="radio" name="config_error_display" value="1" />
                          <?php echo $text_yes; ?>
                          <input type="radio" name="config_error_display" value="0" checked="checked" />
                          <?php echo $text_no; ?>
                          <?php } ?></td>
                      </tr>
                      <tr style="display:none">
                        <td><?php echo $entry_error_log; ?></td>
                        <td><?php if ($config_error_log) { ?>
                          <input type="radio" name="config_error_log" value="1" checked="checked" />
                          <?php echo $text_yes; ?>
                          <input type="radio" name="config_error_log" value="0" />
                          <?php echo $text_no; ?>
                          <?php } else { ?>
                          <input type="radio" name="config_error_log" value="1" />
                          <?php echo $text_yes; ?>
                          <input type="radio" name="config_error_log" value="0" checked="checked" />
                          <?php echo $text_no; ?>
                          <?php } ?></td>
                      </tr>
                      <tr style="display:none">
                        <td><span class="required">*</span> <?php echo $entry_error_filename; ?></td>
                        <td><input type="text" class="form-control" name="config_error_filename" value="<?php echo $config_error_filename; ?>" />
                          <?php if ($error_error_filename) { ?>
                          <span class="error"><?php echo $error_error_filename; ?></span>
                          <?php } ?></td>
                      </tr>
                      <tr style="display:none">
                        <td><?php echo $entry_google_analytics; ?></td>
                        <td><textarea name="config_google_analytics" cols="40" rows="5"><?php echo $config_google_analytics; ?></textarea></td>
                      </tr>
                    </table>
                  </div>

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
$('.sidebar-menu').find('#menu-system').addClass('active');
$('.sidebar-menu').find('#menu-setting').addClass('active');

//--></script>
<script type="text/javascript">
function image_upload(field, thumb) {
	$('#dialog').remove();

	$('#content').prepend('<div id="dialog" style="padding: 3px 0px 0px 0px;"><iframe src="index.php?route=common/filemanager&token=<?php echo $token; ?>&field=' + encodeURIComponent(field) + '" style="padding:0; margin: 0; display: block; width: 100%; height: 100%;" frameborder="no" scrolling="auto"></iframe></div>');

	$('#dialog').dialog({
		title: '<?php echo $text_image_manager; ?>',
		close: function (event, ui) {
			if ($('#' + field).val()) {
				$.ajax({
					url: 'index.php?route=common/filemanager/image&token=<?php echo $token; ?>&image=' + encodeURIComponent($('#' + field).val()),
					dataType: 'text',
					success: function(data) {
						$('#' + thumb).replaceWith('<img src="' + data + '" alt="" id="' + thumb + '" />');
					}
				});
			}
		},
		bgiframe: false,
		width: 800,
		height: 400,
		resizable: false,
		modal: false
	});
};
</script>
<script type="text/javascript"><!--
$('#form input').keydown(function(e) {
	if (e.keyCode == 13) {
		filter();
	}
});
//--></script>
<script type="text/javascript"><!--
$('#tabs a').tabs();
$('#timepicker1').timepicker();
//--></script>
<?php echo $footer; ?>
