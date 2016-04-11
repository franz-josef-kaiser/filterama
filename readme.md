# WCM Filterama

> Adds one taxonomy filter/drop-down/select box for each taxonomy attached to a
(custom) post types list in the admin post list page. Also adds a "match" button
so you can now "filter" the list by ALL or ANY taxonomy terms.

## Translations

:speech_balloon: Available in English & German - for now. Translations welcome! 

We currently use _POEditor_, which is tightly integrated with _GitHub_, to lower 
the entry barrier for translating as much as possible.
[**Help us translate!**](https://poeditor.com/join/project/fwdDFCwQpn)
![Filterama on POEditor](http://i.imgur.com/JEr2hgo.png?1)

_Please just file an issue if your language is not on POEditor._

## Development

Development happens on GitHub. The Kanban board can be found on _Waffle_. 

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

## Security Issues

**Do not!** use the issue tracker if you find a security issue. Those are public. 
Please instead send an email to the address that you can read 
[on my profile](https://github.com/franz-josef-kaiser). Else people could use 
that exploit on your site as well. Thank you in advance.

## Screenshots

###1. The Plugin in action on a custom post type###
![The Plugin in action on a custom post type](https://raw.github.com/franz-josef-kaiser/filterama/master/screenshot-1.png)
