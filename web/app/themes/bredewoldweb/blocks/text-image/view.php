<section class="text-image container">
   <div class="inner">

      <div class="text-image-wrapper text-image-wrapper--<?= $fields['image_position']; ?>">

         <div class="text-container">
            <div class="tiny-content">
                <?= $fields['text']; ?>
             </div>
         </div>

         <div class="spacer"></div>

         <picture class="image">
            <img src="<?= $fields['image']['url']; ?>" />
         </picture>

      </div>
      
   </div>
</section>
