<?php

/*
 * DeskPRO (r) has been developed by DeskPRO Ltd. https://www.deskpro.com/
 * a British company located in London, England.
 *
 * All source code and content Copyright (c) 2017, DeskPRO Ltd.
 *
 * The license agreement under which this software is released
 * can be found at https://www.deskpro.com/eula/
 *
 * By using this software, you acknowledge having read the license
 * and agree to be bound thereby.
 *
 * Please note that DeskPRO is not free software. We release the full
 * source code for our software because we trust our users to pay us for
 * the huge investment in time and energy that has gone into both creating
 * this software and supporting our customers. By providing the source code
 * we preserve our customers' ability to modify, audit and learn from our
 * work. We have been developing DeskPRO since 2001, please help us make it
 * another decade.
 *
 * Like the work you see? Think you could make it better? We are always
 * looking for great developers to join us: http://www.deskpro.com/jobs/
 *
 * ~ Thanks, Everyone at Team DeskPRO
 */

namespace DeskPROClient\Api\Resource\Content;

use DeskPROClient\Api\Request\CountRequest;
use DeskPROClient\Api\Request\CreateRequest;
use DeskPROClient\Api\Request\DeleteRequest;
use DeskPROClient\Api\Request\FindRequest;
use DeskPROClient\Api\Request\GetRequest;
use DeskPROClient\Api\Request\UpdateRequest;
use DeskPROClient\Api\Resource\AbstractResource;

/**
 * Class Articles.
 */
class ArticlesResource extends AbstractResource
{
    /**
     * {@inheritdoc}
     */
    public function getEndpoint()
    {
        return 'articles';
    }

    /**
     * @see https://support.deskpro.com/api/v2/doc#get--api-v2-articles-counts
     *
     * @param string $groupBy
     *
     * @return CountRequest
     */
    public function count($groupBy)
    {
        return new CountRequest($this->context, $this->getEndpoint(), $groupBy);
    }

    /**
     * @see https://support.deskpro.com/api/v2/doc#get--api-v2-articles
     *
     * @param array  $criteria
     * @param string $orderBy
     * @param string $orderDir
     * @param int    $count
     * @param int    $page
     *
     * @return FindRequest
     */
    public function find(array $criteria = [], $orderBy = null, $orderDir = null, $count = null, $page = null)
    {
        return new FindRequest($this->context, $this->getEndpoint(), $criteria, $orderBy, $orderDir, $count, $page);
    }

    /**
     * @see https://support.deskpro.com/api/v2/doc#post--api-v2-articles
     *
     * @param array $data
     *
     * @return CreateRequest
     */
    public function create(array $data)
    {
        return new CreateRequest($this->context, $this->getEndpoint(), $data);
    }

    /**
     * @see https://support.deskpro.com/api/v2/doc#get--api-v2-articles-{id}
     *
     * @param int $id
     *
     * @return GetRequest
     */
    public function get($id)
    {
        return new GetRequest($this->context, $this->getEndpoint(), $id);
    }

    /**
     * @see https://support.deskpro.com/api/v2/doc#put--api-v2-articles-{id}
     *
     * @param int   $id
     * @param array $data
     *
     * @return UpdateRequest
     */
    public function update($id, array $data)
    {
        return new UpdateRequest($this->context, $this->getEndpoint(), $id, $data);
    }

    /**
     * @see https://support.deskpro.com/api/v2/doc#delete--api-v2-articles-{id}
     *
     * @param int $id
     *
     * @return DeleteRequest
     */
    public function delete($id)
    {
        return new DeleteRequest($this->context, $this->getEndpoint(), $id);
    }
}