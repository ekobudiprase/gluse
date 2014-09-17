<div class="page-header">
	<h3>Daftar Mata Kuliah Kurikulum</h3>
</div>

<!-- <form class="form-horizontal form ">

	<div class="control-group">
		<label for="selectError3" class="control-label">Tahun</label>
		<div class="controls">
			<select id="selectError3">
				<option value="" selected="selected">-.-pilih-.-</option>
				<?php foreach ($data_tahun as $key => $value) {
					echo "<option value='".$value['val']."'>".$value['label']."</option>";
				}
				?>
			</select>
		</div>
	</div>

	<div class="form-actions">
		<!-.- <button class="btn btn-primary" type="submit">Cari</button> -.->
		<button class="btn">&nbsp; Cari &nbsp;</button>
	</div>

</form> -->

<div class="alert alert-block " <?=$display_warning_kelasnotgenerated?>>
	<button data-dismiss="alert" class="close" type="button"><i class="icon-remove"></i></button>
	<h4 class="alert-heading">Warning!</h4><p>Rekap mata kuliah belum diisi. Prediksi tidak dapat dilakukan.</p>	
</div>

<div class="alert alert-<?=$notif_style?>" <?=$display?>>
	<button data-dismiss="alert" class="close" type="button"><i class="icon-remove"></i></button>
	<?=$notif_message?>
</div>

<div class="btn_add">
	<a href="<?=$url_proses_prediksi?>" <?=$display_button_generatedclass?> >
		<button class="btn btn-success">Prediksi</button>
	</a>

	<a href="<?=$url_import_rekap_matakuliah?>">
		<button class="btn btn-success">Import data</button>
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
			<th>Tahun</th>                   
			<th class="span2">Jumlah Peminat</th>                                       
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
					<td class="center"><?=$value['tahun']?></td>
					<td class="center">
						<span class="help-inline" style="color:red;text-align:center"><?=$value['jml_peminat']?></span>
					</td>                                       
				</tr>
				<?php 
			}
		}else{
			$filter['start'] = $filter['start'] - 1;
			?>
			<tr>
				<td colspan="9" style="text-align:center;font-style:oblique"> -- Data tidak ada --</td>                                      
			</tr>
			<?php
		} 
		?>

	</tbody>
</table>

<div class="row-fluid">
	<div class="" style="margin-left:10px;width:200px;float:left;">
		<div class="dataTables_info" id="DataTables_Table_0_info">
			Showing <?=$filter['start']+1?> to <?=($no-1)?> of <?=$jumlah_data?> entries
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