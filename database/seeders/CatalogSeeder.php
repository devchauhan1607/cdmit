<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\PageSeo;
use App\Models\Service;
use App\Models\SiteSetting;
use App\Models\Technology;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CatalogSeeder extends Seeder
{
    public function run(): void
    {
        $category = Category::updateOrCreate(['slug' => 'digital-engineering'], [
            'name' => 'Digital Engineering', 'type' => 'service',
            'description' => 'Strategy, design, software engineering and cloud delivery.', 'display_order' => 1,
        ]);

        $services = [
            ['Web Application Development', 'Secure, responsive web applications designed around real workflows and built for sustainable growth.', ['Discovery and architecture', 'Responsive application interface', 'Backend and database implementation', 'Testing and production release'], ['Laravel', 'PHP', 'React', 'TypeScript']],
            ['Website Design & Development', 'Conversion-focused corporate and content websites with a clear information structure, strong performance and practical content management.', ['Content and UX planning', 'Responsive visual design', 'CMS implementation', 'SEO and launch configuration'], ['React', 'Next.js', 'Tailwind CSS']],
            ['Mobile App Development', 'Purpose-built mobile experiences for customer, field and operational use cases across supported device platforms.', ['Product discovery', 'Interaction design', 'Application engineering', 'Release and maintenance planning'], ['React Native', 'Flutter', 'Node.js']],
            ['Custom Software Development', 'Business software shaped around unique processes, integrations and operating constraints rather than a generic template.', ['Requirements modelling', 'Solution architecture', 'Incremental implementation', 'Documentation and handover'], ['Laravel', 'Node.js', 'MySQL', 'AWS']],
            ['AI Integration & Automation', 'Responsible integration of AI capabilities and workflow automation into existing products and business operations.', ['Use-case assessment', 'Integration architecture', 'Automation implementation', 'Monitoring and safeguards'], ['OpenAI APIs', 'Node.js', 'Python', 'AWS']],
            ['Cloud Infrastructure & DevOps', 'Production-aware cloud architecture, deployment automation, observability and operational improvements.', ['Cloud environment design', 'CI/CD configuration', 'Security and access review', 'Monitoring and operational guidance'], ['AWS', 'Docker', 'Linux', 'GitHub Actions']],
            ['E-commerce Development', 'E-commerce experiences and operational integrations designed for clear purchasing journeys and maintainable administration.', ['Storefront UX', 'Catalog and checkout implementation', 'Payment and service integrations', 'Analytics and launch support'], ['Laravel', 'React', 'WooCommerce', 'Shopify']],
            ['UI/UX Design', 'Research-informed product and interface design that reduces friction and creates a coherent system for engineering teams.', ['User-flow definition', 'Wireframes and prototypes', 'Interface design system', 'Developer-ready specifications'], ['Figma', 'Design systems', 'Accessibility']],
            ['Maintenance & Technical Support', 'Structured support for production systems, including defect resolution, upgrades, monitoring and planned improvement.', ['Application health review', 'Issue triage and resolution', 'Dependency and security updates', 'Release support'], ['Laravel', 'React', 'AWS', 'MySQL']],
        ];

        foreach ($services as $index => [$title, $summary, $deliverables, $technologies]) {
            Service::updateOrCreate(['slug' => Str::slug($title)], [
                'category_id' => $category->id, 'title' => $title, 'summary' => $summary,
                'overview' => $summary."\n\nEvery engagement begins with context: the business outcome, users, current systems, constraints and ownership model. CDMIT then defines a delivery path with clear responsibilities, milestones and quality expectations.",
                'benefits' => ['A solution aligned to the operating context', 'Transparent delivery decisions and progress', 'Maintainable implementation and documentation'],
                'deliverables' => $deliverables,
                'process' => ['Discover the problem and success criteria', 'Define the solution and delivery roadmap', 'Design and build in reviewable milestones', 'Validate, release and support'],
                'technologies' => $technologies, 'status' => 'published', 'is_featured' => $index < 6,
                'display_order' => $index + 1, 'published_at' => now(),
                'meta_title' => $title.' | CDMIT', 'meta_description' => $summary,
            ]);
        }

        $technologies = [
            'Frontend' => ['React', 'Next.js', 'Angular', 'TypeScript', 'Tailwind CSS'],
            'Backend' => ['Laravel', 'PHP', 'Node.js'],
            'Mobile' => ['React Native', 'Flutter'],
            'Databases' => ['MySQL', 'PostgreSQL', 'Redis'],
            'Cloud & DevOps' => ['AWS', 'Docker', 'Linux', 'GitHub Actions'],
            'AI & Automation' => ['OpenAI APIs', 'Workflow automation'],
            'Commerce' => ['WooCommerce', 'Shopify'],
            'Design' => ['Figma', 'Accessible design systems'],
        ];
        $order = 1;
        foreach ($technologies as $group => $items) {
            foreach ($items as $name) {
                Technology::updateOrCreate(['slug' => Str::slug($name)], ['name' => $name, 'category' => $group, 'display_order' => $order++, 'is_active' => true]);
            }
        }

        foreach ([
            'company_name' => 'CDMIT',
            'tagline' => "Engineering Digital Solutions. Building What's Next.",
            'service_areas' => 'India and international',
            'default_meta_title' => 'CDMIT | Software Engineering & IT Services',
            'default_meta_description' => 'CDMIT designs and engineers web, mobile, cloud, commerce and automation solutions for businesses in India and worldwide.',
        ] as $key => $value) {
            SiteSetting::updateOrCreate(['key' => $key], ['value' => $value, 'type' => 'string', 'group' => str_contains($key, 'meta') ? 'seo' : 'general', 'is_public' => true]);
        }

        foreach ([
            'home' => ['Software Engineering & IT Services | CDMIT', 'CDMIT builds secure web, mobile, cloud and automation solutions for businesses in India and worldwide.'],
            'about' => ['About CDMIT', 'Learn how CDMIT approaches software strategy, design, engineering and long-term technical partnership.'],
            'services' => ['Software Development Services | CDMIT', 'Explore CDMIT services across web, mobile, cloud, custom software, AI automation, commerce, design and technical support.'],
            'portfolio' => ['Software Case Studies | CDMIT', 'Explore approved CDMIT case studies across digital products, business software and cloud delivery.'],
            'technologies' => ['Technology Capabilities | CDMIT', 'Explore the frontend, backend, mobile, database, cloud and AI technologies supported by CDMIT.'],
            'blog' => ['Technical Insights | CDMIT', 'Practical perspectives on software engineering, product design, cloud infrastructure and digital delivery.'],
            'contact' => ['Start a Project | CDMIT', 'Tell CDMIT about your software, web, mobile, cloud or automation project.'],
        ] as $key => [$title, $description]) {
            PageSeo::updateOrCreate(['page_key' => $key], ['title' => $title, 'description' => $description]);
        }
    }
}
