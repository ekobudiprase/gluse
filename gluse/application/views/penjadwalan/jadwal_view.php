<style type="text/css">
#page {
}
</style>
<div class="page-header">
	<h3>Jadwal Kuliah</h3>
</div>

<div class="alert alert-<?=$notif_style?>" <?=$display?>>
	<button data-dismiss="alert" class="close" type="button"><i class="icon-remove"></i></button>
	<?=$notif_message?>
</div>

<div class="btn_add">
	<a href="<?=$url_cetak_excel?>">
		<button class="btn btn-success">Cetak xls</button>
	</a>
</div>
<br>



<div style="height:550px; overflow-x: auto;margin-top: 30px;">
<table class="table table-bordered table-striped table-nonfluid" style="max-width:1000%; width:600%; ">
	<?=$table_header?>
	<?=$table_body?>

</table>
</div>

<script language="javascript">
$(function(){

});
</script>