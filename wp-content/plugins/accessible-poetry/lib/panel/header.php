<?php
function acp_op_header() {
    echo '<header id="acp-panel-header">';
    echo '    <h1>Accessible Poetry</h1>';
    echo '     <p>' . __('Opening WordPress sites to people with disabilites', 'acp') . '</p>';
    echo '</header>';
}