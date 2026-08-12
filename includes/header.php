<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <meta name="description" content="LeadFetcher - Discover businesses and find your next leads.">

    <title>
        <?= e($page_title ?? "LeadFetcher") ?>
    </title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <link
        href="https://fonts.googleapis.com/css2?family=DM+Mono:wght@400;500&family=Manrope:wght@400;500;600;700;800&display=swap"
        rel="stylesheet">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">

    <link rel="stylesheet" href="assets/css/style.css">
</head>

<body>

    <div class="noise"></div>

    <header class="navbar">

        <div class="container nav-inner">

            <a href="index.php" class="logo">

                <span class="logo-mark">L</span>

                <span>
                    Lead<span>Fetcher</span>
                </span>

            </a>

            <nav>

                <a href="index.php">Home</a>

                <a href="features.php">Features</a>

                <a href="about.php">About</a>

                <a href="contact.php">Contact</a>

            </nav>

            <a href="search.php" class="nav-cta">
                Start Searching
                <i class="fa-solid fa-arrow-right"></i>
            </a>

            <button class="menu-toggle">
                <i class="fa-solid fa-bars"></i>
            </button>

        </div>

    </header>

    <main>