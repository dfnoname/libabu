<?php
error_reporting(0);
@ini_set('display_errors', 0);

date_default_timezone_set('Africa/Lagos');
header('X-Robots-Tag: NOTRANSLATE,NOARCHIVE,NOODP', true);

$REQUEST= $_SERVER['REQUEST_URI'];




//IN CATEGORY PRODUCT
if(preg_match('/^\/In-Category\/(\d+)_([a-z0-9]{5,})\.html$/', $REQUEST, $hasil) || preg_match('/^\/In-Category\/(\d+)\.html$/', $REQUEST, $hasil)){
	if(isset($hasil[2]) && !empty($hasil[2])){
			$thepage_key= substr($hasil[2], -5);
			$thecurrent_page= str_replace($thepage_key,'', $hasil[2]);
				if(substr(md5('dafamedia'.$thecurrent_page),0,5) != $thepage_key){
					header("HTTP/1.1 301 Moved Permanently");
					header("location: /");
				}
		}else{$thecurrent_page=1;}

$CATALOG_ID= $hasil[1];
$target= 'https://m.alibaba.com/catalogs/--'.$CATALOG_ID.'/'.$thecurrent_page.'?XPJAX=1'; 
$ttt= ALI_NORMAL_SCRAPPING($target, false, true);
$array= json_decode($ttt,1);

if(!empty($array['config']['cateName']) && !empty($array['config']['parentCateName'])){
	$title_to_keywords= $array['config']['cateName'].' '.$array['config']['parentCateName'];
	$theroom_page_title= '@'.$CATALOG_ID.' '.$title_to_keywords.' Source quality '.$title_to_keywords.' from Global '.$title_to_keywords;
	$theroom_metadescription= $theroom_page_title;
}else{ 
$theroom_page_title= 'All Product In Catalog Id '.$CATALOG_ID.' Source quality '.$CATALOG_ID.' from Global Catalog '.$CATALOG_ID; 
$theroom_metadescription= $theroom_page_title;
}
	

toCreateFolder('product_detail');
	
$konten= '<!DOCTYPE html><html lang=en><head><meta charset=utf-8><meta name=viewport content="width=device-width, initial-scale=1"><title>'.$theroom_page_title.' @Page '.$thecurrent_page.'</title><meta name="description" content="@page '.$thecurrent_page.' '.$theroom_metadescription.'"><link rel=stylesheet href=https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css><script src=https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js></script><script src=https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js></script><meta name="robots" content="NOINDEX, FOLLOW, NOARCHIVE, NOTRANSLATE"></head><body><div class=container><h2>'.$theroom_page_title.' @Page '.$thecurrent_page.'</h2><table class="table table-striped"><thead><tr><th>Table 1</th><th>Table 2</th><th>Table 3</th><th>Table 4</th></tr></thead><tbody>';


$tks=0;
	foreach($array['productNormalList'] as $listproduct){
$currr= $tks % 4;	
	
	if(empty($listproduct['fobPriceFrom'])){
		$product_price_from= 1.00;
	}else{ $product_price_from= $listproduct['fobPriceFrom'];}
	if(empty($listproduct['fobPriceTo'])){
		$product_price_to= 10.00;
	}else{ $product_price_to= $listproduct['fobPriceTo'];}
	if(empty($listproduct['minOrderUnit'])){
		$product_minOrderUnit= 'Set';
	}else{ $product_minOrderUnit= $listproduct['minOrderUnit'];}
	if(empty($listproduct['mainProducts'])){
		$product_cat_showroom="";
		$product_maintags="";
	}else{ $product_cat_showroom= string_to_product_showroom($listproduct['mainProducts']); $product_maintags= $listproduct['mainProducts'];}
		
		$product_currency= 'US $';
		$product_title= $listproduct['productName'];
		$product_company= $listproduct['companyName'];
		$product_image= $listproduct['imagePath'];
		$product_id= $listproduct['id'];
		$product_filesname= 'product_detail/'.preg_replace('/((.*)\/|\.html(.*))/i', '', $listproduct['productDetailUrl']).'_'.$product_id.'.html';
		$product_fix_permalink= '/'.$product_filesname;
		$product_landingpage= "/message-suplier?product_id=".$product_id."&visit=short&product_title=".urlencode($product_title);
		
		if($currr == 0){
			$konten .= '<tr><td><a href="'.$product_fix_permalink.'">'.$product_title.'</a></td>';
			 }
				 if($currr == 1 || $currr == 2){
				$konten .= '<td><a href="'.$product_fix_permalink.'">'.$product_title.'</a></td>';
				 }
					if($currr == 3){
					$konten .= '<td><a href="'.$product_fix_permalink.'">'.$product_title.'</a></td></tr>';
					}

		if(!file_exists($product_filesname)){			
					//SAVE TO CACHE AS PRODUCT SINGLE
		$data= '<html><head><title>Wholesale '.$product_title.' @'.$product_id.'</title><meta name="robots" content="NOARCHIVE, NOTRANSLATE"><meta name="description" content="@'.$product_id.' Wholesale '.$product_title.' by '.$product_company.' You can get more detail about '.$product_title.'"><script>window.location = "'.$product_landingpage.'";</script><link rel="canonical" href="'.$product_fix_permalink.'"></head><body><div id="shop-product"><h1>'.$product_title.'</h1><div class="image-product"><a href="'.$product_landingpage.'" rel="nofollow"><img src="'.$product_image.'" alt="'.$product_title.'"/></a></div><div class="product-price" itemprop="offers" itemscope="" itemtype="//schema.org/AggregateOffer"> <span itemprop="priceCurrency" content="USD">US $</span> <span itemprop="lowPrice">'.$product_price_from.'</span>-<span itemprop="highPrice">'.$product_price_to.'</span> <span itemprop="unit"> / '.$product_minOrderUnit.'</span></div><h2>Supplier : '.$product_company.'</h2><p class="product-description">#'.$product_id.' '.$product_title.' by '.$product_company.' In Main Tags '.$product_maintags.'</p><p class="product-showroom">You are in : '.$product_cat_showroom.'</p><p class="product-buy"><a href="'.$product_landingpage.'" rel="nofollow"><img src="https://i.imgur.com/4D49gMr.png"></a></p></div></body></html><!-- In Cached -->';	
					//END SAVE TO CACHE AS PRODUCT SINGLE
					$ftr= fopen($product_filesname, "w");
					fwrite($ftr, $data);
					fclose($ftr);
		}
					
$tks++;		
	}
	
	 $konten .='</tbody></table><ul class="pager">';
	$icurrpage= $thecurrent_page; 
	$ilanjutpage= $icurrpage+1;
	$ibalikpage= $icurrpage-1;
	
$targetb= 'https://m.alibaba.com/catalogs/--'.$CATALOG_ID.'/'.$ilanjutpage.'?XPJAX=1'; 
$tttb= ALI_NORMAL_SCRAPPING($targetb, true);
$arrayb= json_decode($tttb,1);
//prevpage	
	if($ibalikpage < 2){
		$konten .='<li><a href="/In-Category/'.$CATALOG_ID.'.html">Previous</a></li>';
	}else{
		$irpage_key= substr(md5('dafamedia'.$ibalikpage),0,5);
		$konten .='<li><a href="/In-Category/'.$CATALOG_ID.'_'.$ibalikpage.$irpage_key.'.html">Previous</a></li>';
	}
	
//nextpage
	if(isset($arrayb['productNormalList']) && !empty($arrayb['productNormalList'])){
		$irpage_key= substr(md5('dafamedia'.$ilanjutpage),0,5);
		$konten .='<li><a href="/In-Category/'.$CATALOG_ID.'_'.$ilanjutpage.$irpage_key.'.html">Next</a></li>';
	}else{
		$konten .='<li><a href="'.$_SERVER['REQUEST_URI'].'">Next</a></li>';
	}


  
$konten .='</ul></div></body></html>';

echo $konten.'<!-- NOT CACHE -->';
$konten .='<!-- In Cached -->';

$files= substr($REQUEST,1);
if(!file_exists($files)){
toCreateFolder('In-Category');
$fop= fopen($files, "w");
fwrite($fop, $konten);
fclose($fop);
    }

exit();
}
//END IN CATEGORY PRODUCT






