<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="description" content="@yield('meta_description', 'The Grand Catalogue — a classical literary archive in the tradition of Birmingham\'s finest libraries')">
    <title>@yield('title', 'The Grand Catalogue') · Bibliotheca Classica</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,500;0,600;0,700;0,900;1,400;1,600&family=IM+Fell+English:ital@0;1&family=Cormorant+Garamond:ital,wght@0,300;0,400;0,600;1,300;1,400;1,600&family=Cinzel:wght@400;600;700&family=Crimson+Pro:ital,wght@0,300;0,400;0,600;1,300;1,400&display=swap" rel="stylesheet">

    <style>
        /* ── DESIGN TOKENS ─────────────────────────────────────────────── */
        :root {
            --parchment:     #F2E8D5;
            --parchment-dark:#E8D9BC;
            --ink:           #1A0E05;
            --mahogany:      #2C0E0E;
            --burgundy:      #6B1A1A;
            --gold:          #B8860B;
            --gold-light:    #D4A632;
            --gold-pale:     #F0D080;
            --leather:       #7B3F20;
            --sage-green:    #2E4A3E;
            --cream:         #FBF5E6;
            --shadow:        rgba(26,14,5,0.45);
            --shadow-light:  rgba(26,14,5,0.15);
            --ff-display:    'Cinzel', serif;
            --ff-heading:    'Playfair Display', serif;
            --ff-body:       'Crimson Pro', Georgia, serif;
            --ff-accent:     'IM Fell English', serif;
            --ff-sub:        'Cormorant Garamond', serif;
        }

        /* ── RESET & BASE ─────────────────────────────────────────────── */
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        html { scroll-behavior: smooth; }

        body {
            background-color: var(--mahogany);
            background-image:
                url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='4' height='4'%3E%3Crect width='4' height='4' fill='%232C0E0E'/%3E%3Ccircle cx='1' cy='1' r='0.5' fill='%23391212' opacity='0.4'/%3E%3Ccircle cx='3' cy='3' r='0.5' fill='%23391212' opacity='0.4'/%3E%3C/svg%3E");
            color: var(--ink);
            font-family: var(--ff-body);
            font-size: 18px;
            line-height: 1.7;
            min-height: 100vh;
        }

        /* ── ORNAMENT HELPERS ────────────────────────────────────────── */
        .ornament {
            display: flex;
            align-items: center;
            gap: 12px;
            color: var(--gold);
            font-size: 20px;
            letter-spacing: 6px;
        }
        .ornament::before,
        .ornament::after {
            content: '';
            flex: 1;
            height: 1px;
            background: linear-gradient(to right, transparent, var(--gold), transparent);
        }

        .divider-ornate {
            text-align: center;
            color: var(--gold);
            font-size: 22px;
            letter-spacing: 10px;
            margin: 2rem 0;
            opacity: 0.7;
        }

        /* ── HEADER ───────────────────────────────────────────────────── */
        .site-header {
            background: linear-gradient(180deg, #0D0404 0%, #1C0808 40%, #2C0E0E 100%);
            border-bottom: 3px solid var(--gold);
            position: relative;
            overflow: hidden;
        }

        .site-header::before {
            content: '';
            position: absolute;
            inset: 0;
            background-image:
                repeating-linear-gradient(90deg, transparent, transparent 80px, rgba(184,134,11,0.04) 80px, rgba(184,134,11,0.04) 81px);
            pointer-events: none;
        }

        .header-crest {
            text-align: center;
            padding: 36px 20px 20px;
        }

        .header-crest .crest-svg {
            width: 80px;
            height: 80px;
            margin: 0 auto 12px;
            filter: drop-shadow(0 2px 12px rgba(184,134,11,0.5));
        }

        .site-title {
            font-family: var(--ff-display);
            font-size: clamp(1.8rem, 4vw, 3.2rem);
            font-weight: 700;
            letter-spacing: 0.25em;
            text-transform: uppercase;
            color: var(--gold-light);
            text-shadow: 0 2px 20px rgba(184,134,11,0.4), 0 0 60px rgba(184,134,11,0.1);
            line-height: 1.1;
        }

        .site-subtitle {
            font-family: var(--ff-accent);
            font-style: italic;
            font-size: clamp(0.9rem, 1.5vw, 1.1rem);
            color: var(--parchment-dark);
            letter-spacing: 0.15em;
            margin-top: 6px;
            opacity: 0.85;
        }

        .header-rule {
            width: 260px;
            margin: 18px auto 0;
            height: 1px;
            background: linear-gradient(90deg, transparent, var(--gold), var(--gold-light), var(--gold), transparent);
        }

        /* ── NAVIGATION ──────────────────────────────────────────────── */
        .site-nav {
            background: linear-gradient(180deg, #1A0808 0%, #120606 100%);
            border-top: 1px solid rgba(184,134,11,0.3);
            border-bottom: 2px solid rgba(184,134,11,0.5);
        }

        .nav-inner {
            max-width: 1200px;
            margin: 0 auto;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 24px;
        }

        .nav-links {
            display: flex;
            list-style: none;
            gap: 2px;
        }

        .nav-links a {
            display: block;
            padding: 14px 20px;
            font-family: var(--ff-display);
            font-size: 0.72rem;
            font-weight: 600;
            letter-spacing: 0.2em;
            text-transform: uppercase;
            color: var(--parchment-dark);
            text-decoration: none;
            transition: color 0.2s, background 0.2s;
            position: relative;
        }

        .nav-links a::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 20px;
            right: 20px;
            height: 2px;
            background: var(--gold);
            transform: scaleX(0);
            transition: transform 0.25s ease;
        }

        .nav-links a:hover,
        .nav-links a.active {
            color: var(--gold-light);
        }

        .nav-links a:hover::after,
        .nav-links a.active::after {
            transform: scaleX(1);
        }

        /* ── SEARCH BAR ──────────────────────────────────────────────── */
        .global-search {
            padding: 10px 0;
        }

        .global-search form {
            display: flex;
            align-items: stretch;
            gap: 0;
            max-width: 380px;
        }

        .global-search input {
            flex: 1;
            background: rgba(242,232,213,0.07);
            border: 1px solid rgba(184,134,11,0.4);
            border-right: none;
            border-radius: 3px 0 0 3px;
            padding: 8px 14px;
            font-family: var(--ff-body);
            font-size: 0.9rem;
            color: var(--parchment);
            outline: none;
            transition: border-color 0.2s, background 0.2s;
        }

        .global-search input::placeholder {
            color: rgba(242,232,213,0.35);
            font-style: italic;
        }

        .global-search input:focus {
            background: rgba(242,232,213,0.12);
            border-color: var(--gold);
        }

        .global-search button {
            background: var(--gold);
            border: 1px solid var(--gold);
            border-radius: 0 3px 3px 0;
            padding: 8px 16px;
            color: var(--mahogany);
            font-family: var(--ff-display);
            font-size: 0.65rem;
            font-weight: 700;
            letter-spacing: 0.1em;
            text-transform: uppercase;
            cursor: pointer;
            transition: background 0.2s, color 0.2s;
        }

        .global-search button:hover {
            background: var(--gold-light);
        }

        /* ── PAGE WRAPPER ─────────────────────────────────────────────── */
        .page-content {
            background: var(--cream);
            min-height: calc(100vh - 200px);
            background-image:
                url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='60' height='60'%3E%3Cpath d='M30 5 L55 30 L30 55 L5 30 Z' fill='none' stroke='%23B8860B' stroke-width='0.15' opacity='0.12'/%3E%3C/svg%3E");
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 24px;
        }

        /* ── SECTION HEADINGS ─────────────────────────────────────────── */
        .section-title {
            font-family: var(--ff-heading);
            font-weight: 600;
            font-size: clamp(1.5rem, 3vw, 2.2rem);
            color: var(--mahogany);
            text-align: center;
            margin-bottom: 6px;
        }

        .section-subtitle {
            font-family: var(--ff-accent);
            font-style: italic;
            font-size: 1rem;
            color: var(--leather);
            text-align: center;
            margin-bottom: 36px;
            opacity: 0.8;
        }

        /* ── BOOK CARD ─────────────────────────────────────────────── */
        .books-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));
            gap: 28px;
            padding: 20px 0;
        }

        .book-card {
            display: flex;
            flex-direction: column;
            background: #FEFAF2;
            border: 1px solid rgba(139,67,20,0.2);
            border-radius: 3px;
            overflow: hidden;
            text-decoration: none;
            color: var(--ink);
            transition: transform 0.25s ease, box-shadow 0.25s ease, border-color 0.25s;
            box-shadow: 3px 3px 0 rgba(139,67,20,0.1), 0 2px 12px var(--shadow-light);
            position: relative;
        }

        .book-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 5px;
            height: 100%;
            background: linear-gradient(180deg, var(--burgundy), var(--mahogany));
            opacity: 0.7;
        }

        .book-card:hover {
            transform: translateY(-5px) rotate(-0.4deg);
            box-shadow: 5px 10px 30px var(--shadow), 0 0 0 1px var(--gold);
            border-color: var(--gold);
            z-index: 2;
        }

        .book-cover {
            width: 100%;
            aspect-ratio: 3/4;
            object-fit: cover;
            background: linear-gradient(135deg, var(--mahogany) 0%, var(--burgundy) 100%);
            display: block;
            border-bottom: 1px solid rgba(139,67,20,0.15);
        }

        .book-cover-placeholder {
            width: 100%;
            aspect-ratio: 3/4;
            background: linear-gradient(135deg, var(--mahogany) 0%, #4A1515 50%, var(--burgundy) 100%);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 20px;
            border-bottom: 1px solid rgba(139,67,20,0.2);
            position: relative;
            overflow: hidden;
        }

        .book-cover-placeholder::before {
            content: '';
            position: absolute;
            inset: 6px;
            border: 1px solid rgba(184,134,11,0.3);
            border-radius: 1px;
        }

        .book-cover-placeholder::after {
            content: '';
            position: absolute;
            inset: 10px;
            border: 1px solid rgba(184,134,11,0.15);
            border-radius: 1px;
        }

        .book-cover-placeholder .placeholder-title {
            font-family: var(--ff-heading);
            font-size: 0.8rem;
            font-weight: 700;
            color: var(--gold-pale);
            text-align: center;
            line-height: 1.3;
            position: relative;
            z-index: 1;
        }

        .book-cover-placeholder .placeholder-icon {
            font-size: 2.5rem;
            margin-bottom: 10px;
            filter: drop-shadow(0 2px 4px rgba(0,0,0,0.5));
        }

        .book-info {
            padding: 14px 16px 16px;
            flex: 1;
            display: flex;
            flex-direction: column;
        }

        .book-title {
            font-family: var(--ff-heading);
            font-size: 0.92rem;
            font-weight: 700;
            color: var(--mahogany);
            line-height: 1.3;
            margin-bottom: 5px;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .book-author {
            font-family: var(--ff-sub);
            font-style: italic;
            font-size: 0.82rem;
            color: var(--leather);
            margin-bottom: 8px;
        }

        .book-year {
            font-family: var(--ff-display);
            font-size: 0.65rem;
            font-weight: 600;
            letter-spacing: 0.12em;
            color: var(--gold);
            text-transform: uppercase;
            margin-top: auto;
        }

        /* ── CATEGORY BADGE ─────────────────────────────────────────── */
        .badge {
            display: inline-block;
            padding: 3px 10px;
            background: var(--mahogany);
            color: var(--gold-pale);
            font-family: var(--ff-display);
            font-size: 0.6rem;
            font-weight: 600;
            letter-spacing: 0.15em;
            text-transform: uppercase;
            border-radius: 2px;
        }

        .badge-outlined {
            background: transparent;
            color: var(--burgundy);
            border: 1px solid var(--burgundy);
        }

        /* ── SECTION BLOCK ─────────────────────────────────────────── */
        .section-block {
            padding: 56px 0;
        }

        .section-block + .section-block {
            border-top: 1px solid rgba(139,67,20,0.15);
        }

        /* ── RATING STARS ─────────────────────────────────────────── */
        .stars {
            color: var(--gold);
            font-size: 0.85rem;
            letter-spacing: 2px;
        }

        /* ── PAGINATION ──────────────────────────────────────────── */
        .pagination {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 8px;
            padding: 40px 0;
        }

        .pagination a,
        .pagination span {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 40px;
            height: 40px;
            font-family: var(--ff-display);
            font-size: 0.75rem;
            font-weight: 600;
            letter-spacing: 0.05em;
            border: 1px solid rgba(139,67,20,0.3);
            border-radius: 3px;
            text-decoration: none;
            color: var(--mahogany);
            transition: all 0.2s;
            background: #FEFAF2;
        }

        .pagination a:hover,
        .pagination span.current {
            background: var(--mahogany);
            color: var(--gold-light);
            border-color: var(--mahogany);
        }

        /* ── FOOTER ──────────────────────────────────────────────── */
        .site-footer {
            background: linear-gradient(0deg, #080202 0%, #1C0808 100%);
            border-top: 3px solid var(--gold);
            color: var(--parchment-dark);
            padding: 48px 0 28px;
        }

        .footer-inner {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 24px;
            display: grid;
            grid-template-columns: 1fr 1fr 1fr;
            gap: 40px;
        }

        .footer-col h4 {
            font-family: var(--ff-display);
            font-size: 0.72rem;
            font-weight: 700;
            letter-spacing: 0.25em;
            text-transform: uppercase;
            color: var(--gold);
            margin-bottom: 16px;
        }

        .footer-col p,
        .footer-col a {
            font-family: var(--ff-body);
            font-size: 0.9rem;
            color: rgba(242,232,213,0.55);
            line-height: 1.9;
            text-decoration: none;
            display: block;
            transition: color 0.2s;
        }

        .footer-col a:hover {
            color: var(--gold-light);
        }

        .footer-bottom {
            max-width: 1200px;
            margin: 32px auto 0;
            padding: 20px 24px 0;
            border-top: 1px solid rgba(184,134,11,0.2);
            text-align: center;
            font-family: var(--ff-display);
            font-size: 0.65rem;
            font-weight: 600;
            letter-spacing: 0.2em;
            text-transform: uppercase;
            color: rgba(184,134,11,0.4);
        }

        /* ── HERO BANNER ─────────────────────────────────────────── */
        .hero-banner {
            background:
                linear-gradient(180deg, rgba(12,4,2,0.7) 0%, rgba(26,8,8,0.55) 100%),
                url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='100' height='100'%3E%3Crect width='100' height='100' fill='%23180808'/%3E%3Cpath d='M0 50 L100 50 M50 0 L50 100' stroke='%23B8860B' stroke-width='0.2' opacity='0.1'/%3E%3Cpath d='M0 0 L100 100 M100 0 L0 100' stroke='%23B8860B' stroke-width='0.15' opacity='0.06'/%3E%3Ccircle cx='50' cy='50' r='30' fill='none' stroke='%23B8860B' stroke-width='0.2' opacity='0.07'/%3E%3C/svg%3E");
            padding: 80px 24px 60px;
            text-align: center;
            border-bottom: 2px solid var(--gold);
            position: relative;
        }

        .hero-banner::before {
            content: '— Est. MDCCCXLV —';
            display: block;
            font-family: var(--ff-display);
            font-size: 0.65rem;
            font-weight: 600;
            letter-spacing: 0.3em;
            color: rgba(184,134,11,0.5);
            text-transform: uppercase;
            margin-bottom: 20px;
        }

        .hero-title {
            font-family: var(--ff-heading);
            font-size: clamp(2rem, 5vw, 3.8rem);
            font-weight: 900;
            color: var(--parchment);
            line-height: 1.1;
            text-shadow: 0 2px 30px rgba(0,0,0,0.8);
            margin-bottom: 16px;
        }

        .hero-title em {
            font-style: italic;
            color: var(--gold-light);
        }

        .hero-lead {
            font-family: var(--ff-accent);
            font-style: italic;
            font-size: clamp(0.95rem, 1.5vw, 1.15rem);
            color: rgba(242,232,213,0.75);
            max-width: 560px;
            margin: 0 auto 36px;
            line-height: 1.8;
        }

        /* ── MAIN SEARCH FORM ────────────────────────────────────── */
        .hero-search {
            max-width: 620px;
            margin: 0 auto;
        }

        .hero-search-inner {
            display: flex;
            flex-direction: column;
            gap: 14px;
        }

        .search-input-group {
            display: flex;
            align-items: stretch;
            border: 2px solid var(--gold);
            border-radius: 4px;
            overflow: hidden;
            box-shadow: 0 4px 30px rgba(0,0,0,0.5), 0 0 0 1px rgba(184,134,11,0.2);
        }

        .search-input-group input[type="text"] {
            flex: 1;
            background: rgba(242,232,213,0.08);
            border: none;
            padding: 16px 20px;
            font-family: var(--ff-body);
            font-size: 1.05rem;
            color: var(--parchment);
            outline: none;
        }

        .search-input-group input::placeholder {
            color: rgba(242,232,213,0.35);
            font-style: italic;
        }

        .search-input-group button {
            background: var(--gold);
            border: none;
            padding: 16px 28px;
            cursor: pointer;
            font-family: var(--ff-display);
            font-size: 0.72rem;
            font-weight: 700;
            letter-spacing: 0.2em;
            text-transform: uppercase;
            color: var(--mahogany);
            transition: background 0.2s;
            white-space: nowrap;
        }

        .search-input-group button:hover {
            background: var(--gold-light);
        }

        .search-filters {
            display: flex;
            justify-content: center;
            gap: 10px;
            flex-wrap: wrap;
        }

        .filter-pill {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 6px 14px;
            border: 1px solid rgba(184,134,11,0.4);
            border-radius: 20px;
            background: rgba(184,134,11,0.08);
            color: rgba(242,232,213,0.7);
            font-family: var(--ff-display);
            font-size: 0.62rem;
            font-weight: 600;
            letter-spacing: 0.15em;
            text-transform: uppercase;
            cursor: pointer;
            transition: all 0.2s;
        }

        .filter-pill:hover,
        .filter-pill.active {
            background: var(--gold);
            color: var(--mahogany);
            border-color: var(--gold);
        }

        /* ── UTILITIES ───────────────────────────────────────────── */
        .text-center { text-align: center; }
        .mt-2 { margin-top: 8px; }
        .mt-4 { margin-top: 16px; }
        .mt-8 { margin-top: 32px; }
        .mb-4 { margin-bottom: 16px; }
        .py-4 { padding: 16px 0; }
        .py-8 { padding: 32px 0; }

        .alert {
            padding: 14px 20px;
            border-radius: 3px;
            font-family: var(--ff-body);
            margin-bottom: 20px;
        }

        .alert-warning {
            background: rgba(184,134,11,0.1);
            border: 1px solid rgba(184,134,11,0.4);
            color: var(--leather);
        }

        /* ── RESPONSIVE ──────────────────────────────────────────── */
        @media (max-width: 768px) {
            .footer-inner { grid-template-columns: 1fr; gap: 28px; }
            .nav-inner { flex-direction: column; gap: 0; }
            .global-search { display: none; }
            .books-grid { grid-template-columns: repeat(auto-fill, minmax(140px, 1fr)); gap: 18px; }
        }

        /* ── ANIMATIONS ──────────────────────────────────────────── */
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(18px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        .fade-in { animation: fadeInUp 0.6s ease both; }
        .fade-in-1 { animation-delay: 0.1s; }
        .fade-in-2 { animation-delay: 0.2s; }
        .fade-in-3 { animation-delay: 0.3s; }
        .fade-in-4 { animation-delay: 0.4s; }

        /* ── SCROLL BAR ──────────────────────────────────────────── */
        ::-webkit-scrollbar { width: 10px; }
        ::-webkit-scrollbar-track { background: var(--mahogany); }
        ::-webkit-scrollbar-thumb { background: var(--gold); border-radius: 5px; }
        ::-webkit-scrollbar-thumb:hover { background: var(--gold-light); }

        /* ── BACK-TO-TOP ─────────────────────────────────────────── */
        .back-top {
            position: fixed;
            bottom: 28px;
            right: 28px;
            width: 46px;
            height: 46px;
            background: var(--mahogany);
            border: 2px solid var(--gold);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--gold);
            font-size: 1.2rem;
            text-decoration: none;
            box-shadow: 0 4px 16px rgba(0,0,0,0.4);
            transition: all 0.2s;
            opacity: 0;
            pointer-events: none;
            z-index: 100;
        }

        .back-top.visible {
            opacity: 1;
            pointer-events: auto;
        }

        .back-top:hover {
            background: var(--gold);
            color: var(--mahogany);
            transform: translateY(-3px);
        }
    </style>

    @stack('styles')
</head>
<body>

<!-- ╔═══════════════════════════════════════════╗ -->
<!-- ║  SITE HEADER                              ║ -->
<!-- ╚═══════════════════════════════════════════╝ -->
<header class="site-header">
    <div class="header-crest">
        <svg class="crest-svg" viewBox="0 0 80 80" xmlns="http://www.w3.org/2000/svg">
            <!-- Outer ring -->
            <circle cx="40" cy="40" r="38" fill="none" stroke="#B8860B" stroke-width="1.5" opacity="0.7"/>
            <circle cx="40" cy="40" r="33" fill="none" stroke="#B8860B" stroke-width="0.7" opacity="0.4"/>
            <!-- Shield shape -->
            <path d="M40 10 L62 20 L62 44 Q62 60 40 70 Q18 60 18 44 L18 20 Z" fill="#1C0808" stroke="#B8860B" stroke-width="1.2"/>
            <!-- Book icon -->
            <rect x="28" y="30" width="11" height="18" rx="1" fill="none" stroke="#D4A632" stroke-width="1"/>
            <rect x="41" y="30" width="11" height="18" rx="1" fill="none" stroke="#D4A632" stroke-width="1"/>
            <line x1="39.5" y1="30" x2="39.5" y2="48" stroke="#D4A632" stroke-width="1"/>
            <line x1="28" y1="36" x2="39" y2="36" stroke="#D4A632" stroke-width="0.6" opacity="0.6"/>
            <line x1="28" y1="39" x2="39" y2="39" stroke="#D4A632" stroke-width="0.6" opacity="0.6"/>
            <line x1="28" y1="42" x2="39" y2="42" stroke="#D4A632" stroke-width="0.6" opacity="0.6"/>
            <line x1="41" y1="36" x2="52" y2="36" stroke="#D4A632" stroke-width="0.6" opacity="0.6"/>
            <line x1="41" y1="39" x2="52" y2="39" stroke="#D4A632" stroke-width="0.6" opacity="0.6"/>
            <line x1="41" y1="42" x2="52" y2="42" stroke="#D4A632" stroke-width="0.6" opacity="0.6"/>
            <!-- Stars -->
            <text x="40" y="26" text-anchor="middle" font-size="5" fill="#B8860B">★</text>
            <text x="30" y="57" text-anchor="middle" font-size="4" fill="#B8860B">✦</text>
            <text x="50" y="57" text-anchor="middle" font-size="4" fill="#B8860B">✦</text>
        </svg>

        <h1 class="site-title">Bibliotheca Classica</h1>
        <p class="site-subtitle">The Grand Literary Catalogue of Classical Works</p>
        <div class="header-rule"></div>
    </div>
</header>

<!-- ╔═══════════════════════════════════════════╗ -->
<!-- ║  NAVIGATION                               ║ -->
<!-- ╚═══════════════════════════════════════════╝ -->
<nav class="site-nav">
    <div class="nav-inner">
        <ul class="nav-links">
            <li><a href="{{ route('home') }}" class="{{ request()->routeIs('home') ? 'active' : '' }}">Grand Hall</a></li>
            <li><a href="{{ route('books.search', ['q' => 'victorian literature classic']) }}">Victorian</a></li>
            <li><a href="{{ route('books.search', ['q' => 'renaissance literature classic']) }}">Renaissance</a></li>
            <li><a href="{{ route('books.search', ['q' => 'ancient philosophy classic']) }}">Ancient</a></li>
            <li><a href="{{ route('books.search', ['q' => 'romantic poetry classic english']) }}">Romantic</a></li>
            <li><a href="{{ route('books.search', ['q' => 'gothic horror classic literature']) }}">Gothic</a></li>
        </ul>

        <div class="global-search">
            <form action="{{ route('books.search') }}" method="GET">
                <input type="text" name="q" placeholder="Search the catalogue…" value="{{ request('q') }}" />
                <button type="submit">Search</button>
            </form>
        </div>
    </div>
</nav>

<!-- ╔═══════════════════════════════════════════╗ -->
<!-- ║  PAGE CONTENT                             ║ -->
<!-- ╚═══════════════════════════════════════════╝ -->
<main class="page-content">
    @yield('content')
</main>

<!-- ╔═══════════════════════════════════════════╗ -->
<!-- ║  FOOTER                                   ║ -->
<!-- ╚═══════════════════════════════════════════╝ -->
<footer class="site-footer">
    <div class="footer-inner">
        <div class="footer-col">
            <h4>Bibliotheca Classica</h4>
            <p>A digital catalogue in the grand tradition of Birmingham's finest classical libraries, preserving access to the world's literary heritage.</p>
        </div>
        <div class="footer-col">
            <h4>Collections</h4>
            <a href="{{ route('books.search', ['q' => 'victorian classic']) }}">Victorian Literature</a>
            <a href="{{ route('books.search', ['q' => 'ancient greek philosophy']) }}">Ancient Philosophy</a>
            <a href="{{ route('books.search', ['q' => 'romantic poetry english']) }}">Romantic Poetry</a>
            <a href="{{ route('books.search', ['q' => 'gothic horror fiction']) }}">Gothic & Horror</a>
            <a href="{{ route('books.search', ['q' => 'renaissance masterworks']) }}">Renaissance Works</a>
        </div>
        <div class="footer-col">
            <h4>Data Sources</h4>
            <a href="https://books.google.com" target="_blank" rel="noopener">Google Books API</a>
            <a href="https://openlibrary.org" target="_blank" rel="noopener">Open Library API</a>
        </div>
    </div>
    <div class="footer-bottom">
        <div class="ornament" style="max-width:400px;margin:0 auto 10px;">✦ ✦ ✦</div>
        &copy; {{ date('Y') }} Bibliotheca Classica &nbsp;·&nbsp; Built with Laravel &nbsp;·&nbsp; Powered by Google Books &amp; Open Library
    </div>
</footer>

<a href="#" class="back-top" id="backTop" title="Return to summit">↑</a>

<script>
    // Back-to-top visibility
    const btn = document.getElementById('backTop');
    window.addEventListener('scroll', () => {
        btn.classList.toggle('visible', window.scrollY > 400);
    });
    btn.addEventListener('click', e => {
        e.preventDefault();
        window.scrollTo({ top: 0, behavior: 'smooth' });
    });

    // Filter pills toggle on search page
    document.querySelectorAll('.filter-pill[data-source]').forEach(pill => {
        pill.addEventListener('click', () => {
            document.querySelectorAll('.filter-pill[data-source]').forEach(p => p.classList.remove('active'));
            pill.classList.add('active');
            const form = pill.closest('form') || document.querySelector('form[data-search]');
            if (form) {
                const input = form.querySelector('input[name="source"]');
                if (input) input.value = pill.dataset.source;
            }
        });
    });
</script>

@stack('scripts')
</body>
</html>
