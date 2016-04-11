[![Stories in Ready](https://badge.waffle.io/franz-josef-kaiser/filterama.png?label=ready&title=Ready)](https://waffle.io/franz-josef-kaiser/filterama)
# WCM Filterama

> Adds one taxonomy filter/drop-down/select box for each taxonomy attached to a
(custom) post types list in the admin post list page. Also adds a "match" button
so you can now "filter" the list by ALL or ANY taxonomy terms.

## Translations

Available in English & German. Translations welcome. 
We currently use _POEditor_ which is tightly integrated with _GitHub_ to lower 
the entry barrier for translating as much as possible. 
[**Help us translating!**](https://poeditor.com/join/project/fwdDFCwQpn)

### (WCM) wecodemore

_wecodemore_ is your label for high quality WordPress code from renowned authors.

If you want to get updates, just follow us on…

 * [our Twitter account](https://twitter.com/wecodemore)
 * [our GitHub repository](https://github.com/wecodemore)

## Installation

#### Conventional / sFTP

1. Upload the `filterama` folder to the `/wp-content/plugins/` directory
1. Activate the (WCM) Filterama plugin through the 'Plugins' menu in WordPress
1. Done

## Frequently Asked Questions

#### Filterama doesn't work with my Custom Post Types!

Make sure that your Custom Post Type Taxonomy declares 
`show_admin_column => true` in its `$args` array. 
Filterama will only hook those taxonomies with `show_admin_column` set to true. 

## Screenshots

###1. The Plugin in action on a custom post type###
![The Plugin in action on a custom post type](https://raw.github.com/franz-josef-kaiser/filterama/master/screenshot-1.png)
