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
            <h3 class="box-title">Pembelian Kredit</h3>
            <div class="button pull-right">
                <a onclick="simpan()"><button type="button" class="btn btn-primary">Simpan</button></a>
                  <a href="<?php echo $cancel; ?>"><button type="button" class="btn btn-danger">Kembali</button></a>
								</div>
          </div>
          <div class="box-body">

            <div class="row">
              <div class="col-md-12">
                <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
                <table class="table">
                  <tr>
                      <td>Nomor PO:</td>
                      <td><?php echo $permintaan['no_po'] ?>
                          <input type="hidden" class="form-control" name="id" value="<?php echo $permintaan['id']; ?>" >
                      </td>
                  </tr>
                   <tr>
                      <td>Vendor:</td>
                      <td><?php echo $permintaan['name'] ?></td>
                  </tr>

                 <tr>
                    <td>Jenis Barang:</td>
                    <td><?php echo $permintaan['jenis_barang'] == 1?'Bahan Baku':($permintaan['jenis_barang'] == 2?'Produk Dagang':($permintaan['jenis_barang'] == 3?'ATK':'Aset')); ?></td>
                </tr>
                <tr>
                   <td>Sub Total:</td>
                   <td><?php echo $this->currency->format($permintaan['sub_total']) ?></td>
                  </tr>
                  <tr>
                     <td>Diskon:</td>
                     <td><?php echo $this->currency->format($permintaan['diskon']) ?></td>
                  </tr>
                <tr>
                   <td>Biaya:</td>
                   <td><?php echo $this->currency->format($permintaan['biaya']) ?></td>
                  </tr>
                  <tr>
                     <td>Pajak:</td>
                     <td><?php echo $this->currency->format($permintaan['pajak']) ?></td>
                 </tr>
                 <tr>
                    <td>Total Pembelian:</td>
                    <td><?php echo $this->currency->format($permintaan['total_pembelian']) ?></td>
                </tr>

            </table>

            <table class="table" id="list-biaya">
                <thead>
                  <th>Nama Biaya</th>
                  <th>Total</th>
                </thead>
                <tbody >
                  <?php
                  $row=0;
                  foreach($biayas as $p){
                  ?>
                  <tr id="biaya-row<?php echo $row; ?>">
                    <td><?php echo $p['name']; ?>
                      <input type="hidden" class="form-control" name="biaya[<?php echo $row; ?>][jenisbiaya_id]" value="<?php echo $p['jenisbiaya_id']; ?>" >
                    </td>
                    <td><input type="text" class="form-control" name="biaya[<?php echo $row; ?>][total]" value="<?php echo $p['total']; ?>" >
                      <input type="hidden" class="form-control" name="biaya[<?php echo $row; ?>][id]" value="<?php echo $p['id']; ?>" >

                    </td>
                  </tr>
                  <?php
                  $row++;
                  }
                  ?>
                </tbody>
                <tfoot>
                  <tr>
                  <tr>
                    <td ></td>
                    <td class="left"><a onclick="addBiaya();" class="btn btn-success">Tambah Biaya</a>  </td>
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
$('.sidebar-menu').find('#menu-pembelian').addClass('active');
$('.sidebar-menu').find('#menu-pembelian-kredit').addClass('active');

var biaya_row=<?php echo $row; ?>;
function addBiaya(){

	html = '  <tr id="biaya-row' + biaya_row + '">';
  html += '    <td class="left"><select style="width:300px" data-id="'+biaya_row+'" name="biaya[' + biaya_row + '][jenisbiaya_id]" class="biaya form-control"></select></td>';
  html += '    <td class="right"><input class="form-control"  type="text" name="biaya[' + biaya_row + '][total]" value="0" onchange="hitungtotal()" /></td>';


  html += '    <td class="right"><a class="btn btn-warning" onclick="$(\'#biaya-row'+biaya_row+'\').remove()" class="button">Hapus</a> <span></span></td>';

	html += '  </tr>';


	$('#list-biaya tbody').append(html);

  $(function(){
    $(".biaya").select2({
        ajax: {
        url:"index.php?route=catalog/jenisbiaya/autocomplete&token=<?php echo $this->request->get['token']; ?>",
          dataType: 'json',
        data: function (params) {
          return {
            q: params.term,
          //  statustabung:$(".statustabung").val(),
          //  kategori:$(".jenisorder").val()
            //customer_group_id:$('input[name=\'customer_group_id\']').val()

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
      })
  })
	biaya_row++;

}
$(document).ready(function() {
	$('.date').datepicker({dateFormat: 'yy-mm-dd'});
})
var klik=1;
function simpan(){
  y = 0;
  error=false;
  while(y < biaya_row){
    nilaibiaya=$("input[name='biaya["+y+"][total]']").val();

    if(!$.isNumeric( Number(nilaibiaya) )){
      error=true;
        alert("Nominal biaya harus berupa angka.");
    }
    y++;
  }
  if(!error){
    if(klik == 1){
      klik++;
      $("#form").submit();
    }
  }else{
    klik=1;
  }
}

</script>

<?php echo $footer; ?>
