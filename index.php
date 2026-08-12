<?php

require_once __DIR__ . "/config/db.php";
require_once __DIR__ . "/includes/functions.php";

$page_title = "LeadFetcher — Find Better Leads";

require_once __DIR__ . "/includes/header.php";

?>

<section class="hero">

    <div id="webgl-bg"></div>

    <div class="container hero-inner">

        <div class="hero-copy">

            <div class="eyebrow">
                <span></span>
                BUSINESS DISCOVERY ENGINE
            </div>

            <h1>
                Find businesses.
                <br>
                <em>Find opportunities.</em>
            </h1>

            <p class="hero-text">

                Discover real businesses, websites,
                phone numbers, ratings, photos and
                locations from one powerful lead
                discovery engine.

            </p>

            <form id="searchForm" class="search-box">

                <div class="search-field">

                    <i class="fa-solid fa-magnifying-glass"></i>

                    <input
                        id="keyword"
                        name="keyword"
                        type="text"
                        placeholder="What are you looking for?"
                        required
                    >

                </div>

                <div class="search-field">

                    <i class="fa-solid fa-location-dot"></i>

                    <input
                        id="location"
                        name="location"
                        type="text"
                        placeholder="Location e.g. Karachi, Pakistan"
                        required
                    >

                </div>

                <button type="submit" id="searchBtn">

                    <span>Find Leads</span>

                    <i class="fa-solid fa-arrow-right"></i>

                </button>

            </form>

            <div class="quick">

                <span>Try:</span>

                <button data-keyword="snooker clubs">
                    Snooker clubs
                </button>

                <button data-keyword="restaurants">
                    Restaurants
                </button>

                <button data-keyword="dentists">
                    Dentists
                </button>

                <button data-keyword="gyms">
                    Gyms
                </button>

            </div>

        </div>

        <div class="hero-orb">

            <div class="orb-ring ring-a"></div>
            <div class="orb-ring ring-b"></div>

            <div class="orb-core">

                <div class="core-grid"></div>

                <span>
                    LEAD
                    <br>
                    DATA
                </span>

            </div>

            <div class="float-card card-a">
                <b>4.8</b>
                <small>rating</small>
            </div>

            <div class="float-card card-b">
                <b>+2.4K</b>
                <small>businesses</small>
            </div>

        </div>

    </div>

</section>


<section class="section" id="resultsSection">

    <div class="container">

        <div class="section-head">

            <div>

                <span class="eyebrow">
                    DISCOVERY ENGINE
                </span>

                <h2>
                    Search the real world.
                </h2>

            </div>

            <div id="resultMeta"></div>

        </div>

        <div id="results" class="results-grid">

            <div class="empty-results">

                <i class="fa-solid fa-satellite-dish"></i>

                <h3>
                    Your leads will appear here
                </h3>

                <p>
                    Search for a business category
                    and location to begin.
                </p>

            </div>

        </div>

    </div>

</section>


<section class="section feature-section">

    <div class="container">

        <div class="section-head centered">

            <span class="eyebrow">
                BUILT FOR PROSPECTING
            </span>

            <h2>
                Everything you need to discover leads.
            </h2>

        </div>

        <div class="feature-grid">

            <article class="feature-card">

                <div class="feature-icon">
                    01
                </div>

                <h3>
                    Business Data
                </h3>

                <p>
                    Discover names, addresses,
                    phone numbers, websites,
                    ratings and maps.
                </p>

            </article>


            <article class="feature-card">

                <div class="feature-icon">
                    02
                </div>

                <h3>
                    Fast Discovery
                </h3>

                <p>
                    Search almost any business
                    keyword and target the location
                    you want.
                </p>

            </article>


            <article class="feature-card">

                <div class="feature-icon">
                    03
                </div>

                <h3>
                    Lead Ready
                </h3>

                <p>
                    Turn search results into useful
                    prospecting data.
                </p>

            </article>

        </div>

    </div>

</section>


<section class="cta-section">

    <div class="container cta-box">

        <div>

            <span class="eyebrow">
                START EXPLORING
            </span>

            <h2>
                Your next client could be
                one search away.
            </h2>

        </div>

        <a href="search.php">

            Start Searching

            <i class="fa-solid fa-arrow-right"></i>

        </a>

    </div>

</section>


<?php require_once __DIR__ . "/includes/footer.php"; ?>