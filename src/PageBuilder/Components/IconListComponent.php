<?php

namespace Cubetech\PageBuilder\Components;

use Cubetech\Cards\IconListCard;
/**
 * IconList component class for pagebuilder
 *
 * @author Steeve Jeannin <steeve@cubetech.ch>
 * @version 1.0.0
 * @since 1.0.0
 */
class IconListComponent extends BaseComponent
{
    
    /**
     * Title of this component
     * Generated by acf singleline field
     *
     * @var string
     */
    protected $title;
    
    /**
     * General description of this component
     * Generated by acf multiline field
     *
     * @var string
     */
    protected $leadtext;
    
    /**
     * Constructor method for this component
     * Initializes title, file and description properties
     *
     * @param int $index
     * @param string containerClass
     *
     * @author Steeve Jeannin <steeve@cubetech.ch>
     * @since 1.0.0
     */
    public function __construct($index, $containerClass)
    {
        parent::__construct('IconList', $index, $containerClass);
        $this->title = $this->getComponentField('title');
        $this->leadtext = $this->getComponentField('leadtext');
        $this->iconlist = $this->getComponentRepeaterField("iconlist_repeater", ["icon_title", "icon_content", "icon_icon", "icon_link"]);
        $this->createCards();
    }
    
    private function createCards()
    {
        $cards = [];
        foreach ($this->iconlist as $iconCard) {
            $cards[] = new IconListCard($this->getId(), $iconCard);
        }
        $this->cards = $cards;
    }
    
    /**
     * Renders all readable contents of this component as a string
     *
     * @return string
     *
     * @author Alex Scherer <alex.scherer@cubetech.ch>
     * @since 1.0.0
     */
    public function renderToString()
    {
        $returnString = $this->title . ' ';
        foreach ($this->cards as $card) {
            $returnString .= $card->icon_title . ' ';
        }
        return $returnString;
    }
    
    /**
     * Validates the component for this particular component
     * At least one "icon" is needed
     *
     * @return boolean
     *
     * @author Steeve Jeannin <steeve@cubetech.ch>
     * @since 1.0.0
     */
    public function isValid()
    {
        if (count($this->iconlist) > 0) {
            return true;
        }
        else {
            return false;
        }
    }
}
