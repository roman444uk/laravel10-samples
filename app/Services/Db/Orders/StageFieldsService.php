<?php

namespace App\Services\Db\Orders;

class StageFieldsService extends StageFieldsBaseService
{
    protected array $_props = [
        'constriction',
        'crowding',
        'cross_ratio',
        'canine_ratio_engel',
        'incisors_ratio_vertical',
        'molar_ratio_engel',
        'central_line_offset',
        'occlusal_line_inclination',
        'periodontal_status',
        'peculiarities',
        'treat_dental_arches',
        'central_line',
        'eliminate_crowding_by',
        'cross_ratio_lateral',
        'widening',
        'separation',
        'micro_implant',
        'teeth_eight',
        'sagittal_plane_correction',
        'replacement_installation',
        'discrepancy_incisors_canines',
        'anterior_teeth_alignment',
        'sagittal_incisor_ratio',
        'occlusal_plane_correction',
        'aligners_trimming',
        'virtual_elastic_chain',
        'sequence_tooth_movement',

        'current_aligner',
        'correction_reason',
    ];

    /**
     * 1. Constriction
     */
    protected function fillConstriction(&$stageData, $requestData): void
    {
        $stageData['constriction'] = [
            'top' => $this->getBool($requestData, 'constriction.top'),
            'bottom' => $this->getBool($requestData, 'constriction.bottom'),
        ];
    }

    /**
     * 2. Crowding
     */
    protected function fillCrowding(&$stageData, $requestData): void
    {
        $stageData['crowding'] = [
            'top' => $this->getBool($requestData, 'crowding.top'),
            'bottom' => $this->getBool($requestData, 'crowding.bottom'),
        ];
    }

    /**
     * 3. Cross ratio
     */
    protected function fillCrossRatio(&$stageData, $requestData): void
    {
        $stageData['cross_ratio'] = [
            'value' => $this->getBool($requestData, 'cross_ratio.value'),
            'types' => $this->getArrayList($requestData, 'cross_ratio.types'),
        ];
    }

    /**
     * 4. Canine ratio engel
     */
    protected function fillCanineRatioEngel(&$stageData, $requestData): void
    {
        $stageData['canine_ratio_engel'] = [
            'right' => $this->getString($requestData, 'canine_ratio_engel.right'),
            'left' => $this->getString($requestData, 'canine_ratio_engel.left'),
        ];
    }

    /**
     * 5. Molar ratio engel
     */
    protected function fillMolarRatioEngel(&$stageData, $requestData): void
    {
        $stageData['molar_ratio_engel'] = [
            'right' => $this->getString($requestData, 'molar_ratio_engel.right'),
            'left' => $this->getString($requestData, 'molar_ratio_engel.left'),
        ];
    }

    /**
     * 6. Incisors ratio vertical
     */
    protected function fillIncisorsRatioVertical(&$stageData, $requestData): void
    {
        $stageData['incisors_ratio_vertical'] = [
            'value' => $this->getString($requestData, 'incisors_ratio_vertical.value'),
        ];
    }

    /**
     * 7. Molar ratio engel
     */
    protected function fillCentralLineOffset(&$stageData, $requestData): void
    {
        $stageData['central_line_offset'] = [
            'value' => $this->getBool($requestData, 'central_line_offset.value'),
            'jaw' => $this->getArrayList($requestData, 'central_line_offset.jaw'),
        ];
    }

    /**
     * 8. Molar ratio engel
     */
    protected function fillOcclusalLineInclination(&$stageData, $requestData): void
    {
        $stageData['occlusal_line_inclination'] = [
            'value' => $this->getBool($requestData, 'occlusal_line_inclination.value'),
        ];
    }

    /**
     * 9. Molar ratio engel
     */
    protected function fillPeriodontalStatus(&$stageData, $requestData): void
    {
        $stageData['periodontal_status'] = [
            'value' => $this->getBool($requestData, 'periodontal_status.value'),
        ];
    }

