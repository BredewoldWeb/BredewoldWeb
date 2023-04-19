<?php

$labels = array(
      "name" => __("Template", ""),
      "singular_name" => __("Template", ""),
      'menu_name' => 'Templates',
      "add_new" => 'Template toevoegen',
      "add_new_item" => __("Template toevoegen", ""),
      "edit_item" => __("Bewerk template", ""),
      "new_item" => __("Nieuw template", ""),
      "view_item" => __("Template bekijken", ""),
      "view_items" => __("Bekijk Templates", ""),
      "archives" => __("Templates", ""),
   );

register_post_type(
    "bredewold_template",
    array(
         "label" => __("Template", ""),
         "labels" => $labels,
         "description" => "",
         "public" => false,
         "publicly_queryable" => false,
         "show_ui" => true,
         "show_in_rest" => true,
         "rest_base" => "",
         "has_archive" => false,
         "show_in_menu" => true,
         "exclude_from_search" => true,
         "capability_type" => "post",
         "map_meta_cap" => true,
         "hierarchical" => false,
         "rewrite" => '',
         "query_var" => true,
         "menu_icon" => "dashicons-update",
         "supports" => array("title", "excerpt", "editor")
      )
);


add_action('add_meta_boxes', function () {
    add_meta_box('bredewold_template', 'Templates', 'bredewold_template_meta_box', 'page', 'side', 'high');
    add_meta_box('bredewold_template', 'Templates', 'bredewold_template_meta_box', 'post', 'side', 'high');
});

add_filter('default_excerpt', 'bredewold_load_template', 10, 2);

function bredewold_template_meta_box()
{
    $id = get_the_id();

    $templates = get_posts(array('post_type' => 'bredewold_template')); ?>
  <div style="text-align: right">
     <?php if ($templates) { ?>
        <select class="bredewold-templates" style="width: 100%; box-sizing: border-box; margin-top: 6px;">
           <?php foreach ($templates as $template) { ?>
              <option value="<?= $template->ID ?>"><?= $template->post_title; ?></option>
           <?php } ?>
        </select>
     <?php } ?>
     <input class="button-primary load-template" style="margin-top: 10px;" type="button" value="Template inladen" />
  </div>
  <script>
  jQuery(document).ready(function() {
     jQuery(".load-template").click(function(e) {
        var template_id = jQuery(".bredewold-templates option:selected").val();
        window.location = 'post-new.php?post_id=<?= $id; ?>&bredewold_template=' + template_id;
     });
  });
  </script>
  <?php
}

function bredewold_load_template($excerpt, $post)
{
    global $editing;
    if (! current_user_can('edit_posts') || $editing !== true) {
        return $excerpt;
    }

    $post_type = $post->post_type;

    // Load the template
    $template = false;
    if (isset($_REQUEST['bredewold_template']) && is_numeric($_REQUEST['bredewold_template'])) {
        $template = get_post($_REQUEST['bredewold_template']);
    }

    // Only proceed if we have a template
    if ($template) {
        ob_start();

        if (isset($_REQUEST['post_id'])) {
            $post_id = $_REQUEST['post_id'];
        } else {
            $post_id = $post->ID;
        }

        if ($post_id > 0) {
            $target_post = get_post($post_id);
        }

        $properties = get_object_vars($template);
        $excluded_properties = array(
         'ID',
         'post_title',
         'post_author',
         'post_modified',
         'post_modified_gmt',
         'post_name',
         'guid',
         'post_status',
         'post_date',
         'post_date_gmt',
         'post_type',
         'post_status',
         'post_parent',
         'menu_order',
      );

        $temp_post = array();
        $temp_post['ID'] = $post_id;
        $temp_post['post_status'] = "draft";
        //$empty_only_properties = apply_filters('act_protected_properties', array() );

        foreach ($properties as $property => $value) {
            if (! in_array($property, $excluded_properties)) {

            //$value = apply_filters('act_template_property', $value, $property);
                $temp_post[$property] = iconv(mb_detect_encoding($value), "UTF-8//IGNORE", $value);
            }
        }

        // Dynamic Fields
        $dynamic_fields = array('post_content','post_excerpt');

        foreach ($dynamic_fields as $df) {
            ob_start();
            eval('?>'. html_entity_decode($temp_post[$df]));
            $new = ob_get_clean();
            $temp_post[$df] = addslashes(iconv(mb_detect_encoding($new), "UTF-8//IGNORE", $new));
        }

        wp_update_post($temp_post);

        /* Add template information to the new post */
        $t_l = get_post_meta($_REQUEST['bredewold_template'], 'template_locked', true);
        $t = get_post_meta($_REQUEST['bredewold_template'], 'template', true);
        update_post_meta($post_id, 'template_locked', $t_l);
        update_post_meta($post_id, 'template', $t);

        ob_end_clean();

        wp_redirect(get_edit_post_link($post_id, ''));

        exit();
    }

    return $excerpt;
}
