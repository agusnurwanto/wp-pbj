<?php
	$body = "";
?>
  <link rel="stylesheet" href="<?php echo PBJ_PLUGIN_URL; ?>public/plugins/fontawesome-free/css/all.min.css">
  <link rel="stylesheet" href="<?php echo PBJ_PLUGIN_URL; ?>public/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
  <link rel="stylesheet" href="<?php echo PBJ_PLUGIN_URL; ?>public/plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
  <link rel="stylesheet" href="<?php echo PBJ_PLUGIN_URL; ?>public/plugins/datatables-buttons/css/buttons.bootstrap4.min.css">
  <link rel="stylesheet" href="<?php echo PBJ_PLUGIN_URL; ?>public/dist/css/adminlte.min.css">
<style>
	.td-top td, .td-top th {
		vertical-align: top;
	}
</style>
<section class="content" style="margin-top: 10px;">
  	<div class="container-fluid">
    	<div class="row">
      		<div class="col-md-12">
        		<div class="card card-primary">
          			<div class="card-header">
            			<h3 class="card-title">Pengajuan Paket</h3>
          			</div>
          			<div class="card-body">
				    	<div class="row">
				      		<div class="col-md-12 text_tengah">
          						<button type="button" class="btn btn-success" id="tambah-paket"><i class="fa fa-plus"></i> Ajukan Paket</button>
          					</div>
          				</div>
						<input type="hidden" value="<?php echo get_option( '_crb_pbj_api_key' ); ?>" id="api_key">
						<table id="daftar-paket" class="td-top table table-bordered">
							<thead>
								<tr>
									<th class="text_tengah" style="width: 30px;">No</th>
									<th class="text_tengah" style="width: 100px;">Kode Pekerjaan</th>
									<th class="text_tengah">Nama Pekerjaan</th>
									<th class="text_tengah" style="width: 100px;">Tahun Anggaran</th>
									<th class="text_tengah" style="width: 140px;">HPS</th>
									<th class="text_tengah" style="width: 100px;">Sumber Dana</th>
									<th class="text_tengah" style="width: 100px;">Jenis Pengadaan</th>
									<th class="text_tengah" style="width: 100px;">Aksi</th>
								</tr>
							</thead>
							<tbody>
								<?php echo $body; ?>
							</tbody>
						</table>
          			</div>
          		</div>
    		</div>
      	</div>
    </div>
</section>
<div class="modal fade" id="mod-tambah-paket" tabindex="-1" role="dialog" data-backdrop="static" aria-hidden="true">'
    <div class="modal-dialog" role="document" style="min-width: 1200px;">
        <div class="modal-content">
            <div class="modal-header bgpanel-theme">
                <h4 style="margin: 0;" class="modal-title" id="">Tambah Paket Baru</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span><i class="dashicons dashicons-dismiss"></i></span></button>
            </div>
            <div class="modal-body">
            	<form>
	                <div class="card-body">
	                  	<div class="form-group">
		                    <label for="kode-rup">Kode RUP</label>
		                    <div class="input-group">
		                      	<div class="custom-file">
		                    		<input type="text" class="form-control" id="kode-rup">
		                      	</div>
		                      	<div class="input-group-append" id="cari-rup">
			                        <span class="input-group-text">Cari di SIRUP</span>
		                    	</div>
		                    </div>
	                  	</div>
	                  	<div class="form-group">
		                    <label for="nama-paket">Nama Pekerjaan</label>
		                    <textarea class="form-control" id="nama-paket" disabled="true"></textarea>
	                  	</div>
	                  	<div class="form-group">
		                    <label for="tahun-anggaran">Tahun Anggaran</label>
		                    <input type="number" class="form-control" id="tahun-anggaran" disabled="true">
	                  	</div>
	                  	<div class="form-group">
		                    <label for="pagu-paket">Pagu</label>
		                    <input type="text" class="form-control" id="pagu-paket" disabled="true">
	                  	</div>
	                  	<div class="form-group">
		                    <label for="detail-paket-rup">Detail Data RUP</label>
		                    <textarea class="form-control" id="detail-paket-rup" disabled="true"></textarea>
	                  	</div>
	                </div>
            	</form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary">Simpan</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>
