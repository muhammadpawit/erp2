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

									<!-- Filter By -->
			<div class="row">
              <div class="col-md-12">
                <table class="table">
                  <tr>
                    <td>Range Tanggal</td>
                    <td><?php echo date('d/m/y',strtotime($labarugi['tglawal'])); ?> - <?php echo date('d/m/y',strtotime($labarugi['tglselesai'])); ?></td>
                  </tr>
                  <tr>
                    <td>Laba Rugi</td>
                    <td><?php echo $this->currency->format($labarugi['labarugi']); ?></td>
                  </tr>
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
                    $totalpendapatan=0;
                    foreach($pendapatan as $p){

                      $totalpendapatan +=$p['kredit'];
                      ?>
                      <tr>
                        <td><?php echo $p['ref_akun'].' '.$p['name']; ?></td>
                        <td></td>
                        <td><?php echo $this->currency->format($p['kredit']); ?></td>
                      </tr>
                      <?php
                      

                    }
                    //totalpendapatan
                    ?>
                    <tr>
                      <td><b>Total Pendapatan</b></td>
                      <td></td>
                      <td><b><?php echo $this->currency->format($totalpendapatan); ?></b></td>
                    </tr>

                    <?php

                    $totalhpp=0;
                    foreach($hpp as $p){
                      $totalhpp +=$p['debet'];
                      ?>
                      <tr>
                        <td><?php echo $p['ref_akun'].' '.$p['name']; ?></td>

                        <td><?php echo $this->currency->format($p['debet']); ?></td>
                        <td></td>
                      </tr>
                      <?php

                    }

                    ?>
                    <tr>
                      <td><b>Total HPP</b></td>

                      <td><b><?php echo $this->currency->format($totalhpp); ?></b></td>
                      <td></td>
                    </tr>

                    <tr>
                      <td><b>Laba Rugi Kotor</b></td>

                      <td></td>
                      <td><b><?php echo $this->currency->format($totalpendapatan-$totalhpp); ?></b></td>
                    </tr>

                    <?php

                    $totalbiaya=0;
                    foreach($biaya as $p){
                      $totalbiaya +=$p['debet'];
                      ?>
                      <tr>
                        <td><?php echo $p['ref_akun'].' '.$p['name']; ?></td>

                        <td><?php echo $this->currency->format($p['debet']); ?></td>
                        <td></td>
                      </tr>
                      <?php

                    }

                    ?>
                    <tr>
                      <td><b>Total Biaya</b></td>

                      <td><b><?php echo $this->currency->format($totalbiaya); ?></b></td>
                      <td></td>
                    </tr>



                    <?php
                    //pendapatanlain
                    $totalpendapatanlain=0;
                    foreach($pendapatanlain as $p){
                      $totalpendapatanlain +=$p['kredit'];
                      ?>
                      <tr>
                        <td><?php echo $p['ref_akun'].' '.$p['name']; ?></td>
                        <td></td>
                        <td><?php echo $this->currency->format($p['kredit']); ?></td>
                      </tr>
                      <?php

                    }
                    //totalpendapatan lain
                    ?>
                    <tr>
                      <td><b>Total Pendapatan Lain-Lain</b></td>
                      <td></td>
                      <td><b><?php echo $this->currency->format($totalpendapatanlain); ?></b></td>
                    </tr>

                    <?php

                    $totalbiayalain=0;
                    foreach($biayalain as $p){
                      $totalbiayalain +=$p['debet'];
                      ?>
                      <tr>
                        <td><?php echo $p['ref_akun'].' '.$p['name']; ?></td>

                        <td><?php echo $this->currency->format($p['debet']); ?></td>
                        <td></td>
                      </tr>
                      <?php

                    }

                    ?>
                    <tr>
                      <td><b>Total Biaya Lain-Lain</b></td>

                      <td><b><?php echo $this->currency->format($totalbiayalain); ?></b></td>
                      <td></td>
                    </tr>
                    <?php
                    //pendapatanlb
                    $totalpendapatanluarbiasa=0;
                    foreach($pendapatanluarbiasa as $p){
                      $totalpendapatanluarbiasa +=$p['kredit'];
                      ?>
                      <tr>
                        <td><?php echo $p['ref_akun'].' '.$p['name']; ?></td>
                        <td></td>
                        <td><?php echo $this->currency->format($p['kredit']); ?></td>
                      </tr>
                      <?php

                    }
                    //totalpendapatan
                    ?>
                    <tr>
                      <td><b>Total Pendapatan Luar Biasa</b></td>
                      <td></td>
                      <td><b><?php echo $this->currency->format($totalpendapatanluarbiasa); ?></b></td>
                    </tr>
                    <tr>
                      <td><b>Laba Bersih</b></td>
                      <td></td>
                      <td><b><?php echo $this->currency->format($totalpendapatan-$totalhpp-$totalbiaya-$totalbiayalain+$totalpendapatanlain+$totalpendapatanluarbiasa); ?></b></td>
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
