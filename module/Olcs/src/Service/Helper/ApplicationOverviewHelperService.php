<?php

/**
 * Application Overview Helper Service
 */
namespace Olcs\Service\Helper;

use Common\Service\Helper\AbstractHelperService;
use Common\RefData;

/**
 * Application Overview Helper Service
 */
class ApplicationOverviewHelperService extends AbstractHelperService
{
    /**
     * @param array $application application overview data
     * @param array $licence licence overview data
     * @param string $lva 'application'|'variation' used for URL generation
     * @return array view data
     */
    public function getViewData($application, $lva)
    {
        $licenceOverviewHelper = $this->getServiceLocator()->get('Helper\LicenceOverview');

        $licence = $application['licence'];

        $isPsv = $application['goodsOrPsv']['id'] == RefData::LICENCE_CATEGORY_PSV;
        $isSpecialRestricted = $application['licenceType']['id'] == RefData::LICENCE_TYPE_SPECIAL_RESTRICTED;

        $viewData = [
            'operatorName'              => $licence['organisation']['name'],
            'operatorId'                => $licence['organisation']['id'], // used for URL generation
            'numberOfLicences'          => count($licence['organisation']['licences']),
            'tradingName'               => $licence['tradingName'],
            'currentApplications'       => $licenceOverviewHelper->getCurrentApplications($licence),
            'applicationCreated'        => $application['createdOn'],
            'oppositionCount'           => $application['oppositionCount'],
            'licenceStatus'             => $licence['status']['id'],
            'interimStatus'             => $isPsv ? null :$this->getInterimStatus($application, $lva),
            'outstandingFees'           => $application['feeCount'],
            'licenceStartDate'          => $licence['inForceDate'],
            'licenceGracePeriods'       => $licenceOverviewHelper->getLicenceGracePeriods($licence),
            'continuationDate'          => $licence['expiryDate'],
            'numberOfVehicles'          => $isSpecialRestricted ? null : count($licence['licenceVehicles']),
            'totalVehicleAuthorisation' => $this->getTotalVehicleAuthorisation($application, $licence),
            'numberOfOperatingCentres'  => $isSpecialRestricted ? null : count($licence['operatingCentres']),
            'totalTrailerAuthorisation' => $this->getTotalTrailerAuthorisation($application, $licence),
            'numberOfIssuedDiscs'       => $isPsv && !$isSpecialRestricted ? count($licence['psvDiscs']) : null,
            'numberOfCommunityLicences' => $licenceOverviewHelper->getNumberOfCommunityLicences($licence),
            'openCases'                 => $licenceOverviewHelper->getOpenCases($licence),

            'changeOfEntity'            => (
                (boolean)$application['isVariation'] ?
                null :
                $this->getChangeOfEntity($application, $licence)
            ),

            'receivesMailElectronically' => (
                isset($application['organisation']) ?
                $application['organisation']['allowEmail'] :
                $licence['organisation']['allowEmail']
            ),

            'currentReviewComplaints'   => null, // pending OLCS-7581
            'previousOperatorName'      => null, // pending OLCS-8383
            'previousLicenceNumber'     => null, // pending OLCS-8383

            // out of scope for OLCS-6831
            'outOfOpposition'            => null,
            'outOfRepresentation'        => null,
            'registeredForSelfService'   => null,
        ];

        return $viewData;
    }

    /**
     * @param array $application application data
     * @param string $lva 'application'|'variation' used for URL generation
     * @return string
     */
    public function getInterimStatus($application, $lva)
    {
        $url = $this->getServiceLocator()->get('Helper\Url')
            ->fromRoute('lva-'.$lva.'/interim', [], [], true);

        if (
            isset($application['interimStatus']['id'])
            && !empty($application['interimStatus']['id'])
        ) {
            $interimStatus = sprintf(
                '%s (<a href="%s">Interim details</a>)',
                $application['interimStatus']['description'],
                $url
            );
        } else {
            $interimStatus = sprintf('None (<a href="%s">add interim</a>)', $url);
        }

        return $interimStatus;
    }


    /**
     * The the change of entity status.
     *
     * @param array $application application data
     *
     * @return string A string representing the change of entity status.
     */
    public function getChangeOfEntity($application)
    {
        $args = array(
            'application' => $application['id'],
        );

        $changeOfEntity = $application['licence']['changeOfEntitys'];

        if (!empty($changeOfEntity)) {
            $text = array(
                'Yes', 'update details'
            );

            $args['changeId'] = $changeOfEntity[0]['id'];
        } else {
            $text = array(
                'No', 'add details'
            );
        }

        $url = $this->getServiceLocator()->get('Helper\Url')
            ->fromRoute('lva-application/change-of-entity', $args);
        $value = sprintf('%s (<a class="js-modal-ajax" href="' . $url . '">%s</a>)', $text[0], $text[1]);

        return $value;
    }

    /**
     * @param array $application application overview data
     * @param array $licence licence overview data
     * @return string
     */
    public function getTotalVehicleAuthorisation($application, $licence)
    {
        if ($application['licenceType']['id'] == RefData::LICENCE_TYPE_SPECIAL_RESTRICTED) {
            return null;
        }

        $str = (string) (int) $licence['totAuthVehicles'];

        if ($application['totAuthVehicles'] != $licence['totAuthVehicles']) {
            $str .= ' (' . (string) (int) $application['totAuthVehicles'] . ')';
        }

        return $str;
    }

    /**
     * @param array $application application overview data
     * @param array $licence licence overview data
     * @return string
     */
    public function getTotalTrailerAuthorisation($application, $licence)
    {
        if ($application['goodsOrPsv']['id'] == RefData::LICENCE_CATEGORY_PSV) {
            return null;
        }

        $str = (string) (int) $licence['totAuthTrailers'];

        if ($application['totAuthTrailers'] != $licence['totAuthTrailers']) {
            $str .= ' (' . (string) (int) $application['totAuthTrailers'] . ')';
        }

        return $str;
    }
}
