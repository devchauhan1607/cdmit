import { Link } from '@inertiajs/react';
import { ChevronLeft, ChevronRight } from 'lucide-react';
import type { Paginated } from '@/types';

export function Pagination({ page }: { page: Pick<Paginated<unknown>, 'current_page' | 'last_page' | 'prev_page_url' | 'next_page_url'> }) {
    if (page.last_page <= 1) return null;
    return <nav className="pagination" aria-label="Pagination">
        {page.prev_page_url ? <Link href={page.prev_page_url} preserveScroll><ChevronLeft /> Previous</Link> : <span><ChevronLeft /> Previous</span>}
        <b>Page {page.current_page} of {page.last_page}</b>
        {page.next_page_url ? <Link href={page.next_page_url} preserveScroll>Next <ChevronRight /></Link> : <span>Next <ChevronRight /></span>}
    </nav>;
}
