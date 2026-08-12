document.addEventListener("DOMContentLoaded", () => {
    const form = document.getElementById("searchForm");
    if (!form) return;

    const keyword = document.getElementById("keyword");
    const locationInput = document.getElementById("location");
    const button = document.getElementById("searchBtn");
    const results = document.getElementById("results");
    const resultMeta = document.getElementById("resultMeta");

    const leadStore = new Map();

    let nextPageToken = "";
    let currentKeyword = "";
    let currentLocation = "";
    let isLoadingMore = false;

    const RESULTS_PER_PAGE = 12;

    document.querySelectorAll(".quick button").forEach(btn => {
        btn.addEventListener("click", () => {
            keyword.value = btn.dataset.keyword || "";
            keyword.focus();
        });
    });

    form.addEventListener("submit", async event => {
        event.preventDefault();

        const k = keyword.value.trim();
        const l = locationInput.value.trim();

        if (!k || !l) {
            showMessage(
                "fa-circle-exclamation",
                "Missing information",
                "Please enter both a keyword and location."
            );
            return;
        }

        currentKeyword = k;
        currentLocation = l;
        nextPageToken = "";
        leadStore.clear();

        setLoading(true);

        results.innerHTML = `
            <div class="empty-results">
                <i class="fa-solid fa-satellite-dish fa-spin"></i>
                <h3>Scanning businesses...</h3>
                <p>Searching for ${escapeHTML(k)} in ${escapeHTML(l)}.</p>
            </div>
        `;

        resultMeta.textContent = "Searching...";

        try {
            const data = await fetchResults(k, l);

            renderResults(data.results || [], false);

            nextPageToken = data.next_page_token || "";

            resultMeta.textContent =
                `${Math.min(data.count || 0, RESULTS_PER_PAGE)} results`;

            updateLoadMoreButton();

        } catch (error) {
            console.error("LeadFetcher:", error);

            showMessage(
                "fa-triangle-exclamation",
                "Search unavailable",
                error.message
            );

            resultMeta.textContent = "Search failed";
        } finally {
            setLoading(false);
        }
    });

    async function fetchResults(k, l, pageToken = "") {
        const params = new URLSearchParams({
            keyword: k,
            location: l
        });

        if (pageToken) {
            params.append("page_token", pageToken);
        }

        const response = await fetch(
            `api/search.php?${params.toString()}`,
            {
                method: "GET",
                headers: {
                    "Accept": "application/json"
                }
            }
        );

        const rawText = await response.text();

        let data;

        try {
            data = JSON.parse(rawText);
        } catch {
            console.error("Invalid API response:", rawText);
            throw new Error(
                "Server returned invalid JSON. Check PHP errors."
            );
        }

        if (!response.ok || !data.success) {
            throw new Error(
                data.message || "Search request failed."
            );
        }

        return data;
    }

    function renderResults(data, append = false) {
        if (!data.length && !append) {
            showMessage(
                "fa-magnifying-glass",
                "No businesses found",
                "Try another keyword or location."
            );
            return;
        }

        data.forEach(lead => {
            if (lead.place_id) {
                leadStore.set(lead.place_id, lead);
            }
        });

        const cards = data
            .slice(0, RESULTS_PER_PAGE)
            .map(createLeadCard)
            .join("");

        if (append) {
            results.insertAdjacentHTML("beforeend", cards);
        } else {
            results.innerHTML = cards;
        }

        attachSaveButtons();

        loadPlaceDetails(data);
    }

    function createLeadCard(lead) {
        const image =
            lead.photo_url ||
            "assets/images/placeholder.svg";

        const rating =
            lead.rating !== null &&
                lead.rating !== undefined
                ? `<span>★ ${escapeHTML(lead.rating)}</span>`
                : "";

        const reviews =
            lead.review_count
                ? `<span>(${escapeHTML(lead.review_count)})</span>`
                : "";

        const openStatus =
            lead.opening_hours === true
                ? `<span class="open-status">Open now</span>`
                : lead.opening_hours === false
                    ? `<span class="closed-status">Closed now</span>`
                    : "";

        return `
            <article class="lead-card">

                <img
                    class="lead-photo"
                    src="${escapeHTML(image)}"
                    alt="${escapeHTML(lead.name)}"
                    onerror="this.src='assets/images/placeholder.svg'"
                >

                <div class="lead-body">

                    <h3>
                        ${escapeHTML(lead.name)}
                    </h3>

                    <div class="lead-category">
                        ${escapeHTML(
            formatCategory(lead.category)
        )}

                        ${rating}
                        ${reviews}
                        ${openStatus}
                    </div>

                    <div class="lead-row">
                        <i class="fa-solid fa-location-dot"></i>
                        <span>
                            ${escapeHTML(
            lead.address ||
            "Address unavailable"
        )}
                        </span>
                    </div>

                    <div
                        class="lead-details"
                        data-place-id="${escapeHTML(lead.place_id)}"
                    >

                        <div class="lead-row">
                            <i class="fa-solid fa-phone"></i>
                            <span class="phone-value">
                                Loading phone...
                            </span>
                        </div>

                        <div class="lead-row">
                            <i class="fa-solid fa-globe"></i>
                            <span class="website-value">
                                Loading website...
                            </span>
                        </div>

                    </div>

                    <div class="lead-actions">

                        ${lead.google_maps_url
                ? `
                                    <a
                                        href="${escapeHTML(lead.google_maps_url)}"
                                        target="_blank"
                                        rel="noopener noreferrer"
                                    >
                                        <i class="fa-solid fa-map-location-dot"></i>
                                        Maps
                                    </a>
                                `
                : ""
            }

                        <button
                            type="button"
                            class="save-lead"
                            data-place-id="${escapeHTML(lead.place_id)}"
                        >
                            <i class="fa-regular fa-bookmark"></i>
                            Save Lead
                        </button>

                    </div>

                </div>

            </article>
        `;
    }

    function attachSaveButtons() {
        document.querySelectorAll(".save-lead").forEach(btn => {
            if (btn.dataset.bound === "1") return;

            btn.dataset.bound = "1";

            btn.addEventListener("click", () => {
                saveLead(btn);
            });
        });
    }

    async function loadMore() {
        if (!nextPageToken || isLoadingMore) return;

        isLoadingMore = true;

        const loadMoreBtn =
            document.getElementById("loadMoreBtn");

        if (loadMoreBtn) {
            loadMoreBtn.disabled = true;
            loadMoreBtn.innerHTML = `
                <i class="fa-solid fa-spinner fa-spin"></i>
                Loading...
            `;
        }

        try {
            const data = await fetchResults(
                currentKeyword,
                currentLocation,
                nextPageToken
            );

            renderResults(data.results || [], true);

            nextPageToken =
                data.next_page_token || "";

            updateLoadMoreButton();

            const currentCount =
                document.querySelectorAll(".lead-card").length;

            resultMeta.textContent =
                `${currentCount} results`;

        } catch (error) {
            console.error("Load more error:", error);

            alert(
                error.message ||
                "Could not load more leads."
            );

            updateLoadMoreButton();

        } finally {
            isLoadingMore = false;
        }
    }

    function updateLoadMoreButton() {
        let wrapper =
            document.getElementById("loadMoreWrapper");

        if (!nextPageToken) {
            if (wrapper) {
                wrapper.remove();
            }

            return;
        }

        if (!wrapper) {
            wrapper = document.createElement("div");
            wrapper.id = "loadMoreWrapper";
            wrapper.className = "load-more-wrapper";

            results.parentNode.insertBefore(
                wrapper,
                results.nextSibling
            );
        }
        wrapper.style.width = "100%";
        wrapper.style.display = "flex";
        wrapper.style.justifyContent = "center";
        wrapper.style.alignItems = "center";
        wrapper.style.marginTop = "35px";
        wrapper.style.marginBottom = "60px";
        wrapper.style.padding = "0 20px";
        boxSizing = "border-box";
        wrapper.innerHTML = `
    <button
        type="button"
        id="loadMoreBtn"
        class="load-more-btn"
        style="
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            padding: 14px 30px;
            min-width: 170px;
            border: 1px solid rgba(120, 103, 255, 0.6);
            border-radius: 12px;
            background: linear-gradient(135deg, #7867ff, #5b4be7);
            color: #fff;
            font-family: 'Manrope', sans-serif;
            font-size: 15px;
            font-weight: 700;
            line-height: 1;
            cursor: pointer;
            box-shadow: 0 10px 30px rgba(120, 103, 255, 0.22);
            transition: all 0.25s ease;
            outline: none;
            appearance: none;
            -webkit-appearance: none;
        "
    >
        <span>Load More</span>
        <i class="fa-solid fa-arrow-down"></i>
    </button>
`;

        document
            .getElementById("loadMoreBtn")
            .addEventListener("click", loadMore);
    }

    async function loadPlaceDetails(leads) {
        await Promise.all(
            leads
                .filter(lead => lead.place_id)
                .map(loadSinglePlaceDetails)
        );
    }

    async function loadSinglePlaceDetails(lead) {
        try {
            const response = await fetch(
                `api/place-details.php?place_id=${encodeURIComponent(
                    lead.place_id
                )}`,
                {
                    headers: {
                        "Accept": "application/json"
                    }
                }
            );

            const data = await response.json();

            if (!response.ok || !data.success) {
                return;
            }

            const details = data.result || {};

            const storedLead =
                leadStore.get(lead.place_id);

            if (storedLead) {
                Object.assign(storedLead, {
                    phone: details.phone || "",
                    website: details.website || "",
                    rating:
                        details.rating ??
                        storedLead.rating,
                    review_count:
                        details.review_count ??
                        storedLead.review_count,
                    photo_url:
                        details.photo_url ||
                        storedLead.photo_url,
                    google_maps_url:
                        details.google_maps_url ||
                        storedLead.google_maps_url,
                    opening_hours:
                        details.opening_hours || null
                });
            }

            const card = document.querySelector(
                `.lead-details[data-place-id="${CSS.escape(
                    lead.place_id
                )}"]`
            );

            if (!card) return;

            const phoneEl =
                card.querySelector(".phone-value");

            const websiteEl =
                card.querySelector(".website-value");

            if (phoneEl) {
                phoneEl.innerHTML = details.phone
                    ? `
                        <a href="tel:${escapeHTML(details.phone)}">
                            ${escapeHTML(details.phone)}
                        </a>
                    `
                    : "No phone available";
            }

            if (websiteEl) {
                websiteEl.innerHTML = details.website
                    ? `
                        <a
                            href="${escapeHTML(details.website)}"
                            target="_blank"
                            rel="noopener noreferrer"
                        >
                            Visit Website
                        </a>
                    `
                    : "No website available";
            }

        } catch (error) {
            console.error(
                "Place details error:",
                error
            );
        }
    }

    async function saveLead(button) {
        const placeId =
            button.dataset.placeId;

        const lead =
            leadStore.get(placeId);

        if (!placeId || !lead) {
            alert("Lead data is missing.");
            return;
        }

        const originalHTML =
            button.innerHTML;

        button.disabled = true;

        button.innerHTML = `
            <i class="fa-solid fa-spinner fa-spin"></i>
            Saving...
        `;

        try {
            const response = await fetch(
                "api/save-lead.php",
                {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "Accept": "application/json"
                    },
                    body: JSON.stringify({
                        lead: lead
                    })
                }
            );

            const data =
                await response.json();

            if (!response.ok || !data.success) {
                throw new Error(
                    data.message ||
                    "Could not save lead."
                );
            }

            button.innerHTML =
                data.already_saved
                    ? `
                        <i class="fa-solid fa-check"></i>
                        Already Saved
                    `
                    : `
                        <i class="fa-solid fa-check"></i>
                        Saved
                    `;

        } catch (error) {
            console.error(error);

            button.disabled = false;
            button.innerHTML =
                originalHTML;

            alert(error.message);
        }
    }

    function setLoading(loading) {
        button.disabled = loading;

        button.innerHTML = loading
            ? `
                <i class="fa-solid fa-spinner fa-spin"></i>
                <span>Searching...</span>
            `
            : `
                <span>Find Leads</span>
                <i class="fa-solid fa-arrow-right"></i>
            `;
    }

    function showMessage(
        icon,
        title,
        message
    ) {
        results.innerHTML = `
            <div class="empty-results">
                <i class="fa-solid ${icon}"></i>
                <h3>${escapeHTML(title)}</h3>
                <p>${escapeHTML(message)}</p>
            </div>
        `;

        const wrapper =
            document.getElementById(
                "loadMoreWrapper"
            );

        if (wrapper) {
            wrapper.remove();
        }
    }

    function formatCategory(category) {
        if (!category) {
            return "Business";
        }

        return category
            .replace(/_/g, " ")
            .replace(
                /\b\w/g,
                char => char.toUpperCase()
            );
    }

    function escapeHTML(value) {
        const div =
            document.createElement("div");

        div.textContent =
            value ?? "";

        return div.innerHTML;
    }
});