<?php
echo $header;
?>
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
            <h3 class="box-title">Laporan Stok</h3>
            <div class="button pull-right">
									</div>
          </div>
          <div class="box-body">


            <div class="row">
              <div class="col-md-12">
                <div class="callout callout-success lead">
                  <h4>Data Stok Gudang</h4>

                </div>
                <?php //if($this->user->getName()=="Pawit"){ ?>
                <table class="table table-bordered">
                  <thead>
                    <tr>
                        <th class="center"></th>
                        <th class="center">Qty Tersimpan</th>
                        <th class="center">Qty Proses Transfer</th>
                        <th class="center">Harga Nett</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php if ($product) { ?>


                    <tr>
                        <td colspan="8" class="left"><i><b>Data Stok Gudang</b></i></td>

                    </tr>
                    <?php
                      $jual=0;
                      $transfer=0;
                      $kirim=0;
                      $i=1;
                        foreach($product['datagudang'] as $d){
                        ?>
                      <tr>

                        <td class="left"><a href="index.php?route=catalog/productgudang&token=<?php echo $token; ?>&filter_gudang_id=<?php echo $d['id']; ?>" target="_blank"><?php echo $d['nama']; ?></a></td>
                        <td class="left"><?php echo $d['qty']; ?></td>
                        <td class="left"><?php echo $d['qtyprosestransfer']; ?></td>
                        <td class="left"><?php echo $d['net_cost']; ?></td>
                      </tr>
                      <?php
                          $jual+=$d['qtyprosesjual'];
                          $transfer+=$d['qtyprosestransfer'];
                          $kirim+=$d['qtyproseskirim'];
                        }
                        ?>
                    <tr>
                        <td class="left"><b>Total</b></td>
                        <td class="left"><?php echo $product['totalgudang']; ?></td>
                      <td><?php echo $transfer; ?></td>
                        <td class="left"><?php echo $product['totalhnetgudang']; ?></td>
                      </tr>
                  </tbody>
                  </table>
                  
                    <?php } else { ?>
                    <tr>
                      <td class="center" colspan="4"><?php echo $text_no_results; ?></td>
                    </tr>
                    <?php } ?>
                  </tbody>
                  </table>
                  <?php //} ?>
                  <table class="table table-bordered">
                    <thead>
                      <tr>
                        <th class="center"></th>
                        <th class="center">Qty Tersimpan</th>
                        <th class="center">Harga Nett</th>
                        <th class="center">Qty Proses Transfer</th>
                        <th class="center">Harga Nett</th>
                        <th class="center">Total Harga Nett</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr>
                          <td colspan="6" class="left"><i><b>Data Rincian Produk Stok Gudang</b></i></td>

                      </tr>                      
                      <tr class="accordion-toggle" data-toggle="collapse" data-target="#12345-cores">
                        <td id="showsby" style="visibility: visible;"><a onclick="showsby()">Surabaya</a></td>
                        <td id="hidesby" style="display: none;"><a onclick="hidesby()">Surabaya</a></td>
                        <td></td>
                        <td><?php echo $totalnetsby ?></td>
                        <td><?php echo $qtyprosestransfersby ?></td>
                        <td>0</td>
                        <td><?php echo $totalnetsby ?></td>
                      </tr>
                       <?php //echo count($prodtgr);?>
                      <?php for($i=0; $i<count($prodsby);$i++){?>
                      <tr class="sby" style="display: none;">
                        <td><?php echo $prodsby[$i]['name']?></td>
                        <td><?php echo $prodsby[$i]['quantity']?><span class="pull-right"><small>pcs</small></span></td>
                        <td class="right"><?php echo $this->currency->format($prodsby[$i]['net_cost'])?></td>
                        <td>0</td>
                        <td>0</td>
                        <td><?php echo $this->currency->format($prodsby[$i]['net_cost']*$prodsby[$i]['quantity'])?></td>
                      </tr>
                      <?php } ?>
                      <tr>
                        <td>Total Surabaya</td>
                        <td><?php echo $qtysby ?></td>
                      </tr>                      
                      <tr class="accordion-toggle" data-toggle="collapse" data-target="#98765-cores">
                        <td id="showtgr" style="visibility: visible;"><a onclick="showtgr()">Tangerang</a></td>
                        <td id="hidetgr" style="display: none;"><a onclick="hidetgr()">Tangerang</a></td>
                        <td></td>
                        <td><?php echo $totalnettgr ?></td>
                        <td><?php echo $qtyprosestransfertgr ?></td>
                        <td>0</td>
                        <td><?php echo $totalnettgr ?></td>
                      </tr>
                      <?php for($i=0; $i<count($prodtgr);$i++){?>
                      <tr class="tgr" style="display: none;">
                        <td><?php echo $prodtgr[$i]['name']?></td>
                        <td><?php echo $prodtgr[$i]['quantity']?><span class="pull-right"><small>pcs</small></span></td>
                        <td class="right"><?php echo $this->currency->format($prodtgr[$i]['net_cost'])?></td>
                        <td>0</td>
                        <td>0</td>
                        <td><?php echo $this->currency->format($prodtgr[$i]['net_cost']*$prodtgr[$i]['quantity'])?></td>
                      </tr>
                      <?php } ?>
                      <tr>
                        <td>Total Tangerang</td>
                        <td><?php echo $qtytgr ?></td>
                      </tr>                      
                      <tr class="accordion-toggle" data-toggle="collapse" data-target="#70235-cores">
                        <td><b>Total</b></td>
                        <!--<td><?php echo $totalgudang ?></td>-->
                        <td><?php echo $product['totalqtysbyjkt'] ?></td>
                        <td><?php echo $product['totalnetsbyjkt']; ?></td>
                        <td></td>
                        <td></td>
                        <td><?php echo $product['totalnetsbyjkt']; ?></td>
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
$('.sidebar-menu').find('#menu-laporan').addClass('active');
$('.sidebar-menu').find('#menu-laporan-stok').addClass('active');

function showtgr(){
  $('.tgr').fadeIn();
  $('#hidetgr').fadeIn();
  $('#showtgr').hide();
}
function hidetgr(){
  $('.tgr').fadeOut();
  $('#hidetgr').hide();
  $('#showtgr').fadeIn();
}
function showsby(){
  $('.sby').fadeIn();
  $('#hidesby').fadeIn();
  $('#showsby').hide();
}
function hidesby(){
  $('.sby').fadeOut();
  $('#hidesby').hide();
  $('#showsby').fadeIn();
}
</script>
<?php
echo $footer;
?>
