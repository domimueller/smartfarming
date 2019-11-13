<?php

namespace Cubetech\Packages\Backend;


class CubetechWP_CLI
{
    public static function run()
    {
        if (defined('WP_CLI') && \WP_CLI) {
            \WP_CLI::add_command('ct', __CLASS__);
        }
        
    }
     
    public function cleanacf()
    {
        global $wpdb;
        $keywords = 'acf-field';
        $table = $wpdb->prefix . 'posts';
        $sql = "DELETE  FROM $table  WHERE post_type LIKE '%$keywords%'";
        $result = $wpdb->query($sql);
        
        if ($result) {
            \WP_CLI::success('ACF fields now deleted and ready for new synchronization');
        }
        else {
            \WP_CLI::error('Acf fields could not be deleted or have already been deleted');
        }
    }
}
    