<script src="<?php echo PBJ_PLUGIN_URL; ?>public/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="<?php echo PBJ_PLUGIN_URL; ?>public/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
<script src="<?php echo PBJ_PLUGIN_URL; ?>public/plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
<script src="<?php echo PBJ_PLUGIN_URL; ?>public/plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
<script src="<?php echo PBJ_PLUGIN_URL; ?>public/plugins/datatables-buttons/js/dataTables.buttons.min.js"></script>
<script src="<?php echo PBJ_PLUGIN_URL; ?>public/plugins/datatables-buttons/js/buttons.bootstrap4.min.js"></script>
<script src="<?php echo PBJ_PLUGIN_URL; ?>public/plugins/jszip/jszip.min.js"></script>
<script src="<?php echo PBJ_PLUGIN_URL; ?>public/plugins/pdfmake/pdfmake.min.js"></script>
<script src="<?php echo PBJ_PLUGIN_URL; ?>public/plugins/pdfmake/vfs_fonts.js"></script>
<script src="<?php echo PBJ_PLUGIN_URL; ?>public/plugins/datatables-buttons/js/buttons.html5.min.js"></script>
<script src="<?php echo PBJ_PLUGIN_URL; ?>public/plugins/datatables-buttons/js/buttons.print.min.js"></script>
<script src="<?php echo PBJ_PLUGIN_URL; ?>public/plugins/datatables-buttons/js/buttons.colVis.min.js"></script>
<script src="<?php echo PBJ_PLUGIN_URL; ?>public/dist/js/adminlte.min.js"></script>
<script type="text/javascript">
	jQuery('#daftar-paket').DataTable();
	jQuery('#tambah-paket').on('click', function(){
		jQuery('#mod-tambah-paket').modal('show');
	});
	jQuery('#cari-rup').on('click', function(){
		var id_rup = jQuery('#kode-rup').val();
		if(!id_rup || id_rup==''){
			alert('Kode RUP tidak boleh kosong!');
		}else{
			jQuery('#wrap-loading').show();
			jQuery.ajax({
				url: pbj.ajaxurl,
	          	type: "post",
	          	data: {
	          		"action": "get_detail_sirup_rup",
	          		"api_key": jQuery('#api_key').val(),
	          		"id_rup": id_rup
	          	},
	          	dataType: "json",
	          	success: function(data){
	          		if(data.status == 'success'){
	          			var html = data.data.split('<div class="modal-body" id="modal-body-id">')[1].split('<div class="modal-footer">')[0];
	          			window.html_rup = jQuery('<div>'+html);
	          			window.detail_rup = {};
	          			var td_rup = html_rup.find('#detil > table > tbody > tr > td');
	          			detail_rup.id_rup_sirup = td_rup.eq(1).text();
	          			detail_rup.nama = td_rup.eq(3).text();
	          			detail_rup.klpd = td_rup.eq(5).text();
	          			detail_rup.satker = td_rup.eq(7).text();
	          			detail_rup.tahun = td_rup.eq(9).text();
	          			detail_rup.lokasi = [];
	          			td_rup.eq(11).find('table tr').map(function(i, b){
	          				if(i > 0){
	          					var td_lokasi = jQuery(b).find('td');
	          					detail_rup.lokasi.push({
	          						no: td_lokasi.eq(0).text().replace(/\./g, ''),
	          						prov: td_lokasi.eq(1).text(),
	          						kab_kot: td_lokasi.eq(2).text(),
	          						detail_lokasi: td_lokasi.eq(3).text()
	          					});
	          				}
	          			});
	          			detail_rup.vol = td_rup.eq(13).text();
	          			detail_rup.uraian = td_rup.eq(15).text();
	          			detail_rup.spek_pekerjaan = td_rup.eq(17).text();
	          			detail_rup.prod_dlm_negri = td_rup.eq(19).text();
	          			detail_rup.usaha_kecil = td_rup.eq(21).text();
	          			detail_rup.pra_dipa = td_rup.eq(23).text();
	          			detail_rup.sumber_dana = [];
	          			td_rup.eq(25).find('table tr').map(function(i, b){
	          				if(i > 0){
	          					var td_sd = jQuery(b).find('td');
	          					if(td_sd.length > 2){
		          					detail_rup.sumber_dana.push({
		          						no: td_sd.eq(0).text().replace(/./g, ''),
		          						sumber_dana: td_sd.eq(1).text(),
		          						tahun: td_sd.eq(2).text(),
		          						klpd: td_sd.eq(3).text(),
		          						mak: td_sd.eq(4).text(),
		          						pagu: td_sd.eq(5).text()
		          					});
		          				}
	          				}
	          			});
	          			detail_rup.total_pagu_sd = td_rup.eq(25).find('table tr th span.rupiah2').text();
	          			detail_rup.jenis_pengadaan = td_rup.eq(27).text();
	          			detail_rup.total_pagu = td_rup.eq(29).text();
	          			detail_rup.metode_pemilihan = td_rup.eq(31).text();
	          			detail_rup.pemanfaatan = [];
	          			td_rup.eq(33).find('table tr').map(function(i, b){
	          				if(i > 0){
	          					var td_pemanfaatan = jQuery(b).find('td');
	          					detail_rup.pemanfaatan.push({
	          						mulai: td_pemanfaatan.eq(0).text(),
	          						akhir: td_pemanfaatan.eq(1).text()
	          					});
	          				}
	          			});
	          			detail_rup.jadwal_pelaksanaan_kontrak = [];
	          			td_rup.eq(35).find('table tr').map(function(i, b){
	          				if(i > 0){
	          					var td_pelaksanaan_kontrak = jQuery(b).find('td');
	          					detail_rup.jadwal_pelaksanaan_kontrak.push({
	          						mulai: td_pelaksanaan_kontrak.eq(0).text(),
	          						akhir: td_pelaksanaan_kontrak.eq(1).text()
	          					});
	          				}
	          			});
	          			detail_rup.jadwal_pemilihan_penyedia = [];
	          			td_rup.eq(37).find('table tr').map(function(i, b){
	          				if(i > 0){
	          					var td_pemilihan_penyedia = jQuery(b).find('td');
	          					detail_rup.jadwal_pemilihan_penyedia.push({
	          						mulai: td_pemilihan_penyedia.eq(0).text(),
	          						akhir: td_pemilihan_penyedia.eq(1).text()
	          					});
	          				}
	          			});
	          			detail_rup.tgl_update_paket = html_rup.find("td:contains('Tanggal Perbarui Paket')").closest('tr').find('td').eq(1).text();
	          			jQuery('#nama-paket').val(detail_rup.nama);
	          			jQuery('#tahun-anggaran').val(detail_rup.tahun);
	          			jQuery('#pagu-paket').val(detail_rup.total_pagu);
	          			jQuery('#detail-paket-rup').val(JSON.stringify(detail_rup));
	          		}else{
	          			alert(data.message);
	          		}
	          		jQuery('#wrap-loading').hide();
	          	}
	        });
		}
	});
</script>