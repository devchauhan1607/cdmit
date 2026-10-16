import type { ReactNode } from 'react';
import { FieldError } from '@/components/Ui';

export function Field({ label, error, hint, children, full = false }: { label: string; error?: string; hint?: string; children: ReactNode; full?: boolean }) { return <label className={full ? 'form-field full' : 'form-field'}><span>{label}</span>{children}{hint && <small>{hint}</small>}<FieldError message={error} /></label>; }
export function FormActions({ processing, isEdit }: { processing: boolean; isEdit: boolean }) { return <div className="form-actions"><button className="btn btn-primary" disabled={processing}>{processing ? 'Saving…' : isEdit ? 'Save changes' : 'Create record'}</button></div>; }
export const textToArray = (value: string) => value.split('\n').map((line) => line.trim()).filter(Boolean);
export const arrayToText = (value?: string[]) => value?.join('\n') || '';
