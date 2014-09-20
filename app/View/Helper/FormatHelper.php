<?php
App::import('Vendor', 's3', array('file' => 's3'.DS.'S3.php'));
class FormatHelper extends AppHelper {
	
 	function getRealIpAddr()
	{
		$ip = $_SERVER['REMOTE_ADDR'];
		if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4 | FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE) === false) {
			return false;
		}
		if($this->is_private_ip($ip)) {
			return false;
		}
		return $ip;
		
	}
	function is_private_ip($ip) {
		if (empty($ip) or !ip2long($ip)) {
			return false;
		}
		$private_ips = array (
			array('10.0.0.0','10.255.255.255'),
			array('172.16.0.0','172.31.255.255'),
			array('192.168.0.0','192.168.255.255')
		);
		$ip = ip2long($ip);
		foreach ($private_ips as $ipr) {
			$min = ip2long($ipr[0]);
			$max = ip2long($ipr[1]);
			if (($ip >= $min) && ($ip <= $max)) return true;
		}
		return false;
	}
	function getUserDtls($uid) {
		$User = ClassRegistry::init('User');
		$User->recursive = -1;
		$usrDtls = $User->find('first', array('conditions'=>array('User.id' => $uid), 'fields' => array('User.name','User.photo','User.email','User.last_name')));
		return $usrDtls;
	}
	function displayStorage($value,$flag=0) {
		if(strtolower($value) !='unlimited' && $value){
			if($value < 1024) {
				return $value." MB";
			}
			else {
				if(!$flag){
					return number_format(($value/1024),1,'.','')." GB";
				}else{
					return round(($value/1024))." GB";
				}
			}
		}else{
			return $value;
		}
	}
	function longstringwrap($string = "")
	{
		return $string;
		//return preg_replace_callback( '/\w{10,}/', create_function( '$matches', 'return chunk_split( $matches[0], 5, "&#8203;" );' ), $string );
	}
	function checkProjLimit($limit = NULL)
	{
		$Project = ClassRegistry::init('Project');
		$Project->recursive = -1;
		$totProj = $Project->find('count', array('conditions' => array('Project.company_id'=>SES_COMP),'fields' => 'DISTINCT Project.id'));
		return $totProj;
	}
	function checkCountMilestone($limit = NULL)
	{
		$Milestone = ClassRegistry::init('Milestone');
		$Milestone->recursive = -1;
		$totMlstone = $Milestone->find('count', array('conditions' => array('Milestone.company_id'=>SES_COMP),'fields' => 'DISTINCT Milestone.id'));
		return $totMlstone;
	}
	function checkUsrLimit($limit = NULL)
	{
		App::import('Model','UserInvitation'); $UserInvitation = new UserInvitation();
		$UserInvitation->recursive = -1;
		$totUsr = $UserInvitation->find('count', array('conditions' => array('UserInvitation.company_id'=>SES_COMP),'fields' => 'DISTINCT UserInvitation.id'));
		// 1 is added for the company owner account as its not inserted into the invitation table 
		$totUsr =$totUsr+1;
		return $totUsr;
	}
	function getStatus($type,$legend){
		if($type == 10) {
			return '<div class="label update">Update</div>';
		} else if($legend == 1) {
			return '<div class="label new">New</div>';
		} else if($legend == 2 || $legend == 4) {
			return '<div class="label wip">In Progress</div>';
		}
		if($legend == 3) {
			return '<div class="label closed">Closed</div>';
		} else if($legend == 4) {
			return '<div class="label wip">In Progress</div>';
		} else if($legend == 5) {
			return '<div class="label resolved">Resolved</div>';
		}
	}
	function fixtags($text){
		//$text = htmlspecialchars($text);
		$text = preg_replace("/=/", "=\"\"", $text);
		$text = preg_replace("/&quot;/", "&quot;\"", $text);
		$tags = "/&lt;(\/|)(\w*)(\ |)(\w*)([\\\=]*)(?|(\")\"&quot;\"|)(?|(.*)?&quot;(\")|)([\ ]?)(\/|)&gt;/i";
		$replacement = "<$1$2$3$4$5$6$7$8$9$10>";
		$text = preg_replace($tags, $replacement, $text);
		$text = preg_replace("/=\"\"/", "=", $text);
		return $text;
	}
	function emailText($value)
	{
		$value = stripslashes(trim($value));
		$value = str_replace("“","\"",$value);
        $value = str_replace("”","\"",$value);
		$value = str_replace("�","\"",$value);
		$value = str_replace("�","\"",$value);
		//$value = preg_replace('/[^(\x20-\x7F)\x0A]*/','', $value);
		$value = $this->fixtags($value);
		$value = html_entity_decode($value, ENT_QUOTES);
		return stripslashes($value);
	}
	function getBrowser(){
		$browser = $_SERVER['HTTP_USER_AGENT'];
		if(strstr($browser,"Safari") && !strstr($browser,"Chrome")){
			$agent = "S";
		}
		elseif(strstr($browser,"Firefox")){
			$agent = "F";
		}
		elseif(strstr($browser,"Chrome")){
			$agent = "C";
		}
		elseif(strstr($browser,"MSIE")){
			$agent = "I";
		}
		return $agent;
	}
	 function pub_file_exists($folder,$fileName){ //echo $fileName;exit;
          $s3 = new S3(awsAccessKey, awsSecretKey);
          $info = $s3->getObjectInfo(BUCKET_NAME, $folder.$fileName);
		if ($info){
			//File exists
			return true;
		}
		else{
			//File doesn't exists
			return false;
		}
     }
	function imageExists($dir,$image){
		if($image && file_exists($dir.$image)){
			return true;
		}
		else{
			return false;
		}
	}
	function pagingShowRecords($total_records,$page_limit,$page)
	{
		$numofpages = $total_records / $page_limit;
		for($j = 1; $j <= $numofpages; $j++) { }
		$start = $page*$page_limit - $page_limit;
		if($page == $j)
		{
			$start1 = $start+1;
			$retRec = $start1." - ".$total_records." of ".$total_records;
		}
		else
		{
			$start1 = $start+1;
			$retRec = $start1." - ".$page*$page_limit." of ".$total_records;
		}
		return $retRec;
	}
	function formatText($value)
	{
		/* commented for supporting multi language
		
		$value = str_replace("“","\"",$value);
        $value = str_replace("”","\"",$value);
		$value = str_replace("�","\"",$value);
		$value = str_replace("�","\"",$value);
		$value = preg_replace('/[^(\x20-\x7F)\x0A]/','', $value);
		$value = stripslashes($value);
		$value = html_entity_decode($value, ENT_QUOTES);
		$trans = get_html_translation_table(HTML_ENTITIES, ENT_QUOTES);
		$value = strtr($value, $trans);
		$value = stripslashes(trim($value));
		return $value;*/
		
		$value = str_replace("“","\"",$value);
		$value = str_replace("”","\"",$value);
		$value = str_replace("�","\"",$value);
		$value = str_replace("�","\"",$value);
		$value = stripslashes($value);
		$value = html_entity_decode($value, ENT_QUOTES,'UTF-8');
		$value = stripslashes(trim($value));
		return $value;
	}
	function paragraph_trim($content) {
		$result = preg_replace('!(^(\s*<p>(\s|&nbsp;)*</p>\s*)*|(\s*<p>(\s|&nbsp;)*</p>)*\s*\Z)!em', '', $content);
		return $result === NULL ? $content : $result;
	}
	
	function formatCms($value)
	{
		$value = stripslashes(trim($value));
		$value = str_replace("�","\"",$value);
		$value = str_replace("�","\"",$value);
		//$value = preg_replace('/[^(\x20-\x7F)\x0A]*/','', $value);
		$value = str_replace("~","&#126;",$value);
		
		$value = str_replace("a href=","a style='text-decoration:underline;color:#066D99' target='_blank' href=",$value);
		
		/*$value = str_replace("<ul>","<ul style='list-style:disc;'>",$value);*/
		//$value = $this->makeURL($value);
		
		// /(http:\/\/)?([a-zA-Z0-9\-.]+\.[a-zA-Z0-9\-]+([\/]([a-zA-Z0-9_\/\-.?&%=+])*)*)/
		
		$value = preg_replace("/\b(?:(?:https?|ftp):\/\/|www\.)[-a-z0-9+&@#\/%?=~_|!:,.;]*[-a-z0-9+&@#\/%=~_|]/i", '<a href="http://\\0"target="_blank">\\0</a>', $value);
		
		if(stristr($value,"http://http://")) {
			$value = str_replace("http://http://","http://",$value);
		}
		if(stristr($value,"http://http//")) {
			$value = str_replace("http://http//","http://",$value);
		}
		if(stristr($value,"https://https://")) {
			$value = str_replace("https://https://","https://",$value);
		}
		if(stristr($value,"https://https//")) {
			$value = str_replace("https://https//","https://",$value);
		}
		if(stristr($value,"http://https://")) {
			$value = str_replace("http://https://","https://",$value);
		}
		return stripslashes($value);
	}
	function shortLength($value, $len)
	{
		$value_format = $this->formatText($value);
		$value_raw = html_entity_decode($value_format, ENT_QUOTES);
		if(strlen($value_raw) > $len)
		{
			$value_strip = substr($value_raw,0,$len);
			$value_strip = $this->formatText($value_strip);
			$lengthvalue = $value_strip."...";
		}
		else
		{
			$lengthvalue = $value_format;
		}
		return $lengthvalue;
	}
	function shortLengthCMS($value, $len)
	{
		$value = stripslashes($value);
		$value = str_replace("�","\"",$value);
		$value = str_replace("�","\"",$value);
		//$value = preg_replace('/[^(\x20-\x7F)\x0A]*/','', $value);
		$value = str_replace("~","&#126;",$value);
		$value = strip_tags($value);
		$value = trim($value);
		
		if(strlen($value) > $len) {
			$value_strip = substr($value,0,$len);
			$lengthvalue = $value_strip."...";
		}
		else {
			$lengthvalue = $value;
		}
		//$lengthvalue = preg_replace('/[^(\x20-\x7F)\x0A]*/','', $lengthvalue);
		return $lengthvalue;
	}
	function displayStatus($st)
	{
		if($st == 1) {
			$status = "New";
		}
		elseif($st == 2) {
			$status = "In Progress";
		}
		elseif($st == 3) {
			$status = "Closed";
		}
		elseif($st == 4) {
			$status = "Started";
		}
		elseif($st == 5) {
			$status = "Resolved";
		}
		elseif($st == "hctta") {
			$status = "Files";
		}
		elseif($st == "dpu") {
			$status = "Updates";
		}
		else {
			$status = "All";
		}
		return $status;
	}
	function getFileType($file_type){
		$oldname = strtolower($file_type);
	    $ext = substr(strrchr($oldname, "."), 1);
		
		if($ext == 'pdf') {
			return '<div class="pdf_file cmn_fl fl"></div>';
		}else if($ext == 'doc' || $ext == 'docx' || $ext == 'rtf' || $ext == 'odt' || $ext == 'dotx' || $ext == 'docm'){
			return '<div class="doc_file cmn_fl fl"></div>';
		}else if($ext == 'xls' || $ext == 'xlsx' || $ext == 'ods'){
			return '<div class="xls_file cmn_fl fl"></div>';
		}else if($ext == 'png'){
			return '<div class="png_file cmn_fl fl"></div>';
		}else if($ext == 'tif'){
			return '<div class="tif_file cmn_fl fl"></div>';
		}else if($ext == 'bmp'){
			return '<div class="bmp_file cmn_fl fl"></div>';
		}else if($ext == 'gif'){
			return '<div class="png_file cmn_fl fl"></div>';
		}else if($ext == 'jpg' || $ext == 'jpeg'){
			return '<div class="jpg_file cmn_fl fl"></div>';
		}else if($ext == 'zip' || $ext == 'rar' || $ext == 'gz'){
			return '<div class="zip_file cmn_fl fl"></div>';
		}else{
			return '<div class="html_file cmn_fl fl"></div>';
		}
	}
	function imageType($filename,$width1,$height1,$link,$downloadUrl = NULL, $is_ext=NULL)
	{
		 if($width1 != 0) {
			 $width = "width='".$width1."'";
		 }
		 else {
			 $width = "";
		 }
		 if($height1 != 0) {
			 $height = "height='".$height1."'";
		 }
		 else {
			 $height = "";
		 }
		 
		 $oldname = strtolower($filename);
		 $ext = substr(strrchr($oldname, "."), 1);
		 
		 if($link == 1) {
		     if(isset($downloadUrl) && trim($downloadUrl)){ //By Orangescrum
			 $links1 = "<a href='".$downloadUrl."' target='_blank' style='font:bold 11px verdana;text-transform:uppercase;color:#000000'>";
		     }else{
			$links1 = "<a href='".HTTP_ROOT."easycases/download/".$filename."' style='font:bold 11px verdana;text-transform:uppercase;color:#000000'>";
		     }
			$links2 = "</a>";
		 }
		 else {
			 $links1 = "";
			 $links2 = "";
		 }
		 
		 $style = "style='border:0px solid #C3C3C3'";
		 
		 if(isset($is_ext)) {
		     return $ext;
		 }
		 
		 if($ext == "zip") {
			 $image = $links1."<img src='".HTTP_IMAGES."images/case/zip.png' alt='[zip]' title='".$filename."' ".$width." ".$height." border='0' ".$style." />".$links2;
		 }
		 elseif($ext == "rar") {
			 $image = $links1."<img src='".HTTP_IMAGES."images/case/rar.png' alt='[rar]' title='".$filename."' ".$width." ".$height." border='0' ".$style." />".$links2;
		 }
		 elseif($ext == "xls" || $ext == "xlsx"){
			 $image = $links1."<img src='".HTTP_IMAGES."images/case/xls.png' alt='[xls]' title='".$filename."' ".$width." ".$height." border='0' ".$style." />".$links2;
		 }
		 elseif($ext == "doc" || $ext == "docx" || $ext == "rtf") {
			 $image = $links1."<img src='".HTTP_IMAGES."images/case/doc.png' alt='[doc]' title='".$filename."' ".$width." ".$height." border='0' ".$style." />".$links2;
		 }
		 elseif($ext == "txt") {
			 $image = $links1."<img src='".HTTP_IMAGES."images/case/txt.png' alt='[txt]' title='".$filename."' ".$width." ".$height." border='0' ".$style." />".$links2;
		 }
		 elseif($ext == "jpg" || $ext == "jpeg") {
			 $image = "<img src='".HTTP_IMAGES."images/case/jpg.png' alt='[jpg]' title='".$filename."' ".$width." ".$height." border='0' ".$style." />";
		 }
		 elseif($ext == "png") {
			  $image = "<img src='".HTTP_IMAGES."images/case/png.png' alt='[png]' title='".$filename."' ".$width." ".$height." border='0' ".$style." />";
		 }
		 elseif($ext == "gif") {
			 $image = "<img src='".HTTP_IMAGES."images/case/gif.png' alt='[gif]' title='".$filename."' ".$width." ".$height." border='0' ".$style." />";
		 }
		 elseif($ext == "bmp"){
			 $image = "<img src='".HTTP_IMAGES."images/case/bmp.png' alt='[bmp]' title='".$filename."' ".$width." ".$height." border='0' ".$style." />";
		 }
		 elseif($ext == "ppt") {
			 $image = $links1."<img src='".HTTP_IMAGES."images/case/ppt.png' alt='[ppt]' title='".$filename."' ".$width." ".$height." border='0' ".$style." />".$links2;
		 }
		 elseif($ext == "pdf") {
			 $image = $links1."<img src='".HTTP_IMAGES."images/case/pdf.png' alt='[pdf]' title='".$filename."' ".$width." ".$height." border='0' ".$style." />".$links2;
		 }
		 else {
			 $image = $links1."<img src='".HTTP_IMAGES."images/case/other.png' alt='[other]' title='".$filename."' ".$width." ".$height." border='0' ".$style." />".$links2;



		 }
		 return $image;
	}
	function todo_typ($type,$title)
	{
		$disp_type = '<img src="'.HTTP_IMAGES.'images/types/'.$type.'.png" title="'.$title.'" alt="'.$type.'" />';
		return $disp_type;
	}
	function todo_typ_src($type,$title)
	{
		$disp_type = HTTP_IMAGES."images/types/".$type.".png'";
		return $disp_type;
	}
	######## WordWrap #######
	function html_wordwrap($str, $width , $break = "\n", $cut = false)
	{
		//same functionality as wordwrap, but ignore html tags
		$unused_char = $this->find_unused_char($str); //get a single character that is not used in the string
		$tags_arr = $this->get_tags_array($str);
		$q = '?';
		$str1 = ''; //the string to be wrapped (will not contain tags)
		$element_lengths = array(); //an array containing the string lengths of each element
		foreach($tags_arr as $tag_or_words)
		{
			if(preg_match("/<.*$q>/", $tag_or_words)) continue;
			$str1 .= $tag_or_words;
			$element_lengths[] = strlen($tag_or_words);
		}
		$str1 = wordwrap($str1, $width, $unused_char, $cut);
		foreach($tags_arr as &$tag_or_words)
		{
			if(preg_match("/<.*$q>/", $tag_or_words)) continue;
			$tag_or_words = substr($str1, 0, $element_lengths[0]);
			$str1 = substr($str1, $element_lengths[0]);
			array_shift($element_lengths); //delete the first array element - we have used it now so we do not need it
		}
		$str2 = implode('', $tags_arr);
		$str3 = str_replace($unused_char, $break, $str2);
		return $str3;
	}
	function get_tags_array($str)
	{
		//given a string, return a sequential array with html tags in their own elements
		$q = '?';
		return preg_split("/(<.*$q>)/",$str, -1, PREG_SPLIT_DELIM_CAPTURE | PREG_SPLIT_NO_EMPTY);
	}
	function find_unused_char($str)
	{
		$possible_chars = array('|', '!', '@', '#', '$', '%', '^', '&', '*', '~');
		foreach($possible_chars as $char) if(strpos($str, $char) === false) return $char;
	}
	//Start function explode_ wrap
	function explode_wrap($text, $chunk_length)
	{
		$string_chunks = explode(' ', $text);
		foreach ($string_chunks as $chunk => $value) 
		{
			if(strlen($value) >= $chunk_length)
			{
				$new_string_chunks[$chunk] = chunk_split($value, $chunk_length, ' ');
			}
			else 
			{
				$new_string_chunks[$chunk] = $value;
			}
		}
		return $new_text = implode(' ', $new_string_chunks);
	}
	function strip_word_html($text, $allowed_tags = '<b><i><sup><sub><em><strong><u><br><ul><li><ol><strike>')
	{
		mb_regex_encoding('UTF-8');
		$search = array('/&lsquo;/u', '/&rsquo;/u', '/&ldquo;/u', '/&rdquo;/u', '/&mdash;/u');
		$replace = array('\'', '\'', '"', '"', '-');
		$text = preg_replace($search, $replace, $text);
		$text = html_entity_decode($text, ENT_QUOTES, 'UTF-8');
		if(mb_stripos($text, '/*') !== FALSE){
			$text = mb_eregi_replace('#/\*.*?\*/#s', '', $text, 'm');
		}
		$text = preg_replace(array('/<([0-9]+)/'), array('< $1'), $text);
		$text = strip_tags($text, $allowed_tags);
		$text = preg_replace(array('/^\s\s+/', '/\s\s+$/', '/\s\s+/u'), array('', '', ' '), $text);
		$search = array('#<(strong|b)[^>]*>(.*?)</(strong|b)>#isu', '#<(em|i)[^>]*>(.*?)</(em|i)>#isu', '#<u[^>]*>(.*?)</u>#isu');
		$replace = array('<b>$2</b>', '<i>$2</i>', '<u>$1</u>');
		$text = preg_replace($search, $replace, $text);
		$num_matches = preg_match_all("/\<!--/u", $text, $matches);
		if($num_matches){
			  $text = preg_replace('/\<!--(.)*--\>/isu', '', $text);
		}
		return $text;
	}
	function closetags($html)
	{
		preg_match_all ( "#<([a-z]+)( .*)?(?!/)>#iU", $html, $result );
		$openedtags = $result[1];
		preg_match_all ( "#</([a-z]+)>#iU", $html, $result );
		$closedtags = $result[1];
		$len_opened = count ( $openedtags );
		if( count ( $closedtags ) == $len_opened )
		{
			return $html;
		}
		$openedtags = array_reverse ( $openedtags );
		for( $i = 0; $i < $len_opened; $i++ )
		{
			if ( !in_array ( $openedtags[$i], $closedtags ) )
			{
				$html .= "</" . $openedtags[$i] . ">";
			}
			else
			{
				unset ( $closedtags[array_search ( $openedtags[$i], $closedtags)] );
			}
		}
		return $html;
	}
	function getFileSize($size) {
		if($size) {
			if($size < 1024) {
				return $size." Kb";
			}
			else {
				$filesize = $size/1024;
				return number_format($filesize,2)." Mb";
			}
		}
	}
	function displayImages($caseFileName) {
		$imgaes = "";
		$oldname = strtolower($caseFileName);
		$ext = substr(strrchr($oldname, "."), 1);
		if($ext == "png" || $ext == "jpeg" || $ext == "jpg" || $ext == "gif" || $ext == "ttf" || $ext == "bmp")
		{
			//$size = getimagesize(DIR_CASE_FILES.$caseFileName);
			//$size = getimagesize(DIR_CASE_FILES_S3.$caseFileName);
			$fileurl = $this->generateTemporaryURL(DIR_CASE_FILES_S3.$caseFileName);
			$size = getimagesize($fileurl);
			if($size[0] >= 225)
			{
				$imgaes = "<a href='".HTTP_ROOT."easycases/download/".$caseFileName."'>
								<img src='".HTTP_ROOT."easycases/image_thumb/?type=case&file=".$caseFileName."&sizex=225&sizey=200&quality=100' border='0' style='border:1px solid #D6D6D6;background:#FEFEE2' alt='".$caseFileName."' title='".$caseFileName."'/>
							</a>";
			}
			else
			{
				$imgaes = "<a href='".HTTP_ROOT."easycases/download/".$caseFileName."'>
								<img src='".HTTP_CASE_FILES.$caseFileName."' border='0' style='border:1px solid #D6D6D6;background:#FEFEE2'  alt='".$caseFileName."' title='".$caseFileName."'/>
							</a>";
			}
		}
		return $imgaes;
	}
	function validateImgFileExt($filename)
	{
		$ext = substr(strrchr($filename, "."), 1);
		$extList = array("png","gif","jpg","jpeg","tif","bmp","JPEG");
		
		$ext = strtolower($ext);
		if(in_array($ext,$extList)) {
			return true;
		}
		else {
			return false;
		}
	}
