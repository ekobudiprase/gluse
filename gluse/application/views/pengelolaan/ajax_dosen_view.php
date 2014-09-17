<div id="content_modal">

<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="icon-remove"></i></button>
	<h3>Data Dosen</h3>
</div>

<form id="myform" class="myform" method="post" name="myform" action="#" >
<div class="modal-body">

<div class="row-fluid">


<table class="table table-bordered table-striped table-condensed table-nonfluid" style="overflow:;width:200%;">
	<thead>
		<tr>
			<th class="">No</th>
			<th class="">NIP Dosen</th>
			<th>Nama Dosen</th>
			<th class="">Aksi</th>
		</tr>
	</thead>   
	<tbody>
		<?php 
		$no = $filter['start']+1; 
		if (!empty($data_dosen)) {
			foreach ($data_dosen as $key => $value) { 
				?>
				<tr>
					<td><?=$no++?></td>
					<td class="center"><?=$value['nip']?></td>
					<td class="center"><?=$value['nama']?></td>
					<td class="center">
						<input type="radio" name="dosen" class="dosen" value="<?=$value['id']?>" id="" style=""  >
						<input type="hidden" id="dosen_<?=$value['id']?>" class="dosen" value="<?=$value['nama']?>" id="" style=""  >

						<!-- <a href="#" class="btn btn-small btn-inverse span1">
							<span class="icon icon-white icon-check" title=".icon  .icon-white  .icon-check "></span>
							Pilih
						</a> -->
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

</div>

</div>
<div class="modal-footer">
	<input type="hidden" name="id_kelas" id="id_kelas" value="" />
	<button type="button" data-dismiss="modal" class="btn">Close</button>
	<input type="submit" id="submit" class="btn btn-primary" value="Pilih" ></button>
</div>
</form>


<div class="row-fluid">
	<div class="" style="margin-left:10px;width:200px;float:left;">
		<div class="dataTables_info" id="DataTables_Table_0_info">
			Showing <?=$filter['start']+1?> to <?=($no-1)?> of <?=($jumlah_data_dosen)?> entries
		</div>
	</div>

	<div class="" style="float:right;margin-right:10px;">
		<div class="dataTables_paginate paging_bootstrap pagination" style="margin:0px;">
			<ul>
				<?=$paging_dosen?>
			</ul>
		</div>
	</div>
</div>

</div>

<script type="text/javascript">
$(function(){
	var form = document.myform;
	var dataString = $(form).serialize();

	/*$( "#myform" ).submit(function( event ) {
		alert( "Handler for .submit() called." );
		event.preventDefault();
	});*/

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

	$("#submit" ).on( "click", function() {
		id = $('.dosen:checked').val();
		dosen = $('#dosen_'+id).val();
		
		$('#id_dosen').val(id);
		$('#nama').val(dosen);



		$('#responsive').modal('hide');
		return false;
	});
});
</script>