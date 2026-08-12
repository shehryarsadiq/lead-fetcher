<?php

require_once __DIR__ . "/config/db.php";
require_once __DIR__ . "/includes/functions.php";

$page_title = "Search Leads";

require_once __DIR__ . "/includes/header.php";

?>

<section class="page-hero">

    <div class="container">

        <span class="eyebrow">
            DISCOVERY
        </span>

        <h1>
            Find your next prospects.
        </h1>

        <p>
            Search businesses by keyword and location.
        </p>

    </div>

</section>


<section class="section">

    <div class="container">

        <form id="searchForm" class="search-box standalone">

            <div class="search-field">

                <i class="fa-solid fa-magnifying-glass"></i>

                <input
                    id="keyword"
                    name="keyword"
                    placeholder="Business type"
                    required
                >

            </div>

            <div class="search-field">

                <i class="fa-solid fa-location-dot"></i>

                <input
                    id="location"
                    name="location"
                    placeholder="Location"
                    required
                >

            </div>

            <button type="submit" id="searchBtn">

                <span>
                    Find Leads
                </span>

                <i class="fa-solid fa-arrow-right"></i>

            </button>

        </form>


        <div class="section-head">

            <div>

                <span class="eyebrow">
                    RESULTS
                </span>

                <h2>
                    Search results
                </h2>

            </div>

            <div id="resultMeta"></div>

        </div>


        <div id="results" class="results-grid">

            <div class="empty-results">

                <i class="fa-solid fa-magnifying-glass"></i>

                <h3>
                    Start a search
                </h3>

                <p>
                    Your business leads will appear here.
                </p>

            </div>

        </div>

    </div>

</section>

<?php require_once __DIR__ . "/includes/footer.php"; ?>