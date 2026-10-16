import { Head, Link, router } from '@inertiajs/react';
import { Search } from 'lucide-react';
import { useState, type FormEvent } from 'react';
import AdminLayout from '@/layouts/AdminLayout';
import { formatDate, label } from '@/lib/utils';
import type { Lead, Paginated } from '@/types';
import { Pagination } from '@/components/Pagination';

export default function Leads({ leads, services, statuses, filters }: { leads: Paginated<Lead>; services: { id: number; title: string }[]; statuses: string[]; filters: Record<string, string> }) {
    const [q, setQ] = useState(filters.q || ''); const [status, setStatus] = useState(filters.status || ''); const [service, setService] = useState(filters.service_id || '');
    const submit = (e: FormEvent) => { e.preventDefault(); router.get('/admin/leads', { q, status, service_id: service }, { preserveState: true }); };
    return <AdminLayout title="Leads"><Head title="Lead management" /><div className="toolbar"><form onSubmit={submit}><Search /><input value={q} onChange={(e) => setQ(e.target.value)} placeholder="Name, email or company" /><select value={status} onChange={(e) => setStatus(e.target.value)}><option value="">All statuses</option>{statuses.map((item) => <option value={item} key={item}>{label(item)}</option>)}</select><select value={service} onChange={(e) => setService(e.target.value)}><option value="">All services</option>{services.map((item) => <option value={item.id} key={item.id}>{item.title}</option>)}</select><button>Filter</button></form></div><section className="panel table-panel"><div className="data-table"><table><thead><tr><th>Contact</th><th>Service</th><th>Status</th><th>Notification</th><th>Received</th></tr></thead><tbody>{leads.data.map((lead) => <tr key={lead.id}><td><Link href={`/admin/leads/${lead.id}`}><strong>{lead.name}</strong><small>{lead.company || lead.email}</small></Link></td><td>{lead.service?.title || 'General inquiry'}</td><td><span className={`status ${lead.status}`}>{label(lead.status)}</span></td><td><span className={`status ${lead.notification_status}`}>{label(lead.notification_status)}</span></td><td>{formatDate(lead.created_at)}</td></tr>)}</tbody></table>{!leads.data.length && <p className="empty-copy">No leads match these filters.</p>}</div></section><Pagination page={leads} /></AdminLayout>;
}
