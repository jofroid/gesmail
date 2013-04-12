<?php require("header.php"); ?>
           <h2><?php echo $box; ?>@assos.utc.fr</h2>
            <p>Mailing-liste</p>
            <div class="row-fluid">
              <div class="span8">
                  <?php if(!empty($flash['success'])): ?>
                  <div class="alert alert-success"><?php echo $flash['success']; ?></div>
                  <?php endif; ?>
                  <table class="table table-striped">
                    <thead>
                      <tr>
                        <th>Adresse mail</th>
                        <!--<th>Droits</th>-->
                        <th>Action</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr>
                        <?php foreach($destinataires as $id => $dest): ?>
                        <tr>
                          <td><?php echo $dest; ?></td>
                          <td><a class="btn" href="index_control.php?Delete=<?php echo $id; ?>&amp;adr=<?php echo $adr; ?>">Supprimer</a></td>
                        </tr>
                        <?php endforeach; ?>
                      </tr>
<!--                      <tr>
                        <td>jpascal@bla</td>
                        <td class="btn-group"><a class="btn">Modérateur</a></td>
                        <td class="btn-group"><a class="btn btn-info">Modifier</a> <a class="btn btn-danger">Supprimer</a></td>
                      </tr>-->
                      <tr>
                        <td>
                          <form action="index_control.php?Add=<?php echo $adr; ?>" method="post" class="form-horizontal">
                          <?php if(!empty($_SESSION['adderror'])): ?>
                          <div class="alert alert-error">
                            <?php echo $_SESSION['adderror']; unset($_SESSION['adderror']); ?>
                          </div>
                          <?php endif; ?>
                          <input type="text" name="email" />&nbsp;&nbsp;&nbsp;<input type="submit" class="btn" value="Ajouter &raquo;" />
                          </form>
                        </td>
                        <td></td>
                      </tr>
                    </tbody>
                  </table>
                  Tous les membres listés ci-dessus reçoivent les messages envoyés à la liste. Les modifications sont prises en compte immédiatement.
                </div>
              <div class="span4">
                <!--<h2>Options</h2>
                <form class="form" method="post" action="/gesmail/<?php echo $adr; ?>">                  
                  <input type="hidden" name="_METHOD" value="PUT" />
                  <label>
                    Nom de la liste :
                    <input type="text" value="Polar-Bureau"/>
                  </label>
                  <label>
                    Qui peut écrire sur la liste ?
                    <select name="">
                      <option value="">tout le monde</option>
                      <option value="">les membres de la liste</option>
                      <option value="">les modérateurs de la liste</option>
                    </select>
                  </label>
                  <label>
                    Réponse à :
                    <select name="">
                      <option value="">la liste</option>
                      <option value="">l'expéditeur du message</option>
                    </select>
                  </label>
                </form>
                <p><input type="submit" class="btn" value="Valider" /></p>-->
              </div><!--/span-->
            </div><!--/row-->
<?php require("footer.php"); ?>