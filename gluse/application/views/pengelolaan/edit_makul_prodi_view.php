<div class="page-header">
	<h3>Program Studi Mata kuliah : <?=$makul['kode']?> - <?=$makul['nama']?></h3>
</div>

<div class="alert alert-<?=$notif_style?>" <?=$display?>>
	<button data-dismiss="alert" class="close" type="button"><i class="icon-remove"></i></button>
	<?=$notif_message?>
</div>


<div class="btn_add">
	<a href="<?=$url_tambah?>">
		<button class="btn btn-success">Tambah</button>
	</a>
	<a class="url_back" href="<?=$url_back?>"><button name="back" class="btn ">&nbsp; Back &nbsp;</button></a>
</div>

<table class="table table-bordered table-striped table-condensed table-nonfluid" style="overflow:;width:200%;">
	<thead>
		<tr>
			<th class="span1">No</th>
			<th class="span2">Kode prodi</th>
			<th>Nama prodi</th>
			<th class="span1">Porsi</th>                                 
			<th class="span2">Aksi</th>                                       
		</tr>
	</thead>   
	<tbody>
		<?php 
		$no = $filter['start']+1; 
		if (!empty($data)) {
			foreach ($data as $key => $value) { 
				$rowspan = count($value);
				$display = ($rowspan>1)?'style="display:none;"':'';
				?>
				<tr>
					<td rowspan="<?=$rowspan?>"><?=$no++?></td>
					<td><?=$value[0]['kode']?></td>
					<td><?=$value[0]['nama']?></td>
					<td class="center"><?=$value[0]['porsi']?></td>
					<td class="center">
						<a href="<?=$url_edit.'&idjoin='.$value[0]['id']?>" class="btn btn-warning"><i class="icon-pencil icon-white"></i> </a>
						<a <?=$display?> href="<?=$url_del.'&idjoin='.$value[0]['id']?>" id="<?=$value[0]['id']?>" class="btn btn-danger konfirmdel"><i class="icon-trash icon-white"></i> </a>

					</td>                                       
				</tr>
				<?php 
				if ($rowspan>1) {					
					for ($i=1; $i < $rowspan; $i++) { 
						$display_sub = ($i!=$rowspan-1)?'style="display:none;"':'';
					?>
						<tr>
							<td><?=$value[$i]['kode']?></td>
							<td><?=$value[$i]['nama']?></td>
							<td class="center"><?=$value[$i]['porsi']?></td>
							<td class="center">
								<a href="<?=$url_edit.'&idjoin='.$value[$i]['id']?>" class="btn btn-warning"><i class="icon-pencil icon-white"></i> </a>
								<a <?=$display_sub?> href="<?=$url_del?>" id="<?=$value[$i]['id']?>" class="btn btn-danger konfirmdel"><i class="icon-trash icon-white"></i> </a>

							</td>                                       
						</tr>
					<?php
					}
				}
			}
		}else{
			?>
			<tr>
				<td colspan="5" style="text-align:center;font-style:oblique"> -- Data tidak ada --</td>                                     
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