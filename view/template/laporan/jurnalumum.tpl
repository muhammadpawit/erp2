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
            <h3 class="box-title">Jurnal Umum</h3>
            <div class="button pull-right">

              <?php
              if(!empty($filter_jenis)){
              ?>
              <b>Saldo Berjalan: <?php echo $this->currency->format($totalakun); ?></b>
              <?php
              }
              ?>
              <a href="<?php echo $exportexcel ?>" target="_blank"><button type="button" class="btn btn-success">Export To Excel</button></a>
            </div>
          </div>
          <div class="box-body">

            <div class="row">
              <div class="col-md-12">
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
                <table class="table table-stripped">
                  <thead>
                    <tr>
                      <th>Tanggal Awal</th>
                      <th>Tanggal Akhir</th>
                      <th>Akun</th>
                      <th>Referensi</th>
                      <th>Keterangan</th>
                      <th>Jurnal</th>
                      <th></th>

                    </tr>
                  </thead>
                  <tbody>
                  <tr>
                  <td><input type="text" class="form-control" id="date-start" name="filter_date_start" value="<?php echo $filter_date_start; ?>"></td>
                    <td><input type="text" class="form-control" id="date-end" name="filter_date_end" value="<?php echo $filter_date_end; ?>"></td>
                    <td><select style="width:300px" name="filter_jenis" class="form-control jeniscoa">
                      <option value="0">Semua Akun</option>
                      </select></td>
                    <td><input type="text" class="form-control" name="filter_ref" value="<?php echo $filter_ref; ?>"></td>
                    <td>
                    	<input type="text" class="form-control" name="filter_keterangan" value="<?php echo $filter_keterangan?>">
                    </td>
                    <td>
                      <select name="balance" class="form-control">
                        <option value="*">Semua</option>
                        <option value="2" <?php echo ($balance==2)?'selected':'';?>>Balance</option>
                        <option value="1" <?php echo ($balance==1)?'selected':''; ?>>Tidak Balance</option>
                      </select>
                    </td>
                    <td ><a onclick="filter();" class="btn btn-info">Filter</a></td>
                  </tr>
                </tbody>
                  </table>
              </div>
            </div>

            <div class="row">
              <div class="col-md-12">
                <table border="1" style="border-collapse: collapse;border:1px solid #bfb9b9;width: 100%;border-spacing: 3px;" id="tbl">
                <!--table class="table table-bordered"-->
                    <thead>
                      <tr>
                        <th class="text-center">Tanggal</th>
                        <th class="text-center">Ref</th>
                        <th class="text-center">No. Dokumen</th>
                        <th class="text-center">Keterangan</th>
                        <th colspan="2" class="text-center">Debet</th>
                        <th colspan="2" class="text-center">Kredit</th>
                      </tr>
                      <tr>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th class="text-center">Ref Akun</th>
                        <th></th>
                        <th class="text-center">Ref Akun</th>
                        <th></th>
                      </tr>

                    </thead>
                    <tbody>

                      <?php
                      if ($orders) { ?>
                      <?php foreach ($orders as $product) { ?>
                      <tr>
                        <td>&nbsp;<?php echo $product['tanggal']; ?></td>

                        <td>
                          &nbsp;<?php echo ($product['linkterkait']==null)?$product['ref']:$product['ref']; ?>
                        </td>
                        <td><?php 
                        if($product['idref'] > 0){
                          ?>
                          &nbsp;<a href="<?php echo $product['urlref']; ?>" target="_blank"><?php echo $product['no_dokumen']; ?></a>
                        <?php
                        }else{
                          echo $product['no_dokumen'];
                        }
                        ?></td>
                        <td>&nbsp;<b><?php echo $product['keterangan']; ?></b></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td class="right"><?php foreach ($product['action'] as $action) { ?>
                         <a href="<?php echo $action['href']; ?>" class="badge bg-yellow"><?php echo $action['text']; ?></a>
                        <?php } ?></td>

                      </tr>
                      <?php
                      foreach($product['detail'] as $d){
                      ?>
                      <tr>
                        <td></td>
                        <td></td>
                        <td></td>
                        <?php
                        if($d['debet'] > 0){
                        ?>
                          <td>&nbsp;<?php echo $d['keterangan']; ?></td>
                          <td>&nbsp;<?php echo $d['ref_akun']; ?></td>
                          <td>&nbsp;<?php echo $this->currency->format($d['debet']); ?></td>
                          <td></td>
                          <td></td>
                        <?php
                        }
                        ?>
                        <?php
                        if($d['kredit'] > 0){
                        ?>
                          <td style="padding-left:35px;"><?php echo $d['keterangan']; ?></td>
                          <td></td>
                          <td></td>
                          <td>&nbsp;<?php echo $d['ref_akun']; ?></td>
                          <td>&nbsp;<?php echo $this->currency->format($d['kredit']); ?></td>
                          
                        <?php
                        }
                        ?>

                      </tr>
                      <?php
                      }
                      ?>
                      <?php } ?>
                      <?php } else { ?>
                      <tr>
                        <td class="center" colspan="6">Data tidak ditemukan</td>
                      </tr>
                      <?php } ?>
                    </tbody>
                  </table>
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
<style>
#tbl td,th{padding:5px;}
</style>
<script>
$('.sidebar-menu').find('#menu-laporan').addClass('active');
$('.sidebar-menu').find('#menu-jurnal-umum').addClass('active');



</script>
<script type="text/javascript"><!--
$(document).ready(function() {
	$('#date-start').datepicker({dateFormat: 'yy-mm-dd'});

	$('#date-end').datepicker({dateFormat: 'yy-mm-dd'});
});
//--></script>
<script type="text/javascript"><!--
function filter() {
	url = 'index.php?route=laporan/jurnalumum&token=<?php echo $token; ?>';

	var filter_date_start = $('input[name=\'filter_date_start\']').val();

	if (filter_date_start) {
		url += '&filter_date_start=' + encodeURIComponent(filter_date_start);
	}

  var filter_ref = $('input[name=\'filter_ref\']').val();

	if (filter_ref) {
		url += '&filter_ref=' + encodeURIComponent(filter_ref);
	}

  var filter_date_end = $('input[name=\'filter_date_end\']').val();
  if(filter_date_start!=""){
    if(filter_date_start>filter_date_end){
      swal("tanggal awal harus lebih kecil atau sama dengan tanggal akhir");
      return false;
    }else{
      if(filter_date_end==""){
          swal("Tanggal akhir harus diisi");
          return false;
      }
    }
  }
	if (filter_date_end) {
		url += '&filter_date_end=' + encodeURIComponent(filter_date_end);
	}
  var filter_jenis = $('select[name=\'filter_jenis\']').val();

	if (filter_jenis > 0) {
		url += '&filter_jenis=' + encodeURIComponent(filter_jenis);
	}

	var filter_keterangan =$('input[name=\'filter_keterangan\']').val();

	if (filter_keterangan) {
		url += '&filter_keterangan=' + encodeURIComponent(filter_keterangan);
	}

  var balance = $('select[name=\'balance\']').val();

  if (balance != '*') {
    url += '&balance=' + balance;
  }


	location = url;
}
//--></script>
<script type="text/javascript"><!--
$(document).ready(function() {
	$('.date').datepicker({dateFormat: 'yy-mm-dd'});
  $(".jeniscoa").select2({
    ajax: {
    url:"index.php?route=keuangan/coa/autocompletes&token=<?php echo $this->request->get['token']; ?>",
    dataType: 'json',
    data: function (params) {
      return {
        filter_name: params.term,
      //  p:6200
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
