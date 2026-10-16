import { Head } from '@inertiajs/react';
import type { Seo } from '@/types';

export function SeoHead({ seo, fallbackTitle, fallbackDescription }: { seo?: Seo; fallbackTitle: string; fallbackDescription: string }) {
    const title = seo?.title || fallbackTitle;
    const description = seo?.description || fallbackDescription;
    return <Head title={title}>
        <meta name="description" content={description} />
        {seo?.canonical_url && <link rel="canonical" href={seo.canonical_url} />}
        <meta property="og:title" content={seo?.social_title || title} />
        <meta property="og:description" content={seo?.social_description || description} />
        <meta property="og:type" content="website" />
        {seo?.social_image && <meta property="og:image" content={seo.social_image} />}
        {seo?.noindex && <meta name="robots" content="noindex,nofollow" />}
    </Head>;
}
