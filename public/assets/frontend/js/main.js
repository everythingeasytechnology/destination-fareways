/* Destination Fareways - Phase 2 Frontend Main JS */

$(document).ready(function () {
    // 1. Navbar Scroll Transition
    function handleNavbarScroll() {
        var navbar = $('#frontend-navbar');
        if (navbar.length) {
            // Check if it has the transparent class (only home page has this by default)
            var isTransparentDefault = navbar.hasClass('navbar-transparent');
            if (isTransparentDefault) {
                if ($(window).scrollTop() > 80) {
                    navbar.addClass('scrolled');
                } else {
                    navbar.removeClass('scrolled');
                }
            }
        }
    }
    
    // Execute on scroll and on page load
    $(window).on('scroll', handleNavbarScroll);
    handleNavbarScroll();

    // 2. Floating Buttons Display (Mobile Only: screen < 768px)
    function handleMobileFloatingButtons() {
        var callBtn = $('.floating-call-btn');
        var waBtn = $('.floating-whatsapp-btn');
        if ($(window).width() < 768) {
            callBtn.css('display', 'flex');
            waBtn.css('display', 'flex');
        } else {
            callBtn.hide();
            waBtn.hide();
        }
    }
    
    $(window).on('resize', handleMobileFloatingButtons);
    handleMobileFloatingButtons();

    // 3. Flight Search Form - Tab Toggle Logic
    $('.search-tab-btn').on('click', function () {
        $('.search-tab-btn').removeClass('active');
        $(this).addClass('active');
        
        var tripType = $(this).data('type'); // 'one-way', 'round-trip', 'multi-city'
        $('#trip_type_input').val(tripType);
        
        if (tripType === 'one-way') {
            $('#return_date_wrapper').css('opacity', '0.5');
            $('#return_date_input').prop('disabled', true).val('');
            $('.multi-city-fields').slideUp(180);
            $('#from_city_input, #to_city_input, #depart_date_input').prop('disabled', false);
        } else if (tripType === 'round-trip') {
            $('#return_date_wrapper').css('opacity', '1');
            $('#return_date_input').prop('disabled', false);
            $('.multi-city-fields').slideUp(180);
            $('#from_city_input, #to_city_input, #depart_date_input').prop('disabled', false);
        } else if (tripType === 'multi-city') {
            $('#return_date_wrapper').css('opacity', '0.5');
            $('#return_date_input').prop('disabled', true);
            $('.multi-city-fields').slideDown(180);
            $('#from_city_input, #to_city_input, #depart_date_input').prop('disabled', true);
        }
    });

    var initialTripType = ($('#trip_type_input').val() || '').replace('_', '-');
    if (initialTripType === 'multi-city') {
        $('.search-tab-btn[data-type="multi-city"]').trigger('click');
    } else if (initialTripType === 'one-way') {
        $('.search-tab-btn[data-type="one-way"]').trigger('click');
    }

    // 4. Swap From & To Location Value Toggle
    $('.swap-btn').on('click', function () {
        var fromInput = $('#from_city_input');
        var toInput = $('#to_city_input');
        var fromIdInput = $('#from_location_id');
        var toIdInput = $('#to_location_id');
        
        if (fromInput.length && toInput.length) {
            var temp = fromInput.val();
            fromInput.val(toInput.val());
            toInput.val(temp);

            var tempId = fromIdInput.val();
            fromIdInput.val(toIdInput.val());
            toIdInput.val(tempId);
        }
    });

    // 4b. Booking.com15 location autocomplete
    var locationTimers = {};

    $('.flight-location-input').each(function () {
        var input = $(this);
        var group = input.closest('.search-input-group');
        group.css('position', 'relative');

        if (!group.find('.flight-location-suggestions').length) {
            group.append('<div class="flight-location-suggestions"></div>');
        }
    });

    $('.flight-location-input').on('input', function () {
        var input = $(this);
        var query = input.val().trim();
        var targetId = input.data('target');
        var dropdown = input.closest('.search-input-group').find('.flight-location-suggestions');

        $('#' + targetId).val('');
        clearTimeout(locationTimers[input.attr('id')]);

        if (query.length < 2) {
            dropdown.hide().empty();
            return;
        }

        dropdown.html('<div class="px-3 py-2 small text-muted">Searching...</div>').show();

        locationTimers[input.attr('id')] = setTimeout(function () {
            $.ajax({
                url: '/flights/locations',
                method: 'GET',
                data: { query: query },
                success: function (response) {
                    var locations = response.locations || [];

                    if (!locations.length) {
                        dropdown.html('<div class="px-3 py-2 small text-muted">No locations found</div>').show();
                        return;
                    }

                    var html = locations.map(function (location) {
                        var code = location.code ? '<span class="badge bg-light text-navy border ms-2">' + location.code + '</span>' : '';
                        var country = location.country ? '<div class="small text-muted">' + location.country + ' · ' + location.type + '</div>' : '<div class="small text-muted">' + location.type + '</div>';

                        return '<button type="button" class="flight-location-option" data-id="' + location.id + '" data-label="' + location.label + '">' +
                            '<div class="fw-semibold">' + location.name + code + '</div>' +
                            country +
                        '</button>';
                    }).join('');

                    dropdown.html(html).show();
                },
                error: function () {
                    dropdown.html('<div class="px-3 py-2 small text-muted">Suggestions unavailable</div>').show();
                }
            });
        }, 350);
    });

    $(document).on('click', '.flight-location-option', function () {
        var option = $(this);
        var group = option.closest('.search-input-group');
        var input = group.find('.flight-location-input');
        var targetId = input.data('target');

        input.val(option.data('label'));
        $('#' + targetId).val(option.data('id'));
        group.find('.flight-location-suggestions').hide().empty();
    });

    $(document).on('click', function (e) {
        if (!$(e.target).closest('.search-input-group').length) {
            $('.flight-location-suggestions').hide();
        }
    });

    // 5. Passengers and Cabin Class Dropdown Logic
    var passengerInputWrapper = $('#passengers_input_wrapper');
    var passengerDropdown = $('#passenger_dropdown_menu');

    if (passengerInputWrapper.length && passengerDropdown.length) {
        passengerInputWrapper.on('click', function (e) {
            e.stopPropagation();
            passengerDropdown.fadeToggle(200);
        });

        // Close dropdown when clicking outside
        $(document).on('click', function (e) {
            if (!$(e.target).closest('#passenger_dropdown_container').length) {
                passengerDropdown.fadeOut(200);
            }
        });
    }

    // Passenger counters +/- logic
    $('.counter-btn').on('click', function (e) {
        e.preventDefault();
        e.stopPropagation();
        
        var type = $(this).data('type'); // 'adults', 'children', 'infants'
        var action = $(this).data('action'); // 'plus', 'minus'
        var valSpan = $('#count_' + type);
        var inputField = $('#input_' + type);
        
        var currentVal = parseInt(valSpan.text());
        var minVal = (type === 'adults') ? 1 : 0; // At least 1 adult
        var maxVal = 9;

        if (action === 'plus' && currentVal < maxVal) {
            currentVal++;
        } else if (action === 'minus' && currentVal > minVal) {
            currentVal--;
        }

        valSpan.text(currentVal);
        inputField.val(currentVal);
        
        updatePassengerSummary();
    });

    // Cabin class select change triggers summary update
    $('#cabin_class_select').on('change', function () {
        updatePassengerSummary();
    });

    function updatePassengerSummary() {
        var adults = parseInt($('#count_adults').text() || '1');
        var children = parseInt($('#count_children').text() || '0');
        var infants = parseInt($('#count_infants').text() || '0');
        var total = adults + children + infants;
        
        var cabin = $('#cabin_class_select').val() || 'Economy';
        
        var summaryText = total + ' Passenger' + (total > 1 ? 's' : '') + ', ' + cabin;
        $('#passengers_summary_text').text(summaryText);
        $('#total_passengers_input').val(total);
    }

    // 6. Flatpickr Initializer
    if (typeof flatpickr !== 'undefined') {
        flatpickr(".flatpickr-date", {
            dateFormat: "Y-m-d",
            minDate: "today",
            theme: "material_blue",
            allowInput: true
        });
    }

    // 7. Swiper Initializer for Testimonials (Manual trigger only)
    if (typeof Swiper !== 'undefined' && $('.swiper-testimonials').length) {
        new Swiper('.swiper-testimonials', {
            slidesPerView: 1,
            spaceBetween: 30,
            loop: true,
            pagination: {
                el: '.swiper-pagination',
                clickable: true,
            },
            navigation: {
                nextEl: '.swiper-button-next',
                prevEl: '.swiper-button-prev',
            },
            breakpoints: {
                768: {
                    slidesPerView: 2,
                },
                1024: {
                    slidesPerView: 3,
                }
            }
        });
    }

    // 8. AJAX Newsletter Subscription Form Handler
    $('#newsletter-form').on('submit', function (e) {
        e.preventDefault();
        
        var form = $(this);
        var emailInput = form.find('input[type="email"]');
        var submitBtn = form.find('button');
        var email = emailInput.val();
        
        if (!email) return;

        submitBtn.prop('disabled', true).html('<i class="fa-solid fa-spinner fa-spin"></i>');

        $.ajax({
            url: form.attr('action'),
            method: 'POST',
            data: {
                _token: $('meta[name="csrf-token"]').attr('content'),
                email: email
            },
            success: function (response) {
                submitBtn.prop('disabled', false).text('Subscribed');
                emailInput.val('');
                showToast('success', response.message || 'Successfully subscribed for flight deals!');
            },
            error: function (xhr) {
                submitBtn.prop('disabled', false).text('Subscribe');
                var errMsg = 'An error occurred. Please try again.';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errMsg = xhr.responseJSON.message;
                } else if (xhr.responseJSON && xhr.responseJSON.errors && xhr.responseJSON.errors.email) {
                    errMsg = xhr.responseJSON.errors.email[0];
                }
                showToast('danger', errMsg);
            }
        });
    });

    // Toast generator helper
    function showToast(type, message) {
        var toastContainer = $('.toast-container');
        if (!toastContainer.length) {
            $('body').append('<div class="toast-container position-fixed top-0 end-0 p-3" style="z-index: 1080;"></div>');
            toastContainer = $('.toast-container');
        }

        var icon = type === 'success' ? 'fa-circle-check' : 'fa-circle-exclamation';
        var bgClass = type === 'success' ? 'bg-success' : 'bg-danger';

        var toastHtml = `
            <div class="toast align-items-center text-white ${bgClass} border-0 shadow-lg" role="alert" aria-live="assertive" aria-atomic="true">
                <div class="d-flex">
                    <div class="toast-body d-flex align-items-center">
                        <i class="fa-solid ${icon} fs-5 me-2"></i>
                        ${message}
                    </div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
                </div>
            </div>
        `;

        var toastElement = $(toastHtml).appendTo(toastContainer);
        var bsToast = new bootstrap.Toast(toastElement[0], { delay: 6000 });
        bsToast.show();

        // Autoremove from DOM after hide
        toastElement.on('hidden.bs.toast', function () {
            $(this).remove();
        });
    }
});
