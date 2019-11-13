<?php
namespace Cubetech\Templates;
use Cubetech\Controller\NavigationController;
/**
 * Template class for the redirect child template
 *
 * @author Steeve Jeannin <steeve@cubetech.ch>
 * @since Version 1.0
 */
class RedirectChildTemplate extends BaseTemplate {
    public function __construct()
    {
        $navigation = NavigationController::getNavigation('primary');
        $currentItem = $this->getCurrentNavigationItem($navigation->menuItems);
        if (!$currentItem ||
            !$currentItem->hasChildren ||
            empty($currentItem->children[0]->url)) {
            $this->return404();
        }
        wp_redirect($currentItem->children[0]->url);
        exit;
    }
    /**
     * Returns a 404 status header and loads the 404 Template
     *
     * @return void
     * 
     * @author Alex Scherer <alex.scherer@cubetech.ch>
     * @since 1.0.0
     */
    protected function return404()
    {
        global $wp_query;
        $wp_query->set_404();
        status_header(404);
        get_template_part(404);
        exit();
    }
    /**
     * Searches the navigation array for the currently queried
     * item
     *
     * @param array $menuItems
     * @return MenuItem
     * 
     * @author Alex Scherer <alex.scherer@cubetech.ch>
     * @since 1.0.0
     */
    protected function getCurrentNavigationItem($menuItems)
    {
        foreach ($menuItems as $menu) {
            if ($menu->objectId === get_the_id()) {
                return $menu;
            } elseif ($menu->hasChildren) {
                $match = $this->getCurrentNavigationItem($menu->children);
                if ($match) {
                    return $match;
                }
            }
        }
        return false;
    }
}