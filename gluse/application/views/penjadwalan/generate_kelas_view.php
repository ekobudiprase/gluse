<div class="page-header">
	<h3>Proses Generate Kelas</h3>
</div>

<form class="form-horizontal form " action="<?=$url_submit?>" enctype="multipart/form-data" method="post">
	<div class="control-group">
	  <label for="typeahead" class="control-label">Batas minimal kelas</label>
	  <div class="controls">
		<input name="batas_jml_kelas_min" type="text" value="5" data-items="3" data-provide="typeahead" id="typeahead " class="span2 typeahead batas_jml_kelas" >
		<!-- <p class="help-block">Angka pertama dan terakhir merupakan layer input dan output</p> -->
	  </div>
	</div>
	<div class="control-group">
	  <label for="typeahead" class="control-label">Batas maksimal kelas</label>
	  <div class="controls">
		<input name="batas_jml_kelas" type="text" value="50" data-items="3" data-provide="typeahead" id="typeahead " class="span2 typeahead batas_jml_kelas" >
		<!-- <p class="help-block">Angka pertama dan terakhir merupakan layer input dan output</p> -->
		<span class="help-inline">* Digunakan ketika mata kuliah tidak ada settingan kelas maksimal</span>
	  </div>
	</div>

	<div class="form-actions">
		<!-- <button class="btn btn-primary" type="submit">Cari</button> -->
		<button class="btn btn-primary" type="submit">&nbsp; Proses &nbsp;</button>
		<a class="url_back" href="<?=$url_matakuliah?>"><button name="back" class="btn ">&nbsp; Back &nbsp;</button></a>
	</div>

</form>

<script type="text/javascript">
$(document).ready(function(){
   // $('.layer_formation').inputmask("Regex", { regex: "([0-9][-])*[0-9]"});  //direct mask
   // $('.layer_formation').inputmask("mask", {"mask": "(999) 999-9999"}); //specifying fn & options
   // $('.layer_formation').inputmask({"mask": "99-9999999"}); //specifying options only
   // $('.layer_formation').inputmask("9-a{1,3}9{1,3}"); //direct mask with dynamic syntax 
   // $('.layer_formation').inputmask({ mask: ["999.999", "aa-aa-aa"]}); //direct mask with dynamic syntax 
   // $("#layer_formation").inputmask("99-9999999");
});
</script>