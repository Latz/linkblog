jQuery(document).ready(function($) {
    $('.lb-expansion-trigger').on('click', function() {
        $(this).closest('.lb-expansion-row').toggleClass('is-open');
    });
    $('.js-lb-expand-all').on('click', function() { $('.lb-expansion-row').addClass('is-open'); });
    $('.js-lb-collapse-all').on('click', function() { $('.lb-expansion-row').removeClass('is-open'); });
});
