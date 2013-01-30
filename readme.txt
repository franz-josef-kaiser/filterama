=== (WCM) Filterama ===
Contributors: F J Kaiser, userabuser, kai-ser
License: MIT
Tags: admin, post, list, filter, categories, tags, post-tags, taxonomy, taxonomies
Requires at least: 3.5
Tested up to: 3.5.1
Stable tag: 0.5

== Description ==

Adds one taxonomy filter/drop-down/select box for each taxonomy attached to a
(custom) post types list in the admin post list page. Also adds a "match" button
so you can now "filter" the list by ALL or ANY taxonomy terms.

= (WCM) WeCodeMore =

WeCodeMore is your label for high quality WordPress code from renowned authors.

If you want to get updates, just follow us on…

 * [our page on Google+](https://plus.google.com/b/109907580576615571040/109907580576615571040/posts)
 * [our GitHub repository](https://github.com/wecodemore)

== Installation ==

1. Upload the `filterama` folder to the `/wp-content/plugins/` directory
1. Activate the (WCM) Filterama plugin through the 'Plugins' menu in WordPress
1. Done

== Frequently Asked Questions ==

= Can I match by any term instead of all? =

Preparation for this feature is in production. Expect it with a future version.

== Screenshots ==

1. The Plugin in action on a custom post type

== Changelog ==

= 0.5 =

 * Fixed hierarchical display of hierarchical taxonomy select elements
 * Now uses a core function
 * Sorts elements by parent

= 0.4 =

 * Added l18n/translation loading
 * Added "match" button and behavior
 * Intercept on pre_get_posts to alter the tax query for "match"

= 0.3.4 =

 * Added base file
 * Smartened up loading logic

= 0.3.3 =

 * Split into separate files: bootstrap, filters, match
 * Wrapped up in several classes
 * Added Milestones & GitHub Issues for 0.4 & 0.5

= 0.3.2 =

 * Added "Match" button
 * Added "Match" query string filter
 * Added term ID fetching method

= 0.3.1 =

 * Decent optimisations

= 0.3 =

 * Repo ready
 * Added screenshot
 * Added readme.txt

== Upgrade Notice ==

 * Nothing. Things just work like a charm.