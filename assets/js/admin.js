document.addEventListener('DOMContentLoaded', function() {
    const modeSelect = document.getElementById('visibility_mode');
    const roleContainer = document.getElementById('role_level_container');
    
    function toggleRoleLevel() {
        if (modeSelect && modeSelect.value === 'role_level') {
            roleContainer.style.display = 'block';
        } else if (roleContainer) {
            roleContainer.style.display = 'none';
        }
    }
    
    if (modeSelect) {
        modeSelect.addEventListener('change', toggleRoleLevel);
        toggleRoleLevel();
    }

    // API Key Validation
    const checkBtn = document.getElementById('snootl-check-api');
    const apiKeyInput = document.getElementById('api_key');
    const statusBox = document.getElementById('snootl-api-status');

    if (checkBtn && apiKeyInput && statusBox) {
        checkBtn.addEventListener('click', function(e) {
            e.preventDefault();
            
            const apiKey = apiKeyInput.value.trim();
            if (!apiKey) {
                statusBox.innerHTML = '<span style="color: #ef4444;">Please enter an API Key first.</span>';
                return;
            }

            checkBtn.disabled = true;
            checkBtn.innerText = 'Checking...';
            statusBox.innerHTML = '<span style="color: #64748b;">Validating...</span>';

            const formData = new FormData();
            formData.append('action', 'snootl_validate_api_key');
            formData.append('api_key', apiKey);
            formData.append('nonce', snootl_admin.nonce);

            fetch(snootl_admin.ajax_url, {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                checkBtn.disabled = false;
                checkBtn.innerText = 'Check';
                
                if (data.success) {
                    statusBox.innerHTML = '<span style="color: #10b981; font-weight: 600;">✓ Valid API Key (' + data.data.project_name + ')</span>';
                } else {
                    statusBox.innerHTML = '<span style="color: #ef4444; font-weight: 600;">✗ ' + data.data.message + '</span>';
                }
            })
            .catch(error => {
                checkBtn.disabled = false;
                checkBtn.innerText = 'Check';
                statusBox.innerHTML = '<span style="color: #ef4444;">Error connecting to validation service.</span>';
                console.error('Snootl Error:', error);
            });
        });
    }
});
