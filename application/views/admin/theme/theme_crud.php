
<?php 

foreach($css_files as $file): ?>
	<link type="text/css" rel="stylesheet" href="<?php echo $file; ?>" />
<?php endforeach; ?>

<?php $i=0; foreach($js_files as $file): if($i==1) { ?>

	<script> var $crud = $.noConflict(); </script>

	<?php }  $i++; ?>
	
	<script src="<?php echo $file; ?>"></script>
<?php endforeach; ?>

<?php $this->load->view('admin/theme/message'); ?>
<?php echo $output; ?>
<style>
	.quickSearchBox{display:block !important;}
</style>