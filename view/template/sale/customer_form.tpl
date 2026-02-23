<?php echo $header; ?>
<div id="content" class="content-wrapper">
  <section class="content-header">
    <h1>

    </h1>

  </section>

  <section class="content">
    <div class="row">
      <div class="col-md-12">
        <div class="box">
          <div class="box-header with-border">
            <h3 class="box-title">Customer</h3>
            <div class="button pull-right">
                    <a onclick="$('#form').submit();" class="btn btn-info">Simpan</a>
                    <a onclick="location = '<?php echo $cancel; ?>';" class="btn btn-danger">Kembali</a>
						</div>
          </div>
          <div class="box-body">
            <div class="row">
              <div class="col-md-12">

                <?php if ($success) { ?>
                <div class="alert alert-success alert-dismissible">
                  <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                  <h4><i class="icon fa fa-check"></i> Success!</h4>
                  <?php echo $success; ?>
                </div>
                <?php
                }
                ?>

                <?php if ($error) { ?>
                <div class="alert alert-danger alert-dismissible">
                  <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                  <h4><i class="icon fa fa-check"></i> Alert!</h4>
                  <?php
                  foreach($error as $e){
                   echo $e."<br>";
                 }
                   ?>
                </div>
                <?php
                }
                ?>
              </div>
            </div>

            <div class="row">

                <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
                  <input type="hidden" name="salesold" value="<?php echo $sales ?>"/>
                  <div class="col-md-6">
                  <table class="table">
                    <tr>
                         <td>Sales </td>
                         <td>
                           <?php
                        if(isset($this->request->get['id'])){
                             $this->load->model('user/user');
                             $s=$this->model_user_user->getUser($sales);
                             echo $s['firstname'];
                           }else{
                           ?>
                           <select name="sales" class="sales form-control">

                             </select>
                            <?php
                          }
                            ?>
                         </td>
                     </tr>
                     <?php
                    if(isset($this->request->get['id'])){
                      ?>
                      <tr>
                           <td>Ubah Sales</td>
                           <td>

                             <select name="sales" class="sales form-control">

                               </select>

                           </td>
                       </tr>
                      <?php
                     }

                     ?>
                     <?php
                    /* if($this->user->getGroupId() != 21){
                     if(isset($this->request->get['id'])){
                      ?>
                      <tr>
                           <td>Ubah Sales</td>
                           <td>

                             <select name="sales" class="sales form-control">

                               </select>

                           </td>
                       </tr>
                      <?php
                     }
                   }*/
                     ?>
                    <tr>
                      <td>Customer Group (baru)</td>
                      <td><select class="form-control" name="customer_group_id">
                          <?php foreach ($customer_groups as $customer_group) { ?>
                          <?php if ($customer_group['customer_group_id'] == $customer_group_id) { ?>
                          <option value="<?php echo $customer_group['customer_group_id']; ?>" selected="selected"><?php echo $customer_group['name']; ?></option>
                          <?php } else { ?>
                          <option value="<?php echo $customer_group['customer_group_id']; ?>"><?php echo $customer_group['name']; ?></option>
                          <?php } ?>
                          <?php } ?>
                        </select></td>
                    </tr>
                    <tr>
                      <td>Area</td>
                      <td><select class="form-control" name="area">
                          <?php foreach ($areas as $title) { ?>
                          <?php if ($title['id'] == $area) { ?>
                          <option value="<?php echo $title['id']; ?>" selected="selected"><?php echo $title['name']; ?></option>
                          <?php } else { ?>
                          <option value="<?php echo $title['id']; ?>"><?php echo $title['name']; ?></option>
                          <?php } ?>
                          <?php } ?>
                        </select></td>
                    </tr>
                    <tr>
                      <td>Sapaan/Title</td>
                      <td><select class="form-control" name="title">
                          <?php foreach ($titles as $t) { ?>
                          <?php if ($t['id'] == $sapaan) { ?>
                          <option value="<?php echo $title['id']; ?>" selected="selected"><?php echo $t['name']; ?></option>
                          <?php } else { ?>
                          <option value="<?php echo $t['id']; ?>"><?php echo $t['name']; ?></option>
                          <?php } ?>
                          <?php } ?>
                        </select></td>
                    </tr>
                    <tr>
                      <td valign="top"><span class="required">*</span> Nama Customer<br><small>(akan ditampilkan di SJ)</small></td>
                      <td><input class="form-control" type="text" name="name" value="<?php echo $name; ?>" />
                        <?php if ($error_firstname) { ?>
                        <span class="error"><?php echo $error_firstname; ?></span>
                        <?php } ?></td>
                    </tr>
                    <tr>
                      <td><span>Nama PIC</span></td>
                      <td><small>Nama Depan </small>
                        <input type="text" name="namapicdepantengah" value="<?php echo $namapicdepantengah; ?>" placeholder="Nama depan tengah" class="form-control">
                        <small>Nama Belakang</small>
                        <input type="text" name="namapicbelakang" value="<?php echo $namapicbelakang; ?>" placeholder="Nama belakang" class="form-control">
                      </td>
                    </tr>
                    <tr>
                      <td>Email</td>
                      <td><input class="form-control" type="text" name="email" value="<?php echo $email; ?>" /></td>
                    </tr>
                    <tr>
                      <td>Fax</td>
                      <td><input class="form-control" type="text" name="fax" value="<?php echo $fax; ?>" /></td>
                    </tr>

                    <tr>
                      <td>Alamat<br><small>(akan ditampilkan di SJ)</small></td>
                      <td>
                        <textarea class="form-control " name="alamat"><?php echo $alamat; ?></textarea>

                        </td>
                    </tr>
                    <tr>
                      <td><span class="required">*</span> Propinsi </td>
                      <td><select class="form-control" name="country" onchange="countrys(this, '<?php echo $zone; ?>');">
                          <?php foreach ($countries as $country) { ?>
                          <?php if ($country['country_id'] == $countrys) { ?>
                          <option value="<?php echo $country['country_id']; ?>" selected="selected"><?php echo $country['name']; ?></option>
                          <?php } else { ?>
                          <option value="<?php echo $country['country_id']; ?>"><?php echo $country['name']; ?></option>
                          <?php } ?>
                          <?php } ?>
                        </select>
                      </td>
                    </tr>
                    <!-- tokocepat -->
                    <tr>
                      <td><span class="required">*</span> Kota/Kabupaten</td>
                      <td><select class="form-control" name="zone" onchange="zones(this, '<?php echo $city; ?>');">
                          <option value=""><?php echo $text_select; ?></option>
                          <?php foreach ($zones as $zone) { ?>
                          <?php if ($zone['zone_id'] == $zone) { ?>
                          <option value="<?php echo $zone['zone_id']; ?>" selected="selected"><?php echo $zone['name']; ?></option>
                          <?php } else { ?>
                          <option value="<?php echo $zone['zone_id']; ?>"><?php echo $zone['name']; ?></option>
                          <?php } ?>
                          <?php } ?>
                        </select>
                        </td>
                    </tr>
                    <tr>
                      <td><span class="required">*</span> Kecamatan</td>
                      <td><select class="form-control" name="city">
                          <option value=""><?php echo $text_select; ?></option>
                          <?php foreach ($cities as $city) { ?>
                          <?php if ($city['city_id'] == $city) { ?>
                          <option value="<?php echo $city['city_id']; ?>" selected="selected"><?php echo $city['name']; ?></option>
                          <?php } else { ?>
                          <option value="<?php echo $city['city_id']; ?>"><?php echo $city['name']; ?></option>
                          <?php } ?>
                          <?php } ?>
                        </select>
                        </td>
                    </tr>
                    <tr>
                      <td>*Kode Pos</td>
                      <td><input class="form-control" type="text" name="kodepos" value="<?php echo $kodepos; ?>" />
                        </td>
                    </tr>
                    <tr>
                      <td>Telephone</td>
                      <td><input class="form-control" type="text" name="telephone" value="<?php echo $telephone; ?>" />
                        </td>
                    </tr>
                    <tr>
                      <td>Nomor SIUP</td>
                      <td><input class="form-control" type="text" name="siup" value="<?php echo $siup; ?>" />
                        </td>
                    </tr>

                    <tr>
                      <td>Kadaluwarsa SIUP</td>
                      <td><input class="form-control date" readonly type="text" name="siup_expire" value="<?php echo $siup_expire; ?>" />
                        </td>
                    </tr>
                    <tr>
                      <td>Nomor TDP</td>
                      <td><input class="form-control" type="text" name="tdp" value="<?php echo $tdp; ?>" />
                        </td>
                    </tr>

                    <tr>
                      <td>Kadaluwarsa TDP</td>
                      <td><input class="form-control date" readonly type="text" name="tdp_expire" value="<?php echo $tdp_expire; ?>" />
                        </td>
                    </tr>
                    <tr>
                      <!-- <td>Limit Piutang</td> -->
                      <!-- <td><input class="form-control" type="hidden" name="limit_piutang" value="<?php echo $limit_piutang; ?>" /></td> -->
                      <?php if(isset($_REQUEST['limit'])){ ?>
                        <td>Limit Piutang</td>
                        <td><input class="form-control" type="text" name="limit_piutang" value="<?php echo $limit_piutang; ?>" /></td>
                      <?php } ?>
                    </tr>
                    <input class="form-control" type="hidden" name="limit_piutang" value="0" />
                  </table>
                  </div>
                  <div class="col-md-6">
                    <table class="table table-bordered" style="border:2px solid #000">
                      <tr>
                        <td> Nama NPWP</td>
                        <td><input class="form-control" type="text" name="namanpwp" value="<?php echo $namanpwp; ?>" />
                          </td>
                      </tr>
                      <tr>
                        <td>Alamat NPWP</td>
                        <td><textarea name="alamatnpwp" class="form-control"><?php echo $alamatnpwp; ?></textarea>
                          </td>
                      </tr>
                      <tr>
                        <td> Nomor NPWP</td>
                        <td><input class="form-control" type="text" name="npwp" value="<?php echo $npwp; ?>" />
                          </td>
                      </tr>

                      <tr>
                        <td> Nama KTP</td>
                        <td><input class="form-control" type="text" name="namaktp" value="<?php echo $namaktp; ?>" />
                          </td>
                      </tr>
                      <tr>
                        <td>Alamat KTP</td>
                        <td><textarea name="alamatktp" class="form-control"><?php echo $alamatktp; ?></textarea>
                          </td>
                      </tr>
                      <tr>
                        <td> Nomor KTP</td>
                        <td><input class="form-control" type="text" name="noktp" value="<?php echo $noktp; ?>" />
                          </td>
                      </tr>
                    </table>
                    <table class="table">
                      <tr>
                        <td><span class="required">*</span> Nama Pemilik/Penanggungjawab</td>
                        <td><input class="form-control" type="text" name="nama_pemilik" value="<?php echo $nama_pemilik; ?>" />
                          </td>
                      </tr>
                      <tr>
	                    <td>Telephone </td>
	                    <td><input class="form-control" type="number" name="telephone2" value="<?php echo $telephone2; ?>" />
	                    </td>
	                  </tr>
                      <tr>
                        <td>Telp. Pemilik</td>
                        <td><input class="form-control" type="text" name="telp_pemilik" value="<?php echo $telp_pemilik; ?>" /></td>
                      </tr>
                      <tr>
                        <td>No. HP Pemilik</td>
                        <td><input class="form-control" type="text" name="hp_pemilik" value="<?php echo $hp_pemilik; ?>" /></td>
                      </tr>
                      <tr>
                        <td>Alamat Pemilik</td>
                        <td><textarea name="alamat_pemilik"><?php echo $alamat_pemilik; ?></textarea>
                          </td>
                      </tr>
                      <tr>
                        <td>Cara Penagihan</td>
                        <td>
                          <select name="cara_penagihan" class="form-control">
                          <option value="0" <?php echo $cara_penagihan=='0'?'selected':'';?>>Belum dipilih</option>
                          <option value="1" <?php echo $cara_penagihan=='1'?'selected':'';?>>Tukar TT by kurir Nisson</option>
                          <option value="2" <?php echo $cara_penagihan=='2'?'selected':'';?>>Tukar TT by titip sales</option>
                          <option value="3" <?php echo $cara_penagihan=='3'?'selected':'';?>>Scan kirim email</option>
                          <option value="4" <?php echo $cara_penagihan=='4'?'selected':'';?>>Tukar TT titip supir</option>
                          <option value="5" <?php echo $cara_penagihan=='5'?'selected':'';?>>Tukar TT JNE</option>
                          </select>
                        </td>
                      </tr>
                      <tr>
                        <td>Jadwal Penagihan</td>
                        <td>
                        <?php foreach($namahari as $h) { ?>
                          <?php if(in_array($h['id'], $haris)) { ?>
                            <input type="checkbox" name="jadwalpenagihan[]" value="<?php echo $h['id'] ?>" checked="checked"/> <?php echo $h['namahari'] ?><br>
                          <?php }else{ ?>
                            <input type="checkbox" name="jadwalpenagihan[]" value="<?php echo $h['id'] ?>"/> <?php echo $h['namahari'] ?><br>
                          <?php } ?>
                        <?php } ?>
                        <!--<a onclick="hapusjadwal(<?php echo $this->request->get['id']?>)" class="badge bg-red">Hapus Jadwal</a>-->
                        </td>
                      </tr>
                      <tr>
                        <td>Jam Penagihan</td>
                        <td>
                          <input class="form-control jam" type="text" name="jam_penagihan" value="<?php echo $jam_penagihan; ?>" />
                          <small><i>Contoh : 14.00-16.00</i></small>
                        </td>
                      </tr>
                      <tr>
                        <td>Tempat Lahir</td>
                        <td><input class="form-control" type="text" name="tempat_lahir" value="<?php echo $tempat_lahir; ?>" />
                          </td>
                      </tr>
                      <tr>
                        <td>Tanggal Lahir</td>
                        <td><input class="form-control" type="text" name="tgllahir" value="<?php echo $tgllahir; ?>" placeholder="Format tahun-bulan-tanggal" />
                          </td>
                      </tr>

                      <tr>
                        <td>Status Kawin</td>
                        <td><select class="form-control" name="status_perkawinan">
                              <option value="1">Belum Menikah</option>
                              <option value="2">Sudah Menikah</option>
                              <option value="3">Janda/Duda</option>

                          </select ></td>
                      </tr>

                    </table>
                  </div>
                </form>

            </div>

          </div>
          <div class="box-footer">
            <div class="row">
              <div class="col-md-12">
                <div class="pull-right"><?php echo $pagination; ?></div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
