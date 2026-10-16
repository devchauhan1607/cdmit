import { Link, router } from '@inertiajs/react';
import { ArrowRight, Search } from 'lucide-react';
import { useState, type FormEvent } from 'react';
import PublicLayout from '@/layouts/PublicLayout';
import { EmptyState } from '@/components/Ui';
import { SeoHead } from '@/components/SeoHead';
import { Pagination } from '@/components/Pagination';
import { formatDate } from '@/lib/utils';
import type { Paginated, Post, Seo } from '@/types';

export default function Blog({ posts, filters, seo }: { posts: Paginated<Post>; filters: { q?: string }; seo: Seo }) {
    const [query, setQuery] = useState(filters.q || '');
    const submit = (e: FormEvent) => { e.preventDefault(); router.get('/blog', query ? { q: query } : {}); };
    return <PublicLayout><SeoHead seo={seo} fallbackTitle="Technical insights" fallbackDescription="Practical perspectives from CDMIT on software engineering, product design, cloud infrastructure and digital delivery." />
        <section className="page-hero"><div className="container"><span className="eyebrow">Insights</span><h1>Practical thinking for teams building digital products.</h1><p>Technical perspectives, delivery lessons and decision frameworks from the work.</p></div></section>
        <section className="section"><div className="container"><form className="search-bar" onSubmit={submit}><Search /><input aria-label="Search insights" value={query} onChange={(e) => setQuery(e.target.value)} placeholder="Search articles" /><button>Search</button></form>{posts.data.length ? <><div className="blog-list">{posts.data.map((post) => <Link href={`/blog/${post.slug}`} key={post.id}><div><span>{post.category?.name || 'Insight'}</span><time>{formatDate(post.published_at)}</time></div><h2>{post.title}</h2><p>{post.excerpt}</p><b>Read insight <ArrowRight size={15} /></b></Link>)}</div><Pagination page={posts} /></> : <EmptyState title="No published insights yet" copy="Articles appear here after editorial review and publication." />}</div></section>
    </PublicLayout>;
}
