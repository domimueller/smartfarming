<?php

namespace Cubetech;

/**
 * Base class for the Theme
 *
 * Sets up everything needed for the theme to function
 * i.e. views, components, navigations etc.
 *
 * @author Alex Scherer <alex.scherer@cubetech.ch>
 * @author Marc Mentha <marc@cubetech.ch>
 * @since Version 1.1.0
 */
class Theme
{
    /**
     * Calls every controller's addActions method
     *
     * @return void
     *
     * @author Alex Scherer <alex.scherer@cubetech.ch>
     * @author Marc Mentha <marc@cubetech.ch>
     * @since 1.0.0
     * @version 2.0.0
     */
    public function initialize() : void
    {
        \Cubetech\Controller\ThemeController::addActions();
        \Cubetech\Controller\StyleController::addActions();
        \Cubetech\Controller\ScriptController::addActions();
        \Cubetech\Controller\PosttypeController::addActions();
        \Cubetech\Controller\NavigationController::addActions();
        \Cubetech\Controller\SidebarController::addActions();
        \Cubetech\Controller\PackageController::addActions();
        \Cubetech\Controller\ImageController::addActions();
        
        /**
         * This allows you to easily remove the adminbar for logged in users
         * with no permission to edit/view the profile/dashboard
         * by adding the userrole "no_backend" to the desired users
         */
        \Cubetech\Helpers\RestrictionHandler::hideAdminBarForNoBackendUser();
    }
}
