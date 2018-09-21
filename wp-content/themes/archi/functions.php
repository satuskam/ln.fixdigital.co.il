<?php

function dddk enableRichEditorBehindCloudFront(){
    add_filter('user_can_richedit','__return_true');
}
add_action( 'init', 'enableRichEditorBehindCloudFront' , 9 );