    /**
     * 10. Molar ratio engel
     */
    protected function fillPeculiarities(&$stageData, $requestData): void
    {
        foreach ([1, 2, 3, 4] as $sideNumber) {
            foreach ([1, 2, 3, 4, 5, 6, 7, 8] as $teethNumber) {
                $stageData['peculiarities'][$sideNumber][$teethNumber] = $this->getString($requestData, 'peculiarities.' . $sideNumber . '.' . $teethNumber);
            }
        }
    }

    /**
     * 11. Treat dental arches
     */
    protected function fillTreatDentalArches(&$stageData, $requestData): void
    {
        $stageData['treat_dental_arches'] = [
            'value' => $this->getString($requestData, 'treat_dental_arches.value')
        ];
    }

    /**
     * 12. Central line
     */
    protected function fillCentralLine(&$stageData, $requestData): void
    {
        $stageData['central_line'] = [
            'top' => $this->getString($requestData, 'central_line.top'),
            'bottom' => $this->getString($requestData, 'central_line.bottom'),
            'due_to' => $this->getArrayList($requestData, 'central_line.due_to'),
        ];
    }

    /**
     * 13. Eliminate crowding by
     */
    protected function fillEliminateCrowdingBy(&$stageData, $requestData): void
    {
        $stageData['eliminate_crowding_by'] = [
            'value' => $this->getString($requestData, 'eliminate_crowding_by.value')
        ];
    }

    /**
     * 14. Cross ratio lateral
     */
    protected function fillCrossRatioLateral(&$stageData, $requestData): void
    {
        $stageData['cross_ratio_lateral'] = [
            'value' => $this->getString($requestData, 'cross_ratio_lateral.value'),
            'adjust' => $this->getArrayList($requestData, 'cross_ratio_lateral.adjust'),
        ];
    }

    /**
     * 15. Cross ratio lateral
     */
    protected function fillWidening(&$stageData, $requestData): void
    {
        $stageData['widening'] = [
            'value' => $this->getString($requestData, 'widening.value'),
            'due_to' => [
                'top' => $this->getArrayList($requestData, 'widening.due_to.top'),
                'bottom' => $this->getArrayList($requestData, 'widening.due_to.bottom'),
            ],
            'volume' => $this->getString($requestData, 'widening.volume'),
        ];
    }

    /**
     * 16. Separation
     */
    protected function fillSeparation(&$stageData, $requestData): void
    {
        $stageData['separation'] = [
            'value' => $this->getArrayList($requestData, 'separation.value'),
        ];
    }

    /**
     * 17. Micro implant
     */
    protected function fillMicroImplant(&$stageData, $requestData): void
    {
        $stageData['micro_implant'] = [
            'value' => $this->getString($requestData, 'micro_implant.value'),
            'areas' => $this->getArrayList($requestData, 'micro_implant.areas'),
        ];
    }

    /**
     * 18. Teeth eight
     */
    protected function fillTeethEight(&$stageData, $requestData): void
    {
        $stageData['teeth_eight'] = [
            1 => [
                8 => $this->getString($requestData, 'teeth_eight.1.8'),
            ],
            2 => [
                8 => $this->getString($requestData, 'teeth_eight.2.8'),
            ],
            3 => [
                8 => $this->getString($requestData, 'teeth_eight.3.8'),
            ],
            4 => [
                8 => $this->getString($requestData, 'teeth_eight.4.8'),
            ],
        ];
    }

