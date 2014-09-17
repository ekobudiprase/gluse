<div id="content_modal">

<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="icon-remove"></i></button>
	<h3>Konfirmasi Hapus</h3>
</div>

<form id="myform" class="myform" method="post" name="myform" action="<?=$url_submit?>" >
<div class="modal-body">

<div class="row-fluid">

Hapus <strong><?=$data['nama']?></strong> ?

</div>

</div>
<div class="modal-footer">
	<input type="hidden" name="id" id="id" value="<?=$filter['id']?>" />
	<button type="button" data-dismiss="modal" class="btn">Close</button>
	<input type="submit" id="submit" class="btn btn-danger" value="Hapus" ></button>
</div>
</form>

</div>