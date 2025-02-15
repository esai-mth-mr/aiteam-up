<?php

overall_header(__("Support"));

require_once('ticket-create-modal.php');
require_once('ticket-view-modal.php');
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
                    <?php _e("Support & Training") ?>
                </h3>
                <!-- Breadcrumbs -->
                <nav id="breadcrumbs" class="dark">
                    <ul>
                        <li><a href='<?php echo $config['site_url'] . 'dashboard' ?>'><?php _e("Dashboard") ?></a></li>
                        <li><?php _e("Support") ?></li>
                    </ul>
                </nav>
            </div>

            <div class="dashboard-box document-box margin-top-0 margin-bottom-30">
                <!-- Headline -->
                <div class="headline" style="justify-content: space-between;">
                    <div style="display: flex; align-items: center; width: auto;">
                        <h3 class="col-md-1 headline-label"><i class="icon-feather-file"></i><?php _e("All Tickets") ?></h3>
                        <div class="col-md-4">
                            <div class="document-search">
                                <div class="input-with-icon">
                                    <input class="ai-tag-search" name="search-document" id="search-document" type="text" placeholder="<?php _e("Search Ticket") ?>..." autocomplete="off">
                                    <i class="icon-material-outline-search"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div>
                        <a style="font-size: 13px; color: black;" href='<?php echo $config['faq_url'] ?>' target="_blank">
                            <i style="margin-right: 10px;" class="icon-feather-help-circle"></i> <?php _e("FAQ & Trainning.") ?>
                        </a>
                    </div>
                </div>
                <div>
                    <button type="button" class="support-create-btn"> <?php _e("Create New Support Request") ?>.</button>
                </div>
                <div class="content with-padding">
                    <table class="basic-table">
                        <thead>
                            <tr>
                                <th data-priority="1"><?php _e("Ticket ID") ?></th>
                                <th><?php _e("Category") ?></th>
                                <th><?php _e("Title") ?></th>
                                <th><?php _e("Priority") ?></th>
                                <th class="small-width"><?php _e("Date") ?></th>
                                <th class="small-width"><?php _e("Status") ?></th>
                                <th data-priority="2" class="small-width"><?php _e("Action") ?></th>
                            </tr>
                        </thead>
                        <tbody id="get-search-records">
                            <?php if (empty($tickets)) { ?>
                                <tr class="no-order-found">
                                    <td colspan="9" class="text-center"><?php _e("No tickets found.") ?></td>
                                </tr>
                            <?php } ?>
                            <?php foreach ($tickets as $ticket) { ?>
                                <tr>
                                    <td data-label="<?php _e("Ticket") ?>">
                                        <strong> # <?php _esc($ticket['id']) ?></strong><br>
                                    </td>
                                    <td data-label="<?php _e("Category") ?>"><?php _esc($ticket['category']) ?></td>
                                    <?php if (!empty($ticket['title'])) { ?>
                                        <td data-label="<?php _e("Title") ?>"><?php _esc($ticket['title']) ?></td>
                                    <?php } else { ?>
                                        <td data-label="<?php _e("Title") ?>"><?php _e("Untitled") ?></td>
                                    <?php } ?>
                                    <td data-label="<?php _e("Priority") ?>">
                                        <span class="ticket-priority ticket-priority-<?php echo isset($ticket['priority']) ? strtolower($ticket['priority']) : null; ?>">
                                            <?php _esc($ticket['priority']) ?>
                                        </span>
                                    </td>
                                    <td data-label="<?php _e("Date") ?>">
                                        <small><?php echo _esc($ticket['date'], 0) . ' <br><strong>' . _esc($ticket['time'], 0) . '</strong>' ?></small>
                                    </td>
                                    <td data-label="<?php _e("Status") ?>">
                                        <?php if ($ticket['status'] === "open") { ?>
                                            <span class="ticket-status ticket-status-open"><?php _esc($ticket['status']) ?></span>
                                        <?php } else if ($ticket['status'] === "progress") { ?>
                                            <span class="ticket-status ticket-status-progress"><?php _esc($ticket['status']) ?></span>
                                        <?php } else { ?>
                                            <span class="ticket-status ticket-status-resolved"><?php _esc($ticket['status']) ?></span>
                                        <?php } ?>
                                    </td>
                                    <td data-label="<?php _e("Action") ?>">
                                        <button class="button ripple-effect btn-sm preview-ticket" data-id="<?php _esc($ticket['id']) ?>" data-action="preview_ticket" data-tippy-placement="top" title="<?php _e("Detail") ?>"><i class="fa fa-eye"></i></button>
                                        <button class="button red ripple-effect btn-sm quick-delete-ticket" data-id="<?php _esc($ticket['id']) ?>" data-action="delete_ticket" data-tippy-placement="top" title="<?php _e("Delete") ?>"><i class="fa fa-trash-o"></i>
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
