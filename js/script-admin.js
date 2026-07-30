const buttons = document.querySelectorAll(".tab-btn");
const sections = document.querySelectorAll(".profile-section");

buttons.forEach(button => {

    button.addEventListener("click", () => {

        const target = button.dataset.target;

        // Clicking the active tab returns to the dashboard
        if (button.classList.contains("active")) {

            button.classList.remove("active");

            sections.forEach(section =>
                section.classList.remove("active")
            );

            document.getElementById("dashboard").classList.add("active");

            return;
        }

        buttons.forEach(btn =>
            btn.classList.remove("active")
        );

        sections.forEach(section =>
            section.classList.remove("active")
        );

        button.classList.add("active");
        document.getElementById(target).classList.add("active");

    });

});

document.querySelectorAll(".menu-toggle").forEach(toggle => {
    toggle.addEventListener("click", function (e) {
        e.stopPropagation(); // Prevent parent dropdowns from receiving this click
        this.parentElement.classList.toggle("open");
    });
});

const cards = document.querySelectorAll(".message-card");

const modal = document.querySelector(".message-modal");

const modalSubject = document.getElementById("modalSubject");
const modalName = document.getElementById("modalName");
const modalEmail = document.getElementById("modalEmail");
const modalDate = document.getElementById("modalDate");
const modalMessage = document.getElementById("modalMessage");

cards.forEach(card => {

    card.addEventListener("click", () => {

        currentMessageID = card.dataset.id;

        modal.classList.add("active");

        modalSubject.textContent = card.dataset.subject;
        modalName.textContent = card.dataset.name;
        modalEmail.textContent = card.dataset.email;
        modalDate.textContent = card.dataset.date;
        modalMessage.textContent = card.dataset.message;

        fetch("admin.php", {

            method: "POST",

            headers: {
                "Content-Type": "application/x-www-form-urlencoded"
            },

            body: "mark_read_id=" + currentMessageID

        });

        const dot = card.querySelector(".unread-dot");

        if (dot) {
            dot.remove();
        }

    });

});

document.querySelector(".close-modal").addEventListener("click", () => {

    modal.classList.remove("active");

});

modal.addEventListener("click", e => {

    if (e.target === modal) {
        modal.classList.remove("active");
    }

});

document.addEventListener("keydown", e => {

    if (e.key === "Escape") {
        modal.classList.remove("active");
    }

});

let currentMessageID = null;

const replyBtn = document.getElementById("replyBtn");
const deleteBtn = document.getElementById("deleteBtn");

async function deleteMessage(id) {

    if (!confirm("Delete this message?")) {
        return;
    }

    const response = await fetch("admin.php", {

        method: "POST",

        headers: {
            "Content-Type": "application/x-www-form-urlencoded"
        },

        body: "delete_message_id=" + id

    });

    const result = await response.text();

    if (result.trim() === "success") {

        // Remove the card
        const card = document.querySelector(
            `.message-card[data-id="${id}"]`
        );

        if (card) {

            card.style.opacity = "0";
            card.style.transform = "translateX(20px)";

            setTimeout(() => {

                card.remove();

            }, 250);

        }

        modal.classList.remove("active");

    } else {

        alert("Failed to delete message.");

    }

}

deleteBtn.addEventListener("click", () => {

    deleteMessage(currentMessageID);

});
replyBtn.addEventListener("click", async () => {

    try {

        await navigator.clipboard.writeText(modalEmail.textContent);

        const icon = replyBtn.querySelector("i");
        const text = replyBtn.querySelector("span");

        icon.className = "bx bx-check";
        text.textContent = "Copied!";

        replyBtn.classList.add("copied");

        setTimeout(() => {

            icon.className = "bx bx-copy";
            text.textContent = "Copy Email";

            replyBtn.classList.remove("copied");

        }, 1500);

    } catch (err) {

        alert("Couldn't copy the email address.");

    }

});

function showToast(message, success){

    const toast = document.getElementById("toast");

    toast.textContent = message;

    toast.className = "";

    toast.classList.add(
        success ? "success" : "error",
        "show"
    );

    setTimeout(()=>{
        toast.classList.remove("show");
    },3000);

}

