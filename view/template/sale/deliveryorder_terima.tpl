<?php echo $header; ?>
<div class="content-wrapper" id="content">
  <section class="content-header">
    <h1>

    </h1>

  </section>

  <section class="content" >
      <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
    <div class="row">
      <div class="col-md-12">
        <div class="box">
          <div class="box-header with-border">
            <h3 class="box-title">Delivery Order</h3>
            <div class="button pull-right">
              <a onclick="simpan()" ><button type="button" class="btn btn-primary">Simpan</button></a>
                <a href="<?php echo $cancel; ?>"><button type="button" class="btn btn-danger">Kembali</button></a>

								</div>
          </div>
          <div class="box-body">

            <div class="row">
              <div class="col-md-12">
                <div class="errordisplay"></div>
                <?php if ($error_warning) { ?>
                <div class="alert alert-danger alert-dismissible">
                  <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                  <h4><i class="icon fa fa-ban"></i> Alert!</h4>
                  <?php echo $error_warning; ?>
                </div>
                <?php
                }
                ?>
              </div>
            </div>
            <div class="row">


                <div class="col-md-6">
                  <table class="table">
                    <tr>
                          <td>No. DO</td>
                          <td>
                            <?php echo $order['no_do']; ?>

                          </td>
                      </tr>
                     <tr>
                          <td>Gudang</td>
                          <td>
                            <?php echo $order['nama']; ?>

                          </td>
                      </tr>
                     <tr>
                       <td>Customer</td>
                       <td>
                         <?php echo $order['name']; ?>
                       </td>
                     </tr>
                     <tr>
                         <td>Tanggal Kirim</td>
                         <td>
                           <?php echo date('d/m/y',strtotime($order['date_added']))?>
                         </td>
                     </tr>
                     <tr>
                         <td>Total Tabung Dikirim</td>
                         <td>
                           <?php echo $order['totaltabung']; ?>
                         </td>
                     </tr>
                     
                     
                      
                   </table>
                </div>
                <div class="col-md-6">
                  <table class="table">
                    <tr>
                          <td>Tanggal Terima</td>
                          <td>
                            <input type="text" class="date form-control" name="tglterima" value="<?php echo date('Y-m-d'); ?>" readonly>
                        
                          </td>
                      </tr>
                     <tr>
                          <td>Penerima</td>
                          <td>
                           <input type="text" class="form-control" name="penerima" value="" >
                        

                          </td>
                      </tr>
                      <tr>
                          <td>Keterangan</td>
                          <td>
                           <input type="text" class="form-control" name="keteranganpenerima" value="" >
                        

                          </td>
                      </tr>
                      <tr>
                         <td>Total Tabung Diterima</td>
                         <td>
                           <input type="text" class="form-control" name="totaltabungterima" value="0" readonly>
                         </td>
                     </tr>
                     
                      
                   </table>
                </div>
              </div>
              <div class="row">
                <div class="col-md-12">
     
                    <table class="table" id="list-tabung">
                        <thead>
                            <tr>
                                <th>No. Tabung</th>
                                <th>Keterangan</th>
                                <th>Status</th>
                            
                            </tr>
                        </thead>
                    
                    <tbody>
                        <?php
                            $tabung_row=0;
                            foreach($tabungs as $p){
                            ?>
                            <tr>
                            <td>
                                <?php echo $p['no_tabung']; ?>
                                <input type="hidden" name="tabung[<?php echo $tabung_row; ?>][id]" value="<?php echo $p['id'];?>">
                                
                            </td>
                            <td>
                                <?php echo $p['keterangan']; ?><br>
                                
                            </td>
                            <td>
                            <select onchange="updatetotal()" name="tabung[<?php echo $tabung_row; ?>][status]" class="form-control">
                                <option value="1">Belum Diterima</option>
                                 <option value="2">Diterima</option>
                                 <option value="3">Diretur</option>
                            </select>
                               
                            </td>

                            </tr>
                            <?php
                            $tabung_row++;
                            }
                            ?>
                    </tbody>
                    <tfoot>
                        <tr>
                        </tr>

                    </tfoot>
                    </table>
                        
                

              </div>
            </div>


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
      </form>
  </section>
</div>
</div>
<script>
$('.sidebar-menu').find('#menu-penjualan').addClass('active');
$('.sidebar-menu').find('#menu-penjualan-website').addClass('active');
$('.sidebar-menu').find('#menu-penjualan-detailorder').addClass('active');

</script>

<script type="text/javascript" src="view/javascript/jquery/ui/jquery-ui-timepicker-addon.js"></script>
<script type="text/javascript"><!--
$('.date').datepicker({dateFormat: 'yy-mm-dd'});
</script>
<script>
var tabung_row=<?php echo $tabung_row; ?>;
function updatetotal(){
  totaltabung=0;
  for(i=0;i<tabung_row;i++){
    tid=$("input[name='tabung["+i+"][id]']").val();
    
    if(tid != undefined){
      status=$("select[name='tabung["+i+"][status]']").val();
      
      if(status == 2){
        totaltabung+=1;
      }
    }


  }
  $("input[name='totaltabungterima']").val(totaltabung);
}

function simpan(){
  $('#form').submit();
}
</script>
<?php echo $footer; ?>
