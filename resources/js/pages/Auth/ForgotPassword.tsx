import { Head, Link, useForm } from '@inertiajs/react';
import type { FormEvent } from 'react';
import AuthLayout from '@/layouts/AuthLayout';
import { FieldError } from '@/components/Ui';

export default function ForgotPassword({ status }: { status?: string }) {
    const form = useForm({ email: '' });
    const submit = (e: FormEvent) => { e.preventDefault(); form.post('/forgot-password'); };
    return <AuthLayout title="Reset access" copy="We’ll send a secure reset link if the account is authorized."><Head title="Reset password"><meta name="robots" content="noindex,nofollow" /></Head>{status && <div className="alert success">{status}</div>}<form onSubmit={submit} className="stack-form"><label>Email address<input type="email" value={form.data.email} onChange={(e) => form.setData('email', e.target.value)} /><FieldError message={form.errors.email} /></label><button className="btn btn-primary" disabled={form.processing}>Send reset link</button><Link href="/login" className="text-link">Return to sign in</Link></form></AuthLayout>;
}
