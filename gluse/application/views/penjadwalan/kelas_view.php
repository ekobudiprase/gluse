<style type="text/css">
.modal.fade.in {
    top: 42%;
}
</style>
<div class="page-header">
	<h3>Daftar Kelas Mata Kuliah Kurikulum</h3>
</div>

<div class="alert alert-block " <?=$display_warning_dosenkelas?>>
	<button data-dismiss="alert" class="close" type="button"><i class="icon-remove"></i></button>
	<h4 class="alert-heading">Warning!</h4><p>Dosen kelas belum lengkap.</p>	
</div>

<div class="alert alert-<?=$notif_style?>" <?=$display?>>
	<button data-dismiss="alert" class="close" type="button"><i class="icon-remove"></i></button>
	<?=$notif_message?>
</div>



<div class="btn_add">
	<a href="<?=$url_proses_jadwal?>" <?=$display_buat_jadwal?>>
		<button class="btn btn-success">Buat jadwal kuliah</button>
	</a>
	<a href="<?=$url_list_jadwal?>" <?=$display_list_jadwal?>>
		<button class="btn btn-success">List jadwal kuliah</button>
	</a>
</div>

<table class="table table-bordered table-striped table-condensed table-nonfluid" style="overflow:;width:200%;">
	<thead>
		<tr>
			<th class="span1">No</th>
			<th class="span2">Nama Kelas</th>
			<th>Mata kuliah</th>			
			<th class="span1">Paket Semester</th>
			<th class="span1">Sifat</th>
			<th class="span1">Jumlah Peserta</th>
			<th class="span2">Dosen Kelas</th>
			<th class="span2">Aksi</th>
		</tr>
	</thead>   
	<tbody>
		<?php 
		$no = $filter['start']+1; 
		if (!empty($data)) {
			foreach ($data as $key => $value) { 
				?>
				<tr>
					<td><?=$no++?></td>
					<td><?=$value['nama_kelas']?></td>
					<td class="center"><?=$value['nama_makul']?></td>
					<td class="center"><?=$value['paket_smt']?></td>
					<td class="center"><?=$value['sifat']?></td>
					<td class="center">
						<span class="help-inline" style="color:red;text-align:center"><?=$value['jml_peserta_kls']?></span>
					</td> 
					<td class="center"><?=$value['dosen_kelas']?></td>
					<td class="center">
						<a id="<?=$value['id']?>" href="<?=$url_pilih_dosen?>" class="btn btn-info edit_dosen"><i class="icon-edit icon-white"></i>Dosen</a>
					</td>                                       
				</tr>
				<?php 
			}
		}else{
			?>
			<tr>
				<td colspan="7" style="text-align:center;font-style:oblique"> -- Data tidak ada --</td>                                     
			</tr>
			<?php
		} 
		?>

	</tbody>
</table>


<div class="row-fluid">
	<div class="" style="margin-left:10px;width:200px;float:left;">
		<div class="dataTables_info" id="DataTables_Table_0_info">
			Showing <?=$filter['start']+1?> to <?=($no-1)?> of <?=($jumlah_data)?> entries
		</div>
	</div>

	<div class="" style="float:right;margin-right:10px;">
		<div class="dataTables_paginate paging_bootstrap pagination" style="margin:0px;">
			<ul>
				<?=$paging?>
			</ul>
		</div>
	</div>
</div>


<div id="responsive" class="modal hide fade" tabindex="-1" data-width="760">
	
</div>

<script type="text/javascript">
$(function(){
	
	$modal = $("#responsive");

	$(".edit_dosen" ).on( "click", function() {

		urle = $(this).attr('href');
		id_kelas = $(this).attr('id');
		// alert(urle);
		$.ajax({
			type: "POST",
			// url: "http://localhost/gluse/index.php/penjadwalan/get_dosen",
			url: urle,
			data: { idkls: id_kelas }
		}).done(function( msg ) {
			$('#responsive').html(msg);
			$modal.modal('show');
		});

		return false;
	});

});
</script>