//The Start of the Browser Page
var w = window.innerWidth
|| document.documentElement.clientWidth
|| document.body.clientWidth;

var h = window.innerHeight
|| document.documentElement.clientHeight
|| document.body.clientHeight;

var windowscr = document.getElementById("winfo");
windowscr.innerHTML = "Browser inner window width: " + w + ", height: " + h + ".";

var scr = document.getElementById("sinfo");
scr.innerHTML = "Screen Width: " + screen.width + "<br />"
                + "Screen Height: " + screen.height + "<br />"
                + "Screen Height: " + screen.height + "<br />"
                + "Available Screen Width: " + screen.availWidth + "<br />"
                + "Available Screen Height: " + screen.availHeight  + "<br />"
                + "Screen Color Depth: " + screen.colorDepth + "<br />"
                + "Screen Pixel Depth: " + screen.pixelDepth +"<br />";

var browser = document.getElementById("brinfo");
browser.innerHTML = "Are cookies enabled? " + navigator.cookieEnabled + "<br />"
                    + "Is Java enabled? " + navigator.javaEnabled() + "<br />"
                    + "Navigator Name: " + navigator.appName + "<br />"
                    + "Navigator Codename: " + navigator.appCodeName + "<br />"
                    + "Navigator Engine: " + navigator.product + "<br />"
                    + "Navigator Platform: " + navigator.platform + "<br />"
                    + "Navigator Engine: " + navigator.language + "<br />";

var loc = document.getElementById("linfo");
loc.innerHTML = "Page Location: " + window.location.href + "<br />"
                    + "Page Hostname: " + window.location.hostname + "<br />"
                    + "Page Path: " + window.location.pathname + "<br />"
                    + "Page Protocol " + window.location.protocol + "<br />"
                    + "Port Number: " + window.location.port + "<br />";

var x = document.getElementById("geoinfo");
function getLocation() {
  if (navigator.geolocation) {
    navigator.geolocation.getCurrentPosition(showPosition);
  } else {
    x.innerHTML = "Geolocation is not supported by this browser.";
  }
}

function showPosition(position) {
  x.innerHTML = "Latitude: " + position.coords.latitude +
                "<br>Longitude: " + position.coords.longitude;
}
//The End of the Browser Page