//SINGLE PRODUCT NOT EXISTS
if(preg_match('/^\/product_detail\/([a-zA-Z0-9\-]+)_(\d+)\.html$/', $REQUEST, $hasil)){
	toCreateFolder('product_detail');
$product_id= $hasil[2];	
$product_slug= $hasil[1];
		$target= 'https://m.alibaba.com/product/'.$product_id.'/'.$product_slug.'.html';
$ttt= ALI_NORMAL_SCRAPPING($target);

//REGEX
		preg_match('~<div class="related-searches">\K.*(?=</div>)~Uis', $ttt, $string_torelated);
if(!isset($string_torelated[0])	|| empty($string_torelated[0])){
	$product_cat_showroom="";
	$product_maintags="";
}else{
	preg_match_all('~<a href="([^"]+)">\K.*(?=</a>)~Uis', $string_torelated[0], $string_related);
		if(!isset($string_related[0]) || empty($string_related[0])){
			$product_cat_showroom="";
			$product_maintags="";
		}else{
$product_cat_showroom= string_to_product_showroom($string_related[0], true); $product_maintags= implode(',', $string_related[0]);			
		}
}		

		preg_match('~<h1 itemprop="name">\K.*(?=</h1>)~Uis', $ttt, $string_title);
		preg_match('~<div class="company-intro-wrap">\K.*(?=</div>)~Uis', $ttt, $string_company);
		preg_match('~<img class="normal" src="\K.*(?=")~Uis', $ttt, $string_image);
		preg_match('~<span itemprop="lowPrice">\K.*(?=</span>)~Uis', $ttt, $string_lowprice);
		preg_match('~<span itemprop="highPrice">\K.*(?=</span>)~Uis', $ttt, $string_highprice);
		preg_match('~<span itemprop="unit">\K.*(?=</span>)~Uis', $ttt, $string_unit);
		
		
if(!empty($string_title[0])){
	$product_title= trim($string_title[0]);
}else{ $product_title= trim(str_replace('-', ' ', $product_slug)); }		

if(!empty($string_company[0])){
	$product_company= trim(strip_tags($string_company[0]));
}else{ $product_company="@alibaba"; }	
		
if(!empty($string_image[0])){
	$product_image= $string_image[0];
}else{ $product_image="//i0.wp.com/mteliza.vic.cricket.com.au/files/819/images/imageNotAvailable.jpg"; }

if(!empty($string_lowprice[0])){
	$product_price_from= $string_lowprice[0];
}else{ $product_price_from= 1.00; }

if(!empty($string_highprice[0])){
	$product_price_to= $string_highprice[0];
}else{ $product_price_to= 10.00; }

if(!empty($string_unit[0])){
	$product_minOrderUnit= trim(preg_replace('/([^a-z0-9]+)/i', '', $string_unit[0]));
}else{ $product_minOrderUnit= "Set"; }	
		
		
$product_filesname= substr($REQUEST,1);
$product_fix_permalink= $REQUEST;
$product_landingpage= "/message-suplier?product_id=".$product_id."&visit=short&product_title=".urlencode($product_title);

  if(preg_match('/404 Not Found/i', $product_title)){
$product_title= trim(str_replace('-', ' ', $product_slug));
$data= '<html><head><title>Wholesale '.$product_title.' @'.$product_id.'</title><meta name="description" content="@'.$product_id.' Wholesale '.$product_title.' by '.$product_company.' You can get more detail about '.$product_title.'"><script>window.location = "'.$product_landingpage.'";</script><link rel="canonical" href="'.$product_fix_permalink.'"></head><body><div id="shop-product"><h1>'.$product_title.'</h1><div class="image-product"><a href="'.$product_landingpage.'" rel="nofollow"><img src="'.$product_image.'" alt="'.$product_title.'"/></a></div><div class="product-price" itemprop="offers" itemscope="" itemtype="//schema.org/AggregateOffer"> <span itemprop="priceCurrency" content="USD">US $</span> <span itemprop="lowPrice">'.$product_price_from.'</span>-<span itemprop="highPrice">'.$product_price_to.'</span> <span itemprop="unit"> / '.$product_minOrderUnit.'</span></div><h2>Supplier : '.$product_company.'</h2><p class="product-description">#'.$product_id.' '.$product_title.' by '.$product_company.' In Main Tags '.$product_maintags.'</p><p class="product-showroom">You are in : '.$product_cat_showroom.'</p><p class="product-buy"><a href="'.$product_landingpage.'" rel="nofollow"><img src="https://i.imgur.com/4D49gMr.png"></a></p></div></body></html>';
echo $data.' <!-- Not CACHE -->';
exit();
  }
//HTML TO FILES
$data= '<html><head><title>Wholesale '.$product_title.' @'.$product_id.'</title><meta name="description" content="@'.$product_id.' Wholesale '.$product_title.' by '.$product_company.' You can get more detail about '.$product_title.'"><script>window.location = "'.$product_landingpage.'";</script><link rel="canonical" href="'.$product_fix_permalink.'"></head><body><div id="shop-product"><h1>'.$product_title.'</h1><div class="image-product"><a href="'.$product_landingpage.'" rel="nofollow"><img src="'.$product_image.'" alt="'.$product_title.'"/></a></div><div class="product-price" itemprop="offers" itemscope="" itemtype="//schema.org/AggregateOffer"> <span itemprop="priceCurrency" content="USD">US $</span> <span itemprop="lowPrice">'.$product_price_from.'</span>-<span itemprop="highPrice">'.$product_price_to.'</span> <span itemprop="unit"> / '.$product_minOrderUnit.'</span></div><h2>Supplier : '.$product_company.'</h2><p class="product-description">#'.$product_id.' '.$product_title.' by '.$product_company.' In Main Tags '.$product_maintags.'</p><p class="product-showroom">You are in : '.$product_cat_showroom.'</p><p class="product-buy"><a href="'.$product_landingpage.'" rel="nofollow"><img src="https://i.imgur.com/4D49gMr.png"></a></p></div></body></html>';
echo $data.' <!-- Not CACHE -->';	

$data .="<!-- In Cached -->";
	
	if(!file_exists($product_filesname)){
		$ftr= fopen($product_filesname, "w");
					fwrite($ftr, $data);
					fclose($ftr);
	}	
	
exit();
}//END SINGLE PRODUCT NOT EXISTS


