<?php echo $header; ?>
<div class="content-wrapper">
  <section class="content-header">
    <h1> </h1>
  </section>
  <section class="content">
    <div class="row">
      <div class="col-md-12">
        <div class="box">
          <div class="box-header with-border">
            <h3 class="box-title">Laporan Bank <?php echo $bank['name']; ?><br>
              <small>Saldo Awal: <?php echo $sblm ?></small><br>
              <small>Saldo Masuk: <?php echo $totalmasuk ?></small><br>
              <small>Saldo Keluar: <?php echo $totalkeluar ?></small><br>
              <small>Total Saldo: <?php echo $totalsaldo; ?></small><br>
            </h3>
            <div class="button pull-right">
								<a href="<?php echo $exportexcel; ?>" ><button type="button" class="btn btn-success">Export to Excel</button></a>
                <a href="<?php echo $cancel; ?>" ><button type="button" class="btn btn-danger">Kembali</button></a>
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
              </div>
            </div>

            <div class="row">
              <div class="col-md-3">
                <label>Tanggal Awal</label>
                <input type="text" id="tanggal" name="filter_tgl_awal" class="form-control" value="<?php echo $filter_tgl_awal;?>">
              </div>
              <div class="col-md-3">
                <label>Tanggal Akhir</label>
                <input type="text" id="tanggalakhir" name="filter_tgl_akhir" class="form-control" value="<?php echo $filter_tgl_akhir;?>">
              </div>
              <div class="col-md-3">
                <label>Type</label>
                            <select name="filter_jenis" class="select-ads">
                              <option value="*">Tampil Semua</urutkan>
                              <?php
                              /*foreach($jeniss as $j){
                              ?>
                              <option value="<?php echo $j['id']; ?>" <?php echo $filter_jenis == $j['id']?'selected':'';?>><?php echo $j['type_name']; ?></option>
                              <?php
                              }*/
                              ?>
                            </select>
              </div>
              <div class="col-md-3">
                <label>Saldo</label>
                <select name="filter_saldo" class="form-control">
                  <option value="*">Semua</option>
                  <option value="1" <?php echo ($filter_saldo==1)?'selected':'';?>>Saldo Masuk</option>
                  <option value="2" <?php echo ($filter_saldo==2)?'selected':'';?>>Saldo Keluar</option>
                </select>
              </div>
            </div>
            <div class="row">
              <div class="col-md-4">
                <label>Ref</label>
                <input type="text" name="filter_ref" value="<?php echo $filter_ref?>" class="form-control">
              </div>
              <div class="col-md-4">
                <label>Keterangan</label>
                <input type="text" name="filter_keterangan" value="<?php echo $filter_keterangan ?>" class="form-control">
              </div>
              <div class="col-md-4">
                <label></label><br>
                <a onclick="filter();" class="btn btn-info">Filter</a>
              </div>
            </div><hr>
            <div class="row">
              <div class="col-md-12">
                <table class="table table-bordered">
                    <thead>
                      <tr>
                        <th class="left">Tgl Transaksi</th>
                        <th class="left">Tgl Input</th>
                        <th class="left">Referensi</th>
                        <th class="left">Keterangan</th>
                        <th class="left">Saldo Masuk</th>
                        <th class="left">Saldo Keluar</th>
                        <!--<th class="left">Saldo akhir </th>-->
                        <th class="left">Saldo Akhir </th>
                      </tr>
                    </thead>
                    <tbody>

                      <?php if ($kartustoks) { ?>
                      <?php foreach (array_reverse($kartustoks) as $product) { ?>
                      <tr>
                        <td class="left"><?php echo $product['date_trans'];?> </td>
                        <td class="left"><?php echo $product['date_added']; ?></td>
                        <td class="left">
                        <?php if($product['invoice']>=3000) { ?>
                        <a onclick="detail('<?php echo $product['invoice'] ?>')" data-toggle="modal" data-target="#jurnal"><?php echo empty($product['linkterkait'])?$product['invoice']:$product['linkterkait']; ?></a>
                        <?php }else{ ?>
                        <?php echo $product['invoice']; ?>
                        <?php } ?>
                        </td>
                        <td class="left"><?php echo $product['ket']; ?></td>
                        <td class="left"><?php echo $product['saldo_masuk']; ?></td>
                        <td class="left"><?php echo $product['saldo_keluar']; ?></td>
                        <!--<td><?php //echo $product['saldo_akhir'];?></td>-->
                        <td class="left"><?php echo $product['sisa'];?></td>
                      </tr>
                      <?php } ?>
                      <?php } else { ?>
                      <tr>
                        <td class="center" colspan="9">Data tidak ditemukan</td>
                      </tr>
                      <?php } ?>
                    </tbody>
                    <tfoot>
                      <tr>
                        <td colspan="4"><b>Total</b></td>
                        <td><b><?php echo $totalmasuk ?></b></td>
                        <td><b><?php echo $totalkeluar ?></b></td>
                        <td><b><?php echo $saldo_akhir ?></b></td>
                      </tr>
                      <tr>
                      <!--  <td colspan="4"><b>Total Keseluruhan</b></td>
                        <td colspan="2" align="center"><?php echo $this->currency->format($totalsaldomasuk-$totalsaldokeluar) ?></td>
                        <td></td> -->
                      </tr>
                    </tfoot>
                  </table>

              </div>
            </div>

          </div>
          <div class="box-footer">
            <div class="row">
              <div class="col-md-12">
                <div class="pull-right"><?php //echo $pagination; ?></div>
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
$('.sidebar-menu').find('#menu-keuangan').addClass('active');
$('.sidebar-menu').find('#menu-bank').addClass('active');

