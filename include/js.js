var ajaxRequest;

jQuery(document).ready(function() {

    jQuery('.entry-content').removeClass('is-layout-constrained');

    jQuery('#comparatorFormData input, #comparatorFormData select').change(function() {
        setTimeout(function() {
            comparator_send_search();
        }, 1000);
    });

});

/*
jQuery(document).on('change', '#results-filter input, #results-filter select', function() {
    setTimeout(function() {
        comparator_send_results_filter();
    }, 1000);
});
*/

jQuery(document).on('click', '.open-product-form', function() {

    var product = jQuery(this).attr('data-product');

    

});


function comparator_send_search() {

    if (ajaxRequest) { ajaxRequest.abort(); }

    var data = jQuery('#comparatorFormData').serialize() + '&action=comparator_send_form';

    ajaxRequest = jQuery.post({
        url: '/wp-admin/admin-ajax.php',
        data: data,
        dataType: 'html',
        success: function(response) {
            //jQuery('#search-results').html( response );

            document.getElementById('search-results').innerHTML = response;

            // Initialize the Bootstrap slider
            jQuery("#price-slider").slider({});
        }
    });

}

function comparator_send_results_filter() {

    if (ajaxRequest) { ajaxRequest.abort();}

    var data = jQuery('#results-filter').serialize() + '&action=comparator_send_form_filter';

    ajaxRequest = jQuery.post( '/wp-admin/admin-ajax.php', data, function( response ){
        jQuery('#result-list').html( response );
    });

}



jQuery(document).on('click', '.fa-minus-circle', function() {

    var val = jQuery(this).parent().find('input').val();
    
    if( Number(val) != 0 ) {
        jQuery(this).parent().find('input').val( Number(val)-1 );
    }

    comparator_send_search();
    

});

jQuery(document).on('click', '.fa-plus-circle', function() {

    var val = jQuery(this).parent().find('input').val();
    
    jQuery(this).parent().find('input').val( Number(val)+1 );

    comparator_send_search();

});

jQuery(document).on('change', '.request-type', function() {

    if( jQuery(this).is(":checked") ) {
        
        jQuery(this).parent().parent().parent().find('input').each(function() {
            var input = jQuery(this);
            if( !jQuery(input).hasClass('request-type') ) {
                jQuery(input).attr('disabled', false);
            }
        });

    } else {

        jQuery(this).parent().parent().parent().find('input').each(function() {
            var input = jQuery(this);
            if( !jQuery(input).hasClass('request-type') ) {
                jQuery(input).attr('disabled', true);
            }
        });

    }

    comparator_send_search();

});




function sortDivsByDataValue(order, field) {
    var $sortableDivs = jQuery('.sortable-div');

    $sortableDivs.sort(function(a, b) {
        var aValue = parseInt(jQuery(a).data(field));
        var bValue = parseInt(jQuery(b).data(field));

        return (aValue - bValue) * order;
    });

    // Remove existing divs and append the sorted ones
    $sortableDivs.detach().appendTo('#result-list');
}

function sortDivsByOption(optionValue) {

    switch (optionValue) {
        case 'price_desc':
            var sortOrder = -1;
            sortDivsByDataValue(sortOrder, 'price');
            break;
        case 'price_asc':
            var sortOrder = 1;
            sortDivsByDataValue(sortOrder, 'price');
            break;
        case 'price_promo_desc':
            var sortOrder = -1;
            sortDivsByDataValue(sortOrder, 'promo-price');
            break;
        case 'price_promo_asc':
            var sortOrder = 1;
            sortDivsByDataValue(sortOrder, 'promo-price');
            break;
        default:
            // Do nothing if no valid option is selected
            return;
    }

}

jQuery(document).on('change', '#sortingSelect', function() {
    var selectedOption = jQuery(this).val();
    sortDivsByOption(selectedOption);
});


// Listen for changes in the filter fields
jQuery(document).on('change', '.filter-block input', function() {
    filterResults();
});

// Listen for changes in the filter fields
jQuery(document).on('slideStop', '#price-slider', function(slideEvt) {
    // Update the hidden input values
    jQuery("#min-price").val(slideEvt.value[0]);
    jQuery("#max-price").val(slideEvt.value[1]);

    jQuery("#price-range").text("EUR" + slideEvt.value[0] + " - EUR" + slideEvt.value[1]);

    filterResults();
});


function filterResults() {
    // Get the minimum and maximum values from the price filter fields
    var minPrice = parseFloat(jQuery('#min-price').val()) || 0;
    var maxPrice = parseFloat(jQuery('#max-price').val()) || Infinity;

    // Get the selected operators from the checkboxes
    var selectedOperators = jQuery('input[name="filter[operators][]"]:checked').map(function() {
        return jQuery(this).val();
    }).get();

    // Loop through each result element
    jQuery('.result-list .sortable-div').each(function() {
        var resultPrice = parseFloat(jQuery(this).data('price'));
        var resultOperator = jQuery(this).data('provider');

        // Check if the result matches the price and operator criteria
        var priceCondition = resultPrice >= minPrice && resultPrice <= maxPrice;
        var operatorCondition = selectedOperators.length === 0 || selectedOperators.includes(resultOperator);

        // Show or hide the result based on the criteria
        if (priceCondition && operatorCondition) {
            jQuery(this).show();  // Show the result if it matches the criteria
        } else {
            jQuery(this).hide();  // Hide the result if it doesn't match the criteria
        }
    });
}

