<?php
include '../includes.php';

/* Export users.csv */

$page_title = __('Support Tickets');
include '../header.php'; ?>

<!-- Page Body Start-->
<div class="page-body-wrapper">
    <?php include '../sidebar.php'; ?>
    <!-- Page Sidebar Ends-->
    <div class="page-body">
        <div class="container-fluid">
            <div class="page-header">
                <div class="row">
                    <div class="col-lg-6 main-header">
                        <h2><?php _esc($page_title) ?></h2>
                        <h6 class="mb-0"><?php _e('admin panel') ?></h6>
                    </div>
                </div>
            </div>
        </div>
        <!-- Container-fluid starts-->
        <div class="container-fluid">
            <div class="quick-card card">
                <div class="card-body">
                    <div class="dataTables_wrapper">
                        <table class="table table-striped" id="ajax_datatable" data-jsonfile="tickets.php" data-order-dir="desc" data-order-col="8">
                            <thead>
                            <tr>
                                <th><?php _e('Ticket ID') ?></th>
                                <th><?php _e('Title') ?></th>
                                <th><?php _e('Content') ?></th>
                                <th><?php _e('Created') ?></th>
                                <th><?php _e('User') ?></th>
                                <th><?php _e('Email') ?></th>
                                <th><?php _e('Comment') ?></th>
                                <th><?php _e('Category') ?></th>
                                <th><?php _e('Priority') ?></th>
                                <th><?php _e('Status') ?></th>
                                <th width="20" class="no-sort" data-priority="1"></th>
                                <!-- <th width="20" class="no-sort" data-priority="1">
                                    <div class="checkbox">
                                        <input type="checkbox" id="quick-checkbox-all">
                                        <label for="quick-checkbox-all"><span class="checkbox-icon"></span></label>
                                    </div>
                                </th> -->
                            </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <!-- Container-fluid Ends-->
    </div>
    <!-- Site Action -->
    <div class="site-action">
        <div class="site-action-buttons">
            <button type="button" id="quick-delete-button" data-action="deleteusers"
                    class="btn btn-danger btn-floating animation-slide-bottom">
                <i class="icon icon-feather-trash-2" aria-hidden="true"></i>
            </button>
        </div>
        <button type="button" class="front-icon btn btn-primary btn-floating"
                data-url="panel/users_add.php" data-toggle="slidePanel">
            <i class="icon-feather-plus animation-scale-up" aria-hidden="true"></i>
        </button>
        <button type="button" class="back-icon btn btn-primary btn-floating">
            <i class="icon-feather-x animation-scale-up" aria-hidden="true"></i>
        </button>
    </div>
    <script>
        var QuickMenu = {"page":"users"};
    </script>

    <?php ob_start() ?>
    <link rel="stylesheet" href="../assets/css/datatables.css" />
    <script src="../assets/js/jquery.dataTables.min.js"></script>
    <link rel="stylesheet" href="../assets/css/slidePanel.min.css" />
    <script src="../assets/js/jquery-slidePanel.min.js"></script>
    <?php
    $footer_content = ob_get_clean();

    include '../footer.php'; ?>