$(function(){
  $(".select-ads").select2({


      theme:"bootstrap"
    });
})
</script>
<script type="text/javascript"><!--
function filter() {
	url = "<?php echo htmlspecialchars_decode($url); ?>";
  //url=decodeURIComponent(urls);


	var filter_tgl_awal= $('input[name=\'filter_tgl_awal\']').val();

	if (filter_tgl_awal) {
		url += '&filter_tgl_awal=' + encodeURIComponent(filter_tgl_awal);
	}

  var filter_tgl_akhir= $('input[name=\'filter_tgl_akhir\']').val();

	if (filter_tgl_akhir) {
		url += '&filter_tgl_akhir=' + encodeURIComponent(filter_tgl_akhir);
	}

 var filter_ref= $('input[name=\'filter_ref\']').val();

  if (filter_ref) {
    url += '&filter_ref=' + encodeURIComponent(filter_ref);
  }

 var filter_keterangan= $('input[name=\'filter_keterangan\']').val();

  if (filter_keterangan) {
    url += '&filter_keterangan=' + encodeURIComponent(filter_keterangan);
  }    

  var filter_jenis = $('select[name=\'filter_jenis\']').val();

	if (filter_jenis != '*') {
		url += '&filter_jenis=' + filter_jenis;
	}

  var filter_saldo = $('select[name=\'filter_saldo\']').val();

  if (filter_saldo != '*') {
    url += '&filter_saldo=' + filter_saldo;
  }


  //alert(filter_tanggal_awal);
	location = url;
}
//--></script>
<script>
$(function(){
  $('#tanggal').datepicker({dateFormat: 'yy-mm-dd'});
  $('#tanggalakhir').datepicker({dateFormat: 'yy-mm-dd'});
})
function detail(id){
    $.ajax({
      url: 'index.php?route=keuangan/bank/jurnal&token=<?php echo $token; ?>&id=' +  encodeURIComponent(id),
      //dataType: 'json',
      success: function(json) {
        $('.modal-body').html(json);
      }
    });/**/
}
</script>
<?php echo $footer; ?>
