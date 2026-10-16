import type { ReactNode } from 'react';
import { Brand } from '@/components/Brand';

export default function AuthLayout({ children, title, copy }: { children: ReactNode; title: string; copy: string }) {
    return <main className="auth-shell"><section className="auth-brand"><Brand light /><div><span className="eyebrow">Secure administration</span><h1>Manage CDMIT with confidence.</h1><p>Protected access for the authorized Super Admin only.</p></div></section><section className="auth-panel"><div className="auth-card"><h2>{title}</h2><p>{copy}</p>{children}</div></section></main>;
}
