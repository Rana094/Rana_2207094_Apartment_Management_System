<?php

namespace App\Enums;

/**
 * Allowed categories for uploaded resident/maintenance documents.
 */
enum DocumentType: string
{
    case NationalId = 'national_id';
    case LeaseAgreement = 'lease_agreement';
    case OwnershipProof = 'ownership_proof';
    case PaymentProof = 'payment_proof';
    case WorkCompletionProof = 'work_completion_proof';
    case Other = 'other';
}
