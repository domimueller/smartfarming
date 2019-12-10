<?php

namespace Cubetech\Cards;
use Cubetech\Base\Media;


/**
 * Class for an unified card handling can
 * Cards can extend fro mthis class to apply this functionality
 *
 * @author Dominique Müller <dominiquepeter.mueller@students.bfh.ch>
 * @since 0.0.1
 * @version 1.0.0
 */
class KamerabildCard extends BaseCard
{
    /**
     * Initializes class properties
     *
     * @param CubetechPost $post
     * @return void
     *
     * @author Dominique Müller <dominiquepeter.mueller@students.bfh.ch>
     * @version 1.0.0
     * @since 1.0.0
     */
    public function __construct($postId)
    {
        parent::__construct("KamerabildCard", $postId);
        $this->title = $this->getTitle();
        $this->kamerabildId = intval($this->getField('image'));
        $this->kamerabild = new Media((int) $this->kamerabildId);
        $this->kamerabildURL = $this->kamerabild ? $this->kamerabild->getImageUrl('full') : "";
        $this->link = $this->getLink();  
        $this->date =$this->getField('date');
        $this->time = $this->getField('time');

    }

}