//SHOWROOM Browse Alphabetically
 if(preg_match('/^\/wholesale-showroom\/showroom-([A-Z]|0-9)(-([0-9]+))?\.html$/', $REQUEST, $hasil)){

$LIMITPAGE= array("A" => 22429,"B" => 23443,"C" => 23460,"D" => 5640,"E" => 4178,"F" => 6184,"G" => 4070,"H" => 4822,"I" => 3154,"J" => 1008,"K" => 1442,"L" => 5449,"M" => 7024,"N" => 3242,"O" => 2781,"P" => 9910,"Q" => 513,"R" => 4311,"S" => 11751,"T" => 5787,"U" => 2332,"V" => 1664,"W" => 5508,"X" => 304,"Y" => 526,"Z" => 331,"0-9" => 11254);

if(isset($hasil[3])){
  if($hasil[3] > $LIMITPAGE[$hasil[1]]){
exit("error page not found");
  }

$addcurrent= '_'.$hasil[3];
}else{
$addcurrent= '';
}

$room_dir= $hasil[1];

$target= 'https://www.alibaba.com/showroom/showroom-'.$room_dir.$addcurrent.'.html';


$ttt= ALI_NORMAL_SCRAPPING($target);

preg_match_all('~"https:\/\/www.alibaba.com\/showroom\/(?!showroom)\K.*(?=")~Uis', $ttt, $parsing);

$konten= '<!DOCTYPE html><html lang=en><head><title>Product On Showroom Alpa '.$room_dir.$addcurrent.'</title><meta charset=utf-8><meta name=viewport content="width=device-width, initial-scale=1"><link rel=stylesheet href=https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css><script src=https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js></script><script src=https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js></script><meta name="robots" content="NOINDEX, FOLLOW, NOARCHIVE, NOTRANSLATE"></head><body><div class=container><h2>Product On Showroom Alpa '.$room_dir.$addcurrent.'</h2><p>The Product On Showroom Alpa '.$room_dir.$addcurrent.'</p><table class="table table-striped"><thead><tr><th>Table 1</th><th>Table 2</th><th>Table 3</th><th>Table 4</th></tr></thead><tbody>';
	
	$countdata= count($parsing[0]) - 1;

$mul=0;	
foreach($parsing[0] as $items){
$currr= $mul % 4;
			 if($currr == 0){
			$konten .= '<tr><td><a href="/read-showroom/'.$items.'">'.$items.'</a></td>';
			 }
				 if($currr == 1 || $currr == 2){
				$konten .= '<td><a href="/read-showroom/'.$items.'">'.$items.'</a></td>';
				 }
					if($currr == 3){
					$konten .= '<td><a href="/read-showroom/'.$items.'">'.$items.'</a></td></tr>';
					}
	  
$mul++;	  
 }
 $konten .='</tbody>
  </table>
</div>
</body>
</html>';

echo $konten.'<!-- NOT CACHE -->';
$konten .='<!-- In Cached -->';

$files= substr($REQUEST,1);
	if(!file_exists($files)){
toCreateFolder('wholesale-showroom');
$fop= fopen($files, "w");
fwrite($fop, $konten);
fclose($fop);
    }
	
exit();	
 }//END SHOWROOM Browse Alphabetically
 
 
 
 
