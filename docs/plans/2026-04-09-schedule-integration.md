# Schedule Integration Implementation Plan

> **For Claude:** REQUIRED SUB-SKILL: Use superpowers:executing-plans to implement this plan task-by-task.

**Goal:** Add a Schedule Configuration admin page to the LinkDigest WordPress plugin, backed by `wp_options`, built with `@wordpress/scripts` and WP React libraries.

**Architecture:** The timer's React source is ported into `src/schedule/` inside the plugin, built with `@wordpress/scripts` (webpack), and rendered in a WP admin submenu page. Config saves/loads via two REST endpoints (`GET`/`POST /linkdigest/v1/schedule`) writing to `wp_options('linkdigest_schedule')`. UI uses `@wordpress/components` (Button, NumberControl, SelectControl, Panel, Notice) for a native WP admin look.

**Tech Stack:** PHP, `@wordpress/scripts`, `@wordpress/element`, `@wordpress/components`, `@wordpress/api-fetch`, `@wordpress/i18n`, `rrule` npm package.

---

### Task 1: Build toolchain

**Files:**
- Create: `package.json`
- Create: `webpack.config.js`
- Modify: `.gitignore`

**Step 1: Create `package.json`**

```json
{
  "name": "linkdigest",
  "version": "1.0.0",
  "scripts": {
    "build": "wp-scripts build",
    "start": "wp-scripts start"
  },
  "devDependencies": {
    "@wordpress/scripts": "^30.0.0"
  },
  "dependencies": {
    "rrule": "^2.8.1"
  }
}
```

**Step 2: Create `webpack.config.js`**

`@wordpress/scripts` defaults to `src/index.js`. We override the entry to target our schedule app:

```js
const defaultConfig = require('@wordpress/scripts/config/webpack.config');

module.exports = {
  ...defaultConfig,
  entry: {
    schedule: './src/schedule/index.js',
  },
};
```

**Step 3: Add to `.gitignore`**

Append these lines:
```
node_modules/
build/
```

**Step 4: Install dependencies**

```bash
cd /home/latz/www/wp/wp-content/plugins/LinkDigest
npm install
```

Expected: `node_modules/` created, no errors.

**Step 5: Commit**

```bash
git add package.json webpack.config.js .gitignore
git commit -m "feat: add @wordpress/scripts build toolchain"
```

---

### Task 2: PHP — REST endpoints

**Files:**
- Modify: `linkdigest.php` — inside `linkdigest_register_rest_routes()` (line 918) and add two callbacks

**Step 1: Add schedule routes inside `linkdigest_register_rest_routes()`**

Add before the closing `}` of the function (after the `/categories` route):

```php
    register_rest_route('linkdigest/v1', '/schedule', array(
        array(
            'methods'             => 'GET',
            'callback'            => 'linkdigest_get_schedule',
            'permission_callback' => function() { return current_user_can('manage_options'); },
        ),
        array(
            'methods'             => 'POST',
            'callback'            => 'linkdigest_save_schedule',
            'permission_callback' => function() { return current_user_can('manage_options'); },
        ),
    ));
```

**Step 2: Add the two callback functions** (add after `linkdigest_register_rest_routes`)

```php
function linkdigest_get_schedule() {
    $default = array(
        'mode'       => 'daily',
        'recurrence' => array(
            'interval'  => 1,
            'weekdays'  => array(),
            'monthDays' => array(array('type' => 'day', 'value' => 1, 'nth' => 1, 'weekday' => 'MO')),
            'nthWeek'   => null,
        ),
        'trigger' => array('count' => 10, 'tag_id' => null, 'days' => 7),
        'times'   => array('09:00'),
    );
    $config = get_option('linkdigest_schedule', $default);
    return rest_ensure_response($config);
}

function linkdigest_save_schedule(WP_REST_Request $request) {
    $data = $request->get_json_params();
    if (empty($data) || !isset($data['mode'])) {
        return new WP_Error('invalid_data', __('Invalid schedule data', 'linkdigest'), array('status' => 400));
    }
    update_option('linkdigest_schedule', $data);
    return rest_ensure_response(array('success' => true));
}
```

**Step 3: Verify in browser**

Visit `https://yoursite.com/wp-json/linkdigest/v1/schedule` (logged in as admin). Should return the default JSON config.

**Step 4: Commit**

