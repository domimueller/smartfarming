<?php

namespace Cubetech\Templates;

use \Cubetech\Rendering\TemplatePart;

/**
 * Template class for the index.php template
 *
 * @author Alex Scherer <alex.scherer@cubetech.ch>
 * @since 1.0.0
 * @version 1.0.0
 */
class IndexTemplate extends BaseTemplate
{
    public function __construct()
    {
        parent::__construct('Index');
        $this->contentList->append(new TemplatePart('post-list'));
    }
}