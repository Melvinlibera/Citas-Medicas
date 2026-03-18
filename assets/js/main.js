// Detecta scroll y cambia el header
window.addEventListener("scroll", () => {

    let header = document.getElementById("header");

    if(window.scrollY > 100){
        header.classList.add("scrolled");
    } else {
        header.classList.remove("scrolled");
    }

});