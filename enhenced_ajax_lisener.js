<script id="gtm-jq-ajax-listen" type="text/javascript">
(function() {
    'use strict';
    var $;
    init();

    function init(n) {
        n = n || 0;

        if (typeof jQuery !== 'undefined') {
            $ = jQuery;
            bindEvents();
        } else if (n < 20) {
            setTimeout(function() {
                init(n + 1);
            }, 500);
        }
    }

    function bindEvents() {
        // Listen for all AJAX requests
        $(document).bind('ajaxComplete', function(evt, jqXhr, opts) {
            console.log('AJAX request completed:', opts.url);
            dataLayer.push({
                event: 'ajaxComplete',
                url: opts.url,
                method: opts.type || 'GET',
                status: jqXhr.status || '',
                response: jqXhr.responseJSON || jqXhr.responseText || ''
            });
        });

        // Listen for form submissions
        $(document).on('submit', 'form', function(event) {
            event.preventDefault(); // Prevent default form submission
            var formData = $(this).serialize();
            console.log('Form submitted:', formData);
            dataLayer.push({
                event: 'formSubmit',
                formId: $(this).attr('id') || '',
                formClass: $(this).attr('class') || '',
                formData: formData
            });
            // Submit form data via AJAX if needed
            $.ajax({
                url: $(this).attr('action'),
                method: $(this).attr('method') || 'POST',
                data: formData
            });
        });
    }
})();
</script>
