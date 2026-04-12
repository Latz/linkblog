const FREQ = { daily: 'DAILY', weekly: 'WEEKLY', monthly: 'MONTHLY' }

export function buildRRule({ type, interval = 1, weekdays = [], monthDays = [1], nthWeek = null }) {
  const parts = [`FREQ=${FREQ[type] ?? 'DAILY'}`]

  if (interval !== 1) parts.push(`INTERVAL=${interval}`)

  if (type === 'weekly' && weekdays.length > 0) {
    const prefix = nthWeek ? String(nthWeek) : ''
    parts.push(`BYDAY=${weekdays.map(d => `${prefix}${d}`).join(',')}`)
  }

  if (type === 'monthly' && monthDays.length > 0) {
    const dayNums = monthDays.filter(e => e.type === 'day').map(e => e.value)
    const nthDays = monthDays.filter(e => e.type === 'nth').map(e => `${e.nth}${e.weekday}`)
    if (dayNums.length > 0) parts.push(`BYMONTHDAY=${dayNums.join(',')}`)
    if (nthDays.length > 0) parts.push(`BYDAY=${nthDays.join(',')}`)
  }

  return parts.join(';')
}

export function describeSchedule({ type, interval = 1, weekdays = [], monthDays = [1], nthWeek = null }) {
  const DAY_NAMES = { MO: 'Monday', TU: 'Tuesday', WE: 'Wednesday', TH: 'Thursday', FR: 'Friday', SA: 'Saturday', SU: 'Sunday' }
  const NTH = ['', 'first', 'second', 'third', 'fourth']

  if (type === 'daily') {
    return interval === 1 ? 'Every day' : `Every ${interval} days`
  }

  if (type === 'weekly') {
    const days = weekdays.length
      ? weekdays.map(d => DAY_NAMES[d]).join(', ')
      : 'selected days'
    return interval === 1 && !nthWeek
      ? `Every week on ${days}`
      : `Every ${interval} week${interval > 1 ? 's' : ''} on ${days}`
  }

  if (type === 'monthly') {
    const NTH_LABELS = ['', 'first', 'second', 'third', 'fourth']
    const parts = [
      ...monthDays.filter(e => e.type === 'day').map(e => ordinal(e.value)),
      ...monthDays.filter(e => e.type === 'nth').map(e => `${NTH_LABELS[e.nth]} ${DAY_NAMES[e.weekday]}`),
    ]
    const dayStr = parts.join(', ')
    return interval === 1
      ? `Every month on the ${dayStr}`
      : `Every ${interval} months on the ${dayStr}`
  }

  return ''
}

function ordinal(n) {
  const s = ['th', 'st', 'nd', 'rd']
  const v = n % 100
  return n + (s[(v - 20) % 10] || s[v] || s[0])
}
