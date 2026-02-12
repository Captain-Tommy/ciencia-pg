@php /** @var \Ciencia\DomainObjects\OrderDomainObject $order */ @endphp
@php /** @var \Ciencia\DomainObjects\EventDomainObject $event */ @endphp
@php /** @var \Ciencia\DomainObjects\OrganizerDomainObject $organizer */ @endphp
@php /** @var \Ciencia\Values\MoneyValue $refundAmount */ @endphp
@php /** @var \Ciencia\DomainObjects\EventSettingDomainObject $eventSettings */ @endphp

@php /** @see \Ciencia\Mail\Order\OrderRefunded */ @endphp

<x-mail::message>
{{ __('Hello') }},

{{ __('You have received a refund of :refundAmount for the following event: :eventTitle.', ['refundAmount' => $refundAmount, 'eventTitle' => $event->getTitle()]) }}

{{ __('Thank you') }},<br>
{{ $organizer->getName() ?: config('app.name') }}

{!! $eventSettings->getGetEmailFooterHtml() !!}
</x-mail::message>
