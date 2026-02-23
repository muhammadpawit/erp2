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
            <h3 class="box-title">Daftar PPn Masukan</h3>
            <div class="button pull-right">

                  </div>
          </div>
          <div class="box-body">

            <div class="row">
              <div class="col-md-12">
                <table class="table table-stripped">
                    <thead>
                      <tr>
                        <th>Filter Tanggal Awal</th>
                        <th>Filter Tanggal Akhir</th>
                        <th>Akun</th>
                        <th></th>

                      </tr>
                    </thead>
                    <tbody>
                    <tr>
                    <td><input type="text" class="form-control" id="date-start" name="filter_date_start" value="<?php echo $filter_date_start; ?>"></td>
                      <td><input type="text" class="form-control" id="date-end" name="filter_date_end" value="<?php echo $filter_date_end; ?>"></td>

                      <td ><a onclick="filter();" class="btn btn-info">Filter</a></td>
                    </tr>
                  </tbody>
                  </table>
              </div>
            </div>

            <div class="row">
              <div class="col-md-12">
                <table class="table table-bordered table-hover">
                    <thead>
                      <tr>
                        <th>No.</th>
                        <th>Tanggal</th>
                        <th>Keterangan</th>
                        <th class="text-center">Debet</th>
                        <th class="text-center">Kredit</th>
                        <th class="text-center">No.Faktur Pajak</th>
                      </tr>


                    </thead>
                    <tbody>

                      <?php
                      $i=1;
                      $tkredit=0;
                      $totdebet=0;
                      $t=[];
                      if ($orders) { ?>
                      <?php foreach ($orders as $product) { ?>

                      <?php

                      foreach($product['detail'] as $d){
                        //if($d['kredit']>0){
                          //$tkredit += $d['kredit'];
                          //$t[] = $d['kredit'];
                          //$totdebet += $d['debet'];
                      ?>
                      <tr>
                        <td><?php echo $i++;?></td>
                        <td><?php echo $product['tanggal']; ?></td>

                        <td><?php echo $product['keterangan']; ?></td>
                        <td><?php echo $this->currency->format($d['debet']); ?></td>
                        <td><?php echo $this->currency->format($d['kredit']); ?></td>
                        <td class="text-center no_fakturpajak" id="<?php echo $d['id']; ?>"><?php echo $d['no_fakturpajak']; ?></td>
                      </tr>
                      <?php
                       // }
                      }
                      ?>
                      <?php } ?>

                      <?php } else { ?>
                      <tr>
                        <td class="center" colspan="6">Data tidak ditemukan</td>
                      </tr>
                      <?php } ?>
                      <tr>
                      <?php
                      foreach($alls as $d){
                        foreach($d['detail'] as $d){
                          $tkredit += $d['kredit'];
                          
                          $totdebet += $d['debet'];
                        }
                      }
                      ?>
                        <td colspan="2"></td>
                        <td><b>Total : <?php echo count($alls)?></b></td>
                        <td><b><?php echo $this->currency->format($totdebet); ?></b></td>
                        <td><b><?php echo $this->currency->format($tkredit); ?></b></td>

                      </tr>                      
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
<script>
$('.sidebar-menu').find('#menu-pajak').addClass('active');
$('.sidebar-menu').find('#menu-ppn-masukan').addClass('active');



</script>
<script type="text/javascript"><!--
$(document).ready(function() {
	$('#date-start').datepicker({dateFormat: 'yy-mm-dd'});

	$('#date-end').datepicker({dateFormat: 'yy-mm-dd'});
});
//--></script>
<script type="text/javascript"><!--
function filter() {
	url = 'index.php?route=keuangan/ppnmasukan&token=<?php echo $token; ?>';

	var filter_date_start = $('input[name=\'filter_date_start\']').val();

	if (filter_date_start) {
		url += '&filter_date_start=' + encodeURIComponent(filter_date_start);
	}

  var filter_date_end = $('input[name=\'filter_date_end\']').val();

	if (filter_date_end) {
		url += '&filter_date_end=' + encodeURIComponent(filter_date_end);
	}

	location = url;
}
//--></script>
<script type="text/javascript"><!--
$(document).ready(function() {
	$('.date').datepicker({dateFormat: 'yy-mm-dd'});

});
$(".no_fakturpajak").editable('index.php?route=keuangan/ppnmasukan/edittable&token=<?php echo $token; ?>&column=no_fakturpajak', {
        submit : 'Simpan',
        cancel : 'Cancel',
        cssclass : 'custom-class',
        cancelcssclass : 'badge bg-red',
        submitcssclass : 'badge bg-green',
        formid : 'abc-123',
        id   : 'jurnal_detail_id',
        name : 'no_fakturpajak',
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
