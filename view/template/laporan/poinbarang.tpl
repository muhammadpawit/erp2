<?php echo $header; ?>
<div class="content-wrapper">
  <section class="content-header">
    <h1>
        
    </h1>

  </section>
<!-- Modal -->
<div id="myModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Tambah Barang Baru</h4>
      </div>
      <div class="modal-body">
        <form method="POST" action="<?php echo $simpan ?>">
              <input type="hidden" name="add" value="1">
              <div class="row">
                <div class="col-md-12">
                  <div class="form-group">
                    <label>Kode Barang</label>
                    <input type="text" name="kodebarang" class="form-control" required="required">
                  </div>
                </div>
                <div class="col-md-12">
                  <div class="form-group">
                    <label>Nama Barang</label>
                    <input type="text" name="nama" class="form-control" required="required">
                  </div>
                </div>
                <div class="col-md-12">
                  <div class="form-group">
                    <label>Tambah</label><br>
                    <button type="submit" class="btn btn-success btn-sm" style="'width: 50%">Simpan</button>
                  </div>
                </div>
              </div>
            </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>
  <section class="content">
    <div class="row">
      <div class="col-md-12">
        <div class="box">
          <div class="box-header with-border">
            <h3 class="box-title">Master Poin</h3>
          </div>
          <div class="box-body">
            <div class="row">
              <div class="col-md-4">
                  <div class="form-group">
                    <label>Kode Barang</label>
                    <input type="text" name="kodebarangc" value="<?php echo $filter_date_start?>" class="form-control"/>
                  </div>
              </div>
              <div class="col-md-4">
                  <div class="form-group">
                    <label>Nama Barang</label>
                    <input type="text" name="namac" value="<?php echo $filter_date_end?>" class="form-control"/>
                  </div>
              </div>
              <div class="col-md-4">
                  <div class="form-group">
                    <label>Action</label><br>
                    <a onClick="filterpoin()" class="btn btn-warning"><i class="fa fa-search"></i></a>
                    <button type="button" class="btn btn-info btn-sm" data-toggle="modal" data-target="#myModal">Tambah</button>
                  </div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-12">
                <?php if ($error_warning) { ?>
                <div class="alert alert-danger alert-dismissible">
                  <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                  <h4><i class="icon fa fa-ban"></i> Alert!</h4>
                  <?php echo $error_warning; ?>
                </div>
                <?php
                }
                ?>
                <?php if ($success) { ?>
                <div class="alert alert-success alert-dismissible">
                  <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                  <h4><i class="icon fa fa-check"></i> Success!</h4>
                  <?php echo $success; ?>
                </div>
                <?php
                }
                ?>
              </div>
            </div>
            
            <div class="content">
             <table class="table table-hover table-bordered">
               <thead>
                 <tr>
                   <th>ID</th>
                   <th>Kode Barang</th>
                   <th>Nama</th>
                   <th>Poin</th>
                   <th>Update</th>
                   <th>Delete</th>
                 </tr>
               </thead>
               <tbody>
                 <?php foreach($prods as $p){?>
                  
                    <tr>
                      <td><?php echo $p['id']?></td>
                      <td><?php echo $p['kodebarang']?></td>
                      <td><?php echo $p['nama']?></td>
                      <form method="post" action="<?php echo $simpan ?>">                      
                      <td>
                        <input type="text" name="poin" value="<?php echo $p['poin']?>">
                      </td>
                      <td>
                           <input type="hidden" name="id" value="<?php echo $p['id']?>">
                          <button type="submit" class="btn btn-success btn-sm">Update</button>
                      </td>
                      </form>
                      <td>
                        <form method="post" action="<?php echo $simpan ?>">
                           <input type="hidden" name="delete" value="<?php echo $p['id']?>">
                           <input type="hidden" name="id" value="<?php echo $p['id']?>">
                           <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                        </form>
                      </td>
                    </tr>
                 <?php } ?>
               </tbody>
             </table>
            </div>

          </div>
        </div>
      </div>
    </div>
  </section>
