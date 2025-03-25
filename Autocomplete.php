function load_google_places_autocomplete_for_blocks_shipping() {
    if ( is_checkout() ) {
        ?>
        <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDTjJhzvZ7WKB6Wki9eeKDcBAuKs5_tBFs&libraries=places"></script>
        <script>
        (function() {
            // Set your target country code (e.g., 'NZ', 'US', 'GB', 'CN')
            const COUNTRY_CODE = 'NZ';

            function initAutocomplete() {
                const shippingInput = document.getElementById("shipping-address_1");
                if (!shippingInput) {
                    console.log("No #shipping-address_1 field found in DOM.");
                    return;
                }
                console.log("Found shipping address field:", shippingInput);

                const autocomplete = new google.maps.places.Autocomplete(shippingInput, {
                    types: ['address'],
                    componentRestrictions: { country: COUNTRY_CODE }
                });

                autocomplete.addListener('place_changed', function() {
                    const place = autocomplete.getPlace();
                    if (!place.address_components) return;

                    console.log("User selected place:", place);

                    let street_number = '', route = '', city = '', state = '', postcode = '';

                    place.address_components.forEach(function(component) {
                        const types = component.types;
                        if (types.includes('street_number')) street_number = component.long_name;
                        if (types.includes('route')) route = component.long_name;
                        if (types.includes('locality')) city = component.long_name;
                        if (types.includes('administrative_area_level_1')) state = component.short_name;
                        if (types.includes('postal_code')) postcode = component.long_name;
                    });

                    // Combine street_number + route
                    const fullAddress = [street_number, route].filter(Boolean).join(' ');
                    if (fullAddress) {
                        shippingInput.value = fullAddress;
                        console.log("Filled #shipping-address_1:", fullAddress);
                    }

                    // Fill city, state, postcode if those fields exist
                    const cityInput = document.getElementById("shipping-city");
                    const stateInput = document.getElementById("shipping-state");
                    const postcodeInput = document.getElementById("shipping-postcode");

                    if (city && cityInput) {
                        cityInput.value = city;
                        console.log("Filled city:", city);
                    }
                    if (state && stateInput) {
                        stateInput.value = state;
                        console.log("Filled state:", state);
                    }
                    if (postcode && postcodeInput) {
                        postcodeInput.value = postcode;
                        console.log("Filled postcode:", postcode);
                    }
                });
            }

            // Use a MutationObserver to wait for the React-rendered fields
            const observer = new MutationObserver(function(mutations, obs) {
                const shippingField = document.getElementById("shipping-address_1");
                if (shippingField && typeof google !== 'undefined' && google.maps && google.maps.places) {
                    initAutocomplete();
                    obs.disconnect();
                    console.log("MutationObserver disconnected after attaching autocomplete.");
                }
            });
            observer.observe(document.body, { childList: true, subtree: true });
        })();
        </script>
        <?php
    }
}
add_action('wp_footer', 'load_google_places_autocomplete_for_blocks_shipping', 100);
