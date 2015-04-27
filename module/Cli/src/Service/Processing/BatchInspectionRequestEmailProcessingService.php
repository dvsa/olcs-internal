<?php

/**
 * Batch process inspection request emails
 *
 * @author Dan Eggleston <dan@stolenegg.com>
 */
namespace Cli\Service\Processing;

use Common\Service\Entity\LicenceStatusRuleEntityService;
use Common\Service\Entity\LicenceEntityService;
use Common\Util\RestCallTrait;
use Zend\Log\Logger;

/**
 * Batch process inspection request emails
 *
 * @author Dan Eggleston <dan@stolenegg.com>
 */
class BatchInspectionRequestEmailProcessingService extends AbstractBatchProcessingService
{
    use RestCallTrait;

    const SUBJECT_REGEX = '/\[ Maintenance Inspection \] REQUEST=([\d]+),STATUS=([SU]?)$/';

    /**
     * Process emails
     *
     * @return void
     */
    public function process()
    {
        // get list of pending emails
        $emails = $this->getEmailList();

        if (empty($emails)) {
            $this->outputLine('No emails found - nothing to do!');
            return;
        }

        $this->outputLine(sprintf('Found %d email(s) to process', count($emails)));

        // loop through emails and process
        foreach ($emails as $uniqueId) {

            $this->outputLine('=Processing email id '.$uniqueId);

            $email = $this->getEmail($uniqueId);
            if (!isset($email['subject'])) {
                $this->log('==Could not retrieve email '.$uniqueId, Logger::WARN);
                continue;
            }

            // parse subject line
            list($requestId, $status) = $this->parseSubject($email['subject']);

            if (!$requestId || !$status) {
                // log warn and continue if invalid subject
                $this->log('==Unable to parse email subject line: '.$email['subject'], Logger::WARN);
                continue;
            }

            // process valid status update
            $result = $this->processStatusUpdate($requestId, $status);

            // delete email
            if ($result === true) {
                $this->deleteEmail($uniqueId);
            } else {
                $this->outputLine('==Failed to process email id '.$uniqueId);
            }
        }

        $this->outputLine(sprintf('Done'));
    }

    /**
     * Get list of emails
     *
     * @return array
     */
    protected function getEmailList()
    {
        $this->outputLine('Checking mailbox...');
        return $this->sendGet('email\inspection-request');
    }

    /**
     * Make REST call to retrieve individual email
     *
     * @param string $id
     * @return @array
     */
    protected function getEmail($id)
    {
        $this->outputLine('==Retrieving email '.$id);
        return $this->sendGet('email\inspection-request', ['id' => $id]);
    }

    /**
     * Make REST call to delete an individual email
     *
     * @param string $id
     */
    protected function deleteEmail($id)
    {
        $this->outputLine('==Deleting email '.$id);
        return $this->makeRestCall('email\inspection-request', 'DELETE', ['id' => $id]);
    }

    /**
     * Parse request id and status from a subject line
     *
     * @param type $subject
     * @return array|null
     */
    protected function parseSubject($subject)
    {
        $matches = null;
        preg_match(self::SUBJECT_REGEX, $subject, $matches);
        if (!$matches) {
            return null;
        }
        return [$matches[1], $matches[2]];
    }

    /**
     * Process an inspection request update
     *
     * @param int $requestId
     * @param string $status 'S'|'U'
     * @return boolean success
     */
    protected function processStatusUpdate($requestId, $status)
    {
        $this->outputLine(
            sprintf('==Handling status of \'%s\' for inspection request %s', $status, $requestId)
        );

        $result = $this->getServiceLocator()->get('BusinessServiceManager')
            ->get('InspectionRequestUpdate')
            ->process(
                [
                    'id' => $requestId,
                    'status' => $status,
                ]
            );

        return $result->isOk();
    }
}
