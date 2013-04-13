<?php
require("db.php");

function genpass(){
  $s = "abcdefghijklmnopqrstuvwxyz";
  $str = "";
  for($i=0;$i<8;$i++)
    $str .= $s[rand(0,25)];
  return $str;
}

$stmt = $db->prepare("SELECT Asso, Extension FROM gesmail WHERE Type LIKE 'ml' AND MLPassword IS NULL");
$stmt->bind_result($asso, $extension);
$stmt->execute();
$stmt->store_result();
while($stmt->fetch()){
  $output = '';
  $box = escapeshellarg("$asso-$extension");
  $owner = escapeshellarg("$asso@assos.utc.fr");
  $pass = genpass();

  exec("/usr/lib/mailman/bin/newlist -q $box $owner $pass", $output, $return);
  if($return == 0){
    $dossier = escapeshellarg("/var/lib/mailman/lists/$asso-$extension");
    exec("ln -s ../extend.py $dossier", $output2, $return2);
    if($return2 == 0){
      $uppass = $db->prepare("UPDATE gesmail SET MLPassword = ? WHERE Asso LIKE ? AND Extension LIKE ?");
      $uppass->bind_param("sss", $pass, $asso, $extension);
      $uppass->execute();
      $uppass->close();
      $message = "Bonjour,
Votre liste $asso-$extension@assos.utc.fr vient d'être créée. Rendez-vous sur Gesmail [34m|  l'adresse http://assos.utc.fr/gesmail pour la remplir !

Cordialement,
L'équipe du SiMDE";
      mail("$asso@assos.utc.fr", "[Gesmail] Création de votre liste", $message, "From: simde@assos.utc.fr");
    }
  }

  $warn = "";
  if(!empty($return))
    $warn .= print_r($output, true);
  if(!empty($return2))
    $warn .= "\n\n".print_r($output2, true);

  if(!empty($warn))
    mail("simde@assos.utc.fr", "[Gesmail] Problème création liste", "Problème lors de la création de la liste $asso-$extension@assos.utc.fr :\n\n $warn", "From: simde@assos.utc.fr");
}
