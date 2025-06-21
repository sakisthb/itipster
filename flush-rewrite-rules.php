<?php
/**
 * Flush rewrite rules script
 * Run this file once to activate the new frontend URLs
 */

// Load WordPress
require_once('../../../wp-config.php');

// Flush rewrite rules
flush_rewrite_rules();

echo "✅ Rewrite rules flushed successfully!\n";
echo "🎉 Frontend interface is now active!\n\n";
echo "You can now access:\n";
echo "- http://bet.local/predictions/\n";
echo "- http://bet.local/fixtures/\n";
echo "- http://bet.local/dashboard/\n";
echo "- http://bet.local/fixtures/girona-vs-real-betis/\n\n";
echo "💡 Remember to go to WordPress Admin → Settings → Permalinks and click 'Save Changes' if needed.\n";
?> 