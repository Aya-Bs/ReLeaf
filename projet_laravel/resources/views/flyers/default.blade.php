<!doctype html>
<html>

<head>
    <meta charset="utf-8">
    <style>
        @page {
            margin: 0;
        }

        body {
            margin: 0;
            font-family: Arial, Helvetica, sans-serif;
        }

        .flyer {
            position: relative;
            width: 210mm;
            height: 297mm;
            overflow: hidden;
        }

        .bg {
            position: absolute;
            inset: 0;
            background: #e9f5ee;
        }

        .bg img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            filter: saturate(1.05) contrast(1.05);
        }

        .overlay {
            position: absolute;
            inset: 0;
            background: linear-gradient(180deg, rgba(0, 0, 0, 0.15) 0%, rgba(0, 0, 0, 0.35) 100%);
        }

        .content {
            position: absolute;
            inset: 0;
            display: flex;
            flex-direction: column;
            justify-content: flex-end;
            padding: 28mm;
            color: #fff;
        }

        .title {
            font-size: 32pt;
            font-weight: 800;
            line-height: 1.1;
            margin: 0 0 6mm 0;
        }

        .tagline {
            font-size: 16pt;
            font-weight: 600;
            margin: 0 0 10mm 0;
            opacity: .95;
        }

        .meta {
            display: flex;
            gap: 10mm;
            font-size: 12pt;
        }

        .meta div {
            display: flex;
            align-items: center;
            gap: 4mm;
        }

        .pill {
            display: inline-block;
            padding: 3mm 6mm;
            border-radius: 999px;
            font-size: 10pt;
            background: rgba(0, 0, 0, 0.35);
            margin-top: 8mm;
        }

        .quote {
            font-style: italic;
            opacity: .95;
            font-size: 13pt;
            margin: 0 0 6mm 0;
        }
    </style>
</head>

<body>
    <div class="flyer">
        <div class="bg">
            @if(!empty($bgRel))
            @php $usePublic = config('flyer.save_to_public', true); @endphp
            @if($usePublic)
            <img src="{{ public_path(ltrim($bgRel, '/')) }}" alt="bg">
            @else
            <img src="{{ public_path('storage/' . ltrim($bgRel, '/')) }}" alt="bg">
            @endif
            @endif
        </div>
        <div class="overlay"></div>
        <div class="content">
            <h1 class="title">{{ $event->title }}</h1>
            @if(!empty($quote))
            <div class="quote">“{{ $quote }}”</div>
            @endif
            @if(!empty($tagline))
            <div class="tagline">{{ $tagline }}</div>
            @endif
            <div class="meta">
                @if($event->date)
                <div>
                    <strong>Date</strong>
                    <span>{{ !empty($dateText) ? $dateText : $event->date->format('d/m/Y H:i') }}</span>
                </div>
                @endif
                @if($event->location)
                <div>
                    <strong>Lieu</strong>
                    <span>{{ $event->location->name }}</span>
                </div>
                @endif
            </div>
            <div class="pill">Inscrivez‑vous sur {{ config('app.name') }}</div>
        </div>
    </div>
</body>

</html>