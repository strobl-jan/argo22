<?php
/**
 * ACF Block: Team Member Grid (light theme)
 */
if (!defined('ABSPATH')) exit;

$selected_members = get_field('members');
$columns          = get_field('columns');
$show_position    = get_field('show_position');

// --- načtení členů ---
$members = [];
if (!empty($selected_members) && is_array($selected_members)) {
    foreach ($selected_members as $m) {
        $members[] = is_numeric($m) ? (int)$m : (($m instanceof WP_Post) ? $m->ID : null);
    }
    $members = array_filter($members);
} else {
    $members = get_posts([
            'post_type'      => 'team_member',
            'posts_per_page' => -1,
            'post_status'    => 'publish',
            'orderby'        => ['menu_order' => 'ASC', 'title' => 'ASC'],
            'order'          => 'ASC',
            'fields'         => 'ids',
    ]);
}

$grid_cols = 'md:grid-cols-2';
if ($columns == 3) $grid_cols = 'md:grid-cols-3';
if ($columns == 4) $grid_cols = 'md:grid-cols-4';
?>
<section class="rounded-2xl border border-slate-200 bg-white p-6 shadow-card">
    <?php if (empty($members)) : ?>
        <div class="rounded-xl border border-dashed border-slate-300 p-6 text-slate-500 bg-white">
            <p class="text-sm">Nenalezeni žádní členové týmu. Přidejte Team Members nebo vyberte konkrétní v nastavení bloku.</p>
        </div>
        <?php return; endif; ?>

    <div class="grid grid-cols-1 gap-6 <?php echo esc_attr($grid_cols); ?>">
        <?php foreach ($members as $member_id):
            $name        = get_the_title($member_id);
            $position    = get_field('position', $member_id);
            $phone       = get_field('phone', $member_id);
            $image_field = get_field('profile_image', $member_id);
            $permalink   = get_permalink($member_id);

            // --- obrázek ---
            $image_url = '';
            $image_alt = $name;
            if ($image_field) {
                if (is_array($image_field)) {
                    $image_url = $image_field['url'] ?? '';
                    $image_alt = $image_field['alt'] ?: $name;
                } elseif (is_numeric($image_field)) {
                    $image_url = wp_get_attachment_image_url((int)$image_field, 'medium');
                    $image_alt = get_post_meta((int)$image_field, '_wp_attachment_image_alt', true) ?: $name;
                } elseif (is_string($image_field)) {
                    $image_url = $image_field;
                }
            }
            ?>
            <article class="group relative overflow-hidden rounded-2xl border border-slate-200 bg-white p-5 shadow-sm hover:shadow-card transition">
                <a href="<?php echo esc_url($permalink); ?>" class="absolute inset-0 z-10" aria-label="<?php echo esc_attr($name); ?>"></a>

                <div class="flex items-center gap-4 relative z-0">
                    <div class="shrink-0">
                        <?php if ($image_url): ?>
                            <img src="<?php echo esc_url($image_url); ?>" alt="<?php echo esc_attr($image_alt); ?>"
                                 class="h-20 w-20 rounded-full object-cover ring-4 ring-brand/30 transition group-hover:ring-brand" />
                        <?php else: ?>
                            <div class="flex h-20 w-20 items-center justify-center rounded-full bg-slate-100 text-slate-400 ring-4 ring-brand/30 transition group-hover:ring-brand">
                                <span class="text-xl font-semibold"><?php echo esc_html(mb_substr($name, 0, 1)); ?></span>
                            </div>
                        <?php endif; ?>
                    </div>

                    <div class="min-w-0 flex-1">
                        <h3 class="truncate text-lg font-semibold text-slate-900 group-hover:text-brand transition">
                            <?php echo esc_html($name); ?>
                        </h3>
                        <?php if ($show_position && $position): ?>
                            <p class="mt-0.5 truncate text-sm text-slate-500"><?php echo esc_html($position); ?></p>
                        <?php endif; ?>

                        <?php if ($phone): ?>
                            <div class="mt-3 flex items-center gap-3 text-sm text-slate-700">
                                <a class="inline-flex items-center gap-1.5 hover:underline decoration-brand underline-offset-2 relative z-20"
                                   href="tel:<?php echo esc_attr(preg_replace('/\s+/', '', $phone)); ?>"
                                   onclick="event.stopPropagation();">
                                    <svg class="h-4 w-4 flex-none text-brand" viewBox="0 0 24 24" fill="none" stroke="currentColor" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                              d="M2.25 4.5c0-1.243 1.007-2.25 2.25-2.25h2.25c.993 0 1.84.654 2.11 1.602l.73 2.555a2.25 2.25 0 01-.54 2.217L7.2 10.5a16.5 16.5 0 006.3 6.3l1.876-1.85a2.25 2.25 0 012.217-.54l2.555.73A2.25 2.25 0 0121.75 18v2.25c0 1.243-1.007 2.25-2.25 2.25h-.75C9.268 22.5 1.5 14.732 1.5 5.25v-.75z"/>
                                    </svg>
                                    <span class="truncate"><?php echo esc_html($phone); ?></span>
                                </a>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </article>
        <?php endforeach; ?>
    </div>
</section>
