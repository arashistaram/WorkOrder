"use strict";

/* ============================================================
ICONS (فقط آیکون‌های SVG — نه داده)
============================================================ */
const ICONS = {
    search:'<circle cx="11" cy="11" r="7"/><path d="m20 20-3.6-3.6"/>',
    bell:'<path d="M18 8a6 6 0 1 0-12 0c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.7 21a2 2 0 0 1-3.4 0"/>',
    plus:'<path d="M12 5v14M5 12h14"/>',
    chevronDown:'<path d="m6 9 6 6 6-6"/>',
    chevronRight:'<path d="m9 6 6 6-6 6"/>',
    chevronLeft:'<path d="m15 6-6 6 6 6"/>',
    more:'<circle cx="5" cy="12" r="1.4" fill="currentColor" stroke="none"/><circle cx="12" cy="12" r="1.4" fill="currentColor" stroke="none"/><circle cx="19" cy="12" r="1.4" fill="currentColor" stroke="none"/>',
    x:'<path d="M18 6 6 18M6 6l12 12"/>',
    check:'<path d="m4 12.5 5 5L20 6.5"/>',
    eye:'<path d="M2 12s4-7 10-7 10 7 10 7-4 7-10 7-10-7-10-7z"/><circle cx="12" cy="12" r="3"/>',
    edit:'<path d="M12 20h9"/><path d="M16.5 3.5a2.12 2.12 0 0 1 3 3L7 19l-4 1 1-4z"/>',
    copy:'<rect x="9" y="9" width="12" height="12" rx="2"/><path d="M5 15H4a1 1 0 0 1-1-1V4a1 1 0 0 1 1-1h10a1 1 0 0 1 1 1v1"/>',
    trash:'<path d="M4 7h16M9 7V5a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v2"/><path d="M6 7l1 13a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2l1-13"/>',
    userPlus:'<path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M19 8v6M22 11h-6"/>',
    user:'<path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/>',
    refresh:'<path d="M21 12a9 9 0 1 1-2.6-6.4"/><path d="M21 3v6h-6"/>',
    download:'<path d="M12 3v12"/><path d="m7 11 5 5 5-5"/><path d="M4 21h16"/>',
    paperclip:'<path d="M21 11.5 12.5 20a5 5 0 0 1-7-7l8-8a3.5 3.5 0 0 1 5 5l-8 8a2 2 0 0 1-3-3l7-7"/>',
    file:'<path d="M14 3H7a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V8z"/><path d="M14 3v5h5"/><path d="M9 13h6M9 17h4"/>',
    image:'<rect x="3" y="4" width="18" height="16" rx="2"/><circle cx="9" cy="10" r="1.8"/><path d="m21 15-4.5-4.5L7 20"/>',
    alert:'<path d="M10.3 3.9 1.9 18a2 2 0 0 0 1.7 3h16.8a2 2 0 0 0 1.7-3L13.7 3.9a2 2 0 0 0-3.4 0z"/><path d="M12 9v4.5M12 17h.01"/>',
    alertCircle:'<circle cx="12" cy="12" r="9"/><path d="M12 7.5v5M12 16h.01"/>',
    checkCircle:'<circle cx="12" cy="12" r="9"/><path d="m8.4 12.4 2.4 2.4 4.8-5"/>',
    clock:'<circle cx="12" cy="12" r="9"/><path d="M12 7.5V12l3 1.8"/>',
    calendar:'<rect x="3" y="5" width="18" height="16" rx="2"/><path d="M3 10h18M8 3v4M16 3v4"/>',
    inbox:'<path d="M22 12h-6l-2 3h-4l-2-3H2"/><path d="M5.5 5h13l3.5 7v6a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2v-6z"/>',
    arrowUp:'<path d="M12 19V5M6 11l6-6 6 6"/>',
    arrowDown:'<path d="M12 5v14M18 13l-6 6-6-6"/>',
    minus:'<path d="M5 12h14"/>',
    building:'<rect x="4" y="3" width="16" height="18" rx="1.5"/><path d="M9 7.5h.01M15 7.5h.01M9 11.5h.01M15 11.5h.01M9 15.5h.01M15 15.5h.01"/>',
    pin:'<path d="M20 10.5c0 6-8 11.5-8 11.5s-8-5.5-8-11.5a8 8 0 0 1 16 0z"/><circle cx="12" cy="10.5" r="3"/>',
    tag:'<path d="M20.5 13.5 12.5 21.5 3 12V3h9l8.5 8.5a1.4 1.4 0 0 1 0 2z"/><circle cx="7.5" cy="7.5" r="1.2"/>',
    box:'<path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/><path d="m3.3 7 8.7 5 8.7-5"/><path d="M12 22V12"/>',
    list:'<path d="M8 6h13M8 12h13M8 18h13M3.5 6h.01M3.5 12h.01M3.5 18h.01"/>',
    clockFast:'<path d="M12 3a9 9 0 1 0 9 9"/><path d="M12 7.5V12l3 1.8"/><path d="M18 3v5h5"/>',
    layers:'<path d="m12 2.5 9 5-9 5-9-5z"/><path d="m3 12 9 5 9-5"/><path d="m3 16.5 9 5 9-5"/>',
};

function icon(name, cls){
    const body = ICONS[name] || '';
    return '<svg class="icon ' + (cls || '') + '" viewBox="0 0 24 24" fill="none" stroke="currentColor" '
        + 'stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">' + body + '</svg>';
}

/* ============================================================
UTILITIES
============================================================ */
const $  = (sel, root) => (root || document).querySelector(sel);
const $$ = (sel, root) => Array.from((root || document).querySelectorAll(sel));

