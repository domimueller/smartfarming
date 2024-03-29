<?php

namespace Cubetech\Partials;

use Cubetech\Rendering\TemplatePart;

/**
 * Class to hanlde Archivepages with filters
 * filters are generated by categories available and
 * will not exclude empty categories (No posts assigned)
 *
 * @author Marc Mentha <marc@cubetech.ch>
 * @since 1.0.0
 * @version 1.0.0
 */
class FilterablePostList extends TemplatePart
{
    /**
     * Namespace for cards as string to instantiate cards for the given posttype
     */
    const CARD_NAMESPACE = '\\Cubetech\\Cards\\';
    
    protected $viewDirectory;
    
    /**
     * List of active WP_Terms
     *
     * @var array of WP_Term
     */
    protected $activeFilters;

    /**
     * List of available WP_Terms
     *
     * @var array of WP_Term
     */
    protected $filters;
    
    /**
     * List of card objects of the specific posttype to display
     *
     * @var array
     */
    protected $cards;
    
    /**
     * Ho wide a single card should be displayed
     * in frontend
     *
     * @var string
     */
    public $childWidth;
    
    /**
     * What's the current posttype
     *
     * @var string
     */
    public $posttype;

    /**
     * If there is a GET parameter with a term
     * this variable keeps track of it and is needed
     * to set the correct filter in frontend
     *
     * @var string
     */
    public $activeFilterTerm;
    
    /**
     * Constructor function will initialize the cards
     * and filters
     * Cards are initialized with the posttype
     * If the GET parameter term is set, it will be sanitized and
     * later used in frontend to determine which filter should be active stated
     *
     * @param string $posttype
     * @param string $childWidth   (default: "uk-width-1-2@m")
     * @param int    $maxCardsLoad (default: -1, ALL cards, without LazyLoad)
     */
    public function __construct($posttype, $childWidth = null, $maxCardsLoad = -1)
    {
        $childWidth = is_null($childWidth) ? 'uk-width-1-2@m' : $childWidth;

        $this->posttype = $posttype;
        $this->childWidth = $childWidth;
        $this->maxCardsLoad = $maxCardsLoad;
        $this->lazyload = ($maxCardsLoad !== -1);
        $this->filters = $this->createFilters($posttype);
        $this->activeFilters = $this->getActiveFilters();
        $this->cards = $this->createCards($posttype, $maxCardsLoad);
        if (isset($_GET['term']) && !is_array($_GET['term'])) {
            $this->activeFilterTerm = sanitize_text_field($_GET['term']);
        }
        else {
            $this->activeFilterTerm = '*';
        }
        $this->viewDirectory = get_template_directory() . '/views/filterable-post-list.php';
    }
    
    /**
     * Create a list of available categories
     *
     * @param string $posttype
     * @return array list of WP_Term
     *
     * @author Marc Mentha <marc@cubetech.ch>
     * @since 1.0.0
     * @version 1.0.0
     */
    private function createFilters($posttype)
    {
        if ($posttype === 'post') {
            $filters = get_terms('category');
        }
        else {
            $filters = get_terms($posttype . '-category');
        }
        if ($filters instanceof \WP_Error) {
            return false;
        }
        else {
            return $filters;
        }
    }

    /**
     * Returns the currently set filters as a string for use in
     * the lazyload data-attributes.
     * 
     * @param string $separator (default value: ";")
     * @return string All parameters listed as "ref=val", with separator
     * 
     * @author Steeve Jeannin <steeve@cubetech.ch>
     * @since 1.0.0
     * @version 1.0.0
     */
    private function getActiveFilters(string $separator = ";")
    {
        $filters = [];
        if (!empty($_GET)) {
            foreach ($_GET as $key => $val) {
                if (substr($key, 0, 7) === "filter-") {
                    if (is_array($val)) {
                        foreach ($val as $name) {
                            $filters[] = substr($key, 7) . "=" . $name;
                        }
                    }
                    else {
                        $filters[] = substr($key, 7) . "=" . $val;
                    }
                }
            }
        }
        return implode($separator, $filters);
    }

    /**
     * Takes the posttype and genereates a string with the
     * full path and Namespace to the card related to this posttype
     *
     * @param string $posttype
     * @param int $postsPerPage
     * @return array cards extending from BaseCard
     *
     * @author Marc Mentha <marc@cubetech.ch>
     * @since 1.0.0
     * @version 1.0.0
     */
    private function createCards($posttype, $postsPerPage)
    {
        $nbrCardsToRead = $postsPerPage === -1 ? $postsPerPage : $postsPerPage + 1;

        $postArgs = [
            'post_type'      => $posttype,
            'posts_per_page' => $nbrCardsToRead,
            'fields'         => 'ids'
        ];

        $filters = [];
        if (!empty($_GET)) {
            foreach ($_GET as $key => $var) {
                if (substr($key, 0, 7) === "filter-") {
                    $filters[substr($key, 7)] = $var;
                }
            }
        }

        $taxQuery = [];
        if (!empty($filters)) {
            foreach ($filters as $name => $filter) {
                if (is_array($filter)) {
                    foreach ($filter as $key => $id) {
                        $taxQuery[] = [
                            'taxonomy' => $name,
                            'field'    => 'term_id',
                            'terms'    => $id
                        ];
                    }
                }
                else {
                    $taxQuery[] = [
                        'taxonomy' => $name,
                        'field'    => 'term_id',
                        'terms'    => $filter
                    ];
                }
            }
            $postArgs['tax_query'] = $taxQuery;
        }

        $posts = get_posts($postArgs);

        if (!empty($posts)) {
            $cards = [];
            if ($posttype === 'post') {
                $cardName = self::CARD_NAMESPACE . 'StandardCard';
            }
            else {
                $cardName = self::CARD_NAMESPACE . ucwords($posttype) . 'Card';
                if (!class_exists($cardName)) {
                    $cardName = self::CARD_NAMESPACE . 'StandardCard';
                }
            }
            foreach ($posts as $post) {
                $cards[] = new $cardName($post);
            }

            $this->allCardsLoaded = ( count($cards) <= $postsPerPage );
            if ($postsPerPage !== -1 && $this->allCardsLoaded === false) {
                // The last card was read only to know if it exists more cards to load
                // We can remove it now (only if all cards are loaded)...
                array_pop($cards);
            }
            return $cards;
        }
        return false;
    }
    
    /**
     * Inherited function from IRenderable
     * Will get the template and includes it
     *
     * @return void
     *
     * @author Marc Mentha <marc@cubetech.ch>
     * @since 1.0.0
     * @version 1.0.0
     */
    public function render() : void
    {
        $this->printDebugMessage('<!-- BEGIN FilterablePostList -->');
        $this->includeViewDirectory();
        $this->printDebugMessage('<!-- END FilterablePostList -->');
    }

    /**
     * Checks if file viewDirectory exists and includes it
     * 
     * @return void
     * 
     * @author Marc Mentha <marc@cubetech.ch>
     * @author Steeve Jeannin <steeve@cubetech.ch>
     * @since 1.0.0
     * @version 1.0.0
     */
    private function includeViewDirectory() : void
    {
        if (!file_exists($this->viewDirectory) && defined('WP_DEBUG') && WP_DEBUG) {
            echo '<p class="uk-text-danger uk-text-bold uk-text-center">File: ' . $this->viewDirectory . ' not found!</p>';
            return;
        }
        include $this->viewDirectory;
    }
}
