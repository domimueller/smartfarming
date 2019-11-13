<?php

namespace Cubetech\Rendering;

/**
 * Baseclass for all templateparts
 *
 * @author Marc Mentha <marc@cubetech.ch>
 * @author Alex Scherer <alex.scherer@cubetech.ch>
 * @since 1.0.0
 * @version 1.0.0
 */
class TemplatePart
{
    /**
     * Post id of the current page/post
     *
     * @var int
     */
    protected $id;

    /**
     * Relative path inside views directory without file extension (i.E. headers/frontpage-header)
     *
     * @var string
     */
    protected $template;
    
    /**
     * Initializes the properties $template and $id
     *
     * @return void
     *
     * @param string $template
     * @param int $id
     * 
     * @author Marc Mentha <marc@cubetech.ch>
     * @author Alex Scherer <alex.scherer@cubetech.ch>
     * @since 1.0.0
     * @version 1.0.0
     */
    public function __construct(string $template, $id = false)
    {
        $this->template = get_template_directory() . '/views/' . $template .'.php';
        if ($id !== false) {
            $this->id = $id;
        }
    }

     /**
     * get a value from post meta added by acf
     *
     * @param string $key name of the meta field requested
     * @param bool $formatValue return a single value defaults to true
     * @return mixed string or integer
     *
     * @author Marc Mentha <marc@cubetech.ch>
     * @since 1.0.0
     * @version 1.0.0
     */
    public function getField(string $key, bool $formatValue = true)
    {
        return get_post_meta($this->getId(), $key, $formatValue);
    }
    
    /**
     * Get values of a repeater field without generating queries
     *
     * @param string $key
     * @param array $subFields
     * @return array with stdClasses including the requested values from subFields
     *
     * @author Marc Mentha <marc@cubetech.ch>
     * @since 1.0.0
     * @version 1.0.0
     */
    public function getRepeaterField(string $key, array $subFields) : array
    {
        $fieldCount = $this->getField($key, true);
        $fieldValues = [];
        for ($index = 0; $index < $fieldCount; $index++) {
            $entry = new \stdClass();
            foreach ($subFields as $subKey => $subField) {
                if (is_array($subField)) {
                    $entry->$subKey = $this->getRepeaterField($key . '_' . $index . '_' . $subKey, $subField);
                }
                else {
                    $entry->$subField = $this->getField($key . '_' . $index . '_' . $subField, true);
                }
            }
            $fieldValues[] = $entry;
        }
        return $fieldValues;
    }

    /**
     * Get a value saved in the WP_Post object revering to the id property
     *
     * @param string $key
     * @return mixed
     * 
     * @author Marc Mentha <marc@cubetech.ch>
     * @since 1.2.0
     * @version 1.0.0
     */
    public function getPostField(string $key)
    {
        return get_post_field($key, $this->getId(), false);
    }
    
    /**
     * Get post id
     *
     * If there is a defined $this->id property it returns this id
     * Defaults to get_the_id()
     * 
     * @return int id of post
     *
     * @author Marc Mentha <marc@cubetech.ch>
     * @since 1.0.0
     * @version 1.1.0
     */
    public function getId() : int 
    {
        if (property_exists($this, 'id') && $this->id) {
            return $this->id;
        } else {
            return get_the_id();
        }
    }

    /**
     * Calls debug information and includeTemplate
     *
     * @return void
     * 
     * @author Marc Mentha <marc@cubetech.ch>
     * @since 1.2.0
     * @version 1.0.0
     */
    public function render() : void
    {
        $this->printDebugMessage('<!-- Start ' . basename($this->template) . ' -->');
        $this->includeTemplate();
        $this->printDebugMessage('<!-- End ' . basename($this->template) . ' -->');
    }

    /**
     * Checks if templatefile exists and includes it
     *
     * @return void
     * 
     * @author Marc Mentha <marc@cubetech.ch>
     * @since 1.2.0
     * @version 1.0.0
     */
    public function includeTemplate() : void
    {
        if (!file_exists($this->template) && defined('WP_DEBUG') && WP_DEBUG) {
            echo '<p class="uk-text-danger uk-text-bold uk-text-center">Template: ' . $this->template . ' not found!</p>';
            return;
        }
        include $this->template;            
    }

