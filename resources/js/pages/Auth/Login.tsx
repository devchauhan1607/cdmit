import { Head, Link, useForm } from '@inertiajs/react';
import type { FormEvent } from 'react';
import AuthLayout from '@/layouts/AuthLayout';
import { FieldError } from '@/components/Ui';

export default function Login() {
    const form = useForm({ email: '', password: '', remember: false });
    const submit = (e: FormEvent) => { e.preventDefault(); form.post('/login', { onFinish: () => form.reset('password') }); };
    return <AuthLayout title="Welcome back" copy="Sign in with the authorized administrator account."><Head title="Admin sign in"><meta name="robots" content="noindex,nofollow" /></Head><form onSubmit={submit} className="stack-form"><label>Email address<input type="email" autoComplete="username" autoFocus value={form.data.email} onChange={(e) => form.setData('email', e.target.value)} /><FieldError message={form.errors.email} /></label><label>Password<input type="password" autoComplete="current-password" value={form.data.password} onChange={(e) => form.setData('password', e.target.value)} /><FieldError message={form.errors.password} /></label><label className="checkbox-row"><input type="checkbox" checked={form.data.remember} onChange={(e) => form.setData('remember', e.target.checked)} />Keep me signed in</label><button className="btn btn-primary" disabled={form.processing}>Sign in</button><Link href="/forgot-password" className="text-link">Forgot password?</Link></form></AuthLayout>;
}
