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