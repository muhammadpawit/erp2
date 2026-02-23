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
            <h3 class="box-title">Penyesuaian Kurs</h3>
            <div class="button pull-right">
              <a onclick="$('#form').submit()" ><button type="button" class="btn btn-primary">Simpan</button></a>
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
                  <?php foreach($error_warning as $e){
                    echo $e.'<br>';
                  } ?>
                </div>
                <?php
                }
                ?>
              </div>
            </div>
            <div class="row">
              <div class="col-md-12">
                <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
                  <table class="table">
                    <tr>
                       <td>Tanggal Awal</td>
                       <td>
                         <input type="text" name="tglawal" class="date form-control" readonly value="" >
                       </td>
                     </tr>
                     <tr>
                        <td>Tanggal Akhir</td>
                        <td>
                          <input type="text" name="tglakhir" class="date form-control" readonly value="" >
                        </td>
                      </tr>

                     <tr>
                         <td>Kurs Tengah BI</td>
                         <td><input type="text" name="kursbi" class="form-control" value="" required></td>
                     </tr>
                     <tr>
                         <td>Kurs KMK</td>
                         <td><input type="text" name="kurskmk" class="form-control" value="" required></td>
                     </tr>
                    </table>

                </form>
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
$('.sidebar-menu').find('#menu-pembelian').addClass('active');
$('.sidebar-menu').find('#menu-pembelian-kredit').addClass('active');

</script>

<script type="text/javascript"><!--
$(document).ready(function() {
	$('.date').datepicker({dateFormat: 'yy-mm-dd'});

});

//--></script>

<?php echo $footer; ?>
