<?php if ( ! $this->session->userdata('id') && $this->uri->segment(1) !== 'c_login') header('Location: c_login'); ?>
<!DOCTYPE html>
<html lang="es">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= $page_title; ?></title>

    <!-- Bootstrap -->
    <link href="<?php echo(CSS.'bootstrap.min.css'); ?>" rel="stylesheet">
    <?php foreach($additional_css as $css){ ?>
      <link href="<?= (CSS.$css); ?>" rel="stylesheet">
    <?php } ?>
    <link href="<?php echo(CSS.'style.css'); ?>" rel="stylesheet">

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>
   <body>