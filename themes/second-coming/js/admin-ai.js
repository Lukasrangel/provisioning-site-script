(function () {
    var cfg = window.secondComingAi;
    if (!cfg) {
        return;
    }

    function ready(fn) {
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', fn);
        } else {
            fn();
        }
    }

    ready(function () {
        var button = document.getElementById('sc-ai-generate');
        var prompt = document.getElementById('sc-ai-prompt');
        var status = document.getElementById('sc-ai-status');
        if (!button || !prompt) {
            return;
        }

        button.addEventListener('click', function () {
            var text = prompt.value.replace(/^\s+|\s+$/g, '');
            if (!text) {
                if (status) {
                    status.textContent = cfg.i18n.empty;
                }
                return;
            }

            button.disabled = true;
            if (status) {
                status.textContent = cfg.i18n.working;
            }

            var body = new window.FormData();
            body.append('action', 'second_coming_ai_generate');
            body.append('nonce', cfg.nonce);
            body.append('prompt', text);

            window.fetch(cfg.ajaxUrl, {
                method: 'POST',
                credentials: 'same-origin',
                body: body
            }).then(function (res) {
                return res.json();
            }).then(function (json) {
                if (!json || !json.success || !json.data) {
                    var msg = cfg.i18n.generic;
                    if (json && json.data && json.data.message) {
                        msg = json.data.message;
                    }
                    throw new Error(msg);
                }
                if (json.data.edit_url) {
                    window.location.href = json.data.edit_url;
                    return;
                }
                if (status) {
                    status.textContent = json.data.message || '';
                }
                button.disabled = false;
            }).catch(function (err) {
                if (status) {
                    status.textContent = err.message || cfg.i18n.generic;
                }
                button.disabled = false;
            });
        });
    });
})();
