const MEMBERS_CSV = "https://docs.google.com/spreadsheets/d/e/2PACX-1vTDcMOeylV6xsMNmlNNi9kMPGJT9PGI7bMZu2JQ-CtEUYIeY8ppTXsSnzT0c1jaJF4HTQE_PTXy8DUD/pub?gid=0&single=true&output=csv";
const PRESIDENTS_CSV = "https://docs.google.com/spreadsheets/d/e/2PACX-1vTDcMOeylV6xsMNmlNNi9kMPGJT9PGI7bMZu2JQ-CtEUYIeY8ppTXsSnzT0c1jaJF4HTQE_PTXy8DUD/pub?gid=1473652642&single=true&output=csv"

Papa.parse(MEMBERS_CSV, {
    download: true,
    header: true,
    skipEmptyLines: true,

    complete: function(result) {

        const grid = document.getElementById("membersGrid");

        result.data.forEach((member, index) => {

            const card = document.createElement("div");
            card.className = "member-card";

            card.innerHTML = `
                <img
                    src="${member["Image"]}"
                    alt="${member.Name}"
                    loading="normal"
                    decoding="async"
                >

                <div class="member-info">
                    <h2>${member.Name}</h2>

                    <div class="member-rank">
                        ${member.Rank.toUpperCase()}
                    </div>

                    <p>${member.Description}</p>
                </div>
            `;

            grid.appendChild(card);

            requestAnimationFrame(() => {
                setTimeout(() => {
                    card.classList.add("show");
                }, index * 80);
            });

        });

    }
});

Papa.parse(PRESIDENTS_CSV, {

    download: true,
    header: true,
    skipEmptyLines: true,

    complete: function(result){

        const timeline = document.getElementById("timeline");

        const img = document.getElementById("presidentImage");
        const name = document.getElementById("presidentName");
        const year = document.getElementById("presidentYear");
        const desc = document.getElementById("presidentDescription");


        function showPresident(data){

            const details = document.querySelector(".president-details");

            // Fade out current information
            details.classList.add("fade");


            setTimeout(()=>{

                // Change content after fade out
                img.src = data.Image;
                img.alt = data.Name;

                name.textContent = data.Name;
                year.textContent = data.Year;
                desc.textContent = data.Description;


                // Fade back in
                details.classList.remove("fade");


            },400);

        }


        result.data.forEach((president,index)=>{


            const point = document.createElement("div");

            point.className = "timeline-point";


            point.innerHTML = `
                <div class="circle"></div>
                <span>${president.Year}</span>
            `;


            point.addEventListener("click",()=>{


                document
                .querySelectorAll(".timeline-point")
                .forEach(p=>{
                    p.classList.remove("active");
                });


                point.classList.add("active");


                showPresident(president);


            });


            timeline.appendChild(point);

            timeline.style.display = "flex";
            timeline.style.justifyContent = "space-between";



            // First president automatically selected
            if(index === result.data.length - 1){

                point.classList.add("active");

                showPresident(president);

            }


        });

    }

});