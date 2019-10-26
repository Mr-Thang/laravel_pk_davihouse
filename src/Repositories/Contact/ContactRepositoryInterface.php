<?php

namespace CMS\Package\Repositories\Contact;

use CMS\Package\Repositories\BaseInterface;

interface ContactRepositoryInterface extends BaseInterface
{
    public function paginateContact($limit = null);
}
