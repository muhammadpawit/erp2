<?php echo $header; ?>
<div id="content">
  <div class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
    <?php } ?>
  </div>
  <?php if ($error_warning) { ?>
  <div class="warning"><?php echo $error_warning; ?></div>
  <?php } ?>
  <?php if ($success) { ?>
  <div class="success"><?php echo $success; ?></div>
  <?php } ?>
  <div class="box">
    <div class="heading">
      <h1> Master Data Jabatan</h1>
        <div class="buttons"><a onclick="location = '<?php echo $insert; ?>'" class="button">Insert</a><a onclick="$('#form').submit();" class="button">Delete</a></div>
    </div>
    <div class="content">	
	<table class="form">
        <tr class="filter">
             <td colspan="2">Nama Jabatan: <input type="text" name="filter_name" value="<?php echo $filter_name; ?>" /></td>
              
		<td align="right"><a onclick="filter();" class="button">Filter</a></td>
            </tr>
      </table>
      <form action="<?php echo $delete; ?>" method="post" enctype="multipart/form-data" id="form">
        <table class="list">
          <thead>
            <tr>
              <td width="1" style="text-align: center;"><input type="checkbox" onclick="$('input[name*=\'selected\']').attr('checked', this.checked);" /></td>
              <td class="left">Nama Jabatan</td>
              <td class="left">Tunjangan</td>
             
              
              <td class="right">Action</td>
            </tr>
          </thead>
          <tbody>
            
            <?php if ($jabatans) { ?>
            <?php foreach ($jabatans as $product) { ?>
            <tr>
              <td style="text-align: center;">
		
		<?php if ($product['selected']) { ?>
                <input type="checkbox" name="selected[]" value="<?php echo $product['jabatan_id']; ?>" checked="checked" />
                <?php } else { ?>
                <input type="checkbox" name="selected[]" value="<?php echo $product['jabatan_id']; ?>" />
                <?php } ?>
		</td>
              
              <td class="left"><?php echo $product['nama']; ?></td>
              <td class="left"><?php echo $product['tunjangan']; ?></td>
              
              <td class="right"><?php foreach ($product['action'] as $action) { ?>
                [ <a href="<?php echo $action['href']; ?>"><?php echo $action['text']; ?></a> ]
                <?php } ?></td>
            </tr>
            <?php } ?>
            <?php } else { ?>
            <tr>
              <td class="center" colspan="4">No Results.</td>
            </tr>
            <?php } ?>
          </tbody>
        </table>
      </form>
      <div class="pagination"><?php echo $pagination; ?></div>
	
    </div>
  </div>
</div>
<script type="text/javascript"><!--
function filter() {
	url = 'index.php?route=kepegawaian/jabatan&token=<?php echo $token; ?>';
	
	var filter_name = $('input[name=\'filter_name\']').attr('value');
	
	if (filter_name) {
		url += '&filter_name=' + encodeURIComponent(filter_name);
	}
	
	
	

	location = url;
}
//--></script> 
<script type="text/javascript"><!--
$(document).ready(function() {
	$('#tgl_awal').datepicker({dateFormat: 'yy-mm-dd'});
	$('#tgl_selesai').datepicker({dateFormat: 'yy-mm-dd'});
	
	
});
//--></script> 
<script type="text/javascript"><!--
$('#form input').keydown(function(e) {
	if (e.keyCode == 13) {
		filter();
	}
});
//--></script> 

<?php echo $footer; ?>
