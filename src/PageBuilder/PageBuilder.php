<?php

namespace Cubetech\PageBuilder;

use \Cubetech\Rendering\TemplatePart;

/**
 * PageBuilder class for loading page builder components from a
 * Post object and rendering them.
 *
 * @author Alex Scherer <alex.scherer@cubetech.ch>
 * @author Marc Mentha <marc@cubetech.ch>
 * @version 1.1.0
 * @since 1.0.0
 */
class PageBuilder extends TemplatePart
{
    /**
     * Array with strings containing the recorded components
     *
     * @var array of strings
     */
    private $layouts;
    
    /**
     * Holds the component classes for rendering
     *
     * @var array of \Cubetech\PageBuilder\Components
     */
    private $components;
    
    /**
     * String to define all comoponents container classes.
     *
     * @var string
     */
    private $containerClass;
    
    /**
     * Used for dynamically loading component classes
     */
    const COMPONENT_NAMESPACE = '\Cubetech\PageBuilder\Components\\';
    
    /**
     * Constructor for the pageBuilder class
     * Initializes postId, getting layouts from backend
     * and coponentclasses
     *
     * @param int $postId
     *
     * @author Alex Scherer <alex.scherer@cubetech.ch>
     * @version 1.0.0
     * @since 1.0.0
     */
    public function __construct($containerClass = 'uk-container')
    {
        $this->containerClass = $containerClass;
        $this->layouts = $this->getField('pagebuilder');
        $this->initialize();
    }
    
    /**
     * Initializes the page builder from the post id
     * given to the constructor
     *
     * @return void
     *
     * @author Alex Scherer <alex.scherer@cubetech.ch>
     * @version 1.0.0
     * @since 1.0.0
     */
    public function initialize()
    {
        if (!empty($this->layouts)) {
            foreach ($this->layouts as $componentIndex => $layout) {
                $componentNamespace = self::COMPONENT_NAMESPACE . $layout . 'Component';
                $this->components[] = new $componentNamespace($componentIndex, $this->containerClass);
            }
        }
    }
    
    /**
     * Checks if the pagebuilder has components
     *
     * @return bool
     *
     * @author Marc Mentha <marc@cubetech.ch>
     * @version 1.0.0
     * @since 1.1.0
     */
    public function hasComponents()
    {
        if (empty($this->layouts)) {
            return false;
        }
        return true;
    }
    
    /**
     * Render method for looping through all components for this post
     * and rendering them.
     * Inheritet from Cubetech\Rendering\IRenderable
     *
     * @return void
     *
     * @author Alex Scherer <alex.scherer@cubetech.ch>
     * @version 1.0.0
     * @since 1.0.0
     * @final
     */
    final public function render() : void
    {
        if ( !empty($this->components)) {
            foreach ($this->components as $component) {
                if ($component->isValid()) {
                    $component->render();
                }
            }
        }
    }
    
    /**
     * Renders all compatible components' contents into
     * a string
     *
     * @return string
     *
     * @author Alex Scherer <alex.scherer@cubetech.ch>
     * @version 1.0.0
     * @since 1.0.0
     * @final
     */
    final public function renderToString()
    {
        $returnString = '';
        if ( !empty($this->components)) {
            foreach ($this->components as $component) {
                if ($component instanceof IStringRenderable) {
                    $returnString .= $component->renderToString();
                }
            }
        }
        return $returnString;
    }
}