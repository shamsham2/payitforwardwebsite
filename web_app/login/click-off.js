// Get the loginbox
var loginbox = document.getElementsByClassName('login');
var i;

// Close the loginbox when anyone clicks outside it
window.onclick = function (event) {
  for (i = 0; i < 2; i++) {
    if (event.target == loginbox[i]) {
      loginbox[i].style.display = "none";
    }
  }
}