</div>
</div>
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.20/css/jquery.dataTables.css">
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.js"></script>
<script>
$(document).ready( function () {
  $('.datepicker').datepicker({dateFormat: 'yy-mm-dd'});
    $('#myTable').DataTable( {
        "lengthChange": false,
        "bPaginate": false,
        "bFilter": false,
    } );
} );
</script>
<script>
$('.sidebar-menu').find('#menu-laporan').addClass('active');
function show(id){
  //alert(id);
   $('.collapse'+id).show();
   $('#plus'+id).hide();
   $('#minus'+id).show();
}
function hide(id){
  //alert(id);
   $('.collapse'+id).hide();
   $('#minus'+id).hide();
   $('#plus'+id).show();
}
$(function(){
  $('.date').datepicker({dateFormat: 'yy-mm-dd'});
  $('.list-product').hide();
  $('.total-transaksi').hide();

  $(".invoice").on('click',function(){
    id=$(this).data('id');
    faktur=$(this).data('faktur');

    $("#display-faktur").html(faktur);
    $(".invoice td").css('background-color','#fff');
    $(".invoice td").css('font-weight','normal');
    //$("#list-invoice-"+id+" td").css('background-color','#ccc');
    $("#list-invoice-"+id+" td").css('font-weight','bold');

    $('.list-product').hide();
    $('.total-transaksi').hide();

    $("#list"+id).show();
    $("#total"+id).show();
  });
});
$(".select").select2({
    //width: 'resolve' // need to override the changed default
    theme:"bootstrap"
});
$(".sales").select2({
    ajax: {
    url:"index.php?route=user/user/autocomplete&token=<?php echo $this->request->get['token']; ?>",
      dataType: 'json',
    data: function (params) {
      return {
        q: params.term,
        j:21

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
  });
</script>
<script type="text/javascript"><!--
function filterpoin() {
	url = "index.php?route=laporan/importkomisisales/poinbarang&token=<?php echo $token; ?>";


  var filter_date_start = $('input[name=\'kodebarangc\']').val();

	if (filter_date_start) {
		url += '&kodebarang=' + encodeURIComponent(filter_date_start);
	}

  var filter_date_end = $('input[name=\'namac\']').val();

	if (filter_date_end) {
		url += '&nama=' + encodeURIComponent(filter_date_end);
	}


	location = url;
}
function cetak() {
	//url = "index.php?route=laporan/penjualandetail&print=1&token=<?php echo $token; ?>";
  url ="<?php echo $cetak ?>";

	var filter_customer_id = $('select[name=\'filter_customer_id\']').val();

	if (filter_customer_id != '*') {
		url += '&filter_customer_id=' + encodeURIComponent(filter_customer_id);
	}

  var filter_gudang_id = $('select[name=\'filter_gudang_id\']').val();

	if (filter_gudang_id != '*') {
		url += '&filter_gudang_id=' + encodeURIComponent(filter_gudang_id);
	}

  /*  var filter_status= $('select[name=\'filter_status\']').val();

	if (filter_status != '*') {
		url += '&filter_status=' + encodeURIComponent(filter_status);
	}*/

  var filter_date_start = $('input[name=\'filter_date_start\']').val();

	if (filter_date_start) {
		url += '&filter_date_start=' + encodeURIComponent(filter_date_start);
	}

  var filter_date_end = $('input[name=\'filter_date_end\']').val();

	if (filter_date_end) {
		url += '&filter_date_end=' + encodeURIComponent(filter_date_end);
	}

  var filter_statuss = $("#status input:checkbox:checked").map(function(){
      return $(this).val();
    }).get(); // <----

    //alert(JSON.stringify(filter_statuss));
    url+='&filter_status=' +filter_statuss;

  /*var filter_shipping_method = $('select[name=\'filter_shipping_method\']').val();

	if (filter_shipping_method != '*') {
		url += '&filter_shipping_method=' + encodeURIComponent(filter_shipping_method);
	}
*/


  window.open(
  url,
  '_blank' // <- This is what makes it open in a new window.
  );
}
$(function(){
  $(".select-ads").select2({


      theme:"bootstrap"
    });
  $(".lokasi-pameran").select2({
    ajax: {
    url:"index.php?route=sale/customer/autocomplete&token=<?php echo $token; ?>",
    //url: "index.php?route=pamerantoko/toko/autocomplete&token=<?php echo $this->request->get['token']; ?>",
    dataType: 'json',
    data: function (params) {
      return {
        q: params.term, // search term

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
  });

  $(".salesorder").select2({
    ajax: {
    url:"index.php?route=sale/invoice/autocomplete&token=<?php echo $token; ?>",
    //url: "index.php?route=pamerantoko/toko/autocomplete&token=<?php echo $this->request->get['token']; ?>",
    dataType: 'json',
    data: function (params) {
      return {
        q: params.term, // search term

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
  });

  $(".jenisorder").select2({
    ajax: {
    url:"index.php?route=catalog/product/autocompleteprod&token=<?php echo $this->request->get['token']; ?>",
      dataType: 'json',
    data: function (params) {
      return {
        q: params.term

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
  });
})
//--></script>
<script type="text/javascript"><!--
$('#form input').keydown(function(e) {
	if (e.keyCode == 13) {
		filter();
	}
});
//--></script>
<script type="text/javascript"><!--
$('input[name=\'filter_name\']').autocomplete({
	delay: 0,
	source: function(request, response) {
		$.ajax({
			url: 'index.php?route=catalog/atk/autocomplete&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request.term),
			dataType: 'json',
			success: function(json) {
				response($.map(json, function(item) {
					return {
						label: item.nama,
						value: item.atk_id
					}
				}));
			}
		});
	},
	select: function(event, ui) {
		$('input[name=\'filter_name\']').val(ui.item.label);

		return false;
	},
	focus: function(event, ui) {
      	return false;
   	}
});


//--></script>
<script>
function detail(id,faktur){
  //alert(id);
  $("#display-faktur").html(faktur);
  $(".invoice td").css('background-color','#fff');
  $(".invoice td").css('font-weight','normal');
  $("#list-invoice-"+id+" td").css('background-color','#ccc');
  $("#list-invoice-"+id+" td").css('font-weight','bold');

  $('.list-product').hide();
  $('.total-transaksi').hide();

  $("#list"+id).show();
  $("#total"+id).show();
}
</script>
<?php echo $footer; ?>
