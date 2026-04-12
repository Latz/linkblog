import { __experimentalNumberControl as NumberControl } from '@wordpress/components';
import { __ } from '@wordpress/i18n';

export default function TriggerCondition({ mode, value, onChange }) {
  if (mode === 'count') {
    return (
      <div className="linkblog-rc-row">
        <span>{__('Post when there are at least', 'linkblog')}</span>
        <NumberControl
          value={String(value.count)}
          min={1}
          onChange={v => onChange({ ...value, count: parseInt(v) || 1 })}
          style={{ width: '72px' }}
        />
        <span>{value.count !== 1 ? __('links', 'linkblog') : __('link', 'linkblog')}</span>
      </div>
    );
  }

  if (mode === 'age') {
    return (
      <div className="linkblog-rc-row">
        <span>{__('Post when oldest link is older than', 'linkblog')}</span>
        <NumberControl
          value={String(value.days ?? 1)}
          min={1}
          onChange={v => onChange({ ...value, days: parseInt(v) || 1 })}
          style={{ width: '72px' }}
        />
        <span>{(value.days ?? 1) !== 1 ? __('days', 'linkblog') : __('day', 'linkblog')}</span>
      </div>
    );
  }

  return null;
}
