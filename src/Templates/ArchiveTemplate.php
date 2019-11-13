<?php

namespace Cubetech\Templates;

use \Cubetech\Partials\FilterablePostList;
use Cubetech\Rendering\TemplatePart;
use Cubetech\Partials\Header;

/**
 * Template class for the index.php template
 *
 * @author Alex Scherer <alex.scherer@cubetech.ch>
 * @since Version 1.0
 */
class ArchiveTemplate extends BaseTemplate
{
    public function __construct()
    {
        parent::__construct('Archive');
        $this->headerList->append(new Header('headers/default-header'));
        
        if ($this->getField('posttype')) {
            $posttype = $this->getField('posttype');
        } else {
            $posttype = 'post';
        }
        $this->contentList->append(new FilterablePostList($posttype));
    }
}
