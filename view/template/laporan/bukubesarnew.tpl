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
            <h3 class="box-title">Buku Besar</h3>
            <div class="button pull-right">
              <span class="pull-left"><a href="<?php echo $cetak ?>" target="_blank"><button type="button" class="btn btn-success">Export To Excel</button></a></span>
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
                      <td><select style="width:300px" name="filter_jenis" class="form-control jeniscoa">
                        </select></td>
                      <td ><a onclick="filter();" class="btn btn-info">Filter</a></td>
                    </tr>
                  </tbody>
                  </table>
              </div>
            </div>
            Akun : <?php echo $namaakun?>
            <div class="row">
              <div class="col-md-12">
                <table class="table table-bordered">
                    <thead>
                      <tr>
                        <th>Tanggal</th>
                        <th>No.Dokumen</th>
                        <th>Keterangan</th>
                        <th class="text-center">Debet</th>
                        <th class="text-center">Kredit</th>
                        <th class="text-center">Saldo</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php if ($orders){ ?>
                        <?php
                          $format="d/m/Y";
                          $year=date('Y',strtotime($filter_date_start));
                          $awal= date($format, strtotime($year."-01-01"));
                          $sisa=0;
                          $a=0;
                          $sblm=0;
                          $debet=0;
                          $kredit=0;
                          $saldoakhir=0;
                          foreach ($orders as $product) {
                            foreach($product['detail'] as $d){
                              if($a==0){
                                $sisa += ($d['debet']-$d['kredit']);
                              }else{
                                $sisa += ($d['debet']-$d['kredit']);
                              }

                              if($product['tanggal'] < $filter_date_start){
                                $sblm = $sisa;
                              } 
                              $a++;
                            }
                          }
                        ?>
                            <?php //if($this->user->getUsername()=="pawit"){?>
                            <tr>
                              <td><b><?php //echo echo $awal ?></b></td>
                              <td>-</td>
                              <td>Saldo Awal </td>
                              <td align="right"><?php echo $this->currency->format(0)?></td>
                              <td align="right"><?php echo $this->currency->format(0)?></td>
                              <td align="right"><?php echo $this->currency->format($saldoawal)?></td>
                            </tr>
                            <?php //} ?>
                      <?php } ?>
                      <?php
                      $sisa=0;
                      $a=0;
                      $sblm=0;
                      $debet=0;
                      $kredit=0;
                      $saldoakhir=0;
                      if ($orders) { ?>
                      <?php foreach ($orders as $product) { ?>

                      <?php
                      foreach($product['detail'] as $d){
                      ?>
                      <?php
                          /*
                            if($a==0){
                              $sisa = $saldoawal+($d['debet']-$d['kredit']);
                            }else{
                              $sisa = $saldoawal+($d['debet']-$d['kredit']);
                            }                          
                          if($product['tanggal'] < $filter_date_start){
                            $sblm = $sisa;
                          }else{
                            $sblm=$sisa;
                          }*/
                          
                          if( date('Y-m-d',strtotime($product['tanggal'])) >= $filter_date_start & date('Y-m-d',strtotime($product['tanggal'])) <= $filter_date_end  ) { 
                          if($a==0){
                            $sisa=$saldoawal+($d['debet']-$d['kredit']);
                          }else{
                            $sisa=$sisa+($d['debet']-$d['kredit']);
                          }
                          $a++;
                          }
                      ?>
                      <?php if( date('Y-m-d',strtotime($product['tanggal'])) >= $filter_date_start & date('Y-m-d',strtotime($product['tanggal'])) <= $filter_date_end  ) { ?>
                      <?php 
                          $debet +=$d['debet'];
                          $kredit +=$d['kredit'];
                      ?>
                      <?php 
                        $this->load->model('pembelian/invoicepembeliandagang');
                        $ket=null;       
                        $ketivc=null;                 
                        if($filter_jenis==1101){
                          if($d['kredit']>0){
                            $ket=$this->model_keuangan_jurnal->getnamacust($product['ref']);
                            $ketivc='( '.$this->model_keuangan_jurnal->getnamacustivc($product['ref']).' )';
                          }else{
                            $ket=$this->model_keuangan_jurnal->getnamacustdeb($product['ref']);
                          }
                        }else if($filter_jenis==2101){
                          $ketname=substr($product['keterangan'],0,57);
                          if( strtolower($ketname) == "alokasi pembayaran pembelian produk dagang untuk invoice "){
                            $akhir=substr($product['keterangan'],57);
                            $ket=$this->model_pembelian_invoicepembeliandagang->getkethutangusaha($akhir);
                          }else if( strtolower(substr($product['keterangan'],0,49)) == "invoice pembelian produk dagang dengan no faktur "){
                            $akhir=substr($product['keterangan'],49);
                            $ket=$this->model_pembelian_invoicepembeliandagang->getkethutangusaha($akhir);
                          }else{
                            $ket=null;
                          }
                        }else if($filter_jenis==1311){
                          $ketname=substr($product['keterangan'],0,57);
                          if( strtolower($ketname) == "alokasi pembayaran pembelian produk dagang untuk invoice "){
                            $akhir=substr($product['keterangan'],57);
                            $ket=$this->model_pembelian_invoicepembeliandagang->getkethutangusaha($akhir);
                          }else if( strtolower(substr($product['keterangan'],0,49)) == "invoice pembelian produk dagang dengan no faktur "){
                            $akhir=substr($product['keterangan'],49);
                            $ket=$this->model_pembelian_invoicepembeliandagang->getkethutangusaha($akhir);
                          }else if( strtolower($product['keterangan']) == "deposit pembelian ke vendor"){
                            $ket=$this->model_pembelian_invoicepembeliandagang->getkethutangusaha2($product['ref'],$product['type']);
                          }else{
                            $ket=null;
                          }
                        }
                      ?>
                      <tr>
                        <td><?php echo date('d/m/Y',strtotime($product['tanggal'])); ?></td>
                        <td><?php echo $product['linkterkait'] ?></td>
                        <td><b><?php echo $product['keterangan']; ?> <?php echo $ket ?> <?php echo $ketivc ?></b></td>
                        <td align="right"><?php echo $this->currency->format($d['debet']); ?></td>
                        <td align="right"><?php echo $this->currency->format($d['kredit']); ?></td>
                        <td align="right">
                          <?php echo $this->currency->format($sisa) ?>
                        </td>
                      </tr>
                      <?php }?>
                      <?php
                      }
                      ?>
                      <?php } ?>
                      <?php if($jumlah>101){ ?>
                      <tr class="blurry-text">
                        <td><span><?php echo date('d/m/Y')?></span></td>
                        <td><span>23233</span></td>
                        <td><span>Blur text Css</span></td>
                        <td align="right"><?php echo $this->currency->format(0); ?></td>
                        <td align="right"><?php echo $this->currency->format(0); ?></td>
                        <td align="right"><?php echo $this->currency->format(0); ?></td>
                      </tr>
                      <tr class="blurry-text">
                        <td><span><?php echo date('d/m/Y')?></span></td>
                        <td><span>23233</span></td>
                        <td><span>Blur text Css</span></td>
                        <td align="right"><?php echo $this->currency->format(0); ?></td>
                        <td align="right"><?php echo $this->currency->format(0); ?></td>
                        <td align="right"><?php echo $this->currency->format(0); ?></td>
                      </tr>
                      <tr class="blurry-text">
                        <td><span><?php echo date('d/m/Y')?></span></td>
                        <td><span>23233</span></td>
                        <td><span>Blur text Css</span></td>
                        <td align="right"><?php echo $this->currency->format(0); ?></td>
                        <td align="right"><?php echo $this->currency->format(0); ?></td>
                        <td align="right"><?php echo $this->currency->format(0); ?></td>
                      </tr>
                      <tr>
                        <td colspan="6" align="center"><h3>Tampilan Data Dibatasi. Silahkan download format excel</h3></td>
                      </tr>
                      <?php } ?>
                      <style>
                        .blurry-text {
                          font:bold 14px Helvetica, Arial, Sans-Serif;
                          text-shadow:0 0 5px rgba(0,0,0,0.4);
                          color: transparent;
                        }
                      </style>
                      <tr>
                        <td colspan="2"><b>Saldo Awal</b></td>
                        <td><?php echo $this->currency->format($saldoawal); ?></td>
                      </tr>
                      <tr>
                        <td colspan="2"><b>Total Debet</b></td>
                        <td><?php echo $this->currency->format($alldebet); ?></td>
                      </tr>
                      <tr>
                        <td colspan="2"><b>Total Kredit</b></td>
                        <td><?php echo $this->currency->format($allkredit); ?></td>
                      </tr>
                      <tr>
                        <td colspan="2"><b>Total Saldo</b></td>
                        <td><?php echo $this->currency->format($saldoawal+$alldebet-$allkredit); ?></td>
                      </tr>
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
$('.sidebar-menu').find('#menu-laporan').addClass('active');
$('.sidebar-menu').find('#menu-buku-besar').addClass('active');



</script>
<script type="text/javascript"><!--
$(document).ready(function() {
	$('#date-start').datepicker({dateFormat: 'yy-mm-dd'});

	$('#date-end').datepicker({dateFormat: 'yy-mm-dd'});
});
//--></script>
<script type="text/javascript"><!--
function filter() {
	url = 'index.php?route=keuangan/bukubesar&token=<?php echo $token; ?>&downloadexcel=1';

	var filter_date_start = $('input[name=\'filter_date_start\']').val();

	if (filter_date_start) {
		url += '&filter_date_start=' + encodeURIComponent(filter_date_start);
	}

  var filter_date_end = $('input[name=\'filter_date_end\']').val();

	if (filter_date_end) {
		url += '&filter_date_end=' + encodeURIComponent(filter_date_end);
	}
  var filter_jenis = $('select[name=\'filter_jenis\']').val();

	if (filter_jenis > 0) {
		url += '&filter_jenis=' + encodeURIComponent(filter_jenis);
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
