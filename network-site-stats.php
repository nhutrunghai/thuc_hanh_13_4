<?php
/*
Plugin Name: Network Site Stats
Description: Hiển thị thống kê các site con cho Super Admin.
Version: 1.0
Author: Nhữ Trung Hải
*/

// Chỉ cho phép chạy nếu là Multisite
if ( ! is_multisite() ) return;

// Tạo menu trong Network Admin
add_action('network_admin_menu', function() {
    add_menu_page(
        'Site Stats', 
        'Site Stats', 
        'manage_network', 
        'network-site-stats', 
        'render_stats_page', 
        'dashicons-chart-bar'
    );
});

function render_stats_page() {
    // Lấy danh sách tất cả các site
    $sites = get_sites();
    ?>
    <div class="wrap">
        <h1>Thống kê mạng lưới Website</h1>
        <table class="wp-list-table widefat fixed striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Tên Site</th>
                    <th>Số bài viết</th>
                    <th>Bài mới nhất</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ( $sites as $site ) : 
                    $blog_id = $site->blog_id;
                    
                    // Chuyển ngữ cảnh sang site con
                    switch_to_blog($blog_id);
                    
                    $details = get_blog_details($blog_id);
                    $post_count = wp_count_posts()->publish;
                    $last_post = get_posts(['numberposts' => 1]);
                    $last_date = !empty($last_post) ? $last_post[0]->post_date : 'Chưa có';

                    // Trả lại ngữ cảnh cũ
                    restore_current_blog();
                ?>
                <tr>
                    <td><?php echo $blog_id; ?></td>
                    <td><strong><?php echo $details->blogname; ?></strong></td>
                    <td><?php echo $post_count; ?></td>
                    <td><?php echo $last_date; ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <?php
}