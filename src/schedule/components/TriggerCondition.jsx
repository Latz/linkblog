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
          onChange={v => onChange({ ...value, count: Number.parseInt(v) || 1 })}
          style={{ width: '72px' }}
        />
        <span>{value.count === 1 ? __('link', 'linkdigest') : __('links', 'linkdigest')}</span>
      </div>
    );
  }

  if (mode === 'age') {
    return (
      <div className="linkdigest-rc-row">
        <span>{__('Post when oldest link is older than', 'linkdigest')}</span>
        <NumberControl
          value={String(value.days ?? 1)}
          min={1}
          onChange={v => onChange({ ...value, days: Number.parseInt(v) || 1 })}
          style={{ width: '72px' }}
        />
        <span>{(value.days ?? 1) === 1 ? __('day', 'linkdigest') : __('days', 'linkdigest')}</span>
      </div>
    );
  }

  return null;
}
