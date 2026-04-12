import { Button } from '@wordpress/components';
import { __ } from '@wordpress/i18n';

export default function TimePicker({ times, onChange }) {
  function addTime() {
    onChange([...times, '09:00']);
  }

  function updateTime(i, v) {
    onChange(times.map((t, idx) => idx === i ? v : t));
  }

  function removeTime(i) {
    onChange(times.filter((_, idx) => idx !== i));
  }

  return (
    <div className="linkblog-timepicker">
      {times.map((t, i) => (
        <div key={`${i}-${t}`} className="linkblog-time-row">
          <input
            type="time"
            className="linkblog-time-input"
            value={t}
            onChange={e => updateTime(i, e.target.value)}
          />
          {times.length > 1 && (
            <Button
              variant="destructive"
              size="compact"
              onClick={() => removeTime(i)}
              aria-label={__('Remove time', 'linkblog')}
            >
              ✕
            </Button>
          )}
        </div>
      ))}
      <Button variant="secondary" size="compact" onClick={addTime}>
        + {__('Add time', 'linkblog')}
      </Button>
    </div>
  );
}
