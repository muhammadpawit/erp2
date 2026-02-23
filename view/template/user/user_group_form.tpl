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
            <h3 class="box-title">User Group</h3>
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
                      <td><span class="required">*</span> <?php echo $entry_name; ?></td>
                      <td><input type="text" name="name" value="<?php echo $name; ?>" />
                        <?php if ($error_name) { ?>
                        <span class="error"><?php echo $error_name; ?></span>
                        <?php  } ?></td>
                    </tr>
                  </table>
                  <table class="table table-bordered" >
                  	<thead>
                  		<tr>
                  			<td class="center" style="width:20%">List Feature</td>
                  			<td class="center" style="width:20%">Akses</td>
                  			<td class="center" style="width:20%">Modify</td>

                  		</tr>
                  	<thead>
                  	<tbody>
                  		<tr>
                           	<td></td>
                           	<td><a onclick="$('.access').attr('checked', true);"><?php echo $text_select_all; ?></a> / <a onclick="$('.access').attr('checked', false);"><?php echo $text_unselect_all; ?></a><br></td>
                           	<td><a onclick="$('.modify').attr('checked', true);"><?php echo $text_select_all; ?></a> / <a onclick="$('.modify').attr('checked', false);"><?php echo $text_unselect_all; ?></a> <br></td>
                           </tr>
                  		<?php
                  		$i=0;
                  		foreach ($menus as $permission) {
                  		if($permission['grouping'] != $menus[$i-1]['grouping'] & $permission['sort_order'] != 99){
                  		?>
                  		<tr>
                  		<td colspan="3" class="center" style="background:#ccc"><b><?php echo strToUpper($permission['grouping'])?></b></td>
                  		</tr>
                  		<?php

                  		}
                  		if($permission['sort_order']==99 & $permission['sort_order'] != $menus[$i-1]['sort_order']){
                  		?>
                  		<tr>
                  		<td colspan="3" class="center" style="background:#ccc"><b>Default Opencart</b></td>
                  		</tr>
                  		<?php

                  		}

                  		?>

                          <tr>
                          	<td> <?php echo $permission['sort_order']!=99?$permission['nama']:$permission['url']; ?></td>
                          	<td>
          		              <?php if (in_array($permission['url'], $access)) { ?>
          		              <input type="checkbox" class="access" name="permission[access][]" value="<?php echo $permission['url']; ?>" checked="checked" />

          		              <?php } else { ?>
          		              <input type="checkbox" class="access" name="permission[access][]" value="<?php echo $permission['url']; ?>" />

                            <?php } ?>
                            	</td>
                            	<td>
                            		<?php if (in_array($permission['url'], $modify)) { ?>
          		              <input type="checkbox" class="modify" name="permission[modify][]" value="<?php echo $permission['url']; ?>" checked="checked" />

          		              <?php } else { ?>
          		              <input type="checkbox" class="modify" name="permission[modify][]" value="<?php echo $permission['url']; ?>" />

                            <?php } ?>
                            	</td>
                          </tr>
                          <?php
                          	$i++;
                           } ?>

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
$('.sidebar-menu').find('#menu-user-group').addClass('active');

//--></script>
<script type="text/javascript"><!--
$('#form input').keydown(function(e) {
	if (e.keyCode == 13) {
		filter();
	}
});
//--></script>

<?php echo $footer; ?>
