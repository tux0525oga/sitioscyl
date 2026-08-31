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
use Illuminate\Support\Str;
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

            $validatedServiceIds = $this->validateServiceIds($serviceIds);
            $folio = $this->folioService->nextQuoteFolioWithinTransaction();

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
        $attributes = [
            'firstName' => $this->cleanText($contactData['firstName'] ?? null),
            'lastName' => $this->cleanText($contactData['lastName'] ?? null),
            'phoneNumber' => $this->cleanText($contactData['phoneNumber'] ?? null),
            'whatsAppNumber' => $this->cleanText($contactData['whatsAppNumber'] ?? null),
            'email' => $this->cleanEmail($contactData['email'] ?? null),
            'preferredContactMethodId' =>
                $contactData['preferredContactMethodId'] ?? null,
        ];

        $attributes = array_filter(
            $attributes,
            static fn (mixed $value): bool =>
                $value !== null && $value !== ''
        );

        if (empty($attributes['firstName'])) {
            throw new InvalidArgumentException(
                'El nombre del contacto es obligatorio.'
            );
        }

        if (!empty($contactData['contactId'])) {
            $contact = Contact::query()->find($contactData['contactId']);

            if ($contact !== null) {
                $contact->fill($attributes);
                $contact->save();

                return $contact;
            }
        }

        $contact = $this->findMatchingContact($attributes);

        if ($contact !== null) {
            $contact->fill($attributes);
            $contact->save();

            return $contact;
        }

        return Contact::create($attributes);
    }

    private function findMatchingContact(array $attributes): ?Contact
    {
        $email = $attributes['email'] ?? null;
        $whatsAppNumber = $attributes['whatsAppNumber'] ?? null;
        $phoneNumber = $attributes['phoneNumber'] ?? null;

        if (
            $email === null
            && $whatsAppNumber === null
            && $phoneNumber === null
        ) {
            return null;
        }

        $candidates = Contact::query()
            ->where(function ($query) use (
                $email,
                $whatsAppNumber,
                $phoneNumber
            ): void {
                $hasCondition = false;

                if ($email !== null) {
                    $query->where('email', $email);
                    $hasCondition = true;
                }

                if ($whatsAppNumber !== null) {
                    if ($hasCondition) {
                        $query->orWhere('whatsAppNumber', $whatsAppNumber);
                    } else {
                        $query->where('whatsAppNumber', $whatsAppNumber);
                        $hasCondition = true;
                    }
                }

                if ($phoneNumber !== null) {
                    if ($hasCondition) {
                        $query->orWhere('phoneNumber', $phoneNumber);
                    } else {
                        $query->where('phoneNumber', $phoneNumber);
                    }
                }
            })
            ->get();

        return $candidates->first(
            fn (Contact $candidate): bool =>
                $this->contactNamesMatch($candidate, $attributes)
        );
    }

    private function contactNamesMatch(
        Contact $contact,
        array $attributes
    ): bool {
        return $this->normalizeName($contact->firstName)
            === $this->normalizeName($attributes['firstName'] ?? null)
            && $this->normalizeName($contact->lastName)
            === $this->normalizeName($attributes['lastName'] ?? null);
    }

    private function normalizeName(?string $value): string
    {
        if ($value === null) {
            return '';
        }

        $value = preg_replace('/\s+/u', ' ', trim($value));

        return Str::lower(Str::ascii($value ?? ''));
    }

    private function cleanText(mixed $value): ?string
    {
        if ($value === null) {
            return null;
        }

        $value = trim((string) $value);

        return $value !== '' ? $value : null;
    }

    private function cleanEmail(mixed $value): ?string
    {
        $value = $this->cleanText($value);

        return $value !== null
            ? Str::lower($value)
            : null;
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