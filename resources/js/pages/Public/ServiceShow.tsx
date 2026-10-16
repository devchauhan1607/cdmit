import { Link } from '@inertiajs/react';
import { ArrowRight, CheckCircle2 } from 'lucide-react';
import PublicLayout from '@/layouts/PublicLayout';
import { ButtonLink, SectionHeading } from '@/components/Ui';
import { SeoHead } from '@/components/SeoHead';
import type { Service } from '@/types';

export default function ServiceShow({ service, related = [] }: { service: Service; related?: Service[] }) {
    return <PublicLayout><SeoHead seo={{ title: service.meta_title, description: service.meta_description, canonical_url: service.canonical_url, noindex: service.noindex }} fallbackTitle={service.title} fallbackDescription={service.summary} />
        <section className="detail-hero"><div className="container"><div><span className="eyebrow">{service.eyebrow || service.category?.name || 'CDMIT service'}</span><h1>{service.title}</h1><p>{service.summary}</p><ButtonLink href={`/contact?service=${service.id}`}>Discuss this capability</ButtonLink></div><aside><span>Engagement focus</span><strong>Strategy</strong><strong>Design</strong><strong>Engineering</strong><strong>Launch & support</strong></aside></div></section>
        <section className="section"><div className="container article-grid"><article className="prose"><h2>Overview</h2>{service.overview.split('\n').map((paragraph, i) => <p key={i}>{paragraph}</p>)}{service.benefits?.length ? <><h2>Business benefits</h2><ul>{service.benefits.map((item) => <li key={item}><CheckCircle2 />{item}</li>)}</ul></> : null}</article><aside className="detail-aside"><h3>Typical deliverables</h3>{service.deliverables?.map((item) => <span key={item}>{item}</span>)}{service.technologies?.length ? <><h3>Relevant technologies</h3><div className="chips">{service.technologies.map((item) => <b key={item}>{item}</b>)}</div></> : null}</aside></div></section>
        {service.process?.length ? <section className="section band"><div className="container"><SectionHeading eyebrow="Delivery path" title="How the engagement moves" /><div className="process-grid">{service.process.map((item, i) => <article key={item}><span>{String(i + 1).padStart(2, '0')}</span><p>{item}</p></article>)}</div></div></section> : null}
        {related.length > 0 && <section className="section"><div className="container"><SectionHeading eyebrow="Related capabilities" title="Continue exploring" /><div className="post-grid">{related.map((item) => <Link className="post-card" href={`/services/${item.slug}`} key={item.id}><h3>{item.title}</h3><p>{item.summary}</p><b>View service <ArrowRight size={15} /></b></Link>)}</div></div></section>}
        <section className="cta-band"><div className="container"><div><span className="eyebrow">Build with confidence</span><h2>Let’s shape the right solution for your context.</h2></div><ButtonLink href="/contact">Plan your project</ButtonLink></div></section>
    </PublicLayout>;
}
