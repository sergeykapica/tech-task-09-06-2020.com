                    </div>
                </main>
            <footer>

            </footer>
            
            <?php

            if( $admin_status )
            {
            
            ?>
                
                <div class="admin-menu-lining p-2">
                    <button title="Save" id="save-button" class="d-block admin-menu-item rounded-circle mb-2 fade-transition fade-out">
                        <i class="fa fa-floppy-o" aria-hidden="true"></i>
                    </button>
                    <a href="/authorization_exit" title="Exit" class="d-block admin-menu-item rounded-circle">
                        <i class="fa fa-hand-o-right" aria-hidden="true"></i>
                    </a>
                </div>
    
            <?php
                
            }

            ?>
        </div>
    </body>
</html>