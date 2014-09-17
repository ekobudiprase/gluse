<div class="page-header">
	<h3>Daftar Mata Kuliah Kurikulum</h3>
</div>

<div class="alert alert-<?=$notif_style?>" <?=$display?>>
	<button data-dismiss="alert" class="close" type="button"><i class="icon-remove"></i></button>
	<?=$notif_message?>
</div>


<div class="btn_add">
	<a href="<?=$url_rekap_matakuliah?>">
		<button class="btn btn-success">Daftar Rekap</button>
	</a>
</div>

<table class="table table-bordered table-striped table-condensed table-nonfluid" style="overflow:;width:200%;">
	<thead>
		<tr>
			<th class="span1">No</th>
			<th class="span2">Kode mata kuliah</th>
			<th>Mata kuliah</th>
			<th class="span1">Paket semester</th>
			<th>Semester</th>
			<th>Program Studi</th>                                          
			<th>SKS</th>                                       
			<th class="span2">Prediksi Jumlah Peminat</th>                                       
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
					<td><?=$value['kode']?></td>
					<td><?=$value['nama']?></td>
					<td class="center"><?=$value['paket']?></td>
					<td class="center"><?=$value['smt']?></td>
					<td class="center"><?=$value['nama_prodi']?></td>
					<td class="center"><?=$value['sks']?></td>
					<td class="center">
						<span class="help-inline" style="color:red;text-align:center"><?=$value['pred_jml_peminat']?></span>
					</td>                                       
				</tr>
				<?php 
			}
		}else{
			$filter['start'] = $filter['start'] - 1;
			?>
			<tr>
				<td colspan="8" style="text-align:center;font-style:oblique"> -- Data tidak ada --</td>                                     
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