```bash
git add linkdigest.php
git commit -m "feat: add GET/POST REST endpoints for schedule config"
```

---

### Task 3: PHP — Admin page + enqueue

**Files:**
- Modify: `linkdigest.php` — `linkdigest_admin_menu()` (line 695) and `linkdigest_enqueue_admin_assets()` (line 888)

**Step 1: Add submenu page**

Inside `linkdigest_admin_menu()`, after the last `add_submenu_page` call (before the closing `}`):

```php
    add_submenu_page(
        'linkdigest-dashboard',
        __('Schedule', 'linkdigest'),
        __('Schedule', 'linkdigest'),
        'manage_options',
        'linkdigest-schedule',
        'linkdigest_schedule_page'
    );
```

**Step 2: Add the page callback** (add near the other page callbacks)

```php
function linkdigest_schedule_page() {
    ?>
    <div class="wrap">
        <h1><?php _e('Schedule Configuration', 'linkdigest'); ?></h1>
        <div id="linkdigest-schedule-root"></div>
    </div>
    <?php
}
```

**Step 3: Update `linkdigest_enqueue_admin_assets()`**

Replace the existing function body with:

```php
function linkdigest_enqueue_admin_assets($hook) {
    if (strpos($hook, 'linkdigest') === false) {
        return;
    }

    wp_enqueue_style('dashicons');
    wp_enqueue_style(
        'linkdigest-dashboard',
        plugin_dir_url(__FILE__) . 'dashboard.css',
        array(),
        '1.0.0'
    );

    if (strpos($hook, 'linkdigest-schedule') !== false) {
        $asset_file = plugin_dir_path(__FILE__) . 'build/schedule.asset.php';
        $asset = file_exists($asset_file)
            ? require($asset_file)
            : array('dependencies' => array(), 'version' => '1.0.0');

        wp_enqueue_script(
            'linkdigest-schedule',
            plugin_dir_url(__FILE__) . 'build/schedule.js',
            $asset['dependencies'],
            $asset['version'],
            true
        );

        if (file_exists(plugin_dir_path(__FILE__) . 'build/schedule.css')) {
            wp_enqueue_style(
                'linkdigest-schedule-style',
                plugin_dir_url(__FILE__) . 'build/schedule.css',
                array('wp-components'),
                $asset['version']
            );
        }
    }
}
```

**Step 4: Commit**

```bash
git add linkdigest.php
git commit -m "feat: add Schedule submenu page and enqueue schedule assets"
```

---

### Task 4: Port rrule lib

**Files:**
- Create: `src/schedule/lib/rrule.js`

**Step 1: Create directory and copy file**

```bash
mkdir -p src/schedule/lib src/schedule/components
cp /home/latz/www/timer/src/lib/rrule.js src/schedule/lib/rrule.js
```

No changes needed — the lib is pure JS with no React or CSS imports.

**Step 2: Commit**

```bash
git add src/schedule/lib/rrule.js
git commit -m "feat: add rrule lib for schedule computation"
```

---

### Task 5: ScheduleTypePicker component

**Files:**
- Create: `src/schedule/components/ScheduleTypePicker.jsx`

The mode picker row: `[Daily] [Weekly] [Monthly]  |  [By Count] [By Age]  |  [Manual]`. Uses WP `Button` with `variant="primary"` when active, `variant="secondary"` otherwise.

**Step 1: Create the component**

```jsx
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
```

**Step 2: Commit**

```bash
git add src/schedule/components/ScheduleTypePicker.jsx
git commit -m "feat: add ScheduleTypePicker component"
```

---

### Task 6: RecurrenceConfig component

**Files:**
- Create: `src/schedule/components/RecurrenceConfig.jsx`

Uses `NumberControl` and `SelectControl` from `@wordpress/components`. Monthly section keeps the side-by-side day/nth toggle from the timer.

**Step 1: Create the component**

