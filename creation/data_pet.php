<?php if (is_single() || is_feed()): ?>

          <?php if (is_preview()) :?>
          <div id="preview-pet">
          <?php _e('This pet post is still awaiting moderation, though you can add more information, edit or delete it.', 'wp-ada'); ?>
          <br /><?php edit_post_link( __( 'Edit this pet &raquo;', 'wp-ada' ), '<span class="edit">', '</span>' ); ?>
          </div>
          <?php endif; ?>

    <div class="petinfo">

      <?php  if (is_object_in_term($post->ID,'status',(__('Adopted','wp-ada'))) ) { ?>
      <div class="tag-adopted"><?php echo get_the_term_list( get_the_ID(), 'status' , " " ); ?></div>

      <?php } elseif (is_object_in_term($post->ID,'status',(__('Adopt','wp-ada'))) ) { ?>
      <div class="tag-adopt"><?php echo get_the_term_list( get_the_ID(), 'status' , " " ); ?></div>

      <?php } elseif (is_object_in_term($post->ID,'status',(__('Found','wp-ada'))) ) { ?>
      <div class="tag-found"><?php echo get_the_term_list( get_the_ID(), 'status' , " " ); ?></div>

      <?php } elseif (is_object_in_term($post->ID,'status',(__('Lost','wp-ada'))) ) { ?>
      <div class="tag-lost"><?php echo get_the_term_list( get_the_ID(), 'status' , " " ); ?></div>

      <?php } elseif (is_object_in_term($post->ID,'status',(__('Solved','wp-ada'))) ) { ?>
      <div class="tag-solved"><?php echo get_the_term_list( get_the_ID(), 'status' , " " ); ?></div>

      <?php } else  { ?>
      <div class="tag"><?php echo get_the_term_list( get_the_ID(), 'status' , " " ); ?></div>
      <?php } ?>


    <div class="picture-pet"><a href="<?php the_permalink() ?>" title="<?php the_title(); ?>"><?php the_post_thumbnail('ada_thumb'); ?></a></div>

    <ul class="left-data">
        <li><strong>Status</strong>:
        <?php if (is_object_in_term($post->ID,'status',(__('Adopt','wp-ada')))) : ?>
        <span class="icon-adopt"><?php _e('Adopt', 'wp-ada'); ?></span>

        <?php elseif (is_object_in_term($post->ID,'status',(__('Adopted','wp-ada')))): ?>
        <span class="icon-adopted"><?php _e('Adopted', 'wp-ada'); ?></span>

        <?php elseif (is_object_in_term($post->ID,'status',(__('Found','wp-ada')))) : ?>
        <span class="icon-found"><?php _e('Found', 'wp-ada'); ?></span>

        <?php elseif (is_object_in_term($post->ID,'status',(__('Lost','wp-ada')))): ?>
        <span class="icon-lost"><?php _e('Lost', 'wp-ada'); ?></span>

        <?php elseif (is_object_in_term($post->ID,'status',(__('Solved','wp-ada')))): ?>
        <span class="icon-solved"><?php _e('Solved', 'wp-ada'); ?></span>

        <?php else : ?>
        <?php echo get_the_term_list( get_the_ID(), 'status' , " " ) ?>

        <?php endif; ?>
        </li>

        <li><strong><?php _e('In', 'wp-ada'); ?></strong>: <?php echo get_the_term_list( get_the_ID(), 'types' , " " ) ?></li>
        <li><strong><?php _e('Breed(s)', 'wp-ada'); ?></strong>: <?php  echo get_post_meta($post->ID, 'dbt_breed','true'); ?></li>
        <li><strong><?php _e('Size', 'wp-ada'); ?></strong>: <?php  echo get_post_meta($post->ID, 'dbt_size','true'); ?></li>
        <li><strong><?php _e('Gender', 'wp-ada'); ?></strong>: <?php  echo get_post_meta($post->ID, 'dbt_gender','true'); ?></li>
        <li><strong><?php _e('Age', 'wp-ada'); ?></strong>: <?php  echo get_post_meta($post->ID, 'dbt_age','true'); ?></li>
    </ul>

    <ul class="right-data">
        <li><strong><?php _e('Vaccines', 'wp-ada'); ?></strong>: <?php  echo get_post_meta($post->ID, 'dbt_vac','true'); ?></li>
        <li><strong><?php _e('Hair', 'wp-ada'); ?></strong>: <?php  echo get_post_meta($post->ID, 'dbt_hair','true'); ?></li>
        <li><strong><?php _e('Colors', 'wp-ada'); ?></strong>: <?php echo get_the_term_list( get_the_ID(), 'colors' ,"", ",&nbsp;" ) ?></li>
        <li><strong><?php _e('Pattern', 'wp-ada'); ?></strong>: <?php  echo get_post_meta($post->ID, 'dbt_patt','true'); ?></li>
        <li><strong><?php _e('Added', 'wp-ada'); ?></strong>: <?php the_date(); ?> - <?php the_time(); ?></li>
        <li><strong><?php _e('Updated', 'wp-ada'); ?></strong>: <?php the_modified_date(); ?> - <?php the_modified_time(); ?></li>
    </ul>

    </div><div class="clear"></div>

