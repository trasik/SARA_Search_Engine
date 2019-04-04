function myFunction() {
  var x = document.getElementById("myNav");
  if (x.className === "navbar") {
    x.className += " responsive";
  } else {
    x.className = "navbar";
  }
}

$("#src-text").keyup(function(event) {
    if (event.keyCode === 13) {
        $("#submit").click();
    }
});

var check = document.getElementsByClassName('page_check');
$('#select-1').click(function(){
  for(var i = 0; i <check.length; i++) {
    check[i].checked = true;
  }
});

$('#select-2').click(function(){
  for(var i = 0; i <check.length; i++) {
    check[i].checked = false;
  }
});

function createAlert(headTxt, message) {
  var box = document.createElement('div');
  box.setAttribute("class", "alert-box");

  var box_content = document.createElement('div');
  box_content.setAttribute("class", "alert-content");

  box_header = document.createElement('div');
  box_header.setAttribute("class", "alert-header");
  var close = document.createElement('span');
  close.setAttribute("class", "alert-close");
  close.innerHTML="&times;";
  var head = document.createElement('h2');
  head.innerHTML = headTxt;

  box_body = document.createElement('div');
  box_body.setAttribute("class", "alert-body");
  var content = document.createElement('p');
  content.innerHTML = message;

  box.appendChild(box_content);
  box_header.appendChild(close);
  box_header.appendChild(head);
  box_body.appendChild(content);
  box_content.appendChild(box_header);
  box_content.appendChild(box_body);
  var parent = document.body;
  parent.appendChild(box);

  close.onclick = function() {
    box.style.display = "none";
  }

  window.onclick = function(event) {
    if (event.target == box) {
      box.style.display = "none";
    }
  }
}

function insertAfter(el, referenceNode) {
  referenceNode.parentNode.insertBefore(el, referenceNode.nextSibling);
}

var loadSelectBtns = (function() {
  var executed = false;
  return function() {
    if (!executed) {
      executed = true;
      var select = document.createElement('div');
      select.setAttribute("id", "selections");
      var btn1 = document.createElement("button");
      btn1.setAttribute("type", "button");
      btn1.setAttribute("id", "select-1");
      btn1.setAttribute("class", "select-btn");
      btn1.innerHTML="Select All";
      var btn2 = document.createElement("button");
      btn2.setAttribute("type", "button");
      btn2.setAttribute("id", "select-2");
      btn2.setAttribute("class", "select-btn");
      btn2.innerHTML="Deselect All";
      var parent = document.getElementById('up-down-btns');
      insertAfter(select, parent);
      select.appendChild(btn1);
      select.appendChild(btn2);

      var check = document.getElementsByClassName('page_check');
      $('#select-1').click(function(){
        for(var i = 0; i <check.length; i++) {
          check[i].checked = true;
        }
      });

      $('#select-2').click(function(){
        for(var i = 0; i <check.length; i++) {
          check[i].checked = false;
        }
      });
    }
  };
})();

var loadPagination = (function() {
  var executed = false;
  return function() {
    if (!executed) {
      executed = true;
      var page = document.createElement('footer');
      page.setAttribute("id", "pagelist");
      var page_btn0 = document.createElement("a");
      page_btn0.setAttribute("href", "#");
      page_btn0.setAttribute("id", "page_btn0");
      page_btn0.setAttribute("onclick", "pagePrev()");
      page_btn0.innerHTML="&laquo;";
      var page_btn1 = document.createElement("a");
      page_btn1.setAttribute("href", "#");
      page_btn1.setAttribute("id", "page_btn1");
      page_btn1.setAttribute("onclick", "showPage(1)");
      page_btn1.innerHTML="1";
      var page_btn2 = document.createElement("a");
      page_btn2.setAttribute("href", "#");
      page_btn2.setAttribute("id", "page_btn2");
      page_btn2.setAttribute("onclick", "showPage(2)");
      page_btn2.innerHTML="2";
      var page_btn3 = document.createElement("a");
      page_btn3.setAttribute("href", "#");
      page_btn3.setAttribute("id", "page_btn3");
      page_btn3.setAttribute("onclick", "showPage(3)");
      page_btn3.innerHTML="3";
      var page_btn4 = document.createElement("a");
      page_btn4.setAttribute("href", "#");
      page_btn4.setAttribute("id", "page_btn4");
      page_btn4.setAttribute("onclick", "pageNext()");
      page_btn4.innerHTML="&raquo;";

      var parent = document.getElementById('results_info');
      insertAfter(page, parent);
      page.appendChild(page_btn0);
      page.appendChild(page_btn1);
      page.appendChild(page_btn2);
      page.appendChild(page_btn3);
      page.appendChild(page_btn4);
    }
  };
})();

