import { Head, router, useForm } from '@inertiajs/react';
import { Trash2 } from 'lucide-react';
import type { FormEvent } from 'react';
import AdminLayout from '@/layouts/AdminLayout';

export default function Catalog({ categories, technologies }: { categories: any[]; technologies: any[] }) {
    const category = useForm({ name: '', type: 'service', description: '', display_order: 0 });
    const technology = useForm({ name: '', category: 'Frontend', description: '', display_order: 0 });
    const addCategory = (e: FormEvent) => { e.preventDefault(); category.post('/admin/catalog/categories', { onSuccess: () => category.reset('name', 'description') }); };
    const addTechnology = (e: FormEvent) => { e.preventDefault(); technology.post('/admin/catalog/technologies', { onSuccess: () => technology.reset('name', 'description') }); };
    return <AdminLayout title="Catalog"><Head title="Categories and technologies" /><div className="admin-grid"><section className="panel form-section"><h2>Content categories</h2><form onSubmit={addCategory} className="inline-create"><input value={category.data.name} onChange={(e) => category.setData('name', e.target.value)} placeholder="Category name" /><select value={category.data.type} onChange={(e) => category.setData('type', e.target.value)}><option value="service">Service</option><option value="post">Blog</option></select><button>Add</button></form><div className="manage-list">{categories.map((item) => <div key={item.id}><span><strong>{item.name}</strong><small>{item.type}</small></span><button onClick={() => router.delete(`/admin/catalog/categories/${item.id}`)} title="Remove"><Trash2 /></button></div>)}</div></section><section className="panel form-section"><h2>Technology catalog</h2><form onSubmit={addTechnology} className="inline-create"><input value={technology.data.name} onChange={(e) => technology.setData('name', e.target.value)} placeholder="Technology name" /><input value={technology.data.category} onChange={(e) => technology.setData('category', e.target.value)} placeholder="Group" /><button>Add</button></form><div className="manage-list">{technologies.map((item) => <div key={item.id}><span><strong>{item.name}</strong><small>{item.category}</small></span><button onClick={() => router.delete(`/admin/catalog/technologies/${item.id}`)} title="Remove"><Trash2 /></button></div>)}</div></section></div></AdminLayout>;
}
