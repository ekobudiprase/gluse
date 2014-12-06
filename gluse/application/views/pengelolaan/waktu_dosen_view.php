<div class="page-header">
	<h3>Daftar Dosen</h3>
</div>

<div class="alert alert-<?=$notif_style?>" <?=$display?>>
	<button data-dismiss="alert" class="close" type="button"><i class="icon-remove"></i></button>
	<?=$notif_message?>
</div>


<div class="btn_add">
	<a href="<?=$url_tambah?>">
		<button class="btn btn-success">Tambah</button>
	</a>
</div>

<table class="table table-bordered table-striped table-condensed table-nonfluid" style="overflow:;width:200%;">
	<thead>
		<tr>
			<th class="span1">No</th>
			<th class="span2">NIP</th>
			<th>Nama</th>                               
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
					<td><?=$value['nip']?></td>
					<td><?=$value['nama']?></td>
					<td class="center">
						<a href="<?=$url_edit.'?id='.$value['id']?>" class="btn btn-warning"><i class="icon-pencil icon-white"></i> </a>
						<a href="<?=$url_del.'?id='.$value['id']?>" id="<?=$value['id']?>" class="btn btn-danger konfirmdel"><i class="icon-trash icon-white"></i> </a>

					</td>                                       
				</tr>
				<?php 
			}
		}else{
			$filter['start'] = $filter['start'] - 1;
			?>
			<tr>
				<td colspan="5" style="text-align:center;font-style:oblique"> -- Data tidak ada --</td>                                     
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


<script type="text/javascript">
$(function(){
	
	$modal = $("#confirm_hapus");

	$(".konfirmdel" ).on( "click", function() {

		url_aksi = $(this).attr('href');
		id = $(this).attr('id');

		$.ajax({
			type: "POST",
			url: url_aksi,
			data: { id: id}
		}).done(function( msg ) {
			$('#confirm_hapus').html(msg);
			$modal.modal('show');
		});

		return false;
	});

});
</script>