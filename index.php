<?php
require 'Slim/Slim.php';

class gesmail extends Slim {
  public $db;
  
  public function prepare_menu($asso){
    $menu = array('alias' => array(), 'ml' => array());
    $stmt = $this->db->prepare("SELECT ID, Type, Extension FROM gesmail WHERE Asso LIKE ?");
    $stmt->bind_param("s", $asso);
    $stmt->execute();
    $stmt->bind_result($id, $type, $extension);
    while($req = $stmt->fetch())
      $menu[$type][$id] = $extension;
    $stmt->close();
    return $menu;
  }
  
  public function connectdb(){
    $this->db = new mysqli('localhost', 'root', 'root', 'postfix', 8889);
    $this->db->set_charset("utf8");
  }
  
  public function get_extension_dests($ext){
    // Récupération de la liste de la boîte
    $stmt = $this->db->prepare("SELECT ID, destination FROM postfix_alias WHERE alias LIKE ? AND destination NOT LIKE ?");
    $stmt->bind_param("ss", $ext, $_SESSION['asso']);
    $stmt->execute();
    $stmt->bind_result($id, $destination);
    $destinataires = array();
    while($req = $stmt->fetch())
      $destinataires[$id] = $destination;
    $stmt->close();
    return $destinataires;
  }
  
  public function get_box($id){
    if($id > 0){
      $stmt = $this->db->prepare("SELECT Extension, Type FROM gesmail WHERE Asso LIKE ? AND ID = ?");
      $stmt->bind_param("si", $_SESSION['asso'], $id);
      $stmt->execute();
      $stmt->bind_result($extension, $type);
      $stmt->store_result();
      $adr = $stmt->fetch();
      $stmt->close();
      $box = $_SESSION['asso']."-".$extension;
    }
    else {
      $type = "alias";
      $box = $_SESSION['asso'];
    }
    if($type == 'alias')  
      $typelu = 'redirection';
    else if($type == 'ml')
      $typelu = 'mailing-liste';
      
    return array('name' => $box, 'type' => $type, 'typelu' => $typelu);
  }

  public function verifMail($email) {
  	return preg_match('#^[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,4}$#i', $email);
  }
  
  public function genpass(){
    $s = "abcdefghijklmnopqrstuvwxyz";
    $str = "";
    for($i=0;$i<8;$i++)
      $str .= $s[rand(0,25)];
    return $str;
  }
}

$app = new gesmail(array(
  'debug' => true
));
$app->connectdb();

$_SESSION['asso'] = 'simde';

// FIXME noraml ça ?
Slim_Route::setDefaultConditions(array(
    'id' => '-?\d+'
));

$app->get('/(:id)', function ($id = -1) use ($app) {  
  if(empty($_SESSION['asso'])){
    header("Location: login.php");
    die();
  }
  else {
    $asso = $_SESSION['asso'];
  }
  
  $box = $app->get_box($id);

  $options = array();
  
  if($box['type'] == 'alias'){
    $stmt = $app->db->prepare("SELECT 1 FROM postfix_alias WHERE alias LIKE ? AND destination LIKE ?");
    $stmt->bind_param("ss", $box['name'], $_SESSION['asso']);
    $stmt->execute();
    $stmt->store_result();
    if($stmt->num_rows == 1)
      $options['includeroot'] = ' checked="checked"';
    else
      $options['includeroot'] = '';
    $stmt->close();
  }

  $app->render($box['type'].'.php', array(
    'asso' => $_SESSION['asso'],
    'menu' => $app->prepare_menu($_SESSION['asso']),
    'adr' => $id,
    'box' => $box['name'],
    'destinataires' => $app->get_extension_dests($box['name']),
    'options' => $options,
  ));
});