    /**
     * 19. Teeth eight
     */
    protected function fillSagittalPlaneCorrection(&$stageData, $requestData): void
    {
        $sagittalPlaneCorrection = $requestData['sagittal_plane_correction'] ?? [];

        $stageData['sagittal_plane_correction'] = [
            'value' => $this->getString($requestData, 'sagittal_plane_correction.value'),
            'byte_jump_offset' => $this->getArrayList($requestData, 'sagittal_plane_correction.byte_jump_offset'),
            'distalization' => [
                'sequential' => [
                    1 => $this->getBool($sagittalPlaneCorrection, 'distalization.sequential.1'),
                    2 => $this->getBool($sagittalPlaneCorrection, 'distalization.sequential.2'),
                    3 => $this->getBool($sagittalPlaneCorrection, 'distalization.sequential.3'),
                    4 => $this->getBool($sagittalPlaneCorrection, 'distalization.sequential.4'),
                ],
                'mixed_sequential' => [
                    1 => $this->getBool($sagittalPlaneCorrection, 'distalization.mixed_sequential.1'),
                    2 => $this->getBool($sagittalPlaneCorrection, 'distalization.mixed_sequential.2'),
                    3 => $this->getBool($sagittalPlaneCorrection, 'distalization.mixed_sequential.3'),
                    4 => $this->getBool($sagittalPlaneCorrection, 'distalization.mixed_sequential.4'),
                ],
                'group_vergare' => [
                    1 => $this->getBool($sagittalPlaneCorrection, 'distalization.group_vergare.1'),
                    2 => $this->getBool($sagittalPlaneCorrection, 'distalization.group_vergare.2'),
                    3 => $this->getBool($sagittalPlaneCorrection, 'distalization.group_vergare.3'),
                    4 => $this->getBool($sagittalPlaneCorrection, 'distalization.group_vergare.4'),
                    'type' => $this->getString($sagittalPlaneCorrection, 'distalization.group_vergare.type'),
                ],
            ],
            'mesialization' => [
                'sequential' => [
                    1 => $this->getBool($sagittalPlaneCorrection, 'mesialization.sequential.1'),
                    2 => $this->getBool($sagittalPlaneCorrection, 'mesialization.sequential.2'),
                    3 => $this->getBool($sagittalPlaneCorrection, 'mesialization.sequential.3'),
                    4 => $this->getBool($sagittalPlaneCorrection, 'mesialization.sequential.4'),
                ],
                'closing_gaps' => [
                    1 => $this->getBool($sagittalPlaneCorrection, 'mesialization.closing_gaps.1'),
                    2 => $this->getBool($sagittalPlaneCorrection, 'mesialization.closing_gaps.2'),
                    3 => $this->getBool($sagittalPlaneCorrection, 'mesialization.closing_gaps.3'),
                    4 => $this->getBool($sagittalPlaneCorrection, 'mesialization.closing_gaps.4'),
                ],
                'group_in_sectors' => [
                    1 => $this->getBool($sagittalPlaneCorrection, 'mesialization.group_in_sectors.1'),
                    2 => $this->getBool($sagittalPlaneCorrection, 'mesialization.group_in_sectors.2'),
                    3 => $this->getBool($sagittalPlaneCorrection, 'mesialization.group_in_sectors.3'),
                    4 => $this->getBool($sagittalPlaneCorrection, 'mesialization.group_in_sectors.4'),
                ],
            ],
        ];
    }

    /**
     * 20. Replacement and installation
     */
    protected function fillReplacementInstallation(&$stageData, $requestData): void
    {
        foreach ([
                     'planning_removal', 'removal_according_laboratory_recommendation', 'do_not_move_tooth',
                     'do_not_install_attachments', 'filling', 'implant', 'veneer_crown'
                 ] as $type
        ) {
            foreach ([1, 2, 3, 4] as $side) {
                foreach ([1, 2, 3, 4, 5, 6, 7, 8] as $tooth) {
                    $stageData['replacement_installation'][$type]['schema'][$side][$tooth] = $this->getBool(
                        $requestData, implode('.', ['replacement_installation', $type, 'schema', $side, $tooth]), false
                    );
                }
            }
        }
    }

    /**
     * 21. Discrepancy incisors canines
     */
    protected function fillDiscrepancyIncisorsCanines(&$stageData, $requestData): void
    {
        $stageData['discrepancy_incisors_canines'] = [
            'value' => $this->getString($requestData, 'discrepancy_incisors_canines.value')
        ];
    }

