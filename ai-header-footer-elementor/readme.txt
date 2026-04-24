=== AI Header & Footer for Elementor by PressMeGPT ===
Contributors: pressmegpt
Tags: elementor, header, footer, template, page-builder
Requires at least: 5.9
Tested up to: 6.8
Requires PHP: 7.4
Stable tag: 1.0.0
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Set any Elementor template as your site's global header and footer — upload a JSON file or choose from your existing templates. Works with Elementor Free.

== Description ==

**AI Header & Footer for Elementor** is a free helper plugin for [PressMeGPT.com](https://www.pressmegpt.com) — the AI-powered WordPress theme generator and website builder. It lets you apply a global header and footer to your Elementor-built site without needing Elementor Pro.

When you generate a website with PressMeGPT, your exported theme includes Elementor JSON files for the header and footer. This plugin lets you upload those files and set them as the sitewide header and footer — displayed on every page, in both the browser and the Elementor editor.

**Key Features:**

* Upload an Elementor JSON export and instantly set it as your global header or footer
* Browse and select from templates already in your Elementor library
* Edit your header or footer directly in the Elementor editor at any time
* Works with Elementor Free — no Elementor Pro required
* Full-width rendering on both regular WordPress pages and Elementor canvas pages
* Mobile hamburger menu support included in PressMeGPT exports
* Remove and restore your theme's default header/footer at any time
* Built to be extended — new content types (sidebar, copyright bar, etc.) can be added in a future release

**How It Works With PressMeGPT:**

1. Go to [PressMeGPT.com](https://www.pressmegpt.com) and generate or convert a website to a WordPress theme
2. Export your theme — the download includes Elementor JSON files for the header and footer
3. Install this plugin on your WordPress site
4. Upload the header and footer JSON files in the Template Manager
5. Your site instantly displays the AI-generated header and footer on every page

**Works Independently Too:**

You don't need a PressMeGPT account to use this plugin. Any Elementor template exported from Elementor's built-in export tool can be uploaded and set as your header or footer.

**Requirements:**

* WordPress 5.9 or higher
* PHP 7.4 or higher
* [Elementor](https://wordpress.org/plugins/elementor/) (free version) — must be installed and active

== Installation ==

1. Upload the `ai-header-footer-elementor` folder to the `/wp-content/plugins/` directory, or install directly from the WordPress plugin directory.
2. Activate the plugin through the **Plugins** screen in WordPress.
3. Make sure Elementor is installed and active.
4. Go to **AI Header & Footer** in the WordPress admin sidebar.
5. Click **Get Started** to open the Template Manager.
6. Upload an Elementor JSON file for your header and/or footer, or select an existing Elementor library template.
7. Click **Set as Header** or **Set as Footer** — your templates are now active sitewide.

== Frequently Asked Questions ==

= Do I need Elementor Pro? =

No. This plugin works with the free version of Elementor.

= Where do I get a header or footer JSON file? =

You can generate a complete website — including header and footer templates — at [PressMeGPT.com](https://www.pressmegpt.com). You can also export any template from Elementor's built-in **My Templates** library.

= Will this work with my existing theme? =

Yes. The plugin replaces your theme's default header and footer output using WordPress's standard hooks, so it works with virtually any WordPress theme.

= Can I edit the header or footer after setting it? =

Yes. Once a template is set, an **Edit with Elementor** button appears in the Template Manager. Clicking it opens the template in the Elementor editor where you can make changes and publish.

= What happens if I remove a template? =

Your theme's default header or footer is restored immediately.

= Can I use my own Elementor templates? =

Yes. Any template in your Elementor library can be set as the header or footer. Use the **Browse Templates** tab in the Template Manager to select one.

= Does this work inside the Elementor editor? =

Yes. When you edit a page with Elementor, your header and footer templates are displayed in the editor canvas at full width, just like they appear on the live site.

== Screenshots ==

1. Plugin home screen — three clean cards for quick access to the template manager and PressMeGPT services.
2. Template Manager — set a header and footer by uploading a JSON file or selecting from your Elementor library.
3. Active header and footer displayed full-width on an Elementor page.

== Changelog ==

= 1.0.0 =
* Initial release
* Upload Elementor JSON file to set as sitewide header or footer
* Browse and select from existing Elementor library templates
* Edit active templates directly in the Elementor editor
* Full-width rendering on standard theme pages and Elementor canvas pages
* Mobile hamburger menu support for PressMeGPT-generated headers
* Font Awesome enqueued automatically for icon-based templates
* Auto-migrates template type to Elementor canvas mode for correct editor display
* Dynamic content type registry — extensible for future content types

== Upgrade Notice ==

= 1.0.0 =
Initial release.
