<?php

namespace Cubetech\Templates;

use \Cubetech\Rendering\TemplatePart;
use \Cubetech\Rendering\RenderableList;
use \Cubetech\Partials\Footer;
use \Cubetech\Base\Navigation;

/**
 * Base Template class for every template used from the Theme
 *
 * @author Alex Scherer <alex.scherer@cubetech.ch>
 * @since 1.0.0
 * @version 1.0.0
 */
abstract class BaseTemplate extends TemplatePart
{
    protected $headerList;
    protected $sidebarList;
    protected $contentList;
    protected $footerList;
    protected $head;
    protected $navigation;
    protected $hasSidebar;
    
    public function __construct()
    {
        $this->hasSidebar = false;
        if ($this->getField('is_sidebar_active')) {
            $this->hasSidebar = true;
        }
        
        $this->head = new TemplatePart('head');
        
        $this->navigation = new Navigation('primary');

        $this->headerList = new RenderableList();
        $this->contentList = new RenderableList();
        $this->sidebarList = new RenderableList();
        $this->footerList = new RenderableList();
        $this->footerList->append(new Footer('footers/default-footer'));
    }

    final public function render() : void
    {
        $this->head->render();
        
        if ($this->navigation) {
            $this->navigation->render();
        }
        
        if ( !$this->headerList->empty()) {
            $this->headerList->render();
        }
        
        if ($this->hasSidebar) {
            echo '<div class="ct-main ct-main-sidebar"><div class="uk-container"><div class="uk-grid">';
            $this->sidebarList->render();
            echo '<main class="ct-main-content uk-width-2-3@m uk-width-3-4@l">';
        }
        else {
            echo '<main class="ct-main"><div class="ct-main-content">';
        }
        
        $this->contentList->render();
        
        
        if ($this->hasSidebar) {
            echo '</main></div></div></div>';
        }
        else {
            echo '</div></main>';
        }
        
        if ( !$this->footerList->empty()) {
            $this->footerList->render();
        }
    }
}
