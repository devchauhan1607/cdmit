import { Head, Link, router } from '@inertiajs/react';
import { Eye, Pencil, Plus, Search, Archive } from 'lucide-react';
import { useState, type FormEvent } from 'react';
import AdminLayout from '@/layouts/AdminLayout';
import { formatDate, label } from '@/lib/utils';
import type { Paginated } from '@/types';
import { Pagination } from '@/components/Pagination';

type Item = { id: number; title: string; slug: string; status: string; updated_at: string; published_at?: string; category?: { name: string }; industry?: string };
export default function ContentIndex({ resource, title, items, filters }: { resource: string; title: string; items: Paginated<Item>; filters: { q?: string; status?: string } }) {
    const [q, setQ] = useState(filters.q || ''); const [status, setStatus] = useState(filters.status || '');
    const search = (e: FormEvent) => { e.preventDefault(); router.get(`/admin/${resource}`, { q, status }, { preserveState: true }); };
    const archive = (item: Item) => { if (confirm(`Archive “${item.title}”?`)) router.delete(`/admin/${resource}/${item.id}`); };
    return <AdminLayout title={title}><Head title={title} /><div className="toolbar"><form onSubmit={search}><Search /><input value={q} onChange={(e) => setQ(e.target.value)} placeholder={`Search ${title.toLowerCase()}`} /><select value={status} onChange={(e) => setStatus(e.target.value)}><option value="">All statuses</option><option value="draft">Draft</option><option value="published">Published</option><option value="archived">Archived</option></select><button>Filter</button></form><Link href={`/admin/${resource}/create`} className="btn btn-primary"><Plus size={16} /> New</Link></div><section className="panel table-panel"><div className="data-table"><table><thead><tr><th>Title</th><th>Context</th><th>Status</th><th>Updated</th><th><span className="sr-only">Actions</span></th></tr></thead><tbody>{items.data.map((item) => <tr key={item.id}><td><strong>{item.title}</strong><small>/{item.slug}</small></td><td>{item.category?.name || item.industry || '—'}</td><td><span className={`status ${item.status}`}>{label(item.status)}</span></td><td>{formatDate(item.updated_at)}</td><td><div className="row-actions"><Link href={`/admin/${resource}/${item.id}`} title="Preview"><Eye /></Link><Link href={`/admin/${resource}/${item.id}/edit`} title="Edit"><Pencil /></Link><button onClick={() => archive(item)} title="Archive"><Archive /></button></div></td></tr>)}</tbody></table>{!items.data.length && <p className="empty-copy">No records match these filters.</p>}</div></section><Pagination page={items} /></AdminLayout>;
}
