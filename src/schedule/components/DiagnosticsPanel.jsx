import { useState, useEffect, useCallback } from '@wordpress/element';
import apiFetch from '@wordpress/api-fetch';
import { Button } from '@wordpress/components';
import { __, sprintf } from '@wordpress/i18n';

/**
 * Formats a UTC Unix timestamp as a human-readable local datetime string.
 *
 * @param {number} ts - Unix timestamp (seconds).
 * @returns {string}
 */
function fmtTs(ts) {
  return new Date(ts * 1000).toLocaleString(undefined, {
    dateStyle: 'medium',
    timeStyle: 'short',
  });
}

/**
 * Sidebar panel showing server-side schedule diagnostics: next scheduled run,
 * last run record, and WP-Cron status.
 *
 * @returns {JSX.Element}
 */
export default function DiagnosticsPanel() {
  const [data, setData]       = useState(null);
  const [loading, setLoading] = useState(true);

  const fetch = useCallback(() => {
    setLoading(true);
    apiFetch({ path: '/linkdigest/v1/schedule/diagnostics' })
      .then(d => { setData(d); setLoading(false); })
      .catch(() => setLoading(false));
  }, []);

  useEffect(fetch, [fetch]);

  const lastRun = data?.last_run;
  const statusBadgeClass = lastRun?.status === 'success'
    ? 'linkdigest-diag-badge linkdigest-diag-badge--success'
    : 'linkdigest-diag-badge linkdigest-diag-badge--neutral';

  return (
    <div className="postbox linkdigest-diagnostics">
      <div className="linkdigest-next-heading">
        {__('Diagnostics', 'linkdigest')}
      </div>
      <div className="inside linkdigest-next-schedules-inside">
        {loading && <p className="description">{__('Loading…', 'linkdigest')}</p>}

        {!loading && data && (
          <dl className="linkdigest-diag-list">
            <div className="linkdigest-diag-row">
              <dt>{__('Next run', 'linkdigest')}</dt>
              <dd>
                {data.next_scheduled
                  ? fmtTs(data.next_scheduled)
                  : <em>{__('Not scheduled', 'linkdigest')}</em>}
              </dd>
            </div>

            <div className="linkdigest-diag-row">
              <dt>{__('Last run', 'linkdigest')}</dt>
              <dd>
                {lastRun ? (
                  <>
                    <span className={statusBadgeClass}>{lastRun.status}</span>
                    {' '}
                    {fmtTs(lastRun.ts)}
                    {lastRun.link_count > 0 && (
                      <span className="linkdigest-diag-meta">
                        {' · '}
                        {lastRun.post_id ? (
                          <a
                            href={`/wp-admin/post.php?post=${lastRun.post_id}&action=edit`}
                            target="_blank"
                            rel="noreferrer"
                          >
                            {/* translators: %d: number of links */}
                            {sprintf(__('%d links', 'linkdigest'), lastRun.link_count)}
                          </a>
                        ) : (
                          /* translators: %d: number of links */
                          sprintf(__('%d links', 'linkdigest'), lastRun.link_count)
                        )}
                      </span>
                    )}
                  </>
                ) : (
                  <em>{__('No runs yet', 'linkdigest')}</em>
                )}
              </dd>
            </div>

            <div className="linkdigest-diag-row">
              <dt>{__('WP-Cron', 'linkdigest')}</dt>
              <dd>
                {data.wp_cron_disabled
                  ? <span className="linkdigest-diag-badge linkdigest-diag-badge--warn">{__('Disabled', 'linkdigest')}</span>
                  : <span className="linkdigest-diag-badge linkdigest-diag-badge--success">{__('Active', 'linkdigest')}</span>}
              </dd>
            </div>
          </dl>
        )}

        {!loading && (
          <Button
            variant="link"
            size="compact"
            onClick={fetch}
            className="linkdigest-diag-refresh"
          >
            {__('Refresh', 'linkdigest')}
          </Button>
        )}
      </div>
    </div>
  );
}
