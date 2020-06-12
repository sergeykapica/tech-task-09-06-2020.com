<!DOCTYPE html>
<html>
    <head>
        <?php
            foreach( $styles_items as $style_item ) { 
        ?>
                <link rel="stylesheet" type="text/css" href="<?php echo PATH_TO_CSS . $style_item; ?>.css"/>
        <?php
            }
        ?>
        
        <?php
            foreach( $script_items as $script_item ) { 
        ?>
                <script type="text/javascript" src="<?php echo PATH_TO_JS . $script_item; ?>.js"></script>
        <?php
            }
        ?>
    </head>
    <body>
        <div id="root-wrapper" class="w-100">
            <header>
                <div class="root-center overflow-auto">
                    <div id="root-header-top" class="float-left mt-3">
                        <h3 id="site-title">Task-center.com</h3>
                    </div>
                    <div id="root-header-middle" class="w-100 float-left">
                        <div class="text-center">
                            <h1 id="root-header-headline" class="mb-3">Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium</h1>
                            <a href="/authorization" class="action-button d-inline-block rounded-pill px-4 py-3">Authorization</a>
                        </div>
                    </div>
                </div>
            </header>
            <main>
                <div class="root-center rounded shadow p-4" id="root-content-lining">