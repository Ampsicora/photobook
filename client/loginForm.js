document.addEventListener("DOMContentLoaded", () => {
    let btnBack = document.querySelector("#btnBack");
    btnBack.addEventListener("click", () => {
    event.preventDefault();
    window.location.replace("./index.php");
    });
});