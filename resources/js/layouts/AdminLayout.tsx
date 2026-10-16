import { Link, router, usePage } from '@inertiajs/react';
import { BriefcaseBusiness, FileText, Gauge, Inbox, Layers3, LogOut, Menu, Settings, X } from 'lucide-react';
import { useState, type ReactNode } from 'react';
import { Brand } from '@/components/Brand';
import type { PageProps } from '@/types';

const nav = [
    ['Dashboard', '/admin', Gauge], ['Leads', '/admin/leads', Inbox], ['Services', '/admin/services', Layers3],
    ['Case studies', '/admin/case-studies', BriefcaseBusiness], ['Blog posts', '/admin/posts', FileText],
    ['Catalog', '/admin/catalog', Layers3], ['Settings & SEO', '/admin/settings', Settings],
] as const;

export default function AdminLayout({ children, title }: { children: ReactNode; title: string }) {
    const [open, setOpen] = useState(false);
    const { auth, flash } = usePage().props as unknown as PageProps;
    return <div className="admin-shell">
        <aside className={open ? 'admin-sidebar open' : 'admin-sidebar'}><div className="sidebar-head"><Brand light /><button className="icon-btn sidebar-close" onClick={() => setOpen(false)}><X /></button></div>
            <nav>{nav.map(([label, href, Icon]) => <Link key={href} href={href} className={location.pathname === href || (href !== '/admin' && location.pathname.startsWith(href)) ? 'active' : ''}><Icon size={18} />{label}</Link>)}</nav>
            <div className="sidebar-user"><span>{auth.user?.name}</span><small>{auth.user?.email}</small><button onClick={() => router.post('/logout')}><LogOut size={16} /> Sign out</button></div>
        </aside>
        <div className="admin-main"><header className="admin-topbar"><button className="icon-btn admin-menu" onClick={() => setOpen(true)}><Menu /></button><div><small>CDMIT Control Center</small><h1>{title}</h1></div><Link href="/" className="text-link">View website</Link></header>
            <div className="admin-content">{flash.success && <div className="alert success" role="status">{flash.success}</div>}{flash.error && <div className="alert error" role="alert">{flash.error}</div>}{children}</div>
        </div>
    </div>;
}
