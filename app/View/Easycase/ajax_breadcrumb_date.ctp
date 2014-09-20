<?php
$types = "";
if(isset($Date) && !empty($Date)){
	if(trim($Date) == 'one'){
		echo "Past hour";
	}else if(trim($Date) == '24'){
		echo "Past 24Hour";
	}else if(trim($Date) == 'week'){
		echo "Past Week";
	}else if(trim($Date) == 'month'){
		echo "Past month";
	}else if(trim($Date) == 'year'){
		echo "Past Year";
	}else if(strstr(trim($Date),":")){
		echo str_replace(":"," - ",$Date);
	}
}else { echo "Any Time"; }
?>
