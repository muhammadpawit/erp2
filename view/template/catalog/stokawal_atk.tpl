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
            <h3 class="box-title">Input Stok Awal ATK</h3>
            <div class="button pull-right">
                <a onclick="$('#form').submit();" ><button type="button" class="btn btn-primary">Simpan</button></a>
									<a href="<?php echo $cancel; ?>"><button type="button" class="btn btn-danger">Kembali</button></a>

								</div>
          </div>
          <div class="box-body">
						<div class="row">
              <div class="col-md-12">
								<?php if ($error) { ?>

                <div class="alert alert-danger alert-dismissible">
                  <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                  <h4><i class="icon fa fa-ban"></i> Alert!</h4>
									<?php
									foreach($error as $e){
						  		?>
						  			<p><?php echo $e; ?></p>
						  		<?php
						  		}
						  		?>
                </div>
                <?php
                }
                ?>


              </div>
            </div>
            <div class="row">
              <div class="col-md-12">
                <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
                  <table id="options" class="table table-stripped" style="max-width:550px;">
										<tr>
						              <td >Nama ATK</td>
						              <td><?php echo $productdesc['nama']?>
						              		<input type="hidden" name="atk_id" value="<?php echo $atk_id?>">
						              		<input type="hidden" name="product_name" value="<?php echo $productdesc['nama']?>">

						              </td>
						          </tr>

						          <tr>
						              <td>Quantity</td>
						              <td>
						              		<input class="form-control" type="text" name="qty" value="">

						              </td>
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
$('.sidebar-menu').find('#menu-persediaan').addClass('active');
$('.sidebar-menu').find('#menu-atk').addClass('active');
</script>
</script>
<?php echo $footer; ?>
