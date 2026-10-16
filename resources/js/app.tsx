import '../css/app.css';
import './bootstrap';
import { createInertiaApp } from '@inertiajs/react';
import { createRoot } from 'react-dom/client';
import type { ComponentType } from 'react';

const pages = import.meta.glob('./pages/**/*.tsx');

createInertiaApp({
    title: (title) => title ? `${title} | CDMIT` : 'CDMIT',
    resolve: async (name) => {
        const loader = pages[`./pages/${name}.tsx`];
        if (!loader) throw new Error(`Page not found: ${name}`);
        const page = await loader() as { default: ComponentType };
        return page.default;
    },
    setup({ el, App, props }) { if (el) createRoot(el).render(<App {...props} />); },
    progress: { color: '#11b8d1', showSpinner: false },
});
