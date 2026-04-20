<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<div id="snootl-admin-wrap">
    <div class="snootl-header">
        <h1>Snootl</h1>
        <span class="snootl-badge">Widget</span>
    </div>

    <form method="post" action="options.php">
        <?php settings_fields( 'snootl_option_group' ); ?>

        <div class="snootl-card">
            <div class="snootl-field">
                <label class="snootl-label" for="api_key">API Key</label>
                <div style="display: flex; gap: 10px; margin-bottom: 8px;">
                    <input type="text" id="api_key" name="snootl_options[api_key]" 
                           value="<?php echo isset( $options['api_key'] ) ? esc_attr( $options['api_key'] ) : ''; ?>" 
                           class="snootl-input" placeholder="e.g. keV6IiTNOw" />
                    <button type="button" id="snootl-check-api" class="snootl-save-btn" style="background: #6366f1;">Check</button>
                </div>
                <div id="snootl-api-status" style="margin-bottom: 15px; font-size: 13px;"></div>
                <p class="snootl-description">Get your API key from your <a href="https://app.snootl.com/dashboard" target="_blank">Snootl dashboard</a>.</p>
            </div>
        </div>

        <div class="snootl-card">
            <div class="snootl-field">
                <label class="snootl-label" for="visibility_mode">Visibility Mode</label>
                <select id="visibility_mode" name="snootl_options[visibility_mode]" class="snootl-select">
                    <option value="disabled" <?php selected( isset($options['visibility_mode']) ? $options['visibility_mode'] : '', 'disabled' ); ?>>Disabled (Offline)</option>
                    <option value="everyone" <?php selected( isset($options['visibility_mode']) ? $options['visibility_mode'] : 'everyone', 'everyone' ); ?>>Everyone (Guests + Logged In)</option>
                    <option value="logged_in" <?php selected( isset($options['visibility_mode']) ? $options['visibility_mode'] : '', 'logged_in' ); ?>>Logged In Users Only</option>
                    <option value="role_level" <?php selected( isset($options['visibility_mode']) ? $options['visibility_mode'] : '', 'role_level' ); ?>>Specific Role Level & Above</option>
                </select>
                <p class="snootl-description">Choose who can see the Snootl widget on your website.</p>
            </div>

            <div id="role_level_container" style="display: none;">
                <label class="snootl-label" for="min_role">Minimum Role Required</label>
                <select id="min_role" name="snootl_options[min_role]" class="snootl-select">
                    <option value="subscriber" <?php selected( isset($options['min_role']) ? $options['min_role'] : '', 'subscriber' ); ?>>Subscriber & Above</option>
                    <option value="contributor" <?php selected( isset($options['min_role']) ? $options['min_role'] : '', 'contributor' ); ?>>Contributor & Above</option>
                    <option value="author" <?php selected( isset($options['min_role']) ? $options['min_role'] : '', 'author' ); ?>>Author & Above</option>
                    <option value="editor" <?php selected( isset($options['min_role']) ? $options['min_role'] : '', 'editor' ); ?>>Editor & Above</option>
                    <option value="administrator" <?php selected( isset($options['min_role']) ? $options['min_role'] : '', 'administrator' ); ?>>Administrator Only</option>
                </select>
                <p class="snootl-description">Users with this role or higher permissions will see the widget.</p>
            </div>
        </div>

        <div class="snootl-footer">
            <div>
                <a href="https://snootl.com" target="_blank">snootl.com</a> &bull; 
                <a href="https://justdev.org" target="_blank">justDev</a>
            </div>
            <input type="submit" name="submit" id="submit" class="snootl-save-btn" value="Save Settings">
        </div>
    </form>
</div>