<?php else: ?>

    <?php if (has_post_thumbnail()): ?>
      <?php  if (is_object_in_term($post->ID,'status',(__('Adopted','wp-ada'))) ) { ?>
      <div class="tag-adopted"><?php echo get_the_term_list( get_the_ID(), 'status' , " " ); ?></div>

      <?php } elseif (is_object_in_term($post->ID,'status',(__('Adopt','wp-ada'))) ) { ?>
      <div class="tag-adopt"><?php echo get_the_term_list( get_the_ID(), 'status' , " " ); ?></div>

      <?php } elseif (is_object_in_term($post->ID,'status',(__('Found','wp-ada'))) ) { ?>
      <div class="tag-found"><?php echo get_the_term_list( get_the_ID(), 'status' , " " ); ?></div>

      <?php } elseif (is_object_in_term($post->ID,'status',(__('Lost','wp-ada'))) ) { ?>
      <div class="tag-lost"><?php echo get_the_term_list( get_the_ID(), 'status' , " " ); ?></div>

      <?php } elseif (is_object_in_term($post->ID,'status',(__('Solved','wp-ada'))) ) { ?>
      <div class="tag-solved"><?php echo get_the_term_list( get_the_ID(), 'status' , " " ); ?></div>

      <?php } else  { ?>
      <div class="tag"><?php echo get_the_term_list( get_the_ID(), 'status' , " " ); ?></div>
      <?php } ?>

        <div class="picture-pet"><a href="<?php the_permalink() ?>" title="<?php the_title(); ?>"><?php the_post_thumbnail('ada_thumb'); ?></a></div>
    <?php endif; ?>

    <ul class="pet-data-list">
    <li><strong><?php _e('In', 'wp-ada'); ?>:</strong> <?php echo get_the_term_list( get_the_ID(), 'types' , "" ) ?></li>
    <li><strong><?php _e('Status', 'wp-ada'); ?>:</strong> <?php echo get_the_term_list( get_the_ID(), 'status' , " " ) ?></li>
    <li><strong><?php _e('Added', 'wp-ada'); ?></strong>: <?php the_date(); ?> - <?php the_time(); ?></li>
    <li><strong><?php _e('Updated', 'wp-ada'); ?></strong>: <?php the_modified_date(); ?> - <?php the_modified_time(); ?></li>
    </ul>

<?php endif; ?>