```jsx
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

const NTH_OPTIONS = [
  { label: __('first',  'linkdigest'), value: '1' },
  { label: __('second', 'linkdigest'), value: '2' },
  { label: __('third',  'linkdigest'), value: '3' },
  { label: __('fourth', 'linkdigest'), value: '4' },
];

const WEEKDAY_OPTIONS = WEEKDAYS.map(d => ({ label: WEEKDAY_SHORT[d.value], value: d.value }));

function toggleDay(weekdays, day) {
  return weekdays.includes(day) ? weekdays.filter(d => d !== day) : [...weekdays, day];
}

export default function RecurrenceConfig({ type, value, onChange }) {
  if (type === 'daily') {
    return (
      <div className="linkdigest-rc-row">
        <span>{__('Every', 'linkdigest')}</span>
        <NumberControl
          value={String(value.interval)}
          min={1} max={365}
          onChange={v => onChange({ ...value, interval: parseInt(v) || 1 })}
          style={{ width: '72px' }}
        />
        <span>{value.interval !== 1 ? __('days', 'linkdigest') : __('day', 'linkdigest')}</span>
      </div>
    );
  }

  if (type === 'weekly') {
    return (
      <div className="linkdigest-rc">
        <div className="linkdigest-rc-row">
          <span>{__('Every', 'linkdigest')}</span>
          <NumberControl
            value={String(value.interval)}
            min={1} max={52}
            onChange={v => onChange({ ...value, interval: parseInt(v) || 1 })}
            style={{ width: '72px' }}
          />
          <span>{value.interval !== 1 ? __('weeks', 'linkdigest') : __('week', 'linkdigest')}</span>
        </div>
        <div className="linkdigest-weekdays">
          {WEEKDAYS.map(d => (
            <Button
              key={d.value}
              variant={(value.weekdays || []).includes(d.value) ? 'primary' : 'secondary'}
              size="compact"
              title={WEEKDAY_SHORT[d.value]}
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
      onChange({ ...value, monthDays: [...monthDays, { ...last, value: Math.min(31, last.value + 1) }] });
    }

    function removeDay(i) {
      onChange({ ...value, monthDays: monthDays.filter((_, idx) => idx !== i) });
    }

    return (
      <div className="linkdigest-rc">
        <div className="linkdigest-rc-row">
          <span>{__('Every', 'linkdigest')}</span>
          <NumberControl
            value={String(value.interval)}
            min={1} max={12}
            onChange={v => onChange({ ...value, interval: parseInt(v) || 1 })}
            style={{ width: '72px' }}
          />
          <span>{value.interval !== 1 ? __('months, on', 'linkdigest') : __('month, on', 'linkdigest')}</span>
        </div>

        <div className="linkdigest-month-days">
          {monthDays.map((entry, i) => (
            <div key={i} className="linkdigest-month-day-row">
              <span className="linkdigest-day-index">{String(i + 1).padStart(2, '0')}</span>

              <div
                className={`linkdigest-opt ${entry.type === 'day' ? 'linkdigest-opt--on' : 'linkdigest-opt--off'}`}
                onClick={() => entry.type !== 'day' && updateEntry(i, { type: 'day' })}
              >
                <NumberControl
                  value={String(entry.value ?? 1)}
                  min={1} max={31}
                  autoFocus={i === 0 && entry.type === 'day'}
                  onChange={v => updateEntry(i, { value: parseInt(v) || 1 })}
                  style={{ width: '72px' }}
                />
              </div>

              <span className="linkdigest-opt-sep">{__('or', 'linkdigest')}</span>

              <div
                className={`linkdigest-opt ${entry.type === 'nth' ? 'linkdigest-opt--on' : 'linkdigest-opt--off'}`}
                onClick={() => entry.type !== 'nth' && updateEntry(i, { type: 'nth' })}
              >
                <SelectControl
                  value={String(entry.nth ?? 1)}
                  options={NTH_OPTIONS}
                  onChange={v => updateEntry(i, { nth: parseInt(v) })}
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
                  isDestructive
                  size="compact"
                  onClick={() => removeDay(i)}
                  aria-label={__('Remove', 'linkdigest')}
                >
                  ✕
                </Button>
              )}
            </div>
          ))}

          <Button variant="secondary" size="compact" onClick={addDay}>
            + {__('Add day', 'linkdigest')}
          </Button>
        </div>
      </div>
    );
  }

  return null;
}
```

**Step 2: Commit**

```bash
git add src/schedule/components/RecurrenceConfig.jsx
git commit -m "feat: add RecurrenceConfig component with WP components"
```

---

### Task 7: TriggerCondition component

**Files:**
- Create: `src/schedule/components/TriggerCondition.jsx`

**Step 1: Create the component**

