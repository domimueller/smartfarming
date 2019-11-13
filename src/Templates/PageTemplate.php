<?php

namespace Cubetech\Templates;

use Cubetech\Partials\Header;
use \Cubetech\Rendering\TemplatePart;

/**
 * Template class for the default page template
 *
 * @author Marc Mentha <marc@cubetech.ch>
 * @since 1.0.0
 * @version 1.0.0
 */
class PageTemplate extends BaseTemplate
{
    public function __construct()
    {
        parent::__construct('Page');
        $this->headerList->append(new Header('headers/default-header'));
        $this->contentList->append(new TemplatePart('default-page-content'));
    }
}