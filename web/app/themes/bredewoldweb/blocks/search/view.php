<section class="search container">
   <div class="inner-narrow">

      <h1>Zoeken</h1>

      <div class="filter-wrapper">
         <div class="big-search">

            <div class="search-bar">
               <div class="search-icon"><?= file_get_contents(get_template_directory() . '/blocks/search/img/search.svg'); ?></div>
               <input class="search-input" placeholder="Doorzoek de website" type="text" value="<?= $search; ?>" />
               <div class="status-indicator">
                  <div class="search-cta"><a class="cta cta-1">Zoek</a></div>
                  <div class="loading-icon"><img src="<?= get_template_directory_uri(); ?>/blocks/search/img/ajax-loader.gif" /></div>
               </div>
            </div>

         </div>
      </div>

      <div class="search-notice">
         <p class="notice medium"><?php if (!empty($notice)) {echo $notice;} ?></p>
      </div>

      <div class="results-wrapper">
         <div class="results-container">
            <?php
            if (!empty($search_results)) {
               include 'view-results.php';
            }
            ?>
         </div>
      </div>

   </div>
</section>