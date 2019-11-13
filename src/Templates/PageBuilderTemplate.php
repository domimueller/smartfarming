<?php

namespace Cubetech\Templates;

use Cubetech\Partials\Header;
use \Cubetech\Rendering\TemplatePart;
use \Cubetech\PageBuilder\PageBuilder;
use Cubetech\TemplateLogic\SidebarLogic;
use Cubetech\TemplateLogic\HeaderLogic;

/**
 * Template class for the page builder template
 *
 * @author Alex Scherer <alex.scherer@cubetech.ch>
 * @since 1.0.0
 * @version 1.0.0
 */
class PageBuilderTemplate extends BaseTemplate
{
    public function __construct()
    {
        parent::__construct('PageBuilder');
        $this->headerList->append(new Header('headers/default-header'));
        if ($this->hasSidebar) {
            $this->sidebarList->append(new TemplatePart('sidebars/default-sidebar'));
        }
        $this->contentList->append(new PageBuilder());
    }
}