```jsx
import { __experimentalNumberControl as NumberControl } from '@wordpress/components';
import { __ } from '@wordpress/i18n';

export default function TriggerCondition({ mode, value, onChange }) {
  if (mode === 'count') {
    return (
      <div className="linkdigest-rc-row">
        <span>{__('Post when there are at least', 'linkdigest')}</span>
        <NumberControl
          value={String(value.count)}
          min={1}
          onChange={v => onChange({ ...value, count: parseInt(v) || 1 })}
          style={{ width: '72px' }}
        />
        <span>{value.count !== 1 ? __('links', 'linkdigest') : __('link', 'linkdigest')}</span>
      </div>
    );
  }

  return (
    <div className="linkdigest-rc-row">
      <span>{__('Post when oldest link is older than', 'linkdigest')}</span>
      <NumberControl
        value={String(value.days)}
        min={1}
        onChange={v => onChange({ ...value, days: parseInt(v) || 1 })}
        style={{ width: '72px' }}
      />
      <span>{value.days !== 1 ? __('days', 'linkdigest') : __('day', 'linkdigest')}</span>
    </div>
  );
}
```

**Step 2: Commit**

```bash
git add src/schedule/components/TriggerCondition.jsx
git commit -m "feat: add TriggerCondition component"
```

---

### Task 8: TimePicker component

**Files:**
- Create: `src/schedule/components/TimePicker.jsx`

**Step 1: Create the component**

```jsx
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
    <div className="linkdigest-timepicker">
      {times.map((t, i) => (
        <div key={i} className="linkdigest-time-row">
          <input
            type="time"
            className="linkdigest-time-input"
            value={t}
            onChange={e => updateTime(i, e.target.value)}
          />
          {times.length > 1 && (
            <Button
              isDestructive
              size="compact"
              onClick={() => removeTime(i)}
              aria-label={__('Remove time', 'linkdigest')}
            >
              ✕
            </Button>
          )}
        </div>
      ))}
      <Button variant="secondary" size="compact" onClick={addTime}>
        + {__('Add time', 'linkdigest')}
      </Button>
    </div>
  );
}
```

**Step 2: Commit**

```bash
git add src/schedule/components/TimePicker.jsx
git commit -m "feat: add TimePicker component"
```

---

### Task 9: NextSchedules component

**Files:**
- Create: `src/schedule/components/NextSchedules.jsx`

**Step 1: Create the component**

```jsx
import { useMemo } from '@wordpress/element';
import { RRule } from 'rrule';
import { __ } from '@wordpress/i18n';

const SCHEDULE_MODES = ['daily', 'weekly', 'monthly'];
const DAYS   = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];
const MONTHS = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];

function formatDate(d) {
  return `${DAYS[d.getUTCDay()]} ${d.getUTCDate()} ${MONTHS[d.getUTCMonth()]} ${d.getUTCFullYear()}`;
}

export default function NextSchedules({ config, form }) {
  const isSchedule = SCHEDULE_MODES.includes(form.mode);

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
        <h2 className="hndle">{__('Next 10 Schedules', 'linkdigest')}</h2>
      </div>
      <div className="inside">
        {nextDates.length > 0 ? (
          <ol className="linkdigest-next-schedules">
            {nextDates.map((d, i) => (
              <li key={i} className="linkdigest-next-schedule-row">
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
```

**Step 2: Commit**

```bash
git add src/schedule/components/NextSchedules.jsx
git commit -m "feat: add NextSchedules component"
```

---

### Task 10: App.jsx

**Files:**
- Create: `src/schedule/App.jsx`

**Step 1: Create App.jsx**

