
<?php

use Cubetech\Base\Media;

/* get the data */
$this->kamerabildId = intval($this->getField('image'));
$this->kamerabild = new Media((int) $this->kamerabildId);
$this->kamerabildURL = $this->kamerabild ? $this->kamerabild->getImageUrl('full') : "";

$this->title = apply_filters('the_title', get_post_field('post_title', get_the_id()));

$this->date = $this->getField('date');
$this->time = $this->getField('time');
?>

<div class="uk-container">

	<div class="uk-grid">
		
		    <div class="ct-kamerabild uk-width-1-1 uk-margin-large-bottom">
		        <figure class="ct-card-kamerabild-wrapper">
		            <img class="ct-card-kamerabild" src="<?php echo $this->kamerabildURL; ?>" data-uk-img alt="<?php echo $this->title; ?>"/>
		        </figure>    	
		    </div>
		

		<div class="ct-kamerabild-beschreibung uk-width-1-1">
			
			<h3> Weitere Informationen </h3>
			
			<div class="uk-child-width-auto uk-grid uk-margin-small-top" >
				<?php if ( !empty($this->date)) : ?>
					<div class="beschreibung-datum">
						<span class="uk-margin-small-right" uk-icon="calendar"></span>
						<span class="uk-text-bold">Datum:</span>
						<span>
						
						<?php 
						if ( !empty($this->getField('date'))) :
							if ( !empty(DateTime::createFromFormat('Ymd', $this->getField('date')))) :	
								// ACF speichert Werte immer im Format Ymd (YYYYMMDD) in der Datenbank ab. Daher muss ich diese nun konvertieren zu j F Y, damit sie für den Endbenutzer gut lesbar sind.
								// Falls diese Konvertierung fehlschlägt, den Titel des Post ausgeben
								echo (DateTime::createFromFormat('Ymd', $this->getField('date'))->format('j F Y'));
							else:
								echo($this->title);
							endif;
						endif; ?>

							
						</span>
					</div>					
				<?php endif; ?>	  
			</div>	
			
			<div class="uk-child-width-auto uk-grid uk-margin-small-top" >
				<?php if ( !empty($this->time)) : ?>
					<div class="beschreibung-uhrzeit">
						<span class="uk-margin-small-right" uk-icon="clock"></span>
						<span class="uk-text-bold">Uhrzeit:</span>
						<span><?php echo $this->time ;?></span>
					</div>					
				<?php endif; ?>	  
			</div>						  

		</div>
	</div

</div>
  