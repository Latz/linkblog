import { useState, useEffect, useMemo, useCallback } from '@wordpress/element';
import apiFetch from '@wordpress/api-fetch';
import { Button, Notice, CheckboxControl, TextControl } from '@wordpress/components';
import { __, sprintf } from '@wordpress/i18n';
import { buildRRule } from './lib/rrule';
import { SCHEDULE_MODES } from './lib/modes';
import ScheduleTypePicker from './components/ScheduleTypePicker';
import RecurrenceConfig from './components/RecurrenceConfig';
import TriggerCondition from './components/TriggerCondition';
import TimePicker from './components/TimePicker';
import NextSchedules from './components/NextSchedules';
import DiagnosticsPanel from './components/DiagnosticsPanel';

const DEFAULT_FORM = {
  mode: 'daily',
  recurrence: { interval: 1, weekdays: [], monthDays: [{ type: 'day', value: 1, nth: 1, weekday: 'MO' }], nthWeek: null },
  trigger: { count: 10, tag_id: null, days: 7 },
  times: ['09:00'],
  notify: { enabled: false, email: '' },
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
  const [form, setForm]             = useState(DEFAULT_FORM);
  const [saving, setSaving]         = useState(false);
  const [notice, setNotice]         = useState(null);
  const [runBusy, setRunBusy]       = useState(false);
  const [runResult, setRunResult]   = useState(null);
  const [previewBusy, setPreviewBusy]     = useState(false);
  const [previewResult, setPreviewResult] = useState(null);
  const [confirmRun, setConfirmRun] = useState(false);
  // Initialised from diag.cron_notice_dismissed once diagnostics load.
  const [cronNoticeDismissed, setCronNoticeDismissed] = useState(false);

  // Diagnostics lifted here so App can show a WP-Cron warning and refresh after save.
  const [diag, setDiag]           = useState(null);
  const [diagLoading, setDiagLoading] = useState(true);

  const refreshDiag = useCallback(() => {
    setDiagLoading(true);
    apiFetch({ path: '/linkdigest/v1/schedule/diagnostics' })
      .then(d => {
        setDiag(d);
        setCronNoticeDismissed(!!d.cron_notice_dismissed);
        setDiagLoading(false);
      })
      .catch(() => setDiagLoading(false));
  }, []);

  useEffect(() => {
    apiFetch({ path: '/linkdigest/v1/schedule' })
      .then(data => setForm({ ...DEFAULT_FORM, ...data }))
      .catch(() => {});
  }, []);

  useEffect(refreshDiag, [refreshDiag]);

  async function handleSave() {
    setSaving(true);
    setNotice(null);
    setConfirmRun(false);
    if (new Set(form.times).size !== form.times.length) {
      setNotice({ status: 'error', message: __('Execution times must be unique.', 'linkdigest') });
      setSaving(false);
      return;
    }
    try {
      await apiFetch({ path: '/linkdigest/v1/schedule', method: 'POST', data: form });
      setNotice({ status: 'success', message: __('Schedule saved.', 'linkdigest') });
      refreshDiag();
    } catch {
      setNotice({ status: 'error', message: __('Failed to save schedule.', 'linkdigest') });
    } finally {
      setSaving(false);
    }
  }

  async function handleRunNow() {
    setRunBusy(true);
    setRunResult(null);
    setPreviewResult(null);
    setConfirmRun(false);
    try {
      const res = await apiFetch({ path: '/linkdigest/v1/schedule/run', method: 'POST' });
      setRunResult(res);
    } catch (err) {
      setRunResult({ error: err?.message || __('Run failed.', 'linkdigest') });
    } finally {
      setRunBusy(false);
    }
  }

  async function handlePreview() {
    setPreviewBusy(true);
    setPreviewResult(null);
    setRunResult(null);
    setConfirmRun(false);
    try {
      const res = await apiFetch({ path: '/linkdigest/v1/schedule/preview', method: 'POST' });
      setPreviewResult(res);
    } catch (err) {
      setPreviewResult({ error: err?.message || __('Preview failed.', 'linkdigest') });
    } finally {
      setPreviewBusy(false);
    }
  }

  function handleModeChange(mode) {
    setForm(f => ({
      ...f,
      mode,
      recurrence: SCHEDULE_MODES.has(mode)
        ? { interval: 1, weekdays: [], monthDays: [{ type: 'day', value: 1, nth: 1, weekday: 'MO' }], nthWeek: null }
        : f.recurrence,
    }));
  }

  const isSchedule = SCHEDULE_MODES.has(form.mode);
  const isManual   = form.mode === 'manual';

  const config = useMemo(() => {
    if (isSchedule) {
      return { rrule: buildRRule({ type: form.mode, ...form.recurrence }), times: form.times, trigger: null };
    }
    if (isManual) {
      return { rrule: null, times: [], trigger: { type: 'manual' } };
    }
    return { rrule: null, times: form.times, trigger: { type: form.mode, ...form.trigger } };
  }, [form, isSchedule, isManual]);

  const section02Label = isSchedule ? __('Recurrence', 'linkdigest') : __('Condition', 'linkdigest');

  function renderConditionSection() {
    if (isSchedule) return (
      <RecurrenceConfig
        type={form.mode}
        value={form.recurrence}
        onChange={v => setForm(f => ({ ...f, recurrence: v }))}
      />
    );
    if (isManual) return (
      <p className="description">
        {__('No automatic trigger — posts must be triggered manually.', 'linkdigest')}
      </p>
    );
    return (
      <TriggerCondition
        mode={form.mode}
        value={form.trigger}
        onChange={v => setForm(f => ({ ...f, trigger: v }))}
      />
    );
  }

  function renderRunResult() {
    if (!runResult) return null;
    if (runResult.error) {
      return <p className="linkdigest-run-result linkdigest-run-result--error">{runResult.error}</p>;
    }
    const msg = runResult.published
      ? sprintf(__('Published %d links.', 'linkdigest'), runResult.link_count)
      : (runResult.reason === 'condition_not_met'
          ? __('Condition not met — no post published.', 'linkdigest')
          : __('No post published.', 'linkdigest'));
    return (
      <p className={`linkdigest-run-result ${runResult.published ? 'linkdigest-run-result--ok' : 'linkdigest-run-result--skip'}`}>
        {msg}
      </p>
    );
  }

  function renderPreviewResult() {
    if (!previewResult) return null;
    if (previewResult.error) {
      return <p className="linkdigest-run-result linkdigest-run-result--error">{previewResult.error}</p>;
    }
    if (!previewResult.would_publish) {
      return (
        <p className="linkdigest-run-result linkdigest-run-result--skip">
          {sprintf(__('Would NOT publish — %d links pending, condition not met.', 'linkdigest'), previewResult.total_pending)}
        </p>
      );
    }
    return (
      <div className="linkdigest-preview-result">
        <p className="linkdigest-preview-result__summary">
          {sprintf(__('Would publish %d links:', 'linkdigest'), previewResult.link_count)}
        </p>
        {previewResult.by_category.length > 0 && (
          <ul className="linkdigest-preview-result__cats">
            {previewResult.by_category.map(({ name, count }) => (
              <li key={name}>
                {sprintf(__('%1$s (%2$d)', 'linkdigest'), name, count)}
              </li>
            ))}
          </ul>
        )}
      </div>
    );
  }

  return (
    <div className="linkdigest-schedule-wrap">
      <div className="linkdigest-schedule-main">
        {diag?.wp_cron_disabled && !cronNoticeDismissed && (
          <Notice
            status="warning"
            isDismissible
            onRemove={() => {
              setCronNoticeDismissed(true);
              apiFetch({ path: '/linkdigest/v1/schedule/dismiss-cron-notice', method: 'POST' });
            }}
            className="linkdigest-wpcron-notice"
          >
            <strong>{__('WP-Cron is disabled.', 'linkdigest')}</strong>
            {' '}
            {__('Scheduled runs will not fire automatically. Add a real server cron job or remove', 'linkdigest')}
            {' '}<code>DISABLE_WP_CRON</code>{' '}
            {__('from', 'linkdigest')}
            {' '}<code>wp-config.php</code>.
          </Notice>
        )}

        {notice && (
          <Notice status={notice.status} onRemove={() => setNotice(null)} isDismissible>
            {notice.message}
          </Notice>
        )}

        <Section title={__('Mode', 'linkdigest')}>
          <ScheduleTypePicker value={form.mode} onChange={handleModeChange} />
        </Section>

        <Section title={section02Label}>
          {renderConditionSection()}
        </Section>

        {!isManual && (
          <Section title={__('Execution Times', 'linkdigest')}>
            <TimePicker
              times={form.times}
              onChange={v => setForm(f => ({ ...f, times: v }))}
            />
          </Section>
        )}

        <Section title={__('Notifications', 'linkdigest')}>
          <CheckboxControl
            label={__('Email me after each run', 'linkdigest')}
            checked={form.notify?.enabled ?? false}
            onChange={enabled => setForm(f => ({ ...f, notify: { ...f.notify, enabled } }))}
          />
          {form.notify?.enabled && (
            <TextControl
              label={__('Email address', 'linkdigest')}
              type="email"
              value={form.notify?.email ?? ''}
              placeholder={__('Leave blank to use admin email', 'linkdigest')}
              onChange={email => setForm(f => ({ ...f, notify: { ...f.notify, email } }))}
              __nextHasNoMarginBottom
            />
          )}
        </Section>

        <div className="linkdigest-schedule-actions">
          <Button variant="primary" onClick={handleSave} isBusy={saving} disabled={saving}>
            {__('Save Schedule', 'linkdigest')}
          </Button>
          {' '}
          {confirmRun ? (
            <>
              <Button
                variant="secondary"
                onClick={() => { handleRunNow(); }}
                isBusy={runBusy}
                className="linkdigest-run-confirm"
              >
                {__('Confirm run?', 'linkdigest')}
              </Button>
              {' '}
              <Button variant="tertiary" onClick={() => setConfirmRun(false)}>
                {__('Cancel', 'linkdigest')}
              </Button>
            </>
          ) : (
            <Button variant="secondary" onClick={() => setConfirmRun(true)} disabled={runBusy || previewBusy}>
              {__('Run Now', 'linkdigest')}
            </Button>
          )}
          {' '}
          <Button
            variant="secondary"
            onClick={handlePreview}
            isBusy={previewBusy}
            disabled={runBusy || previewBusy}
            title={__('Show what would be published without actually running', 'linkdigest')}
          >
            {__('Preview', 'linkdigest')}
          </Button>
        </div>
        {renderRunResult()}
        {renderPreviewResult()}
      </div>

      <div className="linkdigest-schedule-sidebar">
        <NextSchedules config={config} form={form} />
        <DiagnosticsPanel data={diag} loading={diagLoading} onRefresh={refreshDiag} />
      </div>
    </div>
  );
}
