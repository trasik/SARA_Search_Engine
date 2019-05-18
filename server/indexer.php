<?php
require 'connection.php';

$already_crawled = array();
$totalCount = 0;
$totalURL = 0;

if (isset($_POST['psubmit'])) {
  set_time_limit(0);
  $root = $_POST['name'];
  $root = filter_var($root, FILTER_SANITIZE_URL);
  if (filter_var($root, FILTER_VALIDATE_URL, FILTER_FLAG_SCHEME_REQUIRED | FILTER_FLAG_HOST_REQUIRED)) {
    $parseOpt = $_POST["parseOpt"];
    if($parseOpt == 'single') {
      $start = microtime(true);
      $totalURL = 1;
      $root = sanitizeUrl($root, $root);
      if (substr($root, -1) != "/") $root = $root."/";
      $info = getInfo($root);
      if($info == 'empty') {
				return;
			}
      $title = (string)$info['title'];
			$description = (string)$info['description'];
			$u = (string)$info['url'];
      if (strpos($u, '#') !== false || strpos($u, '?') !== false) {
        return;
      }

      $sql = "SELECT * FROM `page` WHERE `url` = '{$u}'";
      $results = $conn->query($sql);
      if ($results->num_rows > 0) {
        $sql = "UPDATE `page` SET title=?, description=?, url=? WHERE url=?";
        $sql = $conn->prepare($sql);
        $sql->bind_param("ssss", $title, $description, $u, $u);
        output("Updated URL: $u<br>");
        $sql->execute();
      } else {
        $sql = $conn->prepare("INSERT INTO `page`(`title`, `description`, `url`) VALUES (?,?,?)");
        $sql->bind_param("sss", $title, $description, $u);
        output("Inserted URL: $u<br>");
        $sql->execute();

        $text = getWords($root);
        $words = explode(" ", $text);
        $count = 0;
        foreach($words as $word) {
          if(empty($word)) continue;
          $sql = "SELECT * FROM `word` WHERE `wordName` = '{$word}'";
          $results = $conn->query($sql);
          if($results->num_rows > 0) {
            continue;
          } else {
            $count++;
            $sql = $conn->prepare("INSERT INTO `word`(`wordName`) VALUES (?)");
            $sql->bind_param("s", $word);
            $sql->execute();
          }
        }
        output("Total Words Inserted: $count<br>");
        $totalCount = $count;

        $sql = "SELECT pageid FROM `page` WHERE `url` = '{$u}'";
        $results = $conn->query($sql);
        $results = mysqli_fetch_assoc($results) ;
        $pageID = $results['pageid'];
        $seenWords=[];
        foreach($words as $word) {
          if(empty($word)) continue;
          $sql = "SELECT wordid FROM `word` WHERE `wordName` = '{$word}'";
          $results = $conn->query($sql);
          $results = mysqli_fetch_assoc($results);
          $wordID = $results['wordid'];
          if(!in_array($word, $seenWords)) {
            $seenWords[] = $word;
            $count = 1;
            $sql = "INSERT INTO `page_word` (pageid, wordid, freq_wordcount) VALUES (?, ?, ?)";
            $sql = $conn->prepare($sql);
            $sql->bind_param("iii", $pageID, $wordID, $count);
            $sql->execute();
          } else {
            $sql = "SELECT freq_wordcount FROM `page_word` WHERE pageid='{$pageID}' AND wordid='{$wordID}'";
            $results = $conn->query($sql);
            $results = mysqli_fetch_assoc($results) ;
            $count = $results['freq_wordcount'] + 1;
            $sql = "UPDATE `page_word` SET freq_wordcount=? WHERE pageid=? AND wordid=?";
            $sql = $conn->prepare($sql);
            $sql->bind_param("iii", $count, $pageID, $wordID);
            $sql->execute();
          }
        }
        $finish = microtime(true) - $start;
        $sql = "UPDATE `page` SET timeToIndex=$finish WHERE pageid=$pageID";
        mysqli_query($conn, $sql);
        $sql = "INSERT INTO `indexer` (`baseUrl`, `option`, `totalCount`, `totalLinks`, `totalTime`) VALUES ('$u', '$parseOpt' , $totalCount, $totalURL, $finish)";
        mysqli_query($conn, $sql);
      }
    } else if($parseOpt == 'multiple') {
      $depth = $_POST["depth"];
      if(empty($depth)) {
        header("Location: indexpanel.php?error=emptydepth");
        exit();
      }
      if($depth > 5) {
        header("Location: indexpanel.php?error=invaliddepth");
        exit();
      }
      $start = microtime(true);
      crawl($root, $depth);
      $finish = microtime(true) - $start;
      $sql = "INSERT INTO `indexer` (baseUrl, `option`, totalCount, totalLinks, totalTime) VALUES ('$root', '$parseOpt', $totalCount, $totalURL, $finish)";
      mysqli_query($conn, $sql);
      output("Finished Indexing Pages in $finish seconds.<br>");
    }
  } else {
    output("$root is not a valid URL. Must include the scheme and host http://www or https://www.");
  }
}

