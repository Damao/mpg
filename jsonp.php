<?php
/**
 * Created by IntelliJ IDEA.
 * User: bigCat
 * Date: 12-7-11
 * Time: 下午7:32
 * To change this template use File | Settings | File Templates.
 */
error_reporting(E_ALL);

include_once('lib/simple_html_dom.php');

date_default_timezone_set("PRC");
$today = date("Ymd", time());
function replace($source)
{
    $source_oneline = preg_replace('/\n|\r/', '', $source);
    $match = preg_replace('/<div class="rumors".*?(?=<div class="releases)/', '', $source_oneline);
    $match = preg_replace('%<div class="sponsorblock">.*?</div>%', '', $match);
    $match = preg_replace('%<div id="buyersIntro".*?</div>%', '', $match);

    preg_match('/<div id="content">.*?(?=<div id="right">)/', $match, $match);

    $match = preg_replace('%<h2>.*?>(.*?)</a></h2>%', '<h2>$1</h2>', $match);
    $match = preg_replace('%<div class="date">.*?>(.*?)</a></div>%', '<div class="date">$1</div>', $match);
    $match = preg_replace('/<h3>.*?(?=<ul class="buyerslist")/', '', $match);

    $match = str_replace("http://images.macrumors.com/", "", $match);
    $match = str_replace('src="/', 'src="', $match);
    $match = str_replace("Apple Cinema Display", "Cinema Display", $match);
    $match = str_replace("Recent Releases", "发布周期", $match);
    $match = str_replace("Last Release", "最后更新", $match);
    $match = str_replace("Avg", "平均", $match);
    $match = str_replace("Days Since Update", "距离上次更新", $match);
    $match = str_replace("Recommendation", "推荐程度", $match);
    $match = str_replace("Buy Now", "趁现在", $match);
    $match = str_replace("Product just updated", "刚刚发布", $match);
    $match = str_replace("Buy only if you need it", "按需购买", $match);
    $match = str_replace("Neutral", "一般般", $match);
    $match = str_replace("Mid-product cycle", "更新周期中", $match);
    $match = str_replace("Approaching the end of a cycle", "寿命快到了", $match);
    $match = str_replace("Don't Buy", "别买", $match);
    $match = str_replace("Updates soon", "马上就有更新", $match);
    $match = str_replace("Still outdated, next possible update in 2013", "过气,看13年更新", $match);
//    var_dump($match);
    if (is_array($match)) {
        return $match[0];
    } else {
        return $match;
    }
}

if (file_exists($today . ".html")) {
    $html = file_get_contents($today . ".html");
} else {
    $source = file_get_contents("http://buyersguide.macrumors.com/");
    $html = replace($source);
    $filename = $today . '.html';
    $fh = fopen($filename, "w");
    fwrite($fh, $html);
    fclose($fh);
}

$htmlDOM = str_get_html($html);

$product_mba = $htmlDOM->find(".guide", 0);
$product_mba_json = '"mba":\'' . $product_mba . '\'';
$product_mba_update = $htmlDOM->find(".guide", 0)->find(".days", 0);

$product_mpb = $htmlDOM->find(".guide", 1);
$product_mpb_json = '"mbp":\'' . $product_mpb . '\'';
$product_mpb_update = $htmlDOM->find(".guide", 1)->find(".days", 0);

$product_ipad = $htmlDOM->find(".guide", 2);
$product_ipad_json = '"ipad":\'' . $product_ipad . '\'';
$product_ipad_update = $htmlDOM->find(".guide", 2)->find(".days", 0);

$product_itouch = $htmlDOM->find(".guide", 3);
$product_itouch_json = '"itouch":\'' . $product_itouch . '\'';
$product_itouch_update = $htmlDOM->find(".guide", 3)->find(".days", 0);

$product_nano = $htmlDOM->find(".guide", 4);
$product_nano_json = '"nano":\'' . $product_nano . '\'';
$product_nano_update = $htmlDOM->find(".guide", 4)->find(".days", 0);

$product_iphone = $htmlDOM->find(".guide", 5);
$product_iphone_json = '"iphone":\'' . $product_iphone . '\'';
$product_iphone_update = $htmlDOM->find(".guide", 5)->find(".days", 0);

$product_cinema = $htmlDOM->find(".guide", 6);
$product_cinema_json = '"cinema":\'' . $product_cinema . '\'';
$product_cinema_update = $htmlDOM->find(".guide", 6)->find(".days", 0);

$product_macpro = $htmlDOM->find(".guide", 7);
$product_macpro_json = '"macpro":\'' . $product_macpro . '\'';
$product_macpro_update = $htmlDOM->find(".guide", 7)->find(".days", 0);

$product_macmini = $htmlDOM->find(".guide", 8);
$product_macmini_json = '"macmini":\'' . $product_macmini . '\'';
$product_macmini_update = $htmlDOM->find(".guide", 8)->find(".days", 0);

$product_imac = $htmlDOM->find(".guide", 9);
$product_imac_json = '"imac":\'' . $product_imac . '\'';
$product_imac_update = $htmlDOM->find(".guide", 9)->find(".days", 0);

$product_ipodshuffle = $htmlDOM->find(".guide", 10);
$product_ipodshuffle_json = '"ipodshuffle ":\'' . $product_ipodshuffle . '\'';
$product_ipodshuffle_update = $htmlDOM->find(".guide", 10)->find(".days", 0);

$product_ipodclassic = $htmlDOM->find(".guide", 11);
$product_ipodclassic_json = '"ipodclassic":\'' . $product_ipodclassic . '\'';
$product_ipodclassic_update = $htmlDOM->find(".guide", 11)->find(".days", 0);

$jsonData_all = '{' . $product_mba_json .','. $product_mpb_json .','. $product_ipad_json .','. $product_itouch_json .','. $product_nano_json .','. $product_iphone_json .','. $product_cinema_json .','. $product_macpro_json .','. $product_macmini_json .','. $product_imac_json .','. $product_ipodshuffle_json .','. $product_ipodclassic_json.'}';
switch ($_GET["device"]) {
    case "all":
        echo $_GET['callback'] . '(' . $jsonData_all . ');';
}