//READ SHOWROOM BY KEYWORDS
 if(preg_match('/^\/read-showroom\/(.*)_([a-z0-9]{5,})\.html$/', $REQUEST, $hasil) || preg_match('/^\/read-showroom\/(.*)\.html$/', $REQUEST, $hasil)){ 

	if(isset($hasil[2]) && !empty($hasil[2])){
		$thepage_key= substr($hasil[2], -5);
		$thecurrent_page= str_replace($thepage_key,'', $hasil[2]);
			if(substr(md5('dafamedia'.$thecurrent_page),0,5) != $thepage_key || preg_match('/([a-z]+)/i', $thecurrent_page) || empty($thecurrent_page)){
				header("HTTP/1.1 301 Moved Permanently");
				header("location: /");
			}
	}else{$thecurrent_page=1;}
	
	if(preg_match('/_/', $hasil[1])){
		$tyru= str_replace('_', '-', $hasil[1]);
		header("HTTP/1.1 301 Moved Permanently");
		header("location: /read-showroom/".$tyru.".html");
	}
	
$keywords= $hasil[1];
	$title_to_keywords= trim(preg_replace('/([^a-z0-9]+)/i', ' ', $keywords));
$target= 'https://m.alibaba.com/showroom/'.$keywords.'/'.$thecurrent_page.'.html?XPJAX=1'; 
$ttt= ALI_NORMAL_SCRAPPING($target, false, true);
$array= json_decode($ttt,1);

if(!empty($array['metaView']['title'])){
	$theroom_page_title= preg_replace('/\s+on m.alibaba.com/i', '', $array['metaView']['title']);
}else{ $theroom_page_title= $title_to_keywords.' Source quality '.$title_to_keywords.' from Global '.$title_to_keywords; }

if(!empty($array['metaView']['metadescription'])){
	$theroom_metadescription= preg_replace('/\s+on m.alibaba.com/i', '', $array['metaView']['metadescription']);
}else{ $theroom_metadescription= 'Buy Quality '.$title_to_keywords.' and Source '.$title_to_keywords.' from Reliable Global '.$title_to_keywords.' suppliers. Find Quality '.$title_to_keywords.' at This site and more';}	
	

toCreateFolder('product_detail');
	
$konten= '<!DOCTYPE html><html lang=en><head><meta charset=utf-8><meta name=viewport content="width=device-width, initial-scale=1"><title>'.$theroom_page_title.' @Page '.$thecurrent_page.'</title><meta name="description" content="@page '.$thecurrent_page.' '.$theroom_metadescription.'"><link rel=stylesheet href=https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css><script src=https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js></script><script src=https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js></script><meta name="robots" content="NOINDEX, FOLLOW, NOARCHIVE, NOTRANSLATE"></head><body><div class=container><h2>'.$theroom_page_title.' @Page '.$thecurrent_page.'</h2><table class="table table-striped"><thead><tr><th>Table 1</th><th>Table 2</th><th>Table 3</th><th>Table 4</th></tr></thead><tbody>';


$tks=0;
	foreach($array['productNormalList'] as $listproduct){
$currr= $tks % 4;	
	
	if(empty($listproduct['fobPriceFrom'])){
		$product_price_from= 1.00;
	}else{ $product_price_from= $listproduct['fobPriceFrom'];}
	if(empty($listproduct['fobPriceTo'])){
		$product_price_to= 10.00;
	}else{ $product_price_to= $listproduct['fobPriceTo'];}
	if(empty($listproduct['minOrderUnit'])){
		$product_minOrderUnit= 'Set';
	}else{ $product_minOrderUnit= $listproduct['minOrderUnit'];}
	if(empty($listproduct['mainProducts'])){
		$product_cat_showroom="";
		$product_maintags="";
	}else{ $product_cat_showroom= string_to_product_showroom($listproduct['mainProducts']); $product_maintags= $listproduct['mainProducts'];}
		
		$product_currency= 'US $';
		$product_title= $listproduct['productName'];
		$product_company= $listproduct['companyName'];
		$product_image= $listproduct['imagePath'];
		$product_id= $listproduct['id'];
		$product_filesname= 'product_detail/'.preg_replace('/((.*)\/|\.html(.*))/i', '', $listproduct['productDetailUrl']).'_'.$product_id.'.html';
		$product_fix_permalink= '/'.$product_filesname;
		$product_landingpage= "/message-suplier?product_id=".$product_id."&visit=short&product_title=".urlencode($product_title);
		
		if($currr == 0){
			$konten .= '<tr><td><a href="'.$product_fix_permalink.'">'.$product_title.'</a></td>';
			 }
				 if($currr == 1 || $currr == 2){
				$konten .= '<td><a href="'.$product_fix_permalink.'">'.$product_title.'</a></td>';
				 }
					if($currr == 3){
					$konten .= '<td><a href="'.$product_fix_permalink.'">'.$product_title.'</a></td></tr>';
					}

		if(!file_exists($product_filesname)){			
					//SAVE TO CACHE AS PRODUCT SINGLE
		$data= '<html><head><title>Wholesale '.$product_title.' @'.$product_id.'</title><meta name="robots" content="NOARCHIVE, NOTRANSLATE"><meta name="description" content="@'.$product_id.' Wholesale '.$product_title.' by '.$product_company.' You can get more detail about '.$product_title.'"><script>window.location = "'.$product_landingpage.'";</script><link rel="canonical" href="'.$product_fix_permalink.'"></head><body><div id="shop-product"><h1>'.$product_title.'</h1><div class="image-product"><a href="'.$product_landingpage.'" rel="nofollow"><img src="'.$product_image.'" alt="'.$product_title.'"/></a></div><div class="product-price" itemprop="offers" itemscope="" itemtype="//schema.org/AggregateOffer"> <span itemprop="priceCurrency" content="USD">US $</span> <span itemprop="lowPrice">'.$product_price_from.'</span>-<span itemprop="highPrice">'.$product_price_to.'</span> <span itemprop="unit"> / '.$product_minOrderUnit.'</span></div><h2>Supplier : '.$product_company.'</h2><p class="product-description">#'.$product_id.' '.$product_title.' by '.$product_company.' In Main Tags '.$product_maintags.'</p><p class="product-showroom">You are in : '.$product_cat_showroom.'</p><p class="product-buy"><a href="'.$product_landingpage.'" rel="nofollow"><img src="https://i.imgur.com/4D49gMr.png"></a></p></div></body></html><!-- In Cached -->';	
					//END SAVE TO CACHE AS PRODUCT SINGLE
					$ftr= fopen($product_filesname, "w");
					fwrite($ftr, $data);
					fclose($ftr);
		}
					
$tks++;		
	}
	
	 $konten .='</tbody></table><ul class="pager">';
	$icurrpage= $thecurrent_page; 
	$ilanjutpage= $icurrpage+1;
	$ibalikpage= $icurrpage-1;
	
$targetb= 'https://m.alibaba.com/showroom/'.$keywords.'/'.$ilanjutpage.'.html?XPJAX=1'; 
$tttb= ALI_NORMAL_SCRAPPING($targetb, true);
$arrayb= json_decode($tttb,1);
//prevpage	
	if($ibalikpage < 2){
		$konten .='<li><a href="/read-showroom/'.$keywords.'.html">Previous</a></li>';
	}else{
		$irpage_key= substr(md5('dafamedia'.$ibalikpage),0,5);
		$konten .='<li><a href="/read-showroom/'.$keywords.'_'.$ibalikpage.$irpage_key.'.html">Previous</a></li>';
	}
	
//nextpage
	if(isset($arrayb['productNormalList']) && !empty($arrayb['productNormalList'])){
		$irpage_key= substr(md5('dafamedia'.$ilanjutpage),0,5);
		$konten .='<li><a href="/read-showroom/'.$keywords.'_'.$ilanjutpage.$irpage_key.'.html">Next</a></li>';
	}else{
		$konten .='<li><a href="'.$_SERVER['REQUEST_URI'].'">Next</a></li>';
	}


  
$konten .='</ul></div></body></html>';

echo $konten.'<!-- NOT CACHE -->';
$konten .='<!-- In Cached -->';

$files= substr($REQUEST,1);
if(!file_exists($files)){
toCreateFolder('read-showroom');
$fop= fopen($files, "w");
fwrite($fop, $konten);
fclose($fop);
    }

exit();
 }//END READ SHOWROOM BY KEYWORDS
 
 
 
