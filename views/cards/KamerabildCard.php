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
	
	<div class="uk-grid">
		<?php if ( !empty($this->kamerabildURL)) : ?>
		    <div class="ct-kamerabild uk-width-2-3">
		        <figure class="ct-card-kamerabild-wrapper">
		            <img class="ct-card-kamerabild" src="<?php echo $this->kamerabildURL; ?>" data-uk-img alt="<?php echo $this->title; ?>"/>
		        </figure>    	
		    </div>
		<?php endif; ?>

		<div class="ct-kamerabild-beschreibung uk-width-1-3">

			<div class="uk-child-width-1-2 uk-grid" >
				<?php if ( !empty($this->datum)) : ?>
					<div class="beschreibung-titel">
						<span>Datum</span>
					</div>
					<div class="beschreibung-wert">
						<span><?php echo $this->datum ;?></span>
					</div>					
				<?php endif; ?>	  
			</div>	
			
			<div class="uk-child-width-1-2 uk-grid" >
				<?php if ( !empty($this->uhrzeit)) : ?>
					<div class="beschreibung-titel">
						<span>Uhrzeit</span>
					</div>
					<div class="beschreibung-wert">
						<span><?php echo $this->uhrzeit ;?></span>
					</div>					
				<?php endif; ?>	  
			</div>						  

			<div class="uk-child-width-1-2 uk-grid" >
				<?php if ( !empty($this->breitengrad)) : ?>
					<div class="beschreibung-titel">
						<span>Breitengrad</span>
					</div>
					<div class="beschreibung-wert">
						<span><?php echo $this->breitengrad ;?></span>
					</div>					
				<?php endif; ?>	  
			</div>

			<div class="uk-child-width-1-2 uk-grid" >
				<?php if ( !empty($this->langengrad)) : ?>
					<div class="beschreibung-titel">
						<span>Längengrad</span>
					</div>
					<div class="beschreibung-wert">
						<span><?php echo $this->langengrad ;?></span>
					</div>					
				<?php endif; ?>	  
			</div>						

		</div>
	</div>

    <span class="arrow uk-position-bottom-right uk-margin-bottom uk-margin-right ct-hide-for-print" data-uk-icon="icon: arrow-right; ratio: 1.75;"></span>
    <a class="uk-position-cover" href="<?php echo $this->link; ?>"></a>

</article>
  