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
							<td>'.$panitia['nama'].'</td>
						</tr>
						<tr>
							<td>Tahun</td>
							<td>'.$panitia['tahun'].'</td>
						</tr>
						<tr>
							<td>Nomor SK</td>
							<td>'.$panitia['no_sk'].'</td>
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
	<h1 class="text_tengah">Data Panitia POKJA dan Anggota</h1>
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