const projectForm = document.getElementById("projectForm");

projectForm.addEventListener("submit", async function(e){

    e.preventDefault();

    const formData = new FormData(projectForm);

    const response = await fetch("admin.php",{

        method:"POST",

        body:formData

    });

    const result = await response.json();

    showToast(result.message, result.success);

    if(result.success){

    projectForm.reset();

    addProjectRow(result.project);

    }



});

const newsForm = document.getElementById("newsForm");

newsForm.addEventListener("submit", async function(e){

    e.preventDefault();

    const formData = new FormData(newsForm);

    const response = await fetch("admin.php",{

        method:"POST",

        body:formData

    });

    const result = await response.json();

    showToast(result.message, result.success);

    if(result.success){

    newsForm.reset();

    addNewsRow(result.news);

    }



});

const challengeForm = document.getElementById("challengeForm");

challengeForm.addEventListener("submit", async function(e){

    e.preventDefault();

    const formData = new FormData(challengeForm);

    const response = await fetch("admin.php",{
        method:"POST",
        body:formData
    });

    const result = await response.json();

    showToast(result.message,result.success);

    if(result.success){

        challengeForm.reset();

        addChallengeRow(result.challenge);

    }

});

function attachProjectActions(){

    document.querySelectorAll(".delete-project-form").forEach(form=>{

        if(form.dataset.bound) return;

        form.dataset.bound = "1";

        form.addEventListener("submit", deleteProject);

    });

    document.querySelectorAll(".hide-project-form").forEach(form=>{

        if(form.dataset.bound) return;

        form.dataset.bound = "1";

        form.addEventListener("submit", hideProject);

    });

    document.querySelectorAll(".show-project-form").forEach(form=>{

        if(form.dataset.bound) return;

        form.dataset.bound = "1";

        form.addEventListener("submit", showProject);

    });

}

function attachNewsActions(){

    document.querySelectorAll(".delete-news-form").forEach(form=>{

        if(form.dataset.bound) return;

        form.dataset.bound = "1";

        form.addEventListener("submit", deleteNews);

    });

    document.querySelectorAll(".hide-news-form").forEach(form=>{

        if(form.dataset.bound) return;

        form.dataset.bound = "1";

        form.addEventListener("submit", hideNews);

    });

    document.querySelectorAll(".show-news-form").forEach(form=>{

        if(form.dataset.bound) return;

        form.dataset.bound = "1";

        form.addEventListener("submit", showNews);

    });

}

function attachChallengeActions(){

    document.querySelectorAll(".delete-challenge-form").forEach(form=>{

        if(form.dataset.bound) return;

        form.dataset.bound="1";

        form.addEventListener("submit",deleteChallenge);

    });

    document.querySelectorAll(".hide-challenge-form").forEach(form=>{

        if(form.dataset.bound) return;

        form.dataset.bound="1";

        form.addEventListener("submit",hideChallenge);

    });

    document.querySelectorAll(".show-challenge-form").forEach(form=>{

        if(form.dataset.bound) return;

        form.dataset.bound="1";

        form.addEventListener("submit",showChallenge);

    });

}

async function deleteProject(e){

    e.preventDefault();

    if(!confirm("Delete this project?")){
        return;
    }

    const form = e.currentTarget;

    const response = await fetch("admin.php",{

        method:"POST",

        body:new FormData(form)

    });

    const result = await response.json();

    showToast(result.message,result.success);

    if(result.success){

        form.closest("tr").remove();

    }

}

async function deleteNews(e){

    e.preventDefault();

    if(!confirm("Delete this news item?")){
        return;
    }

    const form = e.currentTarget;

    const response = await fetch("admin.php",{

        method:"POST",

        body:new FormData(form)

    });

    const result = await response.json();

    showToast(result.message,result.success);

    if(result.success){

        form.closest("tr").remove();

    }

}

