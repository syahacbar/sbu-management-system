<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Cetak SBU - {{ $application->name }}</title>
    @vite(['resources/css/app.css'])
    <style>
        /* CSS A4 Print Layout */
        body {
            margin: 0;
            padding: 0;
            background-color: #f1f5f9;
            font-family: ui-sans-serif, system-ui, sans-serif, "Apple Color Emoji", "Segoe UI Emoji", "Segoe UI Symbol", "Noto Color Emoji";
        }

        /* Top navigation header */
        .preview-header {
            background-color: #0f172a;
            color: #ffffff;
            padding: 12px 24px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 0;
            z-index: 50;
            box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1);
        }

        /* Printable area wrapper */
        .print-container {
            display: flex;
            justify-content: center;
            padding: 40px 20px;
        }

        /* High-fidelity A4 page simulation */
        .a4-page {
            background-color: #ffffff;
            width: 210mm;
            min-height: 297mm;
            padding: 20mm;
            box-shadow: 0 10px 15px -3px rgb(0 0 0 / 0.1), 0 4px 6px -4px rgb(0 0 0 / 0.1);
            box-sizing: border-box;
            position: relative;
            display: flex;
            flex-direction: column;
            border: 1px solid #e2e8f0;
        }

        /* Ornate certificate border */
        .certificate-border {
            position: absolute;
            top: 8mm;
            left: 8mm;
            right: 8mm;
            bottom: 8mm;
            border: 4px double #047857; /* Emerald green double border */
            pointer-events: none;
            box-sizing: border-box;
        }

        /* Letterhead / Kop Surat styling */
        .letterhead {
            border-bottom: 3px double #0f172a;
            padding-bottom: 12px;
            margin-bottom: 24px;
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .letterhead-logo {
            max-height: 70px;
            max-width: 120px;
            object-fit: contain;
        }

        .letterhead-text {
            flex-1: 1 0 0%;
            text-align: center;
        }

        .letterhead-title {
            font-size: 16px;
            font-weight: 800;
            letter-spacing: 0.5px;
            text-transform: uppercase;
            margin: 0;
            color: #0f172a;
        }

        .letterhead-subtitle {
            font-size: 11px;
            margin: 4px 0 0 0;
            color: #475569;
            line-height: 1.4;
        }

        /* Content block */
        .document-body {
            flex-grow: 1;
            font-size: 13px;
            line-height: 1.6;
            color: #0f172a;
        }

        /* Footer block */
        .document-footer {
            margin-top: 30px;
            border-top: 1px solid #e2e8f0;
            padding-top: 12px;
            font-size: 10px;
            color: #64748b;
            text-align: center;
        }

        /* Toast styles */
        #toast {
            position: fixed;
            bottom: 24px;
            right: 24px;
            background-color: #1e293b;
            color: #ffffff;
            padding: 12px 24px;
            border-radius: 8px;
            box-shadow: 0 10px 15px -3px rgb(0 0 0 / 0.2);
            font-size: 14px;
            font-weight: 600;
            display: none;
            z-index: 100;
            border-left: 4px solid #10b981;
        }

        /* Print Media overrides */
        @media print {
            body {
                background-color: #ffffff !important;
            }
            .preview-header {
                display: none !important;
            }
            .print-container {
                padding: 0 !important;
            }
            .a4-page {
                box-shadow: none !important;
                border: none !important;
                width: 210mm;
                height: 297mm;
                padding: 20mm;
                page-break-after: always;
            }
        }
    </style>
