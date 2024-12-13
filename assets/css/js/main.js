document.addEventListener("DOMContentLoaded", () => {
    // Animate Navigation Bar
    const navLinks = document.querySelectorAll(".main-nav a");
    navLinks.forEach((link, index) => {
        link.style.opacity = 0;
        setTimeout(() => {
            link.style.opacity = 1;
            link.style.transition = "opacity 0.5s ease";
        }, index * 200);
    });

    // Animate Promo Banner
    const promoBanner = document.querySelector(".promo-banner h1");
    promoBanner.style.transform = "translateY(-100px)";
    promoBanner.style.opacity = 0;

    setTimeout(() => {
        promoBanner.style.transform = "translateY(0)";
        promoBanner.style.opacity = 1;
        promoBanner.style.transition = "transform 0.5s, opacity 0.5s";
    }, 500);
});