$app->post('/:id', function($id) use ($app){
  if($app->verifMail($_POST['email'])){
    $email = $_POST['email'];
    
    $box = $app->get_box($id);

    if($email == $box['name']."@assos.utc.fr")
      $app->flash('adderror', "Il est impossible d'ajouter une adresse à elle-même (regarde les options à droite ;)).");
    else {
      if($box['type'] == 'alias'){
        $stmt = $app->db->prepare("INSERT INTO postfix_alias (alias,destination) VALUES (?,?)");
        $stmt->bind_param("ss", $box['name'], $email);
        $stmt->execute();              
      }
      else if($box['type'] == 'ml'){
        $stmt = $app->db->prepare("INSERT INTO mailman_mysql (listname, address, hide, nomail, ack, not_metoo, digest, plain, password, lang, name, one_last_digest, user_options, delivery_status, topics_userinterest, delivery_status_timestamp, bi_cookie, bi_score, bi_noticesleft, bi_lastnotice, bi_date) VALUES (?, ?, 'N', 'N', 'Y', 'Y', 'N', 'N', ?, 'fr', '', 'N', '264', '0', NULL, '0000-00-00 00:00:00', NULL, '0', '0', '0000-00-00', '0000-00-00')");
        $pass = $app->genpass();
        $stmt->bind_param("sss", $box['name'], $email, $pass);
        $stmt->execute();
      }
      $app->flash('success', "L'adresse <b>$email</b> a été ajoutée.");
    }
  }
  else {
   $app->flash('adderror', "L'adresse saisie est incorrecte.");
  }
  
  $app->redirect("/gesmail/$id");
});

$app->delete('/:id/:dest', function($id, $dest) use ($app){
  $box = $app->get_box($id);    

  if($app->verifMail($dest)){
    if($box['type'] == 'alias')
      $sql = "DELETE FROM postfix_alias WHERE alias LIKE ? AND destination LIKE ?";
    else if($box['type'] == 'ml')
      $sql = "DELETE FROM mailman_mysql WHERE listname LIKE ? AND address LIKE ?";

    $stmt = $app->db->prepare($sql);
    $stmt->bind_param("ss", $box['name'], $dest);
    $stmt->execute();
    $stmt->close();
    $app->flash('success', "L'adresse <b>$dest</b> a été supprimée.");
  }
  $app->redirect("/gesmail/$id");
});

$app->get('/delete/:id', function($id) use ($app){
  $box = $app->get_box($id);    

  $app->render('delete.php', array(
    'asso' => $_SESSION['asso'],
    'menu' => $app->prepare_menu($_SESSION['asso']),
    'box' => $box['name'],
    'adr' => $id,
    'type' => $box['typelu']
  ));
});

$app->delete('/:id', function($id) use ($app){
  $box = $app->get_box($id);    

  if($box['type'] == 'alias' && $id > 0){
    $sql = "DELETE FROM postfix_alias WHERE alias LIKE ?";
    $stmt = $app->db->prepare($sql);
    $stmt->bind_param("s", $box['name']);
    $stmt->execute();
    $stmt->close();
    $sql = "DELETE FROM gesmail WHERE Asso LIKE ? AND ID LIKE ?";
    $stmt = $app->db->prepare($sql);
    $stmt->bind_param("si", $_SESSION['asso'], $id);
    $stmt->execute();
    $stmt->close();
    $app->flash('success', "L'alias a été supprimé");
  }

  else if($box['type'] == 'ml'){
    die("unavailable");
    $sql = "DELETE FROM mailman_mysql WHERE listname LIKE ?";
    $app->flash('success', "La mailing-liste va être supprimée d'ici 5 minutes");
  }

  $app->redirect("/gesmail/");
});

$app->put('/:id', function($id) use ($app){
  $box = $app->get_box($id);

  if($box['type'] == 'alias'){
    $stmt = $app->db->prepare("DELETE FROM postfix_alias WHERE alias LIKE ? AND destination LIKE ?");
    $stmt->bind_param("ss", $box['name'], $_SESSION['asso']);
    $stmt->execute();
    $stmt->close();

    if(!empty($_POST['includeroot'])){
      $stmt = $app->db->prepare("INSERT INTO postfix_alias (alias, destination) VALUES (?, ?)");
      $stmt->bind_param("ss", $box['name'], $_SESSION['asso']);
      $stmt->execute();
      $stmt->close();
    }    
  }
  $app->flash('success', "Options enregistrées");
  $app->redirect("/gesmail/$id");
});

$app->get('/new/(:type)', function($type) use ($app){
  if($type == 'alias'){
    $typelu = 'Redirection';
  }
  else if($type == 'ml'){
    $typelu = 'Mailing-liste';
  }

  $app->render('new.php', array(
    'asso' => $_SESSION['asso'],
    'menu' => $app->prepare_menu($_SESSION['asso']),
    'adr' => 'new'.$type,
    'type' => $typelu
  ));
});

$app->post('/new/(:type)', function($type) use ($app){
  if($type == 'alias'){
    
  }
  else if($type == 'ml'){
    
  }
});

$app->run();