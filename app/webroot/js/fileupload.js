	var total_files=new Array(); var indx = 0;
	$(function ()
	{
		var isExceed = 0;
		$('INPUT[type="file"]').change(function () {
			var isExceed = $("#isExceed").val();
			if(this.value.match(/\.(.+)$/) == null){
			    alert('File "'+this.value+'" has no extension, please upload files with extension ');
			    this.value = '';
			    return false;
			}
			if(this.value) {
				var ext = this.value.match(/\.(.+)$/)[1].toLowerCase();
				if($.inArray(ext, ["bat","com","cpl","dll","exe","msi","msp","pif","shs","sys","cgi","reg","bin","torrent","yps","mp4","mpeg","mpg","3gp","dat","mod","avi","flv","xvid","scr","com","pif","chm","cmd","cpl","crt","hlp","hta","inf","ins","isp","jse?","lnk","mdb","ms","pcd","pif","scr","sct","shs","vb","ws","vbs"]) >= 0) {
					alert("Sorry, '"+ext+"' file type is not allowed!");
					this.value = '';
				}
			}
			else if(isExceed == 1) {
				alert("Sorry, Storage Limit Exceeded!");
				this.value = '';
			}
			total_files = new Array();indx = 0;
		});
	//$('.upload').not(function () { return $(this).data('file_upload'); }).fileUploadUI({/*...*/});
	var i = 0;
	$('#file_upload').fileUploadUI({
		uploadTable: $('#up_files'),
		downloadTable: $('#up_files'),
		buildUploadRow: function (files, index)
		{
		 	var filename = files[index].name;
			var fname = filename.toLowerCase();
			
			if(filename.length > 35)
			{
				filename = filename.substr(0,35);
			}
			gFileupload = 0;
			total_files.push(filename);
			return $('<tr><td valign="top">' + filename + '</td>' +
					'<td valign="top" width="100px" style="padding-left:10px;" title="Uploading..." rel="tooltip"><div class="progress-bar"><div class="progress-bar blue"><\/div><\/div></td>' +
					'<td valign="top" style="padding-left:10px;"><div class="file_upload_cancel">' +
					'<font id="cancel" title="Cancel" title="Cancel" rel="tooltip">' +
					'<span onclick="cancelFile(\''+filename+'\');">Cancel<\/span>' +
					'<\/font><\/div><\/td><\/tr>');
		},
		buildDownloadRow: function (file)
		{
			var fmaxilesize = document.getElementById('fmaxilesize').value;
			indx++;
			if(file.name != "error")
			{
				if(file.message == "success")
				{
					//alert("totalStorage: "+file.totalStorage);
					//alert("storageExceeds: "+file.storageExceeds);
					
					$("#usedstorage").val(file.totalStorage);
					
					var allowedSize = parseInt(fmaxilesize)*1024;
					if(parseInt(file.sizeinkb) <= parseInt(allowedSize)) {
						if($('div [id^="jquerydiv"]').is(":visible")){
							i++;
						}else{
							i=1;	
						}
						
						document.getElementById('totfiles').value = i;
						var oncheck = "";
						var fname = file.filename.split("|");
						
						var filesize = file.sizeinkb;
						if(filesize >= 1024) {
							filesize = filesize/1024;
							filesize = Math.round(filesize*10)/10;
							filesize = filesize+" Mb";
						}
						else {
							filesize = Math.round(filesize*10)/10;
							filesize = filesize+" Kb";
						}
						
						var sizeinMb = file.sizeinkb/1024;
						//var sizeinMb = Math.round(sizeinMb*10)/10;
						
						var pageurl = document.getElementById('pageurl').value;
						
						
						if(parseInt(total_files.length) == indx){
						   gFileupload = 1;
						}
						return $('<tr><td style="color:#0683B8;" valign="top"><div id="jquerydiv'+i+'"><input type="checkbox" checked onClick="return removeFile(\'jqueryfile'+i+'\',\'jquerydiv'+i+'\','+sizeinMb+');" style="cursor:pointer;"/>&nbsp;&nbsp;<a href="'+pageurl+'easycases/download/'+fname[0]+'" style="text-decoration:underline;position:relative;top:-2px;">'+file.name+' ('+filesize+')</a><input type="hidden" class="ajxfileupload" name="data[Easycase][name][]" id="jqueryfile'+i+'" value="'+file.filename+'"/><\/div><\/td><\/tr>');
						//alert(i);
					}
					else {
						alert("Error uploading file. File size cannot be more then "+fmaxilesize+" Mb!");
						if(parseInt(total_files.length) == indx){
						    gFileupload = 1;
						}
					}
					
				}
				else if(file.message == "exceed") {
					alert("Error uploading file.\nStorage usage exceeds by "+file.storageExceeds+" Mb!");
					if(parseInt(total_files.length) == indx){
					    gFileupload = 1;
					}
				}
				else if(file.message == "size") {
					alert("Error uploading file. File size cannot be more then "+fmaxilesize+" Mb!");
					if(parseInt(total_files.length) == indx){
					    gFileupload = 1;
					}
				}
				else if(file.message == "error") {
					alert("Error uploading file. Please try with another file");
					if(parseInt(total_files.length) == indx){
					    gFileupload = 1;
					}
				}
				else if(file.message == "s3_error") {
					alert("Due to some network problem your file is not uploaded.Please try again after sometime.");
					if(parseInt(total_files.length) == indx){
					    gFileupload = 1;
					}
				}
				else {
					alert("Sorry, "+file.message+" file type is not allowed!");
					if(parseInt(total_files.length) == indx){
					    gFileupload = 1;
					}					
					//\nAllowed are: txt, doc, docx, xls, xlsx, pdf, odt, ppt, jpeg, tif, gif, psd, jpg or png.
				}
			}
			else {
				alert("Error uploading file. Please try with another file");
				if(parseInt(total_files.length) == indx){
				    gFileupload = 1;
				}
			}
		}
	});
});

function cancelFile(file_name) {
    if(total_files.length){
	total_files.pop(file_name);
    }
    
    if(total_files.length == 0){
	gFileupload = 1;
    }
}
