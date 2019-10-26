<?php

namespace CMS\Package\Repositories\Contact;

use CMS\Package\Repositories\BaseRepository;

class ContactRepository extends BaseRepository
{
    public function model()
    { }

    public function paginateContact($limit = null)
    {
        return $this->_model->orderBy('status', 'DESC')->paginate($limit);
    }
}
