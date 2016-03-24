<?php

namespace Olcs\Controller\Traits;

/**
 * Class ListDataTrait
 * @package Olcs\Controller
 */
trait ListDataTrait
{
    /**
     * Get a list of sub category description
     *
     * @param int $subCategoryId        Sub category to filter by
     * @param bool|string $firstOption
     *
     * @return array
     */
    public function getListDataSubCategoryDescription($subCategoryId = null, $firstOption = false)
    {
        $dto = \Dvsa\Olcs\Transfer\Query\SubCategoryDescription\GetList::create(
            [
                'order' => 'ASC',
                'sort' => 'description',
                'subCategory' => $subCategoryId,
            ]
        );

        return $this->getListDataOptions($dto, 'id', 'description', $firstOption);
    }

    /**
     * Get a list of doc templates
     *
     * @param int $categoryId           Category to filter by
     * @param int $subCategoryId        Sub category to filter by
     * @param bool|string $firstOption
     *
     * @return array
     */
    public function getListDataDocTemplates($categoryId = null, $subCategoryId = null, $firstOption = false)
    {
        $dto = \Dvsa\Olcs\Transfer\Query\DocTemplate\GetList::create(
            [
                'order' => 'ASC',
                'sort' => 'description',
                'category' => $categoryId,
                'subCategory' => $subCategoryId,
            ]
        );

        return $this->getListDataOptions($dto, 'id', 'description', $firstOption);
    }

    /**
     * Get a list of Task categories
     *
     * @param bool|string $firstOption
     *
     * @return array
     */
    public function getListDataCategoryTasks($firstOption = false)
    {
        $dto = \Dvsa\Olcs\Transfer\Query\Category\GetList::create(
            [
                'order' => 'ASC',
                'sort' => 'description',
                'isTaskCategory' => 'Y',
            ]
        );

        return $this->getListDataOptions($dto, 'id', 'description', $firstOption);
    }

    /**
     * Get a list of Doc categories
     *
     * @param bool|string $firstOption
     *
     * @return array
     */
    public function getListDataCategoryDocs($firstOption = false)
    {
        $dto = \Dvsa\Olcs\Transfer\Query\Category\GetList::create(
            [
                'order' => 'ASC',
                'sort' => 'description',
                'isDocCategory' => 'Y',
            ]
        );

        return $this->getListDataOptions($dto, 'id', 'description', $firstOption);
    }

    /**
     * Get a list of Scan categories
     *
     * @param bool|string $firstOption
     *
     * @return array
     */
    public function getListDataCategoryScan($firstOption = false)
    {
        $dto = \Dvsa\Olcs\Transfer\Query\Category\GetList::create(
            [
                'order' => 'ASC',
                'sort' => 'description',
                'isScanCategory' => 'Y',
            ]
        );

        return $this->getListDataOptions($dto, 'id', 'description', $firstOption);
    }

    /**
     * Get a list of Task sub categories
     *
     * @param int $categoryId          Category to filter by
     * @param bool|string $firstOption
     *
     * @return array
     */
    public function getListDataSubCategoryTask($categoryId = null, $firstOption = false)
    {
        return $this->getListDataSubCategory(['isTaskCategory' => 'Y'], $categoryId, $firstOption);
    }

    /**
     * Get a list of Doc sub categories
     *
     * @param int $categoryId          Category to filter by
     * @param bool|string $firstOption
     *
     * @return array
     */
    public function getListDataSubCategoryDocs($categoryId = null, $firstOption = false)
    {
        return $this->getListDataSubCategory(['isDocCategory' => 'Y'], $categoryId, $firstOption);
    }

    /**
     * Get a list of Scan sub categories
     *
     * @param int $categoryId          Category to filter by
     * @param bool|string $firstOption
     *
     * @return array
     */
    public function getListDataSubCategoryScan($categoryId = null, $firstOption = false)
    {
        return $this->getListDataSubCategory(['isScanCategory' => 'Y'], $categoryId, $firstOption);
    }

    /**
     * Get a list of sub categorys
     *
     * @param array $params            Params to pass to the dto
     * @param int $categoryId          Category to filter by
     * @param string|bool $firstOption @see getListDataOptions
     *
     * @return array
     */
    private function getListDataSubCategory(array $params, $categoryId = null, $firstOption = false)
    {
        $defaultParams = [
            'order' => 'ASC',
            'sort' => 'subCategoryName',
        ];
        $dtoParams = array_merge($defaultParams, $params);

        if ((int) $categoryId !== 0) {
            $dtoParams['category'] = (int) $categoryId;
        }

        $dto = \Dvsa\Olcs\Transfer\Query\SubCategory\GetList::create($dtoParams);

        return $this->getListDataOptions($dto, 'id', 'subCategoryName', $firstOption);
    }

    /**
     * Get a list of teams
     *
     * @param string $firstOption @see getListDataOptions
     *
     * @return array
     */
    public function getListDataTeam($firstOption = false)
    {
        $dto = \Dvsa\Olcs\Transfer\Query\Team\TeamList::create(
            [
                'order' => 'ASC',
                'sort' => 'name',
                'page' => 1,        // we are not paginating these lists so always on page 1
                'limit' => 100      // we are not paginating these lists so set to a large(ish) amount
            ]
        );

        return $this->getListDataOptions($dto, 'id', 'name', $firstOption);
    }

    /**
     * Get a list of users
     *
     * @param int $teamId Option team to filter by
     * @param string $firstOption @see getListDataOptions
     *
     * @return array
     */
    public function getListDataUser($teamId = null, $firstOption = false)
    {
        $params =             [
            'order' => 'ASC',
            'sort' => 'loginId',
        ];
        if ((int) $teamId !== 0) {
            $params['team'] = $teamId;
        } else {
            $params['isInternal'] = true;
        }

        $dto = \Dvsa\Olcs\Transfer\Query\User\UserList::create($params);

        return $this->getListDataOptions($dto, 'id', 'loginId', $firstOption);
    }

    /**
     * Take a DTO and create an array of items suitable for a select element
     *
     * @param \Dvsa\Olcs\Transfer\Query\AbstractQuery $dto
     * @param string $keyName   The key from the dto response to use as the select options id
     * @param string $valueName The key from the dto response to use as the select options text
     * @param bool|string $firstOption false = disable first option, or a string of the text for the first option
     *
     * @return array
     */
    private function getListDataOptions($dto, $keyName, $valueName, $firstOption = false)
    {
        $response = $this->handleQuery($dto);
        if (!$response->isOK()) {
            // something went wrong, assume its a temporary error, as these list lookups should never fail
            return [];
        }

        $options = [];
        // Do we need to add a default first option
        if (is_string($firstOption)) {
            $options[''] = $firstOption;
        }

        // iterate through to create an array of options
        foreach ($response->getResult()['results'] as $item) {
            $key = $item[$keyName];
            $value = $item[$valueName];
            $options[$key] = $value;
        }

        return $options;
    }
}
