<?php
namespace Cubetech\Cards;

/**
 * Class for an unified search results ahndling
 * 
 * @author Marc Mentha <marc@cubetech.ch>
 * @since 0.0.1
 * @version 1.0.0
 */
class SearchCard extends BaseCard {

    const DIGEST_APPENDAGE = 200;
	/**
	 * Initializes class properties
	 *
	 * @return void
	 * 
	 * @author Marc Mentha <marc@cubetech.ch>
	 * @version 1.0.0
	 * @since Version 1.0.0
	 */
	public function __construct($postId) {
        parent::__construct("SearchCard", $postId);
        $this->title = $this->getTitle();
        $this->digest = $this->createDigest();
        $this->link = $this->getLink();
    }
    
    /**
	 * Generates a small text with the searchterm highlighted
	 *
	 * @return string
	 * 
	 * @author Marc Mentha <marc@cubetech.ch>
	 * @version 1.0.1
	 * @since Version 1.0.0
	 */
    private function createDigest() {
        $searchTerm = get_search_query();
        $trimedContent = trim($this->getContent());
        $contentLength = strlen($trimedContent);
        $position = stripos($trimedContent, $searchTerm);
        if ($position > self::DIGEST_APPENDAGE) {
            $digest = '...';
            if ($position + self::DIGEST_APPENDAGE < $contentLength) {
                $digest .= substr($trimedContent, $position - self::DIGEST_APPENDAGE, (self::DIGEST_APPENDAGE * 2));
                $digest .= '...';
            } else {
                $digest .= substr($trimedContent, $position - self::DIGEST_APPENDAGE);
            }
        } else {
            $digest = '';
            if ($position + self::DIGEST_APPENDAGE < $contentLength) {
                $digest .= substr($trimedContent, 0, (self::DIGEST_APPENDAGE * 2));
                $digest .= '...';
            } else {
                $digest .= substr($trimedContent, 0);
            }
        }
        return preg_replace("/\w*?$searchTerm\w*/iu", "<b>$0</b>", $digest);
    }
}