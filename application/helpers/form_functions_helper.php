<?PHP

function EditBox($id,$name,$class,$style,$tabindex, $max_length,$size,$password,$current_value,$initial_value,$autocomplete,$placeholder,$events){				
	if ((!isset($current_value)) and (isset($initial_value))){
		if ($password){
			echo "<input tabindex='".$tabindex."' type='password' class='".$class."'  style='".$style."' id='".$id."' name='".$name."' maxlength='".$max_length."' size='".$size."' ".$events." value='".$initial_value."' autocomplete='".$autocomplete."' placeholder='".$placeholder."'>";			
		}else{
			echo "<input tabindex='".$tabindex."' type='edit' class='".$class."'  style='".$style."' id='".$id."' name='".$name."' maxlength='".$max_length."' size='".$size."' ".$events." value='".$initial_value."' autocomplete='".$autocomplete."' placeholder='".$placeholder."'>";	
		}		
	}else{
		if ($password){
			echo "<input tabindex='".$tabindex."' type='password' class='".$class."'  style='".$style."' id='".$id."' name='".$name."' maxlength='".$max_length."' size='".$size."' ".$events." value='".$current_value."' autocomplete='".$autocomplete."' placeholder='".$placeholder."'>";
		}else{
			echo "<input tabindex='".$tabindex."' type='edit' class='".$class."'  style='".$style."' id='".$id."' name='".$name."' maxlength='".$max_length."' size='".$size."' ".$events." value='".$current_value."' autocomplete='".$autocomplete."' placeholder='".$placeholder."'>";	
		}		
	}	
	return true;
}

function DateEditBox($id, $name, $class, $style, $tabindex, $max_length, $size, $autocomplete, $placeholder, $events) {
    // Establecer la fecha actual como valor por defecto
    $current_value = date('Y-m-d'); // Fecha actual en formato ISO (AAAA-MM-DD)

    echo "<input tabindex='".$tabindex."' type='date' class='".$class."'  style='".$style."' id='".$id."' name='".$name."' maxlength='".$max_length."' size='".$size."' ".$events." value='".$current_value."' autocomplete='".$autocomplete."' placeholder='".$placeholder."'>";

    return true;
}

function NumberBox($id,$name,$class,$style,$tabindex, $max_length,$size,$current_value,$initial_value,$autocomplete,$placeholder,$events){				
	if ((!isset($current_value)) and (isset($initial_value))){
		echo "<input tabindex='".$tabindex."' type='number' class='".$class."'  style='".$style."' id='".$id."' name='".$name."' maxlength='".$max_length."' size='".$size."' ".$events." value='".$initial_value."' autocomplete='".$autocomplete."' placeholder='".$placeholder."'>";			
	}else{
		echo "<input tabindex='".$tabindex."' type='number' class='".$class."'  style='".$style."' id='".$id."' name='".$name."' maxlength='".$max_length."' size='".$size."' ".$events." value='".$current_value."' autocomplete='".$autocomplete."' placeholder='".$placeholder."'>";		
	}	
	return true;
}

function ComboBox($id,$name,$class,$style,$tabindex,$multiple,$size,$values,$current_value,$initial_value,$events,$select_description){				
	if ($multiple){
		echo "<select tabindex='".$tabindex."' multiple class='".$class."'  style='".$style."' id='".$id."' name='".$name."' size='".$size."' ".$events.">";						
	}else{
		echo "<select tabindex='".$tabindex."' class='".$class."'  style='".$style."' id='".$id."' name='".$name."' size='".$size."' ".$events.">";				
	}		    
		if (($select_description!='')){// and (!isset($current_value))){
			echo "<option value=''>".$select_description."</option>\n";	    	
		}		
		foreach ($values as $value => $description) {		
			if ($value==$current_value){
				echo "<option value='".$value."' selected>".$description."</option>\n";
			}else{								
				if ((!isset($current_value)) and (isset($initial_value))){
					if ($value==$initial_value){
	    				echo "<option value='".$value."' selected>".$description."</option>\n";
					}else{
	    				echo "<option value='".$value."'>".$description."</option>\n";	
	    			}
				}else{
					echo "<option value='".$value."'>".$description."</option>\n";	
				}
			}			
		}		
	echo "</select>";
	return true;
}

function formComboBox($name, $options, $selected_value = null, $extra = array()) {
    $attributes = array(
        'name' => $name,
        'class' => isset($extra['class']) ? $extra['class'] : 'form-control',
        'id' => isset($extra['id']) ? $extra['id'] : $name
    );

    if (isset($extra['multiple']) && $extra['multiple'] == true) {
        $attributes['multiple'] = 'multiple';
    }

    echo form_dropdown($attributes, $options, $selected_value, $extra);
}

