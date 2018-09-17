            <div class="footer">
                <?php
                    if (is_active_sidebar('footer')) {
                        dynamic_sidebar('footer');
                    }
                ?>
            </div>
        
        </div> <!-- wrapper -->
    
        <?php wp_footer(); ?>
        
        <script><?= get_theme_mod('custom_js') ?></script>
    
    </body>
</html>

