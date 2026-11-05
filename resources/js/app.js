import './bootstrap';

import Alpine from 'alpinejs';
import gsap from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';

window.Alpine = Alpine;

Alpine.start();

const reducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

document.addEventListener('DOMContentLoaded', () => {
    initRevealObserver();

    if (reducedMotion) return;

    gsap.registerPlugin(ScrollTrigger);

    // Hero entrance — badge, headline, copy, CTAs, meta, preview card
    const heroItems = document.querySelectorAll('[data-hero]');
    if (heroItems.length) {
        gsap.from(heroItems, {
            y: 30,
            opacity: 0,
            duration: 0.75,
            stagger: 0.1,
            ease: 'power3.out',
            clearProps: 'all',
        });
    }

    // Stats strip — count-up cards slide in after hero
    const statItems = document.querySelectorAll('[data-stat]');
    if (statItems.length) {
        gsap.from(statItems, {
            y: 26,
            opacity: 0,
            duration: 0.6,
            stagger: 0.08,
            delay: 0.4,
            ease: 'power3.out',
            clearProps: 'all',
        });
    }

    // Event cards — staggered scroll reveal
    const cardGrids = document.querySelectorAll('[data-gsap-cards]');
    cardGrids.forEach((grid) => {
        gsap.from(grid.children, {
            scrollTrigger: {
                trigger: grid,
                start: 'top 88%',
            },
            y: 40,
            opacity: 0,
            scale: 0.97,
            duration: 0.7,
            stagger: 0.12,
            ease: 'power3.out',
            clearProps: 'all',
        });
    });
});

function initRevealObserver() {
    const items = document.querySelectorAll('.reveal');

    if (!items.length) return;

    if (reducedMotion || !('IntersectionObserver' in window)) {
        items.forEach((el) => el.classList.add('in-view'));

        return;
    }

    const observer = new IntersectionObserver(
        (entries) => {
            entries.forEach((entry) => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('in-view');
                    observer.unobserve(entry.target);
                }
            });
        },
        { threshold: 0.12, rootMargin: '0px 0px -40px 0px' },
    );

    items.forEach((el) => observer.observe(el));
}
