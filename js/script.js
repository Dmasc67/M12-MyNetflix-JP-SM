function toggleModal() {
    var modal = document.getElementById("loginModal");
    modal.style.display = (modal.style.display === "block") ? "none" : "block";
}


window.onclick = function(event) {
    var modal = document.getElementById("loginModal");
    if (event.target == modal) {
        modal.style.display = "none";
    }
}