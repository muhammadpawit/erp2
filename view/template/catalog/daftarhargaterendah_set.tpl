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
            <h3 class="box-title">Set Daftar Harga Terendah.</h3>
            <div class="button pull-right">
                  <button type="button" class="btn btn-info" data-toggle="modal" data-target="#upex">Import Data</button>
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
                          <option value="1" <?php echo $_REQUEST['filter_gudang_id']==1?'selected':''?>>Tangerang</option>
                          <option value="3" <?php echo $_REQUEST['filter_gudang_id']==3?'selected':''?>>Surabaya</option>
                          <option value="70" <?php echo $_REQUEST['filter_gudang_id']==70?'selected':''?>>Hanson (Surabaya)</option>
                        </select>
                      </td>
                      <!--<td><input type="text" class="form-control" name="filter_product_name" value="<?php echo $filter_name; ?>" /></td>-->
                      <td ><a onclick="filter();" class="btn btn-info">Filter</a></td>
                    </tr>
                  </tbody>
                  </table>
              </div>
            </div>
            <div class="row">
              <?php if($filter_gudang_id!=null){?>
              <div class="col-md-6 col-xs-12">
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
                        </tr>
                        </thead>
                        <tbody>
                          <?php foreach($periode as $p){ ?>
                          <tr>
                            <td><?php echo $filter_gudang_id==1?'Tangerang':''; ?> <?php echo $filter_gudang_id==3?'Surabaya':''; ?> <?php echo $filter_gudang_id==70?'Hanson (Surabaya)':''; ?></td>
                            <td><?php echo date('d F Y',strtotime($p['date']))?></td>
                            <td><a href="index.php?route=catalog/productterendah&token=<?php echo $token; ?>&t=1&filter_gudang_id=<?php echo $filter_gudang_id ?>&date=<?php echo date('Y-m-d',strtotime($p['date'])) ?>" class="badge bg-red">Lihat</a></td>
                            <td><a href="index.php?route=catalog/productterendah&token=<?php echo $token; ?>&&print=1&t=1&filter_gudang_id=<?php echo $filter_gudang_id ?>&date=<?php echo date('Y-m-d',strtotime($p['date'])) ?>" class="badge bg-green">Export to Excel</a></td>
                          </tr>
                          <?php } ?>
                        </tbody>
                      </table>
                    </div>
                  </div>
                  <div class="box-footer clearfix" style="">
                  
                  </div>
                </div>
              </div>
              <div class="col-md-6 col-xs-12"></div>
              <?php } ?>
              <?php if($_REQUEST['t']!=null){?>
              <form method="post" action="index.php?route=catalog/productterendah/set&token=<?php echo $token; ?>&t=1&filter_gudang_id=<?php echo $filter_gudang_id ?>&date=<?php echo $this->request->get['date'] ?>">
              <div class="col-md-12">
                <label>Tanggal Mulai Berlaku</label><br>
                <input type="text" name="date" class="date" autocomplete="off" value="" onchange="changedate()" required/>
                <button type="submit" class="btn btn-primary">Simpan</button>
                <table class="table table-bordered" id="myTable">
                    <thead>
                      <tr>
                        <th class="right">Mulai berlaku</th>
                        <th class="left">Gudang</th>
                        <th class="left">Nama Produk</th>
                        <th class="right">Harga Terendah (termasuk PPn)</th>
                        <th class="right">Poin</th>
                      </tr>
                    </thead>
                    <tbody>

                      <?php if ($products) { ?>
                      <?php $i=0; foreach ($products as $product) { ?>
                      <?php if($product['harga']==0){?>
                      <tr style="background-color:#ffcccc">
                      <?php }else{ ?>
                      <tr>
                      <?php } ?>
                        <td><span class="tglberlaku"></span><input type="hidden" name="products[<?php echo $i; ?>][product_id]" value="<?php echo $product['product_id']; ?>"/></td>
                        <td class="left"><?php echo $product['gudang_id'];?><input type="hidden" name="products[<?php echo $i; ?>][gudang_id]" value="<?php echo $product['gudang']; ?>"/></td>
                        <td class="left"><?php echo $product['name']; ?><input type="hidden" name="products[<?php echo $i; ?>][name]" value="<?php echo $product['name']; ?>"/></td>
                        <td><input type="text" name="products[<?php echo $i; ?>][harga]" value="<?php echo $product['harga']; ?>"/></td>
                        <td class="right"><input type="text" name="products[<?php echo $i; ?>][poin]" value="<?php echo $product['poin']; ?>"/> </td>
                      </tr>
                      <?php $i++; } ?>
                      <?php } else { ?>
                      <tr>
                        <td class="center" colspan="9">Data tidak ditemukan</td>
                      </tr>
                      <?php } ?>
                    </tbody>
                  </table>
                </div>
                </form>
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
  var tgdl='<?php echo $date?>';
  console.log(tgdl);
  $('.date').datepicker({
    dateFormat:'yy-mm-dd',
    minDate:tgdl,
  });
  $(".select-ads").select2({
      theme:"bootstrap"
  });
  var date=$(".date").val();
  if(date==""){
    date='belum diisi';
  }
  $(".tglberlaku").html(date);
  $('#myTable').DataTable({
    "lengthChange":false,
    "bPaginate":false,
    "bFilter":true,
  });
  $('#periodetbl').DataTable({
    "lengthChange":false,
    "bPaginate":true,
    "bFilter":false,
    "pageLength": 5,
    "ordering": false
  });
})

function changedate(){
  var date=$(".date").val();
  console.log($(".date").val());
  $(".tglberlaku").html(date);
}
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
//--></script>
<?php echo $footer; ?>
