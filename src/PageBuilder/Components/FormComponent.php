<?php

namespace Cubetech\PageBuilder\Components;

/**
 * Title/Text component class for pagebuilder
 *
 * @author Alex Scherer <alex.scherer@cubetech.ch>
 * @version 1.0.0
 * @since 1.0.0
 */
class FormComponent extends BaseComponent
{
    /**
     * ID of the gravity form
     *
     * @var string
     */
    protected $formId;
    
    /**
     * Constructor method for this component
     * Initializes title and formId properties
     *
     * @param int $index
     *
     * @author Alex Scherer <alex.scherer@cubetech.ch>
     * @since 1.0.0
     */
    public function __construct($index, $containerClass)
    {
        parent::__construct('Form', $index, $containerClass);
        $this->formId = $this->getComponentField('form');
    }

    /**
     * Creates the shortcode according to the settings
     *
     * @return string
     * 
     * @author Marc Mentha <marc@cubetech.ch>
     * @since 1.0.0
     * @version 1.0.0
     */
    public function createShortcode()
    {
        $shortcode = '[gravityform id='.$this->formId;
        if ($this->getComponentField('show_description')) {
            $shortcode .= ' decription=true';        
        } else {
            $shortcode .= ' decription=false';        
        }
        if ($this->getComponentField('show_title')) {   
            $shortcode .= ' title=true';        
        } else {
            $shortcode .= ' title=false';        
        }
        $shortcode .= ' ajax=true]';
        return $shortcode;
    }
    
    /**
     * Returns the text of the component as a string
     *
     * @return string
     *
     * @author Alex Scherer <alex.scherer@cubetech.ch>
     * @since 1.0.0
     */
    public function renderToString()
    {
        return '';
    }
    
    /**
     * Validates the component for this particular component
     * Both the title and text properties are needed
     * and therefore both must have been set
     *
     * @return boolean
     *
     * @author Marc Mentha <marc@cubetech.ch>
     * @since 1.0.0
     */
    public function isValid()
    {
        if ( !empty($this->formId)) {
            return true;
        }
        return false;
    }
}