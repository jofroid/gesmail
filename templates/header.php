<!DOCTYPE html>
<html lang="fr">
  <head>
    <meta charset="utf-8">
    <title>SiMDE - Gestion des mails assos</title>
    <meta name="description" content="">
    <meta name="author" content="Arthur Puyou">

    <!-- Le HTML5 shim, for IE6-8 support of HTML elements -->
    <!--[if lt IE 9]>
      <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->

    <!-- Le styles -->
    <link href="/gesmail/css/bootstrap.css" rel="stylesheet">
    <style type="text/css">
      body {
        padding-top: 60px;
        padding-bottom: 40px;
      }
      .sidebar-nav {
        padding: 9px 0;
      }
      .butseul {
        margin-bottom: 0px;
      }
    </style>
    <link href="/gesmail/css/bootstrap-responsive.css" rel="stylesheet">
  </head>

  <body>

    <div class="navbar navbar-fixed-top">
      <div class="navbar-inner">
        <div class="container-fluid">
          <a class="brand" href="/">SiMDE|Gesmail</a>
          <div class="nav-collapse">
            <p class="navbar-text pull-right">Connecté en tant que <?php echo $asso; ?> <a href="logout.php">déconexion</a></p>
          </div><!--/.nav-collapse -->
        </div>
      </div>
    </div>

    <div class="container-fluid">
      <div class="row-fluid">
        <div class="span3">
          <div class="well sidebar-nav">
            <ul class="nav nav-list">
              <li class="nav-header">Redirections</li>
              <li<?php if($adr == -1) echo ' class="active"'; ?>><a href="/gesmail/">polar</a></li>
              <?php foreach($menu['alias'] as $id => $name): ?>
              <li<?php if($adr == $id) echo ' class="active"'; ?>><a href="/gesmail/<?php echo $id; ?>"><?php echo $asso.'-'.$name; ?></a></li>
              <?php endforeach; ?>
              <li<?php if($adr == 'newalias') echo ' class="active"'; ?>><a href="/gesmail/new/alias">+ Ajouter</a></li>
              <li class="nav-header">Mailing-listes</li>
              <?php foreach($menu['ml'] as $id => $name): ?>
              <li<?php if($adr == $id) echo ' class="active"'; ?>><a href="/gesmail/<?php echo $id; ?>"><?php echo $asso.'-'.$name; ?></a></li>
              <?php endforeach; ?>
              <li<?php if($adr == 'newml') echo ' class="active"'; ?>><a href="/gesmail/new/ml">+ Ajouter</a></li>
            </ul>
          </div><!--/.well -->
        </div><!--/span-->
        <div class="span9">