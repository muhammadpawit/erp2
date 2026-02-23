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
            <h3 class="box-title">Laporan Laba Rugi</h3>
            <div class="button pull-right">

            </div>
          </div>
          <div class="box-body">
            <div class="row">
              <div class="col-md-12">

                <?php if ($success) { ?>
                <div class="alert alert-success alert-dismissible">
                  <button type="button" class="close" data-dismiss="alert" aria-hidden="true">�</button>
                  <h4><i class="icon fa fa-check"></i> Success!</h4>
                  <?php echo $success; ?>
                </div>
                <?php
                }
                ?>
              </div>
            </div>
									<!-- Filter By -->
			<div class="row">
              <div class="col-md-12">
                <table class="table table-stripped">
                    <thead>
                      <tr>
                        <th>Tanggal Awal</th>
                        <th>Tanggal Akhir</th>
                        <th></td>
                      </tr>
                    </thead>
                    <tbody>
                    <tr>
					 <td><input type="text" class="form-control" name="filter_date_start" value="<?php echo $filter_date_start; ?>" id="date-start" size="12" readonly /></td>
                      <td><input type="text" class="form-control" name="filter_date_end" value="<?php echo $filter_date_end; ?>" id="date-end" size="12" readonly /></td>

                      <td ><a onclick="filter();" class="btn btn-info">Filter</a></td>
                    </tr>
                  </tbody>
                  </table>
              </div>
            </div>
            <div class="row">
              <div class="col-md-12">
                  <table class="table">
                  <thead>
                    <tr>
                      <th class="left"></th>
                      <th class="left"></th>
                      <th class="left"></th>


                    </tr>
                  </thead>
                  <tbody>
                    <?php
                    //pendapatan
                    foreach($pendapatan as $p){
                      if($p['kode_rek'] != '4000'){
                      ?>
                      <tr>
                        <td><?php echo $p['kode_rek'].' '.$p['name']; ?></td>
                        <td></td>
                        <td><?php echo $p['saldo']; ?></td>
                      </tr>
                      <?php
                      }else{
                      ?>
                      <tr>
                        <td><b><?php echo $p['name']; ?></b></td>
                        <td></td>
                        <td>
                        <?php
                          if($p['plainsaldo'] > 0){
                            echo $p['saldo']; 
                          }
                        ?>
                        </td>
                      </tr>
                      <?php
                      }
                    }
                    //totalpendapatan
                    ?>
                    <tr>
                      <td><b>Total Pendapatan</b></td>
                      <td></td>
                      <td><b><?php echo $totalpendapatan; ?></b></td>
                    </tr>

                    <?php
                    //hpp
                    foreach($hpp as $p){
                      if($p['kode_rek'] != '5000' & $p['kode_rek'] != '5100'){
                      ?>
                      <tr>
                        <td><?php echo $p['kode_rek'].' '.$p['name']; ?></td>
                        <td></td>
                        <td><?php echo $p['saldo']; ?></td>
                      </tr>
                      <?php
                      }else{
                      ?>
                      <tr>
                        <td><b><?php echo $p['name']; ?></b></td>
                        <td></td>
                        <td>
                        <?php
                          if($p['plainsaldo'] > 0){
                            echo $p['saldo']; 
                          }
                        ?>
                        </td>
                      </tr>
                      <?php
                      }
                    }
                    //hpp
                    ?>
                    <tr>
                      <td><b>Total Harga Pokok Penjualan</b></td>
                      <td><b><?php echo $totalhpp; ?></b></td>
                      <td></td>
                    </tr>
                    <tr>
                      <td><b>Laba Rugi Kotor</b></td>
                      <td></td>
                      <td><b><?php echo $labakotor; ?></b></td>
                    </tr>

                    <?php
                    //biaya
                    foreach($biaya as $p){
                      if($p['kode_rek'] != '6200' & $p['kode_rek'] != '6240' & $p['kode_rek'] != '6250'){
                      ?>
                      <tr>
                        <td><?php echo $p['kode_rek'].' '.$p['name']; ?></td>
                        <td><?php echo $p['saldo']; ?></td>
                        <td></td>
                      </tr>
                      <?php
                      }else{
                      ?>
                      <tr>
                        <td><b><?php echo $p['name']; ?></b></td>
                        <td>
                        <?php
                          if($p['plainsaldo'] > 0){
                            echo $p['saldo']; 
                          }
                        ?>
                        </td>
                        <td></td>
                      </tr>
                      <?php
                      }
                    }
                    //biaya
                    ?>
                    <tr>
                      <td><b>Total Biaya</b></td>
                      <td><b><?php echo $totalbiaya; ?></b></td>
                      <td></td>
                    </tr>

                    <?php
                    //biaya lain
                    foreach($biayalain as $p){
                      if($p['kode_rek'] != '7000' ){
                      ?>
                      <tr>
                        <td><?php echo $p['kode_rek'].' '.$p['name']; ?></td>
                        <td><?php echo $p['saldo']; ?></td>
                        <td></td>
                      </tr>
                      <?php
                      }else{
                      ?>
                      <tr>
                        <td><b><?php echo $p['name']; ?></b></td>
                        <td>
                        <?php
                          if($p['plainsaldo'] > 0){
                            echo $p['saldo']; 
                          }
                        ?>
                        </td>
                        <td></td>
                      </tr>
                      <?php
                      }
                    }
                    //biaya
                    ?>
                    <tr>
                      <td><b>Total Pendapatan Lain-Lain</b></td>
                      <td><b><?php echo $totalbiayalain; ?></b></td>
                      <td></td>
                    </tr>

                    <?php
                    //pendapatan lain
                    foreach($pendapatanlain as $p){
                      if($p['kode_rek'] != '8000' ){
                      ?>
                      <tr>
                        <td><?php echo $p['kode_rek'].' '.$p['name']; ?></td>
                        <td></td>
                        <td><?php echo $p['saldo']; ?></td>
                      </tr>
                      <?php
                      }else{
                      ?>
                      <tr>
                        <td><b><?php echo $p['name']; ?></b></td>
                        <td>
                        <?php
                          if($p['plainsaldo'] > 0){
                            echo $p['saldo']; 
                          }
                        ?>
                        </td>
                        <td></td>
                      </tr>
                      <?php
                      }
                    }
                    //biaya
                    ?>
                    <tr>
                      <td><b>Total Beban Lain-Lain</b></td>
                      <td></td>
                      <td><b><?php echo $totalpendapatanlain; ?></b></td>
                    </tr>

                    <?php

                    //pendapatan luarbiasa
                    foreach($pendapatanluarbiasa as $p){
                      if($p['kode_rek'] != '9000' ){
                      ?>
                      <tr>
                        <td><?php echo $p['kode_rek'].' '.$p['name']; ?></td>
                        <td></td>
                        <td><?php echo $p['saldo']; ?></td>
                      </tr>
                      <?php
                      }else{
                      ?>
                      <tr>
                        <td><b><?php echo $p['name']; ?></b></td>
                        <td>
                        <?php
                          if($p['plainsaldo'] > 0){
                            echo $p['saldo']; 
                          }
                        ?>
                        </td>
                        <td></td>
                      </tr>
                      <?php
                      }
                    }
                    //biaya
                    ?>
                    <tr>
                      <td><b>Total Pendapatan & Beban Luar Biasa</b></td>
                      <td></td>
                      <td><b><?php echo $totalpendapatanluarbiasa; ?></b></td>
                    </tr>

                    <tr>
                      <td><b>Laba Bersih</b></td>
                      <td></td>
                      <td><b><?php echo $lababersih; ?></b></td>
                    </tr>

                  </tbody>
                </table>

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
$('.sidebar-menu').find('#menu-report').addClass('active');


$(function(){
  $(".select-ads").select2({
      theme:"bootstrap"
    });
})
</script>
<script type="text/javascript"><!--
$(document).ready(function() {
	$('#date-start').datepicker({dateFormat: 'yy-mm-dd'});

	$('#date-end').datepicker({dateFormat: 'yy-mm-dd'});
});
//--></script>
<script type="text/javascript"><!--
function filter() {
	url = 'index.php?route=laporan/laporanlabarugi&token=<?php echo $token; ?>';

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
$('#form input').keydown(function(e) {
	if (e.keyCode == 13) {
		filter();
	}
});
//--></script>


<?php echo $footer; ?>
