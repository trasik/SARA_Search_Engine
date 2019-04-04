//The Start of the Browser Page
var w = window.innerWidth
|| document.documentElement.clientWidth
|| document.body.clientWidth;

var h = window.innerHeight
|| document.documentElement.clientHeight
|| document.body.clientHeight;

var windowscr = document.getElementById("winfo");
windowscr.innerHTML = "<strong>Browser inner window width:</strong> " + w + "<strong>, height:</strong> " + h + ".";

var scr = document.getElementById("sinfo");
scr.innerHTML = "<strong>Screen Width:</strong> " + screen.width + "<br />"
                + "<strong>Screen Height:</strong> " + screen.height + "<br />"
                + "<strong>Screen Height:</strong> " + screen.height + "<br />"
                + "<strong>Available Screen Width:</strong> " + screen.availWidth + "<br />"
                + "<strong>Available Screen Height:</strong> " + screen.availHeight  + "<br />"
                + "<strong>Screen Color Depth:</strong> " + screen.colorDepth + "<br />"
                + "<strong>Screen Pixel Depth:</strong> " + screen.pixelDepth +"<br />";

var navi = document.getElementById("ninfo");
navi.innerHTML = "<strong>Are cookies enabled?</strong> " + navigator.cookieEnabled + "<br />"
                    + "<strong>Is Java enabled?</strong> " + navigator.javaEnabled() + "<br />"
                    + "<strong>Navigator Name:</strong> " + navigator.appName + "<br />"
                    + "<strong>Navigator Codename:</strong> " + navigator.appCodeName + "<br />"
                    + "<strong>Navigator Engine:</strong> " + navigator.product + "<br />"
                    + "<strong>Navigator Platform:</strong> " + navigator.platform + "<br />"
                    + "<strong>Navigator Engine:</strong> " + navigator.language + "<br />";

var loc = document.getElementById("linfo");
loc.innerHTML = "<strong>Page Location:</strong> " + window.location.href + "<br />"
                    + "<strong>Page Hostname:</strong> " + window.location.hostname + "<br />"
                    + "<strong>Page Path:</strong> " + window.location.pathname + "<br />"
                    + "<strong>Page Protocol</strong> " + window.location.protocol + "<br />"
                    + "<strong>Port Number:</strong> " + window.location.port + "<br />";

var x = document.getElementById("geoinfo");
function getLocation() {
  if (navigator.geolocation) {
    navigator.geolocation.getCurrentPosition(showPosition);
  } else {
    x.innerHTML = "Geolocation is not supported by this browser.";
  }
}

function showPosition(position) {
  x.innerHTML = "<strong>Latitude:</strong> " + position.coords.latitude +
                "<br><strong>Longitude:</strong> " + position.coords.longitude;
}
//The End of the Browser Page
