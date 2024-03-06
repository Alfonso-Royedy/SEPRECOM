<!-- Manejador de notificaciones y mensajes -->		

<script>
	$(function(){                 
		$("#span_msg_count").html("<?php echo Obtener_Contador_Mensajes(); ?>");         
	});

	$(function(){                 
		$("#span_notif_count").html("<?php echo Obtener_Contador_Notificaciones(); ?>");         
	});
</script> 
<?php
	if (Obtener_Contador_Notificaciones()>0){
		for ($notif=1;$notif<=Obtener_Contador_Notificaciones();$notif++){
?>
<script>
			$(function(){                 
				$("#notification_<?php echo $notif; ?>").appendTo("#div_notifications_content");         
			});
</script> 
<?php
		}
?>
<script>
		$('#modal_notificaciones').modal();	
</script> 
 <?php
	}else{
?>
<script>				
		$(function(){                 
			$("#div_notifications_content").html("<?php echo "No hay notificaciones para mostrar"; ?>");
		});				
</script>
 <?php
	}		 
	if (Obtener_Contador_Mensajes()>0){
		for ($msg=1;$msg<=Obtener_Contador_Mensajes();$msg++){
?>
<script>
			$(function(){                 
				$("#message_<?php echo $msg; ?>").appendTo("#div_messages_content");         
			});
</script> 
 <?php
		}
	}else{
?>
<script>				
		$(function(){                 
			$("#div_messages_content").html("<?php echo "No hay mensajes para mostrar"; ?>");         
		});				
</script>
 <?php
	}	
?>	