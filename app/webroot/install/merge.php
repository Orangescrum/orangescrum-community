<?php 
function recurse_copy($src,$dst) { 
	    $dir = opendir($src); 
	   	if(!is_dir($dst)){
	     	mkdir($dst); 
		}
	    while(false !== ( $file = readdir($dir)) ) { 
	        if (( $file != '.' ) && ( $file != '..' )) { 
	            if ( is_dir($src . DS . $file) ) { 
	                recurse_copy($src . DS . $file,$dst . DS . $file); 
	            } 
	            else { 
	                copy($src . DS . $file,$dst . DS . $file); 
	            } 
	        } 
	    } 
	    closedir($dir); 
	} 

	function recurse_merge($src,$dst) { 
	    $dir = opendir($src); 
	    if(!is_dir($dst)){
	     	mkdir($dst); 
		}
	    while(false !== ( $file = readdir($dir)) ) { 
	        if (( $file != '.' ) && ( $file != '..' )) { 
	            if ( is_dir($src . DS . $file) ) { 
	                recurse_merge($src . DS . $file,$dst . DS . $file); 
	            } 
	            else { 
	            	if($file=='constants.php' || $file=='database.php'|| $file=='EmailReply.php' ||$file=='.htaccess')
	            		continue;

	            	// echo $file."<br />";
	                copy($src . DS . $file,$dst . DS . $file); 
	            } 
	        } 
	    } 
	    closedir($dir); 
	} 

	function delete_dir($src) { 
		if(is_dir($src)){
		    $items = scandir($src);
		    foreach ($items as $item) {
		        if ($item === '.' || $item === '..') {
		            continue;
		        }
		        $path = $src.'/'.$item;
		        if (is_dir($path)) {
		            delete_dir($path);
		        } else {
		            unlink($path);
		        }
		    }
		    rmdir($src);
		}

	}
	?>