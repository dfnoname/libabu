<?php
error_reporting(0);
@ini_set('display_errors', 0);
if(!isset($_GET['product_id'])){
	header("Location: /");exit();
}
if(empty($_GET['product_id'])){
	header("Location: /");exit();
}

if(!isset($_GET['product_title'])){
	$product_title= 'Supplier have been connected';
}
if(empty($_GET['product_title'])){
	$product_title= 'Supplier have been connected';
}
if(isset($_GET['product_title']) && !empty($_GET['product_title'])){
	$product_title= $_GET['product_title'];
}
$URL_OFFER='https://message.alibaba.com/msgsend/contact.htm?action=contact_action&domain=1&id='.$_GET['product_id'];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo $product_title;?></title>
<meta http-equiv="refresh" content="10; url=<?php echo $URL_OFFER;?>">
<meta name="robots" content="noindex, nofollow">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.2/jquery.min.js"></script>
<script type="text/javascript">
var terms = ["Please Wait For ...", "Contacting Message Center ...", "Loading Contact Form ...", "Initializing ..."];
function rotateTerm() {
	var ct = jQuery("#rotate").data("term") || 0;
	jQuery("#rotate")
	.data("term", ct == terms.length -1 ? 0 : ct + 1)
	.text(terms[ct])
	.fadeIn()
	.delay(2000)
	.fadeOut(200, rotateTerm);
}
jQuery(rotateTerm);
</script>
<script type="text/javascript">
var max_time = 10;
var cinterval;
 
function countdown_timer(){
	max_time--;
	document.getElementById('count').innerHTML = max_time;
	if(max_time == 0){
	clearInterval(cinterval);
}
}
// 1,000 means 1 second.
cinterval = setInterval('countdown_timer()', 1000);
</script>
</head>
<body>
<?php
if(isset($_GET['visit'])){
		echo '<iframe src="http://click.alibaba.com/rd/d5pjgvo1" style="display:none;"></iframe>';
}?>
<div class="wrapper-fluid">
	<p style="margin-top:0;padding:40px 0;text-align:center;background:#FF0;font-weight:700;">Electronics &#8226; Apparel &#8226; Textiles &#8226; Health & Beauty &#8226; Jewelry &#8226; Bags & Shoes &#8226; Auto Transportation &#8226; Home Lights Construction &#8226; Gifts Toys &#8226; Electrical Equipment &#8226; Packaging &#8226; Advertising</p>
	<div class="wrapper-fixed" style="max-width: 980px;margin: 0px auto;">
      <div class="gr-9" style="text-align:center;text-align:center;float:left;margin:0;padding: 0 10px;border:none;width: 56.25%;">
            <h1 style="margin-top:40px;"><?php echo $product_title;?></h1>
            <h3>Supplier will be ready in <span id="count">10</span> seconds.</h3>
            <img style="margin:0 auto;" src="https://i0.wp.com/winpamoja.com/Content/images/Loading.gif" />
            <h3 id="rotate">Please wait for ...</h3>
        </div>
		<div class="gr-6" style="padding-left:20px;float: left;margin: 0;padding: 0 10px;border: none;width: 37.5%;">
            <img src="https://i0.wp.com/www.wholesalertips.com/theme/alipress/images/support.png" width="100%" height="auto"/>
        </div>
        <div class="clear"></div>
	</div>
</div>

  <!-- Histats.com  START  (aync)-->
<script type="text/javascript">var _Hasync= _Hasync|| [];
_Hasync.push(['Histats.start', '1,3578785,4,0,0,0,00010000']);
_Hasync.push(['Histats.fasi', '1']);
_Hasync.push(['Histats.track_hits', '']);
(function() {
var hs = document.createElement('script'); hs.type = 'text/javascript'; hs.async = true;
hs.src = ('//s10.histats.com/js15_as.js');
(document.getElementsByTagName('head')[0] || document.getElementsByTagName('body')[0]).appendChild(hs);
})();</script>
<noscript><a href="/" target="_blank"><img  src="//sstatic1.histats.com/0.gif?3578785&101" alt="cool hit counter" border="0"></a></noscript>
<!-- Histats.com  END  -->
</body>
</html>