function dbComboBox($id,$name,$class,$style,$tabindex,$multiple,$size,$arrayRecords,$column_values,$column_descriptions,$current_value,$initial_value,$events,$select_description){								
	if ($multiple){
		echo "<select tabindex='".$tabindex."' multiple class='".$class."'  style='".$style."' id='".$id."' name='".$name."' size='".$size."' ".$events.">";						
	}else{
		echo "<select tabindex='".$tabindex."' class='".$class."'  style='".$style."' id='".$id."' name='".$name."' size='".$size."' ".$events.">";				
	}		
		if (($select_description!='')){//and (!isset($current_value))){
			echo "<option value=''>".$select_description."</option>\n";	    	
		}								
		foreach($arrayRecords as $record){			
			$col_value='';
			foreach ($column_values as $column_value){
				if ($col_value!=''){
					$col_value = $col_value."@";
				}
				$col_value = $col_value.$record[$column_value];												
			}										
			$col_description='';
			foreach ($column_descriptions as $column_description){
				if ($col_description!=''){
					$col_description = $col_description."-";
				}
				$col_description = $col_description.$record[$column_description];												
			}	
			if ($col_value==$current_value){
				echo "<option value='".$col_value."' selected>".$col_description."</option>\n";
			}else{
				if ((!isset($current_value)) and (isset($initial_value))){
					if ($col_value==$initial_value){
	    				echo "<option value='".$col_value."' selected>".$col_description."</option>\n";
					}else{
	    				echo "<option value='".$col_value."'>".$col_description."</option>\n";
	    			}
				}else{
					echo "<option value='".$col_value."'>".$col_description."</option>\n";
				}										
			}			
		}		
	echo "</select>";
	return true;
}

function RadioGroup($id,$name,$class,$style,$tabindex,$values,$current_value,$initial_value,$events,$group_description){				   
	$i=0;
    echo "<div class='".$class."' style='".$style."'>";
    if ($group_description!=''){
    	echo "<legend>".$group_description."</legend>";
    }	    
	foreach ($values as $value => $description) {															
		echo "<label for='".$id.$value."'>"; 			
		if ($value==$current_value){
  			echo "<input type='radio' name='".$name."' tabindex='".$tabindex."' value='".$value."' id='".$id.$value."' checked ".$events."/> ".$description;
		}else{
			if (($current_value=="") and ($initial_value<>"")){
				if ($value==$initial_value){
    				echo "<input type='radio' name='".$name."' tabindex='".$tabindex."' value='".$value."' id='".$id.$value."' checked ".$events."/> ".$description;
				}else{
	   				echo "<input type='radio' name='".$name."' tabindex='".$tabindex."' value='".$value."' id='".$id.$value."' ".$events."/> ".$description;
	   			}
			}else{
				echo "<input type='radio' name='".$name."' tabindex='".$tabindex."' value='".$value."' id='".$id.$value."' ".$events."/> ".$description;
			} 							
 		}
 		echo "</label>";
 		echo "<br>";
	}					
	echo "</div>";
	return true;
}

function dbRadioGroup($id,$name,$class,$style,$tabindex,$arrayRecords,$column_values,$column_descriptions,$current_value,$initial_value,$events,$group_description){			
	$i=0;
	echo "<div class='".$class."' style='".$style."'>";
	if ($group_description!=''){
	  	echo "<legend>".$group_description."</legend>";
	}
	foreach($arrayRecords as $record){			
		$col_value='';
		foreach ($column_values as $column_value){
			if ($col_value!=''){
				$col_value = $col_value."@";
			}
			$col_value = $col_value.$record[$column_value];												
		}													
		$col_description='';
		foreach ($column_descriptions as $column_description){
			if ($col_description!=''){
				$col_description = $col_description."-";
			}
			$col_description = $col_description.$record[$column_description];												
		}							
 		echo "<label for='".$id.$col_value."'>";
 		if ($col_value==$current_value){
    		echo "<input type='radio' name='".$name."' tabindex='".$tabindex."' value='".$col_value."' id='".$id.$col_value."' checked ".$events."/> ".$col_description;
 		}else{
 			if (($current_value=="") and ($initial_value<>"")){
				if ($col_value==$initial_value){
	   				echo "<input type='radio' name='".$name."' tabindex='".$tabindex."' value='".$col_value."' id='".$id.$col_value."' checked ".$events."/> ".$col_description;
				}else{
	   				echo "<input type='radio' name='".$name."' tabindex='".$tabindex."' value='".$col_value."' id='".$id.$col_value."' ".$events."/> ".$col_description;
	   			}
			}else{
				echo "<input type='radio' name='".$name."' tabindex='".$tabindex."' value='".$col_value."' id='".$id.$col_value."' ".$events."/> ".$col_description;
			} 								
 		}
 		echo "</label>";
 		echo "<br>";
	}			
	echo "</div>";
	return true;
}

