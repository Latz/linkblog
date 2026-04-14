import { __experimentalNumberControl as NumberControl, SelectControl, Button } from '@wordpress/components';
import { __ } from '@wordpress/i18n';

const WEEKDAYS = [
  { value: 'MO', label: 'M' },
  { value: 'TU', label: 'T' },
  { value: 'WE', label: 'W' },
  { value: 'TH', label: 'T' },
  { value: 'FR', label: 'F' },
  { value: 'SA', label: 'S' },
  { value: 'SU', label: 'S' },
];

const WEEKDAY_SHORT = { MO: 'Mon', TU: 'Tue', WE: 'Wed', TH: 'Thu', FR: 'Fri', SA: 'Sat', SU: 'Sun' };
const WEEKDAY_FULL  = { MO: 'Monday', TU: 'Tuesday', WE: 'Wednesday', TH: 'Thursday', FR: 'Friday', SA: 'Saturday', SU: 'Sunday' };

const NTH_OPTIONS = [
  { label: __('first',  'linkblog'), value: '1' },
  { label: __('second', 'linkblog'), value: '2' },
  { label: __('third',  'linkblog'), value: '3' },
  { label: __('fourth', 'linkblog'), value: '4' },
];

const WEEKDAY_OPTIONS = WEEKDAYS.map(d => ({ label: WEEKDAY_SHORT[d.value], value: d.value }));

function toggleDay(weekdays, day) {
  return weekdays.includes(day) ? weekdays.filter(d => d !== day) : [...weekdays, day];
}

export default function RecurrenceConfig({ type, value, onChange }) {
  if (type === 'daily') {
    return (
      <div className="linkblog-rc-row">
        <span>{__('Every', 'linkblog')}</span>
        <NumberControl
          value={String(value.interval)}
          min={1} max={365}
          onChange={v => onChange({ ...value, interval: Number.parseInt(v) || 1 })}
          style={{ width: '72px' }}
        />
        <span>{value.interval !== 1 ? __('days', 'linkblog') : __('day', 'linkblog')}</span>
      </div>
    );
  }

  if (type === 'weekly') {
    return (
      <div className="linkblog-rc">
        <div className="linkblog-rc-row">
          <span>{__('Every', 'linkblog')}</span>
          <NumberControl
            value={String(value.interval)}
            min={1} max={52}
            onChange={v => onChange({ ...value, interval: Number.parseInt(v) || 1 })}
            style={{ width: '72px' }}
          />
          <span>{value.interval !== 1 ? __('weeks', 'linkblog') : __('week', 'linkblog')}</span>
        </div>
        <div className="linkblog-weekdays">
          {WEEKDAYS.map(d => (
            <Button
              key={d.value}
              variant={(value.weekdays || []).includes(d.value) ? 'primary' : 'secondary'}
              size="compact"
              title={WEEKDAY_FULL[d.value]}
              onClick={() => onChange({ ...value, weekdays: toggleDay(value.weekdays || [], d.value) })}
            >
              {d.label}
            </Button>
          ))}
        </div>
      </div>
    );
  }

  if (type === 'monthly') {
    const monthDays = value.monthDays ?? [{ type: 'day', value: 1, nth: 1, weekday: 'MO' }];

    function updateEntry(i, patch) {
      onChange({ ...value, monthDays: monthDays.map((e, idx) => idx === i ? { ...e, ...patch } : e) });
    }

    function addDay() {
      const last = monthDays.at(-1) ?? { type: 'day', value: 1, nth: 1, weekday: 'MO' };
      onChange({ ...value, monthDays: [...monthDays, { id: Date.now(), ...last, value: Math.min(31, last.value + 1) }] });
    }

    function removeDay(i) {
      onChange({ ...value, monthDays: monthDays.filter((_, idx) => idx !== i) });
    }

    return (
      <div className="linkblog-rc">
        <div className="linkblog-rc-row">
          <span>{__('Every', 'linkblog')}</span>
          <NumberControl
            value={String(value.interval)}
            min={1} max={12}
            onChange={v => onChange({ ...value, interval: Number.parseInt(v) || 1 })}
            style={{ width: '72px' }}
          />
          <span>{value.interval !== 1 ? __('months, on', 'linkblog') : __('month, on', 'linkblog')}</span>
        </div>

        <div className="linkblog-month-days">
          {monthDays.map((entry, i) => (
            <div key={entry.id ?? i} className="linkblog-month-day-row">
              <span className="linkblog-day-index">{String(i + 1).padStart(2, '0')}</span>

              <div
                className={`linkblog-opt ${entry.type === 'day' ? 'linkblog-opt--on' : 'linkblog-opt--off'}`}
                role="button"
                tabIndex={0}
                onClick={() => entry.type !== 'day' && updateEntry(i, { type: 'day' })}
                onKeyDown={e => (e.key === 'Enter' || e.key === ' ') && entry.type !== 'day' && updateEntry(i, { type: 'day' })}
              >
                <NumberControl
                  value={String(entry.value ?? 1)}
                  min={1} max={31}
                  autoFocus={i === 0 && entry.type === 'day'}
                  onChange={v => updateEntry(i, { value: Number.parseInt(v) || 1 })}
                  style={{ width: '72px' }}
                />
              </div>

              <span className="linkblog-opt-sep">{__('or', 'linkblog')}</span>

              <div
                className={`linkblog-opt ${entry.type === 'nth' ? 'linkblog-opt--on' : 'linkblog-opt--off'}`}
                role="button"
                tabIndex={0}
                onClick={() => entry.type !== 'nth' && updateEntry(i, { type: 'nth' })}
                onKeyDown={e => (e.key === 'Enter' || e.key === ' ') && entry.type !== 'nth' && updateEntry(i, { type: 'nth' })}
              >
                <SelectControl
                  value={String(entry.nth ?? 1)}
                  options={NTH_OPTIONS}
                  onChange={v => updateEntry(i, { nth: Number.parseInt(v) })}
                  __nextHasNoMarginBottom
                />
                <SelectControl
                  value={entry.weekday ?? 'MO'}
                  options={WEEKDAY_OPTIONS}
                  onChange={v => updateEntry(i, { weekday: v })}
                  __nextHasNoMarginBottom
                />
              </div>

              {monthDays.length > 1 && (
                <Button
                  variant="destructive"
                  size="compact"
                  onClick={() => removeDay(i)}
                  aria-label={__('Remove', 'linkblog')}
                >
                  ✕
                </Button>
              )}
            </div>
          ))}

          <Button variant="secondary" size="compact" onClick={addDay}>
            + {__('Add day', 'linkblog')}
          </Button>
        </div>
      </div>
    );
  }

  return null;
}
