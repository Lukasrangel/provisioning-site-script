<?php
/**
 * Title: Jack-in CTA
 * Slug: second-coming/cta-jack-in
 * Categories: second-coming
 * Description: Closing call to action. End most generated pages with this.
 * Keywords: cta, button, contact, jack in
 * Viewport Width: 1200
 */
?>
<!-- wp:group {"className":"sc-cta","layout":{"type":"constrained","contentSize":"640px"}} -->
<div class="wp-block-group sc-cta"><!-- wp:heading {"level":2,"textAlign":"center","className":"sc-section-title"} -->
<h2 class="wp-block-heading has-text-align-center sc-section-title">{{sc_cta_title}}</h2>
<!-- /wp:heading -->

<!-- wp:paragraph {"align":"center"} -->
<p class="has-text-align-center">{{sc_cta_body}}</p>
<!-- /wp:paragraph -->

<!-- wp:buttons {"layout":{"type":"flex","justifyContent":"center"}} -->
<div class="wp-block-buttons"><!-- wp:button {"className":"sc-cta-button is-style-outline"} -->
<div class="wp-block-button sc-cta-button is-style-outline"><a class="wp-block-button__link wp-element-button" href="{{sc_cta_url}}">{{sc_cta_label}}</a></div>
<!-- /wp:button --></div>
<!-- /wp:buttons --></div>
<!-- /wp:group -->