function CheckBox($id,$name,$class,$style,$tabindex,$description,$id_value,$current_value,$initial_value,$events){					    			    										
	echo "<div class='".$class."' style='".$style."'>";
 	echo "<label for='".$id.$id_value."'>"; 			
	if ($current_value<>""){
		if ($current_value==$id_value){
			echo "<input type='checkbox' name='".$name."' tabindex='".$tabindex."' id='".$id.$id_value."' value='".$id_value."' checked ".$events."/> ".$description;
		}else{
			echo "<input type='checkbox' name='".$name."' tabindex='".$tabindex."' id='".$id.$id_value."' value='".$id_value."' ".$events."/> ".$description;
		}
	}else{
		if ($initial_value<>""){
			if ($initial_value==$id_value){
				echo "<input type='checkbox' name='".$name."' tabindex='".$tabindex."' id='".$id.$id_value."' value='".$id_value."' checked ".$events."/> ".$description;
			}else{
				echo "<input type='checkbox' name='".$name."' tabindex='".$tabindex."' id='".$id.$id_value."' value='".$id_value."' ".$events."/> ".$description;
			}			
		}else{
			echo "<input type='checkbox' name='".$name."' tabindex='".$tabindex."' id='".$id.$id_value."' value='".$id_value."' ".$events."/> ".$description;
		}		
	} 							
 	echo "</label>"; 			
 	echo "</div>";		
	return true;
}

function nfsCheckBox($id,$name,$class,$style,$tabindex,$description,$id_value,$current_value,$initial_value,$events){					    			    											
	if ($current_value<>""){
		if ($current_value==$id_value){
			echo "<input type='checkbox' style='".$style."' name='".$name."' tabindex='".$tabindex."' id='".$id.$id_value."' value='".$id_value."' checked ".$events."/> ".$description;
		}else{
			echo "<input type='checkbox' style='".$style."' name='".$name."' tabindex='".$tabindex."' id='".$id.$id_value."' value='".$id_value."' ".$events."/> ".$description;
		}
	}else{
		if ($initial_value<>""){
			if ($initial_value==$id_value){
				echo "<input type='checkbox' name='".$name."' tabindex='".$tabindex."' id='".$id.$id_value."' value='".$id_value."' checked ".$events."/> ".$description;
			}else{
				echo "<input type='checkbox' name='".$name."' tabindex='".$tabindex."' id='".$id.$id_value."' value='".$id_value."' ".$events."/> ".$description;
			}			
		}else{
			echo "<input type='checkbox' name='".$name."' tabindex='".$tabindex."' id='".$id.$id_value."' value='".$id_value."' ".$events."/> ".$description;
		}
	} 							
	return true;
}

function TextArea($id,$name,$class,$style,$tabindex, $rows,$cols,$current_value,$initial_value,$events){				
	if ((!isset($current_value)) and (isset($initial_value))){
		echo "<textarea tabindex='".$tabindex."' class='".$class."'  style='".$style."' id='".$id."' name='".$name."' rows='".$rows."' cols='".$cols."' ".$events.">".$initial_value."</textarea>";				
	}else{		
		echo "<textarea tabindex='".$tabindex."' class='".$class."'  style='".$style."' id='".$id."' name='".$name."' rows='".$rows."' cols='".$cols."' ".$events.">".$current_value."</textarea>";					
	}	
	return true;
}

function DateTimeBox($id,$name,$class,$style,$tabindex, $format,$current_value,$initial_value,$events){	
	if ((!isset($current_value)) and (isset($initial_value))){
		echo "<div class='input-group date' id='div_".$id."' data-date='".$initial_value."'>";
			echo "<input type='text' id='".$id."' name='".$name."' tabindex='".$tabindex."' class='".$class."' value='".$initial_value."'>";								 
			echo "<span class='input-group-addon'><i class='glyphicon glyphicon-calendar'></i></span>";								
		echo "</div>";													
	}else{
		echo "<div class='input-group date' id='div_".$id."' data-date='".$current_value."'>";
			echo "<input type='text' id='".$id."' name='".$name."' tabindex='".$tabindex."' class='".$class."' value='".$current_value."'>";								 
			echo "<span class='input-group-addon'><i class='glyphicon glyphicon-calendar'></i></span>";								
		echo "</div>";											
	}	
?>
<script>			
	$('#div_<?php echo $name; ?>').datetimepicker({
		format: '<?php echo $format; ?>', 
		locale: 'es', 
		showTodayButton: true	
	});						    	
</script>		
<?php	
	return true;
}

?>