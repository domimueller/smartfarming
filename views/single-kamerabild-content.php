
<?php

use Cubetech\Base\Media;

/* get the data */
$this->kamerabildId = intval($this->getField('kamerabild'));
$this->kamerabild = new Media((int) $this->kamerabildId);
$this->kamerabildURL = $this->kamerabild ? $this->kamerabild->getImageUrl('full') : "";

$this->title = apply_filters('the_title', get_post_field('post_title', get_the_id()));

$this->datum = $this->getField('datum');
$this->uhrzeit = $this->getField('uhrzeit');
$this->breitengrad = $this->getField('breitengrad');
$this->langengrad = $this->getField('langengrad');

?>

<div class="uk-container">

	<div class="uk-grid">
		
		    <div class="ct-kamerabild uk-width-1-1 uk-margin-large-bottom">
		        <figure class="ct-card-kamerabild-wrapper">
		            <img class="ct-card-kamerabild" src="<?php echo $this->kamerabildURL; ?>" data-uk-img alt="<?php echo $this->title; ?>"/>
		        </figure>    	
		    </div>
		

		<div class="ct-kamerabild-beschreibung uk-width-1-1">

			<div class="uk-child-width-1-4 uk-grid uk-margin-small-top" >
				<?php if ( !empty($this->datum)) : ?>
					<div class="beschreibung-datum">
						<span class="uk-margin-small-right" uk-icon="calendar"></span>
						<span class="uk-text-bold">Datum:</span>
						<span>
													<?php 
							// ACF speichert Werte immer im Format Ymd (YYYYMMDD) in der Datenbank ab. Daher muss ich diese nun konvertieren zu j F Y, damit sie für den Endbenutzer gut lesbar sind.
							echo (DateTime::createFromFormat('Ymd', $this->getField('datum'))->format('j F Y'));
						?>

							
						</span>
					</div>					
				<?php endif; ?>	  
			</div>	
			
			<div class="uk-child-width-1-4 uk-grid uk-margin-small-top" >
				<?php if ( !empty($this->uhrzeit)) : ?>
					<div class="beschreibung-uhrzeit">
						<span class="uk-margin-small-right" uk-icon="clock"></span>
						<span class="uk-text-bold">Uhrzeit:</span>
						<span><?php echo $this->uhrzeit ;?></span>
					</div>					
				<?php endif; ?>	  
			</div>						  

			<div class="uk-child-width-1-4 uk-grid uk-margin-small-top" >
				<?php if ( !empty($this->breitengrad)) : ?>
					<div class="beschreibung-breitengrad">
						<span class="uk-margin-small-right" uk-icon="location"></span>
						<span class="uk-text-bold">Breitengrad:</span>
						<span><?php echo $this->breitengrad ;?></span>
					</div>					
				<?php endif; ?>	  
			</div>

			<div class="uk-child-width-1-4 uk-grid uk-margin-small-top" >
				<?php if ( !empty($this->langengrad)) : ?>
					<div class="beschreibung-titel">
						<span class="uk-margin-small-right" uk-icon="location"></span>
						<span class="uk-text-bold">Längengrad:</span>
						<span><?php echo $this->langengrad ;?></span>
					</div>					
				<?php endif; ?>	  
			</div>						

		</div>
	</div

</div>
  