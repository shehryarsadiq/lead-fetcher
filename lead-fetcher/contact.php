<?php

require_once __DIR__ . "/config/db.php";
require_once __DIR__ . "/includes/functions.php";

$page_title = "Contact";

require_once __DIR__ . "/includes/header.php";

?>

<section class="page-hero">

    <div class="container">

        <span class="eyebrow">
            CONTACT
        </span>

        <h1>
            Let's build better
            prospecting.
        </h1>

        <p>
            Connect LeadFetcher to your workflow
            and make business discovery simpler.
        </p>

    </div>

</section>


<section class="section">

    <div class="container narrow">

        <div class="contact-card">

            <h2>
                Need help?
            </h2>

            <p>
                For API setup, customization,
                deployment or feature requests,
                connect this page to your preferred
                email service.
            </p>

            <a
                class="primary-link"
                href="mailto:hello@example.com"
            >
                hello@example.com

                <i class="fa-solid fa-arrow-right"></i>
            </a>

        </div>

    </div>

</section>

<?php require_once __DIR__ . "/includes/footer.php"; ?>