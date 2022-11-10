<?php
// a php program to dump the json from the given website into a csv file
$url = 'https://dummyjson.com/products/search?q=Laptop';
$c_session = curl_init();
curl_setopt($c_session, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($c_session, CURLOPT_URL, $url);
$result_url = curl_exec($c_session);
$json = json_decode($result_url);
$filename = "laptop.csv";
$f = fopen($filename, 'w');
$col_head = ["title", "price", "brand"];
fputcsv($f, $col_head);
foreach($json->products as $js){
    $title = $js->title;
    $brand = $js->brand;
    $price = $js->price;
    $row = [$title, $price, $brand];
    fputcsv($f, $row);
    echo $title . "\n". $brand . "\n" . $price . "<br>";
}


?>