/* ===========================
   Sidebar Navigation
=========================== */

const buttons = document.querySelectorAll(".tab-btn");
const sections = document.querySelectorAll(".profile-section");

buttons.forEach(button => {

    button.addEventListener("click", () => {

        const target = button.dataset.target;

        // Clicking the active button returns to the dashboard
        if (button.classList.contains("active")) {

            button.classList.remove("active");

            sections.forEach(section =>
                section.classList.remove("active")
            );

            document.getElementById("dashboard").classList.add("active");

            return;

        }

        // Remove current active states
        buttons.forEach(btn =>
            btn.classList.remove("active")
        );

        sections.forEach(section =>
            section.classList.remove("active")
        );

        // Activate selected section
        button.classList.add("active");

        const section = document.getElementById(target);

        if (section) {
            section.classList.add("active");
        }

    });

});


//  CUSTOM GLOBAL.JS
const stage = document.getElementById("stage");
const logo = document.getElementById("logoWrap");

let firstFlickerDone = false;

document.addEventListener("pointermove", () => {
    if (firstFlickerDone) return;

    firstFlickerDone = true;

    logo.classList.remove("flicker-active");
    void logo.offsetWidth;
    logo.classList.add("flicker-active");

    randomFlicker();
});

function playFlicker(times = 1) {

    let count = 0;

    function flick() {

        logo.classList.remove("flicker-active");
        void logo.offsetWidth;
        logo.classList.add("flicker-active");

        count++;

        if (count < times) {
            setTimeout(flick, 120 + Math.random() * 80);
        }

    }

    flick();

}

function randomFlicker() {

    const delay = 5000 + Math.random() * 15000;

    setTimeout(() => {

        const r = Math.random();

        if (r < 0.7) {
            playFlicker(1);      // 70%
        } else if (r < 0.9) {
            playFlicker(2);      // 20%
        } else {
            playFlicker(3);      // 10%
        }

        randomFlicker();

    }, delay);

}

const challengeSwiper = new Swiper(".challengeSwiper",{
    effect:"coverflow",
    centeredSlides:true,
    slidesPerView:"auto",
    spaceBetween:30,

    navigation:{
        nextEl:".swiper-button-next",
        prevEl:".swiper-button-prev"
    },

    coverflowEffect:{
        rotate:0,
        stretch:0,
        depth:150,
        modifier:1,
        scale:0.9,
        slideShadows:false
    }
});

// const challengeTitle = document.querySelector(".challengeTitle");

// const challengeDescription = document.querySelector(".challengeDescription");

// const startButton = document.getElementById("startChallengeBtn");

// function updateChallengeInfo(){

//     const slide =
//         challengeSwiper.slides[
//             challengeSwiper.activeIndex
//         ];

//     if(!slide) return;

//     challengeTitle.textContent =
//         slide.dataset.title;

//     challengeDescription.textContent =
//         slide.dataset.description;

//     startButton.href =
//         "challenge.php?id=" + slide.dataset.id;

// }

// challengeSwiper.on("slideChange",updateChallengeInfo);

// updateChallengeInfo();