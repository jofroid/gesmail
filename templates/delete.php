<?php require("header.php"); ?>
            <h2><?php echo $box; ?>@assos.utc.fr</h2>
            <p><?php echo ucfirst($type); ?></p>
                Êtes-vous sur de vouloir supprimer définitivement la <?php echo $type; ?> <?php echo $box; ?>@assos.utc.fr et toutes les adresses qu'elle contient ?
                <form action="/gesmail/<?php echo $adr; ?>" method="post" class="form-inline">
                  <input type="hidden" name="_METHOD" value="DELETE" />
                  <input type="submit" class="btn btn-danger" value="Supprimer" /> <a href="/gesmail/<?php echo $adr; ?>" class="btn">Retour</a>
                </form>
<?php require("footer.php"); ?>