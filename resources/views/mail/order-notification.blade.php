{{-- @formatter:off --}}
@extends('mail.notification')

<style>
    .user-content {
        margin-bottom: 2em;
    }
    .user-content > p {
        margin-bottom: 0.3em;
        font-weight: bold;
    }
    .user-content pre {
        /* Inherit-declarations are required to override some user-agent stylesheet, e.g. when viewing e-mails using webmail. */
        font-family: inherit;
        color: inherit;
        font-size: 15px; /* Decreases font size a little bit. Default size from theme is 16px. */
        white-space: pre-wrap;
        margin: 0 1em;
        background: #f5f7fa;
        padding: 0.3em 0.5em;
    }
    .user-content code {
        font-family: inherit;
    }
</style>

@section('outro-lines')
@parent

<div class="user-content">
<p>Veranstaltungsname / Verwendungszweck</p>

    {!! str_replace("\n", "\n\t", $order->event_name) !!}{{-- IMPORTANT: user content must be indentated to have Markdown parser treat it as code block. Otherwise, Markdown and HTML injections are possible. --}}

</div>

@if($order->note)
<div class="user-content">
<p>Anmerkung</p>

    {!! str_replace("\n", "\n\t", $order->note) !!}{{-- IMPORTANT: user content must be indentated to have Markdown parser treat it as code block. Otherwise, Markdown and HTML injections are possible. --}}

</div>
@endif
@endsection
