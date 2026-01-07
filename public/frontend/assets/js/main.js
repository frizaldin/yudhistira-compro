document.addEventListener("DOMContentLoaded", function () {
    // Set placeholder background untuk semua lazy images sejak awal
    // Sembunyikan gambar asli dengan filter opacity(0), tampilkan placeholder abu-abu
    document.querySelectorAll("img[loading='lazy']").forEach((img) => {
        // Set placeholder background dan sembunyikan gambar asli
        img.style.backgroundColor = "#e0e0e0";
        img.style.filter = "opacity(0)";
        img.style.transition = "filter 0.5s ease";
    });
    
    // Mobile Menu Toggle
    $("#mobileMenuToggle").on("click", function () {
        $(".mobile-navigation-menu").toggleClass("active");
        var icon = $(this).find("i");
        if ($(".mobile-navigation-menu").hasClass("active")) {
            icon.removeClass("bi-list").addClass("bi-x");
            $("body").addClass("mobile-menu-open"); // Prevent body scroll when menu is open
        } else {
            icon.removeClass("bi-x").addClass("bi-list");
            $("body").removeClass("mobile-menu-open"); // Restore body scroll
        }
    });

    // Close mobile menu button
    $("#mobileMenuClose").on("click", function () {
        $(".mobile-navigation-menu").removeClass("active");
        $("#mobileMenuToggle i").removeClass("bi-x").addClass("bi-list");
        $("body").removeClass("mobile-menu-open"); // Restore body scroll
    });

    // Close mobile menu when clicking outside (on overlay)
    $(document).on("click", function (e) {
        if (
            $(e.target).hasClass("mobile-navigation-menu") &&
            $(".mobile-navigation-menu").hasClass("active")
        ) {
            $(".mobile-navigation-menu").removeClass("active");
            $("#mobileMenuToggle i").removeClass("bi-x").addClass("bi-list");
            $("body").removeClass("mobile-menu-open"); // Restore body scroll
        }
    });

    // Mobile dropdown toggle
    $(".nav-item-dropdown .nav-link.has-dropdown").on("click", function (e) {
        if ($(window).width() <= 992) {
            e.preventDefault();
            $(this).closest(".nav-item-dropdown").toggleClass("active");
            // Close other dropdowns
            $(".nav-item-dropdown")
                .not($(this).closest(".nav-item-dropdown"))
                .removeClass("active");
        }
    });

    // Language dropdown toggle
    $(".lang-toggle").on("click", function (e) {
        if ($(window).width() <= 992) {
            e.preventDefault();
            e.stopPropagation();
            $(this).closest(".lang-dropdown").toggleClass("active");
            // Close other dropdowns
            $(".lang-dropdown")
                .not($(this).closest(".lang-dropdown"))
                .removeClass("active");
        }
    });

    // Close language dropdown when clicking outside
    $(document).on("click", function (e) {
        if (!$(e.target).closest(".lang-dropdown").length) {
            $(".lang-dropdown").removeClass("active");
        }
    });

    // Close mobile menu when clicking on nav link (non-dropdown)
    $(".mobile-navigation-menu .main-nav .nav-link:not(.has-dropdown)").on(
        "click",
        function () {
            if ($(window).width() <= 992) {
                $(".mobile-navigation-menu").removeClass("active");
                $("#mobileMenuToggle i")
                    .removeClass("bi-x")
                    .addClass("bi-list");
                $("body").removeClass("mobile-menu-open"); // Restore body scroll
            }
        }
    );

    // Hero Slider
    $(".hero-slider").slick({
        dots: true,
        infinite: true,
        speed: 500,
        fade: true,
        cssEase: "linear",
        autoplay: false,
        autoplaySpeed: 2500,
        arrows: false,
        prevArrow:
            '<button type="button" class="slick-prev hero-arrow hero-arrow-left"><i class="bi bi-arrow-left"></i></button>',
        nextArrow:
            '<button type="button" class="slick-next hero-arrow hero-arrow-right"><i class="bi bi-arrow-right"></i></button>',
        appendDots: ".hero",
        responsive: [
            {
                breakpoint: 1024,
                settings: {
                    arrows: false,
                },
            },
        ],
    });

    // School Books Slider
    $(".school-cards-slider").slick({
        dots: false,
        infinite: true,
        speed: 300,
        slidesToShow: 4,
        slidesToScroll: 1,
        autoplay: false,
        autoplaySpeed: 3000,
        arrows: true,
        prevArrow:
            '<button type="button" class="slick-prev school-books-nav school-books-nav-left"><i class="bi bi-arrow-left"></i></button>',
        nextArrow:
            '<button type="button" class="slick-next school-books-nav school-books-nav-right"><i class="bi bi-arrow-right"></i></button>',
        responsive: [
            {
                breakpoint: 1200,
                settings: {
                    slidesToShow: 3,
                    slidesToScroll: 1,
                },
            },
            {
                breakpoint: 992,
                settings: {
                    slidesToShow: 2,
                    slidesToScroll: 1,
                },
            },
            {
                breakpoint: 768,
                settings: {
                    slidesToShow: 1,
                    slidesToScroll: 1,
                    arrows: false,
                },
            },
        ],
    });

    // Books Slider (Explore Books)
    $(".books-slider").slick({
        dots: false,
        infinite: true,
        speed: 300,
        slidesToShow: 6,
        slidesToScroll: 1,
        autoplay: false,
        autoplaySpeed: 2500,
        arrows: true,
        prevArrow:
            '<button type="button" class="slick-prev books-nav books-nav-left"><i class="bi bi-arrow-left"></i></button>',
        nextArrow:
            '<button type="button" class="slick-next books-nav books-nav-right"><i class="bi bi-arrow-right"></i></button>',
        responsive: [
            {
                breakpoint: 1200,
                settings: {
                    slidesToShow: 4,
                    slidesToScroll: 1,
                },
            },
            {
                breakpoint: 992,
                settings: {
                    slidesToShow: 3,
                    slidesToScroll: 1,
                },
            },
            {
                breakpoint: 768,
                settings: {
                    slidesToShow: 2,
                    slidesToScroll: 1,
                    arrows: false,
                },
            },
        ],
    });

    // Book Catalog Slider
    function initBookCatalogSlider() {
        // Only initialize sliders in visible tabs
        $(".book-catalog-slider").each(function () {
            var $slider = $(this);
            var $tabPane = $slider.closest(".tab-pane");

            // Only initialize if tab pane is active/visible
            if (
                $tabPane.length === 0 ||
                $tabPane.hasClass("active") ||
                $tabPane.hasClass("show")
            ) {
                // Check if slider is already initialized
                if ($slider.hasClass("slick-initialized")) {
                    $slider.slick("unslick");
                }
                // Only initialize if slider has items
                if ($slider.find(".book-card").length > 0) {
                    $slider.slick({
                        dots: false,
                        infinite: true,
                        speed: 300,
                        slidesToShow: 5,
                        slidesToScroll: 1,
                        autoplay: false,
                        arrows: true,
                        prevArrow:
                            '<button type="button" class="slick-prev book-catalog-nav book-catalog-nav-left"><i class="bi bi-arrow-left"></i></button>',
                        nextArrow:
                            '<button type="button" class="slick-next book-catalog-nav book-catalog-nav-right"><i class="bi bi-arrow-right"></i></button>',
                        responsive: [
                            {
                                breakpoint: 1200,
                                settings: {
                                    slidesToShow: 4,
                                    slidesToScroll: 1,
                                },
                            },
                            {
                                breakpoint: 992,
                                settings: {
                                    slidesToShow: 3,
                                    slidesToScroll: 1,
                                },
                            },
                            {
                                breakpoint: 768,
                                settings: {
                                    slidesToShow: 2,
                                    slidesToScroll: 1,
                                    arrows: false,
                                },
                            },
                        ],
                    });
                }
            }
        });
    }

    // Initialize book catalog slider on page load (with delay to ensure DOM is ready)
    setTimeout(function () {
        initBookCatalogSlider();
    }, 300);

    // Reinitialize book catalog slider when tab changes
    $(document).on(
        "shown.bs.tab",
        'button[data-bs-toggle="tab"]',
        function (e) {
            setTimeout(function () {
                initBookCatalogSlider();
            }, 100);
        }
    );

    // Featured Articles Slider (New Layout)
    $(".featured-articles-slider").slick({
        dots: true,
        infinite: true,
        speed: 500,
        fade: true,
        cssEase: "linear",
        autoplay: false,
        autoplaySpeed: 3000,
        arrows: true,
        prevArrow:
            '<button type="button" class="slick-prev"><i class="bi bi-chevron-left"></i></button>',
        nextArrow:
            '<button type="button" class="slick-next"><i class="bi bi-chevron-right"></i></button>',
        responsive: [
            {
                breakpoint: 768,
                settings: {
                    arrows: false,
                },
            },
        ],
    });

    // Articles Slider (Old Layout - for backward compatibility)
    $(".articles-slider").slick({
        dots: false,
        infinite: true,
        speed: 300,
        slidesToShow: 4,
        slidesToScroll: 1,
        autoplay: true,
        autoplaySpeed: 3000,
        arrows: true,
        prevArrow:
            '<button type="button" class="slick-prev circle-btn"><i class="bi bi-arrow-left"></i></button>',
        nextArrow:
            '<button type="button" class="slick-next circle-btn"><i class="bi bi-arrow-right"></i></button>',
        responsive: [
            {
                breakpoint: 1200,
                settings: {
                    slidesToShow: 3,
                    slidesToScroll: 1,
                },
            },
            {
                breakpoint: 992,
                settings: {
                    slidesToShow: 2,
                    slidesToScroll: 1,
                },
            },
            {
                breakpoint: 768,
                settings: {
                    slidesToShow: 1,
                    slidesToScroll: 1,
                    arrows: false,
                },
            },
        ],
    });

    // Digital Product Slider
    $(".digital-slider").slick({
        dots: false,
        infinite: true,
        speed: 300,
        slidesToShow: 3,
        slidesToScroll: 1,
        autoplay: true,
        autoplaySpeed: 3000,
        arrows: true,
        prevArrow:
            '<button type="button" class="slick-prev digital-nav digital-nav-left"><i class="bi bi-arrow-left"></i></button>',
        nextArrow:
            '<button type="button" class="slick-next digital-nav digital-nav-right"><i class="bi bi-arrow-right"></i></button>',
        responsive: [
            {
                breakpoint: 1200,
                settings: {
                    slidesToShow: 3,
                    slidesToScroll: 1,
                },
            },
            {
                breakpoint: 992,
                settings: {
                    slidesToShow: 2,
                    slidesToScroll: 1,
                },
            },
            {
                breakpoint: 768,
                settings: {
                    slidesToShow: 1,
                    slidesToScroll: 1,
                    arrows: false,
                },
            },
        ],
    });

    // Equalize digital card heights
    function equalizeDigitalCardHeights() {
        var maxHeight = 0;
        $(".digital-slider .digital-card").each(function () {
            $(this).css("height", "auto");
            var height = $(this).outerHeight();
            if (height > maxHeight) {
                maxHeight = height;
            }
        });
        $(".digital-slider .digital-card").css("height", maxHeight + "px");
    }

    // Call on init and window resize
    $(".digital-slider").on("init", function () {
        setTimeout(equalizeDigitalCardHeights, 100);
    });

    $(".digital-slider").on("setPosition", function () {
        setTimeout(equalizeDigitalCardHeights, 100);
    });

    $(window).on("resize", function () {
        setTimeout(equalizeDigitalCardHeights, 100);
    });

    // Initial call
    setTimeout(equalizeDigitalCardHeights, 300);

    // Helper: compute max slide content height and set .slick-list minHeight
    function computeAndSetSliderMinHeight($el) {
        try {
            // If images inside slider are not yet loaded, wait for them then retry measurement
            var $imgs = $el.find('img');
            var $notLoaded = $imgs.filter(function () {
                return !this.complete;
            });
            if ($notLoaded.length > 0) {
                // When all images finish loading (or error), recompute after a short delay
                var remaining = $notLoaded.length;
                $notLoaded.on('load error', function () {
                    remaining--;
                    if (remaining <= 0) {
                        setTimeout(function () {
                            computeAndSetSliderMinHeight($el);
                        }, 50);
                    }
                });
                // Don't measure now
                return;
            }

            var maxH = 0;
            // Prefer measuring visible slides (active/current, non-cloned)
            var $visibleSlides = $el.find('.slick-slide').not('.slick-cloned').filter(function () {
                var $s = $(this);
                return $s.is('.slick-active') || $s.is('.slick-current') || $s.attr('aria-hidden') === 'false';
            });

            if ($visibleSlides.length > 0) {
                $visibleSlides.each(function () {
                    if (this.offsetParent === null) return;
                    var rect = this.getBoundingClientRect();
                    var h = rect && rect.height ? rect.height : 0;
                    if (h > maxH) maxH = h;
                });
            }

            // Fallback: measure non-cloned slides that are in DOM flow
            if (maxH === 0) {
                $el.find('.slick-slide').not('.slick-cloned').each(function () {
                    if (this.offsetParent === null) return;
                    var rect = this.getBoundingClientRect();
                    var h = rect && rect.height ? rect.height : 0;
                    if (h > maxH) maxH = h;
                });
            }

            if (maxH === 0) maxH = 400; // fallback
            // Cap to a reasonable maximum to avoid runaway values caused by layout quirks
            maxH = Math.min(maxH, 900);
            $el.find('.slick-list').css('minHeight', Math.ceil(maxH) + 'px');
        } catch (err) {
            // ignore
        }
    }

    // Initialize timeline slider (for about.html)
    if ($(".timeline-slider").length) {
        var $timelineSlider = $(".timeline-slider");
        var isManualNavigation = false; // Flag to prevent afterChange from updating button during manual navigation
        var timelineSlider = $timelineSlider.slick({
            dots: false,
            infinite: true,
            speed: 300,
            slidesToShow: 3,
            slidesToScroll: 1,
            autoplay: false,
            arrows: true,
            centerMode: false,
            centerPadding: "0px",
            prevArrow:
                '<button type="button" class="slick-prev timeline-nav timeline-nav-left"><i class="bi bi-chevron-left"></i></button>',
            nextArrow:
                '<button type="button" class="slick-next timeline-nav timeline-nav-right"><i class="bi bi-chevron-right"></i></button>',
            responsive: [
                {
                    breakpoint: 992,
                    settings: {
                        slidesToShow: 2,
                        slidesToScroll: 1,
                    },
                },
                {
                    breakpoint: 768,
                    settings: {
                        slidesToShow: 1,
                        slidesToScroll: 1,
                        arrows: false,
                    },
                },
            ],
        });

        // Function to update opacity based on current slide (first visible slide is active)
        function updateTimelineOpacity() {
            // Get current slide index from Slick
            var currentSlide = timelineSlider.slick("slickCurrentSlide");
            var totalSlides = timelineSlider.slick("getSlick").slideCount;

            // Calculate actual index accounting for infinite mode
            var actualIndex = currentSlide;
            if (
                timelineSlider.slick("getSlick").options.infinite &&
                totalSlides > 0
            ) {
                actualIndex = currentSlide % totalSlides;
            }

            console.log(
                "updateTimelineOpacity - currentSlide:",
                currentSlide,
                "actualIndex:",
                actualIndex
            );

            // Get all slides
            var $allSlides = $(".timeline-slide");

            $allSlides.each(function (index) {
                var $slide = $(this);
                // Get the slide's index from Slick data
                var slideIndex = $slide.data("slick-index");
                if (slideIndex === undefined) {
                    slideIndex = index;
                }

                // Calculate actual slide index accounting for infinite mode
                var slideActualIndex = slideIndex;
                if (
                    timelineSlider.slick("getSlick").options.infinite &&
                    totalSlides > 0
                ) {
                    slideActualIndex = slideIndex % totalSlides;
                }

                // Check if this is the current active slide
                if (slideActualIndex === actualIndex) {
                    $slide.css("opacity", "1");
                    console.log(
                        "Setting opacity 1 for slide index:",
                        slideActualIndex
                    );
                } else {
                    $slide.css("opacity", "0.4");
                }
            });
        }

        // Function to get slide index from year button
        // This is dynamic and works with any number of years from database
        function getSlideIndexFromButton($btn) {
            // Get all timeline year buttons in order
            var $allButtons = $(".timeline-year-btn");
            // Find the index of the clicked button
            var buttonIndex = $allButtons.index($btn);
            return buttonIndex >= 0 ? buttonIndex : 0;
        }

        // Function to get button index from slide index
        function getButtonIndexFromSlide(slideIndex) {
            // In infinite mode, calculate actual index
            var totalSlides = timelineSlider.slick("getSlick").slideCount;
            var actualIndex = slideIndex;
            if (
                timelineSlider.slick("getSlick").options.infinite &&
                totalSlides > 0
            ) {
                actualIndex = slideIndex % totalSlides;
            }
            return actualIndex;
        }

        // Timeline year navigation handler
        function handleTimelineYearClick(e) {
            console.log("clicked");

            e.preventDefault();
            e.stopPropagation();

            var $btn = $(this);
            var year = $btn.data("year");

            // Get slide index dynamically based on button position
            // This works with any number of years from database
            var slideIndex = getSlideIndexFromButton($btn);

            console.log("Year:", year, "Slide Index:", slideIndex);

            // Set flag to prevent afterChange from updating button
            isManualNavigation = true;

            // Update active button
            $(".timeline-year-btn").removeClass("active");
            $btn.addClass("active");

            // Go to slide - use the slider reference
            // When centerMode is false, slickGoTo will position the slide at the left
            if (timelineSlider && typeof timelineSlider.slick === "function") {
                console.log("Navigating to slide index:", slideIndex);

                // Store target index for use in afterChange callback
                var targetIndex = slideIndex;

                // Add one-time listener for afterChange to update opacity
                var afterChangeHandler = function (event, slick, currentSlide) {
                    console.log(
                        "afterChange triggered, currentSlide:",
                        currentSlide
                    );
                    // Update opacity after slide has changed
                    updateTimelineOpacity();
                    // Remove this handler after it fires
                    timelineSlider.off("afterChange", afterChangeHandler);
                };

                timelineSlider.on("afterChange", afterChangeHandler);

                // Use slickGoTo with true parameter to animate
                timelineSlider.slick("slickGoTo", slideIndex, true);
            }

            // Reset flag after animation completes
            setTimeout(function () {
                isManualNavigation = false;
                // Final opacity update after animation
                updateTimelineOpacity();
            }, 500);
        }

        // Attach event handler to timeline year buttons after slider is ready
        // Use event delegation to ensure it works even if buttons are added dynamically
        $(document)
            .off("click", ".timeline-year-btn")
            .on("click", ".timeline-year-btn", handleTimelineYearClick);

        // Function to update active button based on slide index
        function updateActiveButton(slideIndex) {
            $(".timeline-year-btn").removeClass("active");
            var yearButtons = $(".timeline-year-btn");

            // Get the actual button index from slide index (accounting for infinite mode)
            var buttonIndex = getButtonIndexFromSlide(slideIndex);

            if (buttonIndex >= 0 && buttonIndex < yearButtons.length) {
                yearButtons.eq(buttonIndex).addClass("active");
            }
        }

        // Update active year on slide change and handle opacity
        timelineSlider.on("afterChange", function (event, slick, currentSlide) {
            // Only update button if not manually navigating (to prevent conflict with button click)
            if (!isManualNavigation) {
                // Update year button active state based on current slide
                updateActiveButton(currentSlide);
            }

            // Update opacity based on current slide
            updateTimelineOpacity();
        });

        // Also handle on init
        timelineSlider.on("init", function (event, slick) {
            // Set first year as active
            updateActiveButton(0);
            updateTimelineOpacity();
        });

        // Initial setup after slider is initialized
        setTimeout(function () {
            // Ensure first button is active
            var currentSlide = timelineSlider.slick("slickCurrentSlide");
            updateActiveButton(currentSlide);
            updateTimelineOpacity();
        }, 100);

        // Initialize articles slider for about page (with custom nav)
        if ($(".articles-nav-prev").length && $(".articles-nav-next").length) {
            $(".articles-slider").slick("unslick"); // Remove existing slick if any
            $(".articles-slider").slick({
                dots: false,
                infinite: true,
                speed: 300,
                slidesToShow: 4,
                slidesToScroll: 1,
                autoplay: false,
                arrows: true,
                prevArrow: $(".articles-nav-prev"),
                nextArrow: $(".articles-nav-next"),
                responsive: [
                    {
                        breakpoint: 1200,
                        settings: {
                            slidesToShow: 3,
                            slidesToScroll: 1,
                        },
                    },
                    {
                        breakpoint: 992,
                        settings: {
                            slidesToShow: 2,
                            slidesToScroll: 1,
                        },
                    },
                    {
                        breakpoint: 768,
                        settings: {
                            slidesToShow: 1,
                            slidesToScroll: 1,
                            arrows: false,
                        },
                    },
                ],
            });
        }
    }

    // Counter Animation
    function animateCounter($counter, target) {
        const duration = 2000; // 2 seconds
        const start = 0;
        const increment = target / (duration / 16); // 60fps
        let current = start;

        const timer = setInterval(function () {
            current += increment;
            if (current >= target) {
                current = target;
                clearInterval(timer);
            }
            // Format number with thousand separator
            const formattedNumber = Math.floor(current).toLocaleString("id-ID");
            $counter.text(formattedNumber);
        }, 16);
    }

    // Initialize counter animation with Intersection Observer
    const statsRow = document.querySelector(".stats-row");
    if (statsRow) {
        let hasAnimated = false;

        const observer = new IntersectionObserver(
            function (entries) {
                entries.forEach(function (entry) {
                    if (entry.isIntersecting && !hasAnimated) {
                        hasAnimated = true;

                        // Animate all counters
                        $(".stat-item h3").each(function () {
                            const $h3 = $(this);
                            const $counter = $h3.find(".counter-number");
                            const target = parseInt($h3.data("target"));

                            if ($counter.length && target) {
                                animateCounter($counter, target);
                            }
                        });
                    }
                });
            },
            {
                threshold: 0.3, // Trigger when 30% of the element is visible
            }
        );

        observer.observe(statsRow);
    }

    // Timeline Journey Navigation
    function initTimeline() {
        const timelineCards = document.querySelectorAll(".timeline-card");
        const timelineYearBadges = document.querySelectorAll(
            ".timeline-year-badge"
        );
        const prevBtn = document.getElementById("timelinePrev");
        const nextBtn = document.getElementById("timelineNext");

        if (timelineCards.length === 0) {
            return;
        }

        let currentIndex = 0;

        // Find initial active index
        timelineCards.forEach((card, index) => {
            if (card.classList.contains("active")) {
                currentIndex = index;
            }
        });

        // Function to show specific timeline card
        function showTimelineCard(index) {
            if (index < 0 || index >= timelineCards.length) {
                return;
            }

            // Update currentIndex FIRST to prevent findActiveCardFromScroll from changing it
            currentIndex = index;

            // Set flag to prevent sync and findActiveCardFromScroll during programmatic scroll
            // Set BEFORE any scroll operations to prevent race conditions
            isScrolling = true;

            // Remove active class from all cards and badges
            timelineCards.forEach((card) => {
                card.classList.remove("active");
            });
            timelineYearBadges.forEach((badge) => {
                badge.classList.remove("active");
            });

            // Add active class to current card and badge
            if (timelineCards[index]) {
                timelineCards[index].classList.add("active");

                // Scroll to center the active card
                const cardsContainer = document.querySelector(
                    ".timeline-cards-container"
                );
                const yearsRow = document.querySelector(".timeline-years-row");
                const yearItem = document.querySelector(
                    `.timeline-year-item[data-index="${index}"]`
                );

                if (cardsContainer && timelineCards[index]) {
                    const card = timelineCards[index];
                    const cardOffsetLeft = card.offsetLeft;
                    const cardWidth = card.offsetWidth;
                    const containerWidth = cardsContainer.offsetWidth;
                    const containerPadding = 15; // Match CSS padding
                    const isMobile = window.innerWidth <= 768;

                    // On mobile, scroll to start of card (one card per view)
                    // Account for container padding
                    const scrollLeft = isMobile
                        ? cardOffsetLeft - containerPadding
                        : cardOffsetLeft - containerWidth / 2 + cardWidth / 2;

                    cardsContainer.scrollTo({
                        left: Math.max(0, scrollLeft),
                        behavior: "smooth",
                    });
                }

                // Scroll years row to align with active card
                if (yearsRow && yearItem) {
                    const yearItemOffsetLeft = yearItem.offsetLeft;
                    const yearItemWidth = yearItem.offsetWidth;
                    const yearsRowWidth = yearsRow.offsetWidth;
                    const isMobile = window.innerWidth <= 768;

                    // On mobile, scroll to start of year item
                    // On desktop, center the year item
                    const yearsScrollLeft = isMobile
                        ? yearItemOffsetLeft - 10 // 10px padding
                        : yearItemOffsetLeft -
                        yearsRowWidth / 2 +
                        yearItemWidth / 2;

                    yearsRow.scrollTo({
                        left: Math.max(0, yearsScrollLeft),
                        behavior: "smooth",
                    });
                }
            }

            if (timelineYearBadges[index]) {
                timelineYearBadges[index].classList.add("active");
            }

            // Reset flag after scroll animation completes (increased timeout for smoother scroll)
            setTimeout(() => {
                isScrolling = false;
            }, 800);

            updateNavButtons();
        }

        // Function to update navigation buttons state
        function updateNavButtons() {
            if (prevBtn) {
                const isFirst = currentIndex === 0;
                prevBtn.style.opacity = isFirst ? "0.5" : "1";
                prevBtn.style.cursor = isFirst ? "not-allowed" : "pointer";
                prevBtn.disabled = isFirst;
            }
            if (nextBtn) {
                const isLast = currentIndex === timelineCards.length - 1;
                nextBtn.style.opacity = isLast ? "0.5" : "1";
                nextBtn.style.cursor = isLast ? "not-allowed" : "pointer";
                nextBtn.disabled = isLast;
            }
        }

        // Year badge click event
        timelineYearBadges.forEach((badge, index) => {
            badge.addEventListener("click", function (e) {
                e.preventDefault();
                e.stopPropagation();
                // Set flag to prevent scroll sync and findActiveCardFromScroll during programmatic scroll
                isScrolling = true;
                showTimelineCard(index);
                // Flag will be reset in showTimelineCard after scroll completes
            });
        });

        // Previous button click event
        if (prevBtn) {
            prevBtn.addEventListener("click", function (e) {
                e.preventDefault();
                e.stopPropagation();
                if (currentIndex > 0) {
                    showTimelineCard(currentIndex - 1);
                }
            });
        }

        // Next button click event
        if (nextBtn) {
            nextBtn.addEventListener("click", function (e) {
                e.preventDefault();
                e.stopPropagation();
                if (currentIndex < timelineCards.length - 1) {
                    showTimelineCard(currentIndex + 1);
                }
            });
        }

        // Function to find active card based on scroll position
        function findActiveCardFromScroll() {
            // Don't update if we're in the middle of a programmatic scroll
            if (isScrolling) return;

            const cardsContainer = document.querySelector(
                ".timeline-cards-container"
            );
            if (!cardsContainer) return;

            const containerRect = cardsContainer.getBoundingClientRect();
            const containerLeft = containerRect.left;
            const containerWidth = containerRect.width;
            const isMobile = window.innerWidth <= 768;

            let activeIndex = currentIndex; // Start with current index
            let maxVisibleArea = 0;

            timelineCards.forEach((card, index) => {
                const cardRect = card.getBoundingClientRect();
                const cardLeft = cardRect.left;
                const cardRight = cardRect.right;
                const cardWidth = cardRect.width;

                // Calculate visible area of card
                const visibleLeft = Math.max(cardLeft, containerLeft);
                const visibleRight = Math.min(
                    cardRight,
                    containerLeft + containerWidth
                );
                const visibleWidth = Math.max(0, visibleRight - visibleLeft);
                const visibleArea = visibleWidth / cardWidth; // Percentage of card visible

                // Find card with most visible area
                if (visibleArea > maxVisibleArea) {
                    maxVisibleArea = visibleArea;
                    activeIndex = index;
                }
            });

            // Only update if index changed and we found a card with significant visibility
            // Lower threshold on mobile since cards are full width
            // Also check that we're not in the middle of a programmatic scroll
            const visibilityThreshold = isMobile ? 0.5 : 0.3;
            if (
                activeIndex !== currentIndex &&
                maxVisibleArea > visibilityThreshold &&
                !isScrolling
            ) {
                // Update active state without triggering scroll (to avoid loop)
                timelineCards.forEach((card) => {
                    card.classList.remove("active");
                });
                timelineYearBadges.forEach((badge) => {
                    badge.classList.remove("active");
                });

                if (timelineCards[activeIndex]) {
                    timelineCards[activeIndex].classList.add("active");
                }
                if (timelineYearBadges[activeIndex]) {
                    timelineYearBadges[activeIndex].classList.add("active");
                }

                currentIndex = activeIndex;
                updateNavButtons();

                // Scroll years row to match (without triggering sync)
                const yearItem = document.querySelector(
                    `.timeline-year-item[data-index="${activeIndex}"]`
                );

                if (yearsRow && yearItem && !isScrolling) {
                    const yearItemOffsetLeft = yearItem.offsetLeft;
                    const yearItemWidth = yearItem.offsetWidth;
                    const yearsRowWidth = yearsRow.offsetWidth;
                    const isMobile = window.innerWidth <= 768;

                    const yearsScrollLeft = isMobile
                        ? yearItemOffsetLeft - 10
                        : yearItemOffsetLeft -
                        yearsRowWidth / 2 +
                        yearItemWidth / 2;

                    isScrolling = true;
                    yearsRow.scrollTo({
                        left: Math.max(0, yearsScrollLeft),
                        behavior: "smooth",
                    });
                    setTimeout(() => {
                        isScrolling = false;
                    }, 300);
                }
            }
        }

        // Sync scroll between years row and cards container
        const cardsContainer = document.querySelector(
            ".timeline-cards-container"
        );
        const yearsRow = document.querySelector(".timeline-years-row");

        // Shared scrolling flag
        let isScrolling = false;
        let scrollTimeout;

        if (cardsContainer && yearsRow) {
            // Detect active card from cards container scroll
            cardsContainer.addEventListener("scroll", function () {
                // Don't detect active card if we're in programmatic scroll
                if (isScrolling) {
                    // Clear any pending timeouts to prevent conflicts
                    clearTimeout(scrollTimeout);
                    return;
                }

                // Clear previous timeout
                clearTimeout(scrollTimeout);

                // On mobile, find active card immediately during scroll for better UX
                const isMobile = window.innerWidth <= 768;
                if (isMobile) {
                    // Double check isScrolling flag before calling
                    if (!isScrolling) {
                        findActiveCardFromScroll();
                    }
                }

                // Debounce to find active card after scroll stops (for desktop)
                // Increased debounce time to ensure programmatic scrolls are complete
                scrollTimeout = setTimeout(
                    () => {
                        // Double check isScrolling flag before calling
                        if (!isScrolling) {
                            findActiveCardFromScroll();
                        }
                    },
                    isMobile ? 150 : 200
                );

                // Sync scroll to years row (only if not programmatic scroll and not mobile)
                if (isScrolling || isMobile) return;
                isScrolling = true;

                const scrollRatio =
                    cardsContainer.scrollLeft /
                    (cardsContainer.scrollWidth - cardsContainer.clientWidth ||
                        1);
                const yearsMaxScroll =
                    yearsRow.scrollWidth - yearsRow.clientWidth;
                if (yearsMaxScroll > 0) {
                    yearsRow.scrollLeft = scrollRatio * yearsMaxScroll;
                }

                setTimeout(() => {
                    isScrolling = false;
                }, 50);
            });

            // Sync years row scroll to cards container (disabled on mobile to prevent conflicts)
            yearsRow.addEventListener("scroll", function () {
                if (window.innerWidth <= 768) return; // Disable sync on mobile

                if (isScrolling) return;
                isScrolling = true;

                const scrollRatio =
                    yearsRow.scrollLeft /
                    (yearsRow.scrollWidth - yearsRow.clientWidth || 1);
                const cardsMaxScroll =
                    cardsContainer.scrollWidth - cardsContainer.clientWidth;
                if (cardsMaxScroll > 0) {
                    cardsContainer.scrollLeft = scrollRatio * cardsMaxScroll;
                }

                setTimeout(() => {
                    isScrolling = false;
                }, 50);
            });
        }

        // Initialize
        updateNavButtons();
    }

    // Initialize timeline if elements exist
    if (document.querySelector(".timeline-card")) {
        initTimeline();
    }

    // Initialize catalogue sliders
    function initCatalogueSliders() {
        $(".catalogue-slider").each(function () {
            if (!$(this).hasClass("slick-initialized")) {
                $(this).slick({
                    dots: false,
                    infinite: true,
                    speed: 300,
                    slidesToShow: 3,
                    slidesToScroll: 1,
                    autoplay: false,
                    arrows: true,
                    prevArrow:
                        '<button type="button" class="slick-prev"><i class="bi bi-chevron-left"></i></button>',
                    nextArrow:
                        '<button type="button" class="slick-next"><i class="bi bi-chevron-right"></i></button>',
                    responsive: [
                        {
                            breakpoint: 1200,
                            settings: {
                                slidesToShow: 3,
                                slidesToScroll: 1,
                            },
                        },
                        {
                            breakpoint: 992,
                            settings: {
                                slidesToShow: 2,
                                slidesToScroll: 1,
                            },
                        },
                        {
                            breakpoint: 768,
                            settings: {
                                slidesToShow: 1,
                                slidesToScroll: 1,
                            },
                        },
                    ],
                });
            }
        });
    }

    $(document).on('shown.bs.tab', 'button[data-bs-toggle="tab"]', function (e) {
        var targetTab = $(e.target).attr('data-bs-target') || $(e.target).data('bs-target');
        var $tabPane = $(targetTab);
        if (!$tabPane || $tabPane.length === 0) return;

        $tabPane.find('.slider-subitems').each(function () {
            var $el = $(this);
            if (!$el.hasClass('slick-initialized')) {
                $el.slick({
                    slidesToShow: 1,
                    slidesToScroll: 1,
                    arrows: true,
                    autoplay: true,
                    speed: 2000,
                    autoplaySpeed: 2000,
                    infinite: true,
                    adaptiveHeight: true,
                });
                // Ensure slick calculates proper widths after being shown
                setTimeout(function () {
                    try {
                        // Compute min-height from content and recalc layout
                        computeAndSetSliderMinHeight($el);
                        $el.slick('setPosition');
                    } catch (err) {
                        // ignore
                    }
                }, 50);
            } else {
                // If already initialized but may have layout issues, trigger setPosition
                setTimeout(function () {
                    try {
                        computeAndSetSliderMinHeight($el);
                        $el.slick('setPosition');
                    } catch (err) { }
                }, 50);
            }
        });
    });

    $(".tab-pane.show.active")
        .find(".slider-subitems")
        .each(function () {
            if (!$(this).hasClass("slick-initialized")) {
                $(this).slick({
                    slidesToShow: 1,
                    slidesToScroll: 1,
                    arrows: true,
                    autoplay: false,
                    speed: 2000,
                    autoplaySpeed: 2000,
                    infinite: true,
                    adaptiveHeight: true,
                });
                // Ensure minimal height for layout stability and recalc
                var $that = $(this);
                setTimeout(function () {
                    try {
                        computeAndSetSliderMinHeight($that);
                        $that.slick('setPosition');
                    } catch (err) { }
                }, 50);
            }
        });

    // Initialize catalogue sliders if elements exist
    if ($(".catalogue-slider").length > 0) {
        initCatalogueSliders();
    }

    // Delay image display by 1 second after document load
    function showLazyImages() {
        const lazyImages = document.querySelectorAll("img[loading='lazy']");
        lazyImages.forEach((img) => {
            // Pastikan placeholder abu-abu terlihat, gambar asli tersembunyi
            if (!img.classList.contains("lazy-show")) {
                img.style.backgroundColor = "#e0e0e0";
                img.style.filter = "opacity(0)";
                img.style.transition = "filter 0.5s ease";
            }

            // Function untuk menampilkan gambar asli dan menghilangkan placeholder
            function showImage() {
                img.classList.add("lazy-show");
                img.classList.add("lazy-loaded");
                img.classList.remove("lazy-loading");
                // Fade in gambar asli dengan filter opacity
                img.style.filter = "opacity(1)";
                // Hapus placeholder setelah transisi selesai
                setTimeout(() => {
                    img.style.backgroundColor = "transparent";
                }, 500);
            }

            // Check if image is already loaded
            if (img.complete && img.naturalHeight !== 0) {
                // Image sudah terload, tunggu delay lalu tampilkan
                setTimeout(showImage, 100);
            } else {
                // Wait for image to load, then show after delay
                const loadHandler = function () {
                    setTimeout(showImage, 100);
                };

                img.addEventListener("load", loadHandler, { once: true });

                img.addEventListener(
                    "error",
                    function () {
                        this.classList.add("lazy-show");
                        this.classList.add("lazy-error");
                        this.classList.remove("lazy-loading");
                        this.style.backgroundColor = "#e0e0e0";
                        this.style.filter = "opacity(0.5)";
                    },
                    { once: true }
                );

                // Add loading class initially if image is not already loaded
                if (!img.complete) {
                    img.classList.add("lazy-loading");
                } else {
                    // Image already loaded, show it after delay
                    setTimeout(showImage, 100);
                }
            }
        });
    }

    // Wait 2 seconds after document is fully loaded, then show images
    if (document.readyState === "complete") {
        setTimeout(showLazyImages, 1250);
    } else {
        window.addEventListener("load", function () {
            setTimeout(showLazyImages, 1250);
        });
    }

    if ("loading" in HTMLImageElement.prototype) {
    } else {
        const script = document.createElement("script");
        script.src =
            "https://cdn.jsdelivr.net/npm/vanilla-lazyload@17/dist/lazyload.min.js";
        document.body.appendChild(script);

        script.onload = function () {
            if (typeof LazyLoad !== "undefined") {
                const lazyLoadInstance = new LazyLoad({
                    elements_selector: "img[loading='lazy']",
                    threshold: 0,
                    use_native: false,
                    callback_loaded: function (el) {
                        el.classList.add("lazy-show");
                    },
                });
            }
        };
    }
});
