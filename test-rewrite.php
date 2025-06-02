<?php
if (isset($_SERVER['HTTP_MOD_REWRITE']) && $_SERVER['HTTP_MOD_REWRITE'] === 'On') {
    echo "mod_rewrite est ACTIVÉ";
} else {
    echo "mod_rewrite est DÉSACTIVÉ - Contactez le support Hostinger";
}
?>