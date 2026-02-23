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
            <h3 class="box-title">Konversi Satuan</h3>
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
                        <th class="left">Satuan</th>
                        <th class="right">Nilai Konversi</th>

                        <th></th>
                      </tr>
                    </thead>
                    <?php $option_row = 0; ?>
                    <?php foreach ($product_options as $po) { ?>
                    <tbody id="option-row<?php echo $option_row; ?>">
                      <tr>
                        <td><?php echo $po['name']; ?>
                            </td>
                        <td><?php echo $po['nilai']; ?></td>
                        <td>

                              <a onclick="hapusoption(<?php echo $po['id']; ?>)" class="btn btn-warning">Hapus</a>

                        </td>

                      </tr>
                    </tbody>
                    <?php $option_row++; ?>
                    <?php } ?>
                    <tfoot>
                      <tr>
                        <td colspan="2"></td>
                        <td class="left"><a onclick="addOption();" class="btn btn-success">Tambah Konversi</a></td>
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
$('.sidebar-menu').find('#menu-persediaan').addClass('active');

</script>
<script>

var option_row = <?php echo $option_row; ?>;

function addOption() {
	html  = '<tbody id="option-row' + option_row + '">';
	html += '  <tr>';
    html += '    <td class="left"><select style="width:200px" class="select-ads" name="product_options[' + option_row + '][satuan]">';
    <?php
    foreach($options as $opt){
    ?>
    html += '      <option value="<?php echo $opt['id']; ?>"><?php echo $opt['name']; ?></option>';

    <?php
    }
    ?>
    html += '    </select></td>';
    html += '    <td class="right"><input type="text" class="form-control" name="product_options[' + option_row + '][nilai]" value="0" /></td>';
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
  var r=confirm("Anda yakin akan menghapus nilai konversi? Nilai konversi yang sudah dihapus tidak dapat dikembalikan lagi.");
  if(r){
    $.ajax({
			url: "index.php?route=catalog/bahanbaku/hapusoption&token=<?php echo $token; ?>&id=<?php echo $this->request->get['id']; ?>&product_option_id="+option_id,
			success: function(json) {
        //  alert(JSON.stringify(json));
				    if(json['error']){
              alert(json['error']);
            }else{
              alert('Nilai konversi berhasil dihapus');
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
