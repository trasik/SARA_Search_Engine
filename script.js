function myFunction() {
  var x = document.getElementById("myNav");
  if (x.className === "navbar") {
    x.className += " responsive";
  } else {
    x.className = "navbar";
  }
}

function validateForm() {
  var x = document.forms["sform"]["search-txt"].value;
  if (x != "Pizza" && x != "pizza") {
    alert("Only search that works right now is for 'Pizza'");
    return false;
  }
}