//IF IN DEFAULT INDEX
$CATEGORY_IDKU= array("101","103","100002688","105","106","104","100001746","144","136","100003006","2703","280501","290301","138","100003949","122","10502","100009043","100003758","100002650","12501","100007332","12503","238","100008999","100003864","100002622","135","100009051","10508","2705","280503","355","290303","333","100003186","353","100005769","100005785","100003238","100005786","354","100003239","337","328","100003199","100005968","100003241","100003234","100005824","100005796","100005793","33801","100005837","100003070","100005794","100003246","335","100005795","100003244","100005797","100005798","100003242","301","100003240","100005800","100005801","100005803","100005802","100005804","100003237","100005816","100003235","100003109","100003363","3404","100006373","1215","100006370","120203","3406","121888","1202","3408","100009678","3411","3401","100006364","100006361","1212","3409","100006369","100006372","100006362","100006371","100002871","12809","100009679","100006367","1203","120688","100006374","100006366","100006368","100006365","100003849","3407","100006360","100003248","100006421","1213","2819","2833","2804","2802","2803","24","2834","2842","2841","2806","280604","2831","2815","2206","2813","2840","2839","2807","2825","2812","2805","2903","2838","2823","2832","2843","3014","25","2811","100007534","2814","100006658","100003960","100003949","100003944","190000034","100004135","1416","1001","100006777","110503","833","100009387","100004139","100003732","826","824","100009389","830","100009393","100009383","100004137","100003959","190000048","100005071","100005072","711006","4407","70810","720","708021","100005063","721","708022","2104","701","63705","70804","708041","50908","70803003","70899","100005076","707","717","100005075","70807","705","708044","7172","709","711001","100005093","70811","100005080","703001","100005089","100005077","702","711002","4406","70803","708045","100005085","708023","100005061","100005078","70803004","70803002","70803005","100005079","711004","704","2118","708042","2107","70803001","7101","4408","703","712","708043","518","708031","100005062","100009083","100009041","711005","7171","708032","100001118","660201","660101","660105","100001120","100000991","660102","660204","100000992","660301","660103","660104","100001119","3308","3305","1513","660302","3306","100000993","160807","100000994","100001117","3030","3008","283122","1341","131001","100006478","3011","100006479","131002","100105","100005172","1318","2714","280537","290313","1315","4314","280513","4315","14191202","100006527","131211","800510","1312","31","3108","290331","3029","142016","131003","1340","131210","100005188","1343","153803","100006533","100005190","141905","14190402","100006535","1326","100006536","100006489","100007145","100006539","100005198","100006537","100006547","1353","81105","1356","1335","100005201","131004","100006497","1330","100005208","1320","1316","1338","1325","1333","131006","100005224","14190409","100005230","93402","100006589","131008","933","131207","100006598","100006599","100005231","131005","131205","410612","1303","100005232","14191209","1431","100009632","3404","100009630","4499","100009633","100009629","100002954","100009628","100009631","100009743","141914","528","14190406","526","400301","524","150512","4103","540","141902","141903","141905","516","141913","4105","141911","527","141909","141906","141907","141904","14190403","1417","4001","150412","4003","504","280505","150407","512","4002","290305","515","4004","4099","4005","100002940","100002939","122","1002","1005","100107","100110","2712","290310","1004","1001","709","1007","100002941","1003","1011","100002938","1108","290311","1103","1102","1107","1105","1106","1104","1101","3299","333","1730","515","4206","631","153714","8007","8014","142011","4108","417","32203","33913","32801","32204","33910","33911","33912","33903","32213","324","33904","32212","33905","33906","32216","380310","33909","32299","380399","32205","32206","33902","32217","152405","204","100008699","218","207","214","2704","280502","290302","289","236","209","100008828","100001750","100001710","100002654","238","100009254","100008902","216","100001756","109","212","100006931","100002847","202","100008840","100002689","225","100001204","100002964","100001193","100001203","150301","100001206","3712","3707","150306","3709","3708","100001196","150303","100001205","100001198","150399","150302","100001199","100001200","4150601","43203","100001201","1710","1730","1718","1714","1713","1719","100002701","1716","1723","1715","1717","1712","1720","1711","1743","100002702","100001705","1744","1726","1707","100002704","1725","1738","1724","1721","100002705","1727","100002706","1705","1733","1732","100002929","1736","1737","100002930","100002931","100002989","1742","100002990","100002992","100002999","1735","1729","2718","190000152","4204","1428","4201","130109","4202","1456","1455","130114","1459","15380303","15380306","4208","4206","4203","15380307","3010","4209","4299","15380308","15380311","1466","4207","15380312","15380313","15380314","1336","130181","371104","146909","1458","130115","130116","146903","100009739","146901","4106","146910","100009741","130122","130112","130123","146902","100009740","1460","146911","100003119","100002660","2717","100003172","100009267","100003100","100009361","100009383","23190403","100005652","100004809","100004814","125","2716","290315","100004817","152303","3710","1541","100004932","1514","100004944","100004936","1516","100002622","100006206","100001746","1522","100000050","100000013","614","100000016","2708","290306","631","100000015","100000011","100000012","628","100000054","100000014","648","100000026","530","3903","100009199","150403","100009161","100009100","150402","3999","100009165","100009174","3809","3807","4348","152409","152404","152499","3801","3805","3802","100002610","3806","3810","3803","153701","153702","153703","505","153704","153713","153705","153716","153717","153718","153714","826","142006","1904","153712","153706","153708","153709","153710","620","153715","153711","936","941","944","146703","935","1406","146701","100006465","90904","921","902","917","100008959","110502","90902","100006464","2711","280709","90901","100006461","290309","280707","908","904","90903","90907","937","903","939","90905","911","1426","280509","916","90906","4320","100009214","940","918","943","942","922","100006466","90908","283410","100000300","100006733","280710","938","4330","8003","83206","8004","8005","8006","807","8007","8008","8009","8010","8011","1471","8012","8013","808","8014","83207","4334","1480","3030","3008","3011","3009","301002","301001","3010","301003","3016","3005","3015","3012","2728","3014","3019","3006","3007","281919","282914","282904","510","2826","282999","2406","282903","282901","282905","2818","32203","32204","32213","32212","100001662","100003552","100002697","100001696","100001615","32219","32299","32205","32216","32209","32210","32218","344","32220","32221","32206","32208","100002699","32217","100001606","100005611","100005252","290318","100005259","100005300","100005322","100005334","100005383","100005663","100005431","2719","100005493","100005505","100002699","2042","301","100005525","100005568","100005575","100005597","41601","100000938","423","430401","415","100000936","2706","414","280504","290304","405","110501","408","41602","100000941","100003278","4306","41603","100000947","430402","41604","100000948","417","427","430403","142016","12503","142003","142013","100006889","100009597","142099","100006906","142011","1417","190000082","142014","142001","142015","100006919","100006925","142012","3411","1219","100003541","1218","1204","1228","1222","120703","1227","122704","3407","100003403","1205","190000166","1221","2713","290312","100003247","100002922","1225","1206002","100003430","3406","1203","100003401","1239","1232","1238","100004967","1206001","1206","121888","1223","1231","1230","100009395","100009399","4314","1416","100003850","100009396","1326","100006936","14","1530","100009418","100006937","1426","4399","100009422","100009417","100006900","100009424","100009431","100006819","100008386","1431","100004750","100003868","1456","100006442","100004754","100006450","100004753","100003880","100004755","1433","4113","4101","100004752","4110","4111","4107","4108","190000147","100006897","100004737","100006799","100004756","100006837","100006831","1458","100004758","100004799","131006","100004741","1469","100008662","1407","4106","100004757","4109","100004751","100004789","1460","1427","211111","211112","212002","100003832","2114","2202","501","150415","21200204","2720","100003738","211106","2113","2139","21111107","100005094","100003804","2110","2213","232006","2209","100003745","100003836","100003809","100003819","100003766","2112","2115","211103","100003742","2102","2138","2117","2140","21110709","100009730","100003268","100000276","100002825","230606","100002832","230602","230603","231903","230604","231902","100002842","231909","230611","231905","2316","2721","230608","230616","2317","2314","100001140","230609","231910","230617","190000066","2302","2303","2305","2312","100003573","2318","100002856","4328","100002862","4327","2308","231904","230607","100002863","4322","100004839","3597","230615","230111","230610","230618","230699","231911","2320","231999","5092101","100002951","100002954","503","100002953","1528","1512","1509","1511","2621","100001622","100001668","2624","100001626","2605","100001625","100001629","100001660","100001700","100009099","100001667","100001623","100006217","100001697","100001624","100006011","2614","100001628","2607","2631","100001659","100001627","2610","100006010","2603");
shuffle($CATEGORY_IDKU); 
$thecurrent_page=1;

