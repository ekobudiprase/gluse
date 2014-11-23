<div class="page-header">
	<h3>Import Rekap Mata Kuliah Kurikulum</h3>
</div>

<form class="form-horizontal form " action="<?=$url_submit?>" enctype="multipart/form-data" method="post">
	<div class="control-group">
		<label for="fileInput" class="control-label">File input</label>
		<div class="controls">
		<input type="file" value="" name="file" id="file" multiple /> 

		&nbsp; 

		
		<p class="help-block"><a href="<?=$url_download_format_excel?>">download file upload</a></p>
		</div> 

	</div>

	<div class="form-actions">
		<!-- <button class="btn btn-primary" type="submit">Cari</button> -->
		<button class="btn btn-primary" type="submit">&nbsp; Simpan &nbsp;</button>
		<a class="url_back" href="<?=$url_rekap_matakuliah?>"><button name="back" class="btn ">&nbsp; Back &nbsp;</button></a>
	</div>

</form>