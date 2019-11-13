<?php

namespace Cubetech\Cards;

use \Cubetech\Rendering\TemplatePart;

/**
 * Abstract BaseCard describes the bare minimum any
 * Card component must provide and includes a final
 * render method for unified rendering
 *
 * @author Marc Mentha <marc@cubetech.ch>
 * @since 0.0.1
 * @version 1.0.0
 */
abstract class BaseCard extends TemplatePart
{
    /**
     * Array containing all categories set to this post
     *
     * @var array
     */
    private $categories;
    
    /**
     * Initializes class properties
     *
     * @param string $name
     * @return void
     *
     * @author Marc Mentha <marc@cubetech.ch>
     * @version 1.0.0
     * @since 1.0.0
     */
    public function __construct($name, $postId)
    {
        parent::__construct('cards/'.$name, $postId);
    }

    
    /**
     * Checks if categories are set
     *
     * @return boolean
     *
     * @author Marc Mentha <marc@cubetech.ch>
     * @since 1.0.0
     * @version 1.0.0
     */
    public function hasCategories()
    {
        return count($this->categories) > 0;
    }
    
    /**
     * Gets the categories set to this post
     *
     * @return array Wp_term
     *
     * @author Marc Mentha <marc@cubetech.ch>
     * @since 1.0.0
     * @version 1.0.0
     */
    private function extractCategories()
    {
        $taxonomies = get_object_taxonomies($this->getPosttype(), "objects");
        if (count($taxonomies) > 0) {
            $categories = [];
            foreach ($taxonomies as $taxonomy) {
                $terms = get_the_terms($this->getId(), $taxonomy->name);
                if (is_array($terms)) {
                    foreach ($terms as $term) {
                        $categories[] = $term;
                    }
                }
            }
            return $categories;
        }
        return false;
    }
    
    /**
     * get a string with a delimiter of all categories names set to this post
     *
     * @param string $delimiter
     * @return string
     *
     * @author Marc Mentha <marc@cubetech.ch>
     * @since 1.0.0
     * @version 1.0.0
     */
    public function getCategoryNamesAsString($delimiter = ',')
    {
        if ($this->categories) {
            $string = '';
            foreach ($this->categories as $index => $category) {
                $string .= $category->name;
                if ($index + 1 < count($this->categories)) {
                    $string .= $delimiter . ' ';
                }
            }
            return $string;
        }
        return false;
    }
    
    /**
     * get a string with a delimiter of all categories slugs set to this post
     *
     * @param string $delimiter
     * @return string
     *
     * @author Marc Mentha <marc@cubetech.ch>
     * @since 1.0.0
     * @version 1.0.0
     */
    public function getCategorySlugsAsString($delimiter = ',')
    {
        if ($this->categories) {
            $string = '';
            foreach ($this->categories as $index => $category) {
                $string .= $category->slug;
                if ($index + 1 < count($this->categories)) {
                    $string .= $delimiter . ' ';
                }
            }
            return $string;
        }
        return null;
    }
    
    /**
     * Get a list of names of the categories set to this post
     *
     * @return array
     *
     * @author Marc Mentha <marc@cubetech.ch>
     * @since 1.0.0
     * @version 1.0.0
     */
    public function getCategoryNames()
    {
        if ($this->categories) {
            $categories = [];
            foreach ($this->categories as $category) {
                $categories[] = $category->name;
            }
            return $categories;
        }
        return false;
    }
    
    /**
     * Get a list of slug of the categories set to this post
     *
     * @return array
     *
     * @author Marc Mentha <marc@cubetech.ch>
     * @since 1.0.0
     * @version 1.0.0
     */
    public function getCategorySlugs()
    {
        if ($this->categories) {
            $slugs = [];
            foreach ($this->categories as $index => $category) {
                $slugs[] = $category->slug;
            }
            return $slugs;
        }
        return false;
    }
}
