<?php
$args = array(
    'role'    => 'pbj-pokja'
);
$users = get_users( $args );
$data_panita = array();
foreach ($users as $k => $user) {
	$sth = $this->lpse->prepare("
		SELECT
			p.*,
			a.*,
			e.*
		FROM public.anggota_panitia a
			inner join public.panitia p ON a.pnt_id=p.pnt_id
			inner join public.pegawai e ON a.peg_id=e.peg_id
			inner join public.usergroup g ON g.userid=e.peg_namauser
		WHERE e.peg_isactive=-1
			AND g.idgroup='PANITIA'
			AND e.peg_namauser='".$user->data->user_login."'
	");
	$sth->execute();
	$usersdb = $sth->fetchAll(PDO::FETCH_ASSOC);
	foreach ($usersdb as $user_lpse) {
		if(empty($data_panita[$user_lpse['pnt_id']])){
			$data_panita[$user_lpse['pnt_id']] = array(
				'nama' => $user_lpse['pnt_nama'],
				'tahun' => $user_lpse['pnt_tahun'],
				'no_sk' => $user_lpse['pnt_no_sk'],
				'pnt_id' => $user_lpse['pnt_id'],
				'data' => array()
			);
		}
		if(empty($data_panita[$user_lpse['pnt_id']]['data'][$user_lpse['peg_id']])){
			$data_panita[$user_lpse['pnt_id']]['data'][$user_lpse['peg_id']] = $user_lpse;
		}
	}
}
krsort($data_panita);
$body = '';
$no = 0;
foreach($data_panita as $panitia){
	$no++;
	$body .= '
		<tr>
			<td>'.$no.'</td>
			<td>
				<table class="table table-bordered">
					<tbody>
						<tr>
							<td>Nama</td>
							<td class="pnt_nama">'.$panitia['nama'].'</td>
						</tr>
						<tr>
							<td>Tahun</td>
							<td>'.$panitia['tahun'].'</td>
						</tr>
						<tr>
							<td>Nomor SK</td>
							<td>'.$panitia['no_sk'].'</td>
						</tr>
						<tr>
							<td colspan="2" class="text_tengah">
								<a data-pnt-id="'.$panitia['pnt_id'].'" class="daftar-paket button button-primary" onclick="return false;" href="#">Daftar Paket</a>
							</td>
						</tr>
					</tbody>
				</table>
			</td>
			<td>
				<table class="table table-bordered">
					<thead>
						<tr>
							<th>No</th>
							<th>NIP</th>
							<th>Nama</th>
							<th>Alamat</th>
							<th>No. Telepon</th>
							<th>Email</th>
							<th>Golongan</th>
							<th>Pangkat</th>
							<th>Jabatan</th>
						</tr>
					</thead>
					<tbody>
		';
	$no_anggota = 0;
	foreach ($panitia['data'] as $anggota) {
		$no_anggota++;
		$body .= '
			<tr>
				<td>'.$no_anggota.'</td>
				<td>'.$anggota['peg_nip'].'</td>
				<td>'.$anggota['peg_nama'].'</td>
				<td>'.$anggota['peg_alamat'].'</td>
				<td>'.$anggota['peg_telepon'].'</td>
				<td>'.$anggota['peg_email'].'</td>
				<td>'.$anggota['peg_golongan'].'</td>
				<td>'.$anggota['peg_pangkat'].'</td>
				<td>'.$anggota['peg_jabatan'].'</td>
			</tr>
		';
	}
	$body .= '
					</tbody>
				</table>
			</td>
		</tr>
	';
}
?>
<style>
	.td-top td, .td-top th {
		vertical-align: top;
	}
</style>
<div class="wrap-pbj">
	<input type="hidden" value="<?php echo get_option( '_crb_pbj_api_key' ); ?>" id="api_key">
	<h1 class="text_tengah">Daftar Panitia POKJA dan Anggota</h1>
	<table class="td-top table table-bordered">
		<thead>
			<tr>
				<th class="text_tengah" style="width: 30px;">No</th>
				<th class="text_tengah" style="width: 400px;">Nama Panita</th>
				<th class="text_tengah">Anggota</th>
			</tr>
		</thead>
		<tbody>
			<?php echo $body; ?>
		</tbody>
	</table>
</div>
<div class="modal fade" id="mod-paket" tabindex="-1" role="dialog" data-backdrop="static" aria-hidden="true">'
    <div class="modal-dialog" style="min-width: 95%;" role="document">
        <div class="modal-content">
            <div class="modal-header bgpanel-theme">
                <h4 style="margin: 0;" class="modal-title" id="">Daftar Paket Pekerjaan "<span style="font-weight: bold;"></span>"</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span><i class="dashicons dashicons-dismiss"></i></span></button>
            </div>
            <div class="modal-body">
            	<table id="mod-daftar-paket" class="table table-bordered">
            		<thead>
            			<tr>
            				<th class="text_tengah" style="width: 30px;">No</th>
            				<th class="text_tengah">Nama Paket</th>
            				<th class="text_tengah">Pagu</th>
            				<th class="text_tengah">HPS</th>
            				<th class="text_tengah">Tgl Buat</th>
            				<th class="text_tengah">Tgl Assign</th>
            				<th class="text_tengah">Tgl Assign Pokja</th>
            				<th class="text_tengah">Tgl Assign UKPBJ</th>
            			</tr>
            		</thead>
            		<tbody></tbody>
            	</table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
	jQuery('.daftar-paket').on('click', function(){
		jQuery('#wrap-loading').show();
		var pnt_id = jQuery(this).attr('data-pnt-id');
		var pnt_nama = jQuery(this).closest('table').find('.pnt_nama').text();
		jQuery.ajax({
			url: pbj.ajaxurl,
          	type: "post",
          	data: {
          		"action": "get_paket_pokja",
          		"api_key": jQuery('#api_key').val(),
          		"pnt_id": pnt_id
          	},
          	dataType: "json",
          	success: function(data){
				var body = '';
				if(data.status == 'error'){
					alert(data.message);
				}else{
					data.data.map(function(b, i){
						body += ''
							+'<tr>'
								+'<td class="text_tengah">'+(i+1)+'</td>'
								+'<td>'+b.pkt_nama+'</td>'
								+'<td class="text_kanan">'+b.pkt_pagu+'</td>'
								+'<td class="text_kanan">'+b.pkt_hps+'</td>'
								+'<td class="text_tengah">'+b.pkt_tgl_buat+'</td>'
								+'<td class="text_tengah">'+b.pkt_tgl_assign+'</td>'
								+'<td class="text_tengah">'+b.pkt_tgl_assign_pokja+'</td>'
								+'<td class="text_tengah">'+b.pkt_tgl_assign_ukpbj+'</td>'
							+'</tr>';
					});
					jQuery('#mod-daftar-paket tbody').html(body);
					jQuery('#mod-paket .modal-title span').text(pnt_nama);
					jQuery('#mod-paket').modal('show');
				}
				jQuery('#wrap-loading').hide();
			},
			error: function(e) {
				console.log(e);
				return alert(data.message);
			}
		});
	});
</script>