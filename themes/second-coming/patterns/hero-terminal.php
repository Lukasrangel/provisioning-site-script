<?php
/**
 * Title: Hero terminal
 * Slug: second-coming/hero-terminal
 * Categories: second-coming
 * Description: Full-width access node: kicker, headline, lede. Start most generated pages with this.
 * Keywords: hero, matrix, classified, access, headline
 * Viewport Width: 1200
 */
?>
<!-- wp:group {"className":"sc-hero","layout":{"type":"constrained","contentSize":"900px"}} -->
<div class="wp-block-group sc-hero"><!-- wp:paragraph {"className":"sc-kicker"} -->
<p class="sc-kicker">{{sc_kicker}}</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":1,"className":"sc-hero-title"} -->
<h1 class="wp-block-heading sc-hero-title">{{sc_headline}}</h1>
<!-- /wp:heading -->

<!-- wp:paragraph {"className":"sc-lede"} -->
<p class="sc-lede">{{sc_lede}}</p>
<!-- /wp:paragraph --></div>
<!-- /wp:group -->
