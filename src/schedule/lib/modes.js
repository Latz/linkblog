// PHP source of truth: ScheduleMode enum in src/php/ScheduleMode.php.
// Values are localized to window.linkdigestSchedule at runtime by Admin/Menu.php.
const loc = ( typeof window !== 'undefined' && window.linkdigestSchedule ) || {};

export const SCHEDULE_MODES = new Set( loc.timeModes    ?? [ 'daily', 'weekly', 'monthly' ] );
export const TRIGGER_MODES  = new Set( loc.triggerModes ?? [ 'count', 'age' ] );
export const ALL_MODES      = loc.allModes ?? [ 'daily', 'weekly', 'monthly', 'count', 'age', 'manual' ];
