<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<title>YoxView demo - Basic usage</title>
		<link rel="Stylesheet" type="text/css" href="style.css" />
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.8.0/jquery.min.js"></script>
		<script type="text/javascript" src="yoxview-init.js"></script>
		<script type="text/javascript">
		    $(document).ready(function(){
				var yoxviewDownloadButton = $("<a>", {
                title: "Download image",
                target: "yoxviewDownloadImage"
            });
            yoxviewDownloadButton.append($("<img>", {
                src: "./images/right.png",
                alt: "Download",
                css: { width: 18, height: 18 }
            }));
		        $(".yoxview").yoxview({
					autoHideMenu:false,
					autoHideInfo:false,
					backgroundColor: '#282C57',
					infoButtons: {
                    download: yoxviewDownloadButton
                },
				onSelect: function(i, image)
                {
					var urls=(image.media.src).split('/');
					var img_name=urls[urls.length-1];
                    $.yoxview.infoButtons.download.attr("href", 'https://'+img_name);
                }
				});
	
		    });
		</script>
	</head>
<?php
$htp="img/jaganath.jpg";
?>
	<body>
		<div id="container">
			<div class="thumbnails yoxview">
				<ul><li>
				<a class='yoxviewLink' href="<?php echo $htp;?>" title="<?php echo $htp;?>">Jay Jaganath</a></li>
<li>				<a class='yoxviewLink' href="img/tr.jpg">My image</a></li>
<li>				<a class='yoxviewLink' href="img/sun.pdf">My pdf</a></li></ul>

			</div>

			<hr />
			<br />

		</div>
	</body>
</html>
