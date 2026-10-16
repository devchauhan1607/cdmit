import { Braces, Cloud, Database, Smartphone, Sparkles } from 'lucide-react';
import PublicLayout from '@/layouts/PublicLayout';
import { ButtonLink } from '@/components/Ui';
import { SeoHead } from '@/components/SeoHead';
import type { Seo, Technology } from '@/types';

const icons = [Braces, Cloud, Database, Smartphone, Sparkles];
export default function Technologies({ technologies, seo }: { technologies: Record<string, Technology[]>; seo: Seo }) {
    return <PublicLayout><SeoHead seo={seo} fallbackTitle="Technology capabilities" fallbackDescription="Explore the frontend, backend, mobile, database, cloud and AI technologies supported by CDMIT." />
        <section className="page-hero"><div className="container"><span className="eyebrow">Technology stack</span><h1>Tools selected for fit, not fashion.</h1><p>We work across established and modern technology ecosystems, choosing a stack based on product needs, team context, maintainability and operational reality.</p></div></section>
        <section className="section"><div className="container technology-groups">{Object.entries(technologies).map(([category, items], index) => { const Icon = icons[index % icons.length]; return <section key={category}><div><Icon /><h2>{category}</h2><p>Capabilities actively managed through the CDMIT service catalog.</p></div><ul>{items.map((item) => <li key={item.id}><strong>{item.name}</strong>{item.description && <span>{item.description}</span>}</li>)}</ul></section>; })}</div></section>
        <section className="cta-band"><div className="container"><div><span className="eyebrow">Architecture before tooling</span><h2>Let’s choose the right foundation for your system.</h2></div><ButtonLink href="/contact">Discuss your stack</ButtonLink></div></section>
    </PublicLayout>;
}
