<?php

use \Cubetech\Helpers\Helper;

if ( !$this->title || empty($this->title)) {
    return;
}
?>
<article class="uk-position-relative uk-margin-large-bottom ct-search-card uk-padding uk-width-1-1">

    <header class="ct-card-header">
        <h2><?php echo $this->title; ?></h2>
    </header>
	
	<div class="uk-grid">
		<?php if ( !empty($this->kamerabildURL)) : ?>
		    <div class="ct-kamerabild uk-width-auto uk-margin-medium-bottom uk-margin-remove-bottom-@l">
		        <figure class="ct-card-kamerabild-wrapper">
		            <img class="ct-card-kamerabild" src="<?php echo $this->kamerabildURL; ?>" data-uk-img alt="<?php echo $this->title; ?>"/>
		        </figure>    	
		    </div>
		<?php endif; ?>

		<div class="ct-kamerabild-beschreibung uk-width-auto">

			<div class="uk-child-width-auto uk-grid" >
				<?php if ( !empty($this->datum)) : ?>
					<div class="beschreibung-titel">
						<span class="uk-margin-small-right" uk-icon="calendar"></span>
						<span class="uk-text-bold">Datum:</span>
					</div>
					<div class="beschreibung-wert">       
						<?php 
						if ( !empty($this->getField('datum'))) :
							if ( !empty(DateTime::createFromFormat('Ymd', $this->getField('datum')))) :	
								// ACF speichert Werte immer im Format Ymd (YYYYMMDD) in der Datenbank ab. Daher muss ich diese nun konvertieren zu j F Y, damit sie für den Endbenutzer gut lesbar sind.
								// Falls diese Konvertierung fehlschlägt, den Titel des Post ausgeben
								echo (DateTime::createFromFormat('Ymd', $this->getField('datum'))->format('j F Y'));
							else:
								echo($this->title);
							endif;
						endif; ?>

					</div>					
				<?php endif; ?>	  
			</div>	
			
			<div class="uk-child-width-auto uk-grid" >
				<?php if ( !empty($this->uhrzeit)) : ?>
					<div class="beschreibung-titel">
						<span class="uk-margin-small-right" uk-icon="clock"></span>
						<span class="uk-text-bold">Uhrzeit:</span>
					</div>
					<div class="beschreibung-wert">
						<span><?php echo $this->uhrzeit ;?></span>
					</div>					
				<?php endif; ?>	  
			</div>						  

		</div>
	</div>

    <span class="arrow uk-position-bottom-right uk-margin-bottom uk-margin-right ct-hide-for-print" data-uk-icon="icon: arrow-right; ratio: 1.75;"></span>
    <a class="uk-position-cover" href="<?php echo $this->link; ?>"></a>

</article>
  