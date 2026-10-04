=== Second Coming ===
Contributors: tlloancy
Tags: blog, custom-menu, custom-logo, custom-background, translation-ready, full-site-editing, block-patterns
Requires at least: 6.0
Tested up to: 7.1
Requires PHP: 7.4
Stable tag: 1.4.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
Copyright 2025-2026 tlloancy
Second Coming WordPress Theme, (C) 2025-2026 tlloancy
Second Coming is distributed under the terms of the GNU GPL

Matrix-style Full Site Editing theme: cyberpunk neon, animated digital rain, and block patterns you can assemble into pages — by hand or from a sentence.

== Description ==
Second Coming is a cyberpunk Full Site Editing theme. Dark Matrix aesthetic, animated digital rain (blue/red pill), neon type, sticky header, and readable layouts for posts, pages and comments.

It is a standalone block theme (not a child). Patterns live under **Patterns → Second Coming**. On WordPress 7, **Settings → Connectors** can install the AI plugin and a provider; **Pages** then has Generate: describe a page, get a **draft** built only from those patterns. No API key in the theme. Without AI, the theme is unchanged.

Features:
- Animated digital rain (toggleable)
- Eight Matrix block patterns (hero, classified grid, stack, terminal, CTA, …)
- Optional Generate draft on Pages (connector / parse / create)
- Abilities `list-patterns` and `create-page` for the WP assistant / MCP
- Mobile-first navigation
- Full Site Editing templates

Longer walkthrough: `docs/USER-GUIDE.md` (English) and `docs/MODE-DEMPLOI.md` (French).

== Changelog ==
= 1.4.0 = (August 28, 2026)
* Patterns: Eight Second Coming sections (hero, signal, classified grid, stack, terminal, protocol, CTA, access denied) with replaceable copy tokens.
* AI: Optional Generate draft on Pages — phrase → allowlisted patterns → draft page. Needs the official AI plugin and a connector.
* AI: Abilities list-patterns and create-page (edit_pages, draft by default, mcp.public).
* Design: Pattern CSS (file cards, terminal dump, red warning). theme.json v3 palette + Inria Sans for the Site Editor.
* Docs: Click path for Settings → Connectors (in-dashboard AI plugin install, Enable AI, Connector Approvals). No wordpress.org/plugins/ai URL.
* Compatibility: Tested up to WordPress 7.1.

= 1.3.3 = (April 27, 2026)
* **CSS ARCHITECTURE**: Decoupled color logic from JavaScript. Colors are now controlled via CSS Variables (`--matrix-color`), making the theme "Update-Proof" for users.
* **FSE BLOCKS**: Migrated from shorthand placeholders to Full Block Markup for templates (index, page, single).
* **UI/UX**: Comments section now fully customizable via the Site Editor with granular block controls.

= 1.3.2 = (April 25, 2026)
📦 Release v1.3.2: Interactive Stealth Controls

- UI/UX: Added "White Rabbit" toggle for a discreet settings trigger.
- UI/UX: Added "Minimize" mobile-icon button to fold the settings panel.
- UI/UX: Implemented stealth-mode (trigger sits on the screen edge).
- LOGIC: Refactored pill-toggle.js for better state management between trigger and panel.
- CSS: Added neon styling and transitions for the rabbit-hole effect.

= 1.3.1 (April 24, 2026) =
* **I18N**: Fully localized all JavaScript strings via wp_localize_script for WordPress translation standards.
* **UI/UX UPGRADE**: Introduced the Matrix Settings Panel for real-time animation control.
* **ENGINE**: Implemented Custom Events for modular communication between the toggle and the rain renderer.
* **SECURITY**: Updated the reset logic to use selective localStorage pruning (prefix-based) to avoid data collisions.

= 1.3.0 =
* **THE PURGE**: Permanent removal of the legacy PHP architecture (header.php, footer.php, index.php, etc.).
* **FSE REVOLUTION**: Full migration to HTML-based templates and parts (templates/ and parts/).
* **SYS.LOGS_HISTORY**: Enhanced terminal-style sidebar with dynamic post injection.

= 1.2.3 =
* Improved mobile hamburger menu functionality
* Fixed digital rain visibility with semi-transparent content areas
* Added permanent blue/red pill toggle in footer.php
* Updated readme for Theme Check compliance
* Removed unnecessary inline styles

= 1.1.9 =
* Removed Leaflet map (plugin territory)
* Removed unused hero-bg.jpg
* Links in content now underlined
* Fixed all WordPress.org review issues

== Installation ==
1. Appearance → Themes → Add New → Upload the ZIP, or copy the folder to wp-content/themes/second-coming.
2. Activate Second Coming.
3. Optional (WordPress 7): Settings → Connectors → Install the AI plugin (notice on that screen) → install a connector and save the key → Settings → AI → Enable AI. Then Pages → Generate draft. If a request is blocked, Tools → Connector Approvals → approve Second Coming.

== FAQ ==
= Do I need AI for this theme? =
No. Rain, templates and patterns work without it.

= How do I connect AI? =
WordPress 7: **Settings → Connectors**. Use **Install the AI plugin** on that screen, install a connector, save the key, then **Settings → AI → Enable AI**. If Generate is blocked, **Tools → Connector Approvals** and approve Second Coming. No wordpress.org link required.

= Where is Generate? =
Pages (list and page editor). It creates a draft, never a live page by itself.

= Can a visitor generate pages? =
No. edit_pages only.

= Why not free HTML from the model? =
It would not stay Matrix. The theme concatenates its own patterns and fills copy tokens.

== Resources ==
* Inria Sans: SIL Open Font License, https://fonts.google.com/specimen/Inria+Sans
* Twitter SVG: MIT License, https://simpleicons.org/?q=twitter
* Digital rain effect inspired by Matrix, custom implementation