function generateTemporaryURL($resource){
          $bucketname = BUCKET_NAME;
          $awsAccessKey = awsAccessKey;
          $awsSecretKey =awsSecretKey;
          $expires = strtotime('+1 day');//1.day.from_now.to_i; 
          $s3_key = explode(BUCKET_NAME,$resource);  
          $x=$s3_key[1];
          $s3_key[1]=substr($x,1);
          $string = "GET\n\n\n{$expires}\n/{$bucketname}/{$s3_key[1]}";
          $signature = urlencode(base64_encode((hash_hmac("sha1",utf8_encode($string),$awsSecretKey,TRUE))));
          //echo $expires."=====";echo $signature;
          return "{$resource}?AWSAccessKeyId={$awsAccessKey}&Signature={$signature}&Expires={$expires}";
          //https://s3.amazonaws.com/orangescrum-dev/files/case_files/1.jpg?AWSAccessKeyId=AKIAJAVFGWOGKGBOWPWQ&Signature=gZ90JslqYADtRK6haMVR9e2guko%3D&Expires=1360239119
     }
     function convert_ascii($string){ 
	// Replace Single Curly Quotes
	$search[]  = chr(226).chr(128).chr(152);
	$replace[] = "'";
	$search[]  = chr(226).chr(128).chr(153);
	$replace[] = "'";
	
	// Replace Smart Double Curly Quotes
	$search[]  = chr(226).chr(128).chr(156);
	$replace[] = '\"';
	$search[]  = chr(226).chr(128).chr(157);
	$replace[] = '\"';
	
	// Replace En Dash
	$search[]  = chr(226).chr(128).chr(147);
	$replace[] = '--';
	
	// Replace Em Dash
	$search[]  = chr(226).chr(128).chr(148);
	$replace[] = '---';
	
	// Replace Bullet
	$search[]  = chr(226).chr(128).chr(162);
	$replace[] = '*';
	
	// Replace Middle Dot
	$search[]  = chr(194).chr(183);
	$replace[] = '*';
	
	// Replace Ellipsis with three consecutive dots
	$search[]  = chr(226).chr(128).chr(166);
	$replace[] = '...';
	
	$search[]  = chr(150);
	$replace[] = "-";
	
	// Apply Replacements
	$string = str_replace($search, $replace, $string);
	
	// Remove any non-ASCII Characters
	//$string = preg_replace("/[^\x01-\x7F]/","", $string);
	return $string; 
}
/**
 * @method public format_activity_message(json $json_arr,int $log_type_id,array $log) Here we are just formating the message of a log activity
 * @return String Well formated message with respect to the input json value
 */
	function activity_message($json='',$log_type_id='',$logtype){
		$json_arr = json_decode($json,true);
		if($log_type_id==1){// Account Created 
			$message =$logtype[1];
			if(USER_TYPE==1){
				$message .=" &nbsp<span style='color:#008cdd;'>".$json_arr['company_name']."</span> As a "."<span style='color:#008cdd;'>".$json_arr['user_type']."</span> account. ";
			}
			return $message;
		}
		if($log_type_id==24){ // Account Confirmed
			$message =$logtype[$log_type_id];
			if(USER_TYPE==1){
				$message .=" &nbsp<span style='color:#008cdd;'>".$json_arr['company_name']."</span>";
			}
			return $message;
			return $message ="Company account has been confirmed by '<span style='color:#008cdd;'>".$json_arr['name']."</span>' as a <span style='color:#008cdd;'>".$json_arr['user_type']."</span> user ";
		}
		if($log_type_id==25){
			return $message =$logtype[$log_type_id]."&nbsp With email '<span style='color:#008cdd;'>".$json_arr['email']."</span>'";
		}
		if($log_type_id==26){
			return $message =$logtype[26]." With '<span style='color:#008cdd;'>".$json_arr['email']."</span>' ";
		}
		if($log_type_id==27){
			return $message =$logtype[27]." With email '<span style='color:#008cdd;'>".$json_arr['email']."</span>' ";
		}
		if($log_type_id==28){
			return $message =$logtype[28]." With email '<span style='color:#008cdd;'>".$json_arr['email']."</span>' ";
		}
		if($log_type_id==3){
			return $message ="<span style='color:#EE0000'>".$logtype[3]."</span> With '<span style='color:#008cdd;'>".$json_arr['email']."</span>' ";
		}
		if($log_type_id==4){
			return $message =$logtype[4]." from '<span style='color:#008cdd;'>".$json_arr['previous_plan']."</span>' To '<span style='color:#008cdd;'>".$json_arr['current_plan']."</span>'";
		}
		if($log_type_id==5){
			return $message =$logtype[5];
		}
		if($log_type_id==6){
			return $message =$logtype[6];
		}
		if($log_type_id==7){
			return $message =$logtype[7]."- <span style='color:#008cdd;'>$".$json_arr['amount']."</span>";
		}
		if($log_type_id==8){
			return $message =$logtype[8]." - <span style='color:#008cdd;'>$".$json_arr['price']."</span> , updated during <span style='color:#008cdd;'>".$json_arr['message'].'</span> ';
		}
		if($log_type_id==9){
			return $message =$logtype[9]." - <span style='color:#008cdd;'>$".$json_arr['price']."</span>";
		}
		if($log_type_id==12){
			return $message ="<span style='color:#EE0000'>".$logtype[12]."</span> - <span style='color:#008cdd;'>$".$json_arr['price']."</span> ";
		}
		if($log_type_id==17){
			return $message =$logtype[17]." - <span style='color:#008cdd;'>$".$json_arr['price']."</span>  ";
		}
		if($log_type_id==18){
			return $message =$logtype[18]." - $".$json_arr['price']."</span>  ";
		}
		if($log_type_id==20){
			return $message =$logtype[20]." - <span style='color:#008cdd;'>$".$json_arr['price']."</span> ";
		}
		return $logtype[$log_type_id];
	}
	function gettimezone($timezone_id){
		if($timezone_id){
			$timezone = ClassRegistry::init('TimezoneNames')->find('first',array('conditions'=>array('id'=>$timezone_id)));
			return $timezone['TimezoneNames']['gmt']."<br/>".$timezone['TimezoneNames']['zone'];
		}else{
			return false;
		}
	}
	function isiPad() { 
		preg_match('/iPad/i', $_SERVER['HTTP_USER_AGENT'], $match); 
		if (!empty($match)) { 
			return true; 
		} 
		return false; 
	}
/**
 * @method public iptolocation(string $ip) Detect the location from IP
 * @author GDR<support@ornagescrum.com>
 * @return string  Location fromt the ip
 */	

/**
 * @method public formatprofileimage(string $photoname) Get the formatted image
 
 * @return String Formatted Image
 */
	function formatprofileimage($photoname=''){
		if($photoname){
			return '<img src="'.HTTP_ROOT.'users/image_thumb/?type=photos&file='.$photoname.'&sizex=28&sizey=28&quality=100" class="round_profile_img" height="28" width="28" />';
		}else{
			return '<img src="'.HTTP_ROOT.'users/image_thumb/?type=photos&file=user.png&sizex=28&sizey=28&quality=100" class="round_profile_img" height="28" width="28" />';
		}
	}
/**
 * @method public splitwithspace(string $photoname) Get the formatted image
 * @return String Formatted Image
 */
	function splitwithspace($name=''){
		if($name && strstr($name, ' ')){
			$arr = explode(' ', $name);
			return $arr[0];
		}else{
			return $name;
		}
	}

}