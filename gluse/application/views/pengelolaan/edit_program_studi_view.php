<div class="page-header">
	<h3><?=$judul?> Program Studi</h3>
</div>

<div class="alert alert-<?=$notif_style?>" <?=$display?>>
	<button data-dismiss="alert" class="close" type="button"><i class="icon-remove"></i></button>
	<?=$notif_message?>
</div>

<form class="form-horizontal form " action="<?=$url_submit?>" enctype="multipart/form-data" method="post">
	<div class="control-group">
	  <label for="typeahead" class="control-label">Kode</label>
	  <div class="controls">
	  	<input name="kode" type="text" value="<?=$data['kode']?>" data-items="4" data-provide="typeahead" id="typeahead" class="span4 typeahead">
		<span class="help-inline">*</span>
	  </div>
	</div>

	<div class="control-group">
	  <label for="typeahead" class="control-label">Nama</label>

	  <div class="controls">
	  	<input name="nama" type="text" value="<?=$data['nama']?>" data-items="4" data-provide="typeahead" id="typeahead" class="span6 typeahead">
		<span class="help-inline">*</span>
	  </div>
	</div>

	<div class="control-group">
	  <label for="typeahead" class="control-label">Akronim</label>

	  <div class="controls">
	  	<input name="akronim" type="text" value="<?=$data['akronim']?>" data-items="4" data-provide="typeahead" id="typeahead" class="span1 typeahead">
		<span class="help-inline">*</span>
	  </div>
	</div>


	<div class="form-actions">
		<input type="hidden" name="id" value="<?=$filter['id']?>" />
		<!-- <button class="btn btn-primary" type="submit">Cari</button> -->
		<button class="btn btn-primary" type="submit">&nbsp; Simpan &nbsp;</button>
		<a class="url_back" href="<?=$url_program_studi?>"><button name="back" class="btn ">&nbsp; Back &nbsp;</button></a>
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