<?php /* Restored in 2026 by Vegard Gundersen with help from Claude (Anthropic's AI assistant).
 * The 2020 server code was lost; only the April 2020 rough draft survived. This file rebuilds the
 * submitted version from the June 2020 portfolio screenshots and reuses the Retrobaser login system. */
session_start();
session_unset();
session_destroy();
setcookie(session_name(), '', time() - 3600);
header('Location: index.php?done=loggedout');
