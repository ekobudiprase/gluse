<div class="page-header">
	<h3>Proses Prediksi</h3>
</div>

<form class="form-horizontal form " action="<?=$url_submit?>" enctype="multipart/form-data" method="post">
	<div class="control-group">
	  <label for="typeahead" class="control-label">Formasi layer</label>
	  <div class="controls">
		<input name="layer_formation" type="hidden" value="3-3-1" data-items="1" data-provide="typeahead" id="typeahead " class="span2 typeahead layer_formation" >
		<input name="layer_formation_dis" disabled type="text" value="3-3-1" data-items="1" data-provide="typeahead" id="typeahead " class="span2 typeahead layer_formation" >
		<p class="help-block">Angka pertama dan terakhir merupakan layer input dan output</p>
	  </div>
	</div>

	<div class="control-group">
	  <label for="typeahead" class="control-label">Learning rate (Betha) </label>
	  <div class="controls">
		<input name="beta" type="text" value="0.3" data-items="4" data-provide="typeahead" id="typeahead" class="span1 typeahead">
		<!-- <p class="help-block">Start typing to activate auto complete!</p> -->
	  </div>
	</div>

	<!-- <div class="control-group">
	  <label for="typeahead" class="control-label">Momentum (Alpha)</label>
	  <div class="controls">
		<input name="alpha" type="text" value="0.1" data-items="4" data-provide="typeahead" id="typeahead" class="span1 typeahead">
	  </div>
	</div> -->

	<div class="control-group">
	  <label for="typeahead" class="control-label">Epoch</label>
	  <div class="controls">
		<input name="epoch" type="text" value="200000" data-items="4" data-provide="typeahead" id="typeahead" class="span1 typeahead">
		<!-- <p class="help-block">Start typing to activate auto complete!</p> -->
	  </div>
	</div>

	<div class="control-group">
	  <label for="typeahead" class="control-label">Treshold</label>
	  <div class="controls">
		<input name="treshold" type="text" value="0.00001" data-items="4" data-provide="typeahead" id="typeahead" class="span1 typeahead">
		<!-- <p class="help-block">Start typing to activate auto complete!</p> -->
	  </div>
	</div>

	<div class="form-actions">
		<!-- <button class="btn btn-primary" type="submit">Cari</button> -->
		<button class="btn btn-primary" type="submit">&nbsp; Proses &nbsp;</button>
		<a class="url_back" href="<?=$url_rekap_matakuliah?>"><button name="back" class="btn ">&nbsp; Back &nbsp;</button></a>
	</div>

</form>

<form class="form-horizontal form " action="<?=$url_submit?>" enctype="multipart/form-data" method="post" <?=$display?> >
	<div class="control-group">
	  <label for="typeahead" class="control-label">Momentum (Alpha)</label>
	  <div class="controls">
		<input name="alpha" type="text" value="0.1" data-items="4" data-provide="typeahead" id="typeahead" class="span1 typeahead">
		<!-- <p class="help-block">Start typing to activate auto complete!</p> -->
	  </div>
	</div>

	<div class="form-actions">
		<!-- <button class="btn btn-primary" type="submit">Cari</button> -->
		<button class="btn btn-primary" type="submit">&nbsp; Proses &nbsp;</button>
		<a class="url_back" href="<?=$url_rekap_matakuliah?>"><button name="back" class="btn ">&nbsp; Back &nbsp;</button></a>
	</div>

</form>

<script type="text/javascript">
$(document).ready(function(){
   $('.layer_formation').inputmask("Regex", { regex: "([0-9][-])*[0-9]"});  //direct mask
   // $('.layer_formation').inputmask("mask", {"mask": "(999) 999-9999"}); //specifying fn & options
   // $('.layer_formation').inputmask({"mask": "99-9999999"}); //specifying options only
   // $('.layer_formation').inputmask("9-a{1,3}9{1,3}"); //direct mask with dynamic syntax 
   // $('.layer_formation').inputmask({ mask: ["999.999", "aa-aa-aa"]}); //direct mask with dynamic syntax 
   // $("#layer_formation").inputmask("99-9999999");
});
</script>