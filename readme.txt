=== Feedback Widget for Snootl ===
Contributors: justdevstudio, justDev, snootl
Donate link: https://justdev.org
Tags: feedback, widget, support, customer feedback, helpdesk
Requires at least: 5.0
Requires PHP: 7.2
Tested up to: 6.9
Stable tag: 1.1.0
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

A modern WordPress plugin to integrate the Feedback Widget for Snootl into your website.

== Description ==

Snootl allows you to easily integrate a feedback widget into your WordPress site.

You can control widget visibility based on user roles, display it only to logged-in users, or make it available to all visitors.

== Installation ==

1. Upload the plugin folder to the `/wp-content/plugins/` directory.
2. Activate the plugin through the **Plugins** menu in WordPress.
3. Go to **Settings > Snootl** and enter your API key.

== Frequently Asked Questions ==

= How do I get an API key? =

You can obtain your API key from your Snootl dashboard:  
https://app.snootl.com/dashboard

== External services ==

This plugin connects to Snootl services to provide feedback collection functionality.

1. API validation  
The plugin sends the API key entered by the site administrator to:  
https://app.snootl.com/api/widgets/{API_KEY}

This request is used to validate the API key and retrieve widget configuration.  
No personal data is sent during this request, only the API key provided by the administrator.

2. Frontend widget script  
When the widget is enabled, the plugin loads a JavaScript file from:  
https://cdn.snootl.com/scripts/widget/snootl-widget.esm.js

This script is required to display the feedback widget on the website.

When visitors interact with the widget, the Snootl service may process:
- Feedback content submitted by the user  
- Technical data such as browser type, device, and page URL  
- Interaction metadata such as timestamps and actions  

This data is used solely to provide and improve the service.

Service provider: Snootl  
Terms of Service: https://snootl.com/terms-of-service/  
Privacy Policy: https://snootl.com/privacy-policy/

== Screenshots ==

1. Snootl settings page.

== Changelog ==

= 1.1.0 =
* Added an option to show the Feedback Widget for Snootl inside the WordPress admin.
* The admin widget uses the same API key as the frontend widget.
* Updated asset versions for the minor release.

= 1.0.0 =
* Initial release
* API key validation with AJAX feedback
* Modular architecture (PSR-4)
* Visibility controls (roles, logged-in users, all users)
* Security and WordPress compliance improvements