function addResult(c, result, title, url, desc) {
  result = "";
  result += "<br>\n<input type='checkbox' class='page_check' id='check_" + c + "'>\n<div class='page_item'>\n<ul>\n<li><h2>" + title + "</h2></li>\n";
  result += "<li><a href='" + url + "'>" + url + "</a></li>\n";
  result += "<li>" + desc + "</li>\n</ul>\n</div>";
  return result;
}

function handleFileSelect() {
  var files = document.getElementById("files").files;
  for (var i = 0, f; f = files[i]; i++) {
    var ext = f.name.split('.').pop();
    if (ext != "json" && ext != "csv" && ext != "xml") {
      createAlert("Error", "The file uploaded is not supported. The Supported File Types are: .json, .csv, .xml");
      return;
    }
    loadSelectBtns();
    if (ext == "json") {
      var reader = new FileReader();
        reader.onload = (function(theFile) {
          return function(e) {
            var data = JSON.parse(e.target.result);
            var pages = data.pages;
            var items = "";
            for(var i = 0; i < pages.length; i++) {
              items += addResult(i, items, pages[i].title, pages[i].url, pages[i].desc);
            }
            items += "<br>";
            document.getElementById("results_info").innerHTML = items;
          };
        })(f);
      reader.readAsText(f);
    } else if (ext == "csv") {
      var reader = new FileReader();
        reader.onload = (function(theFile) {
          return function(e) {
            var data = e.target.result.split("\n");
            var items = "";
            for(var i = 0; i < data.length - 1; i++) {
              var split = data[i].split(/,(?=(?:(?:[^"]*"){2})*[^"]*$)/);
              var title = split[0].replace(/['"]+/g, '');
              var url = split[1].replace(/['"]+/g, '');
              var desc = split[2].replace(/['"]+/g, '');
              items += addResult(i, items, title, url, desc);
            }
            items += "<br>";
            document.getElementById("results_info").innerHTML = items;
          };
        })(f);
      reader.readAsText(f);
    } else if (ext == "xml") {
      var reader = new FileReader();
        reader.onload = (function(theFile) {
          return function(e) {
            var parser = new DOMParser();
            var parsedData = parser.parseFromString(e.target.result, "application/xml");
            var data = parsedData.getElementsByTagName("page");
            var items = "";
            for(var i = 0; i < data.length; i++) {
              var title = data[i].getElementsByTagName("title")[0].innerHTML;
              var url = data[i].getElementsByTagName("url")[0].innerHTML;
              var desc = data[i].getElementsByTagName("desc")[0].innerHTML;
              items += addResult(i, items, title, url, desc);
            }
            items += "<br>";
            document.getElementById("results_info").innerHTML = items;
          };
        })(f);
      reader.readAsText(f);
    }
  }
}

function fileDownload() {
  var x = document.getElementById("options").value;
  if (x == "json") {
    fdownload("json");
  } else if (x == "csv") {
    fdownload("txt");
  } else if (x == "xml") {
    fdownload("xml");
  }
}

function fdownload(type) {
  var check = document.getElementsByClassName('page_check');
  var selected = new Array();
  for(var i = 0; i <check.length; i++) {
    if (check[i].checked == true) {
      selected.push(check[i].id);
    }
  }
  if (selected.length == 0) {
    createAlert("Error", "Please upload a data source in order to check items for download.");
    return;
  }
  if (type == "json") {
    var data = {"pages": []};
    for(var j = 0; j <selected.length; j++) {
      var x = document.getElementById(selected[j]);
      var title = x.nextSibling.nextElementSibling.childNodes[1].children[0].innerText;
      var url = x.nextSibling.nextElementSibling.childNodes[1].children[1].innerText;
      var desc = x.nextSibling.nextElementSibling.childNodes[1].children[2].innerText;
      data.pages.push({"title": title, "url": url, "desc": desc});
    }
    data = JSON.stringify(data);
    var blob = new Blob([data], {type: "application/json;charset=utf-8"});
    saveAs(blob, "data.json");
  } else if (type == "txt") {
    var data = "";
    for(var j = 0; j <selected.length; j++) {
      var x = document.getElementById(selected[j]);
      var title = x.nextSibling.nextElementSibling.childNodes[1].children[0].innerText;
      var url = x.nextSibling.nextElementSibling.childNodes[1].children[1].innerText;
      var desc = x.nextSibling.nextElementSibling.childNodes[1].children[2].innerText;
      data += '"' + title + '","'+ url + '","' + desc + '"' + '\n';
    }
    var blob = new Blob([data], {type: "text/csv;charset=utf-8"});
    saveAs(blob, "data.csv");
  } else if (type == "xml") {
    var data = "<?xml version='1.0' encoding='UTF-8'?><root>";
    for(var j = 0; j <selected.length; j++) {
      var x = document.getElementById(selected[j]);
      var title = x.nextSibling.nextElementSibling.childNodes[1].children[0].innerText;
      var url = x.nextSibling.nextElementSibling.childNodes[1].children[1].innerText;
      var desc = x.nextSibling.nextElementSibling.childNodes[1].children[2].innerText;
      data += "<page>\n<desc>" + desc + "</desc>\n<title>" + title + "</title>\n<url>" + url + "</url>\n</page>\n"
    }
    data += "</root>";
    var blob = new Blob([data], {type: "text/plain;charset=utf-8"});
    saveAs(blob, "data.xml");
  }
}

function clearPageList() {
  var count = 1;
  for(var i = 0; i <= 4; i++) {
    var x = document.getElementById('page_btn' + i);
    if (x.hasAttribute("class")) {
      count++;
    }
  }
  if(count > 1) {
    for(var i = 0; i <= 4; i++) {
      var x = document.getElementById('page_btn' + i);
      if (x.hasAttribute("class")) {
        x.removeAttribute("class");
      }
    }
  }
}

function showPage(id) {
  var apiKey = 'AIzaSyDNZUOIVXdHVDKtNjJxEaLRi883a7z7Iec';
  var cx = '017797819528819750857:jmzwxfbfxqe';
  var query = document.getElementById('src-text').value;
  var x = (id * 10) - 9;
  var items = "";
    $.get('https://www.googleapis.com/customsearch/v1?key=' + apiKey + '&cx=' + cx + '&q=' + query + '&start=' + x, function(data){
    for(var i = 0; i < data.items.length; i++) {
      items += addResult(i, items, data.items[i].title, data.items[i].formattedUrl, data.items[i].snippet);
    }
    items += "<br>";
    document.getElementById("results_info").innerHTML = items;
  });
  loadSelectBtns();
  loadPagination();
  clearPageList();
  var pageID = document.getElementById('page_btn' + id);
  pageID.setAttribute("class", "page_active");
}

function pagePrev() {
  for(var i = 0; i <= 4; i++) {
    var x = document.getElementById('page_btn' + i);
    if (x.hasAttribute("class")) {
      var id = x.innerHTML;
      if(id == 1) {
        createAlert("Error", "You are on the first page of results.");
        return;
      }
      showPage(id-1);
    }
  }
}

function pageNext() {
  for(var i = 0; i <= 4; i++) {
    var x = document.getElementById('page_btn' + i);
    if (x.hasAttribute("class")) {
      var id = x.innerHTML;
      if(id == 3) {
        createAlert("Error", "You are on the last page of results.");
        return;
      }
      showPage(id+1);
    }
  }
}
