function myFunction() {
  var x = document.getElementById("myTopnav");
  if (x.className === "topnav") {
    x.className += " responsive";
  } else {
    x.className = "topnav";
  }
}

function validateForm() {
  var x = document.forms["sform"]["search-txt"].value;
  if (x != "Pizza" && x != "pizza") {
    alert("Only search that works right now is for 'Pizza'");
    return false;
  }
}