    /**
     * Prints given message if WP_DEBUG is true
     *
     * @param string $message
     * @return void
     * 
     * @author Marc Mentha <marc@cubetech.ch>
     * @since 1.2.0
     * @version 1.0.0
     */
    public function printDebugMessage(string $message) : void
    {
        if (defined('WP_DEBUG') && WP_DEBUG) {
            echo $message;
        }
    }

    /**
     * Gets the post slug
     *
     * @return string
     *
     * @author Alex Scherer <alex.scherer@cubetech.ch>
     * @since 1.0.0
     * @version 1.0.0
     */
    public function getSlug()
    {
        return $this->getPostField('post_name');
    }
    
    /**
     * Gets the post title
     *
     * @return string
     *
     * @author Marc Mentha <marc@cubetech.ch>
     * @author Alex Scherer <alex.scherer@cubetech.ch>
     * @since 1.0.0
     * @version 1.0.0
     */
    public function getTitle()
    {
        return $this->getPostField('post_title');
    }
    
    /**
     * Gets the post excerpt
     *
     * @return string
     *
     * @author Marc Mentha <marc@cubetech.ch>
     * @author Alex Scherer <alex.scherer@cubetech.ch>
     * @since 1.0.0
     * @version 1.0.0
     */
    public function getExcerpt()
    {
        return $this->getPostField('post_excerpt');
    }
    
    /**
     * Gets the post content
     *
     * @return string
     *
     * @author Marc Mentha <marc@cubetech.ch>
     * @author Alex Scherer <alex.scherer@cubetech.ch>
     * @since 1.0.0
     * @version 1.0.0
     */
    public function getContent()
    {
        return $this->getPostField('post_content');
    }
    
    /**
     * Gets the posttype
     *
     * @return string
     *
     * @author Marc Mentha <marc@cubetech.ch>
     * @since 1.0.0
     * @version 1.0.0
     */
    public function getPosttype()
    {
        return $this->getPostField('post_type');
    }
    
    /**
     * Gets the post categories
     *
     * @return array
     *
     * @author Alex Scherer <alex.scherer@cubetech.ch>
     * @since 1.0.0
     * @version 1.0.0
     */
    public function getCategories()
    {
        die('TODO');
    }
    
    /**
     * Gets the posts authors id
     *
     * @return int
     *
     * @author Marc Mentha <marc@cubetech.ch>
     * @author Alex Scherer <alex.scherer@cubetech.ch>
     * @since 1.0.0
     * @version 1.0.0
     */
    public function getAuthorId()
    {
        return $this->getPostField('post_author');
    }
    
    /**
     * Gets the posts permalink
     *
     * @return string
     *
     * @author Marc Mentha <marc@cubetech.ch>
     * @author Alex Scherer <alex.scherer@cubetech.ch>
     * @since 1.0.0
     * @version 1.0.0
     */
    public function getLink()
    {
        return get_permalink($this->getId());
    }
    
    /**
     * Gets the posts creation date, optionally formatted with $format
     *
     * @param string $format
     * @return int|string
     *
     * @author Marc Mentha <marc@cubetech.ch>
     * @author Alex Scherer <alex.scherer@cubetech.ch>
     * @since 1.0.0
     * @version 1.0.0
     */
    public function getDate($format = false)
    {
        if ($format) {
            return date_i18n($format, strtotime($this->getPostField('post_date')));
        }
        return strtotime($this->getPostField('post_date'));
    }
    
    /**
     * Gets the posts modified date, optionally formatted with $format
     *
     * @param string $format
     * @return int|string
     *
     * @author Marc Mentha <marc@cubetech.ch>
     * @author Alex Scherer <alex.scherer@cubetech.ch>
     * @since 1.0.0
     * @version 1.0.0
     */
    public function getModified($format = false)
    {
        if ($format) {
            return date_i18n($format, strtotime($this->getPostField('post_modified')));
        }
        return strtotime($this->getPostField('post_modified'));
    }
}