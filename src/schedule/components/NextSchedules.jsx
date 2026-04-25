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
      const now = new Date();
      const allTimesPast = form.times.every(t => {
        const [h, m] = t.split(':').map(Number);
        const todayAt = new Date(now.getFullYear(), now.getMonth(), now.getDate(), h, m, 0);
        return now >= todayAt;
      });
      const dtstart = allTimesPast
        ? new Date(now.getFullYear(), now.getMonth(), now.getDate() + 1, 0, 0, 0)
        : now;
      const parsed = RRule.fromString(config.rrule);
      const rule = new RRule({ ...parsed.options, dtstart });
      return rule.all((_, i) => i < 10);
    } catch {
      return [];
    }
  }, [config.rrule, form.times, isSchedule]);

  if (!isSchedule) return null;

  return (
    <div className="postbox linkdigest-next-postbox">
      <div className="linkdigest-next-heading">{__('Next 10 Schedules', 'linkdigest')}</div>
      <div className="inside linkdigest-next-schedules-inside">
        {nextDates.length > 0 ? (
          <ol className="linkdigest-next-schedules">
            {nextDates.map((d, i) => (
              <li key={d.toISOString()} className="linkdigest-next-schedule-row">
                <span className="linkdigest-next-date">{formatDate(d)}</span>
                {form.times.length > 0 && (
                  <span className="linkdigest-next-time">{form.times.join(', ')}</span>
                )}
              </li>
            ))}
          </ol>
        ) : (
          <p className="description">{__('No occurrences — check recurrence settings.', 'linkdigest')}</p>
        )}
      </div>
    </div>
  );
}
