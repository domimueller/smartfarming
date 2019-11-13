<?php

namespace Cubetech\Templates;

use \Cubetech\Rendering\TemplatePart;
use Cubetech\Partials\FrontPageHeader;

/**
 * Template class for the front page
 *
 * @author Marc Mentha <marc@cubetech.ch>
 * @since 1.0.0
 * @version 1.0.0
 */
class FrontPageTemplate extends BaseTemplate
{
    
    public function __construct()
    {
        parent::__construct('FrontPage');
        $this->headerList->append(new FrontPageHeader('headers/front-page-header'));
    }
}
