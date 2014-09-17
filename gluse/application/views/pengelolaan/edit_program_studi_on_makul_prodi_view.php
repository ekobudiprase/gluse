<div class="page-header">
	<h3><?=$judul?> Program Studi : <?=$makul['kode']?> - <?=$makul['nama']?></h3>
</div>

<div class="alert alert-<?=$notif_style?>" <?=$display?>>
	<button data-dismiss="alert" class="close" type="button"><i class="icon-remove"></i></button>
	<?=$notif_message?>
</div>

<form class="form-horizontal form " action="<?=$url_submit?>" enctype="multipart/form-data" method="post">
	
	<div class="control-group">
		<label class="control-label" for="selectError1">Program Studi</label>
		<div class="controls">
			<?=$cb_prodi?>
			<span class="help-inline">*</span>
		</div>
	</div>

	<div class="control-group">
		<label class="control-label" for="selectError1">Program Studi Parent</label>
		<div class="controls">
			<?=$cb_prodi_parent?>
			<span class="help-inline"></span>
		</div>
	</div>

	<div class="control-group">
	  <label for="typeahead" class="control-label">Porsi Kelas</label>

	  <div class="controls">
	  	<input name="porsi" type="text" value="<?=$data['porsi']?>" data-items="4" data-provide="typeahead" id="typeahead" class="span1 typeahead">
		<span class="help-inline"></span>
	  </div>
	</div>


	<div class="form-actions">
		<input type="hidden" name="idmk" value="<?=$idmk?>" />
		<input type="hidden" name="id" value="<?=$filter['id']?>" />
		<!-- <button class="btn btn-primary" type="submit">Cari</button> -->
		<button class="btn btn-primary" type="submit">&nbsp; Simpan &nbsp;</button>
		<a class="url_back" href="<?=$url_back?>"><button name="back" class="btn ">&nbsp; Back &nbsp;</button></a>
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