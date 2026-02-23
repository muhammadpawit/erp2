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
            <h3 class="box-title">Neraca</h3>
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
                        <th>Saldo Tanggal</th>
                        <th>Saldo Tanggal</th>
                        <th></td>
                      </tr>
                    </thead>
                    <tbody>
                    <tr>
					 <td><input type="text" class="form-control" name="filter_date_start" value="<?php echo $filter_date_start; ?>" id="date-start" size="12" /></td>
                      <td><input type="text" class="form-control" name="filter_date_end" value="<?php echo $filter_date_end; ?>" id="date-end" size="12" /></td>

                      <td ><a onclick="filter();" class="btn btn-info">Filter</a></td>
                    </tr>
                  </tbody>
                  </table>
              </div>
            </div>
            <div class="row">
              <div class="col-xs-12 col-md-6">
                  <table class="table table-bordered">
                  <thead>
                    <tr>
                      <th class="text-center">Kode Akun</th>
                      <th class="text-center">Nama Akun</th>
                      <th class="text-center">Saldo Tanggal<br><?php echo date('d/m/y',strtotime($filter_date_start)); ?></th>
                      <!--th class="text-center" colspan="2">Saldo Berjalan</th-->
                      <th class="text-center">Saldo Tanggal<br><?php echo date('d/m/y',strtotime($filter_date_end)); ?></th>
                    </tr>

                  </thead>
                  <tbody>
                    <?php
                    //pendapatan
                    foreach($aset as $p){
                      //if($p['parent_id'] != 0){
                      ?>
                      <tr>
                      <?php
                      if($p['parent_id'] != 0){ 
                      ?>
                        <td><?php echo $p['kode_rek']; ?></td>
                        <td><?php echo $p['name']; ?></td>
                      <?php
                      }else{
                      ?>
                      <td><b><?php echo $p['kode_rek']; ?></b></td>
                        <td><b><?php echo $p['name']; ?></b></td>
                      <?php
                      } 
                      ?>
                        <td><?php echo $p['saldoawal']; ?></td>
                        <td><?php echo $p['saldoakhir']; ?></td>

                      </tr>
                      <?php
                     /* }else{
                      ?>
                      <tr>
                        <td><b><?php echo $p['kode_rek']; ?></b></td>
                        <td><b><?php echo $p['name']; ?></b></td>
                        <td></td>
                        <td></td>
                      </tr>
                      <?php
                      }*/
                    }
                    //totalpendapatan
                    ?>
                    <tr>
                      <td><b>Total Aset</b></td>
                      <td></td>
                      <td><b><?php echo $asetawal; ?></b></td>
                      <td><b><?php echo $asetakhir; ?></b></td>

                    </tr>


                    <tr>
                      <td></td>
                      <td></td>
                      <td></td>
                      <td></td>

                    </tr>
                  </tbody>


                </table>

              </div>
              <div class="col-xs-12 col-md-6">
                  <table class="table table-bordered">
                  <thead>
                    <tr>
                      <th class="text-center">Kode Akun</th>
                      <th class="text-center">Nama Akun</th>
                      <th class="text-center">Saldo Tanggal<br><?php echo date('d/m/y',strtotime($filter_date_start)); ?></th>
                      <!--th class="text-center" colspan="2">Saldo Berjalan</th-->
                      <th class="text-center">Saldo Tanggal<br><?php echo date('d/m/y',strtotime($filter_date_end)); ?></th>


                    </tr>

                  </thead>


                  <tbody>
                    <?php
                    //hutang
                    foreach($hutang as $p){
                      //if($p['parent_id'] != 0){
                      ?>
                      <tr>
                        <?php
                      if($p['parent_id'] != 0){ 
                      ?>
                        <td><?php echo $p['kode_rek']; ?></td>
                        <td><?php echo $p['name']; ?></td>
                      <?php
                      }else{
                      ?>
                      <td><b><?php echo $p['kode_rek']; ?></b></td>
                        <td><b><?php echo $p['name']; ?></b></td>
                      <?php
                      } 
                      ?>
                        <td><?php echo $p['saldoawal']; ?></td>
                        <td><?php echo $p['saldoakhir']; ?></td>

                      </tr>
                      <?php
                     /* }else{
                      ?>
                      <tr>
                        <td><b><?php echo $p['kode_rek']; ?></b></td>
                        <td><b><?php echo $p['name']; ?></b></td>
                        <td></td>
                        <td></td>
                      </tr>
                      <?php
                      }*/
                    }
                    //totalpendapatan
                    ?>
                    <tr>
                      <td><b>Total Kewajiban</b></td>
                      <td></td>
                      <td><b><?php echo $hutangawal; ?></b></td>
                      <td><b><?php echo $hutangakhir; ?></b></td>

                    </tr>


                    <tr>
                      <td></td>
                      <td></td>
                      <td></td>
                      <td></td>
                    </tr>
                  </tbody>
                  <tbody>
                    <?php
                    //modal
                    foreach($modal as $p){
                      //if($p['parent_id'] != 0){
                      ?>
                      <tr>
                        <?php
                      if($p['parent_id'] != 0){ 
                      ?>
                        <td><?php echo $p['kode_rek']; ?></td>
                        <td><?php echo $p['name']; ?></td>
                      <?php
                      }else{
                      ?>
                      <td><b><?php echo $p['kode_rek']; ?></b></td>
                        <td><b><?php echo $p['name']; ?></b></td>
                      <?php
                      } 
                      ?>
                        <td><?php echo $p['saldoawal']; ?></td>
                        <td><?php echo $p['saldoakhir']; ?></td>

                      </tr>
                      <?php
                     /* }else{
                      ?>
                      <tr>
                        <td><b><?php echo $p['kode_rek']; ?></b></td>
                        <td><b><?php echo $p['name']; ?></b></td>
                        <td></td>
                        <td></td>
                      </tr>
                      <?php
                      }*/
                    }
                    //totalpendapatan
                    ?>
                    <tr>
                      <td><b>Total Modal</b></td>
                      <td></td>
                      <td><b><?php echo $modalawal; ?></b></td>
                      <td><b><?php echo $modalakhir; ?></b></td>

                    </tr>


                    <tr>
                      <td></td>
                      <td></td>
                      <td><b><?php echo $totalpasivaawal; ?></b></td>
                      <td><b><?php echo $totalpasivaakhir; ?></b></td>

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
	url = 'index.php?route=laporan/neraca&token=<?php echo $this->request->get['token']; ?>';

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