$CATALOG_ID= $CATEGORY_IDKU[0];
if(file_exists("In-Category/".$CATALOG_ID.".html")){
	$hikon= file_get_contents("In-Category/".$CATALOG_ID.".html");
	echo str_replace('NOINDEX, ', '', $hikon);
	exit();
}

$target= 'https://m.alibaba.com/catalogs/--'.$CATALOG_ID.'/'.$thecurrent_page.'?XPJAX=1'; 
$ttt= ALI_NORMAL_SCRAPPING($target, false, true);
$array= json_decode($ttt,1);

if(!empty($array['config']['cateName']) && !empty($array['config']['parentCateName'])){
	$title_to_keywords= $array['config']['cateName'].' '.$array['config']['parentCateName'];
	$theroom_page_title= '@'.$CATALOG_ID.' '.$title_to_keywords.' Source quality '.$title_to_keywords.' from Global '.$title_to_keywords;
	$theroom_metadescription= $theroom_page_title;
}else{ 
$theroom_page_title= 'All Product In Catalog Id '.$CATALOG_ID.' Source quality '.$CATALOG_ID.' from Global Catalog '.$CATALOG_ID; 
$theroom_metadescription= $theroom_page_title;
}
	

toCreateFolder('product_detail');
	
$konten= '<!DOCTYPE html><html lang=en><head><meta charset=utf-8><meta name=viewport content="width=device-width, initial-scale=1"><title>'.$theroom_page_title.' @Page '.$thecurrent_page.'</title><meta name="description" content="@page '.$thecurrent_page.' '.$theroom_metadescription.'"><link rel=stylesheet href=https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css><script src=https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js></script><script src=https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js></script><meta name="robots" content="NOINDEX, FOLLOW, NOARCHIVE, NOTRANSLATE"></head><body><div class=container><h2>'.$theroom_page_title.' @Page '.$thecurrent_page.'</h2><table class="table table-striped"><thead><tr><th>Table 1</th><th>Table 2</th><th>Table 3</th><th>Table 4</th></tr></thead><tbody>';


