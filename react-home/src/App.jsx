import { useEffect, useState } from "react";

import PixelBlast from "./component/PixelBlast";
import PixelSnow from "./component/PixelSnow";
import GradientWaves from "./component/GradientWaves";


function App() {

    /* =========================
       TYPING EFFECT
    ========================= */

    const text = "Welcome to CGC";

    const [displayText, setDisplayText] = useState("");



    /* =========================
       PROJECTS
    ========================= */

    const projects = window.topProjects || [];



    /* =========================
       CORE MEMBERS
    ========================= */

    const [coreMembers, setCoreMembers] = useState([]);


    useEffect(() => {

        /*
         * TEMPORARY TEST DATA
         *
         * Replace this later with
         * the Google Sheets data.
         */

        setCoreMembers([

            {
                name: "John Doe",
                role: "President",
                picture: "/CGC/images/member.jpg",
                description:
                    "Leads the Coding & Graphics Club."
            },

            {
                name: "Jane Doe",
                role: "Vice President",
                picture: "/CGC/images/member.jpg",
                description:
                    "Works with the team on creative projects."
            },

            {
                name: "Alex Smith",
                role: "Graphics Lead",
                picture: "/CGC/images/member.jpg",
                description:
                    "Creates graphics and visual content."
            },

            {
                name: "Member Four",
                role: "Coding Lead",
                picture: "/CGC/images/member.jpg",
                description:
                    "Develops projects and manages coding activities."
            }

        ]);

    }, []);



    /* =========================
       TYPING ANIMATION
    ========================= */

    useEffect(() => {

        let index = 0;

        const typing = setInterval(() => {

            setDisplayText(
                text.slice(0, index + 1)
            );

            index++;

            if (index === text.length) {

                clearInterval(typing);

            }

        }, 100);


        return () => clearInterval(typing);

    }, []);



    return (

        <>

            {/* ==================================================
                HERO
                PIXEL BLAST
            ================================================== */}

            <section className="home-hero">

                <div className="pixel-blast-background">

                    <PixelBlast
                        variant="square"
                        pixelSize={6}
                        color="#12c0c9"
                        patternScale={3}
                        patternDensity={0.8}
                    />

                </div>


                <div className="home-hero-content">

                    <h1>

                        {displayText}

                        <span className="typing-cursor">
                            |
                        </span>

                    </h1>


                    <div className="btn-box">

                        <a href="#intro">
                            Get Started
                        </a>

                    </div>

                </div>

            </section>



            {/* ==================================================
                PIXEL SNOW AREA
                EVERYTHING BETWEEN HERO AND FOOTER
            ================================================== */}

            <div className="pixel-snow-page">


                {/* ==============================================
                    ONE CONTINUOUS PIXEL SNOW BACKGROUND
                ============================================== */}

                <div className="pixel-snow-background">

                    <PixelSnow
                        color="#ffffff"
                        flakeSize={0.01}
                        minFlakeSize={0.85}
                        pixelResolution={400}
                        speed={1.0}
                        depthFade={8}
                        farPlane={20}
                        brightness={1}
                        gamma={0.4545}
                        density={0.3}
                        variant="snowflake"
                        direction={125}
                    />

                </div>

                {/* ==================================================
                    INTRO
                ================================================== */}

                <section
                    id="intro"
                    className="home-about"
                >


                    <div className="home-about-content">


                        {/* ==========================================
                            LEFT — INTRO TEXT
                        ========================================== */}

                        <div className="about-text">

                            <p>

                                Welcome to CGC, home to Glenrich’s
                                digital artists and code wizards.
                                Here, we don’t judge you by your
                                mistakes—we judge you by how many
                                tabs you have open at once.

                            </p>


                            <div className="btn-box about-buttons">


                                {/* INSTAGRAM */}

                                <a
                                    href="https://instagram.com/glenrich.cgc"
                                    className="about-icon-btn"
                                    aria-label="Instagram"
                                >

                                    <i className="bx bxl-instagram"></i>

                                </a>


                                {/* EMAIL */}

                                <a
                                    href="mailto:coding.graphics.2425@gmail.com"
                                    className="about-icon-btn"
                                    aria-label="Email"
                                >

                                    <i className="bx bx-envelope"></i>

                                </a>


                                {/* LEARN MORE */}

                                <a
                                    href="#project"
                                    className="learn-more-btn"
                                >

                                    Learn More

                                </a>

                            </div>

                        </div>



                        {/* ==========================================
                            RIGHT — CORE MEMBERS
                        ========================================== */}

                        <div className="core-members-card">


                            <div className="core-members-header">

                                <div>

                                    <span>
                                        MEET THE TEAM
                                    </span>

                                    <h2>
                                        Active Core Members
                                    </h2>

                                </div>


                                <i className="bx bx-group"></i>

                            </div>



                            {/* SCROLLABLE MEMBERS */}

                            <div className="core-members-list">


                                {coreMembers.length === 0 ? (

                                    <div className="core-members-loading">

                                        Loading core members...

                                    </div>

                                ) : (

                                    coreMembers.map(
                                        (member, index) => (

                                            <div
                                                className="core-member"
                                                key={index}
                                            >


                                                <div className="core-member-image">

                                                    <img
                                                        src={member.picture}
                                                        alt={member.name}
                                                    />

                                                </div>



                                                <div className="core-member-info">

                                                    <h3>
                                                        {member.name}
                                                    </h3>

                                                    <span>
                                                        {member.role}
                                                    </span>

                                                    <p>
                                                        {member.description}
                                                    </p>

                                                </div>


                                            </div>

                                        )
                                    )

                                )}

                            </div>

                        </div>

                    </div>



                    {/* ==================================================
                        TRAILER BUTTON / CARD
                    ================================================== */}

                    <a
                        href="#trailer"
                        className="trailer-card-btn"
                    >


                        <div className="trailer-card-icon">

                            <i className="bx bx-play"></i>

                        </div>



                        <div className="trailer-card-text">

                            <span>
                                WATCH NOW
                            </span>

                            <h3>
                                CGC Trailer
                            </h3>

                            <p>
                                See what CGC is all about.
                            </p>

                        </div>



                        <i className="bx bx-right-arrow-alt trailer-card-arrow"></i>


                    </a>


                </section>



                {/* ==================================================
                    PROJECTS
                ================================================== */}

                <section
                    className="projects-hero"
                    id="project"
                >


                    <div className="projects-content">


                        <h2 className="projects-heading">
                            Featured Projects
                        </h2>



                        <div className="projects-carousel">


                            {projects.length === 0 ? (

                                <div className="no-projects">

                                    <p>
                                        No Top Projects Available
                                    </p>

                                </div>

                            ) : (

                                <>


                                    <div className="projectsSwiper swiper">


                                        <div className="swiper-wrapper">


                                            {projects.map(
                                                (project) => (

                                                    <div
                                                        className="swiper-slide"
                                                        key={project.id}
                                                        data-id={project.id}
                                                        data-title={project.title}
                                                        data-text={project.details}
                                                    >


                                                        {project.media_type === "video" ? (

                                                            <video
                                                                autoPlay
                                                                muted
                                                                loop
                                                                playsInline
                                                            >

                                                                <source
                                                                    src={`/CGC/uploads/projects/${project.media}`}
                                                                    type="video/mp4"
                                                                />

                                                            </video>

                                                        ) : (

                                                            <img
                                                                src={`/CGC/uploads/projects/${project.media}`}
                                                                alt={project.title}
                                                            />

                                                        )}


                                                    </div>

                                                )
                                            )}


                                        </div>



                                        <div className="swiper-button-prev"></div>

                                        <div className="swiper-button-next"></div>


                                    </div>



                                    <div className="project-info">

                                        <h2 className="projectTitle"></h2>

                                        <p className="projectDescription"></p>

                                    </div>



                                    <div className="btn-box project-see-more">

                                        <a
                                            className="projectSeeMore"
                                            href="#"
                                        >
                                            See More
                                        </a>

                                    </div>


                                </>

                            )}


                        </div>


                    </div>


                </section>

                {/* ==================================================
                    TRAILER
                ================================================== */}
                <section
                    id="trailer"
                    className="home-trailer"
                >

                    <div className="home-trailer-content">

                        <h2 className="projects-heading">
                            Trailer
                        </h2>

                        <div className="trailer-video-wrapper">

                            <video
                                autoPlay
                                muted
                                loop
                                playsInline
                                controls
                            >
                                <source
                                    src="images/intro-vid.mp4"
                                    type="video/mp4"
                                />
                            </video>

                        </div>

                    </div>

                </section>

            </div>



            {/* ==================================================
                FOOTER
                GRADIENT WAVES
            ================================================== */}

            <footer className="home-footer">


                <div className="footer-waves">

                    <GradientWaves
                        horizonColor="#12c0c9"
                        waveColor="#ffffff"
                        crestColor="#12c0c9"
                        speed={0.25}
                        amplitude={4.1}
                        waveScale={0.5}
                        waveRatio={0.55}
                        swell={30}
                        turbulence={35}
                        tilt={1}
                        zoom={1}
                        height={5.5}
                        fogDepth={15}
                        detail="medium"
                        brightness={1}
                        opacity={1}
                        mouseInteraction
                        parallaxStrength={0.5}
                        grain={false}
                        grainIntensity={0}
                    />

                </div>



                <div className="home-footer-content">

                    <h2>
                        Coding & Graphics Club
                    </h2>

                    <p className="footer-tagline">
                        Create. Code. Design.
                    </p>


                    <div className="github-embed">

                        <div className="github-embed-header">

                            <i className="bx bxl-github"></i>

                            <div>

                                <h3>
                                    CGC on GitHub
                                </h3>

                                <p>
                                    Explore our projects and code.
                                </p>

                            </div>

                        </div>


                        <a
                            href="https://github.com/"
                            target="_blank"
                            rel="noopener noreferrer"
                            className="github-embed-button"
                        >

                            <i className="bx bxl-github"></i>

                            Visit GitHub

                        </a>

                    </div>


                    <p className="footer-copyright">

                        © 2026 Coding & Graphics Club.
                        All rights reserved.

                    </p>


                </div>


            </footer>

        </>

    );

}


export default App;