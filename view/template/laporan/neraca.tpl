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
            <h3 class="box-title">Neraca Saldo</h3>
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
                  <table class="table table-bordered">
                  <thead>
                    <tr>
                      <th class="text-center">Kode Akun</th>
                      <th class="text-center">Nama Akun</th>
                      <th class="text-center" colspan="2">Saldo Awal</th>
                      <th class="text-center" colspan="2">Saldo Berjalan</th>
                      <th class="text-center" colspan="2">Saldo Akhir</th>


                    </tr>
                    <tr>
                      <th class="text-center"></th>
                      <th class="text-center"></th>
                      <th class="text-center">Debet</th>
                      <th class="text-center">Kredit</th>
                      <th class="text-center">Debet</th>
                      <th class="text-center">Kredit</th>
                      <th class="text-center">Debet</th>
                      <th class="text-center">Kredit</th>


                    </tr>
                  </thead>
                  <tbody>
                    <?php
                    //pendapatan
                    foreach($aset as $p){
                      if($p['parent_id'] != 0){
                      ?>
                      <tr>
                        <td><?php echo $p['kode_rek']; ?></td>
                        <td><?php echo $p['name']; ?></td>
                        <td><?php echo $p['debetawal']; ?></td>
                        <td><?php echo $p['kreditawal']; ?></td>
                        <td><?php echo $p['debetberjalan']; ?></td>
                        <td><?php echo $p['kreditberjalan']; ?></td>
                        <td><?php echo $p['debetakhir']; ?></td>
                        <td><?php echo $p['kreditakhir']; ?></td>
                      </tr>
                      <?php
                      }else{
                      ?>
                      <tr>
                        <td><b><?php echo $p['kode_rek']; ?></b></td>
                        <td><b><?php echo $p['name']; ?></b></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                      </tr>
                      <?php
                      }
                    }
                    //totalpendapatan
                    ?>
                    <tr>
                      <td><b>Total Aset</b></td>
                      <td></td>
                      <td><b><?php echo $asetawal; ?></b></td>
                      <td></td>
                      <td><b><?php echo $asetberjalan; ?></b></td>
                      <td></td>
                      <td><b><?php echo $asetakhir; ?></b></td>
                      <td></td>
                    </tr>

                    <?php
                    //hutang
                    foreach($hutang as $p){
                      if($p['parent_id'] != 0){
                      ?>
                      <tr>
                        <td><?php echo $p['kode_rek']; ?></td>
                        <td><?php echo $p['name']; ?></td>
                        <td><?php echo $p['debetawal']; ?></td>
                        <td><?php echo $p['kreditawal']; ?></td>
                        <td><?php echo $p['debetberjalan']; ?></td>
                        <td><?php echo $p['kreditberjalan']; ?></td>
                        <td><?php echo $p['debetakhir']; ?></td>
                        <td><?php echo $p['kreditakhir']; ?></td>
                      </tr>
                      <?php
                      }else{
                      ?>
                      <tr>
                        <td><b><?php echo $p['kode_rek']; ?></b></td>
                        <td><b><?php echo $p['name']; ?></b></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                      </tr>
                      <?php
                      }
                    }
                    //totalpendapatan
                    ?>
                    <tr>
                      <td><b>Total Hutang</b></td>
                      <td></td>
                      <td></td>
                      <td><b><?php echo $hutangawal; ?></b></td>
                      <td></td>
                      <td><b><?php echo $hutangberjalan; ?></b></td>
                      <td></td>
                      <td><b><?php echo $hutangakhir; ?></b></td>
                    </tr>

                    <?php
                    //modal
                    foreach($modal as $p){
                      if($p['parent_id'] != 0){
                      ?>
                      <tr>
                        <td><?php echo $p['kode_rek']; ?></td>
                        <td><?php echo $p['name']; ?></td>
                        <td><?php echo $p['debetawal']; ?></td>
                        <td><?php echo $p['kreditawal']; ?></td>
                        <td><?php echo $p['debetberjalan']; ?></td>
                        <td><?php echo $p['kreditberjalan']; ?></td>
                        <td><?php echo $p['debetakhir']; ?></td>
                        <td><?php echo $p['kreditakhir']; ?></td>
                      </tr>
                      <?php
                      }else{
                      ?>
                      <tr>
                        <td><b><?php echo $p['kode_rek']; ?></b></td>
                        <td><b><?php echo $p['name']; ?></b></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                      </tr>
                      <?php
                      }
                    }
                    //total modal
                    ?>
                    <tr>
                      <td><b>Total Modal</b></td>
                      <td></td>
                      <td></td>
                      <td><b><?php echo $modalawal; ?></b></td>
                      <td></td>
                      <td><b><?php echo $modalberjalan; ?></b></td>
                      <td></td>
                      <td><b><?php echo $modalakhir; ?></b></td>
                    </tr>

                    <?php
                    //pendapatan
                    foreach($pendapatan as $p){
                      if($p['parent_id'] != 0){
                      ?>
                      <tr>
                        <td><?php echo $p['kode_rek']; ?></td>
                        <td><?php echo $p['name']; ?></td>
                        <td><?php echo $p['debetawal']; ?></td>
                        <td><?php echo $p['kreditawal']; ?></td>
                        <td><?php echo $p['debetberjalan']; ?></td>
                        <td><?php echo $p['kreditberjalan']; ?></td>
                        <td><?php echo $p['debetakhir']; ?></td>
                        <td><?php echo $p['kreditakhir']; ?></td>
                      <?php
                      }else{
                      ?>
                      <tr>
                        <td><b><?php echo $p['kode_rek']; ?></b></td>
                        <td><b><?php echo $p['name']; ?></b></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                      </tr>
                      <?php
                      }
                    }
                    //totalpendapatan
                    ?>
                    <tr>
                      <td><b>Total Pendapatan</b></td>
                      <td></td>
                      <td></td>
                      <td><b><?php echo $pendapatanawal; ?></b></td>
                      <td></td>
                      <td><b><?php echo $pendapatanberjalan; ?></b></td>
                      <td></td>
                      <td><b><?php echo $pendapatanakhir; ?></b></td>
                    </tr>

                    <?php
                    //hpp
                    foreach($hpp as $p){
                      if($p['parent_id'] != 0){
                      ?>
                      <tr>
                        <td><?php echo $p['kode_rek']; ?></td>
                        <td><?php echo $p['name']; ?></td>
                        <td><?php echo $p['debetawal']; ?></td>
                        <td><?php echo $p['kreditawal']; ?></td>
                        <td><?php echo $p['debetberjalan']; ?></td>
                        <td><?php echo $p['kreditberjalan']; ?></td>
                        <td><?php echo $p['debetakhir']; ?></td>
                        <td><?php echo $p['kreditakhir']; ?></td>
                      </tr>
                      <?php
                      }else{
                      ?>
                      <tr>
                        <td><b><?php echo $p['kode_rek']; ?></b></td>
                        <td><b><?php echo $p['name']; ?></b></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                      </tr>
                      <?php
                      }
                    }
                    //totalhpp
                    ?>
                    <tr>
                      <td><b>Total Harga Pokok Penjualan</b></td>
                      <td></td>
                      <td></td>
                      <td><b><?php echo $hppawal; ?></b></td>
                      <td></td>
                      <td><b><?php echo $hppberjalan; ?></b></td>
                      <td></td>
                      <td><b><?php echo $hppakhir; ?></b></td>
                    </tr>

                    <?php
                    //beban
                    foreach($beban as $p){
                      if($p['parent_id'] != 0){
                      ?>
                      <tr>
                        <td><?php echo $p['kode_rek']; ?></td>
                        <td><?php echo $p['name']; ?></td>
                        <td><?php echo $p['debetawal']; ?></td>
                        <td><?php echo $p['kreditawal']; ?></td>
                        <td><?php echo $p['debetberjalan']; ?></td>
                        <td><?php echo $p['kreditberjalan']; ?></td>
                        <td><?php echo $p['debetakhir']; ?></td>
                        <td><?php echo $p['kreditakhir']; ?></td>
                      </tr>
                      <?php
                      }else{
                      ?>
                      <tr>
                        <td><b><?php echo $p['kode_rek']; ?></b></td>
                        <td><b><?php echo $p['name']; ?></b></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                      </tr>
                      <?php
                      }
                    }
                    //totalbeban
                    ?>
                    <tr>
                      <td><b>Total Beban</b></td>
                      <td></td>
                      <td></td>
                      <td><b><?php echo $bebanawal; ?></b></td>
                      <td></td>
                      <td><b><?php echo $bebanberjalan; ?></b></td>
                      <td></td>
                      <td><b><?php echo $bebanakhir; ?></b></td>
                    </tr>

                    <?php
                    //bebanlain
                    foreach($bebanlain as $p){
                      if($p['parent_id'] != 0){
                      ?>
                      <tr>
                        <td><?php echo $p['kode_rek']; ?></td>
                        <td><?php echo $p['name']; ?></td>
                        <td><?php echo $p['debetawal']; ?></td>
                        <td><?php echo $p['kreditawal']; ?></td>
                        <td><?php echo $p['debetberjalan']; ?></td>
                        <td><?php echo $p['kreditberjalan']; ?></td>
                        <td><?php echo $p['debetakhir']; ?></td>
                        <td><?php echo $p['kreditakhir']; ?></td>
                      </tr>
                      <?php
                      }else{
                      ?>
                      <tr>
                        <td><b><?php echo $p['kode_rek']; ?></b></td>
                        <td><b><?php echo $p['name']; ?></b></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                      </tr>
                      <?php
                      }
                    }
                    //totalbeban
                    ?>
                    <tr>
                      <td><b>Total Beban Lain-lain</b></td>
                      <td></td>
                      <td></td>
                      <td><b><?php echo $bebanlainawal; ?></b></td>
                      <td></td>
                      <td><b><?php echo $bebanlainberjalan; ?></b></td>
                      <td></td>
                      <td><b><?php echo $bebanlainakhir; ?></b></td>
                    </tr>

                    <?php
                    //pendapatan lain
                    foreach($pendapatanlain as $p){
                      if($p['parent_id'] != 0){
                      ?>
                      <tr>
                        <td><?php echo $p['kode_rek']; ?></td>
                        <td><?php echo $p['name']; ?></td>
                        <td><?php echo $p['debetawal']; ?></td>
                        <td><?php echo $p['kreditawal']; ?></td>
                        <td><?php echo $p['debetberjalan']; ?></td>
                        <td><?php echo $p['kreditberjalan']; ?></td>
                        <td><?php echo $p['debetakhir']; ?></td>
                        <td><?php echo $p['kreditakhir']; ?></td>
                      </tr>
                      <?php
                      }else{
                      ?>
                      <tr>
                        <td><b><?php echo $p['kode_rek']; ?></b></td>
                        <td><b><?php echo $p['name']; ?></b></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                      </tr>
                      <?php
                      }
                    }
                    //totalbeban
                    ?>
                    <tr>
                      <td><b>Total Pendapatan Lain-Lain</b></td>
                      <td></td>
                      <td></td>
                      <td><b><?php echo $pendapatanlainawal; ?></b></td>
                      <td></td>
                      <td><b><?php echo $pendapatanlainberjalan; ?></b></td>
                      <td></td>
                      <td><b><?php echo $pendapatanlainakhir; ?></b></td>
                    </tr>
                    <?php
                    //bebanpendapatanluarbiasa
                    foreach($pendapatanluar as $p){
                      if($p['parent_id'] != 0){
                      ?>
                      <tr>
                        <td><?php echo $p['kode_rek']; ?></td>
                        <td><?php echo $p['name']; ?></td>
                        <td><?php echo $p['debetawal']; ?></td>
                        <td><?php echo $p['kreditawal']; ?></td>
                        <td><?php echo $p['debetberjalan']; ?></td>
                        <td><?php echo $p['kreditberjalan']; ?></td>
                        <td><?php echo $p['debetakhir']; ?></td>
                        <td><?php echo $p['kreditakhir']; ?></td>
                      </tr>
                      <?php
                      }else{
                      ?>
                      <tr>
                        <td><b><?php echo $p['kode_rek']; ?></b></td>
                        <td><b><?php echo $p['name']; ?></b></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                      </tr>
                      <?php
                      }
                    }
                    //totalbeban
                    ?>
                    <tr>
                      <td><b>Total Pendapatan/Beban Luar Biasa</b></td>
                      <td></td>
                      <td></td>
                      <td><b><?php echo $pendapatanluarawal; ?></b></td>
                      <td></td>
                      <td><b><?php echo $pendapatanluarberjalan; ?></b></td>
                      <td></td>
                      <td><b><?php echo $pendapatanluarakhir; ?></b></td>
                    </tr>
                    <tr>
                      <td></td>
                      <td></td>
                      <td><b><?php echo $totaldebetawal; ?></b></td>
                      <td><b><?php echo $totalkreditawal; ?></b></td>
                      <td><b><?php echo $totaldebetberjalan; ?></b></td>
                      <td><b><?php echo $totalkreditberjalan; ?></b></td>
                      <td><b><?php echo $totaldebetakhir; ?></b></td>
                      <td><b><?php echo $totalkreditakhir; ?></b></td>

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
	url = 'index.php?route=laporan/neracasaldo&token=<?php echo $token; ?>';

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
