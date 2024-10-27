<div id="ada-form">

<?php if(is_user_logged_in()): ?>  <!-- Check if is logged -->
    <div id="add-job">

    <form id="new_post" name="new_post" method="post" action="new_pet" class="wpcf7-form" enctype="multipart/form-data"> <!-- Form starts -->


        <fieldset name="pet-info">
        <h2><?php _e('Animal Informations', 'wp-ada'); ?></h2>
        <p><?php _e('<strong>Animal Informations</strong> - most important informations about a pet. Here you inform the type, status, size etc.', 'wp-ada'); ?></p>


				  <label for="title"><?php _e('Post Title - fill with the animal name', 'wp-ada'); ?></label><br />
				  <input type="text" id="title" value="<?php _e('Your pet\'s name...', 'wp-ada'); ?>" tabindex="6" name="title" /><br />

          <br /><label for="description"><?php _e('Text Area - Add extended info, pictures, videos, links anything else about the animal.', 'wp-ada'); ?></label>
          <textarea id="description" tabindex="7" name="description" cols="80" rows="10"></textarea><br /><br />

    		  <label for="pet-type"><?php _e('Type', 'wp-ada'); ?></label>
    			<?php wp_dropdown_categories( 'tab_index=8&taxonomy=types&hide_empty=0' ); ?>

          <label for="pet-status"><?php _e('Status', 'wp-ada'); ?></label>
          <select name="pet_status" id="pet_status" tabindex="9" >
            <?php
              $terms = get_terms('status', array('hide_empty' => 0));
              foreach ($terms as $term) {echo "<option id='pet_color' value='$term->slug'>$term->name</option>"; }
              ?>
          </select><br />

          <br /><label for="petgender"><?php _e('Gender', 'wp-ada'); ?></label>
            <input type="radio" tabindex="17"  name="petgender"  value="<?php _e('Male', 'wp-ada'); ?>" checked="checked"/><span class="petgender"><?php _e('Male', 'wp-ada'); ?></span>
            <input type="radio" tabindex="18"  name="petgender"  value="<?php _e('Female', 'wp-ada'); ?>" /><span class="petgender"><?php _e('Female', 'wp-ada'); ?></span>
            <input type="radio" tabindex="19" name="petgender"  value="<?php _e('Various', 'wp-ada'); ?>" /><span class="petgender"><?php _e('Various', 'wp-ada'); ?></span><br />

          <br /><label for="petage"><?php _e('Age', 'wp-ada'); ?></label>
            <select tabindex="20" name="petage" id="petage">
             <option value="<?php _e('Baby (Under 1 year)', 'wp-ada'); ?>"><?php _e('Baby (Under 1 year)', 'wp-ada'); ?></option>
             <option value="<?php _e('Adult (2 to 9 years)', 'wp-ada'); ?>"><?php _e('Adult (2 to 9 years)', 'wp-ada'); ?></option>
             <option value="<?php _e('Senior (More than 10 years)', 'wp-ada'); ?>"><?php _e('Senior (More than 10 years)', 'wp-ada'); ?></option>
            </select><br />

          <br /><label for="petbreed"><?php _e('Breed(s)', 'wp-ada'); ?></label>
            <input type="text" value="" id="petbreed" tabindex="21" name="petbreed" style="text-transform:capitalize"/>
            <p class="field-note"><?php _e('One or more breeds separated by commas. Example: Poodle, Unknown', 'wp-ada'); ?></p>

          <label for="petvac"><?php _e('Vaccines', 'wp-ada'); ?></label>
            <input type="radio" tabindex="22"  name="petvac"  value="<?php _e('None', 'wp-ada'); ?>" checked="checked"/><span class="petvac"><?php _e('None', 'wp-ada'); ?></span>
            <input type="radio" tabindex="23"  name="petvac"  value="<?php _e('Unknown', 'wp-ada'); ?>" /><span class="petvac"><?php _e('Unknown', 'wp-ada'); ?></span>
            <input type="radio" tabindex="24" name="petvac"  value="<?php _e('Vaccinated', 'wp-ada'); ?>" /><span class="petvac"><?php _e('Vaccinated', 'wp-ada'); ?></span>
            <input type="radio" tabindex="25" name="petvac"  value="<?php _e('Dose Interval', 'wp-ada'); ?>" /><span class="petvac"><?php _e('Dose Interval', 'wp-ada'); ?></span><br />

          <br /><label for="petsize"><?php _e('Size', 'wp-ada'); ?></label>
            <select tabindex="26" name="petsize" id="petsize">
             <option value="<?php _e('Newborn (Imprecise)', 'wp-ada'); ?>"><?php _e('Newborn (Imprecise)', 'wp-ada'); ?></option>
             <option value="<?php _e('Mini', 'wp-ada'); ?>"><?php _e('Mini', 'wp-ada'); ?></option>
             <option value="<?php _e('Small', 'wp-ada'); ?>"><?php _e('Small', 'wp-ada'); ?></option>
             <option value="<?php _e('Medium', 'wp-ada'); ?>"><?php _e('Medium', 'wp-ada'); ?></option>
             <option value="<?php _e('Large', 'wp-ada'); ?>"><?php _e('Large', 'wp-ada'); ?></option>
             <option value="<?php _e('Huge', 'wp-ada'); ?>"><?php _e('Huge', 'wp-ada'); ?></option>
            </select><br />

          <br /><label for="pethair"><?php _e('Hair', 'wp-ada'); ?></label>
            <select tabindex="27" name="pethair" id="pethair">
             <option value="<?php _e('None', 'wp-ada'); ?>"><?php _e('None', 'wp-ada'); ?></option>
             <option value="<?php _e('Short', 'wp-ada'); ?>"><?php _e('Short', 'wp-ada'); ?></option>
             <option value="<?php _e('Long', 'wp-ada'); ?>"><?php _e('Long', 'wp-ada'); ?></option>
             <option value="<?php _e('Mixed', 'wp-ada'); ?>"><?php _e('Mixed', 'wp-ada'); ?></option>
            </select><br />

          <br /><label for="petpatt"><?php _e('Pattern', 'wp-ada'); ?></label>
            <select tabindex="28" name="petpatt" id="petpatt">
             <option value="<?php _e('Solid', 'wp-ada'); ?>"><?php _e('Solid', 'wp-ada'); ?></option>
             <option value="<?php _e('Brindle', 'wp-ada'); ?>"><?php _e('Brindle', 'wp-ada'); ?></option>
             <option value="<?php _e('Patches', 'wp-ada'); ?>"><?php _e('Patches', 'wp-ada'); ?></option>
             <option value="<?php _e('Spotted', 'wp-ada'); ?>"><?php _e('Spotted', 'wp-ada'); ?></option>
            </select><br />

          <br /><label for="petcolor"><?php _e('Colors', 'wp-ada'); ?></label><br />
            <?php
              $terms = get_terms('colors', array('hide_empty' => 0));
              foreach ($terms as $term) {echo "<input type='checkbox'  id='post_tags' name='post_tags[]' value='$term->slug'>$term->name</option>&nbsp;"; }
            ?><br />

          <br /><label for="petnotes"><?php _e('Notes (optional)', 'wp-ada'); ?></label>
          <textarea id="petnotes" tabindex="30" name="petnotes" cols="80" rows="4"></textarea>
          <p class="field-note"><?php _e('Physical conditions, aspects or any other info', 'wp-ada'); ?></p>
        </fieldset>


        <fieldset name="lostpets">
        <h2><?php _e('Lost & Found', 'wp-ada'); ?></h2>
        <p><?php _e('<strong>Lost & Found</strong> - questions regarding lost and found pets.', 'wp-ada'); ?></p>

        <label for="petdate"><?php _e('Date', 'wp-ada'); ?></label><br />
          <input type="text" value="" id="petdate" tabindex="31" name="petdate" />
          <p class="field-note"><?php _e('Example:  01/05/2011', 'wp-ada'); ?></p>

        <label for="pettime"><?php _e('Time', 'wp-ada'); ?></label><br />
          <input type="text" value="" id="pettime" tabindex="32" name="pettime" />
          <p class="field-note"><?php _e('Example:  11:29 pm', 'wp-ada'); ?></p>

        <label for="petplace"><?php _e('Place', 'wp-ada'); ?></label><br />
          <input type="text" value="" id="petplace" tabindex="33" name="petplace" />
          <p class="field-note"><?php _e('Example: Lincoln Park', 'wp-ada'); ?></p>
  			</fieldset>


        <fieldset name="usercontact">
        <h2><?php _e('Contact Information', 'wp-ada'); ?></h2>
        <p><?php _e('<strong>Contact Information</strong> - if you run a site not involved in the process, you must inform where visitors should ask about this pet.', 'wp-ada'); ?></p>

        <label for="user-contact"><?php _e('Contact Information', 'wp-ada'); ?></label><br />
				<textarea id="user-contact" tabindex="2" name="user-contact" cols="80" rows="4"></textarea><br />

        <br /><label for="user-email"><?php _e('E-mail', 'wp-ada'); ?></label><br />
				  <input type="text" value="" id="user-email" tabindex="3" name="user-email" /><br />

        <br /><label for="pet-city"><?php _e('City', 'wp-ada'); ?></label><br />
				  <input type="text" value="" id="petcity" tabindex="4" name="petcity"  /><br /><br />

        <label for="pet_state"><?php _e('State', 'wp-ada'); ?></label>
        <select name="pet_state" id="pet_state" tabindex="5" >
           <?php
             $terms = get_terms('state', array('hide_empty' => 0));
             foreach ($terms as $term) { echo "<option value='$term->slug'" . selected($term->slug, true, false) . ">$term->name</option>"; }
           ?>
        </select>
        </fieldset>


        <fieldset name="submit">
          <p><?php _e('<strong>Warning:</strong> All These informations will be visible in the website. Be careful sharing personal information such e-mails and addresses.', 'wp-ada'); ?></p>
  				<input type="submit" value="<?php _e('Submit'); ?>" tabindex="40" id="submit" name="submit" />
  			</fieldset>

		  	<input type="hidden" name="action" value="new_post" />

        <?php wp_nonce_field('new_pet'); ?>


</form> <!-- Form ends -->

    </div>

<?php else: ?>
    <div id="ada-login">
    <?php wp_login_form(array( 'value_remember'=> true )); ?>
    </div>
<?php endif; ?>

      </div>
