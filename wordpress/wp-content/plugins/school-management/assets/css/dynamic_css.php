<?php

    $path = preg_replace( '/wp-content(?!.*wp-content).*/', '', __DIR__ );
    
    require_once( $path . 'wp-load.php' );
    
    $color = get_option('smgt_system_color_code');

   header("Content-type: text/css; charset: UTF-8"); //look carefully to this line

?>
<style>
    /* div.plugin_code_start{
        background: <?php echo $color;?>!important;
    } */
    .accordion-header button.accordion-button.class_route_list.collapsed{
        border-left: 5px solid <?php echo $color;?> !important;
    }
    .smgt-navigation li a {
        background-color: <?php echo $color;?> !important;
    }
    .smgt-header .smgt-logo {
        background-color: <?php echo $color;?> !important;
    }
    .btn-sms-color {
        background-color: <?php echo $color;?> !important;
    }
    .save_btn {
        background-color: <?php echo $color;?> !important;
        background: <?php echo $color;?>;
    }
    .main_sidebar #sidebar .rs_side_menu_bgcolor{
        background-color: <?php echo $color;?> !important;
    }
    #main_sidebar-bgcolor {
        background-color: <?php echo $color;?> !important;
        background:<?php echo $color;?> !important;
    }
    .upload_image_btn {
        background-color: <?php echo $color;?> !important;
        border-color: <?php echo $color;?> !important;
    }
    .gnrl_setting_image_background {
        background: <?php echo $color;?> !important;
    }
    .steps li.current a .step-icon, .steps li.current a:active .step-icon, .steps .done::before, .steps li.done a .step-icon, .steps li.done a:active .step-icon {
        background: <?php echo $color;?> !important;
    }
    .dataTables_wrapper .dataTables_paginate .paginate_button.current,
    .dataTables_wrapper .dataTables_paginate .paginate_button.current:hover {
        background: <?php echo $color;?>!important;
    }
    .view_page_header_bg {
        background: <?php echo $color;?>!important;
    }
    .card_heading {
        background-color: <?php echo $color;?> !important;
    }
    .qr_main_div {
        background: <?php echo $color;?> !important;
    }
    .nav-tabs>li.active>a,
    .nav-tabs>li.active>a:focus {
        color: <?php echo $color;?> !important;
        border-bottom-color: <?php echo $color;?> !important;
    }
    .class_border_div {
        border-left: 5px solid <?php echo $color;?> !important;
    }
    #sidebar li .submenu li span:hover {
        color: <?php echo $color;?>;
    }
    .smgt_download_btn a{
        background-color: <?php echo $color;?> !important;
    }
    .save_att_btn{
        background-color: <?php echo $color;?> !important;
    }
    .add_btn {
        background-color: <?php echo $color;?> !important;
        background: <?php echo $color;?>;
    }
    .invoice_table_grand_total{
        background-color: <?php echo $color;?> !important;
    }
    .btn-place a.dt-button{
        border: 1px solid <?php echo $color;?>!important;
        background-color: <?php echo $color;?>!important;
    }
    .btn-place button.dt-button{
        border: 1px solid <?php echo $color;?>!important;
        background-color: <?php echo $color;?>!important; 
    }
    .att_download_csv_btn{
        background: <?php echo $color;?>!important;
    }
    .smgt_inbox_tab span.smgt_inbox_count_number{
        background-color: <?php echo $color;?> !important;
    }
    .main_email_template .smgt_accordion div.accordion-item{
        border-left: 5px solid <?php echo $color;?> !important;
    }
    .main_email_template .accordion-button.bg-gray{
        background-color: <?php echo $color;?>;
    }
    #message {
        border-left: 4px solid <?php echo $color;?> !important;
    }
    .dtsb-add, .dtsb-logic, .dtsb-right, .dtsb-left
    {
        background-color: <?php echo $color;?> !important;
        background: <?php echo $color;?>;
    }
    div.dtsb-searchBuilder div button.dtsb-add:hover, div.dtsb-searchBuilder div button.dtsb-logic:hover, div.dtsb-searchBuilder div button.dtsb-right:hover, div.dtsb-searchBuilder div button.dtsb-left:hover {
        background-color: <?php echo $color;?> !important;
        cursor: pointer;
    }
    .smgt-navigation li .active {
        background-color: #F9FDFF !important;
        color: #5B5D6E;
    }

    .smgt-navigation li a:hover,
    .smgt-navigation li .smgt-droparrow:hover+a {
        background-color: #F9FDFF !important;
        color: #5B5D6E;
    }
    #sidebar .dropdown-menu li a {
        padding: 12px;
        text-decoration: none;
        background: #F2F5FA !important;
        font-style: normal;
        font-weight: normal;
        font-size: 15px;
        line-height: 22px;
        display: flex;
        align-items: center;
        color: #5B5D6E;
    }
    ul li.card-icon::marker
    {
        color: <?php echo $color;?> !important;
    }
</style>