</div>
</div>
<script type="text/javascript">
function hapusjadwal(id){
  var r=window.confirm("apakah yakin?");
  if(r==true){
    $.ajax({
      url: 'index.php?route=sale/customer/hapusjadwal&token=<?php echo $token; ?>&customer_id=' + id,
      success: function(json) {
        console.log(json);
        if(json>0){
          alert("Berhasil Dihapus!");
          location.reload();
        }else{
          alert("Gagal Dihapus!");
        }
      },
    });
  }else{
    return false;
  }
}
$( ".jam" ).keypress(function(e) {
  var key = e.keyCode;
  if (key >= 48 && key <= 57 || key>=45 && key<=46 || key==32) {
    console.log(key);
  }else{
    e.preventDefault();
    swal("hanya boleh memasukan numeric,titik dan strip !");
  }
});
function countrys(element, zone_id) {
  $.ajax({
		url: 'index.php?route=sale/customer/country&token=<?php echo $token; ?>&country_id=' + element.value,
		dataType: 'json',
		beforeSend: function() {
			$('select[name=\'country\']').after('<span class="wait">&nbsp;<img src="view/image/loading.gif" alt="" /></span>');
		},
		complete: function() {
			$('.wait').remove();
		},
		success: function(json) {

			html = '<option value=""><?php echo $text_select; ?></option>';

			if (json['zone'] != '') {
				for (i = 0; i < json['zone'].length; i++) {
        			html += '<option value="' + json['zone'][i]['zone_id'] + '"';

					if (json['zone'][i]['zone_id'] == zone_id) {
	      				html += ' selected="selected"';
	    			}

	    			html += '>' + json['zone'][i]['name'] + '</option>';
				}
			} else {
				html += '<option value="0"><?php echo $text_none; ?></option>';
			}

			$('select[name=\'zone\']').html(html);
      $('select[name=\'zone\']').trigger('change');

		},
		error: function(xhr, ajaxOptions, thrownError) {
			alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
		}
	});

}



