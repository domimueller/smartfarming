<?php

namespace Cubetech\Templates;

use \Cubetech\Rendering\TemplatePart;
use \Cubetech\PageBuilder\PageBuilder;
use Cubetech\Partials\Header;

/**
 * Template class for the SinglePage template
 *
 * @author Steeve Jeannin <steeve@cubetech.ch>
 * @since 1.0.0
 * @version 1.0.0
 */
class SingleTemplate extends BaseTemplate
{
    /**
     * Constructor method is special for this template
     *
     * if the post has a pagebuidler, it will be rendered
     * if not, then the default content will be rendered
     */
    public function __construct()
    {
        if ($this->getField('pagebuilder')) {
            parent::__construct('PageBuilder');
            $this->contentList->append(new PageBuilder());
        } else {
            parent::__construct('Page');
            $this->contentList->append(new TemplatePart('default-page-content'));
        }
        $this->headerList->append(new Header('headers/default-header'));
    }
}