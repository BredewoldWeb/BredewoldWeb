<section class="block text-image">
   <div class="inner-container">

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
