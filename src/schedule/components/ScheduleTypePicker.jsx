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

export default function ScheduleTypePicker({ value, onChange }) {
  return (
    <div className="linkdigest-mode-picker">
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