async function deleteChallenge(e){

    e.preventDefault();

    if(!confirm("Delete this challenge?")){
        return;
    }

    const form = e.currentTarget;

    const response = await fetch("admin.php",{

        method:"POST",

        body:new FormData(form)

    });

    const result = await response.json();

    showToast(result.message,result.success);

    if(result.success){

        form.closest("tr").remove();

    }

}

async function hideProject(e){

    e.preventDefault();

    const form = e.currentTarget;

    const response = await fetch("admin.php",{

        method:"POST",

        body:new FormData(form)

    });

    const result = await response.json();

    showToast(result.message,result.success);

    if(result.success){

    const row = form.closest("tr");

    // Change status
    row.children[4].textContent = "Hidden";

    // Replace Hide form with Show form
    form.outerHTML = `
        <form method="post" class="inline-form show-project-form">

            <input
                type="hidden"
                name="show_project_id"
                value="${row.dataset.projectId}">

            <button class="btn-small show">
                Show
            </button>

        </form>
    `;

    attachProjectActions();

    }

}

async function hideNews(e){

    e.preventDefault();

    const form = e.currentTarget;

    const response = await fetch("admin.php",{

        method:"POST",

        body:new FormData(form)

    });

    const result = await response.json();

    showToast(result.message,result.success);

    if(result.success){

    const row = form.closest("tr");

    // Change status
    row.children[4].textContent = "Hidden";

    // Replace Hide form with Show form
    form.outerHTML = `
        <form method="post" class="inline-form show-news-form">

            <input
                type="hidden"
                name="show_news_id"
                value="${row.dataset.newsId}">

            <button class="btn-small show">
                Show
            </button>

        </form>
    `;

    attachNewsActions();

    }

}

async function hideChallenge(e){

    e.preventDefault();

    const form = e.currentTarget;

    const response = await fetch("admin.php",{

        method:"POST",

        body:new FormData(form)

    });

    const result = await response.json();

    showToast(result.message,result.success);

    if(result.success){

        const row = form.closest("tr");

        row.children[5].textContent = "Hidden";

        form.outerHTML = `
            <form method="post" class="inline-form show-challenge-form">

                <input
                    type="hidden"
                    name="show_challenge_id"
                    value="${row.dataset.challengeId}">

                <button class="btn-small show">
                    Show
                </button>

            </form>
        `;

        attachChallengeActions();

    }

}

async function showProject(e){

    e.preventDefault();

    const form = e.currentTarget;

    const response = await fetch("admin.php",{

        method:"POST",

        body:new FormData(form)

    });

    const result = await response.json();

    showToast(result.message,result.success);

    if(result.success){

    const row = form.closest("tr");

    row.children[4].textContent = "Visible";

    form.outerHTML = `
        <form method="post" class="inline-form hide-project-form">

            <input
                type="hidden"
                name="hide_project_id"
                value="${row.dataset.projectId}">

            <button class="btn-small hide">
                Hide
            </button>

        </form>
    `;

    attachProjectActions();

    }

}

async function showNews(e){

    e.preventDefault();

    const form = e.currentTarget;

    const response = await fetch("admin.php",{

        method:"POST",

        body:new FormData(form)

    });

    const result = await response.json();

    showToast(result.message, result.success);

    if(result.success){

        const row = form.closest("tr");

        // Change status
        row.children[4].textContent = "Visible";

        // Replace Show form with Hide form
        form.outerHTML = `
            <form method="post" class="inline-form hide-news-form">

                <input
                    type="hidden"
                    name="hide_news_id"
                    value="${row.dataset.newsId}">

                <button class="btn-small hide">
                    Hide
                </button>

            </form>
        `;

        attachNewsActions();

    }

}

async function showChallenge(e){

    e.preventDefault();

    const form = e.currentTarget;

    const response = await fetch("admin.php",{

        method:"POST",

        body:new FormData(form)

    });

    const result = await response.json();

    showToast(result.message,result.success);

    if(result.success){

        const row = form.closest("tr");

        row.children[5].textContent = "Visible";

        form.outerHTML = `
            <form method="post" class="inline-form hide-challenge-form">

                <input
                    type="hidden"
                    name="hide_challenge_id"
                    value="${row.dataset.challengeId}">

                <button class="btn-small hide">
                    Hide
                </button>

            </form>
        `;

        attachChallengeActions();

    }

}

