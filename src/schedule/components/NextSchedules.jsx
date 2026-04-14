import { useMemo } from '@wordpress/element';
import { RRule } from 'rrule';
import { __ } from '@wordpress/i18n';

const SCHEDULE_MODES = new Set(['daily', 'weekly', 'monthly']);
const DAYS   = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];
const MONTHS = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];

function formatDate(d) {
  return `${DAYS[d.getUTCDay()]} ${d.getUTCDate()} ${MONTHS[d.getUTCMonth()]} ${d.getUTCFullYear()}`;
}

export default function NextSchedules({ config, form }) {
  const isSchedule = SCHEDULE_MODES.has(form.mode);

  const nextDates = useMemo(() => {
    if (!isSchedule || !config.rrule) return [];
    try {
      const parsed = RRule.fromString(config.rrule);
      const rule = new RRule({ ...parsed.options, dtstart: new Date() });
      return rule.all((_, i) => i < 10);
    } catch {
      return [];
    }
  }, [config.rrule, isSchedule]);

  if (!isSchedule) return null;

  return (
    <div className="postbox">
      <div className="postbox-header">
        <h2 className="hndle">{__('Next 10 Schedules', 'linkblog')}</h2>
      </div>
      <div className="inside">
        {nextDates.length > 0 ? (
          <ol className="linkblog-next-schedules">
            {nextDates.map((d, i) => (
              <li key={d.toISOString()} className="linkblog-next-schedule-row">
                <span className="linkblog-next-date">{formatDate(d)}</span>
                {form.times.length > 0 && (
                  <span className="linkblog-next-time">{form.times.join(', ')}</span>
                )}
              </li>
            ))}
          </ol>
        ) : (
          <p className="description">{__('No occurrences — check recurrence settings.', 'linkblog')}</p>
        )}
      </div>
    </div>
  );
}
