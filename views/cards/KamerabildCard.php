<?php

use \Cubetech\Helpers\Helper;

if ( !$this->title || empty($this->title)) {
    return;
}
?>
<article class="uk-position-relative uk-margin-bottom ct-search-card uk-padding uk-width-1-1">

    <header class="ct-card-header">
        <h2><?php echo $this->title; ?></h2>
    </header>

    <div class="ct-saerch-result-text">

        <figure class="ct-card-team-image-wrapper">
            <img class="ct-card-team-image" src="<?php echo $this->kamerabildURL; ?>" data-src="" data-uk-img alt="<?php echo $this->title; ?>"/>
        </figure>    	
      
    </div>
    <span class="arrow uk-position-bottom-right uk-margin-bottom uk-margin-right ct-hide-for-print" data-uk-icon="icon: arrow-right; ratio: 1.75;"></span>
    <a class="uk-position-cover" href="<?php echo $this->link; ?>"></a>

</article>