attachProjectActions();
attachNewsActions();
attachChallengeActions();

function addProjectRow(project){

    const tbody = document.getElementById("projectsTableBody");

    const row = document.createElement("tr");

    row.dataset.projectId = project.id;

    // Convert category to a nice display name
    const categoryNames = {
        coding: "Coding",
        designs: "Designs",
        "3d_models": "3D Models",
        video_editing: "Video Editing"
    };

    row.innerHTML = `
        <td>${project.id}</td>

        <td>${project.title}</td>

        <td>${categoryNames[project.category] ?? project.category}</td>

        <td>${project.created_at}</td>

        <td>Visible</td>

        <td class="manage-actions">

            <form method="post" class="inline-form hide-project-form">

                <input
                    type="hidden"
                    name="hide_project_id"
                    value="${project.id}">

                <button class="btn-small hide">
                    Hide
                </button>

            </form>

            <form method="post" class="inline-form delete-project-form">

                <input
                    type="hidden"
                    name="delete_project_id"
                    value="${project.id}">

                <button class="btn-small delete">
                    Delete
                </button>

            </form>

        </td>
    `;

    tbody.prepend(row);

    attachProjectActions();

}

function addNewsRow(news){

    const tbody = document.getElementById("newsTableBody");

    const row = document.createElement("tr");

    row.dataset.newsId = news.id;

    row.innerHTML = `
        <td>${news.id}</td>

        <td>${news.title}</td>

        <td>${news.category}</td>

        <td>${news.created_at}</td>

        <td>Visible</td>

        <td class="news-actions">

            <form method="post" class="inline-form hide-news-form">

                <input
                    type="hidden"
                    name="hide_news_id"
                    value="${news.id}">

                <button class="btn-small hide">
                    Hide
                </button>

            </form>

            <form method="post" class="inline-form delete-news-form">

                <input
                    type="hidden"
                    name="delete_news_id"
                    value="${news.id}">

                <button class="btn-small delete">
                    Delete
                </button>

            </form>

        </td>
    `;

    tbody.prepend(row);

    attachNewsActions();

}

function addChallengeRow(challenge){

    const tbody = document.getElementById("challengesTableBody");

    const row = document.createElement("tr");

    row.dataset.challengeId = challenge.id;

    row.innerHTML = `
        <td>${challenge.id}</td>

        <td>${challenge.title}</td>

        <td>${challenge.difficulty}</td>

        <td>${challenge.tags}</td>

        <td>${challenge.created_at}</td>

        <td>Visible</td>

        <td class="challenge-actions">

            <form method="post" class="inline-form hide-challenge-form">

                <input
                    type="hidden"
                    name="hide_challenge_id"
                    value="${challenge.id}">

                <button class="btn-small hide">
                    Hide
                </button>

            </form>

            <form method="post" class="inline-form delete-challenge-form">

                <input
                    type="hidden"
                    name="delete_challenge_id"
                    value="${challenge.id}">

                <button class="btn-small delete">
                    Delete
                </button>

            </form>

        </td>
    `;

    tbody.prepend(row);

    attachChallengeActions();

}

const projectCategory = document.getElementById("project_category");
const projectMedia = document.getElementById("project_media");

projectCategory.addEventListener("change", () => {

    if(projectCategory.value === "video_editing"){

        projectMedia.accept = "video/*";

    }else{

        projectMedia.accept = "image/*";

    }

    // Clear previously selected file
    projectMedia.value = "";

});

const mediaLabel = document.querySelector('label[for="project_media"]');

projectCategory.addEventListener("change", () => {

    if(projectCategory.value === "video_editing"){

        projectMedia.accept = "video/*";
        mediaLabel.textContent = "Video (Required)";

    }else{

        projectMedia.accept = "image/*";
        mediaLabel.textContent = "Image (Required)";

    }

    projectMedia.value = "";

});