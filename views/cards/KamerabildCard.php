<?php

use \Cubetech\Helpers\Helper;

if ( !$this->title || empty($this->title)) {
    return;
}

/* make the date_time information machine readable and perform a sustainable error handling */
if ( !empty(DateTime::createFromFormat('Ymd', $this->date)->format('Y-m-j'))) :
	$semantic_date = (DateTime::createFromFormat('Ymd', $this->date)->format('Y-m-j'));
endif;

if ( !empty(DateTime::createFromFormat('H:i:s', $this->time)->format('H:i'))) :

	$semantic_time = (DateTime::createFromFormat('H:i:s', $this->time)->format('H:i'));
endif;

if ( !empty($semantic_time) && !empty($semantic_date)) :	
	$semantic_date_time = $semantic_date . ' ' . $semantic_time;
endif;
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
			
			<h3> Weitere Informationen </h3>

			<div class="uk-child-width-auto uk-grid uk-margin-small-top" >	
				<?php if ( !empty($this->date)) : ?>
					<div class="beschreibung-titel">
						<span class="uk-margin-small-right" uk-icon="calendar"></span>
						<span class="uk-text-bold">Datum:</span>
					</div>
					<div class="beschreibung-wert"> 
						<?php 
						
							if ( !empty(DateTime::createFromFormat('Ymd', $this->getField('date')))) :	
								// ACF speichert Werte immer im Format Ymd (YYYYMMDD) in der Datenbank ab. Daher muss ich diese nun konvertieren zu j F Y, damit sie für den Endbenutzer gut lesbar sind.
								// Falls diese Konvertierung fehlschlägt, den Titel des Post ausgeben
								echo (DateTime::createFromFormat('Ymd', $this->getField('date'))->format('j F Y'));
							else:
								echo($this->title);

							endif;
						?>

					</div>					
				<?php endif; ?>	  
			</div>	
			
			<div class="uk-child-width-auto uk-grid uk-margin-small-top" >
				<?php if ( !empty($this->time)) : ?>
					<div class="beschreibung-titel">
						<span class="uk-margin-small-right" uk-icon="clock"></span>
						<span class="uk-text-bold">Uhrzeit:</span>
					</div>
					<div class="beschreibung-wert">
						<time datetime=
						<?php
							if  ( !empty($semantic_date_time)) :
								echo('"' . $semantic_date_time . '"');
							endif;								
						?>>
							<?php echo $this->time ;?>
						</time>

					</div>					
				<?php endif; ?>	  
			</div>						  

		</div>
	</div>

    <span class="arrow uk-position-bottom-right uk-margin-bottom uk-margin-right ct-hide-for-print" data-uk-icon="icon: arrow-right; ratio: 1.75;"></span>
    <a class="uk-position-cover" href="<?php echo $this->link; ?>"></a>

</article>
  