```jsx
import { useState, useEffect, useMemo } from '@wordpress/element';
import apiFetch from '@wordpress/api-fetch';
import { Button, Panel, PanelBody, Notice } from '@wordpress/components';
import { __ } from '@wordpress/i18n';
import { buildRRule } from './lib/rrule';
import ScheduleTypePicker from './components/ScheduleTypePicker';
import RecurrenceConfig from './components/RecurrenceConfig';
import TriggerCondition from './components/TriggerCondition';
import TimePicker from './components/TimePicker';
import NextSchedules from './components/NextSchedules';

const SCHEDULE_MODES = ['daily', 'weekly', 'monthly'];

const DEFAULT_FORM = {
  mode: 'daily',
  recurrence: { interval: 1, weekdays: [], monthDays: [{ type: 'day', value: 1, nth: 1, weekday: 'MO' }], nthWeek: null },
  trigger: { count: 10, tag_id: null, days: 7 },
  times: ['09:00'],
};

export default function App() {
  const [form, setForm] = useState(DEFAULT_FORM);
  const [saving, setSaving] = useState(false);
  const [notice, setNotice] = useState(null);

  useEffect(() => {
    apiFetch({ path: '/linkdigest/v1/schedule' })
      .then(data => setForm({ ...DEFAULT_FORM, ...data }))
      .catch(() => {});
  }, []);

  async function handleSave() {
    setSaving(true);
    setNotice(null);
    try {
      await apiFetch({ path: '/linkdigest/v1/schedule', method: 'POST', data: form });
      setNotice({ status: 'success', message: __('Schedule saved.', 'linkdigest') });
    } catch {
      setNotice({ status: 'error', message: __('Failed to save schedule.', 'linkdigest') });
    } finally {
      setSaving(false);
    }
  }

  function handleModeChange(mode) {
    setForm(f => ({
      ...f,
      mode,
      recurrence: SCHEDULE_MODES.includes(mode)
        ? { interval: 1, weekdays: [], monthDays: [{ type: 'day', value: 1, nth: 1, weekday: 'MO' }], nthWeek: null }
        : f.recurrence,
    }));
  }

  const isSchedule = SCHEDULE_MODES.includes(form.mode);
  const isManual   = form.mode === 'manual';

  const config = isSchedule
    ? { rrule: buildRRule({ type: form.mode, ...form.recurrence }), times: form.times, trigger: null }
    : isManual
    ? { rrule: null, times: [], trigger: { type: 'manual' } }
    : { rrule: null, times: form.times, trigger: { type: form.mode, ...form.trigger } };

  const section02Label = isSchedule ? __('Recurrence', 'linkdigest') : __('Condition', 'linkdigest');

  return (
    <div className="linkdigest-schedule-wrap">
      <div className="linkdigest-schedule-main">
        {notice && (
          <Notice status={notice.status} onRemove={() => setNotice(null)} isDismissible>
            {notice.message}
          </Notice>
        )}

        <Panel>
          <PanelBody title={__('Mode', 'linkdigest')} initialOpen>
            <ScheduleTypePicker value={form.mode} onChange={handleModeChange} />
          </PanelBody>

          <PanelBody title={section02Label} initialOpen>
            {isSchedule ? (
              <RecurrenceConfig
                type={form.mode}
                value={form.recurrence}
                onChange={v => setForm(f => ({ ...f, recurrence: v }))}
              />
            ) : isManual ? (
              <p className="description">
                {__('No automatic trigger — posts must be triggered manually.', 'linkdigest')}
              </p>
            ) : (
              <TriggerCondition
                mode={form.mode}
                value={form.trigger}
                onChange={v => setForm(f => ({ ...f, trigger: v }))}
              />
            )}
          </PanelBody>

          {!isManual && (
            <PanelBody title={__('Execution Times', 'linkdigest')} initialOpen>
              <TimePicker
                times={form.times}
                onChange={v => setForm(f => ({ ...f, times: v }))}
              />
            </PanelBody>
          )}
        </Panel>

        <div className="linkdigest-schedule-actions">
          <Button variant="primary" onClick={handleSave} isBusy={saving} disabled={saving}>
            {__('Save Schedule', 'linkdigest')}
          </Button>
        </div>
      </div>

      <div className="linkdigest-schedule-sidebar">
        <NextSchedules config={config} form={form} />
      </div>
    </div>
  );
}
```

**Step 2: Commit**

```bash
git add src/schedule/App.jsx
git commit -m "feat: add main App component with load/save via REST API"
```

---

### Task 11: Entry point + CSS

**Files:**
- Create: `src/schedule/index.js`
- Create: `src/schedule/schedule.css`

**Step 1: Create `src/schedule/index.js`**

```js
import { createRoot } from '@wordpress/element';
import App from './App';
import './schedule.css';

const root = document.getElementById('linkdigest-schedule-root');
if (root) {
  createRoot(root).render(<App />);
}
```

**Step 2: Create `src/schedule/schedule.css`**

Minimal layout — everything else uses WP admin defaults:

