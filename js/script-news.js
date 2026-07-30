const search = document.getElementById("newsSearch");
const cards = document.querySelectorAll(".news-card");

search.addEventListener("input", () => {

    const value = search.value.toLowerCase().trim();

    cards.forEach(card => {

        const title = card.dataset.title || "";
        const summary = card.dataset.summary || "";

        if (title.includes(value) || summary.includes(value)) {
            card.style.display = "";
        } else {
            card.style.display = "none";
        }

    });

});