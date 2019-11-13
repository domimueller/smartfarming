<header class="ct-header ct-frontpage-header  uk-position-relative">
    <div class="uk-container-expand">
        <?php if ($this->image !== false): ?>
            <div class="image-wrapper">
                <?php echo $this->image->getUikitImage('header'); ?>
            </div>
        <?php endif; ?>
        <div class="uk-container ct-frontpage-content uk-position-cover">
            <h1 class="ct-page-title"><?php echo $this->pageTitle; ?></h1>
            <?php if ($this->lead) : ?>
                <p class="uk-text-lead ct-page-lead">
                    <?php echo nl2br($this->lead); ?>
                </p>
            <?php endif; ?>
        </div>
    </div>
</header>