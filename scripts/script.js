function myFunction() {
  var x = document.getElementById("myNav");
  if (x.className === "navbar") {
    x.className += " responsive";
  } else {
    x.className = "navbar";
  }
}

function clear() {
  document.getElementById("results_info").innerHTML = "";
}

function loadJSON() {
  clear();
  var xreq = new XMLHttpRequest();
  xreq.overrideMimeType("application/json");
  xreq.onreadystatechange = function() {
    if (this.readyState == 4 && this.status == 200) {
      var data = JSON.parse(this.responseText);
      var pages = data.pages;
      var items = "";
      for(var i = 0; i < pages.length; i++) {
        items += "<br><input type='checkbox' class='page_check' id='check_" + i + "'><div class='page_item'><ul><li><h2>" + pages[i].title + "</h2></li>";
        items += "<li><a href='" + pages[i].url + "'>" + pages[i].url + "</a></li>";
        items += "<li>" + pages[i].desc + "</li></ul></div>";
      }
      items += "<br>";
      document.getElementById("results_info").innerHTML = items;
    }
  };
  xreq.open("GET", "./data/pages.json", true);
  xreq.send();
}

function loadCSV() {
  clear();
  var xreq = new XMLHttpRequest();
  xreq.overrideMimeType("text/plain");
  xreq.onreadystatechange = function() {
    if (this.readyState == 4 && this.status == 200) {
      var data = this.responseText.split("\n");
      var items = "";
      for(var i = 0; i < data.length - 1; i++) {
        var split = data[i].split(/,(?=(?:(?:[^"]*"){2})*[^"]*$)/);
        items += "<br><input type='checkbox' class='page_check' id='check_" + i + "'><div class='page_item'><ul><li><h2>" + split[0] + "</h2></li>";
        items += "<li><a href='" + split[1] + "'>" + split[1] + "</a></li>";
        items += "<li>" + split[2] + "</li></ul>";
      }
      items += "<br>";
      document.getElementById("results_info").innerHTML = items;
    }
  };
  xreq.open("GET", "./data/pages.txt", true);
  xreq.send();
}

function loadXML() {
  clear();
  var xreq = new XMLHttpRequest();
  xreq.onreadystatechange = function() {
    if (this.readyState == 4 && this.status == 200) {
      var data = this.responseXML.getElementsByTagName("page");
      var items = "";
      for(var i = 0; i < data.length; i++) {
        var title = data[i].getElementsByTagName("title")[0].innerHTML;
        var url = data[i].getElementsByTagName("url")[0].innerHTML;
        var desc = data[i].getElementsByTagName("desc")[0].innerHTML;
        items += "<br><input type='checkbox' class='page_check' id='check_" + i + "'><div class='page_item'><ul><li><h2>" + title + "</h2></li>";
        items += "<li><a href='" + url + "'>" + url + "</a></li>";
        items += "<li>" + desc + "</li></ul>";
      }
      items += "<br>";
      document.getElementById("results_info").innerHTML = items;
    }
  };
  xreq.open("GET", "./data/pages.xml", true);
  xreq.send();
}

function downloadJSON() {
  var check = document.getElementsByClassName('page_check');
  var selected = new Array();
  for(var i = 0; i <check.length; i++) {
    if (check[i].checked == true) {
      selected.push(check[i].id);
    }
  }
  if (selected.length == 0) {
    alert("Please check a item to download.");
    return;
  }
  var data = {"pages": []};
  for(var j = 0; j <selected.length; j++) {
    var x = document.getElementById(selected[j]);
    var title = x.nextSibling.childNodes[0].childNodes[0].childNodes[0].childNodes[0].nodeValue;
    var url = x.nextSibling.childNodes[0].childNodes[1].childNodes[0].childNodes[0].nodeValue;
    var desc = x.nextSibling.childNodes[0].childNodes[2].childNodes[0].nodeValue;
    data.pages.push({"title": title, "url": url, "desc": desc});
  }
  var jdata = "data:text/json;charset=utf-8," + encodeURIComponent(JSON.stringify(data));
  var download = document.createElement('a');
  download.setAttribute("href", jdata);
  download.setAttribute("download", "data.json");
  document.body.appendChild(download);
  download.click();
  download.remove();
  /*Code using library
  var jdata = JSON.stringify(data);
  var blob = new Blob([jdata], {
    type: "text/json;charset=utf-8"
  });

  saveAs(blob, "data.json");
  */
}

function downloadCSV() {
  var check = document.getElementsByClassName('page_check');
  var selected = new Array();
  for(var i = 0; i <check.length; i++) {
    if (check[i].checked == true) {
      selected.push(check[i].id);
    }
  }
  if (selected.length == 0) {
    alert("Please check a item to download.");
    return;
  }
  var data = "";
  for(var j = 0; j <selected.length; j++) {
    var x = document.getElementById(selected[j]);
    var title = x.nextSibling.childNodes[0].childNodes[0].childNodes[0].childNodes[0].nodeValue;
    var url = x.nextSibling.childNodes[0].childNodes[1].childNodes[0].childNodes[0].nodeValue;
    var desc = x.nextSibling.childNodes[0].childNodes[2].childNodes[0].nodeValue;
    data += "'" + title + "','"+ url + "','" + desc + "'" + "\n";
  }
  var cdata = "data:text/plain;charset=utf-8," + encodeURIComponent(data);
  var download = document.createElement('a');
  download.setAttribute("href", cdata);
  download.setAttribute("download", "data.txt");
  document.body.appendChild(download);
  download.click();
  download.remove();
  /*Code using library
  var cdata = data;
  var blob = new Blob([cdata], {
    type: "text/plain;charset=utf-8"
  });

  saveAs(blob, "data.txt");
  */
}

function downloadXML() {
  var check = document.getElementsByClassName('page_check');
  var selected = new Array();
  for(var i = 0; i <check.length; i++) {
    if (check[i].checked == true) {
      selected.push(check[i].id);
    }
  }
  if (selected.length == 0) {
    alert("Please check a item to download.");
    return;
  }
  var data = "<?xml version='1.0' encoding='UTF-8'?><root>";
  for(var j = 0; j <selected.length; j++) {
    var x = document.getElementById(selected[j]);
    var title = x.nextSibling.childNodes[0].childNodes[0].childNodes[0].childNodes[0].nodeValue;
    var url = x.nextSibling.childNodes[0].childNodes[1].childNodes[0].childNodes[0].nodeValue;
    var desc = x.nextSibling.childNodes[0].childNodes[2].childNodes[0].nodeValue;
    data += "<page><desc>" + desc + "</desc><title>" + title + "</title><url>" + url + "</url></page>"
  }
  data += "</root>";
  var cdata = "data:text/xml;charset=utf-8," + encodeURIComponent(data);
  var download = document.createElement('a');
  download.setAttribute("href", cdata);
  download.setAttribute("download", "data.xml");
  document.body.appendChild(download);
  download.click();
  download.remove();
  /*Code using library
  var cdata = data;
  var blob = new Blob([cdata], {
    type: "text/xml;charset=utf-8"
  });

  saveAs(blob, "data.xml");
  */
}
