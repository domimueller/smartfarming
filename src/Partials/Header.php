<?php


namespace Cubetech\Partials;


use Cubetech\Base\Media;
use Cubetech\Rendering\TemplatePart;
use Cubetech\Helpers\Helper;

class Header extends TemplatePart
{
    public function __construct(string $template)
    {
        parent::__construct($template);
        $imageId = $this->getField('header_image');
        $this->image = $imageId ? new Media((int)$imageId) : false;
        $this->lead = $this->getField('page_lead');
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
    public function extractPageTitle()
    {
        if ($this->getField('optional_page_title')) {
            $this->pageTitle = Helper::shyify($this->getField('optional_page_title'));
        }
        else {
            $this->pageTitle = get_the_title($this->getId());
        }
    }
}