/* tokocepat */

function zones(element, city_id) {
	$.ajax({
		url: 'index.php?route=sale/customer/zone&token=<?php echo $token; ?>&zone_id=' + element.value,
		dataType: 'json',
		beforeSend: function() {
			$('select[name=\'zone\']').after('<span class="wait">&nbsp;<img src="view/image/loading.gif" alt="" /></span>');
		},
		complete: function() {
			$('.wait').remove();
		},
		success: function(json) {
			html = '<option value=""><?php echo $text_select; ?></option>';

			if (json['city'] != '') {
				for (i = 0; i < json['city'].length; i++) {
        			html += '<option value="' + json['city'][i]['city_id'] + '"';

					if (json['city'][i]['city_id'] == city_id) {
	      				html += ' selected="selected"';
	    			}

	    			html += '>' + json['city'][i]['name'] + '</option>';
				}
			} else {
				html += '<option value="0"><?php echo $text_none; ?></option>';
			}

			$('select[name=\'city\']').html(html);
		},
		error: function(xhr, ajaxOptions, thrownError) {
			alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
		}
	});
}


$(function(){
  $('select[name=\'country\']').trigger('change');


})
//--></script>
<script>
$('.sidebar-menu').find('#menu-customer').addClass('active');
$('.sidebar-menu').find('#menu-customer-group').addClass('active');
$(function(){
  $(".select-ads").select2({


      theme:"bootstrap"
    });
})
</script>
<script>
$(function(){
  $('.date').datepicker({dateFormat: 'yy-mm-dd',changeMonth: true,
      changeYear: true});
})
</script>
<script type="text/javascript"><!--
$('#form input').keydown(function(e) {
	if (e.keyCode == 13) {
		filter();
	}
});
$(function(){
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
})

//function simpan()
//--></script>


<?php echo $footer; ?>
