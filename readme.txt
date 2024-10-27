=== WP ADA ===
Contributors: Dianakc
Donate link: http://arquivo.tk/dev/ada
Tags: post types, animals, adoption, pets, ngo, widgets
Requires at least: 3.0
Tested up to: 3.3
Stable Tag: 1.8

ADA plugin was designed for small or local websites on lost animals and animal adoption projects.

== Description ==

The ADA Plugin, offers an easy way to keep a database with specific information for every pet for adoption or lost pet.

* Auto-tag status in thumbnails - Through CSS, the status is displayed as a tag placed on every thumbnail.
* Change status, not permalinks - By using posts tags, you can change status any time, without changing the post permalink.
* Every pet is a fully featured post - You have feed, comments, can add images, videos, everything you need just like a normal post.
* Create your own statuses - Beside native Lost, Found etc you can create your own statuses.
* Special information are kept on every post - Data such size, breed, colors etc are kept individually on every post.
* Widgets for display - Just drop the ADA widgets for display a random available pet for adoption, search form, lost pet and pet color tagcloud.
* Works with any theme - Add the code for display the special data in pet posts or create a theme template file.
* Output uses style sheet - You don't need to dive into codes for customize colors and fonts, change everything within the stylesheet.
* Export and Import - ADA Plugin uses post type, a WordPress feature, so you can export and import pets posts whenever you need.
* Contextual Help - Are you lost? Click the Help tab when editing or adding a post type for a quick guide.
* Fully localized - Help on adding new languages too!
* Thumbs and special data on feeds - Pet feed shows thumbnail and special data.


== Installation ==

1. Unzip and upload ADA Plugin folder to the `/wp-content/plugins/` directory
1. Activate the plugin.
1. Add some Types, Colors, Status and State (Federal Location).
1. Two new panels appears for add posts types. Chose an option and add a post. Click the ADA Plugin Panel for usage.
1. Place `<?php if(function_exists('ada_pet')) { ada_pet(); } ?>` in your templates wherever the single post, archive etc context is used.
Best place is below `the_title()`, but you can place it anywhere within the loop.
1. Add a widget or menu item for list/display the item.

== Frequently Asked Questions ==

= I found that permalinks for pets are not working or what?! =
Right after install ADA, go to Settings > Permalinks. You do not need to change anything, just loading this page will reload the permalink structure for the new posts types.

= How to display the especial info in single posts? =
Place `<?php if(function_exists('ada_pet')) { ada_pet(); } ?>` in your templates wherever the single post, archive etc context is used.
Best place is below `the_title()`, but you can place it anywhere within the loop.

= How to display pets for adoption/lost pets? =
ADA Plugin uses post types for keeping everything in their place. You can add widgets or menu itens for pets available, adopted pets, lost pets and so on.

= What are the template files I can create for my theme when using ADA Plugin? =
The following files will be used if existing in your theme directory:
single-pet.php, taxonomy-status.php, taxonomy-colors.php, archive-pet.php etc.
More info: http://codex.wordpress.org/Template_Hierarchy

= How to change the output style and colors? =
By editing `wp-content/plugins/wp-ada/creation/presentation/ada-styles.css`.

= When adding a pet/lost pet, how to hide contact info if pet was found/adopted? =
You don't need to. Just change pet status do `Found` or `Adopted` and the contact info will not be shown.
Please note: statuses you have created will not work with this.

= Can I add any statuses for lost pets? =
Yes. Although you can't add status when editing, you can do it in Status sub-panel screen. Also, you can style the tags editing
`wp-content/plugins/wp-ada/creation/presentation/ada-styles.css`. Note that some special features will not work,
i.e. hide extra info for 'adopted' status.
Also, you can create your own tag image, named tag-(your-status-slug).

= The tag image is not showing for status ''

= How to donate? =
If I saved you some time, send any amount to my PayPal at dianakac[at]gmail.com.

== Screenshots ==
1. Pet for adoption single post.
1. Widget displaying animals for adoption (all types and statuses)
1. Editing screen
1. Widgets

== Changelog ==

= 1.8 =
* Removed status creation on plugin activation. This will let you create any status you want. Note that some features such hide extra info for adopted/found pets still works.
* Fixed an issue when generating feeds for posts and pet post types.
* Added user post count on Users sub-panel. Now you can know how many posts, pages, pets etc was created by every user.
* Cleaned ou the pet-data.php file. This file handles how pet info looks. Now the tag image is generated by tag-(you status-slug) instead.
* Added new widget: Pet Categories lists with post number.

= 1.7 =
* Added form for posts or pages.

= 1.6 =
* Solved issue on listing all pets.

= 1.5 =
* Updated metabox script
* Removed `lost` post type. Now ADA keep evertyhing under a single post type called `pet`.
* Status and Types are created freely but not in editing screen, what is good for contributors-driven sites.
* Added State (taxonomy) and City (metadata), allow search/browse pets in specific cities/state if need.
* Added Reference and Fee datas.
* Merged metaboxes for an universal database.
* Contributors and up only can see their own posts in editing screens.
* Widgets display post count in both Type and Status filter.

= 1.4 =
* Distinct labels in Menu creation for Pet Status/Lost Status, Pet Types/Lost Types
* Solved an issue when browsing pets types, statuses and colors

= 1.3 =
* Working localization

= 1.2 =
* Better localization
* Thumbnail and special info now in feeds!

= 1.0 =
* ADA Plugin launched.

== Read me now! ==
ADA Plugin is a free plugin but I discourage pet shops for using it and I will not provide support for such.
Also, I want you ask you never buy animals as a pet. There are plenty of animals in shelters you can adopt, stop
keeping this obsolete market alive.