function crawl($url, $depth) {
  if ($depth <= 0) return;

	global $already_crawled;
  global $totalCount;
  global $totalURL;
  global $conn;

	$dom = getReq($url);

	$links = $dom->getElementsByTagName("a");
	foreach ($links as $link) {
    $start = microtime(true);
		$a =  $link->getAttribute("href");
    $a = sanitizeUrl($a, $url);
    if (substr($a, -1) != "/") $a = $a."/";
    $check = explode(".", $a);
    if (sizeof($check) <= 1) {
      output("Skipped URL: $a <br>");
      continue;
    }
		if (@!in_array($a, $already_crawled)) {
			$already_crawled[] = $a;

			$info = getInfo($a);
      if($info == 'empty') {
				continue;
			}
      $title = (string)$info['title'];
			$description = (string)$info['description'];
			$u = (string)$info['url'];
      if (strpos($u, '#') !== false || strpos($u, '?') !== false) {
       continue;
      }

      $sql = "SELECT * FROM `page` WHERE `url` = '{$u}'";
      $results = $conn->query($sql);
      if ($results->num_rows > 0) {
        $sql = "UPDATE `page` SET title=?, description=?, url=? WHERE url=?";
        $sql = $conn->prepare($sql);
        $sql->bind_param("ssss", $title, $description, $u, $u);
        output("Updated URL: $u<br>");
        $sql->execute();
      } else {
        $sql = $conn->prepare("INSERT INTO `page`(`title`, `description`, `url`) VALUES (?,?,?)");
        $sql->bind_param("sss", $title, $description, $u);
        output("Inserted URL: $u<br>");
        $sql->execute();

        $totalURL++;
        $text = getWords($a);
        $words = explode(" ", $text);
        $count = 0;
        foreach($words as $word) {
          if(empty($word)) continue;
          $sql = "SELECT * FROM `word` WHERE `wordName` = '{$word}'";
          $results = $conn->query($sql);
          if($results->num_rows > 0) {
            continue;
          } else {
            $count++;
            $sql = $conn->prepare("INSERT INTO `word`(`wordName`) VALUES (?)");
            $sql->bind_param("s", $word);
            $sql->execute();
          }
        }
        output("Total Words Inserted: $count<br>");
        $totalCount += $count;

        $sql = "SELECT pageid FROM `page` WHERE `url` = '{$u}'";
        $results = $conn->query($sql);
        $results = mysqli_fetch_assoc($results) ;
        $pageID = $results['pageid'];
        $seenWords=[];
        foreach($words as $word) {
          if(empty($word)) continue;
          $sql = "SELECT wordid FROM `word` WHERE `wordName` = '{$word}'";
          $results = $conn->query($sql);
          $results = mysqli_fetch_assoc($results) ;
          $wordID = $results['wordid'];
          if(!in_array($word, $seenWords)) {
            $seenWords[] = $word;
            $count = 1;
            $sql = "INSERT INTO `page_word` (pageid, wordid, freq_wordcount) VALUES (?, ?, ?)";
            $sql = $conn->prepare($sql);
            $sql->bind_param("iii", $pageID, $wordID, $count);
            $sql->execute();
          } else {
            $sql = "SELECT freq_wordcount FROM `page_word` WHERE pageid='{$pageID}' AND wordid='{$wordID}'";
            $results = $conn->query($sql);
            $results = mysqli_fetch_assoc($results) ;
            $count = $results['freq_wordcount'] + 1;
            $sql = "UPDATE `page_word` SET freq_wordcount=? WHERE pageid=? AND wordid=?";
            $sql = $conn->prepare($sql);
            $sql->bind_param("iii", $count, $pageID, $wordID);
            $sql->execute();
          }
        }
        $finish = microtime(true) - $start;
        $sql = "UPDATE `page` SET timeToIndex=$finish WHERE pageid=$pageID";
        mysqli_query($conn, $sql);
      }
		}
    crawl($a, $depth - 1);
	}
}

