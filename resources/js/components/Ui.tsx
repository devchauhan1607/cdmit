import { Link } from '@inertiajs/react';
import { ArrowRight } from 'lucide-react';
import type { ReactNode } from 'react';
import { cn } from '@/lib/utils';

export function ButtonLink({ href, children, variant = 'primary', className }: { href: string; children: ReactNode; variant?: 'primary' | 'secondary' | 'ghost'; className?: string }) {
    return <Link href={href} className={cn('btn', `btn-${variant}`, className)}>{children}<ArrowRight size={16} aria-hidden="true" /></Link>;
}
export function SectionHeading({ eyebrow, title, copy }: { eyebrow?: string; title: string; copy?: string }) {
    return <div className="section-heading">{eyebrow && <span className="eyebrow">{eyebrow}</span>}<h2>{title}</h2>{copy && <p>{copy}</p>}</div>;
}
export function EmptyState({ title, copy }: { title: string; copy: string }) {
    return <div className="empty-state"><h3>{title}</h3><p>{copy}</p></div>;
}
export function FieldError({ message }: { message?: string }) { return message ? <p className="field-error" role="alert">{message}</p> : null; }
