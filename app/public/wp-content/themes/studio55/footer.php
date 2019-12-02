<div class="container-fluid footer">
    <div class="row d-flex align-items-top">
      <div class="col-xs-12 col-sm-4 text-center justify-content-center">
        <h3>Explore</h3>
      <?php wp_nav_menu(array(
        'theme_location' => 'footerMenu'
        )) ?>
      </div>
      <div class="col-xs-12 col-sm-4 text-center justify-content-center">
        <h3>Legal</h3>
      <?php wp_nav_menu(array(
        'theme_location' => 'legalMenu'
        )) ?>
      </div>
      <div class="col-xs-12 col-sm-4 text-center justify-content-center">
        <h3>Stay Connected</h3>
        <div><?php echo DISPLAY_ULTIMATE_PLUS(); ?></div>
      </div>  
    </div>
    <div class="row d-flex align-items-center">
      <div class="col text-center justify-content-center">
        <p>2019 Copyright Reserved KAD Enterprises </p>
      </div>
      </div>
  </div>

<?php wp_footer(); ?>
</body>
</html>