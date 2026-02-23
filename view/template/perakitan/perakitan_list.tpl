<?php echo $header; ?>
<div class="content-wrapper">
  <section class="content-header">
    <h1>

    </h1>

  </section>

<!-- Trigger the modal with a button -->
<!-- <button type="button" class="btn btn-info btn-lg" data-toggle="modal" data-target="#myModal">Open Modal</button> -->

  <section class="content">
    <div class="row">
      <div class="col-md-12">
        <div class="box">
          <div class="box-header with-border">
            <h3 class="box-title">Perakitan</h3>
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
                <?php //if($this->user->getUsername()=="pawit"){ ?>
                  <div class="col-md-12">
                  <table class="table">
                    <tr>
                      <td>Gudang</td>
                      <td>Nama Produk</td>
                      <td></td>
                    </tr>
                    <tr>
                      <td>
                        <select name="filter_gudang_id" class="form-control">
                        <option value="*">Semua</option>
                        <?php
                              foreach($gudangs as $g){
                                ?>
                                  <option value="<?php echo $g['gudang_id']; ?>" <?php echo ($filter_gudang_id==$g['gudang_id'])?'selected':'' ?>><?php echo $g['nama']; ?></option>
                                <?php
                              }
                              ?>
                      </select>
                      </td>
                      <td><input type="text" name="filter_name" class="form-control" value="<?php echo $filter_name ?>"></td>
                      <td><button class="btn btn-primary" onclick="filter()">Filter</button></td>
                    </tr>
                  </table>
                  </div>
                  <!-- <div class="col-md-4">
                    <div class="form-group">
                      <label>Gudang</label>
                      <select name="filter_gudang_id" class="form-control">
                        <option value=""></option>
                        <?php
                              foreach($gudangs as $g){
                                ?>
                                  <option value="<?php echo $g['gudang_id']; ?>"><?php echo $g['nama']; ?></option>
                                <?php
                              }
                              ?>
                      </select>
                    </div>
                  </div>
                  <div class="col-md-4">
                    <div class="form-group">
                      <br><br>
                      <button class="btn btn-primary" onclick="filter()">Filter</button>
                    </div>
                  </div> -->
                <?php //} ?>
            </div>
            <div class="row">
              <div class="col-md-12">
                <form action="<?php echo $delete; ?>" method="post" enctype="multipart/form-data" id="form">
                  <table class="table table-bordered">
                    <thead>
                      <tr>
                        <!-- <td width="1" style="text-align: center;"><input type="checkbox" onclick="$('input[name*=\'selected\']').attr('checked', this.checked);" /></td> -->
                        <th class="left">No Dokumen</th>
                        <th class="left">Gudang</th>
                        <th class="left">Tgl</th>
                        <th class="left">Tgl Proses</th>
                        <th class="left">Product ID</th>
                        <th class="left">Nama Produk</th>
                        <th class="right">Quantity</th>
                        <th class="right">Status</th>
                        <th class="right"></th>
                      </tr>
                    </thead>
                    <tbody>

                      <?php if ($products) { ?>
                      <?php foreach ($products as $product) { ?>
                      <tr>
                        <!-- <td style="text-align: center;">
                    		<?php echo $no++ ?>
                    		</td> -->
                        <td class="left"><?php echo $product['no_dokumen']; ?></td>
                        <td class="left"><?php echo $product['namagudang']; ?></td>
                        <td class="left"><?php echo date('d F Y',strtotime($product['tanggal_perakitan'])); ?></td>
                        <td class="left"><?php echo ($product['tgl_proses']==null)?'':date('d F Y',strtotime($product['tgl_proses'])); ?></td>
                        <td class="left"><?php echo $product['product_id']; ?></td>
                        <td class="left"><?php echo $product['name']; ?></td>
                        <td class="right"><?php if ($product['quantity'] <= 0) { ?>
                          <span style="color: #FF0000;"><?php echo $product['quantity']; ?></span>
                          <?php } elseif ($product['quantity'] <= 5) { ?>
                          <span style="color: #FFA500;"><?php echo $product['quantity']; ?></span>
                          <?php } else { ?>
                          <span style="color: #008000;"><?php echo $product['quantity']; ?></span>
                          <?php } ?> <?php echo $product['satuan']; ?></td>
                        <td class="right"><?php echo $product['status'] ?></td>
                        <td class="right"><?php foreach ($product['action'] as $action) { ?>
                           <a href="<?php echo $action['href']; ?>" class="label label-primary"><?php echo $action['text']; ?></a>&nbsp;
                          <?php } ?>
                          <?php if($product['stat']==0){?>
                            <a onclick="proses('<?php echo $product['id']?>','<?php echo $product['gudang_id']?>')" class="label label-primary"  data-toggle="modal" data-target="#myModal">Proses</a>
                          <?php }?>
                          <?php if($product['stat']==1 && $batalkanproses==1){?>
                          <a onclick="batalkanproses('<?php echo $product['id']?>','<?php echo $product['gudang_id']?>')" class="label label-primary"  data-toggle="modal" data-target="#myModal">Batalkan Proses</a>
                        <?php } ?>
                        </td>
                      </tr>
                      <?php } ?>
                      <?php } else { ?>
                      <tr>
                        <td class="center" colspan="9">Data tidak ditemukan</td>
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
<!-- Modal -->
<div id="myModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <!-- <button type="button" class="close" data-dismiss="modal">&times;</button> -->
        <h4 class="modal-title">Apakah anda yakin akan memproses perakitan ?</h4>
      </div>
      <div class="modal-body">
        
      </div>
      <div class="modal-footer">
      </div>
    </div>
  </div>
</div>
<script>
$('.sidebar-menu').find('#menu-persediaan').addClass('active');
$('.sidebar-menu').find('#menu-persediaan-produkdagang').addClass('active');
$('.sidebar-menu').find('#menu-perakitan').addClass('active');
</script>
<script type="text/javascript">
  function refresh(){
    //location.reload();
  }


function batalkanproses(id,gudang_id){
    $.ajax({
      url: 'index.php?route=perakitan/perakitan/modaldetailbatalkan&token=<?php echo $token; ?>&id=' +  encodeURIComponent(id),
      //dataType: 'json',
      success: function(json) {
        $('.modal-body').html(json);
      }
    });
}

function proses(id,gudang_id){
    $.ajax({
      url: 'index.php?route=perakitan/perakitan/modaldetail&token=<?php echo $token; ?>&id=' +  encodeURIComponent(id),
      //dataType: 'json',
      success: function(json) {
        $('.modal-body').html(json);
      }
    });
}
function detail(id,gudang_id){
  alert(gudang_id);
  //$("#id").html(id);
    $.ajax({
      url: 'index.php?route=perakitan/perakitan/perdeetail&token=<?php echo $token; ?>&id=' +  encodeURIComponent(id),
      //dataType: 'json',
      success: function(json) {
        $('#tblp tbody').html(json);
        
      }
    });
}
function filter() {
	url = 'index.php?route=perakitan/perakitan&token=<?php echo $token; ?>';

	var filter_name = $('input[name=\'filter_name\']').val();

	if (filter_name) {
		url += '&filter_name=' + encodeURIComponent(filter_name);
	}

  var filter_gudang_id = $('select[name=\'filter_gudang_id\']').val();

	if (filter_gudang_id != '*') {
		url += '&filter_gudang_id=' + encodeURIComponent(filter_gudang_id);
	}

	location = url;
}
</script>
<script type="text/javascript"><!--
$('#form input').keydown(function(e) {
	if (e.keyCode == 13) {
		filter();
	}
});
//--></script>
<script type="text/javascript"><!--
$('input[name=\'filter_name\']').autocomplete({
	delay: 0,
	source: function(request, response) {
		$.ajax({
			url: 'index.php?route=catalog/product/autocomplete&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request.term),
			dataType: 'json',
			success: function(json) {
				response($.map(json, function(item) {
					return {
						label: item.name,
						value: item.product_id
					}
				}));
			}
		});
	},
	select: function(event, ui) {
		$('input[name=\'filter_name\']').val(ui.item.label);

		return false;
	},
	focus: function(event, ui) {
      	return false;
   	}
});

$('input[name=\'filter_model\']').autocomplete({
	delay: 0,
	source: function(request, response) {
		$.ajax({
			url: 'index.php?route=catalog/product/autocomplete&token=<?php echo $token; ?>&filter_model=' +  encodeURIComponent(request.term),
			dataType: 'json',
			success: function(json) {
				response($.map(json, function(item) {
					return {
						label: item.model,
						value: item.product_id
					}
				}));
			}
		});
	},
	select: function(event, ui) {
		$('input[name=\'filter_model\']').val(ui.item.label);

		return false;
	},
	focus: function(event, ui) {
      	return false;
   	}
});
//--></script>
<?php echo $footer; ?>
