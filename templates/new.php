<?php require("header.php"); ?>
            <h2>Nouveau</h2>
            <p>Redirection</p>
            <div class="row-fluid">
              <div class="span8">
                  <?php if(!empty($flash['success'])): ?>
                  <div class="alert alert-success"><?php echo $flash['success']; ?></div>
                  <?php endif; ?>

                </div>
              <div class="span4">
              </div><!--/span-->
            </div><!--/row-->
<?php require("footer.php"); ?>