import { Link } from '@inertiajs/react';

export function Brand({ light = false }: { light?: boolean }) {
    return <Link href="/" className="brand" aria-label="CDMIT home">
        <span className="brand-mark" aria-hidden="true"><i>C</i><b /></span>
        <span className={light ? 'text-white' : ''}>CDMIT<small>Digital engineering</small></span>
    </Link>;
}
