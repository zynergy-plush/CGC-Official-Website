const glow = document.querySelector(".cursor-glow");

let mouseX = 0;
let mouseY = 0;
let x = 0;
let y = 0;

let initialized = false;

// First movement
document.addEventListener("pointermove", (e) => {

    mouseX = e.clientX;
    mouseY = e.clientY;

    if (!initialized) {

        x = mouseX;
        y = mouseY;

        glow.style.left = "0";
        glow.style.top = "0";

        glow.classList.add("visible");

        initialized = true;

        requestAnimationFrame(animate);
    }

});

function animate(){

    x += (mouseX - x) * 0.12;
    y += (mouseY - y) * 0.12;

    glow.style.transform =
        `translate(${x}px, ${y}px) translate(-50%, -50%)`;

    requestAnimationFrame(animate);
}

// Hide when browser loses focus
window.addEventListener("blur", () => {
    glow.classList.add("hidden");
});

// Show again
window.addEventListener("focus", () => {
    if (initialized) {
        glow.classList.remove("hidden");
    }
});

// Hide when mouse leaves the browser window
document.addEventListener("mouseout", (e) => {

    if (!e.relatedTarget && !e.toElement) {
        glow.classList.add("hidden");
    }

});

// Show when mouse re-enters
document.addEventListener("mouseover", () => {

    if (initialized) {
        glow.classList.remove("hidden");
    }

});

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