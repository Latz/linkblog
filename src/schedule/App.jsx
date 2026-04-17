import { useState, useEffect, useMemo } from '@wordpress/element';
import apiFetch from '@wordpress/api-fetch';
import { Button, Panel, PanelBody, Notice } from '@wordpress/components';
import { __ } from '@wordpress/i18n';
import { buildRRule } from './lib/rrule';
import ScheduleTypePicker from './components/ScheduleTypePicker';
import RecurrenceConfig from './components/RecurrenceConfig';
import TriggerCondition from './components/TriggerCondition';
import TimePicker from './components/TimePicker';
import NextSchedules from './components/NextSchedules';

const SCHEDULE_MODES = new Set(['daily', 'weekly', 'monthly']);

const DEFAULT_FORM = {
  mode: 'daily',
  recurrence: { interval: 1, weekdays: [], monthDays: [{ type: 'day', value: 1, nth: 1, weekday: 'MO' }], nthWeek: null },
  trigger: { count: 10, tag_id: null, days: 7 },
  times: ['09:00'],
};

export default function App() {
  const [form, setForm] = useState(DEFAULT_FORM);
  const [saving, setSaving] = useState(false);
  const [notice, setNotice] = useState(null);

  useEffect(() => {
    apiFetch({ path: '/linkblog/v1/schedule' })
      .then(data => setForm({ ...DEFAULT_FORM, ...data }))
      .catch(() => {});
  }, []);

  async function handleSave() {
    setSaving(true);
    setNotice(null);
    try {
      await apiFetch({ path: '/linkblog/v1/schedule', method: 'POST', data: form });
      setNotice({ status: 'success', message: __('Schedule saved.', 'linkblog') });
    } catch {
      setNotice({ status: 'error', message: __('Failed to save schedule.', 'linkblog') });
    } finally {
      setSaving(false);
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

  const section02Label = isSchedule ? __('Recurrence', 'linkblog') : __('Condition', 'linkblog');

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
        {__('No automatic trigger — posts must be triggered manually.', 'linkblog')}
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
    <div className="linkblog-schedule-wrap">
      <div className="linkblog-schedule-main">
        {notice && (
          <Notice status={notice.status} onRemove={() => setNotice(null)} isDismissible>
            {notice.message}
          </Notice>
        )}

        <Panel>
          <PanelBody title={__('Mode', 'linkblog')} initialOpen>
            <ScheduleTypePicker value={form.mode} onChange={handleModeChange} />
          </PanelBody>

          <PanelBody title={section02Label} initialOpen>
            {renderConditionSection()}
          </PanelBody>

          {!isManual && (
            <PanelBody title={__('Execution Times', 'linkblog')} initialOpen>
              <TimePicker
                times={form.times}
                onChange={v => setForm(f => ({ ...f, times: v }))}
              />
            </PanelBody>
          )}
        </Panel>

        <div className="linkblog-schedule-actions">
          <Button variant="primary" onClick={handleSave} isBusy={saving} disabled={saving}>
            {__('Save Schedule', 'linkblog')}
          </Button>
        </div>
      </div>

      <div className="linkblog-schedule-sidebar">
        <NextSchedules config={config} form={form} />
      </div>
    </div>
  );
}
