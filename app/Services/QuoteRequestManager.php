<?php

namespace App\Services;

use App\Models\Contact;
use App\Models\QuoteRequest;
use App\Models\QuoteRequestNote;
use App\Models\QuoteRequestService;
use App\Models\QuoteStatus;
use App\Models\QuoteStatusHistory;
use App\Models\Service;
use App\Models\UserAccount;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;

class QuoteRequestManager
{
    public function __construct(
        private readonly FolioService $folioService
    ) {
    }

    public function createQuoteRequest(
        array $contactData,
        array $quoteData,
        array $serviceIds
    ): QuoteRequest {
        return DB::transaction(function () use (
            $contactData,
            $quoteData,
            $serviceIds
        ): QuoteRequest {
            $contact = $this->resolveContact($contactData);

            $newStatus = QuoteStatus::query()
                ->where('code', 'New')
                ->where('isActive', true)
                ->firstOrFail();

            $validatedServiceIds = $this->validateServiceIds(
                $serviceIds
            );

            $folio = $this->folioService
                ->nextQuoteFolioWithinTransaction();

            $quoteRequest = QuoteRequest::create([
                'folio' => $folio,
                'contactId' => $contact->contactId,
                'description' => $quoteData['description'] ?? null,
                'locationCity' => $quoteData['locationCity'] ?? null,
                'locationState' => $quoteData['locationState'] ?? null,
                'locationNeighborhood' => $quoteData['locationNeighborhood'] ?? null,
                'preferredTimeframeId' => $quoteData['preferredTimeframeId'] ?? null,
                'referenceProjectId' => $quoteData['referenceProjectId'] ?? null,
                'sourcePage' => $quoteData['sourcePage'] ?? null,
                'sourceUrl' => $quoteData['sourceUrl'] ?? null,
                'quoteStatusId' => $newStatus->quoteStatusId,
            ]);

            foreach ($validatedServiceIds as $serviceId) {
                QuoteRequestService::create([
                    'quoteRequestId' => $quoteRequest->quoteRequestId,
                    'serviceId' => $serviceId,
                ]);
            }

            QuoteStatusHistory::create([
                'quoteRequestId' => $quoteRequest->quoteRequestId,
                'quoteStatusId' => $newStatus->quoteStatusId,
                'changedBy' => null,
            ]);

            return $quoteRequest->load([
                'contact',
                'preferredTimeframe',
                'referenceProject',
                'status',
                'serviceLinks.service',
                'statusHistory.status',
            ]);
        }, 3);
    }

    public function changeStatus(
        string $quoteRequestId,
        string $statusCode,
        ?string $changedBy = null
    ): QuoteRequest {
        return DB::transaction(function () use (
            $quoteRequestId,
            $statusCode,
            $changedBy
        ): QuoteRequest {
            $quoteRequest = QuoteRequest::query()
                ->where('quoteRequestId', $quoteRequestId)
                ->lockForUpdate()
                ->firstOrFail();

            $newStatus = QuoteStatus::query()
                ->where('code', $statusCode)
                ->where('isActive', true)
                ->firstOrFail();

            if ($changedBy !== null) {
                $this->validateActiveUser($changedBy);
            }

            if ($quoteRequest->quoteStatusId !== $newStatus->quoteStatusId) {
                $quoteRequest->quoteStatusId = $newStatus->quoteStatusId;
                $quoteRequest->save();

                QuoteStatusHistory::create([
                    'quoteRequestId' => $quoteRequest->quoteRequestId,
                    'quoteStatusId' => $newStatus->quoteStatusId,
                    'changedBy' => $changedBy,
                ]);
            }

            return $quoteRequest->fresh([
                'contact',
                'status',
                'statusHistory.status',
                'statusHistory.changedByUser',
            ]);
        }, 3);
    }

    public function addInternalNote(
        string $quoteRequestId,
        string $noteText,
        ?string $userId = null
    ): QuoteRequestNote {
        $noteText = trim($noteText);

        if ($noteText === '') {
            throw new InvalidArgumentException(
                'La nota interna no puede estar vacía.'
            );
        }

        if ($userId !== null) {
            $this->validateActiveUser($userId);
        }

        return DB::transaction(function () use (
            $quoteRequestId,
            $noteText,
            $userId
        ): QuoteRequestNote {
            $quoteRequest = QuoteRequest::query()
                ->where('quoteRequestId', $quoteRequestId)
                ->lockForUpdate()
                ->firstOrFail();

            return $quoteRequest->notes()->create([
                'userId' => $userId,
                'noteText' => $noteText,
            ]);
        }, 3);
    }

    private function resolveContact(array $contactData): Contact
    {
        $contact = null;

        if (!empty($contactData['contactId'])) {
            $contact = Contact::query()->find(
                $contactData['contactId']
            );
        }

        if (
            $contact === null &&
            !empty($contactData['email'])
        ) {
            $contact = Contact::query()
                ->where('email', $contactData['email'])
                ->first();
        }

        if (
            $contact === null &&
            !empty($contactData['whatsAppNumber'])
        ) {
            $contact = Contact::query()
                ->where(
                    'whatsAppNumber',
                    $contactData['whatsAppNumber']
                )
                ->first();
        }

        if (
            $contact === null &&
            !empty($contactData['phoneNumber'])
        ) {
            $contact = Contact::query()
                ->where(
                    'phoneNumber',
                    $contactData['phoneNumber']
                )
                ->first();
        }

        $attributes = [
            'firstName' => $contactData['firstName'] ?? null,
            'lastName' => $contactData['lastName'] ?? null,
            'phoneNumber' => $contactData['phoneNumber'] ?? null,
            'whatsAppNumber' => $contactData['whatsAppNumber'] ?? null,
            'email' => $contactData['email'] ?? null,
            'preferredContactMethodId' => $contactData['preferredContactMethodId'] ?? null,
        ];

        $attributes = array_filter(
            $attributes,
            static fn (mixed $value): bool =>
                $value !== null && $value !== ''
        );

        if ($contact !== null) {
            $contact->fill($attributes);
            $contact->save();

            return $contact;
        }

        if (empty($attributes['firstName'])) {
            throw new InvalidArgumentException(
                'El nombre del contacto es obligatorio.'
            );
        }

        return Contact::create($attributes);
    }

    private function validateServiceIds(array $serviceIds): array
    {
        $serviceIds = array_values(
            array_unique(
                array_filter($serviceIds)
            )
        );

        if ($serviceIds === []) {
            throw new InvalidArgumentException(
                'La solicitud debe incluir al menos un servicio.'
            );
        }

        $existingServiceIds = Service::query()
            ->whereIn('serviceId', $serviceIds)
            ->pluck('serviceId')
            ->all();

        if (count($existingServiceIds) !== count($serviceIds)) {
            throw new InvalidArgumentException(
                'Uno o más servicios seleccionados no existen.'
            );
        }

        return $serviceIds;
    }

    private function validateActiveUser(string $userId): void
    {
        $exists = UserAccount::query()
            ->where('userId', $userId)
            ->where('isActive', true)
            ->exists();

        if (!$exists) {
            throw new InvalidArgumentException(
                'El usuario administrativo no existe o está inactivo.'
            );
        }
    }
}
