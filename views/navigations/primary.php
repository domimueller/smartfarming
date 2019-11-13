<nav class="navigation">
    <?php $this->printLogo(); ?>
    <label class="navigation-toggler" for="toggle-navigation">menu</label>
    <input type="checkbox" id="toggle-navigation" class="naviagtion-checkbox"/>
    <?php $this->printRecursiveNavigation(3); ?>
</nav>