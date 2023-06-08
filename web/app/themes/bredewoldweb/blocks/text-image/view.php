<section class="text-image container">
   <div class="inner">

      <div class="text-image-wrapper <?= $fields['image_position']; ?>">

         <div class="text-container">
            <?= $fields['text']; ?>
         </div>

         <div class="spacer"></div>

         <picture class="image">
            <source srcset="<?= $fields['cropped']['desktop_webp']; ?>" type="image/webp"/>
            <img src="<?= $fields['cropped']['desktop']; ?>" alt="<?= $fields['image']['alt']; ?>"/>
         </picture>

      </div>
      
   </div>
</section>
