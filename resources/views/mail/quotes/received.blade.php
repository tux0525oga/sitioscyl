<x-mail::message>
# Nueva solicitud de cotización

Se recibió una nueva solicitud desde **somosconstructivos.com**.

**Folio:** {{ $quoteRequest->folio }}

**Fecha:** {{ $quoteRequest->createdAt?->format('d/m/Y H:i') }}

## Cliente

**Nombre:** {{ trim(
    ($quoteRequest->contact?->firstName ?? '')
    . ' '
    . ($quoteRequest->contact?->lastName ?? '')
) }}

@if($quoteRequest->contact?->whatsAppNumber)
**WhatsApp:** {{ $quoteRequest->contact->whatsAppNumber }}
@endif

@if($quoteRequest->contact?->phoneNumber)
**Teléfono:** {{ $quoteRequest->contact->phoneNumber }}
@endif

@if($quoteRequest->contact?->email)
**Correo:** {{ $quoteRequest->contact->email }}
@endif

## Ubicación

{{ $quoteRequest->locationNeighborhood
    ? $quoteRequest->locationNeighborhood . ', '
    : '' }}{{ $quoteRequest->locationCity }}, {{ $quoteRequest->locationState }}

## Servicios solicitados

@foreach($quoteRequest->serviceLinks as $serviceLink)
- {{ $serviceLink->service?->name ?? 'Servicio' }}
@endforeach

## Descripción

{{ $quoteRequest->description }}

@if($quoteRequest->files->count() > 0)
**Archivos adjuntos en la solicitud:** {{ $quoteRequest->files->count() }}

Los archivos pueden consultarse desde el administrador.
@endif

<x-mail::button :url="$adminUrl">
Ver cotización en administrador
</x-mail::button>

Tu proyecto, nuestro compromiso!

**Somos Constructivos**
</x-mail::message>