/* When the user clicks on the button,
toggle between hiding and showing the dropdown content */
function myFunction(id) {
  var dropdowns = document.getElementsByClassName("dropdown-content");
  var i;
  for (i = 0; i < dropdowns.length; i++) {
  var openDropdown = dropdowns[i];
    openDropdown.classList.remove('show');
  }
  document.getElementById(id).classList.toggle("show");
}

function validateForm() {
  var x = document.forms["sform"]["search-txt"].value;
  if (x != "Pizza" && x != "pizza") {
    alert("Only search that works right now is for 'Pizza'");
    return false;
  }
}

window.onclick = function(event) {
  if (!event.target.matches('.dropbtn')) {
  var dropdowns = document.getElementsByClassName("dropdown-content");
  var i;
  for (i = 0; i < dropdowns.length; i++) {
    var openDropdown = dropdowns[i];
    if (openDropdown.classList.contains('show')) {
      openDropdown.classList.remove('show');
      }
    }
  }
}
