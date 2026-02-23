<?php echo $header; ?>
<div class="content-wrapper" id="content">
  <section class="content-header">
    <h1>

    </h1>

  </section>

  <section class="content" >
    <div class="row">
      <div class="col-md-12">
        <div class="box">
          <div class="box-header with-border">
            <h3 class="box-title">Ukuran/Warna Produk</h3>
            <div class="button pull-right">
                <a onclick="$('#form').submit();" ><button type="button" class="btn btn-primary">Simpan</button></a>
									<a href="<?php echo $cancel; ?>"><button type="button" class="btn btn-danger">Kembali</button></a>

								</div>
          </div>
          <div class="box-body">

            <div class="row">
              <div class="col-md-12">
                <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
                  <table id="options" class="table">
                    <thead>
                      <tr>
                        <th class="left">Ukuran/Warna</th>
                        <th class="right">Quantity</th>

                        <th></th>
                      </tr>
                    </thead>
                    <?php $option_row = 0; ?>
                    <?php foreach ($product_options as $po) { ?>
                    <tbody id="option-row<?php echo $option_row; ?>">
                      <tr>
                        <td><?php echo $po['name']; ?>
                            </td>
                        <td><?php echo $po['quantity']; ?></td>
                        <td>
                            <?php
                            if($po['quantity'] == 0){
                            ?>
                              <a onclick="hapusoption(<?php echo $po['product_option_id']; ?>)" class="btn btn-warning">Hapus</a>
                            <?php
                            }
                            ?>
                        </td>

                      </tr>
                    </tbody>
                    <?php $option_row++; ?>
                    <?php } ?>
                    <tfoot>
                      <tr>
                        <td colspan="2"></td>
                        <td class="left"><a onclick="addOption();" class="btn btn-success">Tambah Ukuran</a></td>
                      </tr>
                    </tfoot>
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
$('.sidebar-menu').find('#menu-produk').addClass('active');
$('.sidebar-menu').find('#menu-daftar-produk').addClass('active');
</script>
<script>

var option_row = <?php echo $option_row; ?>;

function addOption() {
	html  = '<tbody id="option-row' + option_row + '">';
	html += '  <tr>';
    html += '    <td class="left"><select class="select-ads" name="product_options[' + option_row + '][product_options_id]">';
    <?php
    foreach($options as $opt){
    ?>
    html += '      <option value="<?php echo $opt['product_options_id']; ?>"><?php echo $opt['name']; ?></option>';

    <?php
    }
    ?>
    html += '    </select></td>';
    html += '    <td class="right"><input type="text" class="form-control" name="product_options[' + option_row + '][quantity]" value="0" readonly size="2" /></td>';
  html += '    <td class="left"><a onclick="$(\'#option-row' + option_row + '\').remove();" class="btn btn-warning">Hapus</a></td>';
	html += '  </tr>';
    html += '</tbody>';

	$('#options tfoot').before(html);

  $(function(){
    $(".select-ads").select2({


        theme:"bootstrap"
      });
  })

	option_row++;
}

function hapusoption(option_id){
  var r=confirm("Anda yakin akan menghapus ukuran/warna? Ukuran/warna yang sudah dihapus tidak dapat dikembalikan lagi.");
  if(r){
    $.ajax({
			url: "index.php?route=catalog/product/hapusoption&token=<?php echo $token; ?>&product_id=<?php echo $this->request->get['product_id']; ?>&product_option_id="+option_id,
			success: function(json) {
        //  alert(JSON.stringify(json));
				    if(json['error']){
              alert(json['error']);
            }else{
              alert('Ukuran/Warna berhasil dihapus');
              location.reload();
            }
      }

				});
		}


}

$(function(){
  $(".select-ads").select2({


      theme:"bootstrap"
    });
})
</script>
<?php echo $footer; ?>
