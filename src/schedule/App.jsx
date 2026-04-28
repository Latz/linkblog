import { useState, useEffect, useMemo } from '@wordpress/element';
import apiFetch from '@wordpress/api-fetch';
import { Button, Panel, PanelBody, Notice } from '@wordpress/components';
import { __ } from '@wordpress/i18n';
import { buildRRule } from './lib/rrule';
import { SCHEDULE_MODES } from './lib/modes';
import ScheduleTypePicker from './components/ScheduleTypePicker';
import RecurrenceConfig from './components/RecurrenceConfig';
import TriggerCondition from './components/TriggerCondition';
import TimePicker from './components/TimePicker';
import NextSchedules from './components/NextSchedules';

// Baseline form state. Also used as a fallback template when the API response
// omits keys (spread with API data in the useEffect below).
const DEFAULT_FORM = {
  mode: 'daily',
  // recurrence is only used by daily/weekly/monthly modes
  recurrence: { interval: 1, weekdays: [], monthDays: [{ type: 'day', value: 1, nth: 1, weekday: 'MO' }], nthWeek: null },
  // trigger is only used by count/age modes
  trigger: { count: 10, tag_id: null, days: 7 },
  times: ['09:00'],
};

/**
 * Root component for the schedule settings page.
 *
 * @returns {JSX.Element}
 */
export default function App() {
  const [form, setForm] = useState(DEFAULT_FORM);
  const [saving, setSaving] = useState(false);
  const [notice, setNotice] = useState(null);

  useEffect(() => {
    apiFetch({ path: '/linkdigest/v1/schedule' })
      // Spread over DEFAULT_FORM so any keys absent from the API response
      // (e.g. first-run with no saved schedule) still get valid defaults.
      .then(data => setForm({ ...DEFAULT_FORM, ...data }))
      .catch(() => {});
  }, []);

  /**
   * Validates times, then POSTs the current form state to the schedule API.
   *
   * @returns {Promise<void>}
   */
  async function handleSave() {
    setSaving(true);
    setNotice(null);
    // Duplicate times would result in the scheduler firing multiple jobs at the
    // same instant; catch this client-side before hitting the API.
    if (new Set(form.times).size !== form.times.length) {
      setNotice({ status: 'error', message: __('Execution times must be unique.', 'linkdigest') });
      setSaving(false);
      return;
    }
    try {
      await apiFetch({ path: '/linkdigest/v1/schedule', method: 'POST', data: form });
      setNotice({ status: 'success', message: __('Schedule saved.', 'linkdigest') });
    } catch {
      setNotice({ status: 'error', message: __('Failed to save schedule.', 'linkdigest') });
    } finally {
      setSaving(false);
    }
  }

  /**
   * Switches the schedule mode and resets recurrence state when entering a
   * time-based mode so stale weekly/monthly config does not carry over.
   *
   * @param {string} mode - New schedule mode (daily | weekly | monthly | count | age | manual).
   */
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

  // Derived config passed to NextSchedules and ultimately saved to the API.
  // Shape varies by mode:
  //   schedule → rrule string + times, no trigger
  //   manual   → no rrule, no times, trigger signals "run on demand"
  //   trigger  → no rrule, times still apply (fire when condition + time align)
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

  /**
   * Returns the recurrence, trigger, or manual-notice section depending on mode.
   *
   * @returns {JSX.Element|null}
   */
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

  return (
    <div className="linkdigest-schedule-wrap">
      <div className="linkdigest-schedule-main">
        {notice && (
          <Notice status={notice.status} onRemove={() => setNotice(null)} isDismissible>
            {notice.message}
          </Notice>
        )}

        <Panel>
          <PanelBody title={__('Mode', 'linkdigest')} initialOpen>
            <ScheduleTypePicker value={form.mode} onChange={handleModeChange} />
          </PanelBody>

          <PanelBody title={section02Label} initialOpen>
            {renderConditionSection()}
          </PanelBody>

          {!isManual && (
            <PanelBody title={__('Execution Times', 'linkdigest')} initialOpen>
              <TimePicker
                times={form.times}
                onChange={v => setForm(f => ({ ...f, times: v }))}
              />
            </PanelBody>
          )}
        </Panel>

        <div className="linkdigest-schedule-actions">
          <Button variant="primary" onClick={handleSave} isBusy={saving} disabled={saving}>
            {__('Save Schedule', 'linkdigest')}
          </Button>
        </div>
      </div>

      <div className="linkdigest-schedule-sidebar">
        <NextSchedules config={config} form={form} />
      </div>
    </div>
  );
}
