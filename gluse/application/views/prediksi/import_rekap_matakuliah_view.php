<div class="page-header">
	<h3>Import Rekap Mata Kuliah Kurikulum</h3>
</div>

<form class="form-horizontal form " action="<?=$url_submit?>" enctype="multipart/form-data" method="post">
	<div class="control-group">
		<label for="fileInput" class="control-label">File input</label>
		<div class="controls">
		<div class="uploader" id="uniform-fileInput">
			<input type="file" name="file" id="fileInput" class="input-file uniform_on" size="19" style="opacity: 0;">
		<span class="filename" style="-moz-user-select: none;">No file selected</span><span class="action" style="-moz-user-select: none;">Choose File</span>
		</div>
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