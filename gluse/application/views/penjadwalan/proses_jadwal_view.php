<div class="page-header">
	<h3>Proses Prediksi</h3>
</div>

<form class="form-horizontal form " action="<?=$url_submit?>" enctype="multipart/form-data" method="post">

	<div class="control-group">
	  <label for="typeahead" class="control-label">Rule </label>
	  <div class="controls">
		<div class="alert alert-info"> 
			<strong>Aturan Wajib (Hard Constraint): </strong>
			<ol>
				<li>Durasi kelas mata kuliah tidak lebih dari jam 17.20 pada hari yang sama.</li>
				<li>Durasi kelas mata kuliah tidak diadakan pada hari Jumat jam 11.30 - 13.00 .</li>
				<li>Kelas mata kuliah yang satu paket tidak diadakan pada waktu yang sama.</li>
				<li>Kapasitas ruang kelas mata kuliah lebih dari atau sama dengan jumlah peserta.</li>
				<li>Dosen tidak mengajar lebih dari satu kelas mata kuliah pada waktu yang sama.</li>
				<li>Pertemuan kelas mata kuliah yang lebih dari satu kali diadakan pada hari yang berbeda.</li>
				<li>Kelas mata kuliah wajib yang berdekatan jenis semesternya (ganjil/genap) pada paket semester yang sama tidak diadakan pada waktu yang sama.</li>
				<li>Kelas yang memiliki mata kuliah yang sama (kelas paralel) diadakan pada waktu yang sama.</li>
			</ol>

			<strong>Aturan Tidak Wajib (Soft Constraint):</strong>
			<ol>

				<li>Kelas mata kuliah pilihan dengan kelas mata kuliah wajib pada paket semester yang berdekatan jenis semesternya (ganjil/genap) tidak diadakan pada waktu yang sama.</li>
				<li>Kelas mata kuliah diadakan pada ruang yang sesuai dengan program studi.</li>
				<li>Kelas mata kuliah yang memiliki paket yang sama maksimal berjumlah 8 sks dalam sehari.</li>
				<li>Ruang kelas ditempati minimal sejumlah prosentase dari kapasitasnya.</li>
				<li>Waktu kelas mata kuliah diadakan sesuai dengan rekomendasi waktu dosen pengajar yang bersangkutan.</li>
			</ol>
		</div>
	  </div>
	</div>

	<div class="control-group">
	  <label for="typeahead" class="control-label">Jumlah individu dalam populasi</label>
	  <div class="controls">
		<input name="jml_individu" type="text" value="30" data-items="1" data-provide="typeahead" id="typeahead " class="span2 typeahead layer_formation" >
		<!-- <p class="help-block">Angka pertama dan terakhir merupakan layer input dan output</p> -->
	  </div>
	</div>

	<div class="control-group">
	  <label for="typeahead" class="control-label">Pc</label>
	  <div class="controls">
		<input name="pc" type="text" value="0.6" data-items="4" data-provide="typeahead" id="typeahead" class="span1 typeahead">
		<!-- <p class="help-block">Start typing to activate auto complete!</p> -->
	  </div>
	</div>

	<div class="control-group">
	  <label for="typeahead" class="control-label">Pm</label>
	  <div class="controls">
		<input name="pm" type="text" value="0.4" data-items="4" data-provide="typeahead" id="typeahead" class="span1 typeahead">
		<!-- <p class="help-block">Start typing to activate auto complete!</p> -->
	  </div>
	</div>

	<div class="control-group">
	  <label for="typeahead" class="control-label">Jumlah Generasi</label>
	  <div class="controls">
		<input name="generation" type="text" value="10" data-items="4" data-provide="typeahead" id="typeahead" class="span1 typeahead">
		<!-- <p class="help-block">Start typing to activate auto complete!</p> -->
	  </div>
	</div>

	<div class="form-actions">
		<!-- <button class="btn btn-primary" type="submit">Cari</button> -->
		<button class="btn btn-primary" type="submit">&nbsp; Proses &nbsp;</button>
		<a class="url_back" href="<?=$url_kelas?>"><button name="back" class="btn ">&nbsp; Back &nbsp;</button></a>
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