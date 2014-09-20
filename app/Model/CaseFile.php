<?php
class CaseFile extends AppModel{
	var $name = 'CaseFile';
/**
	 * This method calculate the total storage used by user.
	 * 
	 * @author Orangescrum
	 * @method getStorage
	 * @param
	 * @return string
	*/
	var $cacheQueries = false;
	function getStorage(){
	   $this->recursive = -1;
	   $sql = "SELECT SUM(file_size) AS file_size  FROM case_files   WHERE company_id = '".SES_COMP."'";
	   $res1 = $this->query($sql);
	   $filesize = $res1['0']['0']['file_size']/1024;
	   return number_format($filesize,2);
	}						
}
?>