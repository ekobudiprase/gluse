<div class="page-header">
	<h3>Konfigurasi</h3>
</div>

<div class="alert alert-<?=$notif_style?>" <?=$display?>>
	<button data-dismiss="alert" class="close" type="button"><i class="icon-remove"></i></button>
	<?=$notif_message?>
</div>

<table class="table table-bordered table-striped table-condensed table-nonfluid" style="overflow:;width:200%;">
	<thead>
		<tr>
			<th class="span1">No</th>
			<th>Nama</th>
			<th class="span1">Nilai</th>                       
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
					<td><?=$value['nama']?></td>
					<td class="center"><?=$value['nilai']?></td>
					<td class="center">
						<a href="<?=$url_edit.'?id='.$value['id']?>" class="btn btn-warning"><i class="icon-pencil icon-white"></i> </a>
						
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
