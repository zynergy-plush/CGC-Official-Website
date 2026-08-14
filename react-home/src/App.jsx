import { useEffect, useState } from "react";
import PixelBlast from "./component/PixelBlast";
import PixelSnow from "./component/PixelSnow";

function App() {
    const text = "Welcome to CGC";
    const [displayText, setDisplayText] = useState("");
    const projects = window.topProjects || [];

    useEffect(() => {
        let index = 0;

        const typing = setInterval(() => {
            setDisplayText(text.slice(0, index + 1));
            index++;

            if (index === text.length) {
                clearInterval(typing);
            }
        }, 100);

        return () => clearInterval(typing);
    }, []);

    return (
        <>
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
                        <span className="typing-cursor">|</span>
                    </h1>

                    <div className="btn-box">
                        <a href="#intro">Get Started</a>
                    </div>
                </div>

            </section>

            

            <section id="intro" className="home-about">

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

                <div className="home-about-content">

                    <p>
                        Welcome to CGC, home to Glenrich’s digital artists and
                        code wizards. Here, we don’t judge you by your
                        mistakes—we judge you by how many tabs you have open
                        at once.
                    </p>

                    <div className="btn-box about-buttons">

                        {/* Instagram */}
                        <a
                            href="https://instagram.com/glenrich.cgc"
                            className="about-icon-btn"
                            aria-label="Instagram"
                        >
                            <i className="bx bxl-instagram"></i>
                        </a>

                        {/* Email */}
                        <a
                            href="mailto:coding.graphics.2425@gmail.com"
                            className="about-icon-btn"
                            aria-label="Email"
                        >
                            <i className="bx bx-envelope"></i>
                        </a>

                        {/* Trailer */}
                        <a
                            href="#trailer"
                            className="about-icon-btn"
                            aria-label="Trailer"
                        >
                            <i className="bx bx-play-circle"></i>
                        </a>

                        {/* Learn More */}
                        <a
                            href="#project"
                            className="learn-more-btn"
                        >
                            Learn More
                        </a>

                    </div>

                </div>

            </section>

            <section className="projects-hero" id="project">

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

                <div className="projects-content">

                    <h2 className="projects-heading">
                        Featured Projects
                    </h2>

                    <div className="projects-carousel">

                        {projects.length === 0 ? (

                            <div className="no-projects">
                                <p>No Top Projects Available</p>
                            </div>

                        ) : (

                            <>
                                <div className="projectsSwiper swiper">

                                    <div className="swiper-wrapper">

                                        {projects.map((project) => (
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
                                        ))}

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
            <section id="trailer" className="home-trailer">

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

                <div className="home-trailer-content">

                    <h2 className="projects-heading">CGC Trailer</h2>

                    <div className="trailer-video">

                        <video
                            controls
                            playsInline
                            autoPlay
                            muted
                            loop
                        >
                            <source
                                src="images/intro-vid.mp4"
                                type="video/mp4"
                                autoplay loop
                            />
                            Your browser does not support the video tag.
                        </video>

                    </div>

                </div>

            </section>
        </>
    );
}
    

export default App;