</head>
<body>

    <!-- Header Panel (Non-Printable) -->
    <header class="preview-header no-print">
        <div class="flex items-center gap-3">
            <span class="inline-flex h-9 w-9 items-center justify-center rounded-lg bg-emerald-600 font-bold text-white text-sm">SBU</span>
            <div>
                <h1 class="text-sm font-bold text-white leading-none">Pratinjau Dokumen SBU</h1>
                <p class="text-[10px] text-slate-400 mt-1">Perusahaan: {{ $company->name }}</p>
            </div>
        </div>

        <div class="flex items-center gap-2">
            <!-- Save to Archive Button -->
            <button
                id="btn-archive"
                onclick="saveToArchive()"
                class="inline-flex items-center gap-1.5 rounded-md bg-slate-800 border border-slate-700 px-4 py-2 text-xs font-semibold text-white shadow-sm transition hover:bg-slate-700"
            >
                <svg class="h-4 w-4 text-emerald-400" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 16.5V9.75m0 0l3 3m-3-3l-3 3M6.75 19.5a4.5 4.5 0 01-1.41-8.775 5.25 5.25 0 0110.233-2.33 3 3 0 013.758 3.848A3.752 3.752 0 0118 19.5H6.75z" />
                </svg>
                Simpan ke Arsip Perusahaan
            </button>

            <!-- Print Button -->
            <button
                onclick="window.print()"
                class="inline-flex items-center gap-1.5 rounded-md bg-emerald-600 px-4 py-2 text-xs font-semibold text-white shadow-sm transition hover:bg-emerald-700"
            >
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6.72 13.829c-.24.03-.48.062-.72.096m.72-.096a42.415 42.415 0 0110.56 0m-10.56 0L6.34 18m10.94-4.171c.24.03.48.062.72.096m-.72-.096L17.66 18m0 0a42.27 42.27 0 00-11.32 0M19 9l-1.5-2-1.5-2H8L5 9m14 0v3.829a1.5 1.5 0 01-.073.469c-.283.743-1.015 1.258-1.81 1.258H4.884c-.796 0-1.528-.515-1.81-1.258A1.5 1.5 0 013 12.83V9m16 0H3" />
                </svg>
                Cetak ke PDF / Kertas (Ctrl+P)
            </button>

            <!-- Close Button -->
            <button
                onclick="window.close()"
                class="inline-flex items-center gap-1.5 rounded-md border border-slate-700 bg-transparent px-4 py-2 text-xs font-semibold text-slate-300 hover:text-white transition"
            >
                Tutup
            </button>
        </div>
    </header>

    <!-- Main Container -->
    <div class="print-container">
        <!-- A4 Mock Sheet -->
        <article class="a4-page relative" id="rendered-document">
            
            <!-- Emerald double border for certificates -->
            <div class="certificate-border"></div>

            <!-- Letterhead / Kop Surat -->
            @if ($template->header_text || $template->logo_path)
                <div class="letterhead">
                    @if ($template->logo_path)
                        <img src="{{ asset('storage/' . $template->logo_path) }}" class="letterhead-logo" alt="Logo">
                    @endif
                    <div class="letterhead-text">
                        <h2 class="letterhead-title">{{ $template->header_text ?: 'Pemerintah Republik Indonesia' }}</h2>
                        <p class="letterhead-subtitle">Sistem Informasi Manajemen Sertifikat SBU Elektronik Terintegrasi</p>
                    </div>
                </div>
            @endif

            <!-- HTML Main Body -->
            <div class="document-body">
                {!! $renderedBody !!}
            </div>

            <!-- Footer Text -->
            @if ($template->footer_text)
                <footer class="document-footer">
                    {{ $template->footer_text }}
                </footer>
            @endif

        </article>
    </div>

    <!-- Alert Toast -->
    <div id="toast"></div>

    <!-- AJAX Script to Save to Archive -->
    <script>
        function saveToArchive() {
            const btn = document.getElementById('btn-archive');
            const originalHTML = btn.innerHTML;
            
            // Disable button during AJAX
            btn.disabled = true;
            btn.innerHTML = '<span class="inline-flex items-center gap-1"><span class="h-3 w-3 animate-spin rounded-full border-2 border-white border-t-transparent"></span> Menyimpan...</span>';

            // Get CSRF Token
            const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            
            // Get full A4 Page HTML including inline styling
            const element = document.getElementById('rendered-document');
            const innerHTML = element.outerHTML;

            const payload = {
                name: 'Arsip Sertifikat SBU - {{ $application->name }}',
                code: '{{ $application->code ?: 'SBU-' . strtoupper(Str::random(6)) }}',
                html: innerHTML
            };

            fetch('{{ route("companies.workspace.generate.save-archive", $company) }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': token,
                    'Accept': 'application/json'
                },
                body: JSON.stringify(payload)
            })
            .then(response => response.json())
            .then(data => {
                showToast(data.message || 'Sertifikat berhasil diarsipkan.');
                btn.innerHTML = '✓ Tersimpan di Arsip';
                btn.classList.remove('bg-slate-800', 'hover:bg-slate-700');
                btn.classList.add('bg-emerald-800', 'cursor-default');
            })
            .catch(error => {
                console.error('Error:', error);
                showToast('Gagal mengarsipkan sertifikat: Terjadi kesalahan server.');
                btn.disabled = false;
                btn.innerHTML = originalHTML;
            });
        }

        function showToast(message) {
            const toast = document.getElementById('toast');
            toast.innerText = message;
            toast.style.display = 'block';
            setTimeout(() => {
                toast.style.display = 'none';
            }, 3000);
        }
    </script>
</body>
</html>
