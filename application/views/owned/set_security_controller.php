<script>
	$(document).ready( function () {
<?php
		foreach($operaciones_no_permitidas as $control=>$tipo){
			//$tipo
			//	'button'
			//	'datatable_buttons'
			switch ($tipo){
				case 'button':
?>
					$('#<?php echo $control; ?>').remove();
<?php
					break;
				case 'datatable_buttons':
?>
					<?php echo $control; ?>.button(0).remove();
					<?php echo $control; ?>.button(0).remove();
					<?php echo $control; ?>.button(0).remove();					
<?php
					break;
			}
		}
?>
	})
</script>
