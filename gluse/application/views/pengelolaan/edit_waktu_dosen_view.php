<div class="page-header">
	<h3><?=$judul?> Waktu Dosen</h3>
</div>

<div class="alert alert-<?=$notif_style?>" <?=$display?>>
	<button data-dismiss="alert" class="close" type="button"><i class="icon-remove"></i></button>
	<?=$notif_message?>
</div>

<form class="form-horizontal form " action="<?=$url_submit?>" enctype="multipart/form-data" method="post">
	<div class="control-group">
	  <label for="typeahead" class="control-label">Dosen</label>
	  <div class="controls">
	  	<?php
		if ($filter['id']=='') {			
			echo '<input id="nama" name="nama" type="text" value="'.$data["nama"].'" data-items="4" data-provide="typeahead" id="typeahead" class="span4 typeahead">';
	  	}else{
	  		echo '<span class="input-xlarge uneditable-input">'.$data["nama"].'</span>';
	  	}
		?>
	  	
		<input type="hidden" value="<?=$data['id_dosen']?>" name="id_dosen" id="id_dosen" />
		<span class="help-inline">*</span>
		<?php
		if ($filter['id']=='') {			
			echo '<a class="get_dosen" href="'.$url_pilih_dosen.'"><button name="back" class="btn btn-inverse">&nbsp; Pilih dosen &nbsp;</button></a>';
	  	}
		?>
		
	  </div>
	</div>

	<div class="control-group">
	  <label for="typeahead" class="control-label">Waktu</label>

	  <div class="controls">
	  	<a class="get_waktu" href="<?=$url_pilih_waktu?>"><button name="back" class="btn btn-inverse">&nbsp; Pilih waktu &nbsp;</button></a> *
	  	<br>
	  	<table id="table_time" class="table table-bordered table-striped table-condensed table-nonfluid" style="margin-top:10px;">
	  		
	  		<thead>
	  		<tr>
	  			<th class="span2">Jam ke-</th>
	  			<th>Hari</th>
	  			<th>Jam</th>
	  			<th class="span1">Aksi</th>
	  		</tr>
	  		</thead>
	  		<tbody>
	  			<?=$tabel_waktu?>
	  		</tbody>
	  	</table>
	  </div>
	</div>


	<div class="form-actions">
		<input type="hidden" name="id" value="<?=$filter['id']?>" />
		<!-- <button class="btn btn-primary" type="submit">Cari</button> -->
		<button class="btn btn-primary" type="submit">&nbsp; Simpan &nbsp;</button>
		<a class="url_back" href="<?=$url_waktu_dosen?>"><button name="back" class="btn ">&nbsp; Back &nbsp;</button></a>
	</div>

</form>

<div id="responsive" class="modal hide fade" tabindex="-1" data-width="760"></div>
<div id="waktune" class="modal hide fade" tabindex="-1" data-width="760"></div>


<script type="text/javascript">
$(function(){

	$modal_prodi = $("#responsive");
	$modal_waktune = $("#waktune");

	$(".get_dosen" ).on( "click", function() {

		urle = $(this).attr('href');
		// idp = $(this).attr('id');
		// alert(urle);
		
		$.ajax({
			type: "POST",
			// url: "http://localhost/gluse/index.php/penjadwalan/get_dosen",
			url: urle
			// data: { id_ru: idp }
		}).done(function( msg ) {
			$('#responsive').html(msg);
			$modal_prodi.modal('show');
		});
		
		return false;
	});

	$(".get_waktu" ).on( "click", function() {
		url = $(this).attr('href');		
		$.ajax({
			type: "POST",
			// url: "http://localhost/gluse/index.php/penjadwalan/get_dosen",
			url: url
			// data: { id_ru: idp }
		}).done(function( msg ) {
			$('#waktune').html(msg);
			$modal_waktune.modal('show');
		});
		
		return false;
	});

	$('.remove').live("click",function() {
		tr = $(this).parents('tr');
		test = tr.attr('class');
		// alert(test);

		tr.remove();

		return false;
	});

});
</script>