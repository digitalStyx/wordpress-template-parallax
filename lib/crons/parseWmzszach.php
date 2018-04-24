<?php

if (!defined('ABSPATH')) {
    define('ABSPATH', dirname(__FILE__) . '/../../../../../');
}

require_once(ABSPATH . 'wp-config.php');
require_once(ABSPATH . 'wp-settings.php');
/*
$html = @file_get_contents("/export/www/tmp/wmzszach.txt");
if (empty($html)) {
    $html = file_get_contents("http://www.wmzszach.cba.pl/aktualnosci.html");
    file_put_contents("/export/www/tmp/wmzszach.txt", $html);
}
*/
$dom = new DOMDocument;
@$dom->loadHTML($html);

$finder = new DomXPath($dom);
$nodes = $finder->query("//*[contains(concat(' ', normalize-space(@class), ' '), 'ramka')]");

$array = array();
foreach ($nodes as $div) {
    if (empty($div->getElementsByTagName('h2')[0]->nodeValue)) {
        continue;
    }
    $title = $div->getElementsByTagName('h2')[0]->nodeValue;
    $find = $div->getElementsByTagName('div')[0];


    foreach ($find->getElementsByTagName('img') as $img) {
        $img->setAttribute('src', "http://www.wmzszach.cba.pl/" . $img->getAttribute('src'));
    }

    $document = new DOMDocument();
    $document->appendChild($document->importNode($find, true));
    $text = $document->saveHTML();

    $array[] = array(
        'choice' => 'parallax_icon',
        'icon_value' => 'none',
        'title' => esc_html__($title, 'parallax-one'),
        'text' => __($text, 'parallax-one'),
        'image_url' => ''
    );
}

set_theme_mod('parallax_one_services_content',
    json_encode(
        $array
    )
);