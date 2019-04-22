<?php
require 'connection.php';

//$lastMod;

if (isset($_POST['psubmit'])) {
  $root = $_POST['name'];
  $root = filter_var($root, FILTER_SANITIZE_URL);
  if (filter_var($root, FILTER_VALIDATE_URL, FILTER_FLAG_SCHEME_REQUIRED | FILTER_FLAG_HOST_REQUIRED) && substr($root, -1) == '/') {
    $parseOpt = $_POST["parseOpt"];
    if($parseOpt == 'single') {
      $info = getInfo($root);
      if($info == 'empty') {
        return;
      }

      $title = (string)$info['title'];
      $description = (string)$info['description'];
      $u = (string)$info['url'];

      $query = "SELECT * FROM `pagetable` WHERE `url` = '{$u}'";
      $result = mysqli_query($conn, $query);
      $sql;
      if (mysqli_num_rows($result) > 0) {
        $stmt = "UPDATE `pagetable` SET title=?, description=?, url=? WHERE url=?";
        $sql = $conn->prepare($stmt);
        $sql->bind_param("ssss", $title, $description, $u, $u);
        echo $u . " Update " . "<br>";
        $sql->execute();
      } else {
        $sql = $conn->prepare("INSERT INTO `pagetable`(`title`, `description`, `url`) VALUES (?,?,?)");
        $sql->bind_param("sss", $title, $description, $u);
        $sql->execute();
      }
    } else if($parseOpt == 'multiple') {
      $time = $_POST["time"];
      if(empty($time)) {
        header("Location: indexpanel.php?error=emptytime");
        exit();
      }
      if($time >= 600) {
        header("Location: indexpanel.php?error=invalidtime");
        exit();
      }
      set_time_limit($time);
      $seen = array($root);
    	$url = array_shift($seen);
    	$seen = array_merge($seen, getLinks($url));
    }
  } else {
    echo("$root is not a valid URL. Must include the scheme and host http://www or https://www AND must include / at the end.");
  }
}

function sanitizeUrl($url, $base) {
	if (substr($url, 0, 1) == "/" && substr($url, 0, 2) != "//") {
		$url = parse_url($base)["scheme"]."://".parse_url($base)["host"].$url;
	} else if (substr($url, 0, 2) == "//") {
		$url = parse_url($base)["scheme"].":".$url;
	} else if (substr($url, 0, 2) == "./") {
		$url = parse_url($base)["scheme"]."://".parse_url($base)["host"].dirname(parse_url($base)["path"]).substr($url, 1);
	} else if (substr($url, 0, 1) == "#") {
		$url = parse_url($base)["scheme"]."://".parse_url($base)["host"].parse_url($base)["path"].$url;
	} else if (substr($url, 0, 3) == "../") {
		$url = parse_url($base)["scheme"]."://".parse_url($base)["host"]."/".$url;
	} else if (substr($url, 0, 11) == "javascript:") {
		return;
	} else if (substr($url, 0, 5) != "https" && substr($url, 0, 4) != "http") {
		$url = parse_url($base)["scheme"]."://".parse_url($base)["host"]."/".$url;
	}
	return $url;
}

function getReq($url) {
  //global $lastMod;
  $curl = curl_init();
  curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
  curl_setopt($curl, CURLOPT_HEADER, TRUE);
  curl_setopt($curl, CURLOPT_FOLLOWLOCATION, TRUE);
  curl_setopt($curl, CURLOPT_URL, $url);
  curl_setopt($curl, CURLOPT_REFERER, $url);
  curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
  curl_setopt($curl, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 6.1; en-US) AppleWebKit/533.4 (KHTML, like Gecko) Chrome/5.0.375.125 Safari/533.4");
  $html = curl_exec($curl);
  curl_close($curl);

  /*$headers = get_headers($url,1);
  @$temp = $headers['Last-Modified'];
  if(empty($temp))
    $temp = date("Y-m-d H:i:s");

  $lastMod = $temp;*/

  $dom = new DOMDocument();
  @$dom->loadHtml($html);

  return $dom;
}

function getInfo($link) {
	$html = getReq($link);

	$title = $html->getElementsByTagName("title");
	@$title = $title->item(0)->nodeValue;

	if(empty($title) || (strpos($title, "Error") !== false)) {
		return 'empty';
	}

	$description = "";
	$metas = $html->getElementsByTagName("meta");
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
		"url"=>$link);

	return $info;
}

function getLinks($url) {
	global $seen;
	global $conn;
  //global $lastMod;
	$temp = array();

	$html = getReq($url);
	$links = $html->getElementsByTagName("a");
	foreach ($links as $link) {
		$link = $link->getAttribute("href");
		$link = sanitizeUrl($link, $url);
		if(!in_array($link, $seen)) {
			$temp[] = $link;
			$info = getInfo($link);
			if($info == 'empty') {
				array_pop($temp);
				continue;
			}

			$title = (string)$info['title'];
			$description = (string)$info['description'];
			$u = (string)$info['url'];

      $query = "SELECT * FROM `pagetable` WHERE `url` = '{$u}'";
      $result = mysqli_query($conn, $query);
      $sql;
      if (mysqli_num_rows($result) > 0) {
        $stmt = "UPDATE `pagetable` SET title=?, description=?, url=? WHERE url=?";
        $sql = $conn->prepare($stmt);
        $sql->bind_param("ssss", $title, $description, $u, $u);
        echo $u . " Update " . "<br>";
        $sql->execute();
      } else {
        $sql = $conn->prepare("INSERT INTO `pagetable`(`title`, `description`, `url`) VALUES (?,?,?)");
        $sql->bind_param("sss", $title, $description, $u);
        $sql->execute();
      }
		}
	}
	return $temp;
}

?>
