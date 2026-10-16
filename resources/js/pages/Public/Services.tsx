import { Link, router } from '@inertiajs/react';
import { ArrowRight, Search } from 'lucide-react';
import { useState, type FormEvent } from 'react';
import PublicLayout from '@/layouts/PublicLayout';
import { EmptyState } from '@/components/Ui';
import { SeoHead } from '@/components/SeoHead';
import { Pagination } from '@/components/Pagination';
import type { Paginated, Seo, Service } from '@/types';

export default function Services({ services, filters, seo }: { services: Paginated<Service>; filters: { q?: string }; seo: Seo }) {
    const [query, setQuery] = useState(filters.q || '');
    const search = (event: FormEvent) => { event.preventDefault(); router.get('/services', query ? { q: query } : {}, { preserveState: true }); };
    return <PublicLayout><SeoHead seo={seo} fallbackTitle="Software development services" fallbackDescription="Explore CDMIT services across web, mobile, cloud, custom software, AI automation, design and technical support." />
        <section className="page-hero"><div className="container"><span className="eyebrow">Capabilities</span><h1>Engineering services for products, platforms and business systems.</h1><p>Choose a focused capability or bring us a cross-functional challenge. Our engagements adapt to the work, while keeping ownership and communication clear.</p></div></section>
        <section className="section"><div className="container"><form className="search-bar" onSubmit={search}><Search /><label className="sr-only" htmlFor="service-search">Search services</label><input id="service-search" value={query} onChange={(e) => setQuery(e.target.value)} placeholder="Search by capability or technology" /><button>Search</button></form>
            {services.data.length ? <><div className="service-list">{services.data.map((service, index) => <Link href={`/services/${service.slug}`} className="service-row" key={service.id}><span>{String(index + 1).padStart(2, '0')}</span><div><small>{service.category?.name || 'Engineering service'}</small><h2>{service.title}</h2><p>{service.summary}</p></div><ArrowRight /></Link>)}</div><Pagination page={services} /></> : <EmptyState title="No matching service" copy="Try a broader search or contact us to discuss a capability not listed yet." />}
        </div></section>
    </PublicLayout>;
}