<?php if (is_single()): ?>
     <?php if (is_object_in_term($post->ID,'status',(__('Adopt','wp-ada'))) ) { ?>
       <?php $intro = get_post_meta($post->ID, "dbt_notes", true); if (!empty($intro)) echo "<h3>" . __('Notes & Info','wp-ada') . "</h3><p>$intro</p> "; ?>
       <?php $fee = get_post_meta($post->ID, "dbt_fee", true); if (!empty($fee)) echo "<p><strong>" . __('Fee','wp-ada') . ":</strong> $fee</p> "; ?>
       <p><?php $city = get_post_meta($post->ID, "dbt_city", true); if (!empty($city)) echo "<strong>" . __('Local','wp-ada') . ":</strong> $city - "; ?>
       <?php echo get_the_term_list( get_the_ID(), 'state' ,"", "" ) ?></p>


       <?php $contato = get_post_meta($post->ID, "dbt_contact", true); if (!empty($contato)) echo "<h3>" . __('Contacts','wp-ada') . "</h3><p>$contato</p> "; ?>
       <?php $mail = get_post_meta($post->ID, "dbt_mail", true); if (!empty($mail)) echo "<h3>E-mail</h3><p class= 'contact'><a href='mailto:$mail?subject=" . _x('I want adopt', 'e-mail subject', 'wp-ada') . "'>" . __('Contact by e-mail', 'wp-ada') . "</a></p> "; ?>



     <?php } ?>

      <?php if (is_object_in_term($post->ID,'status',(__('Lost','wp-ada')) ) || is_object_in_term($post->ID,'status',(__('Lost','wp-ada')) )) { ?>

     <?php $origem = get_post_meta($post->ID, "dbt_date", true); if (!empty($origem)) echo "<h3>" . __('Last place and time', 'wp-ada') . "</h3><p>" . "<p>$origem "; ?>
     <?php $time = get_post_meta($post->ID, "dbt_time", true); if (!empty($time)) echo "- $time - "; ?>
     <?php $place = get_post_meta($post->ID, "dbt_place", true); if (!empty($place)) echo "$place</p> "; ?>
     <p><?php $city = get_post_meta($post->ID, "dbt_city", true); if (!empty($city)) echo "$city - "; ?>
     <?php echo get_the_term_list( get_the_ID(), 'state' ,"", "" ) ?></p>

     <?php $contato = get_post_meta($post->ID, "dbt_contact", true); if (!empty($contato)) echo "<h3>" . __('Contacts','wp-ada') . "</h3><p>$contato</p> "; ?>
     <?php $mail = get_post_meta($post->ID, "dbt_mail", true); if (!empty($mail)) echo "<h3>E-mail</h3><p class= 'contact'><a href='mailto:$mail?subject=" . _x('Pet found', 'e-mail subject', 'wp-ada') . "'>" . __('Contact by e-mail', 'wp-ada') . "</a></p> "; ?>

     <?php } ?>

     <?php if (is_object_in_term($post->ID,'status',(__('Found','wp-ada')) ) || is_object_in_term($post->ID,'status',(__('Found','wp-ada')) )) { ?>

     <?php $origem = get_post_meta($post->ID, "dbt_date", true); if (!empty($origem)) echo "<h3>" . __('Last place and time', 'wp-ada') . "</h3><p>" . "<p>$origem "; ?>
     <?php $time = get_post_meta($post->ID, "dbt_time", true); if (!empty($time)) echo "- $time - "; ?>
     <?php $place = get_post_meta($post->ID, "dbt_place", true); if (!empty($place)) echo "$place</p> "; ?>
     <p><?php $city = get_post_meta($post->ID, "dbt_city", true); if (!empty($city)) echo "$city - "; ?>
     <?php echo get_the_term_list( get_the_ID(), 'state' ,"", "" ) ?></p>


     <?php $contato = get_post_meta($post->ID, "dbt_contact", true); if (!empty($contato)) echo "<h3>" . __('Contacts','wp-ada') . "</h3><p>$contato</p> "; ?>


     <?php $mail = get_post_meta($post->ID, "dbt_mail", true); if (!empty($mail)) echo "<h3>E-mail</h3><p class= 'contact'><a href='mailto:$mail?subject=" . _x('Pet found', 'e-mail subject', 'wp-ada') . "'>" . __('Contact by e-mail', 'wp-ada') . "</a></p> "; ?>

     <?php } ?>

<?php endif; ?>