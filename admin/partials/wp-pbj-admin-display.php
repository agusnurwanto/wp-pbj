<style>

</style>
<div class="wrap-pbj">
	<input type="hidden" value="<?php echo get_option( '_crb_pbj_api_key' ); ?>" id="api_key">
	<h1 class="text_tengah">Sinkronisasi Data LPSE</h1>
	<table class="table table-bordered">
		<thead>
			<tr>
				<th class="text_tengah" style="width: 30px;">No</th>
				<th class="text_tengah">Keterangan</th>
				<th class="text_tengah" style="width: 400px;">Aksi</th>
			</tr>
		</thead>
		<tbody>
			<tr>
				<td class="text_tengah">1</td>
				<td>User pada tabel <b>public.pegawai</b> yang memiliki usergroup PPE akan dijadikan user wordpress dengan user role <b>pbj-ppe</b></td>
				<td class="text_tengah"><a id="pbj_singkron_user_ppe" onclick="return false;" href="#" class="pbj_singkron_user button button-primary button-large">User Admin PPE</a></td>
			</tr>
			<tr>
				<td class="text_tengah">2</td>
				<td>User pada tabel <b>public.pegawai</b> yang memiliki usergroup UKPBJ akan dijadikan user wordpress dengan user role <b>pbj-kupbj</b></td>
				<td class="text_tengah"><a id="pbj_singkron_user_kupbj" onclick="return false;" href="#" class="pbj_singkron_user button button-primary button-large">User KUPBJ</a></td>
			</tr>
			<tr>
				<td class="text_tengah">3</td>
				<td>User pada tabel <b>public.ppk</b> akan dijadikan user wordpress dengan user role <b>pbj-ppk</b></td>
				<td class="text_tengah"><a id="pbj_singkron_user_ppk" onclick="return false;" href="#" class="pbj_singkron_user button button-primary button-large">User PPK</a></td>
			</tr>
			<tr>
				<td class="text_tengah">4</td>
				<td>User pada tabel <b>public.pegawai</b> yang memiliki usergroup POKJA akan dijadikan user wordpress dengan user role <b>pbj-pokja</b></td>
				<td class="text_tengah"><a id="pbj_singkron_user_pokja" onclick="return false;" href="#" class="pbj_singkron_user button button-primary button-large">User POKJA</a></td>
			</tr>
		</tbody>
	</table>
</div>
<script type="text/javascript">
	jQuery(document).ready(function(){
		jQuery('.pbj_singkron_user').on('click', function(){
			var type_aksi = jQuery(this).attr('id');
			var usergroup = '';
			if(type_aksi == 'pbj_singkron_user_ppk'){
				usergroup = 'PPK';
			}else if(type_aksi == 'pbj_singkron_user_ppe'){
				usergroup = 'Admin PPE';
			}else if(type_aksi == 'pbj_singkron_user_ukpbj'){
				usergroup = 'UKPBJ';
			}else if(type_aksi == 'pbj_singkron_user_pokja'){
				usergroup = 'POKJA';
			}
			if(confirm("Apakah anda yakin akan mengsingkonisasi user "+usergroup+"?")){
				jQuery('#wrap-loading').show();
				console.log('type_aksi', type_aksi);
				jQuery.ajax({
					url: pbj.ajaxurl,
		          	type: "post",
		          	data: {
		          		"action": "pbj_singkron_user",
		          		"type_aksi": type_aksi,
		          		"api_key": jQuery('#api_key').val(),
		          		"pass": prompt('Masukan password default untuk User yang akan dibuat')
		          	},
		          	dataType: "json",
		          	success: function(data){
						jQuery('#wrap-loading').hide();
						return alert(data.message);
					},
					error: function(e) {
						console.log(e);
						return alert(data.message);
					}
				});
			}
		});
	});
</script>