function esc(value){
    return String(value === null || value === undefined ? '' : value)
        .replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;')
        .replace(/"/g,'&quot;').replace(/'/g,'&#39;');
}

const MS_DAY = 86400000;
const TODAY = (() => { const d = new Date(); d.setHours(0,0,0,0); return d; })();

function addDays(date, n){ const d = new Date(date); d.setDate(d.getDate() + n); return d; }
function toISO(date){
    const y = date.getFullYear();
    const m = String(date.getMonth()+1).padStart(2,'0');
    const d = String(date.getDate()).padStart(2,'0');
    return y + '-' + m + '-' + d;
}
function parseISO(str){ return new Date(str + 'T00:00:00'); }

function toJalali(gy, gm, gd){
    const g_d_m = [0,31,59,90,120,151,181,212,243,273,304,334];
    let jy = (gy <= 1600) ? 0 : 979;
    gy -= (gy <= 1600) ? 621 : 1600;
    const gy2 = (gm > 2) ? (gy + 1) : gy;
    let days = (365*gy) + Math.floor((gy2+3)/4) - Math.floor((gy2+99)/100)
        + Math.floor((gy2+399)/400) - 80 + gd + g_d_m[gm-1];
    jy += 33 * Math.floor(days/12053);
    days %= 12053;
    jy += 4 * Math.floor(days/1461);
    days %= 1461;
    if(days > 365){
        jy += Math.floor((days-1)/365);
        days = (days-1) % 365;
    }
    const jm = (days < 186) ? 1 + Math.floor(days/31) : 7 + Math.floor((days-186)/30);
    const jd = 1 + ((days < 186) ? (days%31) : ((days-186)%30));
    return [jy, jm, jd];
}
const JALALI_MONTHS = ['فروردین','اردیبهشت','خرداد','تیر','مرداد','شهریور','مهر','آبان','آذر','دی','بهمن','اسفند'];
const JALALI_MONTHS_SHORT = ['فرو','ارد','خرد','تیر','مرد','شهر','مهر','آبا','آذر','دی','بهم','اسف'];

function fmtDate(str){
    if(!str) return '—';
    const d = parseISO(str);
    const [jy, jm, jd] = toJalali(d.getFullYear(), d.getMonth()+1, d.getDate());
    return jd + ' ' + JALALI_MONTHS[jm-1] + ' ' + jy;
}
function fmtDay(str){
    if(!str) return '—';
    const d = parseISO(str);
    const [jy, jm, jd] = toJalali(d.getFullYear(), d.getMonth()+1, d.getDate());
    return jd + ' ' + JALALI_MONTHS_SHORT[jm-1];
}
function daysUntil(str){
    return Math.round((parseISO(str) - TODAY) / MS_DAY);
}
function relativeTime(minutes){
    if(minutes < 1) return 'همین الان';
    if(minutes < 60) return minutes + ' دقیقه پیش';
    const hours = Math.round(minutes/60);
    if(hours < 24) return hours + ' ساعت پیش';
    const days = Math.round(hours/24);
    if(days < 30) return days + ' روز پیش';
    const months = Math.round(days/30);
    return months + ' ماه پیش';
}
function unique(arr){ return Array.from(new Set(arr)); }
function pluralize(n, word){ return n + ' ' + word; }

/* ============================================================
خواندن داده‌ها از DOM (تنها منبع داده — بدون JSON در JS)
============================================================ */
function readSeedData(){
    const root = document.getElementById('seed-data');
    if(!root) throw new Error('#seed-data not found');

    const getList = (sel) => Array.from(root.querySelectorAll(sel))
        .map(el => el.dataset.value);

    const currentUser = root.querySelector('#seed-current-user').dataset.value;
    const defaultChecklist = getList('#seed-default-checklist li');
    const statuses = getList('#seed-statuses li');
    const priorities = getList('#seed-priorities li');
    const customers = getList('#seed-customers li');
    const projects = getList('#seed-projects li');
    const locations = getList('#seed-locations li');
    const assets = getList('#seed-assets li');
    const categories = getList('#seed-categories li');

    const people = Array.from(root.querySelectorAll('#seed-people li')).map(li => ({
        name: li.dataset.name,
        initials: li.dataset.initials,
        tone: li.dataset.tone
    }));

    const orders = Array.from(root.querySelectorAll('.seed-order')).map(art => {
        const checklist = Array.from(art.querySelectorAll('.seed-checklist li')).map(li => ({
            label: li.dataset.label,
            done: li.dataset.done === 'true'
        }));
        const attachments = Array.from(art.querySelectorAll('.seed-attachments li')).map(li => ({
            name: li.dataset.name,
            size: li.dataset.size,
            kind: li.dataset.kind || 'file'
        }));
        const activity = Array.from(art.querySelectorAll('.seed-activity li')).map(li => ({
            user: li.dataset.user,
            action: li.dataset.action,
            meta: li.dataset.meta || undefined,
            minutes: Number(li.dataset.minutes) || 0
        }));
        const tags = (art.dataset.tags || '').split('|').map(s => s.trim()).filter(Boolean);
        return {
            id: art.dataset.id,
            title: art.querySelector('.seed-title').textContent.trim(),
            description: art.querySelector('.seed-description').textContent.trim(),
            customer: art.dataset.customer,
            project: art.dataset.project,
            assignee: art.dataset.assignee,
            priority: art.dataset.priority,
            status: art.dataset.status,
            dueIn: Number(art.dataset.dueIn) || 0,
            createdAgo: Number(art.dataset.createdAgo) || 0,
            location: art.dataset.location,
            asset: art.dataset.asset,
            category: art.dataset.category,
            estimatedHours: Number(art.dataset.estimatedHours) || 0,
            tags: tags,
            checklist: checklist,
            attachments: attachments,
            activity: activity
        };
    });

    return {
        currentUser, defaultChecklist, statuses, priorities, people,
        customers, projects, locations, assets, categories, orders
    };
}

const SEED = readSeedData();

const CURRENT_USER      = SEED.currentUser;
const STATUSES          = SEED.statuses.slice();
const PRIORITIES        = SEED.priorities.slice();
const PEOPLE            = SEED.people;
const DEFAULT_CHECKLIST = SEED.defaultChecklist.slice();

const STATUS_META = {
    'پیش‌نویس':    { badge:'badge--neutral', dot:'#98a1ad' },
    'باز':         { badge:'badge--info',    dot:'#1a6aa8' },
    'در حال انجام':{ badge:'badge--primary', dot:'#2b52d6' },
    'متوقف':       { badge:'badge--warning', dot:'#9a5b06' },
    'تکمیل‌شده':   { badge:'badge--success', dot:'#0f7a41' },
    'لغو شده':     { badge:'badge--neutral', dot:'#98a1ad' },
};
const PRIORITY_META = {
    'کم':     { badge:'badge--neutral' },
    'متوسط':  { badge:'badge--info' },
    'بالا':   { badge:'badge--warning' },
    'بحرانی': { badge:'badge--danger' },
};
const PRIORITY_ORDER = { 'بحرانی':4, 'بالا':3, 'متوسط':2, 'کم':1 };
const STATUS_ORDER   = { 'پیش‌نویس':1, 'باز':2, 'در حال انجام':3, 'متوقف':4, 'تکمیل‌شده':5, 'لغو شده':6 };

const UNASSIGNED = { name:'تخصیص‌نیافته', initials:'', tone:'empty' };

function personByName(name){
    if(!name || name === 'تخصیص‌نیافته' || name === 'Unassigned') return UNASSIGNED;
    return PEOPLE.find(p => p.name === name) || { name:name, initials:name.slice(0,2), tone:'slate' };
}

/* ============================================================
ساخت state از روی داده‌های DOM
============================================================ */
function buildOrders(){
    return SEED.orders.map(function(o){
        return {
            id: o.id,
            title: o.title,
            customer: o.customer,
            project: o.project,
            assignee: o.assignee,
            priority: o.priority,
            status: o.status,
            dueDate: toISO(addDays(TODAY, Number(o.dueIn) || 0)),
            createdAt: toISO(addDays(TODAY, -(Number(o.createdAgo) || 0))),
            description: o.description || '',
            location: o.location || '—',
            asset: o.asset || '—',
            category: o.category || '—',
            estimatedHours: o.estimatedHours || 0,
            tags: (o.tags || []).slice(),
            checklist: (o.checklist || []).map(c => ({ label:c.label, done:!!c.done })),
            attachments: (o.attachments || []).map(a => ({ name:a.name, size:a.size, kind:a.kind || 'file' })),
            activity: (o.activity || []).map(a => ({ user:a.user, action:a.action, meta:a.meta, minutes:a.minutes || 0 })),
        };
    });
}

function nextOrderId(){
    const max = state.orders.reduce(function(m, o){
        const n = parseInt(String(o.id).replace(/[^0-9]/g, ''), 10);
        return isNaN(n) ? m : Math.max(m, n);
    }, 0);
    return 'WO-' + (max + 1);
}

/* ============================================================
STATE
============================================================ */
const state = {
    orders: [],
    route: { name:'list', id:null },
    filters: { q:'', status:'all', priority:'all', assignee:'all', customer:'all', due:'all', view:'all' },
    sort: { key:'dueDate', dir:'asc' },
    page: 1,
    pageSize: 10,
    selected: new Set(),
    loading: false,
    error: null,
    forceEmpty: false,
    openMenuAnchor: null,
    pendingConfirm: null,
};

/* ============================================================
DERIVED DATA
============================================================ */
function isOverdue(order){
    return order.status !== 'تکمیل‌شده' && order.status !== 'لغو شده' && daysUntil(order.dueDate) < 0;
}
function isDueToday(order){
    return order.status !== 'تکمیل‌شده' && order.status !== 'لغو شده' && daysUntil(order.dueDate) === 0;
}
function isActive(order){
    return order.status !== 'تکمیل‌شده' && order.status !== 'لغو شده';
}
function dueClass(order){
    const d = daysUntil(order.dueDate);
    if(!isActive(order)) return '';
    if(d < 0) return 'date-overdue';
    if(d === 0) return 'date-today';
    return '';
}
function checklistProgress(order){
    const total = order.checklist.length || 1;
    const done = order.checklist.filter(c => c.done).length;
    return { done: done, total: order.checklist.length, pct: Math.round((done / total) * 100) };
}

function getFilteredOrders(){
    if(state.forceEmpty) return [];
    const f = state.filters;
    let rows = state.orders.slice();

    if(f.view === 'mine')      rows = rows.filter(o => o.assignee === CURRENT_USER);
    if(f.view === 'overdue')   rows = rows.filter(isOverdue);
    if(f.view === 'completed') rows = rows.filter(o => o.status === 'تکمیل‌شده');

    if(f.q){
        const q = f.q.trim().toLowerCase();
        if(q){
            rows = rows.filter(o =>
                (o.id + ' ' + o.title + ' ' + o.customer + ' ' + o.project + ' ' + o.assignee)
                    .toLowerCase().indexOf(q) !== -1
            );
        }
    }
    if(f.status !== 'all')   rows = rows.filter(o => o.status === f.status);
    if(f.priority !== 'all') rows = rows.filter(o => o.priority === f.priority);
    if(f.assignee !== 'all') rows = rows.filter(o => o.assignee === f.assignee);
    if(f.customer !== 'all') rows = rows.filter(o => o.customer === f.customer);
    if(f.due !== 'all'){
        rows = rows.filter(o => {
            const d = daysUntil(o.dueDate);
            if(f.due === 'overdue')  return isActive(o) && d < 0;
            if(f.due === 'today')    return isActive(o) && d === 0;
            if(f.due === 'week')     return isActive(o) && d >= 0 && d <= 7;
            if(f.due === 'month')    return isActive(o) && d >= 0 && d <= 30;
            return true;
        });
    }

    const key = state.sort.key;
    const dir = state.sort.dir === 'asc' ? 1 : -1;
    rows.sort((a, b) => {
        let av, bv;
        switch(key){
            case 'id':        av = a.id; bv = b.id; break;
            case 'title':     av = a.title; bv = b.title; break;
            case 'customer':  av = a.customer; bv = b.customer; break;
            case 'assignee':  av = a.assignee; bv = b.assignee; break;
            case 'priority':  av = PRIORITY_ORDER[a.priority]; bv = PRIORITY_ORDER[b.priority]; break;
            case 'status':    av = STATUS_ORDER[a.status]; bv = STATUS_ORDER[b.status]; break;
            case 'createdAt': av = a.createdAt; bv = b.createdAt; break;
            default:          av = a.dueDate; bv = b.dueDate;
        }
        if(av < bv) return -1 * dir;
        if(av > bv) return  1 * dir;
        return 0;
    });
    return rows;
}

function computeStats(){
    const orders = state.orders;
    const open = orders.filter(o => o.status === 'باز').length;
    const inProgress = orders.filter(o => o.status === 'در حال انجام').length;
    const dueToday = orders.filter(isDueToday).length;
    const overdue = orders.filter(isOverdue).length;
    const completed = orders.filter(o => o.status === 'تکمیل‌شده').length;
    const byStatus = {};
    STATUSES.forEach(s => { byStatus[s] = orders.filter(o => o.status === s).length; });
    const workload = PEOPLE.map(p => ({
        person: p,
        count: orders.filter(o => o.assignee === p.name && isActive(o)).length,
    })).sort((a,b) => b.count - a.count);
    const attention = orders
        .filter(o => isActive(o) && daysUntil(o.dueDate) <= 1)
        .sort((a,b) => a.dueDate < b.dueDate ? -1 : 1)
        .slice(0, 6);
    const feed = [];
    orders.forEach(o => {
        o.activity.slice(0, 2).forEach(a => feed.push({ order:o, activity:a }));
    });
    feed.sort((a,b) => a.activity.minutes - b.activity.minutes);
    return {
        open, inProgress, dueToday, overdue, completed, byStatus, workload,
        attention, feed: feed.slice(0, 6), total: orders.length,
    };
}

function hasActiveFilters(){
    const f = state.filters;
    return !!(f.q || f.status !== 'all' || f.priority !== 'all' ||
        f.assignee !== 'all' || f.customer !== 'all' || f.due !== 'all');
}

/* ============================================================
COMPONENT HELPERS
============================================================ */
function statusBadge(status){
    const meta = STATUS_META[status] || STATUS_META['پیش‌نویس'];
    return '<span class="badge ' + meta.badge + '"><span class="badge__dot"></span>' + esc(status) + '</span>';
}
function priorityBadge(priority){
    const meta = PRIORITY_META[priority] || PRIORITY_META['متوسط'];
    return '<span class="badge ' + meta.badge + '">' + esc(priority) + '</span>';
}
function avatar(person, size){
    const cls = 'avatar' + (size ? ' avatar--' + size : '') + ' avatar--' + person.tone;
    if(person.tone === 'empty'){
        return '<span class="' + cls + '" aria-hidden="true">' + icon('user','icon--sm') + '</span>';
    }
    return '<span class="' + cls + '" aria-hidden="true">' + esc(person.initials) + '</span>';
}
function personCell(name, size){
    const p = personByName(name);
    return '<span class="avatar-stack">' + avatar(p, size) +
        '<span class="name">' + esc(p.name) + '</span></span>';
}
function statusSwatch(status){
    const meta = STATUS_META[status] || STATUS_META['پیش‌نویس'];
    return '<span class="status-swatch" style="background:' + meta.dot + '"></span>';
}
function sparkline(values){
    const max = Math.max.apply(null, values) || 1;
    const bars = values.map((v, i) => {
        const h = Math.max(3, Math.round((v / max) * 19));
        const x = i * 7;
        const opacity = i === values.length - 1 ? 1 : 0.26;
        return '<rect x="' + x + '" y="' + (22 - h) + '" width="5" height="' + h +
            '" rx="1" fill="currentColor" opacity="' + opacity + '"/>';
    }).join('');
    return '<svg class="spark" viewBox="0 0 56 24" aria-hidden="true">' + bars + '</svg>';
}

/* ============================================================
DASHBOARD
============================================================ */
function kpiCard(opts){
    const deltaCls = opts.direction === 'up' ? 'delta--up'
        : opts.direction === 'down' ? 'delta--down' : 'delta--flat';
    const deltaIcon = opts.direction === 'up' ? 'arrowUp'
        : opts.direction === 'down' ? 'arrowDown' : 'minus';
    return '' +
        '<article class="kpi">' +
        '<div class="kpi-top">' +
        '<span class="kpi-label">' + esc(opts.label) + '</span>' +
        '<span class="kpi-icon">' + icon(opts.icon) + '</span>' +
        '</div>' +
        '<div class="kpi-value">' + esc(opts.value) + '</div>' +
        '<div class="kpi-foot">' +
        '<div class="kpi-meta">' +
        '<span class="delta ' + deltaCls + '">' + icon(deltaIcon, 'icon--sm') + esc(opts.delta) + '</span>' +
        '<span class="kpi-sub">' + esc(opts.sub) + '</span>' +
        '</div>' +
        sparkline(opts.spark) +
        '</div>' +
        '</article>';
}

function dashboardView(){
    const s = computeStats();

    const attentionRows = s.attention.length
        ? s.attention.map(o => {
            const p = personByName(o.assignee);
            const d = daysUntil(o.dueDate);
            const dueLabel = d < 0 ? pluralize(Math.abs(d), 'روز') + ' معوق'
                : d === 0 ? 'سررسید امروز'
                    : 'سررسید فردا';
            const dueTone = d < 0 ? 'badge--danger' : d === 0 ? 'badge--warning' : 'badge--neutral';
            return '' +
                '<div class="attention-row" data-action="open-wo" data-id="' + esc(o.id) + '">' +
                '<div class="attention-main">' +
                '<div class="attention-title">' + esc(o.title) + '</div>' +
                '<div class="attention-meta">' +
                '<span class="mono">' + esc(o.id) + '</span>' +
                '<span class="dot"></span><span>' + esc(o.customer) + '</span>' +
                '<span class="dot"></span><span>' + esc(p.name) + '</span>' +
                '</div>' +
                '</div>' +
                '<span class="badge ' + dueTone + '">' + esc(dueLabel) + '</span>' +
                statusBadge(o.status) +
                '</div>';
        }).join('')
        : '<div class="state-block" style="padding:40px 24px">' +
        '<p>در حال حاضر موردی نیازمند توجه نیست. همه دستورکارهای فعال طبق برنامه پیش می‌روند.</p>' +
        '</div>';

    const maxWorkload = Math.max.apply(null, s.workload.map(w => w.count)) || 1;
    const workloadRows = s.workload.map(w =>
        '<div class="workload-row">' +
        '<span class="avatar-stack">' + avatar(w.person) + '<span class="name">' + esc(w.person.name) + '</span></span>' +
        '<span class="workload-bar"><span style="width:' + Math.round((w.count / maxWorkload) * 100) + '%"></span></span>' +
        '<span class="workload-count">' + w.count + '</span>' +
        '</div>'
    ).join('');

    const maxStatus = Math.max.apply(null, STATUSES.map(st => s.byStatus[st])) || 1;
    const statusRows = STATUSES.map(st =>
        '<div class="status-row">' +
        statusBadge(st) +
        '<span class="status-bar"><span style="width:' + Math.round((s.byStatus[st] / maxStatus) * 100) + '%"></span></span>' +
        '<span class="status-count">' + s.byStatus[st] + '</span>' +
        '</div>'
    ).join('');

    const feedItems = s.feed.map(item => {
        const a = item.activity;
        const p = personByName(a.user);
        return '' +
            '<li class="tl-item">' +
            '<span class="tl-avatar">' + avatar(p, 'sm') + '</span>' +
            '<div class="tl-body">' +
            '<div class="tl-text"><strong>' + esc(p.name) + '</strong> ' + esc(a.action) +
            ' روی <span class="mono">' + esc(item.order.id) + '</span></div>' +
            (a.meta ? '<div class="tl-meta">' + esc(a.meta) + '</div>' : '') +
            '<div class="tl-time">' + esc(relativeTime(a.minutes)) + '</div>' +
            '</div>' +
            '</li>';
    }).join('');

    return '' +
        '<div class="page-head">' +
        '<div>' +
        '<h1 class="page-title">نمای کلی عملیات</h1>' +
        '<p class="page-sub">' + pluralize(s.total, 'دستورکار') + ' · ' +
        s.overdue + ' معوق · ' + s.dueToday + ' سررسید امروز</p>' +
        '</div>' +
        '<div class="page-head-actions">' +
        '<button class="btn" data-action="toast" data-toast="خروجی گزارش در صف قرار گرفت. پس از آماده شدن ایمیل دریافت خواهید کرد.">' +
        icon('download','icon--sm') + 'خروجی</button>' +
        '<button class="btn" data-action="nav" data-view="list">' + icon('list','icon--sm') + 'مشاهده همه دستورکارها</button>' +
        '</div>' +
        '</div>' +

        '<section class="kpi-grid" aria-label="شاخص‌های کلیدی">' +
        kpiCard({ label:'دستورکارهای باز', value:s.open, icon:'inbox',
            delta:'+12.5%', direction:'up', sub:'نسبت به ۳۰ روز گذشته',
            spark:[8,10,9,12,11,14,13,s.open] }) +
        kpiCard({ label:'در حال انجام', value:s.inProgress, icon:'clockFast',
            delta:'+4.1%', direction:'up', sub:'نسبت به ۳۰ روز گذشته',
            spark:[6,7,7,9,8,10,11,s.inProgress] }) +
        kpiCard({ label:'سررسید امروز', value:s.dueToday, icon:'calendar',
            delta:s.overdue + ' معوق', direction:s.overdue > 0 ? 'down' : 'flat', sub:'نیازمند زمان‌بندی',
            spark:[3,2,4,3,5,4,2,s.dueToday] }) +
        kpiCard({ label:'تکمیل‌شده این ماه', value:s.completed, icon:'checkCircle',
            delta:'+8.3%', direction:'up', sub:'نسبت به ۳۰ روز گذشته',
            spark:[9,11,10,12,13,12,14,s.completed] }) +
        '</section>' +

        '<div class="dash-grid">' +
        '<section class="card">' +
        '<header class="card-head">' +
        '<h2>نیازمند توجه</h2>' +
        '<span class="card-sub">معوق و سررسید تا ۲۴ ساعت آینده</span>' +
        '</header>' +
        '<div class="card-body card-body--flush">' + attentionRows + '</div>' +
        '</section>' +
        '<section class="card">' +
        '<header class="card-head">' +
        '<h2>بار کاری فعال</h2>' +
        '<span class="card-sub">موارد باز به تفکیک تکنسین</span>' +
        '</header>' +
        '<div class="workload">' + workloadRows + '</div>' +
        '</section>' +
        '</div>' +

        '<div class="dash-grid dash-grid--even">' +
        '<section class="card">' +
        '<header class="card-head">' +
        '<h2>فعالیت‌های اخیر</h2>' +
        '<span class="card-sub">در همه دستورکارها</span>' +
        '</header>' +
        '<ul class="timeline">' + feedItems + '</ul>' +
        '</section>' +
        '<section class="card">' +
        '<header class="card-head">' +
        '<h2>دستورکارها بر اساس وضعیت</h2>' +
        '<span class="card-sub">از ابتدا</span>' +
        '</header>' +
        '<div style="padding:8px 0 12px">' + statusRows + '</div>' +
        '</section>' +
        '</div>';
}

/* ============================================================
LIST VIEW
============================================================ */
function optionList(values, selected, allLabel){
    let html = '<option value="all">' + esc(allLabel) + '</option>';
    values.forEach(v => {
        html += '<option value="' + esc(v) + '"' + (v === selected ? ' selected' : '') + '>' + esc(v) + '</option>';
    });
    return html;
}

function listView(){
    const s = computeStats();
    const f = state.filters;
    const customerOptions = unique(state.orders.map(o => o.customer)).sort();
    const assigneeOptions = unique(state.orders.map(o => o.assignee)).sort();

    const tabs = [
        { key:'all',       label:'همه دستورکارها', count:state.orders.length },
        { key:'mine',      label:'تخصیصی به من',   count:state.orders.filter(o => o.assignee === CURRENT_USER).length },
        { key:'overdue',   label:'معوق',           count:s.overdue },
        { key:'completed', label:'تکمیل‌شده',      count:s.completed },
    ].map(t =>
        '<button class="tab' + (f.view === t.key ? ' is-active' : '') + '" data-action="set-view" data-view="' + t.key + '">' +
        esc(t.label) + '<span class="count">' + t.count + '</span>' +
        '</button>'
    ).join('');

    return '' +
        '<div class="page-head">' +
        '<div>' +
        '<h1 class="page-title">دستورکارها</h1>' +
        '<p class="page-sub">' + pluralize(state.orders.length, 'دستورکار') + ' · ' +
        s.overdue + ' معوق · ' + s.inProgress + ' در حال انجام</p>' +
        '</div>' +
        '<div class="page-head-actions">' +
        '<button class="btn" data-action="toast" data-toast="خروجی در صف قرار گرفت — ' + state.orders.length + ' دستورکار به‌صورت CSV ایمیل خواهد شد.">' +
        icon('download','icon--sm') + 'خروجی</button>' +
        '<button class="btn btn--primary" data-action="new-wo">' +
        icon('plus','icon--sm') + 'دستورکار جدید</button>' +
        '</div>' +
        '</div>' +

        '<div class="tabs" role="tablist">' + tabs + '</div>' +

        '<div class="toolbar">' +
        '<div class="toolbar-search">' +
        icon('search','icon--sm') +
        '<input type="search" id="list-search" placeholder="جستجو بر اساس شماره، عنوان، مشتری…" ' +
        'aria-label="جستجوی دستورکارها" value="' + esc(f.q) + '" autocomplete="off">' +
        '</div>' +
        '<div class="toolbar-filters">' +
        '<select class="select" data-filter="status" aria-label="فیلتر بر اساس وضعیت">' +
        optionList(STATUSES, f.status, 'همه وضعیت‌ها') + '</select>' +
        '<select class="select" data-filter="priority" aria-label="فیلتر بر اساس اولویت">' +
        optionList(PRIORITIES, f.priority, 'همه اولویت‌ها') + '</select>' +
        '<select class="select" data-filter="assignee" aria-label="فیلتر بر اساس مسئول">' +
        optionList(assigneeOptions, f.assignee, 'همه مسئولان') + '</select>' +
        '<select class="select" data-filter="customer" aria-label="فیلتر بر اساس مشتری">' +
        optionList(customerOptions, f.customer, 'همه مشتریان') + '</select>' +
        '<select class="select" data-filter="due" aria-label="فیلتر بر اساس سررسید">' +
        '<option value="all"' + (f.due === 'all' ? ' selected' : '') + '>هر سررسیدی</option>' +
        '<option value="overdue"' + (f.due === 'overdue' ? ' selected' : '') + '>معوق</option>' +
        '<option value="today"' + (f.due === 'today' ? ' selected' : '') + '>سررسید امروز</option>' +
        '<option value="week"' + (f.due === 'week' ? ' selected' : '') + '>سررسید این هفته</option>' +
        '<option value="month"' + (f.due === 'month' ? ' selected' : '') + '>سررسید این ماه</option>' +
        '</select>' +
        '</div>' +
        '<button class="btn btn--ghost btn--sm" data-action="clear-filters" id="clear-filters">' +
        icon('x','icon--sm') + 'پاک کردن</button>' +
        '</div>' +

        '<div id="table-region"></div>';
}

function renderTableRegion(){
    const region = $('#table-region');
    if(!region) return;

    if(state.loading){ region.innerHTML = skeletonTable(); return; }
    if(state.error){ region.innerHTML = errorState(); return; }

    const rows = getFilteredOrders();
    const total = rows.length;
    const totalPages = Math.max(1, Math.ceil(total / state.pageSize));
    if(state.page > totalPages) state.page = totalPages;
    const start = (state.page - 1) * state.pageSize;
    const pageRows = rows.slice(start, start + state.pageSize);

    const clearBtn = $('#clear-filters');
    if(clearBtn) clearBtn.disabled = !hasActiveFilters();

    const chips = [];
    const f = state.filters;
    const pushChip = (label, key, value) => {
        chips.push('<span class="chip">' + esc(label) + ': <b>' + esc(value) + '</b>' +
            '<button data-action="clear-one-filter" data-key="' + key + '" aria-label="حذف فیلتر">' +
            icon('x','icon--sm') + '</button></span>');
    };
    if(f.q) pushChip('جستجو', 'q', f.q);
    if(f.status !== 'all') pushChip('وضعیت', 'status', f.status);
    if(f.priority !== 'all') pushChip('اولویت', 'priority', f.priority);
    if(f.assignee !== 'all') pushChip('مسئول', 'assignee', f.assignee);
    if(f.customer !== 'all') pushChip('مشتری', 'customer', f.customer);
    if(f.due !== 'all') pushChip('سررسید', 'due', f.due === 'week' ? 'این هفته' : f.due === 'month' ? 'این ماه' : f.due === 'today' ? 'امروز' : f.due === 'overdue' ? 'معوق' : f.due);

    const chipsHtml = chips.length
        ? '<div class="chips">' + chips.join('') +
        '<button class="chip" style="padding:0 9px" data-action="clear-filters">پاک کردن همه</button></div>'
        : '';

    const selectedCount = state.selected.size;
    const bulkHtml = selectedCount
        ? '<div class="bulk-bar" role="status">' +
        '<span class="count-badge">' + selectedCount + '</span>' +
        '<span>انتخاب‌شده</span>' +
        '<span class="spacer"></span>' +
        '<button class="btn" data-action="bulk-assign">' + icon('userPlus','icon--sm') + 'تخصیص</button>' +
        '<button class="btn" data-action="bulk-status">' + icon('refresh','icon--sm') + 'تغییر وضعیت</button>' +
        '<button class="btn" data-action="bulk-delete">' + icon('trash','icon--sm') + 'حذف</button>' +
        '<button class="icon-btn" data-action="clear-selection" aria-label="پاک کردن انتخاب">' + icon('x') + '</button>' +
        '</div>'
        : '';

    if(total === 0){
        region.innerHTML = chipsHtml + emptyState();
        return;
    }

    const allOnPageSelected = pageRows.length > 0 && pageRows.every(o => state.selected.has(o.id));
    const someOnPageSelected = pageRows.some(o => state.selected.has(o.id));

    const bodyRows = pageRows.map(o => {
        const selected = state.selected.has(o.id);
        return '' +
            '<tr data-action="open-wo" data-id="' + esc(o.id) + '"' + (selected ? ' class="is-selected"' : '') + '>' +
            '<td class="cell-check">' +
            '<input type="checkbox" class="checkbox" data-action="toggle-select" data-id="' + esc(o.id) + '"' +
            (selected ? ' checked' : '') + ' aria-label="انتخاب ' + esc(o.id) + '">' +
            '</td>' +
            '<td class="cell-wo" data-label="شماره">' +
            '<button class="wo-link" data-action="open-wo" data-id="' + esc(o.id) + '">' + esc(o.id) + '</button>' +
            '</td>' +
            '<td class="cell-title" data-label="عنوان">' +
            '<span class="title-text">' + esc(o.title) + '</span>' +
            '</td>' +
            '<td class="cell-customer" data-label="مشتری">' +
            '<div class="cell-sub" style="color:var(--color-text-2)">' + esc(o.customer) + '</div>' +
            '</td>' +
            '<td class="cell-project" data-label="پروژه">' +
            '<div class="cell-sub">' + esc(o.project) + '</div>' +
            '</td>' +
            '<td class="cell-assignee" data-label="مسئول">' + personCell(o.assignee, 'sm') + '</td>' +
            '<td class="cell-priority" data-label="اولویت">' + priorityBadge(o.priority) + '</td>' +
            '<td class="cell-status" data-label="وضعیت">' + statusBadge(o.status) + '</td>' +
            '<td class="cell-due cell-date ' + dueClass(o) + '" data-label="سررسید">' + esc(fmtDate(o.dueDate)) + '</td>' +
            '<td class="cell-created cell-date" data-label="ایجاد">' + esc(fmtDay(o.createdAt)) + '</td>' +
            '<td class="cell-actions">' +
            '<button class="icon-btn icon-btn--sm" data-action="row-menu" data-id="' + esc(o.id) + '" ' +
            'aria-label="اقدامات برای ' + esc(o.id) + '" aria-haspopup="menu">' + icon('more') + '</button>' +
            '</td>' +
            '</tr>';
    }).join('');

    region.innerHTML = chipsHtml + bulkHtml + '' +
        '<div class="table-wrap">' +
        '<div class="table-scroll">' +
        '<table class="table">' +
        '<thead><tr>' +
        '<th class="cell-check">' +
        '<input type="checkbox" class="checkbox" id="select-all" data-action="select-all"' +
        (allOnPageSelected ? ' checked' : '') + ' aria-label="انتخاب همه دستورکارهای این صفحه">' +
        '</th>' +
        th('id','شماره') +
        th('title','عنوان') +
        th('customer','مشتری') +
        '<th class="cell-project">پروژه</th>' +
        th('assignee','مسئول') +
        th('priority','اولویت') +
        th('status','وضعیت') +
        th('dueDate','سررسید') +
        th('createdAt','ایجاد') +
        '<th class="cell-actions"><span class="sr-only">اقدامات</span></th>' +
        '</tr></thead>' +
        '<tbody>' + bodyRows + '</tbody>' +
        '</table>' +
        '</div>' +
        paginationHtml(total, start, pageRows.length, totalPages) +
        '</div>';

    const selectAll = $('#select-all');
    if(selectAll) selectAll.indeterminate = !allOnPageSelected && someOnPageSelected;
}

function th(key, label){
    const active = state.sort.key === key;
    const dir = state.sort.dir;
    const iconName = !active ? 'chevronDown' : (dir === 'asc' ? 'arrowUp' : 'arrowDown');
    return '<th><button class="th-sort' + (active ? ' is-sorted' : '') + '" data-action="sort" data-key="' + key + '" ' +
        'aria-label="مرتب‌سازی بر اساس ' + esc(label) + '">' + esc(label) + icon(iconName) + '</button></th>';
}

function paginationHtml(total, start, count, totalPages){
    const from = total === 0 ? 0 : start + 1;
    const to = start + count;
    let pageButtons = '';
    const pages = [];
    for(let i = 1; i <= totalPages; i++){
        if(i === 1 || i === totalPages || Math.abs(i - state.page) <= 1) pages.push(i);
        else if(pages[pages.length - 1] !== '…') pages.push('…');
    }
    pageButtons = pages.map(p => {
        if(p === '…') return '<span class="page-ellipsis">…</span>';
        return '<button class="page-btn' + (p === state.page ? ' is-active' : '') + '" data-action="page" data-page="' + p + '">' + p + '</button>';
    }).join('');

    return '' +
        '<div class="pagination">' +
        '<div class="pagination-info">نمایش <b>' + from + '–' + to + '</b> از <b>' + total + '</b> دستورکار</div>' +
        '<div class="pagination-controls">' +
        '<button class="icon-btn icon-btn--bordered icon-btn--sm" data-action="page" data-page="' + (state.page - 1) + '"' +
        (state.page <= 1 ? ' disabled' : '') + ' aria-label="صفحه قبل">' + icon('chevronRight') + '</button>' +
        pageButtons +
        '<button class="icon-btn icon-btn--bordered icon-btn--sm" data-action="page" data-page="' + (state.page + 1) + '"' +
        (state.page >= totalPages ? ' disabled' : '') + ' aria-label="صفحه بعد">' + icon('chevronLeft') + '</button>' +
        '</div>' +
        '</div>';
}

function skeletonTable(){
    let rows = '';
    for(let i = 0; i < 8; i++){
        rows += '<div class="skel-row">' +
            '<span class="skeleton" style="width:14px;height:14px;border-radius:3px"></span>' +
            '<span class="skeleton skel-bar" style="width:64px"></span>' +
            '<span class="skeleton skel-bar" style="width:180px"></span>' +
            '<span class="skeleton skel-bar" style="width:110px"></span>' +
            '<span class="skeleton skel-bar" style="width:90px"></span>' +
            '<span class="skeleton skel-bar" style="width:76px"></span>' +
            '<span class="skeleton skel-bar" style="width:70px"></span>' +
            '<span class="skeleton skel-bar" style="width:80px"></span>' +
            '</div>';
    }
    let head = '<div class="skeleton-head">';
    [14,64,180,110,90,76,70,80].forEach(w => {
        head += '<span class="skeleton skel-bar" style="width:' + w + 'px"></span>';
    });
    head += '</div>';
    return '<div class="table-wrap">' + head + rows + '</div>';
}

function emptyState(){
    return '' +
        '<div class="table-wrap">' +
        '<div class="state-block">' +
        '<span class="state-icon">' + icon('inbox') + '</span>' +
        '<h3>دستورکاری یافت نشد</h3>' +
        '<p>فیلترها را تغییر دهید یا یک دستورکار جدید ایجاد کنید.</p>' +
        '<div class="state-actions">' +
        '<button class="btn" data-action="clear-filters">پاک کردن فیلترها</button>' +
        '<button class="btn btn--primary" data-action="new-wo">' + icon('plus','icon--sm') + 'دستورکار جدید</button>' +
        '</div>' +
        '</div>' +
        '</div>';
}

function errorState(){
    return '' +
        '<div class="table-wrap">' +
        '<div class="state-block state-block--error">' +
        '<span class="state-icon">' + icon('alert') + '</span>' +
        '<h3>بارگذاری دستورکارها ناموفق بود</h3>' +
        '<p>خطایی در دریافت داده‌ها رخ داد. اتصال خود را بررسی کنید و دوباره تلاش کنید.</p>' +
        '<div class="state-actions">' +
        '<button class="btn btn--primary" data-action="retry">' + icon('refresh','icon--sm') + 'تلاش مجدد</button>' +
        '</div>' +
        '</div>' +
        '</div>';
}

/* ============================================================
DETAIL VIEW
============================================================ */
function detailView(id){
    const order = state.orders.find(o => o.id === id);
    if(!order){
        return '<div class="table-wrap"><div class="state-block">' +
            '<span class="state-icon">' + icon('alertCircle') + '</span>' +
            '<h3>دستورکار یافت نشد</h3>' +
            '<p>دستورکار <span class="mono">' + esc(id) + '</span> پیدا نشد. ممکن است حذف شده باشد.</p>' +
            '<div class="state-actions"><button class="btn" data-action="nav" data-view="list">بازگشت به دستورکارها</button></div>' +
            '</div></div>';
    }

    const p = personByName(order.assignee);
    const prog = checklistProgress(order);
    const createdBy = personByName('سارا چن');

    const checklistHtml = order.checklist.map((item, idx) =>
        '<li class="check-item' + (item.done ? ' is-done' : '') + '">' +
        '<label>' +
        '<input type="checkbox" class="checkbox" data-action="toggle-check" data-id="' + esc(order.id) + '" ' +
        'data-index="' + idx + '"' + (item.done ? ' checked' : '') + '>' +
        '<span class="check-label">' + esc(item.label) + '</span>' +
        '</label>' +
        (item.done ? '<span class="check-time">' + esc(relativeTime(30 + idx * 45)) + '</span>' : '') +
        '</li>'
    ).join('');

    const attachmentsHtml = order.attachments.length
        ? order.attachments.map(a =>
            '<div class="attach-row">' +
            '<span class="attach-icon">' + icon(a.kind === 'image' ? 'image' : 'file') + '</span>' +
            '<div class="attach-main">' +
            '<div class="attach-name">' + esc(a.name) + '</div>' +
            '<div class="attach-meta">' + esc(a.size) + ' · بارگذاری ' + esc(relativeTime(120)) + '</div>' +
            '</div>' +
            '<div class="attach-actions">' +
            '<button class="icon-btn icon-btn--sm" data-action="toast" data-toast="در حال باز کردن پیش‌نمایش ' + esc(a.name) + '…" aria-label="پیش‌نمایش">' + icon('eye') + '</button>' +
            '<button class="icon-btn icon-btn--sm" data-action="toast" data-toast="در حال دانلود ' + esc(a.name) + '…" aria-label="دانلود">' + icon('download') + '</button>' +
            '</div>' +
            '</div>'
        ).join('')
        : '<div class="state-block" style="padding:36px 24px">' +
        '<p>هنوز پیوستی وجود ندارد. فایل‌ها را اینجا رها کنید یا از دکمه زیر استفاده کنید.</p>' +
        '</div>';

    const activityHtml = order.activity.map(a => {
        const ap = personByName(a.user);
        return '' +
            '<li class="tl-item">' +
            '<span class="tl-avatar">' + avatar(ap, 'sm') + '</span>' +
            '<div class="tl-body">' +
            '<div class="tl-text"><strong>' + esc(ap.name) + '</strong> ' + esc(a.action) + '</div>' +
            (a.meta ? '<div class="tl-meta">' + esc(a.meta) + '</div>' : '') +
            '<div class="tl-time">' + esc(relativeTime(a.minutes)) + '</div>' +
            '</div>' +
            '</li>';
    }).join('');

    const tagsHtml = order.tags.length
        ? order.tags.map(t => '<span class="badge badge--neutral">' + esc(t) + '</span>').join('')
        : '<span style="color:var(--color-muted)">—</span>';

    return '' +
        '<button class="btn btn--ghost btn--sm" data-action="nav" data-view="list" style="margin-bottom:14px">' +
        icon('chevronRight','icon--sm') + 'بازگشت به دستورکارها</button>' +

        '<div class="detail-header">' +
        '<div style="min-width:0">' +
        '<div class="detail-eyebrow">' +
        '<span class="mono">' + esc(order.id) + '</span>' +
        '<span>·</span><span>' + esc(order.project) + '</span>' +
        '</div>' +
        '<h1 class="detail-title">' + esc(order.title) + '</h1>' +
        '<div class="detail-badges">' +
        statusBadge(order.status) +
        priorityBadge(order.priority) +
        '</div>' +
        '<div class="detail-meta">' +
        '<span class="meta-item">' + avatar(p,'sm') + '<strong>' + esc(p.name) + '</strong></span>' +
        '<span class="meta-item">' + icon('calendar') + 'سررسید <strong class="' + dueClass(order) + '">' + esc(fmtDate(order.dueDate)) + '</strong></span>' +
        '<span class="meta-item">' + icon('building') + esc(order.customer) + '</span>' +
        '<span class="meta-item">' + icon('pin') + esc(order.location) + '</span>' +
        '</div>' +
        '</div>' +
        '<div class="detail-actions">' +
        '<button class="btn" data-action="edit-wo" data-id="' + esc(order.id) + '">' + icon('edit','icon--sm') + 'ویرایش</button>' +
        '<button class="btn" data-action="assign-wo" data-id="' + esc(order.id) + '">' + icon('userPlus','icon--sm') + 'تخصیص</button>' +
        '<button class="btn btn--primary" data-action="set-status" data-id="' + esc(order.id) + '" data-status="تکمیل‌شده">' +
        icon('check','icon--sm') + 'علامت‌گذاری تکمیل</button>' +
        '<button class="icon-btn icon-btn--bordered" data-action="row-menu" data-id="' + esc(order.id) + '" aria-label="اقدامات بیشتر">' + icon('more') + '</button>' +
        '</div>' +
        '</div>' +

        '<div class="detail-grid">' +
        '<div class="detail-col">' +

        '<section class="card">' +
        '<header class="card-head"><h2>نمای کلی</h2></header>' +
        '<div class="card-body">' +
        '<p class="prose">' + esc(order.description) + '</p>' +
        '<div class="def-grid">' +
        '<div class="def"><span class="def-key">مشتری</span><span class="def-val">' + icon('building') + esc(order.customer) + '</span></div>' +
        '<div class="def"><span class="def-key">پروژه</span><span class="def-val">' + icon('layers') + esc(order.project) + '</span></div>' +
        '<div class="def"><span class="def-key">مکان</span><span class="def-val">' + icon('pin') + esc(order.location) + '</span></div>' +
        '<div class="def"><span class="def-key">دارایی</span><span class="def-val">' + icon('box') + '<span class="mono">' + esc(order.asset) + '</span></span></div>' +
        '<div class="def"><span class="def-key">دسته‌بندی</span><span class="def-val">' + icon('tag') + esc(order.category) + '</span></div>' +
        '<div class="def"><span class="def-key">ساعات تخمینی</span><span class="def-val">' + icon('clock') + esc(order.estimatedHours) + ' ساعت</span></div>' +
        '<div class="def" style="grid-column:1/-1"><span class="def-key">برچسب‌ها</span><span class="def-val">' + tagsHtml + '</span></div>' +
        '</div>' +
        '</div>' +
        '</section>' +

        '<section class="card">' +
        '<header class="card-head">' +
        '<h2>چک‌لیست</h2>' +
        '<span class="card-sub">' + prog.done + ' از ' + prog.total + ' تکمیل‌شده</span>' +
        '</header>' +
        '<ul class="checklist">' + checklistHtml + '</ul>' +
        '<div class="progress-wrap">' +
        '<div class="progress-head"><span>پیشرفت</span><b>' + prog.pct + '%</b></div>' +
        '<div class="progress" role="progressbar" aria-valuenow="' + prog.pct + '" aria-valuemin="0" aria-valuemax="100">' +
        '<div class="progress__bar" style="width:' + prog.pct + '%"></div>' +
        '</div>' +
        '</div>' +
        '</section>' +

        '<section class="card">' +
        '<header class="card-head">' +
        '<h2>پیوست‌ها</h2>' +
        '<button class="btn btn--sm" data-action="toast" data-toast="انتخابگر فایل در این نمونه در دسترس نیست.">' +
        icon('paperclip','icon--sm') + 'افزودن فایل</button>' +
        '</header>' +
        '<div class="card-body card-body--flush">' + attachmentsHtml + '</div>' +
        '</section>' +

        '<section class="card">' +
        '<header class="card-head">' +
        '<h2>فعالیت‌ها</h2>' +
        '<span class="card-sub">تاریخچه کامل</span>' +
        '</header>' +
        '<ul class="timeline">' + activityHtml + '</ul>' +
        '</section>' +

        '</div>' +

        '<aside class="detail-col">' +
        '<section class="card">' +
        '<header class="card-head"><h2>مشخصات</h2></header>' +
        '<div class="prop-list">' +
        '<div class="prop"><span class="prop-key">وضعیت</span><span class="prop-val">' + statusBadge(order.status) + '</span></div>' +
        '<div class="prop"><span class="prop-key">اولویت</span><span class="prop-val">' + priorityBadge(order.priority) + '</span></div>' +
        '<div class="prop"><span class="prop-key">مسئول</span><span class="prop-val">' +
        '<span class="avatar-stack">' + avatar(p,'sm') + '<span class="truncate">' + esc(p.name) + '</span></span></span></div>' +
        '<div class="prop"><span class="prop-key">سررسید</span><span class="prop-val ' + dueClass(order) + '">' + esc(fmtDate(order.dueDate)) + '</span></div>' +
        '<div class="prop"><span class="prop-key">تاریخ ایجاد</span><span class="prop-val">' + esc(fmtDate(order.createdAt)) + '</span></div>' +
        '<div class="prop"><span class="prop-key">ایجادکننده</span><span class="prop-val">' +
        '<span class="avatar-stack">' + avatar(createdBy,'sm') + '<span class="truncate">' + esc(createdBy.name) + '</span></span></span></div>' +
        '<div class="prop"><span class="prop-key">شماره دستورکار</span><span class="prop-val mono">' + esc(order.id) + '</span></div>' +
        '</div>' +
        '</section>' +

        '<section class="card">' +
        '<header class="card-head"><h2>اقدامات سریع</h2></header>' +
        '<div class="card-body" style="display:flex;flex-direction:column;gap:8px">' +
        '<button class="btn btn--block" data-action="assign-me" data-id="' + esc(order.id) + '">' +
        icon('userPlus','icon--sm') + 'تخصیص به من</button>' +
        '<button class="btn btn--block" data-action="set-status" data-id="' + esc(order.id) + '" data-status="متوقف">' +
        icon('clock','icon--sm') + 'متوقف کردن</button>' +
        '<button class="btn btn--block" data-action="duplicate-wo" data-id="' + esc(order.id) + '">' +
        icon('copy','icon--sm') + 'کپی دستورکار</button>' +
        '</div>' +
        '</section>' +
        '</aside>' +
        '</div>';
}

/* ============================================================
PLACEHOLDER
============================================================ */
function placeholderView(name){
    const titles = {
        customers:'مشتریان', projects:'پروژه‌ها', team:'تیم',
        assets:'دارایی‌ها', reports:'گزارش‌ها', settings:'تنظیمات',
    };
    const label = titles[name] || 'صفحه';
    return '' +
        '<div class="page-head"><div>' +
        '<h1 class="page-title">' + esc(label) + '</h1>' +
        '<p class="page-sub">این ماژول بخشی از نمونه فعلی نیست.</p>' +
        '</div></div>' +
        '<div class="table-wrap"><div class="state-block">' +
        '<span class="state-icon">' + icon('layers') + '</span>' +
        '<h3>' + esc(label) + ' هنوز ساخته نشده است</h3>' +
        '<p>این نمونه بر جریان کاری دستورکار تمرکز دارد. ناوبری متصل است تا پوسته، چیدمان و سیستم طراحی به‌صورت کامل ارزیابی شوند.</p>' +
        '<div class="state-actions">' +
        '<button class="btn btn--primary" data-action="nav" data-view="list">رفتن به دستورکارها</button>' +
        '</div>' +
        '</div></div>';
}

/* ============================================================
ROUTER
============================================================ */
function renderBreadcrumb(){
    const el = $('#breadcrumb');
    if(!el) return;
    const r = state.route;
    const parts = [];
    parts.push('<button data-action="nav" data-view="dashboard" class="crumb-parent">عملیات</button>');
    if(r.name === 'dashboard'){
        parts.push('<span class="sep">/</span><span class="current" aria-current="page">داشبورد</span>');
    } else if(r.name === 'list'){
        parts.push('<span class="sep">/</span><span class="current" aria-current="page">دستورکارها</span>');
    } else if(r.name === 'detail'){
        parts.push('<span class="sep">/</span>');
        parts.push('<button data-action="nav" data-view="list" class="crumb-parent">دستورکارها</button>');
        parts.push('<span class="sep">/</span><span class="current" aria-current="page">' + esc(r.id) + '</span>');
    } else {
        parts.push('<span class="sep">/</span><span class="current" aria-current="page">' + esc(state.route.name) + '</span>');
    }
    el.innerHTML = parts.join('');
}

function renderSidebarActive(){
    const activeKey = state.route.name === 'detail' ? 'list' : state.route.name;
    $$('.sidebar-nav .nav-item[data-nav]').forEach(btn => {
        const isActive = btn.dataset.nav === activeKey;
        btn.classList.toggle('is-active', isActive);
        if(isActive) btn.setAttribute('aria-current','page');
        else btn.removeAttribute('aria-current');
    });
    const count = $('#nav-wo-count');
    if(count) count.textContent = state.orders.length;
}

function render(){
    renderSidebarActive();
    renderBreadcrumb();
    const view = $('#view');
    if(state.route.name === 'dashboard'){
        view.innerHTML = dashboardView();
    } else if(state.route.name === 'list'){
        view.innerHTML = listView();
        attachListEvents();
        renderTableRegion();
    } else if(state.route.name === 'detail'){
        view.innerHTML = detailView(state.route.id);
    } else {
        view.innerHTML = placeholderView(state.route.name);
    }
}

function attachListEvents(){
    const search = $('#list-search');
    if(search){
        search.addEventListener('input', e => {
            state.filters.q = e.target.value;
            state.page = 1;
            renderTableRegion();
        });
    }
    $$('.toolbar-filters .select').forEach(sel => {
        sel.addEventListener('change', e => {
            state.filters[e.target.dataset.filter] = e.target.value;
            state.page = 1;
            renderTableRegion();
        });
    });
}

function navigate(name, id){
    state.route = { name: name, id: id || null };
    state.selected.clear();
    closeFloatingMenu();
    window.scrollTo({ top:0, behavior:'auto' });
    render();
}

/* ============================================================
FLOATING MENU
============================================================ */
function rowMenuHtml(id){
    const order = state.orders.find(o => o.id === id);
    if(!order) return '';
    const statusOptions = STATUSES.map(s =>
        '<button class="menu-item" role="menuitem" data-action="set-status" data-id="' + esc(id) + '" data-status="' + esc(s) + '">' +
        statusSwatch(s) + esc(s) +
        (s === order.status ? '<span style="margin-inline-start:auto">' + icon('check','icon--sm') + '</span>' : '') +
        '</button>'
    ).join('');
    return '' +
        '<button class="menu-item" role="menuitem" data-action="open-wo" data-id="' + esc(id) + '">' +
        icon('eye') + 'مشاهده جزئیات</button>' +
        '<button class="menu-item" role="menuitem" data-action="edit-wo" data-id="' + esc(id) + '">' +
        icon('edit') + 'ویرایش دستورکار</button>' +
        '<button class="menu-item" role="menuitem" data-action="duplicate-wo" data-id="' + esc(id) + '">' +
        icon('copy') + 'کپی</button>' +
        '<button class="menu-item" role="menuitem" data-action="assign-wo" data-id="' + esc(id) + '">' +
        icon('userPlus') + 'تخصیص به…</button>' +
        '<div class="menu-sep"></div>' +
        '<div class="menu-item menu-item--sub" role="menuitem" tabindex="0" aria-haspopup="menu">' +
        icon('refresh') + 'تغییر وضعیت' + icon('chevronLeft','menu-chevron') +
        '<div class="submenu" role="menu">' + statusOptions + '</div>' +
        '</div>' +
        '<div class="menu-sep"></div>' +
        '<button class="menu-item menu-item--danger" role="menuitem" data-action="delete-wo" data-id="' + esc(id) + '">' +
        icon('trash') + 'حذف</button>';
}

function openFloatingMenu(anchor, html){
    const menu = $('#floating-menu');
    if(!menu) return;
    menu.innerHTML = html;
    menu.classList.add('is-open');
    const rect = anchor.getBoundingClientRect();
    menu.style.visibility = 'hidden';
    menu.style.left = '0px';
    menu.style.top = '0px';
    const mw = menu.offsetWidth;
    const mh = menu.offsetHeight;
    let left = rect.left;
    let top = rect.bottom + 4;
    if(left + mw > window.innerWidth - 8){
        left = Math.max(8, window.innerWidth - mw - 8);
    }
    left = Math.max(8, left);
    if(top + mh > window.innerHeight - 8){
        top = Math.max(8, rect.top - mh - 4);
    }
    menu.style.left = left + 'px';
    menu.style.top = top + 'px';
    menu.style.visibility = '';
    state.openMenuAnchor = anchor;
}

function closeFloatingMenu(){
    const menu = $('#floating-menu');
    if(!menu) return;
    menu.classList.remove('is-open');
    state.openMenuAnchor = null;
}

/* ============================================================
TOASTS
============================================================ */
function showToast(message, opts){
    opts = opts || {};
    const type = opts.type || 'info';
    const root = $('#toast-root');
    const icons = { success:'checkCircle', error:'alert', warning:'alert', info:'alertCircle' };
    const el = document.createElement('div');
    el.className = 'toast toast--' + type;
    el.setAttribute('role', type === 'error' ? 'alert' : 'status');
    el.innerHTML =
        icon(icons[type] || 'alertCircle') +
        '<div class="toast-body">' +
        '<div class="toast-title">' + esc(message) + '</div>' +
        (opts.sub ? '<div class="toast-sub">' + esc(opts.sub) + '</div>' : '') +
        '</div>' +
        '<button class="toast-close" aria-label="بستن">' + icon('x','icon--sm') + '</button>';
    root.appendChild(el);
    const remove = () => {
        if(!el.parentNode) return;
        el.classList.add('is-leaving');
        setTimeout(() => el.remove(), 180);
    };
    el.querySelector('.toast-close').addEventListener('click', remove);
    setTimeout(remove, opts.duration || 4200);
}

/* ============================================================
MODALS
============================================================ */
function field(opts){
    const id = 'f-' + opts.name;
    const required = opts.required ? ' <span class="req" aria-hidden="true">*</span>' : '';
    const reqAttr = opts.required ? ' required aria-required="true"' : '';
    let control;
    if(opts.type === 'select'){
        control = '<select class="select" id="' + id + '" name="' + opts.name + '" style="max-width:none;width:100%"' + reqAttr + '>' +
            (opts.placeholder ? '<option value="">' + esc(opts.placeholder) + '</option>' : '') +
            (opts.options || []).map(o => {
                const value = typeof o === 'string' ? o : o.value;
                const label = typeof o === 'string' ? o : o.label;
                return '<option value="' + esc(value) + '"' + (value === opts.value ? ' selected' : '') + '>' + esc(label) + '</option>';
            }).join('') +
            '</select>';
    } else if(opts.type === 'textarea'){
        control = '<textarea class="textarea" id="' + id + '" name="' + opts.name + '" ' +
            'placeholder="' + esc(opts.placeholder || '') + '"' + reqAttr + '>' + esc(opts.value || '') + '</textarea>';
    } else {
        control = '<input class="input" type="' + (opts.type || 'text') + '" id="' + id + '" name="' + opts.name + '" ' +
            'value="' + esc(opts.value === undefined ? '' : opts.value) + '" ' +
            'placeholder="' + esc(opts.placeholder || '') + '"' + reqAttr +
            (opts.min !== undefined ? ' min="' + opts.min + '"' : '') + '>';
    }
    return '' +
        '<div class="field' + (opts.span === 2 ? ' span-2' : '') + '" data-field="' + opts.name + '">' +
        '<label class="field-label" for="' + id + '">' + esc(opts.label) + required + '</label>' +
        control +
        (opts.hint ? '<span class="field-hint">' + esc(opts.hint) + '</span>' : '') +
        '<span class="field-error" data-error-for="' + opts.name + '">' + icon('alertCircle','icon--sm') +
        '<span class="error-text"></span></span>' +
        '</div>';
}

function openWorkOrderModal(id){
    const order = id ? state.orders.find(o => o.id === id) : null;
    const isEdit = !!order;
    const customerOptions = unique(state.orders.map(o => o.customer)).sort();
    const projectOptions = unique(state.orders.map(o => o.project)).sort();
    const assigneeOptions = PEOPLE.map(p => p.name);

    const v = {
        title: order ? order.title : '',
        customer: order ? order.customer : '',
        project: order ? order.project : '',
        description: order ? order.description : '',
        priority: order ? order.priority : 'متوسط',
        status: order ? order.status : 'باز',
        assignee: order ? order.assignee : 'تخصیص‌نیافته',
        dueDate: order ? order.dueDate : toISO(addDays(TODAY, 7)),
        location: order ? order.location : '',
        asset: order ? order.asset : '',
        estimatedHours: order ? order.estimatedHours : '',
        tags: order ? order.tags.join('، ') : '',
    };

    const html = '' +
        '<div class="modal-backdrop" data-action="backdrop-close">' +
        '<div class="modal" role="dialog" aria-modal="true" aria-labelledby="modal-title">' +
        '<div class="modal-head">' +
        '<div>' +
        '<h2 id="modal-title">' + (isEdit ? 'ویرایش دستورکار' : 'ایجاد دستورکار جدید') + '</h2>' +
        '<p>' + (isEdit ? 'به‌روزرسانی جزئیات برای ' + esc(order.id) + '.'
            : 'دستورکارها یک واحد کار را در برابر یک مشتری و پروژه پیگیری می‌کنند.') + '</p>' +
        '</div>' +
        '<button class="icon-btn" data-action="close-modal" aria-label="بستن">' + icon('x') + '</button>' +
        '</div>' +
        '<form id="wo-form" novalidate>' +
        '<div class="modal-body">' +
        '<div class="form-grid">' +
        '<div class="form-section-label">جزئیات</div>' +
        field({ label:'عنوان', name:'title', value:v.title, required:true, span:2,
            placeholder:'مثلاً تعویض کمپرسور تهویه' }) +
        field({ label:'مشتری', name:'customer', type:'select', value:v.customer, required:true,
            options:customerOptions, placeholder:'انتخاب مشتری',
            hint:'حسابی که این کار به آن صورت‌حساب می‌شود.' }) +
        field({ label:'پروژه', name:'project', type:'select', value:v.project,
            options:projectOptions, placeholder:'انتخاب پروژه' }) +
        field({ label:'توضیحات', name:'description', type:'textarea', value:v.description, span:2,
            placeholder:'مشکل، دامنه کار و نیازهای دسترسی را توضیح دهید…' }) +
        '<div class="form-section-label">زمان‌بندی</div>' +
        field({ label:'اولویت', name:'priority', type:'select', value:v.priority, options:PRIORITIES }) +
        field({ label:'وضعیت', name:'status', type:'select', value:v.status, options:STATUSES }) +
        field({ label:'مسئول', name:'assignee', type:'select', value:v.assignee, options:assigneeOptions,
            hint:'برای قرارگیری در صف اعزام، «تخصیص‌نیافته» را انتخاب کنید.' }) +
        field({ label:'سررسید', name:'dueDate', type:'date', value:v.dueDate, required:true }) +
        '<div class="form-section-label">دارایی و مکان</div>' +
        field({ label:'مکان', name:'location', value:v.location, placeholder:'ساختمان A — پشت‌بام' }) +
        field({ label:'دارایی', name:'asset', value:v.asset, placeholder:'HVAC-2201' }) +
        field({ label:'ساعات تخمینی', name:'estimatedHours', type:'number', value:v.estimatedHours, min:0,
            placeholder:'4' }) +
        field({ label:'برچسب‌ها', name:'tags', value:v.tags, placeholder:'ایمنی، برنامه‌ریزی‌شده',
            hint:'برچسب‌ها را با کاما جدا کنید.' }) +
        '</div>' +
        '</div>' +
        '<div class="modal-foot">' +
        '<span class="spacer"></span>' +
        '<button type="button" class="btn" data-action="close-modal">انصراف</button>' +
        '<button type="submit" class="btn btn--primary">' +
        (isEdit ? 'ذخیره تغییرات' : 'ایجاد دستورکار') + '</button>' +
        '</div>' +
        '</form>' +
        '</div>' +
        '</div>';

    const root = $('#modal-root');
    root.innerHTML = html;
    document.body.classList.add('is-locked');

    const form = $('#wo-form');
    const firstInput = form.querySelector('input[name="title"]');
    if(firstInput) setTimeout(() => firstInput.focus(), 60);

    form.addEventListener('submit', e => {
        e.preventDefault();
        if(!validateForm(form)) return;

        const data = new FormData(form);
        if(isEdit){
            order.title = String(data.get('title')).trim();
            order.customer = data.get('customer');
            order.project = data.get('project') || 'پروژه بدون نام';
            order.description = String(data.get('description') || '').trim() || order.description;
            order.priority = data.get('priority');
            order.status = data.get('status');
            order.assignee = data.get('assignee');
            order.dueDate = data.get('dueDate');
            order.location = String(data.get('location') || '').trim() || '—';
            order.asset = String(data.get('asset') || '').trim() || '—';
            order.estimatedHours = Number(data.get('estimatedHours')) || order.estimatedHours;
            order.tags = String(data.get('tags') || '').split(/[،,]/).map(s => s.trim()).filter(Boolean);
            order.activity.unshift({ user: CURRENT_USER, action: 'این دستورکار را به‌روزرسانی کرد', minutes: 0 });
            closeModal();
            render();
            showToast('دستورکار به‌روزرسانی شد', { type:'success', sub: order.id + ' · ' + order.title });
        } else {
            const newOrder = {
                id: nextOrderId(),
                title: String(data.get('title')).trim(),
                customer: data.get('customer'),
                project: data.get('project') || 'پروژه بدون نام',
                assignee: data.get('assignee') || 'تخصیص‌نیافته',
                priority: data.get('priority'),
                status: data.get('status'),
                dueDate: data.get('dueDate'),
                createdAt: toISO(TODAY),
                description: String(data.get('description') || '').trim() ||
                    'هنوز توضیحاتی ثبت نشده است. زمینه را اضافه کنید تا تکنسین مسئول بداند روی سایت با چه چیزی روبرو می‌شود.',
                location: String(data.get('location') || '').trim() || '—',
                asset: String(data.get('asset') || '').trim() || '—',
                category: 'مکانیک',
                estimatedHours: Number(data.get('estimatedHours')) || 4,
                tags: String(data.get('tags') || '').split(/[،,]/).map(s => s.trim()).filter(Boolean),
                checklist: DEFAULT_CHECKLIST.map(label => ({ label: label, done: false })),
                attachments: [],
                activity: [{ user: CURRENT_USER, action:'این دستورکار را ایجاد کرد', minutes: 0 }],
            };
            state.orders.unshift(newOrder);
            closeModal();
            navigate('detail', newOrder.id);
            showToast('دستورکار ایجاد شد', { type:'success', sub:newOrder.id + ' · ' + newOrder.title });
        }
    });
}

function validateForm(form){
    let valid = true;
    const required = ['title','customer','dueDate'];
    required.forEach(name => {
        const fieldEl = form.querySelector('[data-field="' + name + '"]');
        const input = form.querySelector('[name="' + name + '"]');
        const errorText = fieldEl.querySelector('.error-text');
        const value = input ? input.value.trim() : '';
        if(!value){
            valid = false;
            fieldEl.classList.add('has-error');
            input.setAttribute('aria-invalid','true');
            errorText.textContent = name === 'title' ? 'وارد کردن عنوان الزامی است.'
                : name === 'customer' ? 'مشتری را انتخاب کنید.'
                    : 'تاریخ سررسید را انتخاب کنید.';
        } else {
            fieldEl.classList.remove('has-error');
            input.removeAttribute('aria-invalid');
            errorText.textContent = '';
        }
    });
    if(!valid){
        const firstError = form.querySelector('.field.has-error .input, .field.has-error .select');
        if(firstError) firstError.focus();
    }
    return valid;
}

function openAssignModal(id){
    const order = state.orders.find(o => o.id === id);
    if(!order) return;
    const html = '' +
        '<div class="modal-backdrop" data-action="backdrop-close">' +
        '<div class="modal modal--sm" role="dialog" aria-modal="true" aria-labelledby="assign-title">' +
        '<div class="modal-head">' +
        '<div>' +
        '<h2 id="assign-title">تخصیص دستورکار</h2>' +
        '<p>' + esc(order.id) + ' · ' + esc(order.title) + '</p>' +
        '</div>' +
        '<button class="icon-btn" data-action="close-modal" aria-label="بستن">' + icon('x') + '</button>' +
        '</div>' +
        '<div class="modal-body" style="max-height:none">' +
        '<div class="radio-list">' +
        PEOPLE.map(p =>
            '<label class="radio-item' + (p.name === order.assignee ? ' is-checked' : '') + '">' +
            '<input type="radio" name="assignee" value="' + esc(p.name) + '"' +
            (p.name === order.assignee ? ' checked' : '') + '>' +
            avatar(p, 'sm') + '<span>' + esc(p.name) + '</span>' +
            '</label>'
        ).join('') +
        '</div>' +
        '</div>' +
        '<div class="modal-foot">' +
        '<button class="btn" data-action="close-modal">انصراف</button>' +
        '<button class="btn btn--primary" data-action="confirm-assign" data-id="' + esc(id) + '">تخصیص</button>' +
        '</div>' +
        '</div>' +
        '</div>';
    $('#modal-root').innerHTML = html;
    document.body.classList.add('is-locked');
}

function openConfirmModal(opts){
    const html = '' +
        '<div class="modal-backdrop" data-action="backdrop-close">' +
        '<div class="modal modal--sm" role="dialog" aria-modal="true" aria-labelledby="confirm-title">' +
        '<div class="modal-head">' +
        '<div>' +
        '<h2 id="confirm-title">' + esc(opts.title) + '</h2>' +
        '<p>' + esc(opts.message) + '</p>' +
        '</div>' +
        '</div>' +
        '<div class="modal-foot">' +
        '<button class="btn" data-action="close-modal">انصراف</button>' +
        '<button class="btn ' + (opts.danger ? 'btn--danger' : 'btn--primary') + '" data-action="confirm-action">' +
        esc(opts.confirmLabel || 'تأیید') + '</button>' +
        '</div>' +
        '</div>' +
        '</div>';
    state.pendingConfirm = opts.onConfirm;
    $('#modal-root').innerHTML = html;
    document.body.classList.add('is-locked');
}

function closeModal(){
    $('#modal-root').innerHTML = '';
    document.body.classList.remove('is-locked');
    state.pendingConfirm = null;
}

/* ============================================================
ACTIONS
============================================================ */
function updateOrderStatus(id, status){
    const order = state.orders.find(o => o.id === id);
    if(!order) return;
    if(order.status === status) return;
    order.status = status;
    order.activity.unshift({ user: CURRENT_USER, action:'وضعیت را به «' + status + '» تغییر داد', minutes: 0 });
    if(status === 'تکمیل‌شده'){
        order.checklist.forEach(c => { c.done = true; });
    }
    render();
    showToast('وضعیت به «' + status + '» تغییر کرد', { type:'success', sub:order.id + ' · ' + order.title });
}

function deleteOrder(id){
    const order = state.orders.find(o => o.id === id);
    if(!order) return;
    openConfirmModal({
        title: 'حذف دستورکار؟',
        message: order.id + ' — «' + order.title + '» برای همیشه حذف خواهد شد. این عمل قابل بازگشت نیست.',
        confirmLabel: 'حذف',
        danger: true,
        onConfirm: () => {
            state.orders = state.orders.filter(o => o.id !== id);
            state.selected.delete(id);
            closeModal();
            if(state.route.name === 'detail' && state.route.id === id) navigate('list');
            else render();
            showToast('دستورکار حذف شد', { type:'success', sub:id });
        },
    });
}

function duplicateOrder(id){
    const order = state.orders.find(o => o.id === id);
    if(!order) return;
    const copy = JSON.parse(JSON.stringify(order));
    copy.id = nextOrderId();
    copy.title = order.title + ' (کپی)';
    copy.status = 'پیش‌نویس';
    copy.createdAt = toISO(TODAY);
    copy.activity = [{ user: CURRENT_USER, action:'کپی گرفت از ' + order.id, minutes: 0 }];
    state.orders.unshift(copy);
    render();
    showToast('دستورکار کپی شد', { type:'success', sub:copy.id + ' · ' + copy.title });
}

function handleAction(action, el, event){
    if(!action) return;
    switch(action){
        case 'toggle-sidebar': {
            const sidebar = $('#sidebar');
            const scrim = $('#sidebar-scrim');
            const isOpen = sidebar.classList.toggle('is-open');
            scrim.classList.toggle('is-open', isOpen);
            break;
        }
        case 'nav': navigate(el.dataset.view); break;
        case 'open-wo': navigate('detail', el.dataset.id); break;
        case 'set-view':
            state.filters.view = el.dataset.view;
            state.page = 1;
            state.selected.clear();
            render();
            break;
        case 'new-wo': openWorkOrderModal(null); break;
        case 'edit-wo': openWorkOrderModal(el.dataset.id); break;
        case 'row-menu':
            if(state.openMenuAnchor === el){ closeFloatingMenu(); break; }
            openFloatingMenu(el, rowMenuHtml(el.dataset.id));
            break;
        case 'toggle-select': {
            const id = el.dataset.id;
            if(state.selected.has(id)) state.selected.delete(id);
            else state.selected.add(id);
            renderTableRegion();
            break;
        }
        case 'select-all': {
            const rows = getFilteredOrders();
            const start = (state.page - 1) * state.pageSize;
            const pageRows = rows.slice(start, start + state.pageSize);
            const allSelected = pageRows.every(o => state.selected.has(o.id));
            pageRows.forEach(o => {
                if(allSelected) state.selected.delete(o.id);
                else state.selected.add(o.id);
            });
            renderTableRegion();
            break;
        }
        case 'clear-selection':
            state.selected.clear();
            renderTableRegion();
            break;
        case 'sort': {
            const key = el.dataset.key;
            if(state.sort.key === key){
                state.sort.dir = state.sort.dir === 'asc' ? 'desc' : 'asc';
            } else {
                state.sort.key = key;
                state.sort.dir = 'asc';
            }
            renderTableRegion();
            break;
        }
        case 'page': {
            const page = Number(el.dataset.page);
            if(!page || page < 1) break;
            state.page = page;
            state.selected.clear();
            renderTableRegion();
            const wrap = $('.table-wrap');
            if(wrap) wrap.scrollIntoView({ behavior:'smooth', block:'nearest' });
            break;
        }
        case 'clear-filters':
            state.filters = { q:'', status:'all', priority:'all', assignee:'all', customer:'all', due:'all', view: state.filters.view };
            state.page = 1;
            state.forceEmpty = false;
            state.error = null;
            if(state.route.name !== 'list') navigate('list');
            else render();
            break;
        case 'clear-one-filter': {
            const key = el.dataset.key;
            state.filters[key] = key === 'q' ? '' : 'all';
            state.page = 1;
            render();
            break;
        }
        case 'toggle-check': {
            const order = state.orders.find(o => o.id === el.dataset.id);
            if(!order) break;
            const idx = Number(el.dataset.index);
            order.checklist[idx].done = el.checked;
            const prog = checklistProgress(order);
            if(prog.done === prog.total && order.status === 'در حال انجام'){
                order.status = 'تکمیل‌شده';
                order.activity.unshift({ user: CURRENT_USER, action:'همه آیتم‌های چک‌لیست را تکمیل کرد', minutes: 0 });
                showToast('دستورکار تکمیل شد', { type:'success', sub:order.id });
            }
            render();
            break;
        }
        case 'set-status': updateOrderStatus(el.dataset.id, el.dataset.status); break;
        case 'assign-wo': openAssignModal(el.dataset.id); break;
        case 'assign-me': {
            const order = state.orders.find(o => o.id === el.dataset.id);
            if(!order) break;
            order.assignee = CURRENT_USER;
            order.activity.unshift({ user:'سیستم', action:'دستورکار به ' + CURRENT_USER + ' تخصیص یافت', minutes: 0 });
            render();
            showToast('به شما تخصیص یافت', { type:'success', sub:order.id });
            break;
        }
        case 'confirm-assign': {
            const id = el.dataset.id;
            const selected = document.querySelector('input[name="assignee"]:checked');
            const order = state.orders.find(o => o.id === id);
            if(order && selected){
                order.assignee = selected.value;
                order.activity.unshift({ user:'سیستم', action:'دستورکار به ' + selected.value + ' تخصیص یافت', minutes: 0 });
                closeModal();
                render();
                showToast('دستورکار تخصیص یافت', { type:'success', sub:id + ' به ' + selected.value });
            } else {
                closeModal();
            }
            break;
        }
        case 'duplicate-wo': duplicateOrder(el.dataset.id); break;
        case 'delete-wo': deleteOrder(el.dataset.id); break;
        case 'bulk-assign': {
            const count = state.selected.size;
            showToast('تخصیص گروهی', { type:'info', sub:count + ' دستورکار آماده تخصیص مجدد. برای ذخیره‌سازی به API متصل شوید.' });
            break;
        }
        case 'bulk-status': {
            const ids = Array.from(state.selected);
            ids.forEach(id => {
                const order = state.orders.find(o => o.id === id);
                if(order && order.status !== 'تکمیل‌شده'){
                    order.status = 'تکمیل‌شده';
                    order.checklist.forEach(c => { c.done = true; });
                }
            });
            state.selected.clear();
            render();
            showToast(ids.length + ' دستورکار به‌عنوان تکمیل‌شده علامت‌گذاری شد', { type:'success' });
            break;
        }
        case 'bulk-delete': {
            const ids = Array.from(state.selected);
            openConfirmModal({
                title: 'حذف ' + ids.length + ' دستورکار؟',
                message: 'دستورکارهای انتخاب‌شده برای همیشه حذف خواهند شد. این عمل قابل بازگشت نیست.',
                confirmLabel: 'حذف ' + ids.length,
                danger: true,
                onConfirm: () => {
                    state.orders = state.orders.filter(o => !state.selected.has(o.id));
                    state.selected.clear();
                    closeModal();
                    render();
                    showToast(ids.length + ' دستورکار حذف شد', { type:'success' });
                },
            });
            break;
        }
        case 'close-modal': closeModal(); break;
        case 'backdrop-close': if(event.target === el) closeModal(); break;
        case 'confirm-action': {
            const fn = state.pendingConfirm;
            state.pendingConfirm = null;
            if(typeof fn === 'function') fn();
            break;
        }
        case 'retry':
            state.error = null;
            state.loading = true;
            render();
            setTimeout(() => {
                state.loading = false;
                renderTableRegion();
                showToast('داده‌ها بارگذاری مجدد شد', { type:'success' });
            }, 900);
            break;
        case 'refresh': {
            if(state.route.name !== 'list'){
                showToast('در حال بارگذاری…', { type:'info' });
                setTimeout(() => showToast('همه چیز به‌روز است', { type:'success' }), 800);
                break;
            }
            state.error = null;
            state.loading = true;
            renderTableRegion();
            setTimeout(() => {
                state.loading = false;
                renderTableRegion();
                showToast('داده‌ها بارگذاری مجدد شد', { type:'success' });
            }, 900);
            break;
        }
        case 'sim-loading':
            state.route = { name:'list', id:null };
            state.error = null;
            state.forceEmpty = false;
            state.loading = true;
            render();
            setTimeout(() => {
                state.loading = false;
                renderTableRegion();
            }, 1800);
            break;
        case 'sim-error':
            state.route = { name:'list', id:null };
            state.loading = false;
            state.forceEmpty = false;
            state.error = 'درخواست ناموفق';
            render();
            break;
        case 'sim-empty':
            state.route = { name:'list', id:null };
            state.loading = false;
            state.error = null;
            state.forceEmpty = true;
            state.page = 1;
            render();
            break;
        case 'sim-reset':
            state.loading = false;
            state.error = null;
            state.forceEmpty = false;
            state.filters = { q:'', status:'all', priority:'all', assignee:'all', customer:'all', due:'all', view:'all' };
            state.page = 1;
            navigate('list');
            showToast('حالت پیش‌نمایش بازنشانی شد', { type:'info' });
            break;
        case 'toast':
            showToast(el.dataset.toast || 'انجام شد', { type:'info' });
            break;
        default: break;
    }
}

/* ============================================================
GLOBAL EVENTS
============================================================ */
document.addEventListener('click', e => {
    const menuEl = $('#floating-menu');
    const insideMenu = menuEl.contains(e.target);
    const el = e.target.closest('[data-action]');
    const action = el ? el.dataset.action : null;
    if(action === 'row-menu'){
        e.preventDefault();
        e.stopPropagation();
        if(state.openMenuAnchor === el){ closeFloatingMenu(); return; }
        openFloatingMenu(el, rowMenuHtml(el.dataset.id));
        return;
    }
    if(!insideMenu) closeFloatingMenu();
    if(action) handleAction(action, el, e);
    else if(insideMenu) closeFloatingMenu();
    if(insideMenu) closeFloatingMenu();
});

document.addEventListener('change', e => {
    if(e.target.name === 'assignee' && e.target.type === 'radio'){
        $$('.radio-item').forEach(item => {
            const input = item.querySelector('input');
            item.classList.toggle('is-checked', input && input.checked);
        });
    }
});

document.addEventListener('keydown', e => {
    if(e.key === 'Escape'){
        if($('#modal-root').innerHTML){
            closeModal();
        } else {
            closeFloatingMenu();
            const sidebar = $('#sidebar');
            if(sidebar.classList.contains('is-open')){
                sidebar.classList.remove('is-open');
                $('#sidebar-scrim').classList.remove('is-open');
            }
        }
    }
    if(e.key === 'Tab' && $('#modal-root').innerHTML){
        const modal = $('#modal-root .modal');
        if(!modal) return;
        const focusables = $$(
            'a[href], button:not([disabled]), textarea, input, select, [tabindex]:not([tabindex="-1"])',
            modal
        ).filter(el => el.offsetParent !== null);
        if(!focusables.length) return;
        const first = focusables[0];
        const last = focusables[focusables.length - 1];
        if(e.shiftKey && document.activeElement === first){
            e.preventDefault(); last.focus();
        } else if(!e.shiftKey && document.activeElement === last){
            e.preventDefault(); first.focus();
        }
    }
    if((e.metaKey || e.ctrlKey) && e.key.toLowerCase() === 'k'){
        e.preventDefault();
        const input = $('#global-search');
        if(input) input.focus();
    }
});

const globalSearch = $('#global-search');
if(globalSearch){
    let debounce;
    globalSearch.addEventListener('input', e => {
        clearTimeout(debounce);
        const value = e.target.value;
        debounce = setTimeout(() => {
            state.filters.q = value;
            state.page = 1;
            state.forceEmpty = false;
            state.error = null;
            if(state.route.name !== 'list'){
                state.route = { name:'list', id:null };
                render();
            } else {
                const listSearch = $('#list-search');
                if(listSearch) listSearch.value = value;
                renderTableRegion();
            }
        }, 180);
    });
}

window.addEventListener('resize', closeFloatingMenu);
window.addEventListener('scroll', closeFloatingMenu, true);

/* ============================================================
INIT
============================================================ */
function init(){
    state.orders = buildOrders();
    render();
}

init();
