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
      <div className="linkdigest-mode-group">
        <div className="linkdigest-mode-group-label">{__('Scheduled', 'linkdigest')}</div>
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
      </div>

      <div className="linkdigest-btn-group-sep" />

      <div className="linkdigest-mode-group">
        <div className="linkdigest-mode-group-label">{__('Trigger-based', 'linkdigest')}</div>
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
      </div>

      <div className="linkdigest-btn-group-sep" />

      <div className="linkdigest-mode-group">
        <div className="linkdigest-mode-group-label">{__('Manual', 'linkdigest')}</div>
        <div className="linkdigest-btn-group">
          <Button
            variant={value === 'manual' ? 'primary' : 'secondary'}
            onClick={() => onChange('manual')}
          >
            {__('Manual', 'linkdigest')}
          </Button>
        </div>
      </div>
    </div>
  );
}
