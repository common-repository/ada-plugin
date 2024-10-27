
<div id="content">
	<div class="ada-admin">
		<div class="wrap">

    <img src="<?php echo WP_PLUGIN_URL .'/'. WPADA_DIR_NAME. '/control/logo.png' ;?>" alt="logo" />

      <h2><?php _e('About ADA Plugin','wp-ada');?></h2>
				<div id="about">
					<p><?php _e('ADA plugin was designed for small or local websites on animal adoption projects.','wp-ada');?></p>
          <p><?php _e('The ADA Plugin, offers a neat and easy way to keep an database with specific information for every pet for adoption or lost pet.','wp-ada');?></p>
					<ul>
						<li><?php _e('Every pet is a fully featured post - You have permalinks, comments, can add images, videos, everything you need.','wp-ada');?></li>
						<li><?php _e('Special informations are kept on every post - Data such size, breed, colors etc are kept individually on every post.','wp-ada');?></li>
						<li><?php _e('Widgets for display - There are four ADA widgets for display pets, with different options, a search form and a pet color tagcloud.','wp-ada');?></li>
						<li><?php _e('Works with any theme - Add the code for display data in pet posts or create a template file.','wp-ada');?></li>
						<li><?php _e('Output uses stylesheet - You don\'t need to dive into codes for customize colors and fonts, you change everything within the stylesheet.','wp-ada');?></li>
            <li><?php _e('Export and Import - ADA Plugin uses post type, a WordPress feature, so you can export and import pets posts whenever you need','wp-ada');?></li>
            <li><?php _e('Special info and thumbnail in feeds - Feeds from pet categories display thumbnails and the special info.','wp-ada');?></li>
            <li><?php _e('Form for post or page - Let registered users post through your site by adding the form shortcode in post or page.','wp-ada');?></li>
					</ul>
				</div>

				<div id="how">
        <h3><?php _e('WordPress Features','wp-ada');?></h3>
				<p><?php _e('ADA rely on native WordPress features such post types and taxonomies, the following info will be useful if you want style every context when displaying different statuses, pets etc by using theme files.','wp-ada');?>
        <?php _e('Refer to <a href="http://codex.wordpress.org/Template_Hierarchy">Template Hierarchy</a>','wp-ada');?></p>

        <p><?php _e('<strong>Please note:</strong> you must add the <a href="edit-tags.php?taxonomy=types&post_type=pet">Types</a>, <a href="edit-tags.php?taxonomy=status&post_type=pet">Status</a>, <a href="edit-tags.php?taxonomy=colors&post_type=pet">Colors</a> and <a href="edit-tags.php?taxonomy=st&post_type=pet">States</a> in order to make them available in editing screen.','wp-ada');?></p>

        <h4><?php _e('Taxonomies','wp-ada');?></h4>
        <p><?php _e('ADA uses taxonomies for sort and organize pet post. The following taxonomies are created:','wp-ada');?></p>
        <ul>
        <li><?php _e('Types - Animal types. Example: Dogs, Cats, etc','wp-ada');?>. <?php _e('Theme file:','wp-ada');?><code>taxonomy-types.php</code></li>
        <li><?php _e('Colors - Animal colors. Example: White, Black, etc','wp-ada');?>. <?php _e('Theme file:','wp-ada');?><code>taxonomy-colors.php</code></li>
        <li><?php _e('Status - Animal status. Example: Adopt, Adopted, Found, Lost, Deceased etc','wp-ada');?><?php _e('Theme file:','wp-ada');?><code>taxonomy-status.php</code></li>
        <li><?php _e('State - Animal location. Example: IL, TX etc','wp-ada');?>. <?php _e('Theme file:','wp-ada');?><code>taxonomy-state.php</code></li>
        </ul>

        <h4><?php _e('Post Type','wp-ada');?></h4>
        <ul>
        <li><?php _e('Single post:','wp-ada');?><code>single-pet.php</code></li>
        <li><?php _e('Listing all pets:','wp-ada');?><code>archive-pet.php</code></li>
        </ul>

        <h4><?php _e('Metadatas','wp-ada');?></h4>
        <p><?php _e('Information like age, breed, size etc are stored as metadatas. Please, refer to following file:','wp-ada');?></p>
        <code><?php echo WP_PLUGIN_URL .'/'. WPADA_DIR_NAME. '/creation/data_pet.php' ;?></code>

        <h4>Feeds & RSS</h4>
        <p><?php _e('Here are some examples you can implement:','wp-ada');?></p>

        <ul>
        <li><code><?php echo bloginfo( 'url' ); ?>/?feed=rss2&post_type=pet</code> - <?php _e('all pets','wp-ada');?></li>
        <li><code><?php echo bloginfo( 'url' ); ?>/?feed=rss2&status=found</code> - <?php _e('only found status','wp-ada');?></li>
        <li><code><?php echo bloginfo( 'url' ); ?>/?feed=rss2&types=dogs</code> - <?php _e('only dogs','wp-ada');?></li>
        <li><code><?php echo bloginfo( 'url' ); ?>/?feed=rss2&types=dogs&status=found</code> - <?php _e('only dogs with found status','wp-ada');?></li>
        </ul>

				</div>

				<div id="display">
        <h3><?php _e('How to Display','wp-ada');?></h3>
				<p><?php _e('ADA Plugin works with any theme, just add the code in your single post context file or create a template file for you theme (refer to the previous section).','wp-ada');?></p>
        <p><?php _e('Add the following code in <code>single.php</code>, <code>archive.php</code> files etc anywhere inside the loop. The beste place is after <code>the_title</code>','wp-ada');?></p>
        <p><span ><code>&lt;?php if(function_exists('ada_pet')) { ada_pet(); } ?&gt;</code></span></p>
        <p><?php _e('This code will get the pet thumbnail and special info. Also, will show differently in single and archive contexts. Bellow, where to insert the code in default themes:','wp-ada');?></p>

        <h4>Twenty Ten</h4>
        <ul>
        <li><code>loop-single.php</code> <?php _e('Line:');?>33</li>
        <li><code>loop.php</code> <?php _e('Line:');?>139</li>
        </ul>

        <h4>Twenty Eleven</h4>
        <ul>
        <li><code>content-single.php</code> <?php _e('Line:');?>23</li>
        <li><code>content.php</code> <?php _e('Line:');?>41</li>
        </ul>

        <h4><?php _e('Widgets');?></h4>
        <p><?php _e('Add <a href="widgets.php">ADA Widgets</a> and display random pets for adoption, lost pets, pets search form and pet color tagcloud.','wp-ada');?></p>

        <h4><?php _e('Menu Itens');?></h4>
        <p>
        <?php _e('Add statuses, animal categories and more as <a href="nav-menus.php">Menus</a> itens.','wp-ada');?>
        <?php _e('For list all pets, add an URL as menu item in accordance with your permalink structure:','wp-ada');?>
        </p>

        <ul>
        <li><b><?php _e('Default') ;?>:</b> <code><?php echo bloginfo( 'url' ) ;?>/?post-type=pet</code></li>
        <li><b><?php _e('Custom Structure') ;?>:</b> <code><?php echo bloginfo( 'url' ) ;?>/pet</code></li>
        </ul>


        <h4><?php _e('Submission Form');?></h4>
        <p><?php _e('Now you can let registered users submit pet posts directly in your site. This form handles the same info as in ADA panel and also must be reviewed by an Admin/Editor before going online.','wp-ada');?></p>
        <p><?php _e('How to set the form:','wp-ada');?></p>

        <ul>
        <li><?php _e('Create a <a href="post-new.php?post_type=page">Page</a> or <a href="post-new.php?post_type=post">Post</a>.','wp-ada');?></li>
        <li><?php _e('Place the shortcode','wp-ada');?> <code>[ada]</code></li>
        </ul>

        <p><?php _e('<strong>Notes:</strong> the form always stay <strong>before</strong> the post or page content, no matter if you add the shortcode after or between some text, so is better to let only the shortcode in content.','wp-ada');?>
        <?php _e('A safer and good pratice is not to link the form page directly, but through another page, i.e. Agreement Policies etc.','wp-ada');?>
        <?php _e('Also consider using specific plugins for handle post creation and forms in order to get special features! This is quite a basic form only!','wp-ada');?>
        </p>

        <h4><?php _e('Styling');?></h4>
        <p><?php _e('You can change how widgets and special info look on you theme by editing ADA stylesheet','wp-ada');?>:<br /><code><?php echo WP_PLUGIN_URL .'/'. WPADA_DIR_NAME. '/creation/presentation/ada-styles.css' ;?></code></p>
        <p><?php _e('Also, all images are in this folder:','wp-ada');?>:<br /><code><?php echo WP_PLUGIN_URL .'/'. WPADA_DIR_NAME. '/creation/presentation/' ;?></code></p>
				</div>

        <h3><?php _e('Support & Contact','wp-ada');?></h3>
        <p><?php _e('Let me know what you have done! I will love to know how your site is using this plugin. Send me a <a href="mailto:dianakac@gmail.com?subject=About ADA Plugin">e-mail</a> anytime. You can also reach me at my <a href="http://arquivo.tk" _target="_blank" >website</a>','wp-ada');?></p>

        <p><?php _e('If I saved you sometime, consider make a donation.','wp-ada');?></p>

        <form action="https://www.paypal.com/cgi-bin/webscr" method="post">
        <input type="hidden" name="cmd" value="_donations">
        <input type="hidden" name="business" value="LQ79B3CWJ7CZG">
        <input type="hidden" name="lc" value="US">
        <input type="hidden" name="item_name" value="ADA Plugin">
        <input type="hidden" name="currency_code" value="USD">
        <input type="hidden" name="bn" value="PP-DonationsBF:btn_donate_LG.gif:NonHosted">
        <input type="image" src="<?php echo WP_PLUGIN_URL ."/".WPADA_DIR_NAME . '/control/paypal.png' ;?>" border="0" name="submit" alt="<?php _e('PayPal - The safer, easier way to pay online!','wp-ada');?>">
        <img alt="" border="0" src="https://www.paypalobjects.com/pt_BR/i/scr/pixel.gif" width="1" height="1">
        </form>

        <h3><?php _e('Translation','wp-ada');?></h3>
        <p><?php _e('This translation to','wp-ada');?> <?php echo get_locale();?> <?php _e('was made by <a href="mailto:dianakac@gmail.com?subject=ADA Plugin Translation">Diana K. Cury</a>','wp-ada');?>.</p>

        <h3><?php _e('Disclaimer','wp-ada');?></h3>
        <p><?php _e('ADA Plugin is a free plugin but I discourage pet shops for using it and I will not provide support for such. Also, I want you ask you never buy animals as a pet. There are plenty of animals in shelters you can adopt. Stop keeping this obsolete market alive.','wp-ada');?></p>

			<br class="clear">
		</div>
	
	
	</div>
</div>