```css
.linkdigest-schedule-wrap {
  display: flex;
  gap: 24px;
  align-items: flex-start;
  margin-top: 16px;
}

.linkdigest-schedule-main {
  flex: 1;
  min-width: 0;
}

.linkdigest-schedule-sidebar {
  width: 280px;
  flex-shrink: 0;
}

.linkdigest-schedule-actions {
  margin-top: 16px;
}

/* Mode picker */
.linkdigest-mode-picker {
  display: flex;
  align-items: center;
  gap: 4px;
  flex-wrap: wrap;
}

.linkdigest-btn-group {
  display: flex;
  gap: 0;
}

.linkdigest-btn-group .components-button {
  border-radius: 0;
  margin: 0;
  border-right-width: 0;
}

.linkdigest-btn-group .components-button:first-child { border-radius: 2px 0 0 2px; }
.linkdigest-btn-group .components-button:last-child  { border-radius: 0 2px 2px 0; border-right-width: 1px; }

.linkdigest-btn-group-sep {
  width: 1px;
  background: #dcdcde;
  margin: 2px 6px;
  align-self: stretch;
}

/* Recurrence rows */
.linkdigest-rc-row {
  display: flex;
  align-items: center;
  gap: 8px;
  flex-wrap: wrap;
}

.linkdigest-rc {
  display: flex;
  flex-direction: column;
  gap: 12px;
}

.linkdigest-weekdays {
  display: flex;
  gap: 2px;
}

/* Monthly day rows */
.linkdigest-month-days {
  display: flex;
  flex-direction: column;
  gap: 6px;
}

.linkdigest-month-day-row {
  display: flex;
  align-items: center;
  gap: 8px;
  flex-wrap: wrap;
}

.linkdigest-day-index {
  font-size: 11px;
  color: #787c82;
  width: 18px;
  text-align: right;
  flex-shrink: 0;
}

.linkdigest-opt {
  display: flex;
  align-items: center;
  gap: 6px;
  border-radius: 2px;
  transition: opacity 0.15s;
}

.linkdigest-opt--off {
  opacity: 0.3;
  cursor: pointer;
}

.linkdigest-opt--off * {
  pointer-events: none;
}

.linkdigest-opt-sep {
  font-size: 12px;
  color: #787c82;
}

/* TimePicker */
.linkdigest-timepicker {
  display: flex;
  flex-direction: column;
  gap: 6px;
}

.linkdigest-time-row {
  display: flex;
  align-items: center;
  gap: 8px;
}

.linkdigest-time-input {
  padding: 4px 8px;
  border: 1px solid #8c8f94;
  border-radius: 3px;
  font-size: 14px;
}

/* Next schedules */
.linkdigest-next-schedules {
  margin: 0;
  padding: 0;
  list-style: none;
}

.linkdigest-next-schedule-row {
  display: flex;
  justify-content: space-between;
  padding: 4px 0;
  border-bottom: 1px solid #f0f0f1;
  font-size: 13px;
}

.linkdigest-next-schedule-row:last-child {
  border-bottom: none;
}

.linkdigest-next-time {
  color: #2271b1;
  font-size: 12px;
}
```

**Step 3: Commit**

```bash
git add src/schedule/index.js src/schedule/schedule.css
git commit -m "feat: add schedule entry point and layout CSS"
```

---

### Task 12: Build and verify

**Step 1: Build**

```bash
cd /home/latz/www/wp/wp-content/plugins/LinkDigest
npm run build
```

Expected: `build/schedule.js`, `build/schedule.css`, `build/schedule.asset.php` created with no errors.

**Step 2: Check assets exist**

```bash
ls -la build/
```

Expected: `schedule.js`, `schedule.css`, `schedule.asset.php` present.

**Step 3: Verify in browser**

1. Go to WordPress admin → LinkDigest → Schedule
2. Page should load with the Schedule Configuration form
3. All three section panels should be visible: Mode, Recurrence/Condition, Execution Times
4. Change mode to Weekly, select some days — "Next 10 Schedules" postbox should update
5. Click "Save Schedule" — success notice should appear
6. Reload page — saved values should persist

**Step 4: Verify database**

In WP admin → Tools → Site Health → Info, or via WP CLI:
```bash
wp option get linkdigest_schedule --format=json
```

Expected: JSON object matching the saved form state.

**Step 5: Final commit**

```bash
git add build/
git commit -m "feat: add compiled schedule assets"
```

> Note: If you prefer not to commit build artifacts, add `build/` back to `.gitignore` and set up a build step in your deployment process instead.
