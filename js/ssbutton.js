let slideIndex = 0;
let timeOut = 4000;
let autoOn = true;
let slides, dots;

document.addEventListener('DOMContentLoaded', function() {
    slides = document.getElementsByClassName("mySlides");
    dots = document.getElementsByClassName("dot");
    showSlides(slideIndex);
    autoSlides();
});

function autoSlides() {
    if (autoOn) {
        timeOut -= 20;
        if (timeOut < 0) {
            showSlides(slideIndex + 1);
            timeOut = 4000;
        }
    }
    setTimeout(autoSlides, 20);
}

function showSlides(n) {
    for (let i = 0; i < slides.length; i++) {
        slides[i].style.display = "none";
        dots[i].className = dots[i].className.replace(" active", "");
    }

    if (n === undefined) {
        slideIndex++;
    } else {
        slideIndex = n;
    }

    if (slideIndex >= slides.length) {
        slideIndex = 0;
    }
    if (slideIndex < 0) {
        slideIndex = slides.length - 1;
    }

    slides[slideIndex].style.display = "block";
    dots[slideIndex].className += " active";
}

function prevSlide() {
    timeOut = 4000;
    showSlides(slideIndex - 1);
}

function nextSlide() {
    timeOut = 4000;
    showSlides(slideIndex + 1);
}

function currentSlide(n) {
    timeOut = 4000;
    showSlides(n);
}

document.querySelector(".filter-btn").addEventListener("click", (e) => {
    e.preventDefault();
    document.querySelector(".advanced-search").classList.toggle("open");
    document.getElementById("home-service").scrollIntoView();
})

document.querySelector(".form-search-input").addEventListener("click", (e) => {
    e.preventDefault();
    document.getElementById("home-service").scrollIntoView();
})

function closeSearchAdvanced() {
    document.querySelector(".advanced-search").classList.toggle("open");
}

