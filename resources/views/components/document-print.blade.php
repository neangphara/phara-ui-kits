@props([
    'title' => 'Document',
    'pageSize' => 'A4',         // Options: A4, Letter, Legal, etc.
    'orientation' => 'portrait', // Options: portrait, landscape
    'margin' => '20mm',
    'autoPrint' => false,        // Set to true to open print dialog automatically
])

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title }}</title>

    <!-- Paged.js Polyfill -->
    <script src="https://unpkg.com/pagedjs/dist/paged.polyfill.js"></script>

    {{ $links ?? '' }}

    <style>
        /* ==========================================================================
           Paged Media Setup (Print Rules)
           ========================================================================== */
        @page {
            size: {{ $pageSize }} {{ $orientation }};
            margin: {{ $margin }};

            /* Bottom Right Footer: Page Numbers */
            @bottom-right {
                content: "Page " counter(page) " of " counter(pages);
                font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
                font-size: 10px;
                color: #666;
            }

            /* Bottom Left Footer: Document Title */
            @bottom-left {
                content: "{{ $title }}";
                font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
                font-size: 10px;
                color: #666;
            }
        }

        /* First page without headers/footers (optional, good for cover pages) */
        /* @page:first {
            @bottom-right { content: none; }
            @bottom-left { content: none; }
        } */

        /* ==========================================================================
           Document Typography & Aesthetics
           ========================================================================== */
        :root {
            --primary-color: #2c3e50;
            --text-color: #333333;
        }

        body {
            font-family: 'Georgia', serif;
            line-height: 1.6;
            color: var(--text-color);
        }

        h1, h2, h3, h4, h5, h6 {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            color: var(--primary-color);
            break-after: avoid; /* Prevent title at the bottom of a page */
            page-break-after: avoid;
            margin-top: 1.5em;
        }

        p, ul, ol {
            orphans: 3; /* Minimum lines left at the bottom of a page */
            widows: 3;  /* Minimum lines pushed to the top of the next page */
        }

        /* Table formatting for print */
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 1em 0;
            break-inside: auto;
        }
        
        tr {
            break-inside: avoid; /* Prevent rows splitting across pages */
            page-break-inside: avoid;
        }

        th, td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
        }

        th {
            background-color: #f8f9fa;
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
        }

        /* Utility classes for manual page breaks */
        .page-break-before { break-before: page; }
        .page-break-after { break-after: page; }

        /* ==========================================================================
           Screen Preview Styling (Makes it look like a PDF viewer in the browser)
           ========================================================================== */
        @media screen {
            body {
                background-color: #e5e5f7;
                background-image: radial-gradient(#cbd5e1 1px, transparent 1px);
                background-size: 20px 20px;
                margin: 0;
            }
            .pagedjs_pages {
                display: flex;
                flex-direction: column;
                align-items: center;
                gap: 24px;
                padding: 24px;
            }
            .pagedjs_page {
                background: white;
                box-shadow: 0 10px 25px rgba(0,0,0,0.15);
                margin: 0 !important; /* Override Paged.js default margins */
            }
        }

        /* Inject Custom Styles passed to the component */
        {{ $styles ?? '' }}
    </style>
</head>
<body>
    
    <div class="content">
        {{ $slot }}
    </div>

    @if($autoPrint)
    <script>
        // Hook into Paged.js lifecycle to print after rendering finishes
        class AutoPrintHandler extends Paged.Handler {
            afterRendered(pages) {
                window.print();
            }
        }
        Paged.registerHandlers(AutoPrintHandler);
    </script>
    @endif

</body>
</html>