import { Link, router } from '@inertiajs/react';
import { ArrowRight, Search } from 'lucide-react';
import { useState, type FormEvent } from 'react';
import PublicLayout from '@/layouts/PublicLayout';
import { EmptyState } from '@/components/Ui';
import { SeoHead } from '@/components/SeoHead';
import { Pagination } from '@/components/Pagination';
import { asset } from '@/lib/utils';
import type { CaseStudy, Paginated, Seo } from '@/types';

export default function Portfolio({ caseStudies, filters, seo }: { caseStudies: Paginated<CaseStudy>; filters: { q?: string }; seo: Seo }) {
    const [query, setQuery] = useState(filters.q || '');
    const submit = (e: FormEvent) => { e.preventDefault(); router.get('/portfolio', query ? { q: query } : {}); };
    return <PublicLayout><SeoHead seo={seo} fallbackTitle="Software case studies" fallbackDescription="Explore selected CDMIT software engineering and digital product work." />
        <section className="page-hero"><div className="container"><span className="eyebrow">Case studies</span><h1>How thoughtful engineering turns constraints into progress.</h1><p>Published work appears here only when project details and outcomes are approved for public use.</p></div></section>
        <section className="section"><div className="container"><form className="search-bar" onSubmit={submit}><Search /><input aria-label="Search case studies" value={query} onChange={(e) => setQuery(e.target.value)} placeholder="Search projects or industries" /><button>Search</button></form>{caseStudies.data.length ? <><div className="portfolio-grid">{caseStudies.data.map((item) => <Link href={`/portfolio/${item.slug}`} key={item.id} className="portfolio-card"><div>{item.cover_image ? <img src={asset(item.cover_image)} alt="" /> : <span>CDMIT / CASE STUDY</span>}</div><small>{item.industry || 'Software engineering'}</small><h2>{item.title}</h2><p>{item.summary}</p><b>Explore project <ArrowRight size={15} /></b></Link>)}</div><Pagination page={caseStudies} /></> : <EmptyState title="Case studies are being prepared" copy="We publish client work only after the details are verified and authorized. Contact us to discuss relevant experience privately." />}</div></section>
    </PublicLayout>;
}
