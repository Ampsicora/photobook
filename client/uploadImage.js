let input, btnUpload, imgUploading, cardUploading;
document.addEventListener("DOMContentLoaded", () => {
    cardUploading = document.querySelector("#cardUploading");
    imgUploading = document.querySelector("#imgUploading");
    btnUpload = document.querySelector("#btnUpload");
    btnUpload.addEventListener("click", ()=>{
        event.preventDefault();
        input = document.querySelector("#inputUpload");
        input.click();
        input.addEventListener("change", () => {
            cardUploading.style.backgroundImage = `url("${ URL.createObjectURL(input.files[0]) }")`;
        });
    });

})