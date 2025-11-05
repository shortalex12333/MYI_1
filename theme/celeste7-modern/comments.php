<?php
/**
 * Comments Template
 *
 * @package Celeste7_Modern
 */

if (post_password_required()) {
    return;
}
?>

<div id="comments" class="comments-area mt-12 pt-12 border-t border-gray-200">
    <?php if (have_comments()) : ?>
        <h2 class="comments-title text-2xl font-bold mb-8">
            <?php
            $comment_count = get_comments_number();
            if ('1' === $comment_count) {
                printf(
                    esc_html__('One comment on &ldquo;%s&rdquo;', 'celeste7-modern'),
                    '<span>' . get_the_title() . '</span>'
                );
            } else {
                printf(
                    esc_html(_nx('%1$s comment', '%1$s comments', $comment_count, 'comments title', 'celeste7-modern')),
                    number_format_i18n($comment_count)
                );
            }
            ?>
        </h2>

        <ol class="comment-list space-y-6 mb-8">
            <?php
            wp_list_comments(array(
                'style'       => 'ol',
                'short_ping'  => true,
                'avatar_size' => 50,
                'callback'    => 'celeste7_comment_callback',
            ));
            ?>
        </ol>

        <?php
        the_comments_navigation(array(
            'prev_text' => '<svg class="w-5 h-5 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg> Older Comments',
            'next_text' => 'Newer Comments <svg class="w-5 h-5 inline ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>',
        ));
        ?>

    <?php endif; ?>

    <?php if (!comments_open() && get_comments_number() && post_type_supports(get_post_type(), 'comments')) : ?>
        <p class="no-comments text-gray-600 italic">
            <?php esc_html_e('Comments are closed.', 'celeste7-modern'); ?>
        </p>
    <?php endif; ?>

    <?php
    comment_form(array(
        'title_reply'         => __('Leave a Comment', 'celeste7-modern'),
        'title_reply_to'      => __('Reply to %s', 'celeste7-modern'),
        'cancel_reply_link'   => __('Cancel Reply', 'celeste7-modern'),
        'label_submit'        => __('Post Comment', 'celeste7-modern'),
        'comment_field'       => '<p class="comment-form-comment"><textarea id="comment" name="comment" cols="45" rows="6" maxlength="65525" required="required" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:border-electric-blue focus:ring-2 focus:ring-electric-blue focus:ring-opacity-20" placeholder="Your comment..."></textarea></p>',
        'class_submit'        => 'btn btn-primary',
        'submit_field'        => '<p class="form-submit">%1$s %2$s</p>',
    ));
    ?>
</div>
