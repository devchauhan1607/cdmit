import { Link } from '@inertiajs/react';
import { ArrowRight } from 'lucide-react';
import PublicLayout from '@/layouts/PublicLayout';
import { SeoHead } from '@/components/SeoHead';
import { formatDate } from '@/lib/utils';
import type { Post } from '@/types';

export default function PostShow({ post, related = [] }: { post: Post; related?: Post[] }) {
    return <PublicLayout><SeoHead seo={{ title: post.meta_title, description: post.meta_description, canonical_url: post.canonical_url, noindex: post.noindex }} fallbackTitle={post.title} fallbackDescription={post.excerpt} />
        <article><header className="article-hero"><div className="container narrow"><span className="eyebrow">{post.category?.name || 'Technical insight'}</span><h1>{post.title}</h1><p>{post.excerpt}</p><div><span>{post.author?.name || 'CDMIT'}</span><time>{formatDate(post.published_at)}</time></div></div></header><div className="container reading-column">{post.content.split('\n').map((paragraph, i) => paragraph.trim() ? <p key={i}>{paragraph}</p> : null)}<div className="tag-row">{post.tags?.map((tag) => <Link key={tag.id} href={`/blog?tag=${tag.slug}`}>{tag.name}</Link>)}</div></div></article>
        {related.length > 0 && <section className="section band"><div className="container"><h2>Related insights</h2><div className="post-grid">{related.map((item) => <Link className="post-card" href={`/blog/${item.slug}`} key={item.id}><h3>{item.title}</h3><p>{item.excerpt}</p><b>Read article <ArrowRight size={15} /></b></Link>)}</div></div></section>}
    </PublicLayout>;
}
