// Get the modal
var modal = document.getElementById('modalVerwidjeren');

// Get the button that opens the modal
var btn = document.getElementById("verwijderen");

// Get the <span> element that closes the modal
var span = document.getElementsByClassName("close")[0];

// annuleer kop
var cancel = document.getElementById("annuleermodal");

// When the user clicks the button, open the modal 
btn.onclick = function() {
    modal.style.display = "block";
}

// When the user clicks on <span> (x), close the modal
span.onclick = function() {
    modal.style.display = "none";
}

cancel.onclick = function() {
    modal.style.display = "none";
}

// When the user clicks anywhere outside of the modal, close it
window.onclick = function(event) {
    if (event.target == modal) {
        modal.style.display = "none";
    }
}

