<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Report {{ $report->serial_number }}</title>
    <style>
        body {
            font-family: 'figtree', sans-serif;
            font-size: 14px;
            line-height: 1.5;
        }

        body, h4, p {
            margin: 0;
        }

        .text-3xl {
            font-size: 1.5rem;
            line-height: 1.75rem;
            font-weight: 700;
        }

        .text-gray-600 {
            color: #718096;
        }

        .block {
            display: block;
        }

        .text-sm {
            font-size: 0.875rem;
            line-height: 1.25rem;
            font-weight: 600;
        }

        .font-sans {
            font-family: ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, "Noto Sans", sans-serif;
        }

        .mb-4 {
            margin-bottom: 1rem;
        }

        .report-tag {
            display: inline-block;
            padding: 0.25rem 0;
            margin-right: 0.5rem;
            font-size: 0.875rem;
            font-weight: 600;
            color: #4a5568;
        }
    </style>
</head>
<body class="font-sans">
<div>
    <h1 class="text-3xl font-bold mb-4">Serial Number - {{ $report->serial_number }}</h1>
    @if(count($report->tags) > 0)
        <div class="mb-4">
                @foreach($report->tags as $tag)
                    <span class="report-tag">#{{ $tag->title }}</span>
                @endforeach
        </div>
    @endif

    @if($report->shift)
        <div class="mb-4">
            <h4>Shift</h4>
            <p class="text-gray-600 text-sm">{{ $report->shift }}</p>
        </div>
    @endif

    <div class="mb-4">
        <h4>Venue</h4>
        <p class="text-gray-600 text-sm">{{ $report->venue ?? 'NA' }}</p>
    </div>
    <div class="mb-4">
        <h4>Dealing Officer</h4>
        <p class="text-gray-600 text-sm">{{ $report->dealing_officer ?? 'NA' }}</p>
    </div>

    <div class="mb-4">
        <h4>Description</h4>
        <p class="text-gray-600 text-sm">{{ $report->description ?? 'NA' }}</p>
    </div>

    <div class="mb-4">
        <h4>Approved</h4>
        <p class="text-gray-600 text-sm">{{ $report->approved? "APPROVED" : 'PENDING' }}</p>
    </div>

    <div class="mb-4">
        <h4>Attachments</h4>
        @if(count($report->attachments) > 0)
            @foreach($report->attachments as $attachment)
                <a href="{{ $attachment->path }}" target="_blank" class="block text-gray-600 text-sm">{{ $attachment->name }}</a>
            @endforeach
        @else
            <span class="block text-gray-600 text-sm">NA</span>
        @endif
    </div>
</div>
</body>
</html>
