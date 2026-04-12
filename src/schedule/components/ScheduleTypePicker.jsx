import { Button } from '@wordpress/components';
import { __ } from '@wordpress/i18n';

const SCHEDULE_TYPES = [
  { value: 'daily',   label: __('Daily',   'linkblog') },
  { value: 'weekly',  label: __('Weekly',  'linkblog') },
  { value: 'monthly', label: __('Monthly', 'linkblog') },
];

const TRIGGER_TYPES = [
  { value: 'count', label: __('By Count', 'linkblog') },
  { value: 'age',   label: __('By Age',   'linkblog') },
];

export default function ScheduleTypePicker({ value, onChange }) {
  return (
    <div className="linkblog-mode-picker">
      <div className="linkblog-btn-group">
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
      <div className="linkblog-btn-group-sep" />
      <div className="linkblog-btn-group">
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
      <div className="linkblog-btn-group-sep" />
      <div className="linkblog-btn-group">
        <Button
          variant={value === 'manual' ? 'primary' : 'secondary'}
          onClick={() => onChange('manual')}
        >
          {__('Manual', 'linkblog')}
        </Button>
      </div>
    </div>
  );
}
