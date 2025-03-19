let slideIndex = 0;
let slideTimer;

function showSlides() {
    const slides = document.querySelectorAll(".slide");
    const dots = document.querySelectorAll(".dot");

    slides.forEach(slide => slide.style.display = "none");
    slideIndex++;
    if (slideIndex > slides.length) { slideIndex = 1; }

    slides[slideIndex - 1].style.display = "block";
    dots.forEach(dot => dot.classList.remove("active"));
    dots[slideIndex - 1].classList.add("active");

    slideTimer = setTimeout(showSlides, 5000); // Change slide every 5 seconds
}

function plusSlides(n) {
    clearTimeout(slideTimer); // Stop automatic slideshow
    const slides = document.querySelectorAll(".slide");
    slideIndex += n;

    if (slideIndex > slides.length) {
        slideIndex = 1;
    } else if (slideIndex < 1) {
        slideIndex = slides.length;
    }

    showSlideByIndex(slideIndex - 1);
    restartSlideshow();
}

function currentSlide(n) {
    clearTimeout(slideTimer); // Stop automatic slideshow
    slideIndex = n;
    showSlideByIndex(slideIndex - 1);
    restartSlideshow();
}

function showSlideByIndex(index) {
    const slides = document.querySelectorAll(".slide");
    const dots = document.querySelectorAll(".dot");

    slides.forEach(slide => slide.style.display = "none");
    slides[index].style.display = "block";

    dots.forEach(dot => dot.classList.remove("active"));
    dots[index].classList.add("active");
}

function restartSlideshow() {
    slideTimer = setTimeout(showSlides, 5000); // Restart automatic slideshow
}

document.addEventListener("DOMContentLoaded", showSlides);
