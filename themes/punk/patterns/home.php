<?php
/**
 * Title: home
 * Slug: punk/home
 * Inserter: no
 */
?>
<!-- wp:template-part {"slug":"header","tagName":"header"} /-->

<!-- wp:group {"tagName":"main","metadata":{"name":"Content"},"style":{"spacing":{"padding":{"right":"var:preset|spacing|30","left":"var:preset|spacing|30"}}},"layout":{"type":"default"}} -->
<main class="wp-block-group" style="padding-right:var(--wp--preset--spacing--30);padding-left:var(--wp--preset--spacing--30)"><!-- wp:image {"alt":"Four young punk rockers sitting on a subway, wearing spiked leather jackets and sporting colorful mohawks in yellow, red, and pink/purple.","sizeSlug":"full","linkDestination":"none","align":"full"} -->
<figure class="wp-block-image alignfull size-full"><img src="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/images/punk-img-1.webp" alt="<?php esc_attr_e('Four young punk rockers sitting on a subway, wearing spiked leather jackets and sporting colorful mohawks in yellow, red, and pink/purple.', 'punk');?>"/></figure>
<!-- /wp:image -->

<!-- wp:heading {"fontFamily":"plymouthpress","fitText":true} -->
<h2 class="wp-block-heading has-fit-text has-plymouthpress-font-family"><?php esc_html_e('Live On Tour! UK and Europe', 'punk');?></h2>
<!-- /wp:heading -->

<!-- wp:group {"style":{"spacing":{"blockGap":"var:preset|spacing|20"}},"layout":{"type":"default"}} -->
<div class="wp-block-group"><!-- wp:paragraph {"style":{"typography":{"lineHeight":"0.8"}},"fontFamily":"plymouthpress","fitText":true} -->
<p class="has-fit-text has-plymouthpress-font-family" style="line-height:0.8"><?php esc_html_e('London·Leeds·Dublin·Manchester·Glasgow', 'punk');?></p>
<!-- /wp:paragraph -->

<!-- wp:paragraph {"style":{"typography":{"lineHeight":"0.8"}},"fontFamily":"plymouthpress","fitText":true} -->
<p class="has-fit-text has-plymouthpress-font-family" style="line-height:0.8"><?php esc_html_e('Edinburgh·Munich·Hamburg·Berlin·Athens', 'punk');?></p>
<!-- /wp:paragraph -->

<!-- wp:paragraph {"style":{"typography":{"lineHeight":"0.8"}},"fontFamily":"plymouthpress","fitText":true} -->
<p class="has-fit-text has-plymouthpress-font-family" style="line-height:0.8"><?php esc_html_e('Birmingham·Bristol·Brighton·Bordeaux', 'punk');?></p>
<!-- /wp:paragraph -->

<!-- wp:paragraph {"style":{"typography":{"lineHeight":"0.8"}},"fontFamily":"plymouthpress","fitText":true} -->
<p class="has-fit-text has-plymouthpress-font-family" style="line-height:0.8"><?php esc_html_e('Paris·Madrid·Barcelona·Valencia·Marseille', 'punk');?></p>
<!-- /wp:paragraph -->

<!-- wp:paragraph {"style":{"typography":{"lineHeight":"0.8"}},"fontFamily":"plymouthpress","fitText":true} -->
<p class="has-fit-text has-plymouthpress-font-family" style="line-height:0.8"><?php esc_html_e('Lisbon·Porto·Milan·Florence·Warsaw·Riga', 'punk');?></p>
<!-- /wp:paragraph -->

<!-- wp:paragraph {"style":{"typography":{"lineHeight":"0.8"}},"fontFamily":"plymouthpress","fitText":true} -->
<p class="has-fit-text has-plymouthpress-font-family" style="line-height:0.8"><?php esc_html_e('Amsterdam·Vienna·copenhaghen·Stockholm', 'punk');?></p>
<!-- /wp:paragraph --></div>
<!-- /wp:group --></main>
<!-- /wp:group -->

<!-- wp:template-part {"slug":"footer","tagName":"footer"} /-->