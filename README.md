# DM Customisations

Modifications to WordPress's default behaviour to better suit our projects. Also adds some helpful functionality.

## Functionality

### Disable core functionality

- Disables comment/trackback functionality (Toggle with `DM_DISABLE_COMMENTS`; default true).
- Disables builtin search functionality (Toggle with `DM_DISABLE_SEARCH`; default false).
- Disables output of emoji styles/scripts (Toggle with `DM_DISABLE_EMOJIS`; default true) ☹️.
- Disables RSS feeds (Toggle with `DM_DISABLE_RSS`; default true).
- Prevents anonymous access to the REST API (Toggle with `DM_DISABLE_REST_ANON`; default true). By filtering dm_allowed_anonymous_restnamespaces individual namespaces can be whitelisted.
- Modifies plugin install screen to show our recommended plugins first (Toggle with `DM_MODIFY_PLUGINS_SCREEN`; default true).
- Removes XMLRPC functionality; X-Pingback headers; tidies up wp_head();

### Additional functionality

- Adds a 'Flush Cache' button to the admin bar when in /wp-admin/ which allows users to flush the object cache (By default this is visble to users with the Editor or Administrator role, you can override this by setting `DM_CACHEFLUSH_PERMS` to contain an array of the desired roles).	
- Tracks last login times for users accounts (Toggle with `DM_LASTLOGIN`; defaults to true).

### Third party plugin modifications

- Remove aggressive advertising in wp-admin from Yoast's WordPress SEO plugin when deleting posts or terms (Toggle with `DM_REMOVE_YOAST_ADS`; defaults to true).
- Attempts to move the Yoast WordPress SEO metabox to the bottom of the screen.
- Prevents GravityForms from storing entries in the database (use `DM_GFORM_DELETE` to toggle).

## Installation

Install via Composer (`composer require deliciousmedia/dm-customisations`), or just clone/copy the files to your mu-plugins folder.

---
Built by the team at [Delicious Media](https://www.deliciousmedia.co.uk/), a specialist WordPress development agency based in Sheffield, UK.