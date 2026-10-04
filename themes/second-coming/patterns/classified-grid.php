<?php
/**
 * Title: Classified file grid
 * Slug: second-coming/classified-grid
 * Categories: second-coming
 * Description: Three file cards. Use for confidential dossiers, archives, case files.
 * Keywords: classified, files, grid, confidential, cards
 * Viewport Width: 1200
 */
?>
<!-- wp:group {"className":"sc-section","layout":{"type":"constrained"}} -->
<div class="wp-block-group sc-section"><!-- wp:heading {"level":2,"className":"sc-section-title"} -->
<h2 class="wp-block-heading sc-section-title">{{sc_section_title}}</h2>
<!-- /wp:heading -->

<!-- wp:columns {"className":"sc-classified-grid"} -->
<div class="wp-block-columns sc-classified-grid"><!-- wp:column -->
<div class="wp-block-column"><!-- wp:group {"className":"sc-file-card"} -->
<div class="wp-block-group sc-file-card"><!-- wp:paragraph {"className":"sc-file-id"} -->
<p class="sc-file-id">FILE 01</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":3} -->
<h3 class="wp-block-heading">{{sc_card1_title}}</h3>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>{{sc_card1_body}}</p>
<!-- /wp:paragraph --></div>
<!-- /wp:group --></div>
<!-- /wp:column -->

<!-- wp:column -->
<div class="wp-block-column"><!-- wp:group {"className":"sc-file-card"} -->
<div class="wp-block-group sc-file-card"><!-- wp:paragraph {"className":"sc-file-id"} -->
<p class="sc-file-id">FILE 02</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":3} -->
<h3 class="wp-block-heading">{{sc_card2_title}}</h3>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>{{sc_card2_body}}</p>
<!-- /wp:paragraph --></div>
<!-- /wp:group --></div>
<!-- /wp:column -->

<!-- wp:column -->
<div class="wp-block-column"><!-- wp:group {"className":"sc-file-card"} -->
<div class="wp-block-group sc-file-card"><!-- wp:paragraph {"className":"sc-file-id"} -->
<p class="sc-file-id">FILE 03</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":3} -->
<h3 class="wp-block-heading">{{sc_card3_title}}</h3>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>{{sc_card3_body}}</p>
<!-- /wp:paragraph --></div>
<!-- /wp:group --></div>
<!-- /wp:column --></div>
<!-- /wp:columns --></div>
<!-- /wp:group -->
