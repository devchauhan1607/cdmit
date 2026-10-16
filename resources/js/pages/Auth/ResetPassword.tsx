import { Head, useForm } from '@inertiajs/react';
import type { FormEvent } from 'react';
import AuthLayout from '@/layouts/AuthLayout';
import { FieldError } from '@/components/Ui';

export default function ResetPassword({ email, token }: { email: string; token: string }) {
    const form = useForm({ email, token, password: '', password_confirmation: '' });
    const submit = (e: FormEvent) => { e.preventDefault(); form.post('/reset-password', { onFinish: () => form.reset('password', 'password_confirmation') }); };
    return <AuthLayout title="Choose a new password" copy="Use a unique password of at least 12 characters."><Head title="Choose a new password"><meta name="robots" content="noindex,nofollow" /></Head><form onSubmit={submit} className="stack-form"><label>Email<input type="email" value={form.data.email} readOnly /><FieldError message={form.errors.email} /></label><label>New password<input type="password" autoFocus value={form.data.password} onChange={(e) => form.setData('password', e.target.value)} /><FieldError message={form.errors.password} /></label><label>Confirm password<input type="password" value={form.data.password_confirmation} onChange={(e) => form.setData('password_confirmation', e.target.value)} /></label><button className="btn btn-primary" disabled={form.processing}>Reset password</button></form></AuthLayout>;
}
