<?php
function acp_op_menu() {
    echo '<ul class="tabs-menu nav nav-tabs" role="tablist">';
    echo '    <li class="active" role="presentation">';
    echo '        <a href="#acp_general" class="button button-primary" aria-controls="acp_general" role="tab" data-toggle="tab">' . __('General', 'acp') . '</a>';
    echo '     </li>';
    echo '     <li role="presentation">';
    echo '        <a href="#acp_toolbar" class="button button-primary" aria-controls="acp_toolbar" role="tab" data-toggle="tab">' . __('Toolbar', 'acp') . '</a>';
    echo '     </li>';
    echo '     <li role="presentation">';
    echo '        <a href="#acp_skiplinks" class="button button-primary" aria-controls="acp_skiplinks" role="tab" data-toggle="tab">' . __('Skiplinks', 'acp'). '</a>';
    echo '     </li>';
    echo '     <li role="presentation">';
    echo '        <a href="#acp_customcode" class="button button-primary" aria-controls="acp_customcode" role="tab" data-toggle="tab">' . __('Custom Code', 'acp'). '</a>';
    echo '     </li>';
    echo '</ul>';
}