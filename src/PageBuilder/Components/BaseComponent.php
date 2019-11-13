<?php

namespace Cubetech\PageBuilder\Components;

use \Cubetech\Rendering\TemplatePart;

/**
 * Abstract BaseComponent describes the bare minimum any
 * PageBuilder component must provide and includes a final
 * render method for unified rendering
 *
 * @author Alex Scherer <alex.scherer@cubetech.ch>
 * @author Marc Mentha <marc@cubetech.ch>
 * @since 0.0.1
 * @version 1.0.0
 * @abstract
 */
abstract class BaseComponent extends TemplatePart
{
    protected $name;
    /**
     * The index (position) inside the pagbuilder.
     * Also used to get the values out of the database
     *
     * @var int
     */
    protected $index;
    
    /**
     * Initializes class properties
     *
     * @param string $name
     * @param int $index
     * @return void
     *
     * @author Marc Mentha <marc@cubetech.ch>
     * @author Alex Scherer <alex.scherer@cubetech.ch>
     * @version 1.0.0
     * @since 1.0.0
     */
    public function __construct($name, $index, $containerClass)
    {
        parent::__construct('components/' . $name);
        $this->name = $name;
        $this->index = $index;
        $this->containerClass = $containerClass;
    }
    
    /**
     * Checks if the current component is valid.
     * CAUTION: Only valid components will be rendered!
     *
     * @return bool
     *
     * @author Alex Scherer <alex.scherer@cubetech.ch>
     * @version 1.0.0
     * @since 1.0.0
     * @abstract
     */
    abstract public function isValid();
    
    /**
     * Creates the correct format for getting a value from the pagebuilder
     * without generating queries
     *
     * @param string $name of the acf field inside the pagebuilder
     * @return string
     *
     * @author Alex Scherer <alex.scherer@cubetech.ch>
     * @version 1.0.0
     * @since 1.0.0
     */
    protected function getFieldName($name)
    {
        return $this->name . '_fields_' . $name;
    }
    
    /**
     * Implementation specially for pagebuildercomponents values
     *
     * @param string $name
     * @param boolean $returnSingle
     * @return string
     *
     * @author Alex Scherer <alex.scherer@cubetech.ch>
     * @author Marc Mentha <marc@cubetech.ch>
     * @version 1.0.0
     * @since 1.0.0
     */
    protected function getComponentField($name, $returnSingle = true)
    {
        return $this->getField('pagebuilder_' . $this->index . '_' . $this->getFieldName($name), $returnSingle);
    }
    
    /**
     * Implementation specially for pagebuildercomponents repeater fields
     *
     * @param string $name
     * @param array $subFields
     * @param int $subIndex (for recursive call only)
     * @param string $subName (for recursive call only)
     * @return string
     *
     * @author Marc Mentha <marc@cubetech.ch>
     * @author Steeve Jeannin <steeve@cubetech.ch>
     * @version 1.0.0
     * @since 1.0.0
     */
    protected function getComponentRepeaterField($name, $subFields, $subIndex = -1, $subName = "")
    {
        $recursiveName = ($subIndex > -1) && !empty($subName) ? "_" . $subIndex . "_" . $subName : "";
        $count = $this->getComponentField($name . $recursiveName);
        $acfFlexibleContentLayout = [];
        if (is_array($count)) {
            // Possible in case of "Flexible Content" (Flexible Inhalte) instead of "Repeater" (Wiederholung)
            $acfFlexibleContentLayout = $count;
            $count = count($count);
        }
        $results = [];
        for ($i = 0; $i < $count; $i++) {
            $entry = new \stdClass();
            foreach ($subFields as $subKey => $subField) {
                if (is_array($subField)) {
                    $entry->$subKey = $this->getComponentRepeaterField($name, $subField, $i, $subKey);
                }
                else {
                    $fieldName = 'pagebuilder_' . $this->index . '_' . $this->getFieldName($name) . $recursiveName . '_' . $i . '_' . $subField;
                    $entry->$subField = $this->getField($fieldName);
                }
            }
            if (isset($acfFlexibleContentLayout[$i])) {
                $entry->acfFlexibleContentLayout = $acfFlexibleContentLayout[$i];
            }
            $results[] = $entry;
        }
        return $results;
    }
}