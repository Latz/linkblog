import { __ } from '@wordpress/i18n';

const GROUPS = [
  {
    label: __('Scheduled', 'linkdigest'),
    modes: [
      { value: 'daily',   label: __('Daily',   'linkdigest'), desc: __('Every N days', 'linkdigest') },
      { value: 'weekly',  label: __('Weekly',  'linkdigest'), desc: __('Specific weekdays', 'linkdigest') },
      { value: 'monthly', label: __('Monthly', 'linkdigest'), desc: __('Calendar days', 'linkdigest') },
    ],
  },
  {
    label: __('Trigger-based', 'linkdigest'),
    modes: [
      { value: 'count', label: __('By Count', 'linkdigest'), desc: __('When N links queue', 'linkdigest') },
      { value: 'age',   label: __('By Age',   'linkdigest'), desc: __('When oldest link ages', 'linkdigest') },
    ],
  },
  {
    label: __('Manual', 'linkdigest'),
    modes: [
      { value: 'manual', label: __('Manual', 'linkdigest'), desc: __('No auto-publish', 'linkdigest') },
    ],
  },
];

export default function ScheduleTypePicker({ value, onChange }) {
  return (
    <div className="linkdigest-mode-picker-v2" role="radiogroup">
      {GROUPS.map(group => (
        <div key={group.label} className="linkdigest-mode-card-group">
          <div className="linkdigest-mode-card-group-label">{group.label}</div>
          <div className="linkdigest-mode-cards">
            {group.modes.map(mode => {
              const active = value === mode.value;
              return (
                <button
                  key={mode.value}
                  role="radio"
                  aria-checked={active}
                  className={`linkdigest-mode-card${active ? ' linkdigest-mode-card--active' : ''}`}
                  onClick={() => onChange(mode.value)}
                  type="button"
                >
                  <span className="linkdigest-mode-card__check">
                    {active && (
                      <svg width="8" height="8" viewBox="0 0 8 8" fill="none">
                        <circle cx="4" cy="4" r="3" fill="#fff" />
                      </svg>
                    )}
                  </span>
                  <div className="linkdigest-mode-card__title">{mode.label}</div>
                  <div className="linkdigest-mode-card__desc">{mode.desc}</div>
                </button>
              );
            })}
          </div>
        </div>
      ))}
    </div>
  );
}
