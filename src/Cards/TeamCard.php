<?php

namespace Cubetech\Cards;

use Cubetech\Base\Media;

/**
 * Class for an team card handling
 *
 * @author Steeve Jeannin <steeve@cubetech.ch>
 * @since 0.0.1
 * @version 1.0.0
 */
class TeamCard extends BaseCard
{
    /**
     * Initializes class properties
     *
     * @param CubetechPost $post
     * @return void
     *
     * @author Steeve Jeannin <steeve@cubetech.ch>
     * @version 1.0.0
     * @since 1.0.0
     */
    public function __construct($postId)
    {
        parent::__construct("TeamCard", $postId);
        $this->title = $this->getTitle();
        $this->imageId = intval($this->extractImage());
        $this->image = new Media((int) $this->imageId);
        $this->imageURL = $this->image ? $this->image->getImageUrl('full') : "";
        $this->function = $this->extractSimpleString("function");
        $this->email = $this->extractSimpleString("email");
        $this->phone = $this->extractSimpleString("phone");
        $this->socialMediaAccounts = $this->getRepeaterField("social_medias", ["social_media_link"]);
    }
    
    /**
     * Checks if the optional image is set
     * Defaults to false if empty
     *
     * @return string|false
     * @version 1.0.0
     * @since 1.0.0
     * @author Steeve Jeannin <steeve@cubetech.ch>
     */
    private function extractImage()
    {
        if ($this->getField('image')) {
            return $this->getField('image');
        }
        return false;
    }
    
    /**
     * Checks if any optional string field (simpleline, email, url, ...) is set
     * Defaults to empty if empty
     *
     * @return string
     * @version 1.0.0
     * @since 1.0.0
     * @author Steeve Jeannin <steeve@cubetech.ch>
     */
    private function extractSimpleString($elementId)
    {
        if ($this->getField($elementId)) {
            return $this->getField($elementId);
        }
        return "";
    }
}
