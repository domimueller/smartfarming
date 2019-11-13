<?php


namespace Cubetech\Partials;


use Cubetech\Base\Media;
use Cubetech\Helpers\Helper;
use Cubetech\Rendering\TemplatePart;

class FrontPageHeader extends TemplatePart
{
    
    public function __construct($template)
    {
        parent::__construct($template);
        $imageId = $this->getField('header_image');
        $this->image = $imageId ? new Media((int)$imageId) : false;
        $this->lead = $this->getField('lead');
        $this->extractPageTitle();
    }
    
    
    /**
     * Checks if an optional page title isset
     * and either sets the pageTitle property this value
     * or the post_title
     *
     * @return void
     *
     * @author Marc Mentha <marc@cubetech.ch>
     * @since 1.0.0
     * @version 1.0.0
     */
    private function extractPageTitle()
    {
        if ($this->getField('optional_page_title')) {
            $this->pageTitle = Helper::shyify($this->getField('optional_page_title'));
        }
        else {
            $this->pageTitle = get_the_title($this->getId());
        }
    }
    
}