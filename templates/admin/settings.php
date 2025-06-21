<?php
/**
 * Admin Settings Template
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

$options = get_option('itipster_pro_settings', array());
?>

<div class="wrap itipster-admin-wrap">
    <h1><span class="dashicons dashicons-admin-settings"></span> iTipster Pro Settings</h1>
    
    <form method="post" action="options.php">
        <?php settings_fields('itipster_pro_settings'); ?>
        
        <div class="itipster-settings-grid">
            <div class="settings-card">
                <h3>🔑 API Configuration</h3>
                <table class="form-table">
                    <tr>
                        <th scope="row">
                            <label for="api_token">OddAlerts API Token</label>
                        </th>
                        <td>
                            <input type="text" id="api_token" name="itipster_pro_settings[api_token]" 
                                   value="<?php echo esc_attr($options['api_token'] ?? ''); ?>" 
                                   class="regular-text" />
                            <p class="description">
                                Enter your OddAlerts API token. Get one from 
                                <a href="https://oddalerts.com" target="_blank">oddalerts.com</a>
                            </p>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">
                            <label for="demo_mode">Demo Mode</label>
                        </th>
                        <td>
                            <input type="checkbox" id="demo_mode" name="itipster_pro_settings[demo_mode]" 
                                   value="1" <?php checked(1, $options['demo_mode'] ?? true); ?> />
                            <label for="demo_mode">Enable demo mode (uses sample data instead of API calls)</label>
                        </td>
                    </tr>
                </table>
            </div>
            
            <div class="settings-card">
                <h3>⚙️ General Settings</h3>
                <table class="form-table">
                    <tr>
                        <th scope="row">
                            <label for="rate_limit">API Rate Limit</label>
                        </th>
                        <td>
                            <input type="number" id="rate_limit" name="itipster_pro_settings[rate_limit]" 
                                   value="<?php echo esc_attr($options['rate_limit'] ?? 100); ?>" 
                                   min="1" max="1000" />
                            <p class="description">Maximum API requests per hour</p>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">
                            <label for="cache_duration">Cache Duration</label>
                        </th>
                        <td>
                            <input type="number" id="cache_duration" name="itipster_pro_settings[cache_duration]" 
                                   value="<?php echo esc_attr($options['cache_duration'] ?? 900); ?>" 
                                   min="60" max="3600" />
                            <p class="description">Cache duration in seconds (default: 900 = 15 minutes)</p>
                        </td>
                    </tr>
                </table>
            </div>
            
            <div class="settings-card">
                <h3>🎨 UI Settings</h3>
                <table class="form-table">
                    <tr>
                        <th scope="row">
                            <label for="glassmorphism">Glassmorphism UI</label>
                        </th>
                        <td>
                            <input type="checkbox" id="glassmorphism" name="itipster_pro_settings[ui_settings][glassmorphism]" 
                                   value="1" <?php checked(1, $options['ui_settings']['glassmorphism'] ?? true); ?> />
                            <label for="glassmorphism">Enable glassmorphism effects</label>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">
                            <label for="dark_mode">Dark Mode</label>
                        </th>
                        <td>
                            <input type="checkbox" id="dark_mode" name="itipster_pro_settings[ui_settings][dark_mode]" 
                                   value="1" <?php checked(1, $options['ui_settings']['dark_mode'] ?? false); ?> />
                            <label for="dark_mode">Enable dark mode</label>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">
                            <label for="animations">Animations</label>
                        </th>
                        <td>
                            <input type="checkbox" id="animations" name="itipster_pro_settings[ui_settings][animations]" 
                                   value="1" <?php checked(1, $options['ui_settings']['animations'] ?? true); ?> />
                            <label for="animations">Enable UI animations</label>
                        </td>
                    </tr>
                </table>
            </div>
            
            <div class="settings-card">
                <h3>🔧 Tools</h3>
                <p>
                    <button type="button" id="test_api" class="button">Test API Connection</button>
                    <span id="api_test_result"></span>
                </p>
                <p>
                    <button type="button" id="clear_cache" class="button">Clear Cache</button>
                    <span id="cache_clear_result"></span>
                </p>
                <p>
                    <button type="button" id="insert_demo_data" class="button">Insert Demo Data</button>
                    <span id="demo_data_result"></span>
                </p>
            </div>
        </div>
        
        <?php submit_button('Save Settings'); ?>
    </form>
</div>

<script>
jQuery(document).ready(function($) {
    $('#test_api').on('click', function() {
        var button = $(this);
        var result = $('#api_test_result');
        
        button.prop('disabled', true);
        result.html('Testing...');
        
        $.ajax({
            url: ajaxurl,
            type: 'POST',
            data: {
                action: 'itipster_test_api',
                nonce: '<?php echo wp_create_nonce('itipster_admin_nonce'); ?>'
            },
            success: function(response) {
                if (response.success) {
                    result.html('<span style="color: green;">✓ ' + response.data.message + '</span>');
                } else {
                    result.html('<span style="color: red;">✗ ' + response.data + '</span>');
                }
            },
            error: function() {
                result.html('<span style="color: red;">✗ Connection failed</span>');
            },
            complete: function() {
                button.prop('disabled', false);
            }
        });
    });
    
    $('#clear_cache').on('click', function() {
        var button = $(this);
        var result = $('#cache_clear_result');
        
        button.prop('disabled', true);
        result.html('Clearing...');
        
        $.ajax({
            url: ajaxurl,
            type: 'POST',
            data: {
                action: 'itipster_clear_cache',
                nonce: '<?php echo wp_create_nonce('itipster_admin_nonce'); ?>'
            },
            success: function(response) {
                if (response.success) {
                    result.html('<span style="color: green;">✓ Cache cleared successfully</span>');
                } else {
                    result.html('<span style="color: red;">✗ Failed to clear cache</span>');
                }
            },
            error: function() {
                result.html('<span style="color: red;">✗ Request failed</span>');
            },
            complete: function() {
                button.prop('disabled', false);
            }
        });
    });
    
    $('#insert_demo_data').on('click', function() {
        var button = $(this);
        var result = $('#demo_data_result');
        
        button.prop('disabled', true);
        result.html('Inserting...');
        
        $.ajax({
            url: ajaxurl,
            type: 'POST',
            data: {
                action: 'itipster_insert_demo_data',
                nonce: '<?php echo wp_create_nonce('itipster_admin_nonce'); ?>'
            },
            success: function(response) {
                if (response.success) {
                    result.html('<span style="color: green;">✓ Demo data inserted successfully</span>');
                } else {
                    result.html('<span style="color: red;">✗ Failed to insert demo data</span>');
                }
            },
            error: function() {
                result.html('<span style="color: red;">✗ Request failed</span>');
            },
            complete: function() {
                button.prop('disabled', false);
            }
        });
    });
});
</script>

<style>
.itipster-admin-wrap {
    background: #f1f1f1;
    padding: 20px;
    margin: 20px 0;
    border-radius: 8px;
}

.itipster-settings-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(400px, 1fr));
    gap: 20px;
    margin-top: 20px;
}

.settings-card {
    background: white;
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
}

.settings-card h3 {
    margin-top: 0;
    border-bottom: 2px solid #6366f1;
    padding-bottom: 10px;
}

.form-table th {
    width: 200px;
}

.form-table input[type="text"],
.form-table input[type="number"] {
    width: 100%;
    max-width: 300px;
}

.form-table .description {
    margin-top: 5px;
    color: #666;
}

#api_test_result,
#cache_clear_result,
#demo_data_result {
    margin-left: 10px;
    font-weight: bold;
}
</style> 