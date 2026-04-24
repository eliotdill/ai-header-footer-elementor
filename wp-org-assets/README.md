# WordPress.org SVN Assets

These files are uploaded to the WordPress.org SVN `assets/` folder AFTER
the plugin is approved. They are NOT included in the plugin zip.

## Required files to place here before SVN upload:

| File | Size | Purpose |
|------|------|---------|
| banner-1544x500.png | 1544x500 px | Plugin directory page banner (required) |
| banner-772x250.png | 772x250 px | Mobile banner (recommended) |
| icon-256x256.png | 256x256 px | Plugin icon in search results (required) |
| icon-128x128.png | 128x128 px | Small icon fallback (recommended) |
| icon.svg | square viewBox | Animated icon — overrides PNG when present |
| screenshot-1.png | any | Plugin home screen (3-card landing) |
| screenshot-2.png | any | Template Manager with header/footer set |
| screenshot-3.png | any | Live site with header/footer active |

## How to upload after approval:

1. Check out your plugin's SVN repo:
   svn co https://plugins.svn.wordpress.org/ai-header-footer-elementor .

2. Copy these files into the `assets/` folder of the SVN checkout.

3. svn add assets/*
   svn ci -m "Add plugin assets"
