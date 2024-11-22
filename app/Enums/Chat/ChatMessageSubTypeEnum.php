<?php

namespace App\Enums\Chat;

use App\Traits\EnumTrait;

enum ChatMessageSubTypeEnum: string
{
    use EnumTrait;

    /**
     * Clinical director.
     */
    case CLINICAL_DIRECTOR_CHECK_SENT_TO_VERIFICATION = 'clinical-director-check-sent-to-verification';
    case CLINICAL_DIRECTOR_CHECK_RECALLED_FROM_DOCTOR = 'clinical-director-check-recalled-from-doctor';
    case CLINICAL_DIRECTOR_CHECK_REJECTED = 'clinical-director-check-rejected';

    /**
     * Clinical specialist.
     */
    case CLINICAL_SPECIALIST_CHECK_SENT_TO_VERIFICATION = 'clinical-specialist-check-sent-to-verification';
    case CLINICAL_SPECIALIST_CHECK_REJECTED = 'clinical-specialist-check-rejected';

    /**
     * Doctor.
     */
    case DOCTOR_HAVE_PROBLEM = 'doctor-have-problem';
    case DOCTOR_PRODUCTION_REQUIRE_NEXT = 'doctor-production-require-next';
    case DOCTOR_PRODUCTION_NEED_MORE_STEPS = 'doctor-production-need-more-steps';
    case DOCTOR_TAKE_CASTS = 'doctor-take-casts';
    case DOCTOR_CHECK_ACCEPTED = 'doctor-check-accepted';
    case DOCTOR_CHECK_REJECTED = 'doctor-check-rejected';

    /**
     * 3D modeler.
     */
    case MODELER_3D_CHECK_SENT_TO_VERIFICATION = 'modeler-3d-check-sent-to-verification';

    /**
     * Stage
     */
    case STAGE_TO_DRAFT_FROM_VERIFICATION = 'stage-to-draft-from-verification';
    case STAGE_TO_VERIFICATION = 'stage-to-verification';
    case STAGE_TO_VERIFICATION_FROM_PREPARATION = 'stage-to-verification-from-preparation';
    case STAGE_TO_PREPARATION = 'stage-to-preparation';
    case STAGE_TO_PREPARATION_FROM_MODELING = 'stage-to-preparation-from-modeling';
    case STAGE_TO_MODELING = 'stage-to-modeling';
    case STAGE_TO_MODELING_FROM_CHECK_VERIFICATION_BY_CLINICAL_SPECIALIST = 'stage-to-modeling-from-check-verification-by-clinical-specialist';
    case STAGE_TO_MODELING_FROM_CHECK_VERIFICATION_BY_CLINICAL_DIRECTOR = 'stage-to-modeling-from-check-verification-by-clinical-director';
    case STAGE_TO_MODELING_FROM_VERIFICATION_BY_DOCTOR = 'stage-to-modeling-from-check-verification-by-doctor';
    case STAGE_TO_CHECK_VERIFICATION_BY_CLINICAL_SPECIALIST = 'stage-to-check-verification-by-clinical-specialist';
    case STAGE_TO_CHECK_VERIFICATION_BY_CLINICAL_DIRECTOR = 'stage-to-check-verification-by-clinical-director';
    case STAGE_TO_CHECK_VERIFICATION_BY_DOCTOR = 'stage-to-check-verification-by-doctor';
    case STAGE_TO_PAYMENT_BILL_AFTER_REJECTION = 'stage-to-payment-bill-after-rejection';
    case STAGE_TO_PAYMENT_BILL = 'stage-to-payment-bill';
    case STAGE_TO_PAYMENT_AWAITING = 'stage-to-payment-awaiting';
    case STAGE_TO_PAYMENT_AWAITING_FROM_PAYMENT_BILL_AFTER_REJECTION = 'stage-to-payment-awaiting-from-payment-bill-after-rejection';
    case STAGE_TO_PRODUCTION_OPTIONS = 'stage-to-production-options';
    case STAGE_TO_PRODUCTION_OPTIONS_FROM_TREATMENT = 'stage-to-production-options-from-treatment';
    case STAGE_TO_PRODUCTION_PREPARATION = 'stage-to-production-preparation';
    case STAGE_TO_PRODUCTION_RELEASE = 'stage-to-production-release';
    case STAGE_TO_PRODUCTION_PACKAGING = 'stage-to-production-packaging';
    case STAGE_TO_PRODUCTION_CONTROL = 'stage-to-production-control';
    case STAGE_TO_DELIVERY_PREPARATION = 'stage-to-delivery-preparation';
    case STAGE_TO_DELIVERY = 'stage-to-delivery';
    case STAGE_TO_DELIVERED = 'stage-to-delivered';
    case STAGE_TO_TREATMENT = 'stage-to-treatment';
    case STAGE_TO_COMPLETED = 'stage-to-completed';
}