function getReq($url) {
  $curl = curl_init();
  curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
  curl_setopt($curl, CURLOPT_HEADER, FALSE);
  curl_setopt($curl, CURLOPT_FOLLOWLOCATION, TRUE);
  curl_setopt($curl, CURLOPT_URL, $url);
  curl_setopt($curl, CURLOPT_FAILONERROR, TRUE);
  curl_setopt($curl, CURLOPT_REFERER, $url);
  curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
  curl_setopt($curl, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 6.1; en-US) AppleWebKit/533.4 (KHTML, like Gecko) Chrome/5.0.375.125 Safari/533.4");
  $html = curl_exec($curl);
  curl_close($curl);

  $dom = new DOMDocument();
  @$dom->loadHtml($html);

  return $dom;
}

function output($str) {
  echo $str;
  ob_flush();
  flush();
}

function outputArr($str) {
  print_r($str);
  ob_flush();
  flush();
}

function sanitizeUrl($url, $base) {
	if (substr($url, 0, 1) == "/" && substr($url, 0, 2) != "//") {
		$url = parse_url($base)["scheme"]."://".parse_url($base)["host"].$url;
	} else if (substr($url, 0, 2) == "//") {
		$url = parse_url($base)["scheme"].":".$url;
	} else if (substr($url, 0, 2) == "./") {
		$url = parse_url($base)["scheme"]."://".parse_url($base)["host"]. "/". dirname(parse_url($base)["path"]).substr($url, 1);
	} else if (substr($url, 0, 1) == "#") {
    return $url;
	} else if (substr($url, 0, 3) == "../") {
		$url = parse_url($base)["scheme"]."://".parse_url($base)["host"]."/".$url;
	} else if (substr($url, 0, 11) == "javascript:") {
		return;
	} else if (substr($url, 0, 5) != "https" && substr($url, 0, 4) != "http") {
		$url = parse_url($base)["scheme"]."://".parse_url($base)["host"]."/".$url;
	}
	return $url;
}

function getInfo($url) {
	$dom = getReq($url);
	$title = $dom->getElementsByTagName("title");
	@$title = $title->item(0)->nodeValue;

  if(empty($title) || (strpos($title, "Error") !== false)) {
		return 'empty';
	}

	$description = "";
	$metas = $dom->getElementsByTagName("meta");
	for ($i = 0; $i < $metas->length; $i++) {
		$meta = $metas->item($i);
		if (strtolower($meta->getAttribute("name")) == "description")
			$description = $meta->getAttribute("content");
	}

  if($description == '') {
    $description = "The home page of ". $title;
  }

  $info = array(
		"title"=>trim($title),
		"description"=>trim($description),
		"url"=>$url);

  return $info;
}

function getWords($url) {
  global $conn;
  $dom = getReq($url);

  $tagsToSearch = array('p');
  $string = "";
  foreach($tagsToSearch as $tag) {
    $element = $dom->getElementsByTagName($tag);
    foreach($element as $item) {
      $text = $item->nodeValue;
      $text = preg_replace("/[^a-zA-Z0-9_ -]/s","",$text);
      $arr = str_word_count($text, 1);
      foreach ($arr as $key => $value) {
        $string .= " " . $value;
      }
    }
  }
  return $string;
}
?>
