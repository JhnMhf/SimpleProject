<?php

namespace UR\DB\OldBundle\Entity;

/**
 * IndexBerufe
 */
class IndexBerufe
{
    /**
     * @var integer
     */
    private $id = '0';

    /**
     * @var string
     */
    private $beruf;


    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set beruf
     *
     * @param string $beruf
     *
     * @return IndexBerufe
     */
    public function setBeruf($beruf)
    {
        $this->beruf = $beruf;

        return $this;
    }

    /**
     * Get beruf
     *
     * @return string
     */
    public function getBeruf()
    {
        return $this->beruf;
    }
}

