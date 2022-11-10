<?php
$hashArr = array();
$category = strtolower("Science");

function word_digit($value)
{

    $result = '';

    switch (strtolower(trim($value))) {
        case 'zero':
            $result .= 0.0;
            break;
        case 'one':
            $result .= 1.0;
            break;
        case 'two':
            $result .= 2.0;
            break;
        case 'three':
            $result .= 3.0;
            break;
        case 'four':
            $result .= 4.0;
            break;
        case 'five':
            $result .= 5.0;
            break;
        case 'six':
            $result .= 6.0;
            break;
        case 'seven':
            $result .= 7.0;
            break;
        case 'eight':
            $result .= 8.0;
            break;
        case 'nine':
            $result .= 9.0;
            break;
    }
    return $result;
}


function genUniq($hashArr)
{

    $tmpId = substr(uniqid(), 6);
    $hsval = hash("md5", $tmpId, false);
    $count = 0;
    while (array_key_exists($hsval, $hashArr)) {
        $tmpId = substr(uniqid(), 6);
        $hsval = hash("md5", $tmpId, false);
        if ($count >= 100) {
            break;
        }
        $count += 1;
    }
    return [$tmpId, $hsval];
}


$books  = array();
$url = 'https://books.toscrape.com/';
$c_session = curl_init();
curl_setopt($c_session, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($c_session, CURLOPT_URL, $url);
$result_url = curl_exec($c_session);
$html = new DOMDocument();
libxml_use_internal_errors(true);
$html->loadHTML($result_url);
$navList = $html->getElementsByTagName('a');
foreach ($navList as $nav) { //assuming there is only one category value that is to be crawled to
    $val = $nav->nodeValue;
    $val = strtolower(trim($val));
    if ($val === $category) {
        $href = $nav->getAttribute('href');
    }
}

function scrapper($books, $hashArr, $url, $c_session, $href, $html, $category, $next = "")
{
    if ($next !== "") {
        $categoryUrl = $url . substr($href, 0, strlen($href) - 10) . $next;
    } elseif ($next === "") {
        $categoryUrl = $url . $href;
    }


    curl_setopt($c_session, CURLOPT_URL, $categoryUrl);
    $category_result = curl_exec($c_session);
    $html->loadHTML($category_result);
    $next = $html->getElementsByTagName("a");
    foreach ($next as $n) {
        $val = $n->nodeValue;
        if ($val === "next") {
            $nextHref = $n->getAttribute('href');
        } else {
            $nextHref = false;
        }
    }
    $xpath = new DOMXPath($html);
    $bookLinkList = $xpath->evaluate("//article/h3/a");
    foreach ($bookLinkList as $bl) {
        $bookLink = substr($bl->getAttribute("href"), 9);
        $bookUrl = $url . "catalogue/" . $bookLink;
        curl_setopt($c_session, CURLOPT_URL, $bookUrl);
        $bookResult = curl_exec($c_session);
        $book = new DOMDocument();
        $book->loadHTML($bookResult);
        $xpath = new DOMXPath($book);
        $bookNames = $xpath->query("//h1");
        foreach ($bookNames as $b) {
            $bookTitle = $b->nodeValue;
        }
        $para = $xpath->query("//div[@class='row']/div/p");
        foreach ($para as $p) {
            if ($p->getAttribute("class") == "price_color") {
                $bookPrice = (float)substr($p->nodeValue, 2);
            } elseif ($p->getAttribute("class") == "instock availability") {
                $avail = $p->nodeValue;
            } elseif (substr($p->getAttribute("class"), 0, 11) == "star-rating") {
                $rating = word_digit(substr($p->getAttribute("class"), 11));
            }
        }
        $idPhs = genUniq($hashArr);
        $hashArr += [$idPhs[1] => $idPhs[0]];
        $tmpBook = array(
            "id" => $idPhs[0],
            "category" => $category,
            "category_url" => $categoryUrl,
            "title" => $bookTitle,
            "price" => $bookPrice,
            "stock" => $avail,
            "rating" => number_format($rating, 2, '.', ''),
            "url" => $bookUrl,
        );
        array_push($books, $tmpBook);
    }



    return [$nextHref, $hashArr, $books];
}




$hshPnxt = scrapper($books, $hashArr, $url, $c_session, $href, $html, $category);
$nextHref = $hshPnxt[0];
$hashArr = $hshPnxt[1];
$books = $hshPnxt[2];
while ($nextHref) {

    $hshPnxt = scrapper($books, $hashArr, $url, $c_session, $href, $html, $category, $nextHref);
    $nextHref = $hshPnxt[0];
    $hashArr = $hshPnxt[1];
    $books = $hshPnxt[2];
}


$filename = "science_listing.csv";

$f = fopen($filename, "w");
$column_heads = ["id","category", "category_url", "title", "price", "stock", "rating", "url"];
fputcsv($f, $column_heads);
foreach ($books as $b) {
    // foreach ($b as $key => $value) {
    //     echo "$key : $value <br>";
        
    // } 
    $row = [$b["id"], $b["category"], $b["category_url"], $b["title"], $b["price"], $b["stock"], $b["rating"], $b["url"]];
    fputcsv($f, $row);
    echo "<br>";
}

curl_close($c_session);
