<?php

namespace Cubetech\Cards;
use Cubetech\Base\Media;


/**
 * Class for an unified card handling can
 * Cards can extend fro mthis class to apply this functionality
 *
 * @author Marc Mentha <marc@cubetech.ch>
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
     * @author Marc Mentha <marc@cubetech.ch>
     * @version 1.0.0
     * @since 1.0.0
     */
    public function __construct($postId)
    {
        parent::__construct("KamerabildCard", $postId);
        $this->title = $this->getTitle();
        $this->kamerabildId = intval($this->getField('kamerabild'));
        $this->kamerabild = new Media((int) $this->kamerabildId);
        $this->kamerabildURL = $this->kamerabild ? $this->kamerabild->getImageUrl('full') : "";
        $this->datum = $this->getField('datum');
        $this->uhrzeit = $this->getField('uhrzeit');
        $this->breitengrad = $this->getField('breitengrad');
        $this->langengrad = $this->getField('langengrad');

    }
}