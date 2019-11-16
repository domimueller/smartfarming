<?php

namespace Cubetech\Posttypes;

/**
 * Adds the custom posttype "kamerabild" to WordPress
 *
 * @author Dominique Müller <dominiquepeter.mueller@students.bfh.ch>
 * @since 1.0.0
 * @version 1.0.0
 */
class KamerabildPosttype extends APosttype
{
    /**
     * Adds all actions for this posttype
     *
     * @return void
     *
     * @author Dominique Müller <dominiquepeter.mueller@students.bfh.ch>
     * @since 1.0.0
     * @version 1.0.0
     */
    public static function initialize()
    {
        add_action('init', __CLASS__ . '::registerPostType');
    }
    
    /**
     * Adds all actions for this posttype
     *
     * @return void
     *
     * @author Dominique Müller <dominiquepeter.mueller@students.bfh.ch>
     * @since 1.0.0
     * @version 1.0.0
     */
    public static function getType()
    {
        return 'kamerabild';
    }
    
    /**
     * Adds all labels
     *
     * @return void
     *
     * @author Dominique Müller <dominiquepeter.mueller@students.bfh.ch>
     * @since 1.0.0
     * @version 1.0.0
     */
    public static function getLabels()
    {
        return [
            'name'               => _x('Kamerabilder', 'General name', 'wptheme.smartfarming'),
            'singular_name'      => _x('Kamerabild', 'Singular name', 'wptheme.smartfarming'),
            'menu_name'          => _x('Kamerabilder', 'Menu name', 'wptheme.smartfarming'),
            'parent_item_colon'  => _x('Übergeordnetes Kamerabild', 'Parent item with colon', 'wptheme.smartfarming'),
            'all_items'          => _x('Alle Kamerabilder anzeigen', 'All items', 'wptheme.smartfarming'),
            'view_item'          => _x('Kamerabild anzeigen', 'View item', 'wptheme.smartfarming'),
            'add_new_item'       => _x('Kamerabild hinzufügen', 'Add new item', 'wptheme.smartfarming'),
            'add_new'            => _x('Kamerabild hinzufügen', 'Add new', 'wptheme.smartfarming'),
            'edit_item'          => _x('Kamerabild bearbeiten', 'Edit item', 'wptheme.smartfarming'),
            'update_item'        => _x('Kamerabild aktualisieren', 'Update item', 'wptheme.smartfarming'),
            'search_items'       => _x('Kamerabild suchen', 'Search items', 'wptheme.smartfarming'),
            'not_found'          => _x('Keine Kamerabilder gefunden.', 'Not found', 'wptheme.smartfarming'),
            'not_found_in_trash' => _x('Keine Kamerabilder im Papierkorb gefunden.', 'Not found in trash', 'wptheme.smartfarming'),
        ];
    }
    
    /**
     * Adds all arguments
     *
     * @return void
     *
     * @author Dominique Müller <dominiquepeter.mueller@students.bfh.ch>
     * @since 1.0.0
     * @version 1.0.0
     */
    public static function getArguments()
    {
        return [
            'labels'              => self::getLabels(),
            'description'         => _x('Kamerabilder Informationen', 'Description', 'wptheme.smartfarming'),
                'supports'            => ['title', 'thumbnai'],
                'hierarchical'        => false,
                'public'              => true,
                'show_ui'             => true,
                'show_in_menu'        => true,
                'show_in_nav_menus'   => true,
                'show_in_admin_bar'   => true,
                'can_export'          => true,
                'has_archive'         => false,
                'exclude_from_search' => false,
                'publicly_queryable'  => true,
                'capability_type'     => 'post',
                'map_meta_cap'        => true,
                'rewrite'             => ['slug' => _x('kamerabild', 'Slug for posttype Kamerabild', 'wptheme-smartfarming')],
                'menu_icon'           => 'dashicons-camera',
        ];
    }
}