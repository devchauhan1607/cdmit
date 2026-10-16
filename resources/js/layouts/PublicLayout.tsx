import { Link, usePage } from '@inertiajs/react';
import { Menu, X, Mail, MapPin, ArrowUpRight } from 'lucide-react';
import { useState, type ReactNode } from 'react';
import { Brand } from '@/components/Brand';
import type { PageProps } from '@/types';

const links = [['Services', '/services'], ['Portfolio', '/portfolio'], ['Technologies', '/technologies'], ['Insights', '/blog'], ['About', '/about']];

export default function PublicLayout({ children }: { children: ReactNode }) {
    const [open, setOpen] = useState(false);
    const { site } = usePage().props as unknown as PageProps;
    return <div className="site-shell">
        <a href="#main-content" className="skip-link">Skip to content</a>
        <header className="site-header">
            <div className="container header-inner"><Brand />
                <nav className="desktop-nav" aria-label="Primary">{links.map(([label, href]) => <Link key={href} href={href}>{label}</Link>)}<Link className="nav-cta" href="/contact">Start a project</Link></nav>
                <button className="icon-btn mobile-menu" onClick={() => setOpen(!open)} aria-label="Toggle menu" aria-expanded={open}>{open ? <X /> : <Menu />}</button>
            </div>
            {open && <nav className="mobile-nav" aria-label="Mobile">{links.map(([label, href]) => <Link key={href} href={href} onClick={() => setOpen(false)}>{label}</Link>)}<Link href="/contact">Start a project</Link></nav>}
        </header>
        <main id="main-content">{children}</main>
        <footer className="site-footer"><div className="container footer-grid">
            <div><Brand light /><p>{site.tagline || "Engineering Digital Solutions. Building What's Next."}</p></div>
            <div><h3>Expertise</h3><Link href="/services">Software development</Link><Link href="/technologies">Technology capabilities</Link><Link href="/portfolio">Case studies</Link></div>
            <div><h3>Company</h3><Link href="/about">About CDMIT</Link><Link href="/blog">Technical insights</Link><Link href="/contact">Project inquiry</Link></div>
            <div><h3>Contact</h3>{site.contact_email && <a href={`mailto:${site.contact_email}`}><Mail size={15} />{site.contact_email}</a>}{site.service_areas && <span><MapPin size={15} />{site.service_areas}</span>}{site.linkedin_url && <a href={site.linkedin_url} target="_blank" rel="noreferrer">LinkedIn <ArrowUpRight size={14} /></a>}</div>
        </div><div className="container footer-base"><span>© {new Date().getFullYear()} CDMIT. All rights reserved.</span><span>Built with clarity, security and care.</span></div></footer>
    </div>;
}
