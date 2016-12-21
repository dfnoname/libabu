<?php
date_default_timezone_set('Africa/Lagos');
	if (isset($_SERVER['HTTP_IF_MODIFIED_SINCE'])){
		header('HTTP/1.1 304 Not Modified');
        die();
	}
header("Content-Type: text/plain");
header('Cache-control: max-age='.(60*60*365));
header('Expires: '.gmdate(DATE_RFC1123,time()+60*60*365));
header('Last-Modified: '.gmdate(DATE_RFC1123,time()));

echo "User-Agent: *\n";
echo "Allow: /\n";
echo "Sitemap: http://".$_SERVER['SERVER_NAME']."/product-sitemap.xml";