$tks=0;
	foreach($array['productNormalList'] as $listproduct){
$currr= $tks % 4;	
	
	if(empty($listproduct['fobPriceFrom'])){
		$product_price_from= 1.00;
	}else{ $product_price_from= $listproduct['fobPriceFrom'];}
	if(empty($listproduct['fobPriceTo'])){
		$product_price_to= 10.00;
	}else{ $product_price_to= $listproduct['fobPriceTo'];}
	if(empty($listproduct['minOrderUnit'])){
		$product_minOrderUnit= 'Set';
	}else{ $product_minOrderUnit= $listproduct['minOrderUnit'];}
	if(empty($listproduct['mainProducts'])){
		$product_cat_showroom="";
		$product_maintags="";
	}else{ $product_cat_showroom= string_to_product_showroom($listproduct['mainProducts']); $product_maintags= $listproduct['mainProducts'];}
		
		$product_currency= 'US $';
		$product_title= $listproduct['productName'];
		$product_company= $listproduct['companyName'];
		$product_image= $listproduct['imagePath'];
		$product_id= $listproduct['id'];
		$product_filesname= 'product_detail/'.preg_replace('/((.*)\/|\.html(.*))/i', '', $listproduct['productDetailUrl']).'_'.$product_id.'.html';
		$product_fix_permalink= '/'.$product_filesname;
		$product_landingpage= "/message-suplier?product_id=".$product_id."&visit=short&product_title=".urlencode($product_title);
		
		if($currr == 0){
			$konten .= '<tr><td><a href="'.$product_fix_permalink.'">'.$product_title.'</a></td>';
			 }
				 if($currr == 1 || $currr == 2){
				$konten .= '<td><a href="'.$product_fix_permalink.'">'.$product_title.'</a></td>';
				 }
					if($currr == 3){
					$konten .= '<td><a href="'.$product_fix_permalink.'">'.$product_title.'</a></td></tr>';
					}

		if(!file_exists($product_filesname)){			
					//SAVE TO CACHE AS PRODUCT SINGLE
		$data= '<html><head><title>Wholesale '.$product_title.' @'.$product_id.'</title><meta name="robots" content="NOARCHIVE, NOTRANSLATE"><meta name="description" content="@'.$product_id.' Wholesale '.$product_title.' by '.$product_company.' You can get more detail about '.$product_title.'"><script>window.location = "'.$product_landingpage.'";</script><link rel="canonical" href="'.$product_fix_permalink.'"></head><body><div id="shop-product"><h1>'.$product_title.'</h1><div class="image-product"><a href="'.$product_landingpage.'" rel="nofollow"><img src="'.$product_image.'" alt="'.$product_title.'"/></a></div><div class="product-price" itemprop="offers" itemscope="" itemtype="//schema.org/AggregateOffer"> <span itemprop="priceCurrency" content="USD">US $</span> <span itemprop="lowPrice">'.$product_price_from.'</span>-<span itemprop="highPrice">'.$product_price_to.'</span> <span itemprop="unit"> / '.$product_minOrderUnit.'</span></div><h2>Supplier : '.$product_company.'</h2><p class="product-description">#'.$product_id.' '.$product_title.' by '.$product_company.' In Main Tags '.$product_maintags.'</p><p class="product-showroom">You are in : '.$product_cat_showroom.'</p><p class="product-buy"><a href="'.$product_landingpage.'" rel="nofollow"><img src="https://i.imgur.com/4D49gMr.png"></a></p></div></body></html><!-- In Cached -->';	
					//END SAVE TO CACHE AS PRODUCT SINGLE
					$ftr= fopen($product_filesname, "w");
					fwrite($ftr, $data);
					fclose($ftr);
		}
					
$tks++;		
	}
	
	 $konten .='</tbody></table><ul class="pager">';
	$icurrpage= $thecurrent_page; 
	$ilanjutpage= $icurrpage+1;
	$ibalikpage= $icurrpage-1;
	
