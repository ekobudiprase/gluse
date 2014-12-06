<div id="content_modal">

<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="icon-remove"></i></button>
	<h3>Data Dosen</h3>
</div>

<form id="myforms" class="myform" method="post" name="myform" action="#" >
<div class="modal-body">

<div class="row-fluid">


<table class="table table-bordered table-striped table-condensed table-nonfluid" style="overflow:;width:200%;">
	<thead>
		<tr>
			<th class="">No</th>
			<th class="">Jam Ke-</th>
			<th>Hari</th>
			<th>Waktu</th>
			<th class="">Aksi</th>
		</tr>
	</thead>   
	<tbody>
		<?php 
		$no = $filter['start']+1; 
		if (!empty($data_waktu)) {
			foreach ($data_waktu as $key => $value) { 
				?>
				<tr>
					<td><?=$no++?></td>
					<td class="center"><?=$value['id']?></td>
					<td class="center"><?=$value['hari']?></td>
					<td class="center"><?=$value['jam']?></td>
					<td class="center">
						<input type="radio" name="waktu" class="waktu" value="<?=$value['id']?>" id="id" style=""  >
						<input type="hidden" id="hari_<?=$value['id']?>" class="waktu" value="<?=$value['hari']?>" id="" style=""  >
						<input type="hidden" id="jam_<?=$value['id']?>" class="waktu" value="<?=$value['jam']?>" id="" style=""  >

						<!-- <a href="#" class="btn btn-small btn-inverse span1">
							<span class="icon icon-white icon-check" title=".icon  .icon-white  .icon-check "></span>
							Pilih
						</a> -->
					</td>                                       
				</tr>
				<?php 
			}
		}else{
			$filter['start'] = $filter['start'] - 1;
			?>
			<tr>
				<td colspan="7" style="text-align:center;font-style:oblique"> -- Data tidak ada --</td>                                     
			</tr>
			<?php
		} 
		?>

	</tbody>
</table>

</div>

</div>
<div class="modal-footer">
	<input type="hidden" name="id_kelas" id="id_kelas" value="" />
	<button type="button" data-dismiss="modal" class="btn">Close</button>
	<input type="submit" id="submit_waktu" class="btn btn-primary" value="Pilih" ></button>
</div>
</form>


<div class="row-fluid">
	<div class="" style="margin-left:10px;width:200px;float:left;">
		<div class="dataTables_info" id="DataTables_Table_0_info">
			Showing <?=$filter['start']+1?> to <?=($no-1)?> of <?=($jumlah_data_waktu)?> entries
		</div>
	</div>

	<div class="" style="float:right;margin-right:10px;">
		<div class="dataTables_paginate paging_bootstrap pagination" style="margin:0px;">
			<ul>
				<?=$paging_waktu?>
			</ul>
		</div>
	</div>
</div>

</div>

<script type="text/javascript">
$(function(){

	$(".pagin" ).on( "click", function() {

		
		urle = $(this).find("a").attr('href');
		id_kelas = $("#id_kelas").val();
		// alert(urle);
		$.ajax({
			type: "POST",
			// url: "http://localhost/gluse/index.php/penjadwalan/get_dosen",
			url: urle,
			data: { idkls: id_kelas }
		}).done(function( msg ) {
			$('#content_modal').html(msg);
			$modal.modal('show');
		});

		return false;
	});

	$("#submit_waktu" ).on( "click", function() {
		id = $('.waktu:checked').val();
		hari = $('#hari_'+id).val();
		jam = $('#jam_'+id).val();
		rowCount = $('#table_time').find('tr').index();
		rowCount++;
		
		var row_data = "";
		row_data += "<tr class='"+id+"'>";
		row_data += "<td>"+id+"</td>";
		row_data += "<td>"+hari+"</td>";
		row_data += "<td>"+jam+"</td>";
		row_data += "<td>";
		row_data += "<a href='' class='btn btn-danger remove'><i class='icon-trash icon-white'></i></a>";
		row_data += "<input type='hidden' name='waktu[]' value='"+id+"' >";
		row_data += "</td>";
		row_data += "</tr>";

		$("#table_time>tbody").append(row_data);


		$('#waktune').modal('hide');
		return false;
	});
});
</script>