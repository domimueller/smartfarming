<div class="uk-container">
    <div data-uk-filter="target: .archive-post-list">
        <?php if ($this->filters) : ?>
            <ul class="uk-subnav uk-subnav-pill">
                <?php if ($this->lazyload) : ?>
                    <li class="<?php echo empty($this->activeFilters) ? 'uk-active' : ''; ?>">
                        <a class="ct-filter" href="./"><?php echo _x('Alle', 'All filter button text', 'wptheme-basetheme'); ?></a>
                    </li>
                    <?php foreach ($this->filters as $filter) : ?>
                        <?php $valTaxonomy = $filter->taxonomy . "=" . $filter->term_taxonomy_id; ?>
                        <?php $active = in_array($valTaxonomy, explode(";", $this->activeFilters)); ?>
                        <li class="<?php echo $active ? 'uk-active' : ''; ?>">
                            <a class="ct-filter" href="<?php echo "?filter-" . $valTaxonomy; ?>"><?php echo $filter->name; ?></a>
                        </li>
                    <?php endforeach; ?>
                <?php else : ?>
                    <li class="<?php echo $this->activeFilterTerm === '*' ? 'uk-active' : ''; ?>" data-uk-filter-control>
                        <a class="ct-filter" href="#"><?php echo _x('Alle', 'All filter button text', 'wptheme-basetheme'); ?></a>
                    </li>
                    <?php foreach ($this->filters as $filter) : ?>
                        <li class="<?php echo $this->activeFilterTerm === $filter->slug ? 'uk-active' : ''; ?>" data-uk-filter-control="[data-term*='<?php echo $filter->slug; ?>']">
                            <a class="ct-filter" href="#"><?php echo $filter->name; ?></a>
                        </li>
                    <?php endforeach; ?>
                <?php endif; ?>
            </ul>
        <?php endif; ?>

        <?php if ($this->cards) : ?>
            <?php $wrapperClass = "uk-margin-bottom " . $this->childWidth; ?>
            <div id="ct-filterable-post-container" class="archive-post-list uk-grid <?php echo $this->lazyload ? "" : "uk-margin-large-top"; ?> uk-flex-center" data-ct-lazyload-target>
                <?php
                    foreach ($this->cards as $card) {
                        $card->wrapperClass = $wrapperClass;
                        $card->render();
                    }
                ?>
            </div>
            <?php if ($this->lazyload && !$this->allCardsLoaded) : ?>
                <div class="uk-margin-top uk-margin-bottom uk-text-center lazyload-button-container">
                    <button class="uk-button uk-button-primary button-lazyload lazyload-button-trigger"
                        data-ct-lazyload-trigger
                        data-ct-lazyload-post-type="<?php echo $this->posttype; ?>"
                        data-ct-lazyload-page="2"
                        data-ct-lazyload-posts-per-page="<?php echo $this->maxCardsLoad; ?>"
                        data-ct-lazyload-wrapper-class="<?php echo $wrapperClass; ?>"
                        data-ct-lazyload-filter="<?php echo $this->activeFilters; ?>"
                        ><?php echo _x('Mehr laden', 'news overview lazyload button', 'wptheme-basetheme'); ?></button>
                    <div class="hidden lazyload-button-spinner" data-ct-lazyload-spinner data-uk-spinner></div>
                </div>
            <?php endif; ?>
        <?php else : ?>
            <p class="uk-text-center"><?php echo _x('Keine Inhalte gefunden', 'Empty state message on archive pages', 'wptheme-basetheme'); ?></p>
        <?php endif; ?>
    </div>
</div>