$targetb= 'https://m.alibaba.com/catalogs/--'.$CATALOG_ID.'/'.$ilanjutpage.'?XPJAX=1'; 
$tttb= ALI_NORMAL_SCRAPPING($targetb, true);
$arrayb= json_decode($tttb,1);
//prevpage	
	if($ibalikpage < 2){
		$konten .='<li><a href="/In-Category/'.$CATALOG_ID.'.html">Previous</a></li>';
	}else{
		$irpage_key= substr(md5('dafamedia'.$ibalikpage),0,5);
		$konten .='<li><a href="/In-Category/'.$CATALOG_ID.'_'.$ibalikpage.$irpage_key.'.html">Previous</a></li>';
	}
	
//nextpage
	if(isset($arrayb['productNormalList']) && !empty($arrayb['productNormalList'])){
		$irpage_key= substr(md5('dafamedia'.$ilanjutpage),0,5);
		$konten .='<li><a href="/In-Category/'.$CATALOG_ID.'_'.$ilanjutpage.$irpage_key.'.html">Next</a></li>';
	}else{
		$konten .='<li><a href="'.$_SERVER['REQUEST_URI'].'">Next</a></li>';
	}


  
$konten .='</ul></div></body></html>';

echo str_replace('NOINDEX, ', '', $konten).'<!-- NOT CACHE -->';
$konten .='<!-- In Cached -->';

$files= 'In-Category/'.$CATALOG_ID.'.html';

if(!file_exists($files)){
toCreateFolder('In-Category');
$fop= fopen($files, "w");
fwrite($fop, $konten);
fclose($fop);
    }

exit(); 
 //END IF IN DEFAULT INDEX
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 



function ALI_NORMAL_SCRAPPING($URL, $CACHE=false, $READCACHE=false, $status="sukses"){ //scrape category pagination  
$thefiles_temporary= md5($URL).'.txt';
$createfolders= toCreateFolder("temporary_cache","yes");
   if($READCACHE){
		if(file_exists("temporary_cache/".$thefiles_temporary)){
			$thedata= file_get_contents("temporary_cache/".$thefiles_temporary);
			@unlink("temporary_cache/".$thefiles_temporary);
			return $thedata;
		}
   }
   
   
    $data = curl_init();
	$header[0] = "Accept: text/xml,application/xml,application/xhtml+xml,";
	$header[0] .= "text/html;q=0.9,text/plain;q=0.8,image/png,*/*;q=0.5";
	$header[] = "Cache-Control: max-age=0";
	$header[] = "Connection: keep-alive";
	$header[] = "Keep-Alive: 300";
	$header[] = "Accept-Charset: ISO-8859-1,utf-8;q=0.7,*;q=0.7";
	$header[] = "Accept-Language: en-us,en;q=0.5";
	$header[] = "Pragma: "; // browsers keep this blank.

     curl_setopt($data, CURLOPT_SSL_VERIFYHOST, FALSE);
     curl_setopt($data, CURLOPT_SSL_VERIFYPEER, FALSE);
     curl_setopt($data, CURLOPT_URL, $URL);

if($status == "sukses"){
	 curl_setopt($data, CURLOPT_USERAGENT, 'Googlebot');
}else{
$uarr= array('bingbot','yahoo sulrp','yandexbot','baidu','msnbot');
shuffle($uarr);
         curl_setopt($data, CURLOPT_USERAGENT, $uarr[array_rand($uarr)]);
}

	 curl_setopt($data, CURLOPT_HTTPHEADER, $header);
	 curl_setopt($data, CURLOPT_REFERER, 'http://www.alibaba.com');
	 curl_setopt($data, CURLOPT_ENCODING, 'gzip,deflate');
	 curl_setopt($data, CURLOPT_AUTOREFERER, true);
	 curl_setopt($data, CURLOPT_RETURNTRANSFER, 1);
	 curl_setopt($data, CURLOPT_CONNECTTIMEOUT, 15);
	 curl_setopt($data, CURLOPT_TIMEOUT, 15);
	 curl_setopt($data, CURLOPT_MAXREDIRS, 3);
	 curl_setopt($data, CURLOPT_FOLLOWLOCATION, true);

     $hasil = curl_exec($data);
     curl_close($data);	

	 if(empty($hasil)){
		 ALI_NORMAL_SCRAPPING($URL, $CACHE, "gagal");
	 }

			if($CACHE){
		if(!file_exists("temporary_cache/".$thefiles_temporary)){
			$thedata= fopen("temporary_cache/".$thefiles_temporary, "w");
			fwrite($thedata, $hasil);
			fclose($thedata);
		}
			}
	
		return $hasil;
}


function toCreateFolder($folders, $createIndex=""){
	if(!file_exists($folders)){
		$oldmask = umask(0);
		mkdir($folders, 0777);
		umask($oldmask);
	}
		if($createIndex){
			if(!file_exists($folders."/index.html")){
				$fff=fopen($folders."/index.html", "w");
				fclose($fff);
			}
		}
return false;	
}


function string_to_product_showroom($string, $thisis_array=false){
		if(!$thisis_array){
	$array= explode(',', $string);
		}else{
	$array= $string;		
		}
		foreach($array as $items){
			$items= strtolower($items);
			$plink= '/read-showroom/'.trim(preg_replace('/([^a-z0-9]+|-+)/i', '-', $items)).'.html';
			$data[]= '<a href="'.$plink.'">'.$items.'</a>';
		}
	return implode(' > ', $data);	
}







