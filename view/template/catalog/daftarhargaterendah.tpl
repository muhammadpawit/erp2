<?php echo $header; ?>
<!-- Modal -->
<div id="upex" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Upload Harga Terendah</h4>
      </div>
      <div class="modal-body">
        <form action="<?php echo $uploadex ?>" method="post" name="frmExcelImport" id="frmExcelImport" enctype="multipart/form-data">
            <div>
                <label>Pilih File</label> <input type="file" name="file"
                    id="file" accept=".xls,.xlsx">
                    <br>
                <button type="submit" id="submit" name="import"
                    class="btn btn-primary btn-submit">Import</button>
        
            </div>
        
        </form>
        <br>
        <!--Download format excel <a href="http://erp2.nissonindonesia.com/Format_Excel.xlsx">disini</a>-->
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal">Batalkan</button>
      </div>
    </div>
  </div>
</div>
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
            <h3 class="box-title">Daftar Harga Terendah</h3>
            <div class="button pull-right">
                  <!--<button type="button" class="btn btn-info" data-toggle="modal" data-target="#upex">Import Data</button>-->
            </div>
          </div>
          <div class="box-body">
            <div class="row">
              <div class="col-md-12">
                <?php
                if(isset($permission)){
                  if(!$permission){?>
                <div class="alert alert-danger alert-dismissible">
                  <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                  <h4><i class="icon fa fa-ban"></i> Alert!</h4>
                  Anda tidak diijinkan mengakses gudang yang dipilih.
                </div>
                <?php
              }}
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
                <table class="table table-stripped">
                    <thead>
                      <tr>
                        <th>Gudang</th>
                        <!--<th>Nama Produk</th>-->
                        <th></th>
                      </tr>
                    </thead>
                    <tbody>
                    <tr>
                      <td >
                        <select name="filter_gudang_id" class="form-control">
                          <option value="*">Pilih Gudang</option>
                          <?php foreach ($gudangs as $gudang) { ?>
                          <option value="<?php echo $gudang['gudang_id']; ?>" <?php echo $filter_gudang_id == $gudang['gudang_id'] ? 'selected' : ''; ?>><?php echo $gudang['nama']; ?></option>
                          <?php } ?>
                        </select>
                      </td>
                      <!--<td><input type="text" class="form-control" name="filter_product_name" value="<?php echo $filter_name; ?>" /></td>-->
                      <td ><a onclick="filter();" class="btn btn-info"><i class="fa fa-search"></i></a></td>
                    </tr>
                  </tbody>
                  </table>
              </div>
            </div>
            <div class="row">
              <?php if($filter_gudang_id!=null){?>
              <div class="col-md-4 col-xs-12">
                <div class="box box-info">
                  <div class="box-header with-border">
                    <h3 class="box-title">Periode Harga Terendah</h3>
                    <div class="box-tools pull-right">
                      <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                    </div>
                  </div>
                  <div class="box-body" style="">
                    <div class="table-responsive">
                        <table class="table no-margin" id="periodetbl">
                          <thead>
                          <tr>
                            <th>Gudang</th>
                            <th>Mulai berlaku</th>
                            <th></th>
                            <th></th>
                            <th></th>
                          </tr>
                          </thead>
                          <tbody>
                            <?php foreach($periode as $p){ ?>
                            <tr>
                              <td><?php echo $gudang_name; ?></td>
                              <td><?php echo date('d/m/Y',strtotime($p['date']))?></td>
                              <td><a href="index.php?route=catalog/productterendah&token=<?php echo $token; ?>&t=1&filter_gudang_id=<?php echo $filter_gudang_id ?>&date=<?php echo date('Y-m-d',strtotime($p['date'])) ?>" class="badge bg-red">Lihat</a></td>
                              <td><a href="index.php?route=catalog/productterendah&token=<?php echo $token; ?>&&print=1&t=1&filter_gudang_id=<?php echo $filter_gudang_id ?>&date=<?php echo date('Y-m-d',strtotime($p['date'])) ?>" class="badge bg-green">Export to Excel</a></td>
                              <td><a onclick="confirm('Apakah anda yakin ingin menghapus periode ini?') ? location = 'index.php?route=catalog/productterendah/deleteperiode&token=<?php echo $token; ?>&filter_gudang_id=<?php echo $filter_gudang_id ?>&date=<?php echo date('Y-m-d',strtotime($p['date'])) ?>' : false;" class="badge bg-red" title="Hapus"><i class="fa fa-trash"></i> Hapus</a></td>
                            </tr>
                            <?php } ?>
                          </tbody>
                        </table>
                    </div>
                  </div>
                  <div class="box-footer clearfix" style="">
                  <a href="index.php?route=catalog/productterendah/set&token=<?php echo $token; ?>&t=1&filter_gudang_id=<?php echo $filter_gudang_id ?>&date=<?php echo date('Y-m-d',strtotime($tglterakhir)) ?>" class="badge bg-blue">Set harga terendah pada daftar barang terakhir Gudang <?php echo $gudang_name; ?></a>
                  </div>
                </div>
              </div>
              <div class="col-md-8 col-xs-12">
                    <h4>Daftar produk terbaru yang belum di set harga terendahnya per tanggal <?php echo date('d-m-Y',strtotime($tglterakhir))?> gudang <?php echo $gudang_name; ?> </h4>
                    <form method="post" action="<?php echo $setbelum ?>">
                    <table class="table table-bordered">
                      <thead>
                        <tr>
                            <th>Tgl berlaku</>
                            <th>Gudang</>
                            <th>Produk</>
                            <th>Harga Terendah</>
                            <th>Poin</th>
                            <th></th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php $j=0; ?>
                        <?php foreach($pdr as $k){?>
                          <tr>
                            <input type="hidden" name="set[<?php echo $j?>][product_id]" value="<?php echo $k['product_id']?>">
                            <input type="hidden" name="set[<?php echo $j?>][gudang_id]" value="<?php echo $filter_gudang_id?>">
                            <td><?php echo date('d-m-Y',strtotime($tglterakhir))?></td>
                            <td><?php echo $gudang_name; ?></td>
                            <td><?php echo $k['name']; ?></td>
                            <td>
                              <input type="text" name="set[<?php echo $j?>][harga_terendah]" value="0">
                              <input type="hidden" name="set[<?php echo $j?>][date]" value="<?php echo date('Y-m-d',strtotime($tglterakhir))?>">
                              <input type="hidden" name="set[<?php echo $j?>][name]" value="<?php echo $k['name']?>">
                            </td>
                            <td><input type="text" name="set[<?php echo $j?>][poin]" value="0"></td>
                            <td><a onclick="confirm('Apakah anda yakin ingin menghapus produk ini dari gudang?') ? location = 'index.php?route=catalog/productterendah/deleteproductgudang&token=<?php echo $token; ?>&product_id=<?php echo $k['product_id']; ?>&filter_gudang_id=<?php echo $filter_gudang_id; ?>&date=<?php echo date('Y-m-d',strtotime($tglterakhir)) ?>' : false;" class="badge bg-red" title="Hapus dari Gudang"><i class="fa fa-trash"></i> Hapus</a></td>
                          </tr>
                          <?php $j++;?>
                        <?php } ?>
                      </tbody>
                      <tfoot>
                            <tr>
                              <td colspan="5"><button type="submit" class="btn btn-primary">Simpan</button></td>
                            </tr>
                          </tfoot>
                    </table>
                  </form>
              </div>
              <?php } ?>
              <?php if(isset($_REQUEST['t']) && $_REQUEST['t']!=null){?>
              <div class="col-md-12">
                <table class="table table-bordered" id="myTable">
                    <thead>
                      <tr>
                        <th class="right">Mulai berlaku</th>
                        <th class="left">Gudang</th>
                        <th class="left">Nama Produk</th>
                        <th class="right">Harga Terendah (termasuk PPn)</th>
                        <th class="right">Poin</th>
                        <th></th>
                      </tr>
                    </thead>
                    <tbody>

                      <?php if ($products) { ?>
                      <?php foreach ($products as $product) { ?>
                      <tr>
                        <td><?php echo $product['date']; ?></td>
                        <td class="left"><?php echo $product['gudang_id'];
                          ?></td>
                        <td class="left"><?php echo $product['name']; ?></td>
                        <td class="harga" id="<?php echo $product['id']; ?>"><?php echo $product['harga']; ?></td>
                        <td class="right poin" id="<?php echo $product['id']; ?>"> <?php echo $product['poin']; ?> </td>
                        <td class="right"><?php foreach ($product['action'] as $action) { ?>
                          <a class="badge bg-green" href="<?php echo $action['href']; ?>" target="_blank"><?php echo $action['text']; ?></a>
                          <?php } ?>
                          <a onclick="confirm('Apakah anda yakin ingin menghapus harga terendah ini?') ? location = 'index.php?route=catalog/productterendah/deletehargaterendah&token=<?php echo $token; ?>&id=<?php echo $product['id']; ?>&filter_gudang_id=<?php echo $filter_gudang_id; ?>&date=<?php echo $date; ?>&t=1' : false;" class="badge bg-red" title="Hapus Harga"><i class="fa fa-trash"></i> Hapus</a>
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
              </div>
              <?php } ?>
            </div>

          </div>
          <div class="box-footer">
            <div class="row">
              <div class="col-md-12">
                <div class="pull-right"><?php //echo count($products); ?></div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
</div>
</div>
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.20/css/jquery.dataTables.css">
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.js"></script>
<script>
$('.sidebar-menu').find('#menu-persediaan').addClass('active');
$('.sidebar-menu').find('#menu-persediaan-produkdagang').addClass('active');
$('.sidebar-menu').find('#menu-produk-daftarhargaterendah').addClass('active');
$(function(){
  $('.date').datepicker({dateFormat:'yy-mm-dd'});
  $(".select-ads").select2({
      theme:"bootstrap"
  });
  $('#myTable').DataTable({
    "lengthChange":false,
    "bPaginate":false,
    "bFilter":true,
  })
  $('#periodetbl').DataTable({
    "lengthChange":false,
    "bPaginate":true,
    "bFilter":false,
    "pageLength": 5,
    "ordering": false
  });
})
</script>
<script type="text/javascript"><!--
function filter() {
  url = 'index.php?route=catalog/productterendah&token=<?php echo $token; ?>';

  var filter_name = $('input[name=\'filter_product_name\']').val();

  if (filter_name) {
    url += '&filter_name=' + encodeURIComponent(filter_name);
  }

  var date = $('input[name=\'filter_tanggal\']').val();

  if (date) {
    url += '&date=' + encodeURIComponent(date);
  }

  var filter_gudang_id = $('select[name=\'filter_gudang_id\']').val();

  if (filter_gudang_id !="*") {
    url += '&filter_gudang_id=' + filter_gudang_id;
  }

  location = url;
}
//--></script>
<script type="text/javascript"><!--
$('input[name=\'filter_product_name\']').autocomplete({
  delay: 0,
  source: function(request, response) {
    $.ajax({
      url: 'index.php?route=catalog/product/autocomplete&token=<?php echo $token; ?>&filter_name=' + encodeURIComponent(request.term),
      dataType: 'json',
      success: function(json) {
        response($.map(json, function(item) {
          return {
            label: item.name,
            value: item.product_id,

          }
        }));
      }
    });
  },
  select: function(event, ui) {
    $('input[name=\'filter_product_name\']').val(ui.item['label']);

    //$('input[name=\'filter_product_id\']').attr('value', ui.item['value']);



    return false;
  },
  focus: function(event, ui) {
        return false;
    }
});

$(".harga").editable('index.php?route=catalog/productterendah/edittable&token=<?php echo $token; ?>&column=harga_terendah&gudang_id=<?php echo $filter_gudang_id?>&date=<?php echo $date?>', {
        submit : 'Simpan',
        cancel : 'Cancel',
        cssclass : 'custom-class',
        cancelcssclass : 'badge bg-red',
        submitcssclass : 'badge bg-green',
        formid : 'abc-123',
        id   : 'id',
        name : 'harga_terendah',
});
$(".poin").editable('index.php?route=catalog/productterendah/edittable&token=<?php echo $token; ?>&column=poin&gudang_id=<?php echo $filter_gudang_id?>&date=<?php echo $date?>', {
        submit : 'Simpan',
        cancel : 'Cancel',
        cssclass : 'custom-class',
        cancelcssclass : 'badge bg-red',
        submitcssclass : 'badge bg-green',
        formid : 'abc-123',
        id   : 'id',
        name : 'harga_terendah',
});
//--></script>
<?php echo $footer; ?>
