<div>
    <article class="uk-card uk-card-body uk-text-center uk-card-default ct-card-iconlist">
        <?php if ($this->image) : ?>
            <figure class="ct-card-iconlist-icon">
                <img src="#" data-src="<?php echo $this->image->getImageUrl('half'); ?>" alt="<?php echo
                $this->image->alt; ?>" class="image" data-uk-img/>
            </figure>
        <?php endif; ?>
        
        <header class="ct-card-header">
            <h3 class="ct-card-iconlist-title ct-hyphenate"><?php echo $this->icon_title; ?></h3>
        </header>
        
        <?php if ($this->icon_content) : ?>
            <div class="ct-card-iconlist-content">
                <?php echo wpautop($this->icon_content); ?>
            </div>
        <?php endif; ?>
        
        <?php if ($this->icon_link) : ?>
            <div class="ct-card-iconlist-link">
                <?php
                    echo "<a href='" . $this->icon_link["url"] . "'" . (empty($this->icon_link["target"]) ? "" : " target='" . $this->icon_link["target"] . "'") . ">" . ($this->icon_link["title"] ? $this->icon_link["title"] : $this->icon_link["url"]) . "</a>";
                ?>
            </div>
        <?php endif; ?>
    </article>
</div>
