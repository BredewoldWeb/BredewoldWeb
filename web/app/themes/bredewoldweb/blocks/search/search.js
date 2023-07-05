jQuery('document').ready(function(){

    /* Make sure the search block exists, otherwise return; */
    if (!jQuery('section.search').length) {
        return;
    }

    /* Begin the search when the user presses enter, or clicks on the search CTA */
    jQuery('input.search-input').keyup(function (e) {
        
        /* keyCode 13 is the enter key */
        if (e.keyCode == 13) {
           e.preventDefault();
           e.target.blur();
           let $search = jQuery(this).val();
           search($search);
        }

     });
     
     /* Search when the user clicks on the search-cta */
     jQuery('.search-cta a').click(function (e) {
        
           let $search = jQuery('input.search-input').val();
           search($search);

     });
    
});


function search($search) {

    /* set loading state */
    jQuery('section.search').addClass('loading');

    /* Add $search to the url without reloading the page */
    let urlPath = window.location.href.split('?')[0] + '?search=' + $search;
    window.history.pushState("", "", urlPath);
    
    /* Pass the filter values to the ajax function, and reload the items */
    var data = {
        'action': 'ajax_search',
        'search': $search,
    };

    /* Get html through ajax */
    jQuery.post(theme.ajax_url, data, function (response) {
        
        /* If there is no response, something went bad */
        if (!response) { 
            jQuery('section.search .search-notice p.notice').html('Er is iets fout gegaan, probeer het later opnieuw.'); 
            return;
        }
            
        /* Set results HTML */
        jQuery('section.search .results-container').html(response.html);
        jQuery('section.search .search-notice p.notice').html(response.notice);
            
        /* Remove loading state */
        jQuery('section.search').removeClass('loading');

    });

}