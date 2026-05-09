import { useState, useEffect } from '@wordpress/element';
import apiFetch from '@wordpress/api-fetch';
import { Button, Notice, CheckboxControl, TextControl } from '@wordpress/components';
import { __ } from '@wordpress/i18n';

const DEFAULT_NOTIFY = {
  enabled: false,
  email: '',
  discord_webhook: '',
  slack_webhook: '',
};

function Section({ title, children }) {
  return (
    <div className="linkdigest-section">
      <h3 className="linkdigest-section-heading">{title}</h3>
      <div className="linkdigest-section-body">{children}</div>
    </div>
  );
}

export default function App() {
  const [notify, setNotify] = useState(DEFAULT_NOTIFY);
  const [saving, setSaving] = useState(false);
  const [notice, setNotice] = useState(null);

  useEffect(() => {
    apiFetch({ path: '/linkdigest/v1/notify' })
      .then(data => setNotify({ ...DEFAULT_NOTIFY, ...data }))
      .catch(() => {});
  }, []);

  function handleSave() {
    setSaving(true);
    setNotice(null);
    apiFetch({ path: '/linkdigest/v1/notify', method: 'POST', data: notify })
      .then(() => setNotice({ status: 'success', message: __('Settings saved.', 'linkdigest') }))
      .catch(err => setNotice({ status: 'error', message: err.message || __('Save failed.', 'linkdigest') }))
      .finally(() => setSaving(false));
  }

  return (
    <div className="linkdigest-settings-wrap">
      {notice && (
        <Notice status={notice.status} isDismissible onRemove={() => setNotice(null)}>
          {notice.message}
        </Notice>
      )}

      <Section title={__('Notifications', 'linkdigest')}>
        <CheckboxControl
          label={__('Email me after each run', 'linkdigest')}
          checked={notify.enabled}
          onChange={enabled => setNotify(n => ({ ...n, enabled }))}
          __nextHasNoMarginBottom
        />
        {notify.enabled && (
          <div className="linkdigest-field-mt">
            <TextControl
              label={__('Email address', 'linkdigest')}
              type="email"
              value={notify.email}
              placeholder={__('Leave blank to use admin email', 'linkdigest')}
              onChange={email => setNotify(n => ({ ...n, email }))}
              __nextHasNoMarginBottom
            />
          </div>
        )}
      </Section>

      <Section title={__('Webhooks', 'linkdigest')}>
        <TextControl
          label={__('Discord Webhook URL', 'linkdigest')}
          type="url"
          value={notify.discord_webhook}
          placeholder="https://discord.com/api/webhooks/…"
          onChange={discord_webhook => setNotify(n => ({ ...n, discord_webhook }))}
          __nextHasNoMarginBottom
        />
        <div className="linkdigest-field-mt">
          <TextControl
            label={__('Slack Webhook URL', 'linkdigest')}
            type="url"
            value={notify.slack_webhook}
            placeholder="https://hooks.slack.com/services/…"
            onChange={slack_webhook => setNotify(n => ({ ...n, slack_webhook }))}
            __nextHasNoMarginBottom
          />
        </div>
      </Section>

      <div className="linkdigest-settings-actions">
        <Button variant="primary" onClick={handleSave} isBusy={saving} disabled={saving}>
          {__('Save Settings', 'linkdigest')}
        </Button>
      </div>
    </div>
  );
}
