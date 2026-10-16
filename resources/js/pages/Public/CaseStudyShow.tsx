import { ExternalLink, CheckCircle2 } from 'lucide-react';
import PublicLayout from '@/layouts/PublicLayout';
import { ButtonLink } from '@/components/Ui';
import { SeoHead } from '@/components/SeoHead';
import { asset } from '@/lib/utils';
import type { CaseStudy } from '@/types';

export default function CaseStudyShow({ caseStudy }: { caseStudy: CaseStudy }) {
    return <PublicLayout><SeoHead seo={{ title: caseStudy.meta_title, description: caseStudy.meta_description, canonical_url: caseStudy.canonical_url, noindex: caseStudy.noindex }} fallbackTitle={caseStudy.title} fallbackDescription={caseStudy.summary} />
        <section className="case-detail-hero"><div className="container"><span className="eyebrow">{caseStudy.industry || 'Case study'}</span><h1>{caseStudy.title}</h1><p>{caseStudy.summary}</p><div className="chips">{caseStudy.technologies?.map((item) => <b key={item.id}>{item.name}</b>)}</div></div></section>
        {caseStudy.cover_image && <div className="container case-cover"><img src={asset(caseStudy.cover_image)} alt={`${caseStudy.title} project`} /></div>}
        <section className="section"><div className="container story-grid"><aside><span>Project</span><strong>{caseStudy.title}</strong>{caseStudy.client_name && <><span>Client</span><strong>{caseStudy.client_name}</strong></>}{caseStudy.project_url && <a href={caseStudy.project_url} target="_blank" rel="noreferrer">Visit project <ExternalLink size={14} /></a>}</aside><article className="prose"><h2>Overview</h2><p>{caseStudy.overview}</p><h2>The challenge</h2><p>{caseStudy.challenge}</p><h2>The solution</h2><p>{caseStudy.solution}</p>{caseStudy.implementation && <><h2>Implementation</h2><p>{caseStudy.implementation}</p></>}{caseStudy.results?.length ? <><h2>Verified outcomes</h2><ul>{caseStudy.results.map((result) => <li key={result}><CheckCircle2 />{result}</li>)}</ul></> : null}</article></div></section>
        <section className="cta-band"><div className="container"><div><span className="eyebrow">Your next project</span><h2>Need a team that can navigate the difficult parts?</h2></div><ButtonLink href="/contact">Talk to CDMIT</ButtonLink></div></section>
    </PublicLayout>;
}