    /**
     * 22. Anterior teeth alignment
     */
    protected function fillAnteriorTeethAlignment(&$stageData, $requestData): void
    {
        $stageData['anterior_teeth_alignment'] = [
            'top' => [
                'value' => $this->getString($requestData, 'anterior_teeth_alignment.top.value'),
                'along_cutting_edge' => $this->getString($requestData, 'anterior_teeth_alignment.top.along_cutting_edge'),
                'take_as_standard' => $this->getString($requestData, 'anterior_teeth_alignment.top.take_as_standard'),
            ],
            'bottom' => [
                'value' => $this->getString($requestData, 'anterior_teeth_alignment.bottom.value'),
                'take_as_standard' => $this->getString($requestData, 'anterior_teeth_alignment.bottom.take_as_standard'),
            ],
        ];
    }

    /**
     * 23. Sagittal incisor ratio
     */
    protected function fillSagittalIncisorRatio(&$stageData, $requestData): void
    {
        $stageData['sagittal_incisor_ratio'] = [
            'top' => [
                'value' => $this->getString($requestData, 'sagittal_incisor_ratio.top.value'),
                'change' => $this->getArrayList($requestData, 'sagittal_incisor_ratio.top.change'),
            ],
            'bottom' => [
                'value' => $this->getString($requestData, 'sagittal_incisor_ratio.bottom.value'),
                'change' => $this->getArrayList($requestData, 'sagittal_incisor_ratio.bottom.change'),
            ],
        ];
    }

    /**
     * 24. Occlusal plane correction
     */
    protected function fillOcclusalPlaneCorrection(&$stageData, $requestData): void
    {
        $typeRequest = $this->getString($requestData, 'occlusal_plane_correction.type');

        $stageData['occlusal_plane_correction'] = [
            'value' => $this->getString($requestData, 'occlusal_plane_correction.value'),
            'type' => $typeRequest,
        ];

        foreach (['intrusion', 'extrusion'] as $type) {
//            if ($typeRequest === $type) {
                foreach ([1, 2, 3, 4] as $side) {
                    foreach ([1, 2, 3, 4, 5, 6, 7, 8] as $teeth) {
                        $stageData['occlusal_plane_correction'][$type]['schema'][$side][$teeth] = $this->getBool(
                            $requestData, implode('.', ['occlusal_plane_correction', $type, 'schema', $side, $teeth])
                        );
                    }
                }
//            }
        }
    }

    /**
     * 25. Aligners trimming
     */
    protected function fillAlignersTrimming(&$stageData, $requestData): void
    {
        $stageData['aligners_trimming'] = [
            'value' => $this->getString($requestData, 'aligners_trimming.value'),
            'low_trimming_zone' => $this->getString($requestData, 'aligners_trimming.low_trimming_zone'),
        ];
    }

    /**
     * 26. Virtual elastic chain
     */
    protected function fillVirtualElasticChain(&$stageData, $requestData): void
    {
        $stageData['virtual_elastic_chain'] = [
            'value' => $this->getString($requestData, 'virtual_elastic_chain.value'),
        ];
    }

    /**
     * 27. Aligners trimming
     */
    protected function fillSequenceToothMovement(&$stageData, $requestData): void
    {
        $stageData['sequence_tooth_movement'] = [
            'value' => $this->getString($requestData, 'sequence_tooth_movement.value'),
            'description' => $this->getString($requestData, 'sequence_tooth_movement.description'),
        ];
    }

    /**
     * Current aligners
     */
    protected function fillCurrentAligner(&$stageData, $requestData): void
    {
        $stageData['current_aligner'] = [
            'top' => $this->getString($requestData, 'current_aligner.top'),
            'bottom' => $this->getString($requestData, 'current_aligner.bottom'),
        ];
    }

    /**
     * Correction reason
     */
    protected function fillCorrectionReason(&$stageData, $requestData): void
    {
        $stageData['correction_reason'] = [
            'value' => $this->getArrayList($requestData, 'correction_reason.value'),
            'description' => $this->getString($requestData, 'correction_reason.description'),
        ];
    }
}
