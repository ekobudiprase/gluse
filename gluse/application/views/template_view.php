<!DOCTYPE html>
<html>
	<head>
		<title>Gluse</title>
		<link rel="shortcut icon" type="image/x-icon" href="<?=$parse_uri_root?>assets/img/favicon5.ico">
		<link rel="stylesheet" type="text/css" href="<?=$parse_uri_root?>assets/css/bootstrap-classic.min.css" />
		<link rel="stylesheet" type="text/css" href="<?=$parse_uri_root?>assets/css/uniform.default.css" />
		<link rel="stylesheet" type="text/css" href="<?=$parse_uri_root?>assets/css/style.css" />
		
		<!-- jQuery -->
		<script src="<?=$parse_uri_root?>assets/js/jquery-1.7.2.min.js"></script>
		<!-- jQuery UI -->
		<script src="<?=$parse_uri_root?>assets/js/jquery-ui-1.8.21.custom.min.js"></script>
		<!-- transition / effect library -->
		<script src="<?=$parse_uri_root?>assets/js/bootstrap-transition.js"></script>
		<!-- alert enhancer library -->
		<script src="<?=$parse_uri_root?>assets/js/bootstrap-alert.js"></script>
		<!-- modal / dialog library -->
		<script src="<?=$parse_uri_root?>assets/js/bootstrap-modal.js"></script>
		<!-- custom dropdown library -->
		<script src="<?=$parse_uri_root?>assets/js/bootstrap-dropdown.js"></script>
		<!-- scrolspy library -->
		<script src="<?=$parse_uri_root?>assets/js/bootstrap-scrollspy.js"></script>
		<!-- library for creating tabs -->
		<script src="<?=$parse_uri_root?>assets/js/bootstrap-tab.js"></script>
		<!-- library for advanced tooltip -->
		<script src="<?=$parse_uri_root?>assets/js/bootstrap-tooltip.js"></script>
		<!-- popover effect library -->
		<script src="<?=$parse_uri_root?>assets/js/bootstrap-popover.js"></script>
		<!-- button enhancer library -->
		<script src="<?=$parse_uri_root?>assets/js/bootstrap-button.js"></script>
		<!-- accordion library (optional, not used in demo) -->
		<script src="<?=$parse_uri_root?>assets/js/bootstrap-collapse.js"></script>
		<!-- carousel slideshow library (optional, not used in demo) -->
		<script src="<?=$parse_uri_root?>assets/js/bootstrap-carousel.js"></script>
		<!-- autocomplete library -->
		<script src="<?=$parse_uri_root?>assets/js/bootstrap-typeahead.js"></script>
		<!-- tour library -->
		<script src="<?=$parse_uri_root?>assets/js/bootstrap-tour.js"></script>
		<!-- library for cookie management -->
		<script src="<?=$parse_uri_root?>assets/js/jquery.cookie.js"></script>
		<!-- calander plugin -->
		<script src='<?=$parse_uri_root?>assets/js/fullcalendar.min.js'></script>
		<!-- data table plugin -->
		<script src='<?=$parse_uri_root?>assets/js/jquery.dataTables.min.js'></script>

		<!-- chart libraries start -->
		<script src="<?=$parse_uri_root?>assets/js/excanvas.js"></script>
		<script src="<?=$parse_uri_root?>assets/js/jquery.flot.min.js"></script>
		<script src="<?=$parse_uri_root?>assets/js/jquery.flot.pie.min.js"></script>
		<script src="<?=$parse_uri_root?>assets/js/jquery.flot.stack.js"></script>
		<script src="<?=$parse_uri_root?>assets/js/jquery.flot.resize.min.js"></script>
		<!-- chart libraries end -->

		<!-- select or dropdown enhancer -->
		<script src="<?=$parse_uri_root?>assets/js/jquery.chosen.min.js"></script>
		<!-- checkbox, radio, and file input styler -->
		<script src="<?=$parse_uri_root?>assets/js/jquery.uniform.min.js"></script>
		<!-- plugin for gallery image view -->
		<script src="<?=$parse_uri_root?>assets/js/jquery.colorbox.min.js"></script>
		<!-- rich text editor library -->
		<script src="<?=$parse_uri_root?>assets/js/jquery.cleditor.min.js"></script>
		<!-- notification plugin -->
		<script src="<?=$parse_uri_root?>assets/js/jquery.noty.js"></script>
		<!-- file manager library -->
		<script src="<?=$parse_uri_root?>assets/js/jquery.elfinder.min.js"></script>
		<!-- star rating plugin -->
		<script src="<?=$parse_uri_root?>assets/js/jquery.raty.min.js"></script>
		<!-- for iOS style toggle switch -->
		<script src="<?=$parse_uri_root?>assets/js/jquery.iphone.toggle.js"></script>
		<!-- autogrowing textarea plugin -->
		<script src="<?=$parse_uri_root?>assets/js/jquery.autogrow-textarea.js"></script>
		<!-- multiple file upload plugin -->
		<script src="<?=$parse_uri_root?>assets/js/jquery.uploadify-3.1.min.js"></script>
		<!-- history.js for cross-browser state change on ajax -->
		<script src="<?=$parse_uri_root?>assets/js/jquery.history.js"></script>
		<!-- application script for Charisma demo -->
		<script src="<?=$parse_uri_root?>assets/js/charisma.js"></script>
		<script src="<?=$parse_uri_root?>assets/js/jquery.inputmask.bundle.min.js"></script>



		<link href='<?=$parse_uri_root?>assets/css/opa-icons.css' rel='stylesheet'>
		<link href='<?=$parse_uri_root?>assets/css/style_menu.css' rel='stylesheet'>
	</head>
	<body class="custom-background">
		<div id="sidebar" >
			<div id="header" >
				<div class="ImageLogo">
				
				</div>
			</div>
			<ul id="nav">
				<li class=" menu-header">menu</li>
				<li class="menu"><a href="<?=$parse_uri_root?>index.php/pengelolaan">
					<i class="icon-th-large"></i>
					Pengelolaan Data</a></li>
				<li class="menu"><a href="<?=$parse_uri_root?>index.php/prediksi">
					<i class="icon-th-large"></i>
					Prediksi Jumlah Peminat</a></li>
				<li class="menu"><a href="<?=$parse_uri_root?>index.php/penjadwalan">
					<i class="icon-th-large"></i>
					Penjadwalan Mata Kuliah</a></li>

			</ul>

			<div id="footer">
				ekobudiprase&commat;gmail.com <br />
				Copyright &copy; 2014
			</div>
		</div>
		<div id="content">
			<ul id="breadcrumb">
				<?=$parse_breadcrumbs?> 
			</ul>
			<div id="page">
				
				<?=$parse_template_konten?>

			</div>
		</div>

		<div id="confirm_hapus" class="modal hide fade" tabindex="-1" data-width="760"></div>

	</body>

<script type="text/javascript">
$(document).ready(function(){
   $('.url_back').click(function(){
   		window.location = $(this).attr('href');
   		return false;
   });
});
</script>

</html>