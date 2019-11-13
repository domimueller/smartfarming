<?php

namespace Cubetech\Templates;

use \Cubetech\Partials\Header;
use \Cubetech\Cards\SearchCard;
use \Cubetech\Rendering\TemplatePart;

/**
 * Template class for the default page template
 *
 * @author Marc Mentha <marc@cubetech.ch>
 * @since 1.0.0
 * @version 1.0.0
 */
class SearchTemplate extends BaseTemplate
{
    public function __construct()
    {
        parent::__construct('Search');
        $this->headerList->append(new Header('headers/search-header'));
        $this->contentList->append(new TemplatePart('search-results'));
    }
    
    public static function printSearchResults()
    {
        if (!have_posts()) {
            echo '<p calss="uk-text-center">'._x('Keine Suchresultate gefunden', 'Empty state message on search page', 'wptheme-basetheme');'</p>';
            return false;
        }
        
        $results = [];
        $user = wp_get_current_user();
        while (have_posts()) {
            the_post();
            $card = new SearchCard(get_the_id());
            $card->render();
        }
    }
}
