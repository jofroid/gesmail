<?php require("header.php"); ?>
            <h2><?php echo $box; ?>@assos.utc.fr</h2>
            <p>Redirection</p>
            <div class="row-fluid">
              <div class="span8">
                  <?php if(!empty($flash['success'])): ?>
                  <div class="alert alert-success"><?php echo $flash['success']; ?></div>
                  <?php endif; ?>
                  <table class="table table-striped">
                    <thead>
                      <tr>
                        <th>Adresse mail</th>
                        <th>Action</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php foreach($destinataires as $id => $dest): ?>
                      <tr>
                        <td><?php echo $dest; ?></td>
                        <td>
                          <form action="/gesmail/<?php echo $adr; ?>/<?php echo $dest; ?>" method="post" class="butseul form-inline">
                            <input type="hidden" name="_METHOD" value="DELETE" />
                            <input type="submit" class="btn" value="Supprimer" />
                          </form>
                        </td>
                      </tr>
                      <?php endforeach; ?>
                      <tr>
                        <form action="/gesmail/<?php echo $adr; ?>" method="post" class="form-horizontal">
                        <td>
                          <?php if(!empty($flash['adderror'])): ?>
                          <div class="alert alert-error">
                            <?php echo $flash['adderror']; ?>
                          </div>
                          <?php endif; ?>
                          <input type="text" name="email" />&nbsp;&nbsp;&nbsp;<input type="submit" class="btn" value="Ajouter &raquo;" />
                        </td>
                        <td>&nbsp;</td>
                        </form>
                      </tr>
                    </tbody>
                  </table>
                  Toutes les adresses listées ci-dessus reçoivent les messages envoyés à la liste.
                </div>
              <div class="span4">
                <h2>Options</h2>
                <form class="form" method="post" action="/gesmail/<?php echo $adr; ?>">                  
                  <input type="hidden" name="_METHOD" value="PUT" />
                  <?php if($adr > 0): ?>
                  <label class="checkbox">
                    <input type="checkbox" name="includeroot" value="oui"<?php echo $options['includeroot']; ?> />
                    Inclure <?php echo $asso; ?>@assos.utc.fr
                    <p class="help-block">Une copie du mail est envoyée à l'adresse principale (redirection et/ou boîte)</p>
                  </label>
                  <?php else: ?>
                    <label class="checkbox">
                    <input type="checkbox" name="includeroot" value="oui"<?php echo $options['includeroot']; ?> />
                      Garder les messages dans la boîte de l'assos
                      <p class="help-block">Recommandé, facilite les passations</p>
                    </label>
                    <?php endif; ?>
                  <input type="submit" class="btn" value="Valider" />
                </form>
                <?php if($adr > 0): ?>
                <h2>Suppression</h2>
                Pour supprimer définitivement l'alias et son contenu
                <form class="form" method="get" action="/gesmail/<?php echo $adr; ?>">
                  <input type="submit" class="btn btn-danger" value="Supprimer" />
                </form>
                <?php endif; ?>
              </div><!--/span-->
            </div><!--/row-->
<?php require("footer.php"); ?>