<div <?php echo (empty($this->wrapperClass) ? '' : ' class="' . $this->wrapperClass . '"') .
               ($this->hasCategories() ? ' data-term="' . $this->getCategorySlugsAsString('') . '"' : ''); ?>>

    <article class="uk-card">
        <header class="ct-card-header">
            <h3 class="ct-card-title"><?php echo $this->title; ?></h3>
        </header>
        
        <?php if ($this->getField('lead')) : ?>
            <div class="ct-card-content">
                <?php echo $this->lead; ?>
            </div>
        <?php endif; ?>
        
        <a href="<?php echo $this->link; ?>" class="uk-position-cover"></a>
    </article>

</div>