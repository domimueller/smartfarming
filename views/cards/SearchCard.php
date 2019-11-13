<article class="uk-position-relative uk-margin-bottom ct-search-card uk-padding">

    <header class="ct-card-header">
        <h2><?php echo $this->title; ?></h2>
    </header>

    <div class="ct-saerch-result-text">
        <p><?php echo nl2br($this->digest); ?></p>
    </div>
    <span class="arrow uk-position-bottom-right uk-margin-bottom uk-margin-right ct-hide-for-print" data-uk-icon="icon: arrow-right; ratio: 1.75;"></span>
    <a class="uk-position-cover" href="<?php echo $this->link; ?>"></a>
</article>