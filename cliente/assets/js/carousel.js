// HERO CAROUSEL
document.addEventListener("DOMContentLoaded", () => {
    const heroCarousel = document.querySelector("#heroCarousel");

    if (heroCarousel) {
    new bootstrap.Carousel(heroCarousel, {
        interval: 5000,
        pause: "hover",
        wrap: true,
        touch: true,
    });
    }
});
