<?php require 'config.php'; ?>
<!doctype html>
<html lang="pt-br" data-bs-theme="light">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Controle de Animais - HenSo Tech</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="styles.css">
    <style>
        .logo-light { display: block;
                      padding-right: 50px;
                    }
        .logo-dark { display: none; }
        body.dark-mode .logo-light { display: none; }
        body.dark-mode .logo-dark { display: block; }
    </style>
</head>
<body>
    <h1><br></h1>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg fixed-top bg-primary text-white shadow">
        <div class="container-fluid">
            <button class="navbar-toggler" type="button" data-bs-toggle="offcanvas" data-bs-target="#sidebar">
                <span class="navbar-toggler-icon"></span>
            </button>
            <a class="navbar-brand text-white mx-auto mx-lg-3" href="index.php">
                <img src="imgs/Mystic-removebg-preview.png" alt="HenSo Tech" height="80" class="logo-light" >
              
            </a>
        </div>
    </nav>

    <!-- Sidebar Offcanvas (mobile) + Sidebar fixa (desktop) -->
    <div class="offcanvas offcanvas-start" tabindex="-1" id="sidebar">
        <div class="offcanvas-header">
            <h5 class="offcanvas-title">Menu</h5>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas"></button>
        </div>
        <div class="offcanvas-body p-0">
            <?php include 'sidebar.php'; ?>
        </div>
    </div>

    <div class="d-none d-lg-block">
        <?php include 'sidebar.php'; ?>
    </div>

    <main class="main-content container-fluid p-4">