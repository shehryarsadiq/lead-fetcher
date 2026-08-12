document.addEventListener("DOMContentLoaded", () => {

    const menu =
        document.querySelector(".menu-toggle");

    const nav =
        document.querySelector(".navbar nav");


    if (menu && nav) {

        menu.addEventListener("click", () => {

            nav.classList.toggle(
                "mobile-open"
            );

        });

    }


    if (window.gsap) {

        gsap.from(".hero-copy > *", {

            y: 30,
            opacity: 0,
            duration: 0.8,
            stagger: 0.08,
            ease: "power3.out"

        });


        gsap.from(".hero-orb", {

            scale: 0.85,
            opacity: 0,
            duration: 1.2,
            delay: 0.2,
            ease: "power3.out"

        });

    }

});