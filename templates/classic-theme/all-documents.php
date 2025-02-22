<?php

overall_header(__("All Documents"));
?>

<!-- Dashboard Container -->
<div class="dashboard-container">
    <?php
    include_once TEMPLATE_PATH . '/dashboard_sidebar.php';
    ?>
    <!-- Dashboard Content
        ================================================== -->
    <div class="dashboard-content-container" data-simplebar>
        <div class="dashboard-content-inner">
            <?php print_adsense_code('header_bottom'); ?>
            <!-- Dashboard Headline -->
            <div class="dashboard-headline">
                <h3 class="d-flex align-items-center">
                    <?php _e("All Documents") ?>
                    <div class="word-used-wrapper margin-left-10">
                        <i class="icon-feather-bar-chart-2"></i>
                        <?php echo '<i id="quick-words-left">' .
                            _esc(number_format((float) $total_words_used), 0) . '</i> / ' .
                            ($words_limit == -1
                                ? __('Unlimited')
                                : _esc(number_format($words_limit + get_user_option($_SESSION['user']['id'], 'total_words_available', 0)), 0));
                        ?>
                        <strong><?php _e('Words Used'); ?></strong>
                    </div>
                </h3>
                <!-- Breadcrumbs -->
                <nav id="breadcrumbs" class="dark">
                    <ul>
                        <li><a href='<?php echo $config['site_url'] . 'dashboard' ?>'><?php _e("Dashboard") ?></a></li>
                        <li><?php _e("All Documents") ?></li>
                    </ul>
                </nav>
            </div>

            <div class="dashboard-box document-box margin-top-0 margin-bottom-30">
                <!-- Headline -->
                <div class="headline">
                    <h3 class="col-md-1 headline-label"><i class="icon-feather-file"></i><?php _e("All Documents") ?></h3>
                    <div class="col-md-4">
                        <div class="document-search">
                            <div class="input-with-icon">
                                <input class="ai-tag-search" name="search-document" id="search-document" type="text" placeholder="Search Document..." autocomplete="off">
                                <i class="icon-material-outline-search"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="content with-padding">
                    <table class="basic-table">
                        <thead>
                            <tr>
                                <th data-priority="1"><?php _e("Document") ?></th>
                                <th><?php _e("Content") ?></th>
                                <th class="small-width"><?php _e("Date") ?></th>
                                <th data-priority="2" class="small-width"><?php _e("Action") ?></th>
                            </tr>
                        </thead>
                        <tbody id="get-search-records">
                            <?php if (empty($documents)) { ?>
                                <tr class="no-order-found">
                                    <td colspan="4" class="text-center"><?php _e("No documents found.") ?></td>
                                </tr>
                            <?php } ?>
                            <?php foreach ($documents as $document) { ?>
                                <tr>
                                    <td data-label="<?php _e("Document") ?>">
                                        <strong><?php _esc($document['title']) ?></strong><br>
                                        <small class="color"><i class="<?php _esc($document['template']['icon']) ?>"></i>&nbsp; <?php _esc($document['template']['title']) ?></small>
                                    </td>
                                    <?php if(!empty($document['tag_id'])) { ?>
                                        <td data-label="<?php _e("Content") ?>">Total number of images <?php _esc($document['content']) ?></td>
                                    <?php } else if(empty($document['tag_id'])) { ?>
                                        <td data-label="<?php _e("Content") ?>"><?php _esc($document['content']) ?></td>
                                    <?php } ?>
                                    <td data-label="<?php _e("Date") ?>">
                                        <small><?php echo _esc($document['date'], 0) . ' <br><strong>' . _esc($document['time'], 0) . '</strong>' ?></small>
                                    </td>
                                    <td data-label="<?php _e("Action") ?>">
                                    <?php if(!empty($document['tag_id'])) { ?>
                                        <button onclick="window.location.href='<?php echo $config['site_url'] . 'ai-images' . '?tag_id='.$document['tag_id'] ?>'" class="button ripple-effect btn-sm" data-tippy-placement="top" title="<?php _e("Show Document") ?>"><i class="fa fa-eye"></i>
                                        </button>
                                    <?php } else if(empty($document['tag_id'])) { ?>
                                        <button onclick="window.location.href='<?php echo !$document['bot_id'] ? url('ALL_DOCUMENTS', 0) . '/' . $document['id'] : $config['site_url'] . 'ai-chat' . '/' . $document['bot_id'] ?>'" class="button ripple-effect btn-sm" data-tippy-placement="top" title="<?php _e("Show Document") ?>"><i class="fa fa-eye"></i>
                                        </button>
                                    <?php } ?>
                                        <button class="button red ripple-effect btn-sm quick-delete" data-id="<?php _esc($document['id']) ?>" data-action="delete_document" data-tippy-placement="top" title="<?php _e("Delete") ?>"><i class="fa fa-trash-o"></i>
                                        </button>
                                    </td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                    <?php if ($show_paging) { ?>
                        <!-- Pagination -->
                        <div class="pagination-container margin-top-20">
                            <nav class="pagination">
                                <ul>
                                    <?php
                                    foreach ($pagging as $page) {
                                        if ($page['current'] == 0) {
                                    ?>
                                            <li>
                                                <a href="<?php _esc($page['link']) ?>"><?php _esc($page['title']) ?></a>
                                            </li>
                                        <?php } else {
                                        ?>
                                            <li><a href="#" class="current-page"><?php _esc($page['title']) ?></a>
                                            </li>
                                    <?php }
                                    }
                                    ?>
                                </ul>
                            </nav>
                        </div>
                    <?php } ?>
                </div>
            </div>
            <?php print_adsense_code('footer_top'); ?>
            <!-- Footer -->
            <div class="dashboard-footer-spacer"></div>
            <div class="small-footer margin-top-15">
                <div class="footer-copyright">
                    <?php _esc($config['copyright_text']); ?>
                </div>
                <ul class="footer-social-links">
                    <?php
                    if ($config['facebook_link'] != "")
                        echo '<li><a href="' . _esc($config['facebook_link'], false) . '" target="_blank" rel="nofollow"><i class="fa fa-facebook"></i></a></li>';
                    if ($config['twitter_link'] != "")
                        echo '<li><a href="' . _esc($config['twitter_link'], false) . '" target="_blank" rel="nofollow"><i class="fa fa-twitter"></i></a></li>';
                    if ($config['instagram_link'] != "")
                        echo '<li><a href="' . _esc($config['instagram_link'], false) . '" target="_blank" rel="nofollow"><i class="fa fa-instagram"></i></a></li>';
                    if ($config['linkedin_link'] != "")
                        echo '<li><a href="' . _esc($config['linkedin_link'], false) . '" target="_blank" rel="nofollow"><i class="fa fa-linkedin"></i></a></li>';
                    if ($config['pinterest_link'] != "")
                        echo '<li><a href="' . _esc($config['pinterest_link'], false) . '" target="_blank" rel="nofollow"><i class="fa fa-pinterest"></i></a></li>';
                    if ($config['youtube_link'] != "")
                        echo '<li><a href="' . _esc($config['youtube_link'], false) . '" target="_blank" rel="nofollow"><i class="fa fa-youtube"></i></a></li>';
                    ?>
                </ul>
                <div class="clearfix"></div>
            </div>

        </div>
    </div>
</div>

<?php ob_start() ?>
<?php
$footer_content = ob_get_clean();
include_once TEMPLATE_PATH . '/overall_footer_dashboard.php';
