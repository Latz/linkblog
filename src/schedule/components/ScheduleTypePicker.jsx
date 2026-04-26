/**
 * Button-group picker for selecting the schedule mode (daily/weekly/monthly,
 * trigger-based, or manual).
 */

import { Button } from '@wordpress/components';
import { __ } from '@wordpress/i18n';

const SCHEDULE_TYPES = [
  { value: 'daily',   label: __('Daily',   'linkdigest') },
  { value: 'weekly',  label: __('Weekly',  'linkdigest') },
  { value: 'monthly', label: __('Monthly', 'linkdigest') },
];

const TRIGGER_TYPES = [
  { value: 'count', label: __('By Count', 'linkdigest') },
  { value: 'age',   label: __('By Age',   'linkdigest') },
];

/**
 * Button-group picker for selecting the schedule mode.
 *
 * @param {string}   value    - Currently selected mode.
 * @param {Function} onChange - Called with the new mode string when selection changes.
 * @returns {JSX.Element}
 */
export default function ScheduleTypePicker({ value, onChange }) {
  return (
    <div className="linkdigest-mode-picker">
      {/* Group 1: time-based recurrence (rrule-driven) */}
      <div className="linkdigest-btn-group">
        {SCHEDULE_TYPES.map(t => (
          <Button
            key={t.value}
            variant={value === t.value ? 'primary' : 'secondary'}
            onClick={() => onChange(t.value)}
          >
            {t.label}
          </Button>
        ))}
      </div>
      <div className="linkdigest-btn-group-sep" />
      {/* Group 2: condition-based triggers (fire when threshold is met) */}
      <div className="linkdigest-btn-group">
        {TRIGGER_TYPES.map(t => (
          <Button
            key={t.value}
            variant={value === t.value ? 'primary' : 'secondary'}
            onClick={() => onChange(t.value)}
          >
            {t.label}
          </Button>
        ))}
      </div>
      <div className="linkdigest-btn-group-sep" />
      {/* Group 3: no automatic trigger — user starts the roundup manually */}
      <div className="linkdigest-btn-group">
        <Button
          variant={value === 'manual' ? 'primary' : 'secondary'}
          onClick={() => onChange('manual')}
        >
          {__('Manual', 'linkdigest')}
        </Button>
      </div>
    </div>
  );
}
