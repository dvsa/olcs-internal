<?php

namespace Olcs\Service\Permits\Bilateral;

use Zend\Form\Factory as FormFactory;
use Zend\Form\Fieldset;
use RuntimeException;

/**
 * Period fieldset generator
 */
class PeriodFieldsetGenerator
{
    /** @var FormFactory */
    protected $formFactory;

    /** @var array */
    protected $fieldsetPopulators = [];

    /**
     * Create service instance
     *
     * @param FormFactory $formFactory
     *
     * @return PeriodFieldsetGenerator
     */
    public function __construct(FormFactory $formFactory)
    {
        $this->formFactory = $formFactory;
    }

    /**
     * Return a Fieldset element corresponding to the provided data
     *
     * @param array $period
     * @param string $type
     *
     * @return Fieldset
     */
    public function generate(array $period, $type)
    {
        if (!isset($this->fieldsetPopulators[$type])) {
            throw new RuntimeException('Fieldset populator not found for type ' . $type);
        }

        $periodName = 'period' . $period['id'];

        $periodFieldset = $this->formFactory->create(
            [
                'type' => Fieldset::class,
                'name' => $periodName,
                'attributes' => [
                    'id' => $periodName,
                    'data-role' => 'period',
                ]
            ]
        );

        $fieldsetPopulator = $this->fieldsetPopulators[$type];
        $fieldsetPopulator->populate($periodFieldset, $period['fields']);

        return $periodFieldset;
    }

    /**
     * Associate an implementation of FieldsetPopulatorInterface with a type attribute as returned by the backend
     *
     * @param string $type
     * @param FieldsetPopulatorInterface $fieldsetPopulator
     */
    public function registerFieldsetPopulator($type, FieldsetPopulatorInterface $fieldsetPopulator)
    {
        $this->fieldsetPopulators[$type] = $fieldsetPopulator;
    }
}
