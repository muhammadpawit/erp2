<?php
echo $header;
?>
<div class="content-wrapper">
  <section class="content-header">
    <h1>

    </h1>
  
  </section>

  <section class="content">
    <?php if ($success) { ?>
    <div class="alert alert-success"><i class="fa fa-check-circle"></i> <?php echo $success; ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>
    <?php if ($error) { ?>
    <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error; ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>
    <div class="row">
      <div class="col-md-12">
        <div class="box">
          <div class="box-header with-border">
            <h3 class="box-title">Title</h3>
          </div>
          <div class="box-body">
            <div class="row">
              <div class="col-md-12">

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
<?php
echo $footer;
?>
