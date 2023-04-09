<?php
/* https://github.com/Log1x/acf-builder-cheatsheet */


$block->addRadio('image_position', [
  'label' => 'Positionering',
  'choices' => [
    'text-right' => 'Afbeelding | Tekst',
    'text-left' => 'Tekst | Afbeelding'
  ],
  'default_value' => ['text-right'],
  'wrapper' => [
    'width' => 100
  ]
])

->addField('image', 'image_aspect_ratio_crop', [
  'label' => 'Afbeelding',
  'crop_type' => 'aspect_ratio',
  'aspect_ratio_width' => '500',
  'aspect_ratio_height' => '500',
  'return_format' => 'array',
  'preview_size' => 'medium',
  'wrapper' => [
    'width' => 35
  ]
])

->addWysiwyg('Tekst', [
  'name' => 'text',
  'wrapper' => [
    'width' => 65
  ]
]);

