<?php
/**
 * Template to render the event in the event list.
 *
 * @author LaStudio
 * @category Core
 * @package LaStudioEvents/Admin
 * @version 1.0.0
 */

$classes = 'la_event_item ' . $classes;

?>
<div class="<?php echo esc_attr($classes); ?>" itemscope itemtype="http://schema.org/Event">
    <?php do_action('le_event_list_item_start'); ?>
    <meta itemprop="name" content="<?php echo esc_attr($name); ?>">
    <meta itemprop="url" content="<?php echo esc_url($permalink); ?>">
    <?php if (!empty($thumbnail_url)) : ?>
        <meta itemprop="image" content="<?php echo esc_url($thumbnail_url); ?>">
    <?php endif; ?>
    <meta itemprop="description" content="<?php echo esc_attr($description); ?>">



    <div class="la_event_item-table-cell la_event_item--date" itemprop="startDate" content="<?php echo esc_attr($raw_start_date); ?>">
        <?php if (!empty($formatted_start_date)) : ?>
            <?php echo le_sanitize_date($formatted_start_date); ?>
        <?php endif; ?>
    </div>
    <div class="la_event_item-table-cell la_event_item--info" itemprop="location" itemscope itemtype="http://schema.org/Place">
		<span itemprop="address" itemscope itemtype="http://schema.org/PostalAddress">
			<?php if (!empty($city)) : ?>
                <meta itemprop="addressLocality" content="<?php echo esc_attr($city); ?>">
            <?php endif; ?>
            <?php if (!empty($address)) : ?>
                <meta itemprop="streetAddress" content="<?php echo esc_attr($address); ?>">
            <?php endif; ?>

            <?php if (!empty($state)) : ?>
                <meta itemprop="addressRegion" content="<?php echo esc_attr($state); ?>">
            <?php endif; ?>
            <?php if (!empty($zipcode)) : ?>
                <meta itemprop="postalCode" content="<?php echo esc_attr($zipcode); ?>">
            <?php endif; ?>
		</span>
        <?php if (!empty($link)) : ?><a rel="bookmark" class="entry-link" href="<?php the_permalink(); ?>"><?php endif; ?>
            <span class="la_event_item--name"><?php echo esc_html($name); ?></span>
            <span itemprop="name" class="la_event_item--venue"><?php echo sanitize_text_field($venue); ?></span>
            <span class="la_event_item--time"><?php echo sanitize_text_field($time); ?></span>
        <?php if (!empty($link)) : ?></a><?php endif; ?>

    </div>
    <div class="la_event_item-table-cell la_event_item--action">
        <?php if (!empty($action)) : ?>
            <?php echo le_sanitize_action($action); ?>
        <?php endif; ?>
    </div>
    <?php do_action('le_event_list_item_end'); ?>
</div>
