const carousels = document.querySelectorAll(".projects-carousel");


function getSlidesPerView(count){
    return Math.min(count, 2);
}


carousels.forEach((carousel)=>{


    const swiperElement = carousel.querySelector(".projectsSwiper");


    const slides = swiperElement.querySelectorAll(".swiper-slide");


    const slideCount = slides.length;



    // Hide empty carousels

    if(slideCount === 0){

        carousel.innerHTML = `
            <div class="no-projects">
                <p>No Projects Available Yet</p>
            </div>
        `;

        return;

    }



    const title = carousel.querySelector(".projectTitle");

    const text = carousel.querySelector(".projectDescription");

    let swiper;

    swiper = new Swiper(swiperElement,{
        effect:"coverflow",

        
        observer:false,
        observeParents:false,
        resizeObserver:false,

        centeredSlides:true,

        slidesPerView:getSlidesPerView(slideCount),

        loop:slideCount >= 5,

        speed:700,

        allowTouchMove: false,

        spaceBetween:-30,

        watchOverflow:true,

        observer:true,

        observeParents:true,

        coverflowEffect:{

            rotate:0,

            stretch:40,

            depth:180,

            modifier:1,

            scale:.84,

            slideShadows:false

        },

        navigation:{

            nextEl:carousel.querySelector(".swiper-button-next"),

            prevEl:carousel.querySelector(".swiper-button-prev")

        }


    });



    function updateInfo(){


        const active = swiper.slides[swiper.activeIndex];


        if(!active) return;


        title.style.opacity = 0;
        text.style.opacity = 0;


        setTimeout(()=>{


            title.textContent = active.dataset.title || "";

            text.textContent = active.dataset.text || "";


            title.style.opacity = 1;

            text.style.opacity = 1;


        },180);


    }



    title.style.transition="0.3s";

    text.style.transition="0.3s";



   swiper.on(
        "slideChangeTransitionEnd",
        updateInfo
    );


    updateInfo();

    swiper.update();


});