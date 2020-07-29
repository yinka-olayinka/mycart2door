<?php
    session_start();

    if(!$_SESSION['session'])
    {
        header('location:../');
    }
?>

<html>
    <head>
        <meta charset="utf-8" />
        
        <meta name="description" content="An online delivery service" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="title" content="MyCart2Door - Best Online Delivery Service" />
        <meta name="keywords" content="Online, Deliver, Delivery, Shop, Shopping, Service, Services, Pay, Payment" />
        
        <title>MyCart2Door - Best Online Delivery Service</title>
        
        <link rel="stylesheet" href="../assets/css/main.css" />
        <link rel="stylesheet" href="../assets/css/style.css" />
        
        <link rel="stylesheet" href="../assets/css/inputs.css" />
        <link rel="stylesheet" href="../assets/css/buttons.css" />
        <link rel="stylesheet" href="../assets/css/preloaders.css" />
        <link rel="stylesheet" href="../assets/css/media-screen.css" />
        <link rel="stylesheet" href="../assets/animate/animate.min.css" />
        <link rel="stylesheet" href="../assets/custombox/custombox.min.css" />
        <link rel="stylesheet" href="../assets/bootstrap/css/bootstrap.min.css" />
        <link rel="stylesheet" href="../assets/lineicons-1.0.1/LineIcons.min.css" />
        <link rel="stylesheet" href="../assets/linea_basic_1.0/_ICONFONT/styles.css" />
        <link rel="stylesheet" href="../assets/bootstrap/css/bootstrap-grid.min.css" />
        <link rel="stylesheet" href="../assets/bootstrap/css/bootstrap-reboot.min.css" />
    </head>
    <body>
        <section class="content">
            <nav class="transition">
                <div class="brand transition"><img src="../assets/images/mc2d_logo.png" /></div>
            </nav>
            <div class="wrapper"></div>
        </section>
    
        <div class="modalbox"></div>
        
        <script src="../assets/js/jquery-3.31.min.js"></script>
        <script src="../assets/js/script.js"></script>
        
        <script src="../assets/js/countries.js"></script>
        <script src="../assets/custombox/custombox.min.js"></script>
        <script src="../assets/bootstrap/js/bootstrap.min.js"></script>
        <script src="../assets/bootstrap/js/bootstrap.bundle.min.js"></script>
    </body>
</html>